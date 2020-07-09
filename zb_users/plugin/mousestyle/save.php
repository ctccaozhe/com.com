<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';

$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('mousestyle')) {$zbp->ShowError(48);die();}

if($_GET['type'] == 'body' ){
	global $zbp;
	foreach ($_FILES as $key => $value) {
		if(!strpos($key, "_php")){
			if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
				$tmp_name = $_FILES[$key]['tmp_name'];
				$name = $_FILES[$key]['name'];
				@move_uploaded_file($_FILES[$key]['tmp_name'], $zbp->usersdir . 'plugin/mousestyle/body.cur');
			}
		}
	}
	$zbp->SetHint('good','网页主体鼠标样式修改成功');
	Redirect('./main.php');
}

if($_GET['type'] == 'a' ){
	global $zbp;
	foreach ($_FILES as $key => $value) {
		if(!strpos($key, "_php")){
			if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
				$tmp_name = $_FILES[$key]['tmp_name'];
				$name = $_FILES[$key]['name'];
				@move_uploaded_file($_FILES[$key]['tmp_name'], $zbp->usersdir . 'plugin/mousestyle/a.cur');
			}
		}
	}
	$zbp->SetHint('good','网页A标签鼠标样式修改成功');
	Redirect('./main.php');
}

?>