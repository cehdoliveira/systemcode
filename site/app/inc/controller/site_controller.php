<?php
class site_controller{
	public function logout(){
		unset($_SESSION[constant("cAppKey")]);
		basic_redir($GLOBALS["home_url"]);
	}

	public static function check_login(){
		return isset($_SESSION[constant("cAppKey")]["credential"]["idx"]) && (int)$_SESSION[constant("cAppKey")]["credential"]["idx"] > 0;
	}

	public function display($info){
		include(constant("cRootServer") . "ui/common/head.inc.php");
		include(constant("cRootServer") . "ui/common/header.inc.php");
		include(constant("cRootServer") . "ui/common/navmenu.inc.php");
		if (site_controller::check_login()) {
			print("logado");
		} 
		else {
			include(constant("cRootServer") . "ui/page/inscricao.php");
		}
		include( constant("cRootServer") . "ui/common/foot.php");
		include( constant("cRootServer") . "ui/common/footer.php");
	}
}
