<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('clicktip')) {$zbp->ShowError(48);die();}

$blogtitle='拓源点击提示插件';
$act = "";
if ($_GET['act']){
$act = $_GET['act'] == "" ? 'config' : $_GET['act'];
}
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<link rel="stylesheet" href="./script/admin.css">
<script type="text/javascript" src="./script/custom.js"></script>
<div class="twrapper">
<div class="theader">
	<div class="theadbg"></div>
	<div class="tuser">
		<div class="tuserimg"><img src="style/images/sethead.png" /></div>
		<div class="tusername"><?php echo $blogtitle;?></div>
	</div>
	<div class="tmenu">
		<ul>
		<?php clicktip_SubMenu($act);?>
		</ul>
	</div>
</div>
<div class="tmain">
<?php
if ($act == 'base'){
	if(isset($_POST['KeyWords'])){
		$zbp->Config('clicktip')->KeyWords = $_POST['KeyWords'];
		$zbp->Config('clicktip')->FirstKeyIndex = $_POST['FirstKeyIndex'];
		$zbp->Config('clicktip')->FontSize = $_POST['FontSize'];
		$zbp->Config('clicktip')->FontColor = $_POST['FontColor'];
		$zbp->Config('clicktip')->FontFamily = $_POST['FontFamily'];
		$zbp->Config('clicktip')->FontStyle = $_POST['FontStyle'];
		$clicktipScript = clicktipScript();
		@file_put_contents($zbp->path.'zb_users/plugin/clicktip/script/common.js', $clicktipScript);
		$zbp->Config('clicktip')->PostSAVECONFIG = $_POST['PostSAVECONFIG'];
		$zbp->SaveConfig('clicktip');
		$zbp->ShowHint('good');
	}
?>
<dl>
	<script type="text/javascript" src="./script/jscolor.js"></script>
	<form method="post" class="setting">
		<dt>基本设置</dt>
		<dd>
			<label for="KeyWords">关键词列表</label>
			<textarea name="KeyWords" id="KeyWords" cols="30" rows="3" class="setinput"><?php echo $zbp->Config('clicktip')->KeyWords;?></textarea>
			<i class="warn">？</i><span class="warncon">点击页面显示的文字词组，以竖线|分隔，末尾不加竖线|。</span>
		</dd>
		<dd>
			<label for="FirstKeyIndex">关键词首显</label>
			<input type="text" id="FirstKeyIndex" name="FirstKeyIndex" value="<?php echo $zbp->Config('clicktip')->FirstKeyIndex;?>" class="settext" />
			<i class="warn">？</i><span class="warncon">设置首次点击页面显示的关键词，默认显示第一个关键词。</span>
		</dd>
		<dd class="half">
			<label for="FontSize">关键词字号</label>
			<input type="text" id="FontSize" name="FontSize" value="<?php echo $zbp->Config('clicktip')->FontSize;?>" class="settext" />
			<i class="warn">？</i><span class="warncon">关键词字号，填写纯数字。</span>
		</dd>
		<dd class="half">
			<label for="FontColor">关键词颜色</label>
			<input type="text" id="FontColor" name="FontColor" value="<?php echo $zbp->Config('clicktip')->FontColor;?>" class="color settext" />
			<i class="warn">？</i><span class="warncon">设置关键词颜色。</span>
		</dd>
		<dd class="half">
			<label for="FontFamily">关键词字体</label>
			<select name="FontFamily" id="FontFamily">
				<option value=""<?php if($zbp->Config('clicktip')->FontFamily==''){echo 'selected="selected"';}?>>不单独指定</option>
				<option value="microsoft yahei"<?php if($zbp->Config('clicktip')->FontFamily=='microsoft yahei'){echo 'selected="selected"';}?>>微软雅黑</option>
				<option value="simsun"<?php if($zbp->Config('clicktip')->FontFamily=='simsun'){echo 'selected="selected"';}?>>宋体</option>
			</select>
			<i class="warn">？</i><span class="warncon">选择关键词字体，默认使用主题字体。</span>
		</dd>
		<dd class="half">
			<label for="FontStyle">关键词样式</label>
			<select name="FontStyle" id="FontStyle">
				<option value="normal"<?php if($zbp->Config('clicktip')->FontStyle=='normal'){echo 'selected="selected"';}?>>常规</option>
				<option value="bold"<?php if($zbp->Config('clicktip')->FontStyle=='bold'){echo 'selected="selected"';}?>>加粗</option>
				<option value="italic"<?php if($zbp->Config('clicktip')->FontStyle=='italic'){echo 'selected="selected"';}?>>倾斜</option>
			</select>
			<i class="warn">？</i><span class="warncon">设置关键词字体样式。</span>
		</dd>
		<dd class="half">
			<label>保存配置信息</label>
			<input type="text" id="PostSAVECONFIG" name="PostSAVECONFIG" class="checkbox" value="<?php echo $zbp->Config('clicktip')->PostSAVECONFIG;?>" />
			<i class="warn">？</i><span class="warncon">“ON”为保存配置信息，启用或卸载插件后不清空配置信息；<br>“OFF”为删除配置信息，启用或卸载插件后将清空配置信息。<br>若不再使用本插件，请选择"OFF"提交，则清空配置信息。</span>
		</dd>
		<dd class="setok"><input type="submit" value="保存设置" class="setbtn" /><img id="statloading" src="style/images/loading.gif" /></dd>
	</form>
</dl>
<?php }
if ($act == 'update'){
	if(isset($_POST['PostUSER'])){
		$zbp->Config('clicktip')->PostUSER = $_POST['PostUSER'];				//用户名
		$zbp->SaveConfig('clicktip');
		$zbp->ShowHint('good');
	}
?>
<dl>
	<dt>检测更新</dt>
	<dd class="update">
		<label>检测更新</label>
		<?php
		//检测新版本
		if (file_exists($blogpath.'zb_users/plugin/clicktip/plugin.xml')){
			$xml = new DOMDocument();
			@$xml->load($blogpath.'zb_users/plugin/clicktip/plugin.xml');
			foreach($xml->getElementsByTagName('version') as $list)
			{
				$ver = $list->firstChild->nodeValue;
			}
			$version = GetHttpContent(base64_decode("aHR0cDovL3d3dy50b3llYW4uY29tL3piX3VzZXJzL3VwbG9hZC9jbGlja3RpcC0zYzAyQjEvdmVyc2lvbi50eHQ="));
			if($version>$ver){
				echo "发现新版本！<span class='setok'><a href='".base64_decode("aHR0cDovL3d3dy50b3llYW4uY29tL3piX3VzZXJzL3VwbG9hZC9jbGlja3RpcC0zYzAyQjEvZG93bmxvYWQucGhw")."' target='_blank' class='update'>立即更新</a></span>";

			}else{
				echo "已是最新！当前版本 V".$ver;
			}
		}else{
			die('插件plugin.xml文件不存在，请重新安装插件！');
		}
		?>
	</dd>
</dl>
<?php }?>
</div>
</div>
<div class="tfooter">
	<ul>
		<li><a href="../../plugin/AppCentre/main.php?auth=e9210072-2342-4f96-91e7-7a6f35a7901e" target="_blank">全部主题</a></li>
		<li><a href="http://www.toyean.com/?advice" target="_blank">意见反馈</a></li>
		<li><a href="http://www.toyean.com/post/clicktip.html" target="_blank">帮助说明</a></li>
		<li><a href="http://wpa.qq.com/msgrd?v=3&uin=476590949&site=qq&menu=yes" target="_blank">技术支持</a></li>
		<li><a href="https://jq.qq.com/?_wv=1027&k=44zyTKi" target="_blank">主题交流群</a></li>
	</ul>
	<p>Copyright &copy; 2010-2017 <a href="http://www.toyean.com/" target="_blank">TOYEAN.COM</a> all rights reserved.</p>
</div>
<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>