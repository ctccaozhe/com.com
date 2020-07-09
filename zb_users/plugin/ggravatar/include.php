<?php

#注册插件
RegisterPlugin("ggravatar", "ActivePlugin_ggravatar");

function ActivePlugin_ggravatar() {

	Add_Filter_Plugin('Filter_Plugin_Mebmer_Avatar', 'ggravatar_Url');

}

function InstallPlugin_ggravatar() {
	global $zbp;
	$zbp->Config('ggravatar')->s = '80';
	$zbp->Config('ggravatar')->d = 'identicon';
	$zbp->Config('ggravatar')->dd = $zbp->host.'zb_users/avatar/0.png';
	$zbp->Config('ggravatar')->r = 'G';
	$zbp->Config('ggravatar')->qq = 1;
	$zbp->Config('ggravatar')->check = 0;
	$zbp->Config('ggravatar')->default_url = 'https://cdn.v2ex.com/gravatar/';
	$zbp->Config('ggravatar')->local_priority = 1;
	$zbp->SaveConfig('ggravatar');
}

function UninstallPlugin_ggravatar() {
	global $zbp;
	$zbp->DelConfig('ggravatar');
}
//用的这个
function ggravatar_get( $email,$s = '80', $d = 'identicon', $r = 'G', $img = null, $atts = array()) {
	global $zbp;
	$url = '';
	$local_rg = $zbp->host . 'zb_users/plugin/ggravatar/avatars/'.mt_rand(1,20).'.jpg';
	$local_g = $zbp->Config('ggravatar')->dd?$zbp->Config('ggravatar')->dd:$zbp->host.'zb_users/avatar/0.png';

	if (!$email || $email == 'null@null.com'){
		if($zbp->Config('ggravatar')->d == "local_rg"){
			$url = $local_rg;
		}else{
			$url = $local_g;
		}
	}else{
		$urlencode_local_rg = urlencode($local_rg);
		$urlencode_local_g = urlencode($local_g);
		if (!$s){
			$s = $zbp->Config('ggravatar')->s;
		}
		if (!$d){
			if ($zbp->Config('ggravatar')->d == "local_g"){
				$d = $urlencode_local_g;
			}elseif($zbp->Config('ggravatar')->d == "local_rg"){
				$d = $urlencode_local_rg;
			}else{
				$d = $zbp->Config('ggravatar')->d;
			}
		}
		if (!$r){
			$r = $zbp->Config('ggravatar')->r;
		}
		
		
		/* $img 控制是否联网检查链接是否404 */
		if (!$img)$img = $zbp->Config('ggravatar')->check;
		if ($img){
			if (ggravatar_check($email,$img)){
				$url = $zbp->Config('ggravatar')->default_url. md5( strtolower( trim( $email ) ) ) . '?'.($s?'s='.$s:'').($r?'&r='.$r:'');
			}else{
				if ($zbp->Config('ggravatar')->d == "local_rg"){
				$url = $local_rg;
				}else{
				$url = $local_g;
				}
			}
		}else{
			$url = $zbp->Config('ggravatar')->default_url. md5( strtolower( trim( $email ) ) ) . '?'.($s?'s='.$s:'').($d?'&d='.$d:'').($r?'&r='.$r:'');
		}
		/* $atts=array( 'alt'=>'Gravatar', 'class'=>'ggravatar' ) */
		if ( !empty($atts) ) {
			 $url = '<img src="' . $url . '"';
			 foreach ( $atts as $key => $val ){
			 $url .= ' ' . $key . '="' . $val . '"';
			 }
			 $url .= ' />';
		}
	}
	
	return $url;
	
}

//验证是否设置了 Gravatar 头像 而不是 Gravatar 默认的头像  这个判断过程会有点慢
function ggravatar_check($email=null,$netcheck=null,$url=null){
	global $zbp;
	if ($netcheck){
		set_time_limit(0);
		if ($url){
			$headers = @get_headers($url);
			if (!preg_match("|200|", $headers[0])) {
				return false;
			} else {
				return true;
			}
		}else{
			if (!$email) return false;
			$uri = $hash = '';
			$hash = md5(strtolower(trim($email)));
			$uri = $zbp->Config('ggravatar')->default_url . $hash . '?d=404';
			$headers = @get_headers($uri);
			if (!preg_match("|200|", $headers[0])) {
				return false;
			} else {
				return true;
			}
		}
	}else{
		return true;
	}
}


function ggravatar_Url(&$member) {
	global $zbp;
	if ($zbp->Config('ggravatar')->local_priority && $member->ID > 0) {
		if (is_file($zbp->usersdir . 'avatar/' . $member->ID . '.png')) {
			$GLOBALS['Filter_Plugin_Mebmer_Avatar']['ggravatar_Url'] = PLUGIN_EXITSIGNAL_RETURN;
			return $zbp->host . 'zb_users/avatar/' . $member->ID . '.png';
		}
	}else{
		$GLOBALS['Filter_Plugin_Mebmer_Avatar']['ggravatar_Url'] = PLUGIN_EXITSIGNAL_RETURN;
		if($zbp->Config('ggravatar')->qq){
			preg_match_all('/((\d)*)@qq.com/', $member->Email, $vai);
			if (isset($vai['1']['0'])){
				return 'https://q2.qlogo.cn/headimg_dl?dst_uin='.$vai['1']['0'].'&spec=100';
			}else{
				return ggravatar_get( $email=$member->Email, $s = null, $d = null, $r = null,$img = null, $atts = array());
			}
		}else{
			return ggravatar_get( $email=$member->Email, $s = null, $d = null, $r = null,$img = null, $atts = array());
		}
	}
}

?>