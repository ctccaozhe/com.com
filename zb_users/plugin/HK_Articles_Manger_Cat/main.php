<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('HK_Articles_Manger_Cat')) {$zbp->ShowError(48);die();}

$w = array();
$page_cat='';
$PageNow = (int) GetVars('page') == 0 ? 0 : (int) GetVars('page');
$page_do='&category_do=1';
$article_keyword='&article_keyword=';

if (!$zbp->CheckRights('ArticleAll')) {
    $w[] = array('=', 'log_AuthorID', $zbp->user->ID);
}
if(GetVars('category_do')=='1')
{
    if(GetVars('category')!=null&&GetVars('category')!=0)
    {
        $w[] = array('=', 'log_CateID', GetVars('category'));
        $page_cat='&category='.GetVars('category');
    }
    if(GetVars('article_keyword')!=null&&trim(GetVars('article_keyword'))!='')
    {
        $w[] = array('search', 'log_Title', GetVars('article_keyword'));
        $article_keyword='&article_keyword='.GetVars('article_keyword');
    }
    if(GetVars('article_cat')!=null&&trim(GetVars('article_cat'))!='')
    {
        $w[] = array('not in', 'log_CateID', GetVars('article_cat'));
    }
}
if(GetVars('category_do','POST')=='2')
{
    if(GetVars('category')!=null&&GetVars('category')!=0)
    {
        $log_id=implode(',',GetVars('Article_Id'));
        $sql = "UPDATE ".$zbp->table['Post']." SET  log_CateID=".GetVars('category')." Where log_ID IN(".$log_id.")";
        $zbp->db->Update($sql);
        if(GetVars('last_cat'))
        {
            $w[] = array('=', 'log_CateID', GetVars('last_cat'));
            $page_cat='&category='.GetVars('last_cat');
        }

    }
}
if(GetVars('category_do','POST')=='3')
{
    $log_id=implode(',',GetVars('Article_Id'));
    $sql = "DELETE FROM ".$zbp->table['Post']." Where log_ID IN(".$log_id.")";
    $zbp->db->Delete($sql);
    if(GetVars('last_cat')!=null)
    {
        $w[] = array('=', 'log_CateID', GetVars('last_cat'));
        $page_cat='&category='.GetVars('last_cat');
    }
}
$page_Pre=($PageNow-1).$page_cat.$page_do.$article_keyword.'&article_cat='.GetVars('article_cat');
$page_Next=($PageNow+1).$page_cat.$page_do.$article_keyword.'&article_cat='.GetVars('article_cat');
$s = '';
$or = array('log_PostTime' => 'DESC');
$PageCount = $zbp->managecount;
$l = array($PageNow*$PageCount,$PageCount);
$array = $zbp->GetArticleList(
    $s,
    $w,
    $or,
    $l
);
$articles_count=count($array);
$blogtitle='HK_Articles_Manger_Cat';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<style>
    .page-left{float: left;}
    .page-right{float: right;}
    .page-bottom{margin-bottom: 20px;}
</style>
    <div id="divMain">
        <div class="divHeader">文章批量管理插件</div>
        <div class="SubMenu">
            <?php echo '<a href="'. $zbp->host .'zb_system/admin/index.php?act=ArticleMng"><span class="m-left">系统文章管理</span></a>';
            echo '<a href="'. $zbp->host .'zb_users/plugin/HK_Articles_Manger_Cat/main.php"><span class="m-left m-now">插件文章管理</span></a>';?>
        </div>
        <div id="divMain2">
            <form method="post" action="<?php echo $zbp->host;?>zb_users/plugin/HK_Articles_Manger_Cat/main.php">
                <input type="hidden" name="last_cat" value="<?php echo GetVars('category');?>" />
            <p>分类：<select class="edit" size="1" name="category" style="width:140px;" ><option value="0">所有分类</option><?php
                foreach ($zbp->categoriesbyorder as $id => $cate) {
                echo '<option value="' . $cate->ID . '">' . $cate->SymbolName . '</option>';
                }?></select>
            操作：<select class="edit" size="1" name="category_do" style="width:140px;" ><option value="1">搜索</option><option value="2">修改</option><option value="3">删除</option></select>不包含分类:<input type="text" name="article_cat" style="width:250px;" placeholder="填写分类ID,多个请用,分开" />标题：<input type="text" name="article_keyword" style="width:250px;" /><input type="submit" value="提交" /><span>结果:<?php echo $articles_count;?>条</span></p>
                <p class="page-bottom"><span class="page-left"><?php if($PageNow>=1){?><a href="main.php?page=<?php echo $page_Pre;?>">上一页</a><?php }?></span><?php if($articles_count==$PageCount){?><span class="page-right"><a href="main.php?page=<?php echo $page_Next;?>">下一页</a></span><?php }?></p>
                <table width="100%" border="1" width="100%" class="tableBorder">
                    <tr>
                        <th width="50px">选择<input type="checkbox" onclick="checkAll()" /></th>
                        <th width="70px">操作</th>
                        <th width="70px">ID</th>
                        <th width="160px">分类</th>
                        <th width="160px">日期</th>
                        <th>标题</th>
                    </tr>
                    <?php foreach ($array as $article){?>
                    <tr>
                        <td><input type="checkbox" name="Article_Id[]" value="<?php echo $article->ID;?>"/></td>
                        <td><a href="<?php echo $zbp->host;?>zb_system/cmd.php?act=ArticleEdt&id=<?php echo $article->ID;?>" class="button"><img src="<?php echo $zbp->host;?>zb_system/image/admin/page_edit.png" alt="编辑" title="编辑" width="16"></a></td>
                        <td><?php echo $article->ID;?></td>
                        <td><?php echo $article->Category->Name;?></td>
                        <td><?php echo $article->Time();?></td>
                        <td><a href="<?php echo $article->Url;?>" target="_blank" class="button"><img src="<?php echo $zbp->host;?>zb_system/image/admin/link.png" width="16"></a><?php echo $article->Title;?></td>
                    </tr>
                    <?php }?>
                </table>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        var isCheckAll = false;
        function checkAll() {
            if (isCheckAll) {
                $("input[type='checkbox']").each(function() {
                    this.checked = false;
                });
                isCheckAll = false;
            } else {
                $("input[type='checkbox']").each(function() {
                    this.checked = true;
                });
                isCheckAll = true;
            }
        }
    </script>
<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>