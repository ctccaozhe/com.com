<?php
require dirname(__FILE__) . DIRECTORY_SEPARATOR . '/function.php';
#注册插件
RegisterPlugin("tt_article","ActivePlugin_tt_article");
function ActivePlugin_tt_article() {
	global $zbp;
	
	Add_Filter_Plugin('Filter_Plugin_Admin_Begin','tt_article_Filter_Plugin_Admin_Begin');
	
}

function InstallPlugin_tt_article() {
	global $zbp;
	if(!$zbp->Config('tt_article')->num){
		$zbp->Config('tt_article')->num='50';
		$zbp->SaveConfig('tt_article');
	}
}
function UninstallPlugin_tt_article() {
	global $zbp;
	$zbp->DelConfig('tt_article');
}