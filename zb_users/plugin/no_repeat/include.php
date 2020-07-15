<?php
#注册插件
RegisterPlugin("no_repeat","ActivePlugin_no_repeat");
function ActivePlugin_no_repeat() {
	Add_Filter_Plugin('Filter_Plugin_Cmd_Ajax','no_repeat_Filter_Plugin_Cmd_Ajax');
	Add_Filter_Plugin('Filter_Plugin_Admin_Footer', 'no_repeat_Filter_Plugin_Admin_Footer');	
}
function InstallPlugin_no_repeat() {
}
function UninstallPlugin_no_repeat() {
}
function no_repeat_Filter_Plugin_Cmd_Ajax(){
	global $zbp;
	$action 	=	GetVars('src', 'GET');
	$array  	=	array();
	$no_repeat	=	array();
	$c			=	array();
	$title=trim(GetVars('title', 'POST'));
	$id=trim(GetVars('id', 'POST'));
	if($title!=''){
		switch ($action) {
			case "no_repeat_verify_post_title":
				$no_repeat[] = 'no_repeat_verify_post_title';
				$c=$zbp->GetPostList('',array(array('=','log_Title',$title),array('<>','log_ID',$id)));
				if(count($c)>0){
					$array['msg'] = '该标题在数据库中已存在，请使用其他标题...';
				}
				break;
			case "no_repeat_verify_post_alias":
				$no_repeat[] = 'no_repeat_verify_post_alias';
				$c=$zbp->GetPostList('',array(array('=','log_Alias',$title),array('<>','log_ID',$id)));
				if(count($c)>0){
					$array['msg'] = '该别名在数据库中已存在，请使用其他别名...';
				}
				break;
			case "no_repeat_verify_cate_name":
				$no_repeat[] = 'no_repeat_verify_cate_name';
				$c=$zbp->GetCategoryList('',array(array('=','cate_Name',$title),array('<>','cate_ID',$id)));
				if(count($c)>0){
					$array['msg'] = '该分类名称在数据库中已存在，请使用其他分类名称...';
				}
				break;
			case "no_repeat_verify_cate_alias":
				$no_repeat[] = 'no_repeat_verify_cate_alias';
				$c=$zbp->GetCategoryList('',array(array('=','cate_Alias',$title),array('<>','cate_ID',$id)));
				if(count($c)>0){
					$array['msg'] = '该分类别名在数据库中已存在，请使用其他分类别名...';
				}
				break;
			case "no_repeat_verify_tag_name":
				$no_repeat[] = 'no_repeat_verify_tag_name';
				$c=$zbp->GetTagList('',array(array('=','tag_Name',$title),array('<>','tag_ID',$id)));
				if(count($c)>0){
					$array['msg'] = '该标签名称在数据库中已存在，请使用其他标签名称...';
				}
				break;
			case "no_repeat_verify_tag_alias":
				$no_repeat[] = 'no_repeat_verify_tag_alias';
				$c=$zbp->GetTagList('',array(array('=','tag_Alias',$title),array('<>','tag_ID',$id)));
				if(count($c)>0){
					$array['msg'] = '该标签别名在数据库中已存在，请使用其他标签别名...';
				}
				break;
			case "no_repeat_verify_mem_name":
				$no_repeat[] = 'no_repeat_verify_mem_name';
				$c=$zbp->GetMemberList('',array(array('=','mem_Name',$title),array('<>','mem_ID',$id)));
				if(count($c)>0){
					$array['msg'] = '该用户名称在数据库中已存在，请使用其他用户名称...';
				}
				break;
			case "no_repeat_verify_mem_alias":
				$no_repeat[] = 'no_repeat_verify_mem_alias';
				$c=$zbp->GetMemberList('',array(array('=','mem_Alias',$title),array('<>','mem_ID',$id)));
				if(count($c)>0){
					$array['msg'] = '该用户别名在数据库中已存在，请使用其他用户别名...';
				}
				break;
			default:
				#code...
				break;
		}
		if (in_array($action,$no_repeat)) {
			if (count($array) > 0) {
				echo json_encode($array);
			}
		}
	}
}
function no_repeat_Filter_Plugin_Admin_Footer(){
	global $zbp;
	$act=GetVars('act', 'GET');
	$act_array=array('ArticleEdt','PageEdt','CategoryEdt','TagEdt','MemberNew','MemberEdt');
	if(in_array($act,$act_array)){
		echo '<script src="' . $zbp->host . 'zb_users/plugin/no_repeat/no_repeat.js" type="text/javascript"></script>' . "\r\n";
	}
}