<?php
#注册插件
RegisterPlugin("MyhkPlayer","ActivePlugin_MyhkPlayer");

function ActivePlugin_MyhkPlayer() {
	Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags','MyhkPlayer_Zbp_MakeTemplatetags');
}

function MyhkPlayer_Zbp_MakeTemplatetags() {
	global $zbp;
	$config = array(
		"xl" => $zbp->Config('MyhkPlayer')->xl,
		"jq" => $zbp->Config('MyhkPlayer')->jq,
		"mb" => $zbp->Config('MyhkPlayer')->mb,
		"id" => $zbp->Config('MyhkPlayer')->id
	);
	if($config['jq']=='open'){
		$zbp->header .=  '<script src="//lib.baomitu.com/jquery/3.3.1/jquery.min.js"></script>' . "\r\n";
	}
	if($config['mb']=='open'){
		$zbp->footer .=  '<script id="myhk" src="https://' . $config["xl"] . '.lmih.cn/player/js/player.js" key="' . $config["id"] . '" m="1"></script>' . "\r\n";
	}else{
		$zbp->footer .=  '<script id="myhk" src="https://' . $config["xl"] . '.lmih.cn/player/js/player.js" key="' . $config["id"] . '"></script>' . "\r\n";
	}
}

function InstallPlugin_MyhkPlayer() {
    global $zbp,$obj,$bucket;
    //配置初始化
    if (!$zbp->Config('MyhkPlayer')->HasKey('version')) {
		$zbp->Config('MyhkPlayer')->version = '20191101';
		$zbp->Config('MyhkPlayer')->xl = 'player';
		$zbp->Config('MyhkPlayer')->jq = 'close';
		$zbp->Config('MyhkPlayer')->mb = 'open';
        $zbp->Config('MyhkPlayer')->id = 'demo';
        $zbp->SaveConfig('MyhkPlayer');
    }
}

function UninstallPlugin_MyhkPlayer() {
	global $zbp;
//	$zbp->DelConfig('MyhkPlayer');
}