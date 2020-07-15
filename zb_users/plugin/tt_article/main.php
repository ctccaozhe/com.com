<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';

$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('tt_article')) {$zbp->ShowError(48);die();}
$blogtitle='批量文章管理配置';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
	<div class="divHeader"><?php echo $blogtitle;?></div>
	<div class="SubMenu">
	<a><span  ></span></a>
        <a href="http://www.tusay.net/design.html" target="_blank"><span class="m-right" >赞助</span></a>
        <a href="http://wpa.qq.com/msgrd?v=3&uin=2283276927&site=qq&menu=yes" target="_blank" ><span class="m-left" style="color:#F00">QQ联系帮助</span></a>
    </div>
	<div id="divMain2">
	<?php 
if(isset($_POST['Forum'])){
foreach($_POST['Forum'] as $key=>$val){
$zbp->Config('tt_article')->$key = $val;
}
$zbp->SaveConfig('tt_article');
$zbp->ShowHint('good');
}
	?>
	    <table width="100%" style='padding:0;margin:0;margin-bottom: 5px;' cellspacing='0' cellpadding='0' class="tableBorder">
	 <tr>
    <td><label>
	<p><strong style="color:#F00">温馨提示：</strong>涂涂研版 www.tusay.net QQ客服为：2283276927 客服群： 428358547 可以点击上面的 "QQ联系帮助" 按钮找到我。</p>
	</label></td>
	</tr>
	</table>
	<form id="form1" name="form1" method="post">  
    <table width="100%" style='padding:0;margin:0;' cellspacing='0' cellpadding='0' class="tableBorder">
		<tr>
			<th width="15%"><p align="center">配置名称</p></th>
			<th width="30%"><p align="center">配置内容</p></th>
			<th width='20%'><p align="center">配置说明</p></th>
		</tr>
	
	
		<tr>
			<td><label ><p align="center">填写你的批量文章管理页面每页显示的文章数量：</p></label></td>
			<td><p align="left"><input name="Forum[num]" style="width:250px;" type="text" value="<?php echo $zbp->Config('tt_article')->num;?>"></p></td>

			<td><p align="left">填写你的批量文章管理页面每页显示的文章数量，默认50</p></td>
			
		</tr>
		<tr>
			<td><label ><p align="center">文章管理是否显示浏览量：</p></label></td>
			<td><p align="left"><input name="Forum[view]" class="checkbox"  type="text" value="<?php echo $zbp->Config('tt_article')->view;?>"></p></td>

			<td><p align="left">文章管理是否显示浏览量，默认不显示</p></td>
			
		</tr>
	</table>
	<br />
   <input name="" type="Submit" class="button" value="保存"/>
    </form>		
	</div>
</div>
<script type="text/javascript">
ActiveTopMenu("topmenu_tt_article");
</script> 
<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>