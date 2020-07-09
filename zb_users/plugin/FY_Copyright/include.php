<?php
#注册插件
RegisterPlugin("FY_Copyright","ActivePlugin_FY_Copyright");
function ActivePlugin_FY_Copyright() {
   Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags','FY_Copyright_main');	
}

function FY_Copyright_main() {global $zbp;
if ($zbp->Config('FY_Copyright')->Prompt=="1"){$zbp->footer .= '<script type="text/javascript">document.body.oncopy=function(){alert("复制成功！若要转载请转载请注明出处！");}</script>
' . "\r\n";}
if ($zbp->Config('FY_Copyright')->Rightclick=="1"){$zbp->footer .= '<script type="text/Javascript">
document.onselectstart=function(e){return   false;};
</script>' . "\r\n";}
if ($zbp->Config('FY_Copyright')->Banview=="1"){$zbp->footer .= '<script type="text/Javascript">
document.oncontextmenu=function(e){return   false;};
</script>' . "\r\n";}
if ($zbp->Config('FY_Copyright')->jzff=="1"){$zbp->footer .= '<script src="'.$zbp->host.'zb_users/plugin/FY_Copyright/Copyright.js" type="text/javascript"></script>' . "\r\n";}
}
function InstallPlugin_FY_Copyright() {}
function UninstallPlugin_FY_Copyright() {}