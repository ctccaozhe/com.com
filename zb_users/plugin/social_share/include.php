<?php
#注册插件
RegisterPlugin("social_share","ActivePlugin_social_share");

function ActivePlugin_social_share() {
	Add_Filter_Plugin('Filter_Plugin_ViewPost_Template','social_share_Main');//引用CSS
}

function social_share_Main(&$template){
	global $zbp;
	$article = $template->GetTags('article');
	$zbp->footer .= '<link rel="stylesheet" href="'.$zbp->host.'zb_users/plugin/social_share/css/share.min.css" />' . "\r\n";
	$zbp->footer .= '<script src="'.$zbp->host.'zb_users/plugin/social_share/js/social-share.min.js"></script>' . "\r\n";
	$con = '<div class="social-share" data-initialized="true" style="text-align: center;">
    <a href="#" class="social-share-icon icon-weibo"></a>
    <a href="#" class="social-share-icon icon-qq"></a>
	<a href="#" class="social-share-icon icon-wechat"></a>
	<a href="#" class="social-share-icon icon-tencent"></a>
	<a href="#" class="social-share-icon icon-douban"></a>
    <a href="#" class="social-share-icon icon-qzone"></a>
	<a href="#" class="social-share-icon icon-linkedin"></a>
	<a href="#" class="social-share-icon icon-facebook"></a>
	<a href="#" class="social-share-icon icon-twitter"></a>
	<a href="#" class="social-share-icon icon-google"></a>
</div>';
	$article->Content = $article->Content.$con;
	$template->SetTags('article',$article);
}

function InstallPlugin_social_share() {}
function UninstallPlugin_social_share() {}