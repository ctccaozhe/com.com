<?php
function tt_article_Filter_Plugin_Admin_Begin(){
global $zbp;

if($zbp->user->Level==1){
	$action = GetVars('act', 'GET');
	if($action=='ArticleMng'){
	Redirect($zbp->host.'zb_users/plugin/tt_article/tt_article_ArticleMng.php');
	}
}

}
################################################################################################################
/**
 * 后台文章管理
 */
function tt_article_Admin_ArticleMng() {
global $zbp;
echo '<style>
.tt_article_div{
    float: right;
    font-size: 16px;
}
.tt_article_div a{
    padding: 5px;
    background: #3a6ea5;
    color: #fff!important;
}
</style>';
echo '<script src="'.$zbp->host.'zb_users/plugin/tt_article/com.js" type="text/javascript"></script>';
    echo '<div class="divHeader">批量文章管理<div class="tt_article_div"><a href="http://www.tusay.net/design.html" target="_blank"><span class="m-right" >赞助</span></a>
        <a href="http://wpa.qq.com/msgrd?v=3&uin=2283276927&site=qq&menu=yes" target="_blank" ><span class="m-left">定制主题插件QQ</span></a></div></div>';
    echo '<div class="SubMenu">';
	
    foreach ($GLOBALS['hooks']['Filter_Plugin_Admin_ArticleMng_SubMenu'] as $fpname => &$fpsignal) {
        $fpname();
    }
    echo '</div>';
    echo '<div id="divMain2">';
    echo '<form class="search" id="search" method="post" action="#">';

    echo '<p>' . $zbp->lang['msg']['search'] . ':&nbsp;&nbsp;' . $zbp->lang['msg']['category'] . ' <select class="edit" size="1" name="category" style="width:140px;" ><option value="">' . $zbp->lang['msg']['any'] . '</option>';
    foreach ($zbp->categoriesbyorder as $id => $cate) {
        echo '<option value="' . $cate->ID . '">' . $cate->SymbolName . '</option>';
    }
    echo '</select>&nbsp;&nbsp;&nbsp;&nbsp;' . $zbp->lang['msg']['type'] . ' <select class="edit" size="1" name="status" style="width:100px;" ><option value="">' . $zbp->lang['msg']['any'] . '</option> <option value="10" >' . $zbp->lang['post_status_name']['0'] . '</option><option value="1" >' . $zbp->lang['post_status_name']['1'] . '</option><option value="2" >' . $zbp->lang['post_status_name']['2'] . '</option></select>&nbsp;&nbsp;&nbsp;&nbsp;
	<label><input type="checkbox" name="istop" value="True"/>&nbsp;' . $zbp->lang['msg']['top'] . '</label>&nbsp;&nbsp;&nbsp;&nbsp;
	<input name="search" style="width:250px;" type="text" value="" /> &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" class="button" value="' . $zbp->lang['msg']['submit'] . '"/></p>';
    echo '</form>';

    $p = new Pagebar('{%host%}zb_users/plugin/tt_article/tt_article_ArticleMng.php?{&page=%page%}{&status=%status%}{&istop=%istop%}{&category=%category%}{&search=%search%}', false);
    //$p->PageCount = $zbp->managecount;//不使用系统的。
    $p->PageCount = $zbp->Config('tt_article')->num;
    $p->PageNow = (int) GetVars('page', 'GET') == 0 ? 1 : (int) GetVars('page', 'GET');
    $p->PageBarCount = $zbp->pagebarcount;

    $p->UrlRule->Rules['{%category%}'] = GetVars('category');
    $p->UrlRule->Rules['{%search%}'] = rawurlencode(GetVars('search'));
    $p->UrlRule->Rules['{%status%}'] = GetVars('status');
    $p->UrlRule->Rules['{%istop%}'] = (boolean) GetVars('istop');

    $w = array();
    if (!$zbp->CheckRights('ArticleAll')) {
        $w[] = array('=', 'log_AuthorID', $zbp->user->ID);
    }
    if (GetVars('search')) {
        $w[] = array('search', 'log_Content', 'log_Intro', 'log_Title', GetVars('search'));
    }
    if (GetVars('istop')) {
        $w[] = array('<>', 'log_Istop', '0');
    }
    if (GetVars('status')) {
		if(GetVars('status')==10){
			$status=0;
		}else{
			$status=GetVars('status');
		}
        $w[] = array('=', 'log_Status', $status);
		
    }
    if (GetVars('category')) {
        $w[] = array('=', 'log_CateID', GetVars('category'));
    }

    $s = '';
    $or = array('log_PostTime' => 'DESC');
    $l = array(($p->PageNow - 1) * $p->PageCount, $p->PageCount);
    $op = array('pagebar' => $p);

    foreach ($GLOBALS['hooks']['Filter_Plugin_LargeData_Article'] as $fpname => &$fpsignal) {
        $fpreturn = $fpname($s, $w, $or, $l, $op);
    }

    $array = $zbp->GetArticleList(
        $s,
        $w,
        $or,
        $l,
        $op,
        false
    );

    echo '<table border="1" class="tableFull tableBorder table_hover table_striped tableBorder-thcenter">';
 	echo '<tr>';
    echo'<th class="tdCenter"><input type="button" id="checkAll" value="全选" /></th>';
    echo'<th><input type="button" id="removeAll" value="取消全部" /></th>';
    echo'<th><input type="button" id="reverse" value="反选" /></th>';
    echo'<th></th>';
    echo'<th></th>';
    echo'<th></th>';
    echo'<th></th>';
    echo'<th></th>';
    echo'<th></th>';
    echo'<th></th>';
    echo'</tr>';
    $tables = '';
    $tableths = array();
    $tableths[] = '<tr>';
    $tableths[] = '<th class="tdCenter"><input type="checkbox" id="checkAllChange" /></th>';
    $tableths[] = '<th>' . $zbp->lang['msg']['id'] . '</th>';
    $tableths[] = '<th>' . $zbp->lang['msg']['category'] . '</th>';
    $tableths[] = '<th>' . $zbp->lang['msg']['author'] . '</th>';
    $tableths[] = '<th>' . $zbp->lang['msg']['title'] . '</th>';
	if($zbp->Config('tt_article')->view=="1"){
		 $tableths[] = '<th>浏览量</th>';
	}
    $tableths[] = '<th>' . $zbp->lang['msg']['date'] . '</th>';
    $tableths[] = '<th>' . $zbp->lang['msg']['comment'] . '</th>';
    $tableths[] = '<th>' . $zbp->lang['msg']['status'] . '</th>';
    $tableths[] = '<th></th>';
    $tableths[] = '</tr>';

    foreach ($array as $article) {
		
		//print_r($article->TopType);
		if($article->TopType=="index"){
			$top_sm='首页';
		}elseif($article->TopType=="global"){
			$top_sm='全局';
		}elseif($article->TopType=="category"){
			$top_sm='分类';
		}
        $tabletds = array();//table string
        $tabletds[] = '<tr>';
        $tabletds[] = '<td class="td5 tdCenter"><input type="checkbox" class="Articleid" value="' . $article->ID . '" /></td>';
        $tabletds[] = '<td class="td5">' . $article->ID . '</td>';
        $tabletds[] = '<td class="td10">' . $article->Category->Name . '</td>';
        $tabletds[] = '<td class="td10">' . $article->Author->Name . '</td>';
        $tabletds[] = '<td><a href="' . $article->Url . '" target="_blank"><img src="'.$zbp->host.'zb_system/image/admin/link.png" alt="" title="" width="16" /></a> ' . $article->Title . '</td>';
		
        	if($zbp->Config('tt_article')->view=="1"){
		$tabletds[] = '<td class="td5">' . $article->ViewNums . '</td>';
	}
	$tabletds[] = '<td class="td15">' . $article->Time() . '</td>';
        $tabletds[] = '<td class="td5">' . $article->CommNums . '</td>';
		
        $tabletds[] = '<td class="td10">' . ($article->IsTop ? $top_sm. '|'.$zbp->lang['msg']['top'] . '|' : '') . $article->StatusName . '</td>';
		
		if(function_exists('BuildSafeCmdURL')){
			$tabletds[] = '<td class="td10 tdCenter">' . 
        '<a href="'.$zbp->host.'zb_system/cmd.php?act=ArticleEdt&amp;id=' . $article->ID . '"><img src="'.$zbp->host.'zb_system/image/admin/page_edit.png" alt="' . $zbp->lang['msg']['edit'] . '" title="' . $zbp->lang['msg']['edit'] . '" width="16" /></a>' .
        '&nbsp;&nbsp;&nbsp;&nbsp;' .
        '<a onclick="return window.confirm(\'' . $zbp->lang['msg']['confirm_operating'] . '\');" href="' . BuildSafeCmdURL('act=ArticleDel&amp;id=' . $article->ID) . '"><img src="'.$zbp->host.'zb_system/image/admin/delete.png" alt="' . $zbp->lang['msg']['del'] . '" title="' . $zbp->lang['msg']['del'] . '" width="16" /></a>' . 
        '</td>';
		}else{
			$tabletds[] = '<td class="td10 tdCenter">' . 
        '<a href="'.$zbp->host.'zb_system/cmd.php?act=ArticleEdt&amp;id=' . $article->ID . '"><img src="'.$zbp->host.'zb_system/image/admin/page_edit.png" alt="' . $zbp->lang['msg']['edit'] . '" title="' . $zbp->lang['msg']['edit'] . '" width="16" /></a>' .
        '&nbsp;&nbsp;&nbsp;&nbsp;' .
        '<a onclick="return window.confirm(\'' . $zbp->lang['msg']['confirm_operating'] . '\');" href="'.$zbp->host.'zb_system/cmd.php?act=ArticleDel&amp;id=' . $article->ID . '&amp;token=' . $zbp->GetToken() . '"><img src="'.$zbp->host.'zb_system/image/admin/delete.png" alt="' . $zbp->lang['msg']['del'] . '" title="' . $zbp->lang['msg']['del'] . '" width="16" /></a>' . 
        '</td>';
		}
        

        $tabletds[] = '</tr>';

        foreach ($GLOBALS['hooks']['Filter_Plugin_Admin_ArticleMng_Table'] as $fpname => &$fpsignal) {
            //传入 当前post，当前行，表头
            $fpreturn = $fpname($article,$tabletds,$tableths);
        }

        $tables .= implode($tabletds);
    }
	
    echo implode($tableths) . $tables;

    echo '</table>';
	echo '<style>
	.tt_article_bo{
	    overflow: hidden;
		margin:20px 0;
	}
	.tt_article_bo li{float:left;padding: 0 5px;
	}
	
	</style>';
	echo '<div class="tt_article_bo">';
	
    echo'<li>分类：<select style="width:140px;" class="edit" size="1" id="cmbCateID"><option value="">任意</option>'.OutputOptionItemsOfCategories('99999').'</select></th>';
	 echo'<li>置顶：<select style="width:100px;" class="edit" size="1" id="cmbIsTop"><option value="">任意</option><option value="1">不置顶</option><option value="2">置顶</option></select><select style="width:100px;" class="edit" size="1" id="cmbtop_status"><option value="index">首页</option><option value="global">全局</option><option value="category">分类</option></select></th>';
	echo'<li>类型：<select style="width:140px;" class="edit" size="1" id="cmbStatus"><option value="">任意</option>'.OutputOptionItemsOfPostStatus('5').'</select></th>';
	echo'<li>作者：<select style="width:140px;" class="edit" size="1" id="cmbMem"><option value="">任意</option>'.OutputOptionItemsOfMember('99999').'</select><input type="button" value="批量执行更改" id="tt_article_All"></th>';
    echo'<li><input type="button" value="批量删除" id="delAll"></li>';//ok
    echo'<li></li>';
	
	echo '</div>';
    echo '<hr/><p class="pagebar">';

    foreach ($p->Buttons as $key => $value) {
        if($p->PageNow == $key)
            echo '<span class="now-page">' . $key . '</span>&nbsp;&nbsp;';
        else
            echo '<a href="' . $value . '">' . $key . '</a>&nbsp;&nbsp;';
    }

    echo '</p></div>';
    echo '<script type="text/javascript">ActiveLeftMenu("aArticleMng");</script>';
    echo '<script type="text/javascript">AddHeaderIcon("' . $zbp->host . 'zb_system/image/common/article_32.png' . '");</script>';

}