<?php
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . 'function.php';
RegisterPlugin("clicktip","ActivePlugin_clicktip");

function ActivePlugin_clicktip() {
	Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags', 'clicktipToHeader');
	Add_Filter_Plugin('Filter_Plugin_Zbp_BuildModule','clicktip_AppReg');
}

function clicktip_SubMenu($id){
	$arySubMenu = array(
		0 => array('插件设置', 'base', 'left', false),
		1 => array('检测更新', 'update', 'left', false),
	);
	foreach($arySubMenu as $k => $v){
		echo '<li><a href="?act='.$v[1].'" '.($v[3]==true?'target="_blank"':'').' class="'.($id==$v[1]?'on':'').'">'.$v[0].'</a></li>';
	}
}

function clicktipScript(){
	global $zbp;
	$script = '';
	$keywords = explode('|',$zbp->Config('clicktip')->KeyWords);
	$words = 'Array("'.join('","',$keywords).'")';
	$fontsize = $zbp->Config('clicktip')->FontSize;
	$fontcolor = $zbp->Config('clicktip')->FontColor;
	$fontfamily = $zbp->Config('clicktip')->FontFamily;
	$fontstyle = $zbp->Config('clicktip')->FontStyle;
	$firstkeyindex = $zbp->Config('clicktip')->FirstKeyIndex - 1;
	$script .= '$(function(){
	var keyindex = "'.$firstkeyindex.'";
	$("body").click(function(e){
		var key = new '.$words.';
		var $i = $("<span/>").text(key[keyindex]);
		keyindex = (keyindex + 1) % key.length;
		var x = e.pageX, y = e.pageY;
		$i.css({
			"z-index":9999999999,
			"top":y - 20,
			"left":x,
			"position":"absolute",
			"font-size":"'.$fontsize.'px",
			"color":"#'.$fontcolor.'",
			"font-family":"'.$fontfamily.'",
			"font-style":"'.$fontstyle.'"
		});
		$("body").append($i);
		$i.animate({"top":y-200,"opacity":0},1000,function(){$i.remove();});
	});
});';
	return $script;
}

function clicktipToHeader(){
	global $zbp;
	$zbp->header .= '<script type="text/javascript" src="'. $zbp->host .'zb_users/plugin/clicktip/script/common.js"></script>';
}
function InstallPlugin_clicktip() {
	global $zbp;
	if(!$zbp->Config('clicktip')->HasKey('Version')){
		$zbp->Config('clicktip')->Version = '1.0';
		$zbp->Config('clicktip')->KeyWords = '富强|民主|文明|和谐|自由|平等|公正|法治|爱国|敬业|诚信|友善';
		$zbp->Config('clicktip')->FirstKeyIndex = '1';
		$zbp->Config('clicktip')->FontSize = '14';
		$zbp->Config('clicktip')->FontColor = '004c98';
		$zbp->Config('clicktip')->FontFamily = 'microsoft yahei';
		$zbp->Config('clicktip')->FontStyle = 'normal';
		$zbp->Config('clicktip')->PostSAVECONFIG = '1';
		$zbp->SaveConfig('clicktip');
	}
	clicktip_AppReg();
	$clicktipScript = clicktipScript();
	@file_put_contents($zbp->path.'zb_users/plugin/clicktip/script/common.js', $clicktipScript);
}
function UninstallPlugin_clicktip() {
	global $zbp;
	if($zbp->Config('clicktip')->PostSAVECONFIG == '0'){
		$zbp->DelConfig('clicktip');
	}
}