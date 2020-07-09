<?php
#注册插件
RegisterPlugin("Bdpush_lazy","ActivePlugin_Bdpush_lazy");

function ActivePlugin_Bdpush_lazy() {
	global $zbp;   
	Add_Filter_Plugin('Filter_Plugin_Index_Begin','Bdpush_lazy_Main');
	Add_Filter_Plugin('Filter_Plugin_Edit_Response3', 'Bdpush_lazy_response3');
	Add_Filter_Plugin('Filter_Plugin_PostArticle_Succeed', 'Bdpush_lazy_succeed');
	
}
function Bdpush_lazy_SubMenu($id){
	$aryCSubMenu = array(	
		0 => array('基础配置', '?act=set', 'set', 'left', false),		
		1 => array('Sitemap地图', '?act=bdmap', 'bdmap', 'left', false),	
		2 => array('一键推送', '?act=tuisong', 'tuisong', 'left', false),
		3 => array('快速收录', '?act=kssl', 'kssl', 'left', false),	
		4 => array('定时推送', '?act=dsts', 'zdts', 'left', false),			
		5 => array('帮助', 'http://www.newbii.cn/', '', 'right', true)
	);
	foreach($aryCSubMenu as $k => $v){
		echo '<a href="'.$v[1].'" '.($v[4]==true?'target="_blank"':'').'><span class="m-'.$v[3].' '.($_GET['act']==$v[2]?'m-now':'').'">'.$v[0].'</span></a>';
	}
}
function Bdpush_lazy_Main() {
	global $zbp;   	 	 	  	    	      	
    if( strpos($zbp->currenturl,'Bdpush_lazy_sitemap') !==false ) {
    	$strs = explode('_',$zbp->currenturl);
    	$last = array_pop($strs);
    	if($last==='index.xml'){
    		Bdpush_lazy_sitemapindex();
        }else{
        	Bdpush_lazy_sitemap(null, null);
        }
        die();
    }
}

function Bdpush_lazy_sitemapindex() {
	global $zbp;  
    header('Content-type: text/xml');
    //header("Content-type:application/octet-stream");
    //header("Content-Disposition: attachment; filename=".substr($zbp->currenturl,1));
	$count=ceil($zbp->cache->all_article_nums / $zbp->Config('Bdpush_lazy')->sitemap_nums);
	$list = '';
	for($k=0;$k<$count;$k++){ 
	    $list .= '<sitemap><loc>'.$zbp->host.'Bdpush_lazy_sitemap_'.$k.'.xml</loc></sitemap>';
	}
	echo '<?xml version="1.0" encoding="utf-8"?><sitemapindex>'.$list.'</sitemapindex>';
	
}

function Bdpush_lazy_succeed(&$article) {
	global $zbp;
	if (GetVars('BaiduTuis', 'POST') != '1' && GetVars('KSTuis', 'POST') != '1') {
		return;
	}
	if (GetVars('Status', 'POST') > 0) {
		return;
	}
	
    $ajax = Network::Create(trim($zbp->Config('Bdpush_lazy')->networktype));
    $api = $zbp->Config('Bdpush_lazy')->bdapi;
    $urls[]=$article->Url;   
	if (GetVars('BaiduTuis', 'POST')== '1') {
        $ajax->open('POST', $api);
        $ajax->setTimeOuts(120, 120, 0, 0);	
        $ajax->send(implode("\n", $urls));
		//$result = Bdpush_lazy_getData($api,implode("\n", $urls));
		
		$zbp->SetHint('good','普通收录状态:'.$ajax->responseText);
	}
	if (GetVars('KSTuis', 'POST')== '1') {
		
        $ajax->open('POST', $api . '&type=daily');
        $ajax->setTimeOuts(120, 120, 0, 0);	
        $ajax->send(implode("\n", $urls));
		//$result1 = Bdpush_lazy_getData($api . '&type=daily',implode("\n", $urls));
		$zbp->SetHint('good','快速收录状态:'.$ajax->responseText);
	}
}

function Bdpush_lazy_response3() {
	?>
    <div id="BaiduTuis" class="editmod">
    <input type="checkbox" name="BaiduTuis" value="1" checked="checked" /><span class="tips">百度普通收录</span>
    </div>
    <div id="KSTuis" class="editmod">
    <input type="checkbox" name="KSTuis" value="1" /><span class="tips" style="color: red;">快速收录</span>
    </div>
<?php
}

function Bdpush_lazy_sitemap($nums, $pagenow) {
	global $zbp;  
    $strs = explode('_',$zbp->currenturl);
	$page=explode(".",array_pop($strs));
    $type = $page[1]; 
    $sitemap_nums = $zbp->Config('Bdpush_lazy')->sitemap_nums;
	$count=ceil($zbp->cache->all_article_nums / $zbp->Config('Bdpush_lazy')->sitemap_nums);
	switch ($zbp->Config('Bdpush_lazy')->mobile)
    {
    case "1":
        $mobile = '<mobile:mobile type="mobile"/>';
        $mobileset = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">';
        break;
    case "2":
        $mobile = '<mobile:mobile type="pc,mobile"/>';
        $mobileset = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">';
        break;
    case "3":
        $mobile = '<mobile:mobile type="htmladapt"/>';
        $mobileset = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">';
        break;
    default:
        $mobile = '';
        $mobileset = '<urlset>';
    }

    $pagebar = new Pagebar('');
    $pagebar->PageNow = ($pagenow !==null) ? $pagenow :$page[0] + 1;
    $pagebar->PageCount = ($nums !==null) ? $nums :$sitemap_nums;

    $w = array();
    $w[] = array('=', 'log_Status', '0');
    $w[] = array('=', 'log_Type', '0');
    $limit = array(($pagebar->PageNow -1 ) * $pagebar->PageCount, $pagebar->PageCount);
    $option = array('pagebar' => $pagebar);
    $plink = '';
    $articles = $zbp->GetArticleList('*', $w, array('log_PostTime' => 'DESC'), $limit, $option);
    foreach ($articles as $article) {
    	$time = $article->Time('Y-m-d');
    	$changefreq = $zbp->Config('Bdpush_lazy')->changefreq;
    	$priority = $zbp->Config('Bdpush_lazy')->priority;
	    	if($type == 'txt') {
	    		$plink .= $article->Url."\r\n"; 
	    	} elseif($type == 'xml') {
    	        $plink .= '<url><loc>'.$article->Url.'</loc>'.$mobile.'<lastmod>'.$time.'</lastmod><changefreq>'.$changefreq.'</changefreq><priority>'.$priority.'</priority></url>'; 
	    	}
    }
    if($type == 'txt') {
    	header('Content-Type: text/plain');
        //header("Content-type:application/octet-stream");
        //header("Content-Disposition: attachment; filename=".substr($zbp->currenturl,1));
    	echo $plink;
    }elseif($type == 'xml'){
        header('Content-type: text/xml');
        //header("Content-type:application/octet-stream");
        //header("Content-Disposition: attachment; filename=".substr($zbp->currenturl,1));
    	echo  '<?xml version="1.0" encoding="utf-8"?>'.$mobileset.$plink.'</urlset>';
    }else{
    	return $articles;
    }
}
/**
 * 以下弃用，使用系统send()方法
function Bdpush_lazy_getData($api,$postData = '') {
    if (!$api) {
        return false;
    }
    if (empty($postData)) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        curl_close($ch);
        } else {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_TIMEOUT, 20); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        curl_close($ch);
    }
    if ($result) {
        return $result;
    } else {
        return null;
    }
}
*/
function InstallPlugin_Bdpush_lazy() {
	global $zbp; 
    if (!$zbp->Config('Bdpush_lazy')->HasKey('Version')) {
        $array = array(
            'Version'        => '2.1',
            'bdapi'          => '',
            'sitemap_nums'   => '500',
            'changefreq'     => 'always',
            'priority'       => '0.5',
        );
        foreach ($array as $value => $intro) {
            $zbp->Config('Bdpush_lazy')->$value = $intro;
        }
	}
	$zbp->Config('Bdpush_lazy')->networktype = '';
	$zbp->Config('Bdpush_lazy')->pwd = '';
	$zbp->Config('Bdpush_lazy')->mobile = '0';
    $zbp->SaveConfig('Bdpush_lazy');
}
function UninstallPlugin_Bdpush_lazy() {
	global $zbp;  
}