<?php

/*
 * @name       KandyLinkS
 * @author     吉光片羽
 * @support    www.jgpy.cn
 * @version    1.0
 * @pubdate    2014-11-21
 */

require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';

$zbp->Load();
$action='root';

$app=$zbp->LoadApp('plugin','KandyLinkS');

if (!$zbp->CheckRights($action)) exit($zbp->ShowError(6));
if (!$zbp->CheckPlugin($app->id)) exit($zbp->ShowError(48));

$blogtitle=$app->name.$app->version;

if(count($_POST)>0){

	if(!$zbp->ValidToken(GetVars('token','GET'))) exit($zbp->ShowError(5,__FILE__,__LINE__));

	$mod=$zbp->GetModuleByID(GetVars('ID', 'POST'));
	$content='';
	$sub=0;
	$tree=(int)$_POST['tree']==1;
	foreach($_POST['name'] as $k=>$v){
		if($k==count($_POST['name'])-1) continue;
		if(!$tree){
			if($_POST['sub'][$k]){
				if(++$sub==1) $content.="<kandyLinkSub>\n";
			}else{
				if($sub) $content.="</kandyLinkSub>\n";
				$sub=0;
			}
		}
		$content.='<li'.(!$_POST['sub'][$k]?'':' class="kandyLinkSub"').'><a href="'.$_POST['href'][$k].'"'.(!$_POST['target'][$k]?'':' target="_blank"').' title="'.$_POST['title'][$k].'">'.$_POST['name'][$k].'</a></li>'."\n";
	}
	if(!$tree) $content=str_replace(array("</li>\n<kandyLinkSub>",'</kandyLinkSub>'),array('<ul>','</ul></li>'),CloseTags($content));

	$mod->Content=$content;
	$mod->Name=$_POST['Name'];
	$mod->FileName=$_POST['FileName'];
	$mod->HtmlID=$_POST['HtmlID'];
	$mod->Source=$_POST['Source'];
	$mod->Type='ul';
	$mod->MaxLi=0;
	FilterModule($mod);
	$mod->Save();
	$zbp->AddBuildModule($mod->FileName);
	$zbp->BuildModule();
	$zbp->SetHint('good');
	if($_POST['backup']=='1') file_put_contents($zbp->usersdir .'plugin/KandyLinkS/'.$mod->FileName.'.txt',$content);
	Redirect($_POST['stay']=='1'?$_SERVER['HTTP_REFERER']:'../../../zb_system/cmd.php?act=ModuleMng');
	die();
}

$mod=new Module();
$mod->ID=0;
$mod->Source='plugin_KandyLink';
$list='<tr><td><input type="text" name="href[]" value="http://" size="30" /></td><td><input type="text" name="title[]" value="链接描述" size="30" /></td><td><input type="text" name="name[]" value="新名称" size="20" /></td><td><input type="text" name="target[]" class="checkbox" value="0" /></td><td><input type="text" name="sub[]" class="checkbox" value="0" /></td></tr>';

$islock='';
$tree=null;
$backup='';

if($edit=GetVars('edit','GET')){
	if(!empty($edit)){
		$mod=$zbp->modulesbyfilename[$edit];
		$content=$mod->Content;
		preg_match('/<\/ul><\/li>/i',$content,$tree);
		if($tree) $content=str_replace(array('<ul>','</ul></li>'),array("</li>\n",''),$content);
		$preg=array(
			'tag'=>'/<li.*?<\/li>/',
			'sub'=>'/<li.*?class=[\'|\"](.*?)[\'|\"]/i',
			'href'=>'/<a.*?href=[\'|\"](.*?)[\'|\"]/i',
			'target'=>'/<a.*?target=[\'|\"](.*?)[\'|\"]/i',
			'name'=>'/<a.*?>(.*?)<\/a>/i',
			'title'=>'/<a.*?title=[\'|\"](.*?)[\'|\"]/i',
		);
		$link=array();
		preg_match_all($preg['tag'],$content,$tag);
		foreach($tag[0] as $key=>$val){
			foreach($preg as $k=>$v){
				preg_match($v,$val,$m);
				if(count($m)>1){
					if($k=='name') $m[1]=preg_replace('/<img.*?[\/]>/i','',$m[1]);
					if($k=='sub') $m[1]=!preg_match('/sub/i',$m[1])?'':'kandyLinkSub';
					$link[$k][$key]=$m[1];
				}else{
					$link[$k][$key]='';
				}
			}
		}
		if($link){
			$list='';
			foreach($link['tag'] as $k=>$v){
					$list.='<tr class="'.$link['sub'][$k].'">
						<td><input name="href[]" value="'.$link['href'][$k].'"/ size="30"></td>
						<td><input name="title[]" value="'.$link['title'][$k].'"/ size="30"></td>
						<td><input name="name[]" value="'.$link['name'][$k].'"/ size="20"></td>
						<td><input name="target[]" value="'.($link['target'][$k]?1:0).'" class="checkbox"/></td>
						<td><input name="sub[]" value="'.($link['sub'][$k]?1:0).'" class="checkbox"/></td>
					<tr>';
			}
		}
		if($mod->Source=='system'||$mod->Source=='theme'){
			$islock='readonly="readonly"';
		}
		$cfg=$zbp->Config('KandyLinkS');
		$links=explode('|',$cfg->DefaultLinks);
		$backup=in_array($mod->FileName,$links)?'<input type="text" name="backup" class="checkbox" value="0"/> 备份当前链接内容（开启后提交将覆盖插件启用时备份的原始数据）':'';
	}
}

require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';

?>

<div id="divMain">
	<div class="divHeader"><?php echo $blogtitle;?></div>
	<div class="SubMenu"></div>
	<div id="divMain2">
		<form id="edit" name="edit" method="post" action="main.php?token=<?php echo $zbp->GetToken();?>">
			<input name="ID" type="hidden" value="<?php echo $mod->ID;?>" />
			<input name="Source" type="hidden" value="<?php echo $mod->Source;?>" />
			<table border="1" width="100%">
				<thead>
					<tr>
						<th>链接</th>
						<th>描述</th>
						<th>名称</th>
						<th class="td10"><abbr title="是否在新窗口打开">新窗</abbr></th>
						<th class="td10"><abbr title="作为前一个一级链接的二级链接">二级</abbr></th>
					</tr>
				</thead>
				<tbody id="kandyLinkList">
<?php echo $list; ?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="5" id="kandyLinkDel"><strong>请在下方设置新链接并拖拽到上方完成链接添加。（提交后保存！）</strong><b>不要的链接请往这里丢！</b></th>
					</tr>
					<tr id="kandyLinkAdd">
						<td><input type="text" name="href[]" value="http://" size="30" /></td>
						<td><input type="text" name="title[]" value="链接描述" size="30" /></td>
						<td><input type="text" name="name[]" value="新名称" size="20" /></td>
						<td><input type="text" name="target[]" class="checkbox" value="0" /></td>
						<td><input type="text" name="sub[]" class="checkbox" value="0" /></td>
					</tr>
				</tfoot>
			</table>
			<hr />
			<table border="1" width="100%">
				<tr>
					<th><?php echo $lang['msg']['name']?></th>
					<th><?php echo $lang['msg']['filename']?></th>
					<th><?php echo $lang['msg']['htmlid']?></th>
					<th class="td10"><?php echo $lang['msg']['hide_title']?></th>
					<th class="td10"><abbr title="关闭树形则采用嵌套格式，即二级菜单默认隐藏">树形[?]</abbr></th>
				</tr>
				<tr>
					<td><input id="edtName" size="20" name="Name" maxlength="50" type="text" value="<?php echo $mod->Name;?>" /></td>
					<td><input id="edtFileName" size="20" name="FileName" type="text" value="<?php echo $mod->FileName;?>" <?php echo $islock?>/></td>
					<td><input id="edtHtmlID" size="20" name="HtmlID" type="text" value="<?php echo $mod->HtmlID;?>" /></td>
					<td align="center"><input type="text" id="IsHideTitle" name="IsHideTitle" class="checkbox" value="<?php echo $mod->IsHideTitle;?>"/></td>
					<td align="center"><input type="text" name="tree" class="checkbox" value="<?php echo $tree?0:1;?>"/></td>
				</tr>
			</table>
			<p>
				<input type="submit" class="button" value="<?php echo $lang['msg']['submit']?>" onclick="return checkInfo();" />
				<input type="text" name="stay" class="checkbox" value="0"/> 提交后返回本页
				<?php echo $backup?>
			</p>
		</form>
	</div>
</div>
<script type="text/javascript">

		ActiveLeftMenu("aModuleMng");
		AddHeaderIcon("<?php echo $bloghost . 'zb_users/plugin/KandyLinkS/logo.png';?>");

		function checkInfo(){
			if(!$("#edtName").val()){
				alert("<?php echo $lang['error']['72']?>");
				return false
			}
			if(!$("#edtFileName").val()){
				alert("<?php echo $lang['error']['75']?>");
				return false
			}
			if(!$("#edtHtmlID").val()){
				alert("<?php echo $lang['error']['76']?>");
				return false
			}
		}

		$("#kandyLinkList").sortable({
			axis: "y",
			opacity: .5,
			stop:function(event,ui){
				$("input[name='sub[]']").next(".imgcheck").click(function(){
					var me=this;
					setTimeout(function(){
						$(me).closest("tr")[$(me).hasClass("imgcheck-on")?"addClass":"removeClass"]("kandyLinkSub");
					},20);
				});
			}
		});

		$("#kandyLinkAdd").draggable({
			connectToSortable: "#kandyLinkList",
			helper: "clone",
			revert:	"vaild",
			axis: "y",
			addClasses: false
		});

		$("#kandyLinkDel").droppable({
			accept:"#kandyLinkList tr",
			addClasses: false,
			drop: function( event, ui ) {
				ui.draggable.remove();
			},
			activeClass:"kandyLinkDel"
		});

		$(function(){
			$("input[name='sub[]']").next(".imgcheck").click(function(){
				var me=this;
				setTimeout(function(){
					$(me).closest("tr")[$(me).hasClass("imgcheck-on")?"addClass":"removeClass"]("kandyLinkSub");
				},20);
			});
		});

</script>
<style type="text/css">
td,th{padding:10px}
hr{height:10px}
abbr{cursor:help;border-color:#F00}
#kandyLinkDel b,
.kandyLinkDel strong{display:none;}
.kandyLinkDel b{background:#F36;color:#fff;padding:20px;display:block!important;}
#kandyLinkList tr,#kandyLinkAdd{cursor:move}
#kandyLinkList{height:10px;}
.kandyLinkSub td{text-indent:.5em;}
.kandyLinkSub td:before{content:"└ ";}
</style>
<?php
require $blogpath . 'zb_system/admin/admin_footer.php';

RunTime();
?>
