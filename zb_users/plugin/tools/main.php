<?php
require '../../../zb_system/function/c_system_base.php';

require '../../../zb_system/function/c_system_admin.php';

$zbp->Load();

$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}

if (!$zbp->CheckPlugin('tools')) {$zbp->ShowError(48);die();}

$blogtitle='蜘蛛地图标准协议';

if(count($_POST)>0){
//首页标签
//首页权重为1，推荐
$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><urlset />');
$xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
//XML文件需以utf-8编码
$url = $xml->addChild('url');
//xml标签url
$url->addChild('loc', $zbp->host);
//网站url
$url->addChild('lastmod',date('Y-m-d'));
//更新时间	
$url->addChild('changefreq', 'Daily');
//抓取频率
$url->addChild('priority', '1.0');
//抓取权重
//分类对应标签可参考首页写法
//分类页权重为0.6，推荐
if(GetVars('category')){
	foreach ($zbp->categorys as $c) {
		$url = $xml->addChild('url');
		$url->addChild('loc', $c->Url);
		$url->addChild('lastmod',date('Y-m-d'));	
        $url->addChild('changefreq', 'weekly');
		$url->addChild('priority', '0.6');
	}
}
//日志页对应标签可参考首页写法
//日志页权重为0.8，推荐
if(GetVars('article')){
	$array=$zbp->GetArticleList(
		null,
		array(array('=','log_Status',0)),
		null,
		null,
		null,
		false
		);

	foreach ($array as $key => $value) {
		$url = $xml->addChild('url');
		$url->addChild('loc', $value->Url);
		$url->addChild('lastmod',date('Y-m-d'));	
        $url->addChild('changefreq', 'Daily');
        $url->addChild('priority', '0.8');
	}
}

//独立页对应标签可参考首页写法
//独立页权重为0.8，推荐
if(GetVars('page')){
	$array=$zbp->GetPageList(
		null,
		array(array('=','log_Status',0)),
		null,
		null,
		null
		);

	foreach ($array as $key => $value) {
		$url = $xml->addChild('url');
		$url->addChild('loc', $value->Url);
		$url->addChild('lastmod',date('Y-m-d'));	
        $url->addChild('changefreq', 'Daily');
        $url->addChild('priority', '0.8');
	}
}
//标签页对应标签可参考首页写法
//标签页权重为0.6，推荐
if(GetVars('tag')){
	$array=$zbp->GetTagList();

	foreach ($array as $key => $value) {
		$url = $xml->addChild('url');
		$url->addChild('loc', $value->Url);
		$url->addChild('lastmod',date('Y-m-d'));	
        $url->addChild('changefreq', 'monthly');
        $url->addChild('priority', '0.6');
	}
}


file_put_contents($zbp->path . 'sitemap.xml',$xml->asXML());


	$zbp->SetHint('good');
	Redirect($_SERVER["HTTP_REFERER"]);
}

require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';

?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle;?></div>
  <div class="SubMenu">

  </div>
  <div id="divMain2">
<form id="edit" name="edit" method="post" action="#">
<input id="reset" name="reset" type="hidden" value="" />
<table border="1" class="tableFull tableBorder tableBorder-thcenter">

<tr>
			<th width="33%">
			<p align="center">启用首页（必选）<br>
			<input type="text" class="checkbox" value="1" />
			</p></th>
			<th width="33%">
			<p align="center">启用分类（可选）<br>
			<input type="text" name="category" class="checkbox" value="1" />
			</p></th>
			<th width="33%">
			<p align="center">启用文章（必选）<br>
			<input type="text" name="article" class="checkbox" value="1" />
			</p></th>
</tr>
<tr>
			<th width="33%">
			<p align="center">启用页面（可选）<br>
			<input type="text" name="page" class="checkbox" value="1" />
			</p></th>
			<th width="33%">
			<p align="center">启用标签（可选）<br>
			<input type="text" name="tag" class="checkbox" value="0" />
			</p></th>
			<th width="33%">
			<p align="center">
			<input type="submit" class="button" value="生成文件" />
			</p></th>
</tr>

</table>

<table border="1" class="tableFull tableBorder">
<tr>
	<th class="td20">蜘蛛地图标准协议说明：</td>
	<th><p>本sitemap地图按照标准协议编写，所以搜索引擎通吃，权重、更新频率、时间都会自动生成，严格按照优化标准生成。</p></td>
</tr>
<tr>
	<th class="td20">蜘蛛地图标准协议地址：</td>
	<th><p><?php echo $zbp->host;?>sitemap.xml</p></td>
</tr>
<tr>
	<td class="td20">向Baidu提交蜘蛛地图：</td>
	<td><p><a href="http://zhanzhang.baidu.com/sitemap">http://zhanzhang.baidu.com/sitemap</a></p></td>
</tr>
<tr>
	<td class="td20">向Google提交蜘蛛地图：</td>
	<td><p><a href="http://www.google.com/webmasters">http://www.google.com/webmasters</a></p></td>
</tr>
<tr>
	<td class="td20">向360提交蜘蛛地图：</td>
	<td><p><a href="http://zhanzhang.so.com/?m=Sitemap">http://zhanzhang.so.com/?m=Sitemap</a></p></td>
</tr>
</table>
	  	  
	  
</form>
	<script type="text/javascript">ActiveLeftMenu("aPluginMng");</script>
	<script type="text/javascript">AddHeaderIcon("<?php echo $bloghost . 'zb_users/plugin/tools/logo.png';?>");</script>	
  </div>
</div>


<?php
require $blogpath . 'zb_system/admin/admin_footer.php';

RunTime();
?>