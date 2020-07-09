<?php
#注册插件
RegisterPlugin("wmqtx","ActivePlugin_wmqtx");

function ActivePlugin_wmqtx() {
	Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags','wmqtx_Pre');

}

function wmqtx_Pre(&$template){
	global $zbp;
	$zbp->header .= '<script type="text/javascript" src="/zb_users/plugin/wmqtx/wmqtx.js" id="mymouse"></script>'."\r\n";	
}
function InstallPlugin_wmqtx() {}
function UninstallPlugin_wmqtx() {}