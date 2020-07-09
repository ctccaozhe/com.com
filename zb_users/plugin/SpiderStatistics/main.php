<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('SpiderStatistics')) {$zbp->ShowError(48);die();}

$blogtitle='来访蜘蛛统计';
$act = '';
if ($_GET['act']){$act = $_GET['act'] == "" ? 'config' : $_GET['act'];}
require $blogpath . 'zb_system/admin/admin_header.php';
?>
<link rel="stylesheet" type="text/css" href="style.css"/>
<?php
require $blogpath . 'zb_system/admin/admin_top.php';
?>

<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle;?></div>
  <div class="SubMenu">
  <?php SpiderStatistics_SubMenu($act);?>
  <a href="https://me.alipay.com/zrcs" target="_blank"><span class="m-left" style="color:#F00">赞助</span></a>
  <a href="http://www.fengwensheng.com/" target="_blank"><span class="m-right">帮助</span></a>
  </div>
  <div id="divMain2">
<!--代码-->
<?php if ($act == 'config'){ ?>
<?php
	if(isset($_POST['viewconut'])) {
		$zbp->Config('SpiderStatistics')->viewconut = $_POST['viewconut'];
		$zbp->Config('SpiderStatistics')->spiders = $_POST['spiders'];
		$zbp->SaveConfig('SpiderStatistics');
		$zbp->SetHint('good');
		Redirect('./main.php?act=config');
	}
?>
	<form method="post">
		<table class="tb-set" width="100%">
			<tr>
				<td align="right"><b>显示篇数：</b></td>
				<td><input type="text" class="txt" name="viewconut" value="<?php echo $zbp->Config('SpiderStatistics')->viewconut; ?>" /></td>
			</tr>
			<tr>
				<td align="right"><b>蜘蛛列表：</b><br />格式：蜘蛛名称,显示名称。<br />多个用“|”分隔,如：Baiduspider,Baidu|Googlebot,Google。</td>
				<td><textarea class="txt txt-lar" name="spiders"><?php echo $zbp->Config('SpiderStatistics')->spiders; ?></textarea></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="submit" value="保存" /></td>
			</tr>
		</table>
	</form>

<?php } if ($act == 'spider') { 

	echo '<table border="1" class="tableFull tableBorder tableBorder-thcenter">';
	echo '<tr>
	<th> ID </th>
	<th> 蜘蛛名称 </th>
	<th> 蜘蛛IP </th>
	<th> 抓取时间 </th>
	<th> 抓取地址 </th>
	<th> 抓取状态 </th>
	</tr>';

	$p = new Pagebar('{%host%}zb_users/plugin/SpiderStatistics/main.php?act=spider{&page=%page%}',false);
	$p->PageCount = $zbp->Config('SpiderStatistics')->viewconut;
	$p->PageNow = (int)GetVars('page','GET')==0?1:(int)GetVars('page','GET');
	$p->PageBarCount = $zbp->pagebarcount;

	$select = array('*');
	$where = array();
	$order = array('Spider_ID'=>'DESC');
	$limit = array(($p->PageNow-1) * $p->PageCount,$p->PageCount);
	$option = array('pagebar'=>$p);

	$sql = $zbp->db->sql->Select($zbp->table['SpiderStatistics'], $select, $where, $order, $limit, $option);
	$array = $zbp->GetListType('SpiderStatistics',$sql);

	foreach ($array as $key => $value) {
		echo '<tr>';
		echo '<td class="td5">' . $value->ID .  '</td>';
		echo '<td class="td5">' . $value->Name . '</td>';
		echo '<td class="td5">' . $value->IP . '</td>';
		echo '<td class="td20">' .$value->Time() . '</td>';
		echo '<td><a href="'.$value->Url.'" target="_blank">' .$value->Url . '</a></td>';
		echo '<td class="td5">' . $value->Status .  '</td>';
		echo '</tr>';
	}

	echo '</table>';
	echo '<hr/><p class="pagebar">';

	foreach ($p->buttons as $key => $value) {
		echo '<a href="'. $value .'">' . $key . '</a>&nbsp;&nbsp;' ;
	}

	echo '</p>';

} ?>

  </div>
</div>

<script type="text/javascript">
	AddHeaderIcon("<?php echo $bloghost . 'zb_users/plugin/SpiderStatistics/logo.png';?>");
	ActiveTopMenu("topmenu_SpiderStatistics");
</script>
<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>