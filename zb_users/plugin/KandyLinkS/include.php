<?php

/*
 * @name       KandyLinkS
 * @author     吉光片羽
 * @support    www.jgpy.cn
 * @version    1.0
 * @pubdate    2014-11-21
 */

RegisterPlugin("KandyLinkS","ActivePlugin_KandyLinkS");

function ActivePlugin_KandyLinkS() {

	Add_Filter_Plugin('Filter_Plugin_Admin_CategoryMng_SubMenu','KandyLinkS_AddMenu');
	Add_Filter_Plugin('Filter_Plugin_Admin_PageMng_SubMenu','KandyLinkS_AddMenu');
	Add_Filter_Plugin('Filter_Plugin_Admin_ModuleMng_SubMenu','KandyLinkS_ModuleMenu');
	Add_Filter_Plugin('Filter_Plugin_Admin_Header','KandyLinkS_Head');
	Add_Filter_Plugin('Filter_Plugin_Html_Js_Add','KandyLinkS_AddJS');

}

function InstallPlugin_KandyLinkS(){
	global $zbp;

	if($zbp->CheckApp('KandyLink')){
		$zbp->SetHint('bad','已安装更优秀的【KandyLink链接管理】插件！');
		DisablePlugin('KandyLinkS');
		Redirect($_SERVER['HTTP_REFERER']);
		exit;
	}

	$cfg=$zbp->Config('KandyLinkS');

	$cfg->DefaultLinks='navbar|favorite|link';

	$links=explode('|',$cfg->DefaultLinks);
	foreach($links as $mod){
		$backup=$zbp->modulesbyfilename[$mod]->Content;
		if($mod=='navbar') $backup=preg_replace('/<ins id="kandyNavBtn".*?>&equiv;<\/ins>/','',$backup);
		file_put_contents($zbp->usersdir .'plugin/KandyLinkS/'.$mod.'.txt',$backup);
	}

	$zbp->SaveConfig('KandyLinkS');

}

function UninstallPlugin_KandyLinkS(){
	global $zbp;

	$cfg=$zbp->Config('KandyLinkS');

	$links=explode('|',$cfg->DefaultLinks);
	foreach($links as $mod){
		$file=$zbp->usersdir .'plugin/KandyLinkS/'.$mod.'.txt';
		if(is_file($file)){
			$backup=file_get_contents($file);
			$module=$zbp->modulesbyfilename[$mod];
			$module->Content=$backup;
			$module->Save();
			$zbp->AddBuildModule($module->FileName);
			unlink($file);
		}
	}
}

function KandyLinkS_Head(){
	global $zbp;
	echo <<<CSS
		<style type="text/css">
			.kandyLink{border-left:2px solid #f93;border-radius:3px 0 0 3px;text-shadow:1px 1px #eee;}
CSS;
	echo '</style>';
}

function KandyLinkS_AddMenu(){
	global $zbp;
	echo '<a href="'. $zbp->host .'zb_users/plugin/KandyLinkS/main.php?edit=navbar" class="kandyLink"><span class="m-left">导航管理</span></a>';
}

function KandyLinkS_ModuleMenu(){
	global $zbp;

	$cfg=$zbp->Config('KandyLinkS');

	$array=$zbp->GetModuleList(
		array('*'),
		array(array('=','mod_Source','plugin_KandyLink'))
	);
	$mods=array();
	foreach($array as $mod){
		$mods[]=$mod->FileName;
	}

	$str='<a href="'. $zbp->host .'zb_users/plugin/KandyLinkS/main.php" class="kandyLink"><span class="m-left">新建链接模块</span></a>';

	$url=$zbp->host .'zb_users/plugin/KandyLinkS/main.php';
	$mod=$cfg->DefaultLinks.'|'.join('|',$mods);
	$str.=<<<JS
		<script type="text/javascript">
			$(function(){
				var kandyLinks="{$mod}".split("|"),mod;
				for(link in kandyLinks){
					if(mod=kandyLinks[link]){
						$(".widget-list .widget_id_"+mod).addClass("kandyLink").find("a:first").attr("href","{$url}"+'?edit='+mod);
						$(".SubMenu").find("a[href*='"+mod+"']").addClass("kandyLink").attr("href","{$url}"+'?edit='+mod);
					}
				}
			});
JS;
	echo $str.'</script>';
}

function KandyLinkS_AddJS(){
	global $zbp;
	echo <<<JS
	// Script for KandyLink by jgpy.cn
	$(function(){
		$(".kandyLinkSub").parents("li").addClass("kandyLink");
		$(".kandyLink").each(function(){
			this.delay=null;
			if($("a:first",this).attr("href")=="#"){
				$(this).click(function(){
					$(this).toggleClass("kandyLinkExpend").find("ul").toggle(400);
					$(this).hasClass("kandyLinkExpend")
					?$(this).append(" <s style='text-decoration:none;cursor:pointer;' title='点击关闭'>&times;</s>")
					:$("s",this).remove();
					return false;
				});
			}else{
				$(this).hover(function(){
					this.delay&&clearTimeout(this.delay);
					this.delay=setTimeout(function(self){
						return function(){
							$(self).addClass("kandyLinkExpend").find("ul").show(400);
						};
					}(this),200);
				},function(){
					this.delay&&clearTimeout(this.delay);
					this.delay=setTimeout(function(self){
						return function(){
							$(self).removeClass("kandyLinkExpend").find("ul").hide(400);
						};
					}(this),200);
				})
			}
		}).css("position",function(i,s){
			if(s=="static") return "relative";
		}).find("ul").hide().css("position","absolute");
	});
JS;
}

?>
