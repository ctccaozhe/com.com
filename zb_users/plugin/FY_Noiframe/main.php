<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('FY_Noiframe')) {$zbp->ShowError(48);die();}

$blogtitle='禁止iframe框架引用';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';

if(count($_POST)>0){
	$zbp->Config('FY_Noiframe')->Noiframe = $_POST['Noiframe'];
	$zbp->ShowHint('good');
	$zbp->SaveConfig('FY_Noiframe');
}
?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle;?></div>
  <div class="SubMenu">
	<a href="main.php" ><span class="m-left">基础设置</span></a>
	<a href="http://www.fengyan.cc" target="_blank"><span class="m-right">帮助</span></a>
  </div>
  <div id="divMain2">
	<form method="post">
			<table width="100%" style='padding:0;margin:0;' cellspacing='0' cellpadding='0' class="tableBorder">
				<tr>	
					<th width="30%"><p align="center">选择功能</p></th>
					<th width="70%"><p align="center">使用说明</p></th>
				</tr>
				<tr>
					<th>
						<p align="center">
							<input type="radio" id="Noiframe" name="Noiframe" value="a" <?php if($zbp->Config('FY_Noiframe')->Noiframe == 'a') echo 'checked'?> />方法一<br />
		<input type="radio" id="Noiframe" name="Noiframe" value="b" <?php if($zbp->Config('FY_Noiframe')->Noiframe == 'b') echo 'checked'?> />方法二
						</p>
					</th>
					<th><p align="center">方法一：使用X-Frame-Options防止网页被iframe；方法二：js的防御方案，别人在通过iframe框架引用你的网站网页时，浏览器会自动跳转到你的网站所引用的页面上。</p></th>
				</tr> 
			</table>
			<hr/>
			<p>
				<input type="submit" class="button" value="<?php echo $lang['msg']['submit']?>" />
			</p>
			<table width="100%" style='padding:0;margin:0;' cellspacing='0' cellpadding='0' class="tableBorder">
			<tr height="32"><td>应用作者：烽烟无限 主页：<a href="http://www.fengyan.cc" target="_blank">烽烟博客</a></td></tr>
			<tr height="32"><td>提供ZBlog企业模板、ZBlog淘宝客模板、ZBlog插件、ZBlog免费模板下载。
				承接ZBlog模板定制、ZBlog仿站、ZBlog模板修改、ZBlog插件定制等业务。</td></tr>
			<tr height="32"><td>建站技术交流群：99464245</td></tr>
	</table>
		</form>
  </div>
</div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>