<?php
class setup_controller
{
    public function display(): void
    {
        $periodoDias = 1000;
        $timeframe = "5m";
        $topMoedas = $this->getTop5Moedas();

        echo "<h2>📈 Estratégia aplicada às 5 moedas mais movimentadas</h2>";

        foreach ($topMoedas as $symbol) {
            echo "<h3>🔹 Analisando $symbol...</h3>";
            $precos = $this->getBinanceData($symbol, $timeframe, $periodoDias);
            $bollinger = $this->calcularBandasBollinger($precos);
            $this->estrategiaFechouForaFechouDentro($precos, $bollinger, $symbol);
        }

        $entries = new entries_model();
        $entries->set_filter(["active = 'yes'"]);
        $entries->set_order(["entry_date DESC"]);
        $entries->load_data();
        $entries = $entries->data;

        if (isset($entries[0])) {
            foreach ($entries as $v) {
                echo "<h2>📊 Última Entrada na Moeda " . $v['symbol'] . "</h2>";
                echo "💰 Preço de Compra: " . round($v['entry_price'], 5) . " USDT<br>";
                echo "🎯 Alvo (Gain): " . round($v['target'], 5) . " USDT<br>";
                echo "📅 Data e Hora BR: " . $v['entry_date'] . "<br>";
            }
        } else {
            echo "<h2>📊 Nenhuma entrada encontrada recentemente.</h2>";
        }
    }

    private function getTop5Moedas()
    {
        $url = "https://api.binance.com/api/v3/ticker/24hr";
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        usort($data, fn($a, $b) => $b['quoteVolume'] <=> $a['quoteVolume']);

        $top5 = array_slice(array_column($data, 'symbol'), 0, 40);
        return array_filter(
            $top5,
            fn($symbol) =>
            str_ends_with($symbol, "USDT") &&
                !str_contains($symbol, "USDC") &&
                !str_contains($symbol, "FDUSD")
        );
    }

    private function getBinanceData($symbol, $interval, $limit)
    {
        $url = "https://api.binance.com/api/v3/klines?symbol={$symbol}&interval={$interval}&limit={$limit}";
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        return array_map(fn($candle) => [
            'timestamp' => $candle[0],
            'abertura'  => floatval($candle[1]),
            'preco'     => floatval($candle[4]),
            'high'      => floatval($candle[2]),
            'low'       => floatval($candle[3])
        ], $data);
    }

    private function calcularBandasBollinger($dados, $periodo = 20, $desvio = 2)
    {
        $bollinger = [];
        for ($i = $periodo - 1; $i < count($dados); $i++) {
            $precos = array_slice(array_column($dados, 'preco'), $i - $periodo + 1, $periodo);
            $media = array_sum($precos) / $periodo;
            $desvioPadrao = sqrt(array_sum(array_map(fn($p) => pow($p - $media, 2), $precos)) / $periodo);

            $bollinger[] = [
                'media'          => $media,
                'banda_superior' => $media + ($desvio * $desvioPadrao),
                'banda_inferior' => $media - ($desvio * $desvioPadrao)
            ];
        }
        return $bollinger;
    }

    private function estrategiaFechouForaFechouDentro($dados, $bollinger, $symbol)
    {

        // Certifica que há pelo menos 3 candles
        if (count($dados) < 3 || count($bollinger) < 3) {
            return;
        }

        // Considerando que o array está ordenado do mais antigo (índice 0) para o mais recente
        $ultimo = count($dados) - 1;       // Índice do candle mais recente (Candle 1)
        $candle1 = $dados[$ultimo];        // Candle 1: entrada (mais recente)
        $candle2 = $dados[$ultimo - 1];    // Candle 2: deve ter fechado dentro da banda inferior
        $candle3 = $dados[$ultimo - 2];    // Candle 3: deve ter fechado fora da banda inferior

        // Banda de Bollinger Consolidada para o Candle 3 (Antepenúltimo)
        $bbReferencia3 = $bollinger[$ultimo - 21];

        // Banda de Bollinger Consolidada para o Candle 2 (Penúltimo)
        $bbReferencia2 = $bollinger[$ultimo - 20];

        // Banda de Bollinger ainda sendo calculada para o preço (Preço Atual)
        $bbReferencia1 = $bollinger[$ultimo - 19];

        // Verifica se as chaves necessárias existem para evitar warnings
        if (!isset($candle3['preco'], $candle2['preco'], $bbReferencia3['banda_inferior'], $bbReferencia2['banda_inferior'])) {
            return;
        }

        // Condição: Candle 3 fechou fora da banda inferior e Candle 2 fechou dentro (acima da banda inferior)
        if ($candle3['preco'] < $bbReferencia3['banda_inferior'] && $candle2['preco'] > $bbReferencia2['banda_inferior']) {
            // Entrada será feita com o preço de abertura do Candle 1 (ou, se não existir, seu preço atual)
            $precoEntrada = $candle1['preco'];

            $entries = new entries_model();
            $entries->set_filter([
                "active = 'yes'",
                "symbol = '{$symbol}'",
                "entry_date = '" . date("Y-m-d H:i:s", $candle1['timestamp'] / 1000) . "'"
            ]);
            $entries->load_data();

            if (!isset($entries->data[0])) {
                $entry = new entries_model();
                $entry->populate([
                    'symbol'      => $symbol,
                    'entry_price' => $precoEntrada,
                    'target'      => $bbReferencia1['media'],
                    'entry_date'  => date("Y-m-d H:i:s", $candle1['timestamp'] / 1000)
                ]);
                $entry->save();
            }
        }
    }
}
