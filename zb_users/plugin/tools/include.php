<?php


#注册插件
RegisterPlugin("tools","ActivePlugin_tools");


function ActivePlugin_tools() {

	Add_Filter_Plugin('Filter_Plugin_Admin_SettingMng_SubMenu','tools_AddMenu');

}



function tools_AddMenu(){
	global $zbp;
	echo '<a href="'. $zbp->host .'zb_users/plugin/tools/main.php"><span class="m-left">蜘蛛地图标准协议</span></a>';

}



?>