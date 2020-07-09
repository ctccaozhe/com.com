<?php
#注册插件
RegisterPlugin("mousestyle","ActivePlugin_mousestyle");

function ActivePlugin_mousestyle() {
	Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags','mousestyle_Pre','Filter_Plugin_Upload_SaveFile');

}

function mousestyle_Pre(&$template){
	global $zbp;
	$zbp->header .= "<link rel=\"stylesheet\" href=\"{$zbp->host}zb_users/plugin/mousestyle/mousestyle.css\" type=\"text/css\" />\r\n";	
}

function InstallPlugin_mousestyle() {}
function UninstallPlugin_mousestyle() {}
?>