<?php
$yes_no_lists = array(
  "yes" => "sim" ,
  "no" => "não"
);

$method_lists = array(
  "GET" => "GET" ,
  "POST" => "POST"
);

$week_name =array(
  "0" => "Domingo"
  , "1" => "Segunda-feira"
  , "2" => "Terça-feira"
  , "3" => "Quarta-feira"
  , "4" => "Quinta-feira"
  , "5" => "Sexta-feira"
  , "6" => "Sábado"  
);

$withoutaccents_lists = array(
  'á'=>'a'
  , 'ä'=>'a'
  , 'à'=>'a'
  , 'ã'=>'a'
  , 'â'=>'a'
  , 'Á'=>'A'
  , 'Ä'=>'A'
  , 'À'=>'A'
  , 'Ã'=>'A'
  , 'Â'=>'A'
  , 'é'=>'e'
  , 'ë'=>'e'
  , 'è'=>'e'
  , 'ê'=>'e'
  , 'É'=>'E'
  , 'Ë'=>'E'
  , 'È'=>'E'
  , 'Ê'=>'E'
  , 'í'=>'i'
  , 'ï'=>'i'
  , 'ì'=>'i'
  , 'î'=>'i'
  , 'Í'=>'I'
  , 'Ï'=>'I'
  , 'Ì'=>'I'
  , 'Î'=>'I'
  , 'ó'=>'o'
  , 'ö'=>'o'
  , 'ò'=>'o'
  , 'õ'=>'o'
  , 'ô'=>'o'
  , 'Ó'=>'O'
  , 'Ö'=>'O'
  , 'Ò'=>'O'
  , 'Õ'=>'O'
  , 'Ô'=>'O'
  , 'ú'=>'u'
  , 'ü'=>'u'
  , 'ù'=>'u'
  , 'û'=>'u'
  , 'Ú'=>'U'
  , 'Ü'=>'U'
  , 'Ù'=>'U'
  , 'Û'=>'U'
  , 'ç'=>'c'
  , 'Ç'=>'C'
  , '´'=>''
);

$accents_lists = array(
  'á'=>'Á'
  , 'ä'=>'Ä'
  , 'à'=>'À'
  , 'ã'=>'Ã'
  , 'â'=>'Â'
  , 'é'=>'É'
  , 'ë'=>'Ë'
  , 'è'=>'È'
  , 'ê'=>'Ê'
  , 'í'=>'Í'
  , 'ï'=>'Ï'
  , 'ì'=>'Ì'
  , 'î'=>'Î'
  , 'ó'=>'Ó'
  , 'ö'=>'Ö'
  , 'ò'=>'Ò'
  , 'õ'=>'Õ'
  , 'ô'=>'Ô'
  , 'ú'=>'Ú'
  , 'ü'=>'Ü'
  , 'ù'=>'Ù'
  , 'û'=>'Û'
  , 'ç'=>'Ç'
);

$html_entities_list = array(
  '&nbsp;'  =>  ' ' 
  , '&gt;'  =>  '>' 
  , '<'  =>  '<' 
  , '&amp;'  =>  '&' 
  , '&quot;'  =>  '"' 
  , '&apos;'  =>  '"' 
  , '&iexcl;'  =>  '¡' 
  , '&cent;'  =>  '¢' 
  , '&pound;'  =>  '£' 
  , '&curren;'  =>  '¤' 
  , '&yen;'  =>  '¥' 
  , '&brvbar;'  =>  '¦' 
  , '&sect;'  =>  '§' 
  , '&uml;'  =>  '¨' 
  , '&copy;'  =>  '©' 
  , '&ordf;'  =>  'ª' 
  , '&laquo;'  =>  '«' 
  , '&not;'  =>  '¬' 
  , '&shy;'  =>  '­' 
  , '&reg;'  =>  '®' 
  , '&macr;'  =>  '¯' 
  , '&deg;'  =>  '°' 
  , '&plusmn;'  =>  '±' 
  , '&sup2;'  =>  '²' 
  , '&sup3;'  =>  '³' 
  , '&acute;'  =>  '´' 
  , '&micro;'  =>  'µ' 
  , '&para;'  =>  '¶' 
  , '&middot;'  =>  '·' 
  , '&cedil;'  =>  '¸' 
  , '&sup1;'  =>  '¹' 
  , '&ordm;'  =>  'º' 
  , '&raquo;'  =>  '»' 
  , '&frac14;'  =>  '¼' 
  , '&frac12;'  =>  '½' 
  , '&frac34;'  =>  '¾' 
  , '&iquest;'  =>  '¿' 
  , '&times;'  =>  '×' 
  , '&divide;'  =>  '÷' 
  , '&Agrave;'  =>  'À' 
  , '&Aacute;'  =>  'Á' 
  , '&Acirc;'  =>  'Â' 
  , '&Atilde;'  =>  'Ã' 
  , '&Auml;'  =>  'Ä' 
  , '&Aring;'  =>  'Å' 
  , '&AElig;'  =>  'Æ' 
  , '&Ccedil;'  =>  'Ç' 
  , '&Egrave;'  =>  'È' 
  , '&Eacute;'  =>  'É' 
  , '&Ecirc;'  =>  'Ê' 
  , '&Euml;'  =>  'Ë' 
  , '&Igrave;'  =>  'Ì' 
  , '&Iacute;'  =>  'Í' 
  , '&Icirc;'  =>  'Î' 
  , '&Iuml;'  =>  'Ï' 
  , '&ETH;'  =>  'Ð' 
  , '&Ntilde;'  =>  'Ñ' 
  , '&Ograve;'  =>  'Ò' 
  , '&Oacute;'  =>  'Ó' 
  , '&Ocirc;'  =>  'Ô' 
  , '&Otilde;'  =>  'Õ' 
  , '&Ouml;'  =>  'Ö' 
  , '&Oslash;'  =>  'Ø' 
  , '&Ugrave;'  =>  'Ù' 
  , '&Uacute;'  =>  'Ú' 
  , '&Ucirc;'  =>  'Û' 
  , '&Uuml;'  =>  'Ü' 
  , '&Yacute;'  =>  'Ý' 
  , '&THORN;'  =>  'Þ' 
  , '&szlig;'  =>  'ß' 
  , '&agrave;'  =>  'à' 
  , '&aacute;'  =>  'á' 
  , '&acirc;'  =>  'â' 
  , '&atilde;'  =>  'ã' 
  , '&auml;'  =>  'ä' 
  , '&aring;'  =>  'å' 
  , '&aelig;'  =>  'æ' 
  , '&ccedil;'  =>  'ç' 
  , '&egrave;'  =>  'è' 
  , '&eacute;'  =>  'é' 
  , '&ecirc;'  =>  'ê' 
  , '&euml;'  =>  'ë' 
  , '&igrave;'  =>  'ì' 
  , '&iacute;'  =>  'í' 
  , '&icirc;'  =>  'î' 
  , '&iuml;'  =>  'ï' 
  , '&eth;'  =>  'ð' 
  , '&ntilde;'  =>  'ñ' 
  , '&ograve;'  =>  'ò' 
  , '&oacute;'  =>  'ó' 
  , '&ocirc;'  =>  'ô' 
  , '&otilde;'  =>  'õ' 
  , '&ouml;'  =>  'ö' 
  , '&oslash;'  =>  'ø' 
  , '&ugrave;'  =>  'ù' 
  , '&uacute;'  =>  'ú' 
  , '&ucirc;'  =>  'û' 
  , '&uuml;'  =>  'ü' 
  , '&yacute;'  =>  'ý' 
  , '&thorn;'  =>  'þ' 
  , '&yuml;'  =>  'ÿ' 
);

$month_name = array(
    "01" => "Janeiro"
    , "02" => "Fevereiro"
    , "03" => "Março"
    , "04" => "Abril"
    , "05" => "Maio"
    , "06" => "Junho"
    , "07" => "Julho"
    , "08" => "Agosto"
    , "09" => "Setembro"
    , "10" => "Outubro"
    , "11" => "Novembro"
    , "12" => "Dezembro"
);

$ufbr_lists = array(
    "AC" => "Acre"
    , "AL" => "Alagoas"
    , "AP" => "Amapá"
    , "AM" => "Amazonas"
    , "BA" => "Bahia"
    , "CE" => "Ceará"
    , "DF" => "Distrito Federal"
    , "ES" => "Espírito Santo"
    , "GO" => "Goiás"
    , "MA" => "Maranhão"
    , "MT" => "Mato Grosso"
    , "MS" => "Mato Grosso do Sul"
    , "MG" => "Minas Gerais"
    , "PA" => "Pará"
    , "PB" => "Paraíba"
    , "PR" => "Paraná"
    , "PE" => "Pernambuco"
    , "PI" => "Piauí"
    , "RJ" => "Rio de Janeiro"
    , "RN" => "Rio Grande do Norte"
    , "RS" => "Rio Grande do Sul"
    , "RO" => "Rondônia"
    , "RR" => "Roraima"
    , "SC" => "Santa Catarina"
    , "SE" => "Sergipe"
    , "SP" => "São Paulo"
    , "TO" => "Tocantins"
);

$meta = array(
  "Avert" =>  array(
    "1" =>  "Cumprimento do Sell Out do trimestre da área"
    , "2" => "Positivação do trimestre da área através da média mês do trimestre"
    , "3" => "Cumprimento da PV do trimestre – Venda do Mix planejado com os lançamentos"
    , "4" => "Cumprimento da PV (R$)"
    , "bonus" => "Bônus de Pontos"
  )
  , "DT" => array(
    "Supervisor(a)" => array(
      "1" => "Sell Out Geral do trimestre da Distribuidora"
      , "2" => "Positivação do trimestre da distribuidora através da média mês do trimestre"
      , "3" => "Distribuição numérica dos lançamentos do trimestre da distribuidora através da média mês do trimestre"
      , "bonus" => "Bônus de Pontos"
    )
    , "Vendedor(a)" => array(
      "1" => "Cumprimento do valor de Sell Out do trimestre"
      , "2" => "Positivação do trimestre através da média mês do trimestre"
      , "3" => "Distribuição numérica dos lançamentos do através da média mês do trimestre"
      , "bonus" => "Bônus de Pontos"
    )
    , "Promotor(a)" => array(
      "1" => "Sell Out Geral do trimestre da Distribuidora"
      , "2" => "Positivação do trimestre da distribuidora através da média mês do trimestre"
      , "3" => "Distribuição numérica dos lançamentos do trimestre da distribuidora através da média mês do trimestre"
      , "bonus" => "Bônus de Pontos"
    )
  )
) ;


$periodos = array(
  1 => array(
      "start" => date("Y-m-d H:i:s", strtotime("01-01-2022 00:00:00")),
      "end" => date("Y-m-d H:i:s", strtotime("31-03-2022 23:59:59")),
      "data" => array(),
      "tipo" => array()
  )
  , 2 => array(
      "start" => date("Y-m-d H:i:s", strtotime("01-04-2022 00:00:00")),
      "end" => date("Y-m-d H:i:s", strtotime("30-06-2022 23:59:59")),
      "data" => array(),
      "tipo" => array()
  )
  , 3 => array(
    "start" => date("Y-m-d H:i:s", strtotime("01-07-2022 00:00:00")),
    "end" => date("Y-m-d H:i:s", strtotime("30-09-2022 23:59:59")),
    "data" => array(),
    "tipo" => array()
  )
   , 4 => array(
      "start" => date("Y-m-d H:i:s", strtotime("01-10-2022 00:00:00")),
      "end" => date("Y-m-d H:i:s", strtotime("31-12-2022 23:59:59")),
      "data" => array(),
      "tipo" => array()
  )
  /*, 5 => array(
    "start" => date("Y-m-d H:i:s", strtotime("01-01-2022 00:00:00")),
    "end" => date("Y-m-d H:i:s", strtotime("31-01-2022 23:59:59")),
    "data" => array(),
    "tipo" => array()
  ) */
);

$receita_category = array(
  "bolos-e-tortas-doces" => "BOLOS E TORTAS DOCES",
  "carnes" => "CARNES",
  "aves" => "AVES",
  "peixes-e-frutos-do-mar" => "PEIXES E FRUTOS DO MAR",
  "saladas, molhos e acompanhamentos" => "SALADAS, MOLHOS E ACOMPANHAMENTOS",
  "sopa" => "SOPAS",
  "massas" => "MASSAS",
  "bebidas" => "BEBIDAS",
  "doces-e-sobremesas" => "DOCES E SOBREMESAS",
  "lanches" => "LANCHES",
  "prato-unico" => "PRATO ÚNICO",
  "light" => "LIGHT",
  "alimentacao-saudavel" => "ALIMENTAÇÃO SAUDÁVEL",
) ;
?>