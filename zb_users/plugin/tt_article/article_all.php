<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('tt_article')) {$zbp->ShowError(48);die();}
$postid=GetVars("postid","POST");
$ids = trim($postid);
//$abc=json_encode($ids);
$articles=explode(',',$ids);


$act=GetVars("act","GET");
if($act=="del"){
foreach($articles as $article){
$a=$zbp->GetPostByID($article);
$a->Del();
}
echo json_encode(array('s'=>'ok'));	
}elseif($act=="all"){
	
$cateid=trim(GetVars("cateid","POST"));
$cmbStatus=trim(GetVars("cmbStatus","POST"));
$cmbMem=trim(GetVars("cmbMem","POST"));
$cmbtop=trim(GetVars("cmbtop","POST"));
$cmbtop_status=trim(GetVars("cmbtop_status","POST"));
foreach($articles as $article){
$a=$zbp->GetPostByID($article);
//分类
if($cateid!=''){
$a->CateID=$cateid;	
}
//状态
if($cmbStatus!=''){
$a->Status=$cmbStatus;
}
//作者
if($cmbMem!=''){
$a->AuthorID=$cmbMem;
}



if($cmbtop=="1"){
$a->IsTop=0;
$a->TopType='';
}elseif($cmbtop=="2"){
$a->IsTop=1;
$a->TopType=$cmbtop_status;	
}
$a->Save();

}
echo json_encode(array('s'=>'ok'));	
	
}




?>