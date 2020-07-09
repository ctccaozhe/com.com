<?php
#注册插件
#此插件是由唐朝的插件改写而成
RegisterPlugin ("fangcaiji","ActivePlugin_fangcaiji");
function  ActivePlugin_fangcaiji (){
	Add_Filter_Plugin ('Filter_Plugin_ViewPost_Template','fangcaiji_Content');//处理文章页模板接口
	Add_Filter_Plugin ('Filter_Plugin_Admin_TopMenu','fangcaiji_AddMenu');//定义后台顶部导航栏接口
}

//定义后台顶部导航栏接口
function  fangcaiji_AddMenu (&$m){
	global $zbp;
	$m[]=MakeTopMenu ("root",'仿采集设置',$zbp->host ."zb_users/plugin/fangcaiji/main.php","","topmenu_fangcaiji");
}

//处理文章页模板接口
function  fangcaiji_Content (&$template){
	global $zbp;
	$article=$template->GetTags('article');
	$bobocanshu[0]=$zbp->Config('fangcaiji')->bobocanshu1;//把插件设置的值赋值到变量$bobocanshu
	$bobocanshu[1]=$zbp->Config('fangcaiji')->bobocanshu2;
	$bobocanshu[2]=$zbp->Config('fangcaiji')->bobocanshu3;
	$bobocanshu[3]=$zbp->Config('fangcaiji')->bobocanshu4;
	$bobocanshu[4]=$zbp->Config('fangcaiji')->bobocanshu5;
	$bobocanshu[5]=$zbp->Config('fangcaiji')->bobocanshu6;
	$bobocanshu[6]=$zbp->Config('fangcaiji')->bobocanshu7;
	$bobocanshu[7]=$zbp->Config('fangcaiji')->bobocanshu8;
	$bobocanshu[8]=$zbp->Config('fangcaiji')->bobocanshu9;
	$bobocanshu[9]=$zbp->Config('fangcaiji')->bobocanshu10;
	
	$content=$article->Content.$bobocanshu[array_rand($bobocanshu)];//把文章内容和后缀组合在一起，复制到变量$content
	$article->Content=$content;//把$content传入文章内容
	$template->SetTags('article',$article);
}

function  InstallPlugin_fangcaiji (){
}
function  UninstallPlugin_fangcaiji (){
}