<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';

$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('tt_article')) {$zbp->ShowError(48);die();}
$blogtitle='批量文章管理';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
	<?php 
	tt_article_Admin_ArticleMng();
	;?>
</div>
<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>