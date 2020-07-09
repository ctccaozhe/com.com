<?php
#注册插件
RegisterPlugin("RemoteImage","ActivePlugin_RemoteImage");

function ActivePlugin_RemoteImage() {
	Add_Filter_Plugin('Filter_Plugin_PostArticle_Succeed','RemoteImage_Main');
}

function RemoteImage_Main(&$article) {
	global $zbp;

	set_time_limit(0);
	ZBlogException::ClearErrorHook();
	$content = $article->Content;
	$pattern = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/";
	preg_match_all($pattern,$content,$matchContent);
	$picArray = $matchContent[1];
	if ($picArray){		
		foreach($picArray as $key=>$rurl){			
			if(substr($rurl,0,strlen($zbp->host))!=$zbp->host) {
				$path=$zbp->usersdir.'upload/'.date('Y').'/'.date('m');			
				if(!file_exists($path)) mkdir($path,0755,true);
				$picname=date('YmdHis').'_'.rand(10000,99999).'.'.pathinfo($rurl,PATHINFO_EXTENSION);
				$pic=$path.'/'.$picname;
				$getpic=RemoteImage_Save($rurl,$pic,$picname);
				$picUrl=str_replace($zbp->path,$zbp->host,$pic);
				$article->Content=str_replace($rurl,$picUrl,$article->Content);
			}
		}
	}
	
	$article->Save();

}

function RemoteImage_Save($url,$filename="",$name) {
	global $zbp;

	if($url=="") return false;

	if($filename=="") {
		$ext=strrchr($url,".");
		if($ext!=".gif" && $ext!=".jpg" && $ext!=".png") return false;
		$filename=date("YmdHis").$ext;
	}

	$img = GetHttpContent($url);
	$size = strlen($img);

	$fp2=@fopen($filename, "a");
	fwrite($fp2,$img);
	fclose($fp2);

	$upload = new Upload;
	$upload->Name = $name;
	$upload->SourceName = $name;
	$upload->MimeType = "";
	$upload->Size = $size;
	$upload->AuthorID = $zbp->user->ID;
	
	foreach ($GLOBALS['hooks']['Filter_Plugin_Upload_SaveFile'] as $fpname => &$fpsignal) {
        $fpreturn = $fpname($filename, $upload);
        if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
            $fpsignal = PLUGIN_EXITSIGNAL_NONE;
            return $fpreturn;
        }
    }
	
	$upload->Save();

	return true; 

}

function InstallPlugin_RemoteImage() {

}

function UninstallPlugin_RemoteImage() {

}
