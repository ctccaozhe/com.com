<?php
$table['SpiderStatistics'] = '%pre%spiderstatistics';

$datainfo['SpiderStatistics'] = array(
	'ID'=>array('Spider_ID','integer','',0),
	'Name'=>array('Spider_Name','string',20,''),
	'IP'=>array('Spider_IP','string',15,''),
	'DateTime'=>array('Spider_DateTime','integer','',0),
	'Url'=>array('Spider_Url','string',250,''),
	'Status'=>array('Spider_Status','integer','',1),
);

function SpiderStatistics_SubMenu($id){
	$arySubMenu = array(
		0 => array('基本设置', 'config', 'left', false),
		1 => array('查看记录', 'spider', 'left', false),

	);
	foreach($arySubMenu as $k => $v){
		echo '<a href="?act='.$v[1].'" '.($v[3]==true?'target="_blank"':'').'><span class="m-'.$v[2].' '.($id==$v[1]?'m-now':'').'">'.$v[0].'</span></a>';
	}
}

class SpiderStatistics extends Base{

	function __construct() {
		global $zbp;
		parent::__construct($zbp->table['SpiderStatistics'],$zbp->datainfo['SpiderStatistics']);

		$this->DateTime	= time();
	}

	public function Time($s='Y-m-d H:i:s'){
		return date($s,(int)$this->DateTime);
	}
}
