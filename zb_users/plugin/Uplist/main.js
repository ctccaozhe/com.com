/*-------------------
*Description:        By www.yiwuku.com
*Website:            https://app.zblogcn.com/?auth=3ec7ee20-80f2-498a-a5dd-fda19b198194
*Author:             尔今 erx@qq.com
*update:             2017-11-25(Last:2020-03-17)
-------------------*/

$(function(){
	var uppAct = '<div class="uplist-act"><input type="button" class="button checkall" value="全选/取消"><input type="button" class="button reverse" value="反选"><input type="button" class="button delmany" value="批量删除"></div>';
	$(".search, .pagebar").append(uppAct);
	$(".search").after('<ul class="uplist"></ul>');
	$(".uplist").next(".tableFull").hide();
	var upp_mab = $("#divMain2 .tableFull tr:not(:first)");
	var upp_ulid_str = "", upp_flink_str = "", upp_fname_str = "", upp_fsize_str = "", upp_fctrl_str = "", upp_plist = "";
    upp_mab.each(function () {     
        upp_ulid_str += $(this).children("td:eq(0)").eq(0).text() + ',';
        upp_flink_str += $(this).children("td:eq(2)").children("a").attr("href") + ',';
        upp_fname_str += $(this).children("td:eq(2)").text() + ',';
        upp_fsize_str += $(this).children("td:eq(4)").text()*1 + ',';
        upp_fctrl_str += $(this).children("td:eq(6)").children("a").attr("href") + ',';
    });
	var upp_ulid = upp_ulid_str.split(","),
		upp_flink = upp_flink_str.split(","),
		upp_fname = upp_fname_str.split(","),
		upp_fsize = upp_fsize_str.split(","),
		upp_fctrl = upp_fctrl_str.split(","),
		fnex_arr = ['jpg','jpeg','JPG','gif','png','webp','bmp','ico'];
    for (var i=0; i < upp_flink.length-1; i++){
    	var ftarr = upp_flink[i].split("."),
    		ftstr = ftarr[ftarr.length-1],
    		fsize = (upp_fsize[i]/1024).toFixed(2)+"KB",
    		aplink = '<a href="'+upp_flink[i]+'" target="_blank" title="打开'+upp_fname[i]+'" class="mp"><img src="'+upp_flink[i]+'"></a>';
    	if(upp_fsize[i] >= 1048576){
    		fsize = (upp_fsize[i]/1048576).toFixed(2)+"MB";
    	}
    	if($.inArray(ftstr, fnex_arr)<0){
    		aplink = '<a href="'+upp_flink[i]+'" target="_blank" title="下载'+upp_fname[i]+'" class="mp"><img src="'+bloghost+'zb_users/plugin/Uplist/nopic.png"></a>';
    	}
	    upp_plist += '<li>'+aplink+'<div class="vc"><label><input type="checkbox" value="'+upp_ulid[i]+'" name="ulID[]" class="ulid" data-check="0"><span>'+fsize+'</span></label><a href="javascript:;" class="cpurl">复制地址</a><input type="text" value="'+upp_flink[i]+'" class="flink"><a href="'+upp_fctrl[i]+'" title="删除" onclick="return window.confirm(\'确定删除'+upp_fname[i]+'？\');" class="del">&times;</a></div></li>';
    }
    $(".uplist").html(upp_plist);
	$(document).on('click','.cpurl', function(){
        var e=$(this).next("input");
        e.select();
        document.execCommand("Copy");
        $(this).text("已操作！");
	});
	function erxUpcArray(n){
		var valArr = [];
		$("input[name='"+n+"[]']:checked").each(function(i){
			valArr[i] = $(this).val();
		});
		var upc = valArr.join(',');
		return upc;
	}
	var ulID = [], ulNum = 0, isCheck1 = 0, upToken = $("#upload").attr("action").split("csrfToken=")[1];
	function erxCheckUlid(){
		ulID = [erxUpcArray("ulID")];
		ulNum = erxUpcArray("ulID").split(',').length;
		if(ulNum == 1 && ulID[0] == ''){
			ulNum = 0;
		}
		//console.log(ulNum);
		//console.log(ulID);
	}
	$(document).on('click', '.ulid', function(){
		var c = $(this).attr("data-check")*1;
		if(!c){
			$(this).attr("data-check", 1);
			$(this).parents(".vc").addClass("selected");
		}else{
			$(this).attr("data-check", 0);
			$(this).parents(".vc").removeClass("selected");
		}
		erxCheckUlid();
	});
	$(document).on('click', '.checkall', function(){
        if(isCheck1) {
            $(".ulid").each(function() {
                this.checked = false;
                $(this).attr("data-check", 0);
                $(this).parents(".vc").removeClass("selected");
            });
            isCheck1 = 0;
        }else{
            $(".ulid").each(function() {
                this.checked = true;
                $(this).attr("data-check", 1);
                $(this).parents(".vc").addClass("selected");
            });
            isCheck1 = 1;
        }
		erxCheckUlid();
	});
	$(document).on('click', '.reverse', function(){
        $(".ulid").each(function () {
            var c = $(this).attr("data-check")*1;
			if(!c){
				this.checked = true;
				$(this).attr("data-check", 1);
				$(this).parents(".vc").addClass("selected");
			}else{
				this.checked = false;
				$(this).attr("data-check", 0);
				$(this).parents(".vc").removeClass("selected");
			}
        });
		erxCheckUlid();
	});
	$(document).on('click', '.delmany', function(){
		if(!ulNum){
			alert("没有选择任何文件！");
			return false;
		} 
		if(confirm("确定删除这"+ulNum+"个文件？")==false){
			return false;
		} 
		$.post(bloghost+'zb_users/plugin/Uplist/delbat.php',{
			"ulID":ulID,
			"token":upToken,
			"mustdo":1,
			},function(data){
				var s =data;
				if((s.search("faultCode")>0)&&(s.search("faultString")>0)){
					alert("批量删除出错！请尝试单个删除");
				}else{
					//alert(s);
					window.location.reload();
				}
			}
		);
	});
    $(".pagebar").before('<div class="uplist-tip">当前附件管理正由《图式附件管理》插件（<a href="'+bloghost+'zb_users/plugin/AppCentre/main.php?auth=3ec7ee20-80f2-498a-a5dd-fda19b198194" target="_blank">尔今作品</a>）辅助实现可视化效果，如有疑问或需求建议敬请前往 <a href="http://www.yiwuku.com" target="_blank">作者主页</a> 联系洽询<br>较大单张图片显示可能会有延迟感（1024KB=1MB），图片数量过多导致页面加载缓慢时，可修改“网站设置->页面设置->后台每页文章显示数量”设置<br>灰底无图显示时表示仅有上传信息存于数据库，对应文件则可能已被移除，附件默认存放目录：'+bloghost+'zb_users/upload/</div>');
});