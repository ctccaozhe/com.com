<?php
#注册插件
RegisterPlugin("fancybox","ActivePlugin_fancybox");

function ActivePlugin_fancybox() {
	Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags','fancybox_main');
	Add_Filter_Plugin('Filter_Plugin_ViewPost_Template','fancybox_Content');
}

function fancybox_main() {
	global $zbp;
	$zbp->header .= '<link href="'.$zbp->host.'zb_users/plugin/fancybox/fancybox.css" rel="stylesheet" type="text/css" />
' . "\r\n";
	$zbp->header .= '<script src="'.$zbp->host.'zb_users/plugin/fancybox/fancybox.js"></script>' . "\r\n";
	$zbp->header .= '<script type="text/javascript">$(document).ready(function() {$(".fancybox").fancybox();});</script>' . "\r\n";
}

function fancybox_Content(&$template){
	global $zbp;
	$article = $template->GetTags('article');
	if(!empty($article)){
	$pattern = "/<img(.*?)src=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|swf)('|\")(.*?)>/i";
	$replacement = '<a$1href=$2$3.$4$5 data-fancybox="images" $6><img src=$2$3.$4$5/></a>';
	$content = preg_replace($pattern, $replacement, $article->Content);
	$article->Content = $content;
	$template->SetTags('article', $article);
	}
}

function InstallPlugin_fancybox() {

}

function UninstallPlugin_fancybox() {

}