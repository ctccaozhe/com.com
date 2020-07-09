<?php
#注册插件
RegisterPlugin("FY_Noiframe","ActivePlugin_FY_Noiframe");

function ActivePlugin_FY_Noiframe() {
   Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags','FY_Noiframe_main');
}
function FY_Noiframe_main() {
	global $zbp;
	if ($zbp->Config('FY_Noiframe')->Noiframe=="a"){
		$zbp->header .=header("X-FRAME-OPTIONS:DENY");
	} else {
		$zbp->footer .= '<script>if(self==top){var theBody=document.getElementsByTagName(\'body\')[0];theBody.style.display="block"}else{top.location=self.location}</script>' . "\r\n";
	}
}
function InstallPlugin_FY_Noiframe() {
	global $zbp;
    if (!$zbp->Config('FY_Noiframe')->HasKey('version')) {
        $zbp->Config('FY_Noiframe')->version = '1.0';
        $zbp->Config('FY_Noiframe')->Noiframe = 'a';
        $zbp->SaveConfig('FY_Noiframe');
    }
}
function UninstallPlugin_FY_Noiframe() {}