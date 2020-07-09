<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
Add_Filter_Plugin('Filter_Plugin_Zbp_ShowError','RespondError',PLUGIN_EXITSIGNAL_RETURN);
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('Uplist')) {$zbp->ShowError(48);die();}
if (!$zbp->ValidToken(GetVars('token','POST'))){$zbp->ShowError(5,__FILE__,__LINE__);die();}
$mustdo=$_POST['mustdo'];
if($mustdo){
    $ul_id=implode(',',GetVars('ulID'));
    $array = explode(',',$ul_id);
	foreach ($array as $id){
	    $u = $zbp->GetUploadByID($id);
	    $u->Del();
        CountMemberArray(array($u->AuthorID), array(0, 0, 0, -1));
        $u->DelFile();
	}
	echo '批量删除成功！';
}else{
	echo '非法访问！';
}
?>
