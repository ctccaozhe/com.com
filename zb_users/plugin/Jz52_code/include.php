<?php
#注册插件
RegisterPlugin("Jz52_code","ActivePlugin_Jz52_code");

function ActivePlugin_Jz52_code() {
    Add_Filter_Plugin('Filter_Plugin_ViewPost_Template', 'Jz52_code_main');
}

function Jz52_code_main(){
    global $zbp;
	$zbp->footer .='<link rel="stylesheet" href="'.$zbp->host.'zb_users/plugin/Jz52_code/Jz52_code.css"><script src="'.$zbp->host.'zb_users/plugin/Jz52_code/clipboard.min.js"></script><script src="'.$zbp->host.'zb_users/plugin/Jz52_code/Jz52_code.js" type="text/javascript"/></script>';
}

function InstallPlugin_Jz52_code() {}

function UninstallPlugin_Jz52_code() {}