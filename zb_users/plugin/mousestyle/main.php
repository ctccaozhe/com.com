<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('mousestyle')) {$zbp->ShowError(48);die();}

$blogtitle='mousestyle';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle;?></div>
  <div class="SubMenu">
		<a href="" ><span class="m-left">配置首页</span></a>
        <a href="http://blog.luckyhai.com/22.html" target="_blank"><span class="m-left">帮助</span></a>
  </div>
  <div id="divMain2">

	<form enctype="multipart/form-data" method="post" action="save.php?type=body">  
		<table width="100%" style='padding:0;margin:0;' cellspacing='0' cellpadding='0' class="tableBorder">
			<tr>
				<td width="15%"><label for="body.cur"><p align="center">网页主体鼠标样式</p></label></td>
				<td width="50%"><p align="center"><input name="body.cur" type="file"/></p></td>
				<td width="25%"><p align="center"><input name="" type="Submit" class="button" value="保存"/></p></td>
			</tr>
		</table>
	</form>
	
	<form enctype="multipart/form-data" method="post" action="save.php?type=a">  
		<table width="100%" style='padding:0;margin:0;' cellspacing='0' cellpadding='0' class="tableBorder">
			<tr>
				<td width="15%"><label for="a.cur"><p align="center">网页A标签鼠标样式</p></label></td>
				<td width="50%"><p align="center"><input name="a.cur" type="file"/></p></td>
				<td width="25%"><p align="center"><input name="" type="Submit" class="button" value="保存"/></p></td>
			</tr>
		</table>
	</form>

	<p style="font-size:22px;color:#FD0606;line-height:30px;" >说明：</p>
	<p style="font-size:16px;">1、可以直接使用插件自带的鼠标样式，当然，也可以更换自己所喜欢的鼠标样式。</p>
	<p style="font-size:16px;">2、需要更换鼠标样式的，自行百度搜“鼠标指针”，下载后上传即可，注意是.cur后缀的鼠标样式。</p>
	<p style="font-size:16px;">3、关于ani鼠标指针；除了IE游览器以外，别的都不支持ani鼠标指针，所以这里就不更新了。反而cur鼠标指针的最为通用的。</p>
	<p style="font-size:16px;">4、在IE游览器下，鼠标放到A标签上面，会闪动一下。属于正常情况。（话说为嘛IE中Cursor会和Title、Alt冲突呢？求大神指教！）</p>
	
  </div>
</div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>