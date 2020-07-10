<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('SaveRemoteImage')) {$zbp->ShowError(48);die();}

function lcp_sqlwithpage($page){
    global $zbp;
    $p = new Pagebar("", false); 
    $p->PageCount = 100;
    $p->PageNow = (int) $page;			     	 			        		 	 	
    $sql = $zbp->db->sql->Select($zbp->table['Post'],'*',null,null,array(($p->PageNow - 1) * $p->PageCount, $p->PageCount),array('pagebar' => $p));
    $res = $zbp->GetListType('Post',$sql);
    return $res;
}

function jumpjs($page){
    global $zbp;
    exit('处理：'.($page * 100 - 100) . '-' .($page * 100).'<script>window.location.href="?selall&csrfToken='.$zbp->GetCSRFToken().'&page='.$page.'"</script>');
}

if (isset($_POST['selall']) || isset($_GET['selall'])){
    CheckIsRefererValid();
    $page = (int)GetVars('page','GET');
    if (!$page){
        jumpjs(1);
    }
    $data = lcp_sqlwithpage($page);
    if ($data){
        foreach ($data as $post) {
            $post->Save();
        }
        jumpjs(++$page);
    }else{
        $zbp->SetHint('good');
        redirect('main.php');
    }
}

if ($_POST) {    	 	   	 
    CheckIsRefererValid();   
    $zbp->Config('SaveRemoteImage')->filter = GetVars('filter','POST');       			  
    $zbp->Config('SaveRemoteImage')->Save(); 
} 

$blogtitle='SaveRemoteImage';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
    <div class="divHeader">保存站外图片</div>
    <div class="SubMenu">
        <a href="https://shang.qq.com/wpa/qunwpa?idkey=9718901f430567658b4a0799b23f94e0f248dfbc25bb09e6ed526b96451943c8" target="_blank"><span class="m-left" style="color:red">zblog应用交流群</span></a>
        <a href="https://kfuu.cn/service.html" target="_blank"><span class="m-left" style="color:blue">zblog定制服务</span></a>
    </div>
    <div id="divMain2">
        <form method="post">
            <table border="1" class="tableFull tableBorder">
            <tr>
                <th width='150px'><p>项目</p></th>
                <th>配置</th>
            </tr>
            <tr>
                <td><p>排除域名</p></td>
                <td><input type="text" name="filter" value="<?php echo $zbp->Config('SaveRemoteImage')->filter?>" style="width:100%">此域名下的图片不会被下载。多个域名用|分开</td>
            </tr>
            </table>
            <hr/>
            <input type="hidden" name="csrfToken" value="<?php echo $zbp->GetCSRFToken();?>">
            <p><input type="submit" class="button" value="<?php echo $lang['msg']['submit'] ?>" /> <input type="submit" onclick="this.value='正在处理...'" name="selall" class="button" value="一键处理所有文章" style="background: #e0e1e2;color: #333;border: none;" /></p>
        </form>
    </div>
</div>
<script type="text/javascript">AddHeaderIcon("logo.png");</script>
<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>