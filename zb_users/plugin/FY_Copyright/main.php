<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('FY_Copyright')) {$zbp->ShowError(48);die();}

$blogtitle='FY_Copyright';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle;?></div>
  <div class="SubMenu">
   <a href="http://www.fengyan.cc/" target="_blank"><span class="m-right">帮助</span></a>
  </div>
  <?php 
	if(isset($_POST['Rightclick'])){
	$zbp->Config('FY_Copyright')->Prompt = $_POST['Prompt'];
	$zbp->Config('FY_Copyright')->Rightclick = $_POST['Rightclick'];
	$zbp->Config('FY_Copyright')->Banview = $_POST['Banview'];
	$zbp->Config('FY_Copyright')->jzff = $_POST['jzff'];
		$zbp->SaveConfig('FY_Copyright');
	$zbp->ShowHint('good');
	}
	?>  
<form id="form3" name="form3" method="post">
<table width="100%" style='padding:0;margin:0;' cellspacing='0' cellpadding='0' class="tableBorder">
	<tr>	
<th width="25%"><p align="center">功能管理</p></th>
    <th width="75%"><p align="center">使用说明</p></th>
</tr>
<tr>
<th width="25%"><p align="center">复制文字版权提示<br><input type="text" id="Prompt" name="Prompt" class="checkbox" value="<?php echo $zbp->Config('FY_Copyright')->Prompt;?>"/></p></th>
<th width="75%"><p align="center">当访客复制网站文字时会弹窗提示版权说明；启用禁止复制文字功能此功能失效！</p></th>
</tr> <tr>
<th width="25%"><p align="center">禁止复制网站文字<br><input type="text" id="Rightclick" name="Rightclick" class="checkbox" value="<?php echo $zbp->Config('FY_Copyright')->Rightclick;?>"/></p></th>
<th width="75%"><p align="center">禁止访客复制网站文字</p></th>
</tr> <tr>	
<th width="25%"><p align="center">禁止右键查看<br><input type="text" id="Banview" name="Banview" class="checkbox" value="<?php echo $zbp->Config('FY_Copyright')->Banview;?>"/></p></th>
<th width="75%"><p align="center">启用后网站将不能通过右键查看，但是能通过f12查看属性</p></th>
</tr><tr>
<th width="25%"><p align="center">禁止F12查看<br><input type="text" id="jzff" name="jzff" class="checkbox" value="<?php echo $zbp->Config('FY_Copyright')->jzff;?>"/></p></th>
<th width="75%"><p align="center">启用后网站将不能通过F12查看，按F12自动关闭当前网页</p></th>
</tr>
	</table>
	  <hr/>
	  <p>
		<input type="submit" class="button" value="<?php echo $lang['msg']['submit']?>" />
	  </p>
	</form>
  <div id="divMain2">
<!--代码-->
  </div>
</div>
<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>