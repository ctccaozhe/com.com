<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('Watermark')) {$zbp->ShowError(48);die();}
if ($_POST) {
	CheckIsRefererValid();
	foreach ($_POST as $type=>$crv){
		if ($type == 'csrfToken' || $type == 'submit') continue;
		$zbp->Config('Watermark')->$type = $crv;       			  
	}          		
	$zbp->SaveConfig('Watermark');     					  
}
$blogtitle='图片水印配置';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<!--代码-->
<?php
if(isset($_POST['waterPos'])){
	$zbp->Config('Watermark')->waterPos=$_POST['waterPos'];
	$zbp->Config('Watermark')->waterText=$_POST['waterText'];
	$zbp->Config('Watermark')->textFont=$_POST['textFont'];
	$zbp->Config('Watermark')->textColor=$_POST['textColor'];
	$zbp->Config('Watermark')->WatermarkType=$_POST['WatermarkType'];
	$zbp->SaveConfig('Watermark');
	$zbp->ShowHint('good');
	//Redirect('./main.php');
}
?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle;?></div>
  <div class="SubMenu">
		<a href="" ><span class="m-left">配置首页</span></a>
        <a href="https://www.talklee.com/" target="_blank"><span class="m-right">帮助</span></a>
  </div>
  <div id="divMain2">
<!--代码-->
	<form enctype="multipart/form-data" method="post" action="save.php?type=Watermark"> 
		<table width="100%" style='padding:0;margin:0;' cellspacing='0' cellpadding='0' class="tableBorder">
			<tr>
				<td width="15%"><label for="Watermark.png"><p align="center">上传水印图片（仅限.png）</p></label></td>
				<td width="50%"><p align="center"><img src="/zb_users/plugin/Watermark/img/Watermark.png" alt="" style="max-height:30px;margin-bottom:-9px;margin-right:11px;"><input name="Watermark.png" type="file"/></p></td>
				<td width="25%"><p align="center"><input name="" type="Submit" class="button" value="保存"/></p></td>
			</tr>
		</table>
	</form>
	<form id="form1" name="form1" method="post">
		<?php if (function_exists('CheckIsRefererValid')) {echo '<input type="hidden" name="csrfToken" value="' . $zbp->GetCSRFToken() . '">';}?>
    <table width="100%" style='padding:0;margin:0;' cellspacing='0' cellpadding='0' class="tableBorder">
		<tr>
			<th width="15%"><p align="center">配置名称</p></th>
			<th width="85%"><p align="center">配置内容</p></th>
		</tr>
		<tr>
			<td><label for="waterPos"><p align="center">水印位置</p></label></td>
			<td>
				<table width="100%" border="0">
					<tr>
					<td><label>
						<input type="radio" name="waterPos" id="radio" value="0" <?php if($zbp->Config('Watermark')->waterPos==0):?>checked="checked"<?php endif;?> />
					随机</label></td>
					<td><label>
						<input type="radio" name="waterPos" id="radio" value="1" <?php if($zbp->Config('Watermark')->waterPos==1):?>checked="checked"<?php endif;?> />
					左上</label></td>
					<td><label>
						<input type="radio" name="waterPos" id="radio" value="2" <?php if($zbp->Config('Watermark')->waterPos==2):?>checked="checked"<?php endif;?> />
					中上</label></td>
					<td><label>
						<input type="radio" name="waterPos" id="radio" value="3" <?php if($zbp->Config('Watermark')->waterPos==3):?>checked="checked"<?php endif;?> />
					右上</label></td>
					<td><label>
						<input type="radio" name="waterPos" id="radio" value="4" <?php if($zbp->Config('Watermark')->waterPos==4):?>checked="checked"<?php endif;?> />
					左中</label></td>
					<td><label>
						<input type="radio" name="waterPos" id="radio" value="5" <?php if($zbp->Config('Watermark')->waterPos==5):?>checked="checked"<?php endif;?> />
					中间</label></td>
					<td><label>
						<input type="radio" name="waterPos" id="radio" value="6" <?php if($zbp->Config('Watermark')->waterPos==6):?>checked="checked"<?php endif;?> />
					右中</label></td>
					<td><label>
						<input type="radio" name="waterPos" id="radio" value="7" <?php if($zbp->Config('Watermark')->waterPos==7):?>checked="checked"<?php endif;?> />
					左下</label></td>
					<td><label>
						<input type="radio" name="waterPos" id="radio" value="8" <?php if($zbp->Config('Watermark')->waterPos==8):?>checked="checked"<?php endif;?> />
					中下</label></td>
					<td><label>
						<input type="radio" name="waterPos" id="radio" value="9" <?php if($zbp->Config('Watermark')->waterPos==9):?>checked="checked"<?php endif;?>  />
					右下</label></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td><label for="waterText"><p align="center">水印文字</p></label></td>
			<td><p align="left"><textarea name="waterText" type="text" id="waterText" style="width:98%;height:25px;"><?php echo htmlspecialchars($zbp->Config('Watermark')->waterText);?></textarea></p></td>
		</tr>
		<tr>
			<td><label for="textFont"><p align="center">文字大小</p></label></td>
			<td><p align="left"><textarea name="textFont" type="text" id="textFont" style="width:98%;height:25px;"><?php echo htmlspecialchars($zbp->Config('Watermark')->textFont);?></textarea></p></td>
		</tr>
		<tr>
			<td><label for="textColor"><p align="center">文字颜色</p></label></td>
			<td><p align="left"><textarea name="textColor" type="text" id="textColor" style="width:98%;height:25px;"><?php echo htmlspecialchars($zbp->Config('Watermark')->textColor);?></textarea></p></td>
		</tr>
		<tr>
			<td><label for="WatermarkType"><p align="center">水印类型</p></label></td>
			<td><label><input type="radio" name="WatermarkType" value="text" <?php if($zbp->Config('Watermark')->WatermarkType == 'text') echo 'checked'; ?> />文字水印</label> <label><input type="radio" name="WatermarkType" value="image" <?php if($zbp->Config('Watermark')->WatermarkType == 'image') echo 'checked'; ?> />图片水印</label></td>
		</tr>
	</table><input type="hidden" name="csrfToken" value="<?php echo $zbp->GetCSRFToken();?>">
	<br />
		<input name="" type="Submit" class="button" value="保存"/>
	</form>
  </div>
</div>
<script type="text/javascript">AddHeaderIcon("<?php echo $bloghost . 'zb_users/plugin/Watermark/logo.png';?>");</script>
<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>