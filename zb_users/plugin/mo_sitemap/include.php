<?php
#注册插件
RegisterPlugin("mo_sitemap","ActivePlugin_mo_sitemap");

function ActivePlugin_mo_sitemap() {}
function mo_sitemap_MakeSiteMapHtml(){
	global $zbp;
	$htmls=file_get_contents(dirname(__FILE__).'/sitemap.dat');
	$htmls=str_replace('{网站名称}',$zbp->name,$htmls);
	$htmls=str_replace('{host}',$zbp->host,$htmls);
	$htmls=str_replace('{time}',date('Y-m-d H:i:s',time()),$htmls);
	$maxnum = ($zbp->Config('mo_sitemap')->UrlNum !='')?$zbp->Config('mo_sitemap')->UrlNum:5000;
	$maxpage = ($zbp->Config('mo_sitemap')->UrlPage != '')?$zbp->Config('mo_sitemap')->UrlPage:5;

	//文章0.8
	if($zbp->Config('mo_sitemap')->SitemapArticle == '1'){
		$w=array();$o=array();$l=array();$t='';
		$w[] = array('=','log_Status',0);
		$w[] = array('=','log_Type',0);
		$o = array('log_PostTime'=>'DESC');
		for ($i=0; $i < $maxpage; $i++) { 
			$l = array(($maxnum*$i),$maxnum);
			$array=$zbp->GetPostList('*',$w,$o,$l);
			$an = count($array);
			if($an>0){
				$html = $htmls;
				foreach($array as $key => $v){
					$t .= '<tr><td><a href="'.$v->Url.'">'.$v->Title.'</a></td></tr>'."\r\n";
				}
				$html=str_replace('{content}',$t,$html);
				file_put_contents($zbp->path.'sitemap_article_'.($i+1).'.html',$html);
			}
			unset($array);
			$t='';
			if($an<$maxnum){break;}
		}unset($i);
	}
	//页面0.8
	if($zbp->Config('mo_sitemap')->SitemapPage == '1'){
		$w=array();$o=array();$l=array();$t = '';
		$w[] = array('=','log_Status',0);
		$w[] = array('=','log_Type',1);
		$o = array('log_PostTime'=>'DESC');
		for ($i=0; $i < $maxpage; $i++) {
			$l = array(($maxnum*$i),$maxnum);
			$array=$zbp->GetPostList('*',$w,$o,$l);
			$an = count($array);
			if($an>0){
				$html = $htmls;
				foreach($array as $key => $v){
					$t .= '<tr><td><a href="'.$v->Url.'">'.$v->Title.'</a></td></tr>'."\r\n";
				}
				$html=str_replace('{content}',$t,$html);
				file_put_contents($zbp->path.'sitemap_page_'.($i+1).'.html',$html);
			}
			unset($array);
			$t='';
			if($an<$maxnum){break;}
		}unset($i);
	}
	//分类0.6
	if($zbp->Config('mo_sitemap')->SitemapCate == '1'){
		$w=array();$o=array();$l=array();$t='';
		for ($i=0; $i < $maxpage; $i++) {
			$l = array(($maxnum*$i),$maxnum);
			$array=$zbp->GetCategoryList('*',$w,$o,$l);
			$an = count($array);
			if($an>0){
				$html = $htmls;
				foreach($array as $key => $v){
					$t .= '<tr><td><a href="'.$v->Url.'">'.$v->Name.'</a></td></tr>'."\r\n";
				}
				$html=str_replace('{content}',$t,$html);
				file_put_contents($zbp->path.'sitemap_cate_'.($i+1).'.html',$html);
			}
			unset($array);
			$t='';
			if($an<$maxnum){break;}
		}unset($i);
	}
	//标签0.6
	if($zbp->Config('mo_sitemap')->SitemapTag == '1'){
		$w=array();$o=array();$l=array();$t='';
		for ($i=0; $i < $maxpage; $i++) {
			$l = array(($maxnum*$i),$maxnum);
			$array=$zbp->GetTagList('*',$w,$o,$l);
			$an = count($array);
			if($an>0){
				$html = $htmls;
				foreach($array as $key => $v){
					$t .= '<tr><td><a href="'.$v->Url.'">'.$v->Name.'</a></td></tr>'."\r\n";
				}
				$html=str_replace('{content}',$t,$html);
				file_put_contents($zbp->path.'sitemap_tag_'.($i+1).'.html',$html);
			}
			unset($array);
			$t='';
			if($an<$maxnum){break;}
		}unset($i);
	}


	// $html=str_replace('{articles}',$html_Articles,$html);
	// file_put_contents($zbp->path.'sitemaps.html',$html);
}
function mo_sitemap_MakeSiteMapTxt(){
	global $zbp;
	$maxnum = ($zbp->Config('mo_sitemap')->UrlNum !='')?$zbp->Config('mo_sitemap')->UrlNum:5000;
	$maxpage = ($zbp->Config('mo_sitemap')->UrlPage != '')?$zbp->Config('mo_sitemap')->UrlPage:5;
	//文章0.8
	if($zbp->Config('mo_sitemap')->SitemapArticle == '1'){
		$w=array();$o=array();$l=array();$t=$zbp->host."\r\n";
		$w[] = array('=','log_Status',0);
		$w[] = array('=','log_Type',0);
		$o = array('log_PostTime'=>'DESC');
		for ($i=0; $i < $maxpage; $i++) { 
			$l = array(($maxnum*$i),$maxnum);
			$array=$zbp->GetPostList('*',$w,$o,$l);
			$an = count($array);
			if($an>0){
				foreach($array as $key => $v){
					$t .= $v->Url."\r\n";
				}
				$file = fopen(($zbp->path . 'sitemap_article_'.($i+1).'.txt'),"w")or die("Unable to open file!");
				fwrite($file, $t);
				fclose($file);
				unset($file);
			}
			unset($t);
			if($an<$maxnum){break;}
			$t = '';
		}unset($i);
	}
	//页面0.8
	if($zbp->Config('mo_sitemap')->SitemapPage == '1'){
		$w=array();$o=array();$l=array();$t=$zbp->host."\r\n";
		$w[] = array('=','log_Status',0);
		$w[] = array('=','log_Type',1);
		$o = array('log_PostTime'=>'DESC');
		for ($i=0; $i < $maxpage; $i++) {
			$l = array(($maxnum*$i),$maxnum);
			$array=$zbp->GetPostList('*',$w,$o,$l);
			$an = count($array);
			if($an>0){
				foreach($array as $key => $v){
					$t .= $v->Url."\r\n";
				}
				$file = fopen(($zbp->path . 'sitemap_page_'.($i+1).'.txt'),"w")or die("Unable to open file!");
				fwrite($file, $t);
				fclose($file);
				unset($file);
			}
			unset($t);
			if($an<$maxnum){break;}
			$t = '';
		}unset($i);
	}
	//分类0.6
	if($zbp->Config('mo_sitemap')->SitemapCate == '1'){
		$w=array();$o=array();$l=array();$t=$zbp->host."\r\n";
		for ($i=0; $i < $maxpage; $i++) {
			$l = array(($maxnum*$i),$maxnum);
			$array=$zbp->GetCategoryList('*',$w,$o,$l);
			$an = count($array);
			if($an>0){
				foreach($array as $key => $v){
					$t .= $v->Url."\r\n";
				}
				$file = fopen(($zbp->path . 'sitemap_cate_'.($i+1).'.txt'),"w")or die("Unable to open file!");
				fwrite($file, $t);
				fclose($file);
				unset($file);
			}
			unset($t);
			if($an<$maxnum){break;}
			$t = '';
		}unset($i);
	}
	//标签0.6
	if($zbp->Config('mo_sitemap')->SitemapTag == '1'){
		$w=array();$o=array();$l=array();$t=$zbp->host."\r\n";
		for ($i=0; $i < $maxpage; $i++) {
			$l = array(($maxnum*$i),$maxnum);
			$array=$zbp->GetTagList('*',$w,$o,$l);
			$an = count($array);
			if($an>0){
				foreach($array as $key => $v){
					$t .= $v->Url."\r\n";
				}
				$file = fopen(($zbp->path . 'sitemap_tag_'.($i+1).'.txt'),"w")or die("Unable to open file!");
				fwrite($file, $t);
				fclose($file);
				unset($file);
			}
			unset($t);
			if($an<$maxnum){break;}
			$t = '';
		}unset($i);
	}
}
function mo_sitemap_MakeSiteMapXml(){
	global $zbp;$t='';
	$t .= '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
	$t .= '	<sitemapindex>'."\r\n";
	$maxnum = ($zbp->Config('mo_sitemap')->UrlNum !='')?$zbp->Config('mo_sitemap')->UrlNum:5000;
	$maxpage = ($zbp->Config('mo_sitemap')->UrlPage != '')?$zbp->Config('mo_sitemap')->UrlPage:5;
	//文章0.8
	if($zbp->Config('mo_sitemap')->SitemapArticle == '1'){
		$w=array();$o=array();$l=array();
		$w[] = array('=','log_Status',0);
		$w[] = array('=','log_Type',0);
		$o = array('log_PostTime'=>'DESC');
		for ($i=0; $i < $maxpage; $i++) { 
			$l = array(($maxnum*$i),$maxnum);
			$array=$zbp->GetPostList('*',$w,$o,$l);
			$an = count($array);
			if($an>0){
				$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><urlset />');
				//首页
				$url = $xml->addChild('url');//xml标签url
				$url->addChild('loc', $zbp->host);//网站url
				$url->addChild('lastmod',date('Y-m-d'));//更新时间	
				$url->addChild('changefreq', 'Daily');//抓取频率
				$url->addChild('priority', '1.0');//抓取权重
				foreach($array as $key => $v){
					$url = $xml->addChild('url');
					$url->addChild('loc', $v->Url);
					$url->addChild('lastmod',date('Y-m-d',$v->PostTime));	
					$url->addChild('changefreq', $zbp->Config('mo_sitemap')->SitemapArticleFreq);
					$url->addChild('priority', $zbp->Config('mo_sitemap')->SitemapArticleRank);
				}
				file_put_contents($zbp->path . 'sitemap_article_'.($i+1).'.xml',$xml->asXML());
				$t .= '		<sitemap>'."\r\n";
				$t .= '			<loc>'.$zbp->host . 'sitemap_article_'.($i+1).'.xml</loc>'."\r\n";
				$t .= '			<lastmod>'.date('Y-m-d').'</lastmod>'."\r\n";
				$t .= '		</sitemap>'."\r\n";
				unset($xml);
			}
			unset($array);
			if($an<$maxnum){break;}
		}unset($i);
	}
	//页面0.8
	if($zbp->Config('mo_sitemap')->SitemapPage == '1'){
		$w=array();$o=array();$l=array();
		$w[] = array('=','log_Status',0);
		$w[] = array('=','log_Type',1);
		$o = array('log_PostTime'=>'DESC');
		for ($i=0; $i < $maxpage; $i++) {
			$l = array(($maxnum*$i),$maxnum);
			$array=$zbp->GetPostList('*',$w,$o,$l);
			$an = count($array);
			if($an>0){
				$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><urlset />');
				//首页
				$url = $xml->addChild('url');//xml标签url
				$url->addChild('loc', $zbp->host);//网站url
				$url->addChild('lastmod',date('Y-m-d'));//更新时间	
				$url->addChild('changefreq', 'Daily');//抓取频率
				$url->addChild('priority', '1.0');//抓取权重
				foreach($array as $key => $v){
					$url = $xml->addChild('url');
					$url->addChild('loc', $v->Url);
					$url->addChild('lastmod',date('Y-m-d',$v->PostTime));	
					$url->addChild('changefreq', $zbp->Config('mo_sitemap')->SitemapPageFreq);
					$url->addChild('priority', $zbp->Config('mo_sitemap')->SitemapPageRank	);
				}
				file_put_contents($zbp->path . 'sitemap_page_'.($i+1).'.xml',$xml->asXML());
				$t .= '		<sitemap>'."\r\n";
				$t .= '			<loc>'.$zbp->host . 'sitemap_page_'.($i+1).'.xml</loc>'."\r\n";
				$t .= '			<lastmod>'.date('Y-m-d').'</lastmod>'."\r\n";
				$t .= '		</sitemap>'."\r\n";
				unset($xml);
			}
			unset($array);
			if($an<$maxnum){break;}
		}unset($i);
	}
	//分类0.6
	if($zbp->Config('mo_sitemap')->SitemapCate == '1'){
		$w=array();$o=array();$l=array();
		for ($i=0; $i < $maxpage; $i++) {
			$l = array(($maxnum*$i),$maxnum);
			$array=$zbp->GetCategoryList('*',$w,$o,$l);
			$an = count($array);
			if($an>0){
				$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><urlset />');
				//首页
				$url = $xml->addChild('url');//xml标签url
				$url->addChild('loc', $zbp->host);//网站url
				$url->addChild('lastmod',date('Y-m-d'));//更新时间	
				$url->addChild('changefreq', 'Daily');//抓取频率
				$url->addChild('priority', '1.0');//抓取权重
				foreach($array as $key => $v){
					$url = $xml->addChild('url');
					$url->addChild('loc', $v->Url);
					$url->addChild('lastmod',date('Y-m-d'));	
					$url->addChild('changefreq', $zbp->Config('mo_sitemap')->SitemapCateFreq);
					$url->addChild('priority', $zbp->Config('mo_sitemap')->SitemapCateRank);
				}
				file_put_contents($zbp->path . 'sitemap_cate_'.($i+1).'.xml',$xml->asXML());
				$t .= '		<sitemap>'."\r\n";
				$t .= '			<loc>'.$zbp->host . 'sitemap_cate_'.($i+1).'.xml</loc>'."\r\n";
				$t .= '			<lastmod>'.date('Y-m-d').'</lastmod>'."\r\n";
				$t .= '		</sitemap>'."\r\n";
				unset($xml);
			}
			unset($array);
			if($an<$maxnum){break;}
		}unset($i);
	}
	//标签0.6
	if($zbp->Config('mo_sitemap')->SitemapTag == '1'){
		$w=array();$o=array();$l=array();
		for ($i=0; $i < $maxpage; $i++) {
			$l = array(($maxnum*$i),$maxnum);
			$array=$zbp->GetTagList('*',$w,$o,$l);
			$an = count($array);
			if($an>0){
				$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><urlset />');
				//首页
				$url = $xml->addChild('url');//xml标签url
				$url->addChild('loc', $zbp->host);//网站url
				$url->addChild('lastmod',date('Y-m-d'));//更新时间	
				$url->addChild('changefreq', 'Daily');//抓取频率
				$url->addChild('priority', '1.0');//抓取权重
				foreach($array as $key => $v){
					$url = $xml->addChild('url');
					$url->addChild('loc', $v->Url);
					$url->addChild('lastmod',date('Y-m-d'));	
					$url->addChild('changefreq', $zbp->Config('mo_sitemap')->SitemapTagFreq);
					$url->addChild('priority', $zbp->Config('mo_sitemap')->SitemapTagRank);
				}
				file_put_contents($zbp->path . 'sitemap_tag_'.($i+1).'.xml',$xml->asXML());
				$t .= '		<sitemap>'."\r\n";
				$t .= '			<loc>'.$zbp->host . 'sitemap_tag_'.($i+1).'.xml</loc>'."\r\n";
				$t .= '			<lastmod>'.date('Y-m-d').'</lastmod>'."\r\n";
				$t .= '		</sitemap>'."\r\n";
				unset($xml);
			}
			unset($array);
			if($an<$maxnum){break;}
		}unset($i);
	}

	$t .= '	</sitemapindex>'."\r\n";

	file_put_contents($zbp->path . 'sitemaps.xml',$t);
}

function InstallPlugin_mo_sitemap() {
	global $zbp;
	if(!$zbp->HasConfig('mo_sitemap')) {
		$zbp->Config('mo_sitemap')->SitemapXml=1;
		$zbp->Config('mo_sitemap')->SitemapArticle=1;
		$zbp->Config('mo_sitemap')->SitemapArticleRank='0.8';
		$zbp->Config('mo_sitemap')->SitemapArticleFreq='Daily';
		$zbp->Config('mo_sitemap')->SitemapPage=1;
		$zbp->Config('mo_sitemap')->SitemapPageRank='0.8';
		$zbp->Config('mo_sitemap')->SitemapPageFreq='Daily';
		$zbp->Config('mo_sitemap')->SitemapCate=1;
		$zbp->Config('mo_sitemap')->SitemapCateRank='0.6';
		$zbp->Config('mo_sitemap')->SitemapCateFreq='Daily';
		$zbp->Config('mo_sitemap')->SitemapTag=1;
		$zbp->Config('mo_sitemap')->SitemapTagRank='0.6';
		$zbp->Config('mo_sitemap')->SitemapTagFreq='weekly';
		$zbp->Config('mo_sitemap')->UrlNum='5000';
		$zbp->Config('mo_sitemap')->UrlPage='5';
		$zbp->SaveConfig('mo_sitemap');
	}
}
function UninstallPlugin_mo_sitemap() {
	global $zbp;
	// $zbp->DelConfig('mo_sitemap');
}