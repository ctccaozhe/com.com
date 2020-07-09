<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('mo_sitemap')) {$zbp->ShowError(48);die();}

$blogtitle='sitemap网站地图生成器';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
	<div class="divHeader"><?php echo $blogtitle;?></div>
	<div class="SubMenu">
	</div>
	 <div id="divMain2">
	 	<div><b style="color: red;">更多高级功能，请购买高级版网站地图生成器（<a href="<?php echo $zbp->host;?>zb_users/plugin/AppCentre/main.php?id=2422">sitemap网站地图生成器-高级版</a>）</b></div>
		<?php 
		if(count($_POST)>0){
			if (function_exists('CheckIsRefererValid')) CheckIsRefererValid();
			foreach($_POST as $key=>$value){
				if($key == 'csrfToken'){continue;}
				$zbp->Config('mo_sitemap')->$key = $value;
			}
			$zbp->SaveConfig('mo_sitemap');
			if($zbp->Config('mo_sitemap')->SitemapXml == '1'){mo_sitemap_MakeSiteMapXml();$zbp->SetHint('good','Xml文件生成成功');}
			if($zbp->Config('mo_sitemap')->SitemapTxt == '1'){mo_sitemap_MakeSiteMapTxt();$zbp->SetHint('good','TXT文件生成成功');}
			if($zbp->Config('mo_sitemap')->SitemapHtml == '1'){mo_sitemap_MakeSiteMapHtml();$zbp->SetHint('good','Html文件生成成功');}
			$zbp->SetHint('good','保存成功');
			Redirect('./main.php');
		}?>
			<form id="form" name="form" method="post">
				<?php if (function_exists('CheckIsRefererValid')) {
					echo '<input type="hidden" name="csrfToken" value="'.$zbp->GetCSRFToken().'">';
				}?>
				<div>
					<p><b style="color: red;font-size: 18px">说明：</b></p>
					<p>生成的sitemap索引文件：<a href="<?php echo $zbp->host;?>sitemaps.xml" target="_blank"><?php echo $zbp->host;?>sitemaps.xml</a></p>
					<p>单个sitemap文件请进入索引文件查看</p>
					<p><b style="color: red;">点击保存后开始生成</b></p>
					<p>只有XML会生成索引文件，TXT和HTML不会生成索引文件，因为搜索引擎可能不认识</p>
					<p>所有生成的文件名称格式“sitemap_类型_页数”</p>
					<p>例：文章sitemap_article_1.xml；sitemap_article_1.txt，sitemap_article_1.html。后面数字为生成的页数，以此类推</p>
				</div>
				<div>
					<p>
						<span>生成XML文件</span>
						<span><input type="text" name="SitemapXml" value="<?php echo $zbp->Config('mo_sitemap')->SitemapXml;?>" class="checkbox"/></span>
					</p>
					<p></p>
				</div>
				<div>
					<p>
						<span>生成TXT文件</span>
						<span><input type="text" name="SitemapTxt" value="<?php echo $zbp->Config('mo_sitemap')->SitemapTxt;?>" class="checkbox"/></span>
					</p>
					<p></p>
				</div>
				<div>
					<p>
						<span>生成HTML文件</span>
						<span><input type="text" name="SitemapHtml" value="<?php echo $zbp->Config('mo_sitemap')->SitemapHtml;?>" class="checkbox"/></span>
					</p>
					<p></p>
				</div>
				<div>
					<p>
						<span>加入文章&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="SitemapArticle" value="<?php echo $zbp->Config('mo_sitemap')->SitemapArticle;?>" class="checkbox"/></span>
						<span>
							权重：<?php zbpform::select('SitemapArticleRank',array('1.0'=>'1.0','0.9'=>'0.9','0.8'=>'0.8','0.7'=>'0.7','0.6'=>'0.6','0.5'=>'0.5','0.4'=>'0.4','0.3'=>'0.3','0.2'=>'0.2','0.1'=>'0.1'),$zbp->Config('mo_sitemap')->SitemapArticleRank);?>&nbsp;&nbsp;&nbsp;&nbsp;<br>
							频率：<?php zbpform::select('SitemapArticleFreq',array('Daily'=>'Daily','weekly'=>'weekly','monthly'=>'monthly'),$zbp->Config('mo_sitemap')->SitemapArticleFreq);?>
							</span>
					</p>
					<p></p>
				</div>
				<div>
					<p>
						<span>加入页面&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="SitemapPage" value="<?php echo $zbp->Config('mo_sitemap')->SitemapPage;?>" class="checkbox"/></span>
						<span>
							权重：<?php zbpform::select('SitemapPageRank',array('1.0'=>'1.0','0.9'=>'0.9','0.8'=>'0.8','0.7'=>'0.7','0.6'=>'0.6','0.5'=>'0.5','0.4'=>'0.4','0.3'=>'0.3','0.2'=>'0.2','0.1'=>'0.1'),$zbp->Config('mo_sitemap')->SitemapPageRank);?>&nbsp;&nbsp;&nbsp;&nbsp;<br>
							频率：<?php zbpform::select('SitemapPageFreq',array('Daily'=>'Daily','weekly'=>'weekly','monthly'=>'monthly'),$zbp->Config('mo_sitemap')->SitemapPageFreq);?>
							</span>
					</p>
					<p></p>
				</div>
				<div>
					<p>
						<span>加入分类&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="SitemapCate" value="<?php echo $zbp->Config('mo_sitemap')->SitemapCate;?>" class="checkbox"/></span>
						<span>
							权重：<?php zbpform::select('SitemapCateRank',array('1.0'=>'1.0','0.9'=>'0.9','0.8'=>'0.8','0.7'=>'0.7','0.6'=>'0.6','0.5'=>'0.5','0.4'=>'0.4','0.3'=>'0.3','0.2'=>'0.2','0.1'=>'0.1'),$zbp->Config('mo_sitemap')->SitemapCateRank);?>&nbsp;&nbsp;&nbsp;&nbsp;<br>
							频率：<?php zbpform::select('SitemapCateFreq',array('Daily'=>'Daily','weekly'=>'weekly','monthly'=>'monthly'),$zbp->Config('mo_sitemap')->SitemapCateFreq);?>
							</span>
					</p>
					<p></p>
				</div>
				<div>
					<p>
						<span>加入标签&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="SitemapTag" value="<?php echo $zbp->Config('mo_sitemap')->SitemapTag;?>" class="checkbox"/></span>
						<span>
							权重：<?php zbpform::select('SitemapTagRank',array('1.0'=>'1.0','0.9'=>'0.9','0.8'=>'0.8','0.7'=>'0.7','0.6'=>'0.6','0.5'=>'0.5','0.4'=>'0.4','0.3'=>'0.3','0.2'=>'0.2','0.1'=>'0.1'),$zbp->Config('mo_sitemap')->SitemapTagRank);?>&nbsp;&nbsp;&nbsp;&nbsp;<br>
							频率：<?php zbpform::select('SitemapTagFreq',array('Daily'=>'Daily','weekly'=>'weekly','monthly'=>'monthly'),$zbp->Config('mo_sitemap')->SitemapTagFreq);?>
						</span>
					</p>
					<p></p>
				</div>
				<div>
					<p>
						<span>每页生成链接最大数量</span>
						<span><input type="text" name="UrlNum" value="<?php echo $zbp->Config('mo_sitemap')->UrlNum;?>" placeholder="请输入正整数"></span>
					</p>
					<p>每个文章、分类、标签的sitemap文件所拥有的url最大数量，默认5000<br><span style="color: red;"><b>请根据服务器配置酌情填写，已知1C2G Linux服务器生成单个文件20000条链接约5s-20s，<br>请注意PHP超时设置<br>请注意PHP脚本内存限制，<br>文件生成会在一个PHP线程下生成，多个文件则需要单个时间*数量，后续由于当前服务器状态时间可能会增加，请谨慎设置，否则可能不能生成完毕<br>设置不合理都将可能报错40X或50X</b></span></p>
				</div>
				<div>
					<p>
						<span>最大页数</span>
						<span><input type="text" name="UrlPage" value="<?php echo $zbp->Config('mo_sitemap')->UrlPage;?>" placeholder="请输入正整数"></span>
					</p>
					<p>每个类型sitemap最大个数,默认5个<br>例：<?php echo $zbp->host;?>sitemap_article_1.xml<br>例：<?php echo $zbp->host;?>sitemap_article_5.xml<br><span style="color: red;"><b>请根据服务器配置酌情填写，已知1C2G Linux服务器生成单个文件20000条链接约5s-20s，<br>请注意PHP超时设置<br>请注意PHP脚本内存限制，<br>文件生成会在一个PHP线程下生成，多个文件则需要单个时间*数量，后续由于当前服务器状态时间可能会增加，请谨慎设置，否则可能不能生成完毕<br>设置不合理都将可能报错40X或50X</b></span></p>
				</div>
				<input type="Submit" class="button" value="保存"/>
			</form>
	</div>
</div>

<?php
echo '<link href="'.$zbp->host.'zb_users/plugin/mo_sitemap/style.css" rel="stylesheet" type="text/css" />'."\r\n";
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>