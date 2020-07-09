<?php
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php';

#注册插件
RegisterPlugin("SpiderStatistics","ActivePlugin_SpiderStatistics");

function ActivePlugin_SpiderStatistics() {
	Add_Filter_Plugin('Filter_Plugin_Admin_TopMenu','SpiderStatistics_AddMenu');
	Add_Filter_Plugin('Filter_Plugin_Index_End','SpiderStatistics_Index_End');
}

function SpiderStatistics_AddMenu(&$m){
	global $zbp;
	$m[]=MakeTopMenu('root', '蜘蛛来访', $zbp->host . 'zb_users/plugin/SpiderStatistics/main.php?act=config','','topmenu_SpiderStatistics');
}

function SpiderStatistics_Index_End() {
	global $zbp;
	$array = array();
	$agent = null;
	$status = null;
	$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$ip = GetGuestIP();
	$datetime = time();

	$spiders = explode('|', $zbp->Config('SpiderStatistics')->spiders);

	foreach ($spiders as $key => $spider) {
		$spidername = explode(',', $spider);
		if(strpos(GetGuestAgent(), $spidername[0]) !== false) {
			$agent = $spidername[1];
			break;
		}
	}

	if($url && $agent) {
		$array = array('Spider_Name' => $agent, 'Spider_IP' => $ip, 'Spider_DateTime' => $datetime, 'Spider_Url' => $url, 'Spider_Status' => 200);

		$sql = $zbp->db->sql->Insert($zbp->table['SpiderStatistics'], $array);
		$zbp->db->Insert($sql);		
	}
}

function SpiderStatistics_CreateTable() {
	global $zbp;
	if ($zbp->db->ExistTable($GLOBALS['table']['SpiderStatistics']) == false) {
		$s = $zbp->db->sql->CreateTable($GLOBALS['table']['SpiderStatistics'], $GLOBALS['datainfo']['SpiderStatistics']);
		$zbp->db->QueryMulit($s);
	}
}

function SpiderStatistics_DelTable() {
	global $zbp;
	if ($zbp->db->ExistTable($zbp->table['SpiderStatistics']) == true) {
		$s = $zbp->db->sql->DelTable($zbp->table['SpiderStatistics']);
		$zbp->db->QueryMulit($s);
	}
}

function InstallPlugin_SpiderStatistics() {
	global $zbp;
	//配置初始化
	if (!$zbp->Config('SpiderStatistics')->HasKey('version')) {
		$zbp->Config('SpiderStatistics')->version = '1.0';

		$zbp->Config('SpiderStatistics')->viewconut='20';
		$zbp->Config('SpiderStatistics')->spiders = 'Baiduspider,Baidu|Googlebot,Google|Sosospider,SoSo|YoudaoBot,YouDao|bingbot,Bing|Sogou web spider,SoGou|Yahoo! Slurp,Yahoo|Alexa,Alexa|360Spider,So';

		$zbp->SaveConfig('SpiderStatistics');
	}
	SpiderStatistics_CreateTable();
}

function UninstallPlugin_SpiderStatistics() {
	global $zbp;
	$zbp->DelConfig('SpiderStatistics');
	SpiderStatistics_DelTable();
}
