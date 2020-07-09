<?php
#注册插件
RegisterPlugin("HK_Mouse_Fun","ActivePlugin_HK_Mouse_Fun");

function ActivePlugin_HK_Mouse_Fun() {
    Add_Filter_Plugin("Filter_Plugin_Html_Js_Add","HK_Mouse_Fun_Set_Js");
}
function InstallPlugin_HK_Mouse_Fun() {
    global $zbp;
    $zbp->Config('HK_Mouse_Fun')->Mouse_txt="我很帅|我很酷|我很美|不装逼|少年你来对地方了|让老纳收了你";
    $zbp->Config('HK_Mouse_Fun')->Mouse_Select="文字特效";
    $zbp->SaveConfig('HK_Mouse_Fun');
}
function UninstallPlugin_HK_Mouse_Fun() {}

function HK_Mouse_Fun_Set_Js()
{
    global $zbp;
    if(trim($zbp->Config('HK_Mouse_Fun')->Mouse_Select)!="")
    {
        if($zbp->Config('HK_Mouse_Fun')->Mouse_Select='文字特效')
        {
            if(trim($zbp->Config('HK_Mouse_Fun')->Mouse_txt)!="")
            {
                $tmp=explode('|',$zbp->Config('HK_Mouse_Fun')->Mouse_txt);
                $temp=array();
                foreach ($tmp as $t)
                {
                    $temp[]="'".$t."'";
                }
                $txt=implode(',',$temp);

                ?>document.writeln("<script type='text/javascript'>var hk_idx = 0;$(function(){$('body').click(function(e) {var hk_mouse_array = new Array(<?php echo $txt;?>);var $hk_mouse_i = $('<span/>').text(hk_mouse_array[hk_idx]);hk_idx = (hk_idx + 1) % hk_mouse_array.length;var x = e.pageX,y = e.pageY;$hk_mouse_i.css({'z-index': 9999999999,'top': y - 20,'left': x,'position': 'absolute','font-weight': 'bold','color': '#ff6651'});$('body').append($hk_mouse_i);$hk_mouse_i.animate({'top': y - 180,'opacity': 0},1500,function() {$hk_mouse_i.remove();});});});</script>"); <?php
            }
        }
    }
}