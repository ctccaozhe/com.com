<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('Bdpush_lazy')) {$zbp->ShowError(48);die();}
if (isset($_GET['act'])){$act = $_GET['act'];}
$blogtitle='百度推送配置';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
if ($_POST && function_exists('CheckIsRefererValid')) CheckIsRefererValid();
?>
<style>
.tips{color:#999;margin-left:15px}
.ui-btn {
    padding: 10px 12px;
    display: -webkit-box;
    -webkit-box-pack: center;
}
.btn {
    display: inline-block;
    width: 160px;
    height: 40px;
    font-size: 17px;
    border-radius: 3px;
    margin: 0 auto;
}

#linkbtn,#stop {
    position: relative;
    text-align: center;
    background-color: #fff;
    vertical-align: top;
    color: #000;
    -webkit-box-sizing: border-box;
    background-clip: padding-box;
    border: 1px solid #c3c8cc;
    border: 1px solid #c3c8cc;
    border-radius: 3px;
}
#linkbtn:disabled, #stop:disabled {
    border: 0;
    color: #bbb;
    background: #e9ebec;
    background-clip: padding-box;
}
</style>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle;?></div>
  <div class="SubMenu">
  	<?php Bdpush_lazy_SubMenu(0);?>
  </div>
  <div id="divMain2">
<!--代码-->
<?php if ($act == 'set') { 
if(count($_POST)>0){
	$zbp->Config('Bdpush_lazy')->bdapi=$_POST['bdapi'];	
	$zbp->SaveConfig('Bdpush_lazy');
	$zbp->SetHint('good');
}
?>
<form id="from" method="post" action="#">
<?php if (function_exists('CheckIsRefererValid')) {echo '<input type="hidden" name="csrfToken" value="'.$zbp->GetCSRFToken().'">';}?>
<table width="100%" border="1" class="table_striped table_hover">
      <tbody><tr height="32">
        <td>第一步，填写百度推送API。<span class="tips" style="color:red;">注意:请复制完整普通收录API地址,快速推送会自动转换快速推送API,无需更多配置</span></td>
      </tr>
      <tr height="32">
        <td>
          <input type="text" name="bdapi" value="<?php echo $zbp->Config('Bdpush_lazy')->bdapi; ?>" style="width:600px" />
        </td>
        </tr>
    
    </tbody>
    </table>
 <p>
		<input type="submit" class="button" value="<?php echo $lang['msg']['submit']?>" />
		</p>
 </from>
<?php } if ($act == 'bdmap'){ 
if(count($_POST)>0){
	$zbp->Config('Bdpush_lazy')->sitemap_nums=$_POST['sitemap_nums'];
	$zbp->Config('Bdpush_lazy')->mobile=$_POST['mobile'];		
	$zbp->Config('Bdpush_lazy')->changefreq=$_POST['changefreq'];	
	$zbp->Config('Bdpush_lazy')->priority=$_POST['priority'];	
	$zbp->SaveConfig('Bdpush_lazy');
	$zbp->SetHint('good');
}
?>
<form id="from1" method="post" action="#">
<?php if (function_exists('CheckIsRefererValid')) {echo '<input type="hidden" name="csrfToken" value="'.$zbp->GetCSRFToken().'">';}?>	
<table border="0" class="tableFull tableBorder table_hover table_striped" id="thankslist">
<tbody><tr><td class="td15"><p align="center">Sitemap地图配置</p></td>
<td class="td25"><p><span>单个Sitemap Url数量比如500</span><br /><input type="number" id="sitemap_nums" name="sitemap_nums" value="<?php echo $zbp->Config('Bdpush_lazy')->sitemap_nums; ?>" min="1" step="1" class="settext"></p></td>
<td class="td25">
<p><span style="color:red;">移动Sitemap协议(仅限百度)</span><br /><select id="mobile" name="mobile" style="width:150px;">
<option value="0" <?php if($zbp->Config('Bdpush_lazy')->mobile=='0') echo ' selected="selected"';?>>不使用</option>
<option value="1" <?php if($zbp->Config('Bdpush_lazy')->mobile=='1') echo ' selected="selected"';?>>移动网页</option>
<option value="2" <?php if($zbp->Config('Bdpush_lazy')->mobile=='2') echo ' selected="selected"';?>>自适应网页</option>
<option value="3" <?php if($zbp->Config('Bdpush_lazy')->mobile=='3') echo ' selected="selected"';?>>代码适配</option>
</select></p></td>
<td class="td25">
<p><span>更新频率</span><br /><select id="changefreq" name="changefreq" style="width:150px;">
<option value="always" <?php if($zbp->Config('Bdpush_lazy')->changefreq=='always') echo ' selected="selected"';?>>Always：总是</option>
<option value="hourly" <?php if($zbp->Config('Bdpush_lazy')->changefreq=='hourly') echo ' selected="selected"';?>>Hourly：每小时</option>
<option value="daily" <?php if($zbp->Config('Bdpush_lazy')->changefreq=='daily') echo ' selected="selected"';?>>Daily：每天</option>
<option value="weekly" <?php if($zbp->Config('Bdpush_lazy')->changefreq=='weekly') echo ' selected="selected"';?>>Weekly：每周</option>
<option value="monthly" <?php if($zbp->Config('Bdpush_lazy')->changefreq=='monthly') echo ' selected="selected"';?>>Monthly：每月</option>
<option value="yearly" <?php if($zbp->Config('Bdpush_lazy')->changefreq=='yearly') echo ' selected="selected"';?>>Yearly：每年</option>
<option value="never" <?php if($zbp->Config('Bdpush_lazy')->changefreq=='never') echo ' selected="selected"';?>>Never：从未</option>
</select></p></td>
<td class="td25"><p><span>优先级</span><br /><input id="test" type="number" name="priority" value="<?php echo $zbp->Config('Bdpush_lazy')->priority; ?>" min="0.1" max="1.0" step="0.1"/></p></td>
<td class="td10"><p align="center"><input type="submit" class="button" value="<?php echo $lang['msg']['submit']?>" /></p></td>
</tr>
</tbody>
<tfoot>
</tfoot>
</table>
</from>
<?php if($zbp->Config('Bdpush_lazy')->sitemap_nums>0) { 
$count=ceil($zbp->cache->all_article_nums / $zbp->Config('Bdpush_lazy')->sitemap_nums);
$list = '<tr><td class="td15">Sitemap.xml</td>
<td><a target="_blank" href="'.$zbp->host.'Bdpush_lazy_sitemap_index.xml"  rel="noreferrer">'.$zbp->host.'Bdpush_lazy_sitemap_index.xml</a>&nbsp;<span class="tips">只需提这个交索引文件即可，无需分别提交每个文件。</span></td></tr>';
for($k=0;$k<$count;$k++){ 
	$list .='<tr><td class="td15">Sitemap_'.$k.'.xml</td>
<td><a target="_blank" href="'.$zbp->host.'Bdpush_lazy_sitemap_'.$k.'.xml"  rel="noreferrer">'.$zbp->host.'Bdpush_lazy_sitemap_'.$k.'.xml</a>&nbsp;<span class="tips">(子地图)</span></td></tr>';
}
?>
<table border="0" class="tableFull tableBorder table_hover table_striped" id="thankslist"><thead><tr><th height="32" colspan="2">&nbsp;Sitemap列表<span class="tips">各大搜索引擎通用,支持文本格式 把.xml改.txt提交即可(不包含索引地图)</span></th></tr></thead>
<tbody><?php echo $list; ?>
<tr><th height="32" colspan="2">&nbsp;了解Sitemap：<a target="_blank" href="https://ziyuan.baidu.com/college/courseinfo?id=267&page=2">https://ziyuan.baidu.com/college/courseinfo?id=267&page=2</a></th></tr>
</tbody>
<tfoot>
</tfoot>
</table>
<?php }} if ($act == 'tuisong'){ 
if(count($_POST)>0){	
}
?>
<script type="text/javascript">// skin demo
(function() {
	var _skin, _jQuery;
	var _search = window.location.search;
	if (_search) {
		_skin = _search.split('demoSkin=')[1];
		_jQuery = _search.indexOf('jQuery=true') !== -1;
		if (_jQuery) document.write('<scr'+'ipt src="<?php echo $zbp->host . "zb_system/script/jquery-2.2.4.min.js" ?>"></sc'+'ript>');
	};
	
	document.write('<scr'+'ipt src="artDialog.source.js?skin=' + (_skin || 'Cool') +'"></sc'+'ript>');
	window._isDemoSkin = !!_skin;
})();
</script> 
<h4 align="center">推送返回状态请参考百度站长平台</h4>
<div class="ui-btn">
<span>每页推送：</span><input type="number" id="_nums" name="_nums" value="50" min="1" step="1"  style="width: 160px;height: 29px;">
<input name="" class="btn" id="linkbtn" type="button" value="开始执行"  />
<input name="" class="btn" id="stop" type="button" value="停止"  />
</div>
<div class="cbox" id="linkshow" style="position: relative;display:none"><div class="con" id="linklist"><iframe src="data.php" id="iframepage"></iframe></div><div style="clear:both"></div></div>

<script type="text/javascript">
function cgurl(obj,url){
	obj.href=url;
}
function ck_dn(str) {
	if(!/^([\w-]+\.)+((com)|(net)|(org)|(gov\.cn)|(info)|(cc)|(me)|(asia)|(com\.cn)|(net\.cn)|(org\.cn)|(name)|(biz)|(tv)|(cn)|(la))$/.test(str)){
		return false;
	}else{
		return true;	
	}
}

function ck_nums(str) {
	if(! /^[0-9]+.?[0-9]*$/.test(str)){
		return false;
	}else{
		return true;	
	}
}
</script>
<script type="text/javascript">
    $("#linkbtn").click(function(){	
		art.dialog({
			id : 'loading',
			title : false,
			icon: 'loading',
			content: '<div style="font-size:16px;text-indent:-20px;color:#999;font-weight:bold;">准备开始推送数据，请稍候...<div>',
			background:'#B3B3B3',
			lock: true,
			drag : false,
		});
		$('.aui_state_noTitle').find('.aui_close').hide();		
		var numArr = $('#linklist').find('iframe').contents().find('.nums');		
		var num = numArr.val();
		//var num = num.substring(1,10);
		//var num = parseInt(num.split('.')[0]);
		var p = parseInt(num);

		if(p == 'NaN') p = 1;
		_nums = $("#_nums").val();
		if(!ck_nums(_nums)) {
			art.dialog({
				id : 'error',
				time : 2,
				title : false,
				icon: 'error',
				content: '<div style="font-size:16px;text-indent:-20px;color:#999;font-weight:bold;">提示：请输入合法的数值范围！<div>',
				background:'#B3B3B3',
				lock: true,
				drag : false,
			});
			$('.aui_state_noTitle').find('.aui_close').hide();		
		} 
		if(ck_nums(_nums)){
			$("#linkshow").show();
			$("#linklist").html("<iframe id='iframe' name='iframe' src='data.php?nums="+_nums+"&page="+p+"' width='100%' height='100%' frameborder='0' border='0' marginwidth='0' marginheight='0' scrolling='no' onload='this.height=0;var fdh=(this.Document?this.Document.body.scrollHeight:this.contentDocument.body.offsetHeight);this.height=(fdh>556?fdh:556)'></iframe>");
			setTimeout(function() {
				$('#linkbtn').attr('disabled',true);	
				$('#stop').removeAttr('disabled');		
				art.dialog({id:'loading'}).close();		
							
			},3000);
		} 			 
});

$("#stop").click(function(){
	
				  
		art.dialog({
			id : 'loading',
			title : false,
			icon: 'loading',
			content: '<div style="font-size:16px;text-indent:-20px;color:#999;font-weight:bold;">正在停止推送，请稍候...<div>',
			background:'#B3B3B3',
			lock: true,
			drag : false,
		});
		$('.aui_state_noTitle').find('.aui_close').hide();					  
		var numArr =$('#linklist').find('iframe').contents().find('.nums');		
		var num = numArr.val();
		//var num = num.substring(1,10);
		//var num = parseInt(num.split('.')[0]);
		var p = parseInt(num);
		_nums = $("#_nums").val();
		
		if(_nums==""){
		   alert("请输入推送数量");	
		}else if(ck_nums(_nums)){
			$("#linklist").show();
			$("#linklist").html("<iframe id='iframe' name='iframe' src='data.php?nums="+_nums+"&page="+p+"&parent=true'  width='100%' height='100%' frameborder='0' border='0' marginwidth='0' marginheight='0' scrolling='no' onload='this.height=0;var fdh=(this.Document?this.Document.body.scrollHeight:this.contentDocument.body.offsetHeight);this.height=(fdh>556?fdh:556)'></iframe>");	
			setTimeout(function() {		
				$('#stop').attr('disabled',true);	
				$('#linkbtn').removeAttr('disabled');	
				art.dialog({id:'loading'}).close();		
								
			},3000);
			
		}else{
			alert("请输入正确的域名");
		}				 
		
});

function IsURL(str_url){
        var strRegex = "^((https|http|ftp|rtsp|mms)?://)"
        + "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" 
        + "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184
        + "|" // 允许IP和DOMAIN（域名）
        + "([0-9a-z_!~*'()-]+\.)*" // 域名- www.
        + "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // 二级域名
        + "[a-z]{2,6})" // first level domain- .com or .museum
        + "(:[0-9]{1,4})?" // 端口- :80
        + "((/?)|" // a slash isn't required if there is no file name
        + "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$";
        var re=new RegExp(strRegex);
        //re.test()
        if (re.test(str_url)){
            return (true);
        }else{
            return (false);
        }
}

</script>
<?php } if ($act == 'kssl'){ ?>
<link type="text/css" href="bootstrap.min.css" rel="stylesheet" />
<style>a {text-decoration: none;color: #333333;}</style>
<div class="container" style="float: left;padding: 0;">
<div class="row" style="margin-top:10px;margin-bottom:10px;"><div class="col-xs-12"><textarea id="urls" class="form-control" placeholder="
首先请确保您的网站已经开通快速收录
基本设置那里还是填写你的普通收录接口，这里自动会为您转换天级API
请在这里复制粘贴你想要提交的链接,
一行一个url

需要注意的是每天您仅有10条的配额,尽量提交您的原创,不然很可能会浪费！
配额调整规则：根据上周总体配额使用情况，智能评估出新的配额。" rows="10"></textarea>
</div>
</div>
<div class="text-right"><button type="button" class="btn btn-sm btn-primary" id="startBtn">提交</button></div>
<hr>
<div id="overinfo"></div><div id="jieguo"></div>
</div>
<script type="text/javascript">
//提交代码开始
//判断是否为空函数
function isEmpty(obj){
	if(typeof obj == "undefined" || obj == null || obj == ""){
		return true;
	}else{
		return false;
	}
}

$(function() {
	$("#startBtn").click(function() {
		//初始化参数
		var urls = $("#urls").val();
		if (isEmpty(urls)){
			toastr.error("哥,还没有填写原创文章的url地址呢！");
			document.getElementById("urls").focus();
            return false;
		}
		$("#overinfo").html("<div class=\"alert alert-info text-center\" role=\"alert\">查询中,请稍等</div>");
		$("#jieguo").html("");
		$.ajax({
			url: "data.php?act=kssl",
			data: {
				urls: urls,
			},
			type: "POST",
			dataType: "json",
			error: function() {
				$("#overinfo").html("<div class=\"alert alert-warning text-center\" role=\"alert\">查询失败,请重试！</div>");
			},
			success: function(json) {
				//下面这两个参数会一直存在以后的源码中,因为格式是相同的
				if (json["success"] > 0){
					$("#overinfo").html("<div class=\"alert alert-success text-center\" role=\"alert\"><span class=\"glyphicon glyphicon-ok\"></span>网址推送成功</div>");
					$("#jieguo").html("<div class=\"alert alert-success text-center\" role=\"alert\">成功推送条数："+json["success"]+" ，今天剩余的总额度条数："+json["remain"]+" 感谢您的使用</div>");
				}
				else {
					$("#overinfo").html("<div class=\"alert alert-danger text-center\" role=\"alert\"> "+json["message"]+"！</div>");
				}
			}
		})
	});
});
</script>
<?php } if ($act == 'dsts'){

if(count($_POST)>0){
	$zbp->Config('Bdpush_lazy')->pwd=$_POST['pwd'];	
	$zbp->SaveConfig('Bdpush_lazy');
	$zbp->SetHint('good');
}
?>
<form id="from" method="post" action="#">
<?php if (function_exists('CheckIsRefererValid')) {echo '<input type="hidden" name="csrfToken" value="'.$zbp->GetCSRFToken().'">';}?>	
<table width="100%" border="1" class="table_striped table_hover">
      <tbody><tr height="32">
        <td>设置密码，防止他人搞事情</td>
      </tr>
      <tr height="32">
        <td>
          <input type="text" name="pwd" value="<?php echo $zbp->Config('Bdpush_lazy')->pwd; ?>" style="width:600px" />
        </td>
        </tr>
    <tr height="32">
        <td>推送链接：<?php if($zbp->Config('Bdpush_lazy')->pwd) echo $zbp->host.'zb_users/plugin/Bdpush_lazy/data.php?nums=500&page=1&pwd='.$zbp->Config('Bdpush_lazy')->pwd; ?>
        <br /><span>请先设置密码然后复制链接添加到宝塔定时任务即可 <a target="_blank" href="https://jingyan.baidu.com/article/92255446f56fb3c51648f485.html" style="color: red;">如何设置宝塔定时任务</a></span></td>
      </tr>
    </tbody>
    </table>
 <p>
		<input type="submit" class="button" value="<?php echo $lang['msg']['submit']?>" />
		</p>
 </from>
<?php } ?>
  </div>
</div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>