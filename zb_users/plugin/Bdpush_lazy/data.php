<?php 
require '../../../zb_system/function/c_system_base.php';
$pwd = $zbp->Config('Bdpush_lazy')->pwd;
if(!isset($_GET['pwd'])){
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
}
$api = $zbp->Config('Bdpush_lazy')->bdapi;
if (isset($_GET['act']) && $_GET['act'] == 'kssl' && $_SERVER['REQUEST_METHOD']=="POST") {
$postData = file_get_contents('php://input'); 
$postData = trim(str_replace('urls=','', $postData));
$urls  = array();
$array = explode("\r\n", urldecode($postData));
$ajax = Network::Create(trim($zbp->Config('Bdpush_lazy')->networktype));
$ajax->open('POST', $api . '&type=daily');
$ajax->setTimeOuts(120, 120, 0, 0);	
$ajax->send(implode("\n", $array));
//$result = Bdpush_lazy_getData($api . '&type=daily',implode("\n", $array));
echo $ajax->responseText;
$data=json_decode($ajax->responseText,true);
exit(0);
}
if (!isset($_GET['page'])){
	echo '<input class="nums" type="hidden" value="1">';
	exit();
}elseif($_GET['page']==='NaN' || $_GET['page']==='0') {
	$page =1;
}else{
    $page = $_GET['page'];
}
$next = $page+1;
$_nums =isset($_GET['nums']) ? $_GET['nums'] :50;
$pagecount=ceil($zbp->cache->all_article_nums / $_nums);
$articles = Bdpush_lazy_sitemap($_nums,$page);
//$plink ="";
$plink1 ="";	
$urls  = array();
$ids  = array();
foreach ($articles as $article) {
	    $plink = $article->Url; 
	    $plink1 .= '<tr><td><span class="num">'.$article->ID.'</span></td><td><a target="_blank" href="'.$article->Url.'">'.$article->Url.'</a></td><td><span class="status"><span class="glyphicon glyphicon-status text-status"></span></span></td></tr>';
    array_push($urls ,$plink);
    @ob_flush();
    @flush();
}
//$result1 = $result = Bdpush_lazy_getData($api,implode("\n", $urls));

$ajax = Network::Create(trim($zbp->Config('Bdpush_lazy')->networktype));
$ajax->open('POST', $api);
$ajax->setTimeOuts(120, 120, 0, 0);	
$ajax->send(implode("\n", $urls));

$data=json_decode($ajax->responseText,true); 
$result1 = $result = $ajax->responseText;
if(isset($_GET['pwd']) && $_GET['pwd']==$pwd){
	echo $ajax->responseText;
}else{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title></title></head>
<link type="text/css" href="bootstrap.min.css" rel="stylesheet" />
<style>
@font-face{font-family:'Glyphicons Halflings';src:url(fonts/glyphicons-halflings-regular.eot);src:url(fonts/glyphicons-halflings-regular.eot?#iefix) format('embedded-opentype'),url(fonts/glyphicons-halflings-regular.woff2) format('woff2'),url(fonts/glyphicons-halflings-regular.woff) format('woff'),url(fonts/glyphicons-halflings-regular.ttf) format('truetype'),url(fonts/glyphicons-halflings-regular.svg#glyphicons_halflingsregular) format('svg')}
.glyphicon {
    position: relative;
    top: 1px;
    display: inline-block;
    font-family: 'Glyphicons Halflings';
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.glyphicon-status:before {
	<?php if(isset($data['success']) && $data['success']>0) { ?>
	content: "\e084";
	<?php }else{ ?>
    content: "\e083";
    <?php } ?>
}
.text-status{
	<?php if(isset($data['success']) && $data['success']>0) { ?>
	color: #3c763d;
	<?php }else{ ?>
	color:#a94442;
    <?php } ?>
}
table {
    font-size: 14px;
}
</style>
<body style="overflow: hidden; ">
<?php
if($page>$pagecount){
	echo '<div class="bs-example bs-example-bg-classes" data-example-id="contextual-backgrounds-helpers"><p class="bg-danger" style="padding: 15px;">工作完毕</p>
</div>';
}else{
?>
<div class="bs-example bs-example-bg-classes" data-example-id="contextual-backgrounds-helpers"><p class="bg-info" style="padding: 15px;">返回状态：<?php echo $result1; ?></p>
</div>
<div class="bs-example bs-example-bg-classes" data-example-id="contextual-backgrounds-helpers"><p class="bg-danger" style="padding: 15px;">下面的勾勾叉叉仅作参考，请以上面返回状态标准</p>
</div>
<div class="alert alert-warning" role="alert">（当前<?php echo $page; ?>页/共<?php echo $pagecount; ?>页）请不要关闭页面，<span id="endtime">3</span>秒后跳到下一页!</div>
	<div class="progress">
	  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo round($page/$pagecount*100); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($page/$pagecount*100)."%"; ?>;">
		<span><?php echo round($page/$pagecount*100)."％"; ?></span>
	  </div>
	</div><input class="nums" type="hidden" value="<?php echo $page; ?>">
<table class="table table-hover">
	<thead>
		<tr>
			<th>文章ID</th>
				<th>推送链接</th>
			<th style="width: 88px;">状态</th>
		</tr>
	</thead>
	<tbody>
	<?php echo $plink1; ?>
	</tbody>
</table>
<?php if(!isset($_GET['parent'])){ ?>
<script language="javascript" type="text/javascript">
<!--
//document.getElementById("loading").style.display="none";
var p = <?php echo $next; ?>;
var second=3;
var timer;
function change()
{
	second--;
	if(second>-1)
	{
		document.getElementById("endtime").innerHTML=second;
		timer = setTimeout('change()',1000);
	}
	else
	{
		clearTimeout(timer);
	}
}
timer = setTimeout('change()',1000); 
setTimeout('ourl()',3000*second);

function ourl()
{
	location.href='<?php echo $zbp->host .'zb_users/plugin/Bdpush_lazy/data.php?nums='.$_nums.'&page='; ?>'+p;
}
-->
</script>
<?php }} ?>
</body>
</html>
<?php } ?>