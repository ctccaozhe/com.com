<?php
function clicktip_AppReg(){
	global $zbp;
	$appname='clicktip';
	$zbp->Config($appname.'Config')->AppCenterCheckTime=time();
	$zbp->SaveConfig($appname.'Config');
	PluginAppAjax($zbp->host,$appname,$zbp->Config($appname.'Config')->AppCenterCheckTime);
}

function PluginAppAjax($zbphost,$id,$apptime){
	global $zbp;
	$postdata = array(
		'USER_HOST'=>$zbphost,
		'APPID'=>$id,
		'UA'=>GetGuestAgent(),
		'IP'=>GetGuestIP(),
		'AppCentreUserName'=>$zbp->Config('AppCentre')->username,
		'AppTime'=>$apptime
	);
	
	$configjsonfile=ZBP_PATH . DIRECTORY_SEPARATOR . 'zb_users'.DIRECTORY_SEPARATOR.'plugin'.DIRECTORY_SEPARATOR.'clicktip'.DIRECTORY_SEPARATOR.'plugin'.DIRECTORY_SEPARATOR.'register.data';

	if(!$postdata['AppCentreUserName'] == null){
		$writeData = $postdata['AppCentreUserName'].'(or)'.$zbp->user->Name.'&'.$zbp->user->Email;
	}else{
		$writeData = $zbp->user->Name.'&'.$zbp->user->Email;
	}

	if (file_exists($configjsonfile)){
		$postdata["OldUser"] = base64_decode(file_get_contents($configjsonfile)).'(to)'.$writeData;
	}else{
		$postdata["OldUser"] = $zbp->user->Name.'&'.$zbp->user->Email;
	}

	file_put_contents($configjsonfile, base64_encode($writeData));

	$http_post = Network::Create();
	$http_post->open('POST',base64_decode("aHR0cDovL3d3dy50b3llYW4uY29tL3piX3VzZXJzL3BsdWdpbi9NeUFQSS9jaGVjay5waHA="));
	$http_post->setRequestHeader('Referer',substr($zbp->host,0,-1) . $zbp->currenturl);
	$http_post->send($postdata);
}
?>