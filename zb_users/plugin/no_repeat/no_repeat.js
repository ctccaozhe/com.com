function no_repeat_ajax(element,url){
	$.ajax({
		type: "POST",
		url: ajaxurl + url,
		data: {title:$(element).val(), id:$("#edtID").val()},
		dataType: 'json',
		success: function(data) {
			if (data.msg) {
				$(element).after("<p class='no_repeat_hide' style='color:red;'>"+data.msg+"</p>"); 
			}else{
				$(element).next(".no_repeat_hide").remove();
			}
		}
	});
}
$(function(){
	var no_repeat_get = $.no_repeat_url_get();
	var no_repeat_act = no_repeat_get['act'];
	$(".post_edit #edtTitle").blur(function(){
		no_repeat_ajax(this,"no_repeat_verify_post_title");
	});
	$(".post_edit #edtAlias").blur(function(){
		no_repeat_ajax(this,"no_repeat_verify_post_alias");
	});
	$(".category_edit #edtName").blur(function(){
		no_repeat_ajax(this,"no_repeat_verify_cate_name");
	});
	$(".category_edit #edtAlias").blur(function(){
		no_repeat_ajax(this,"no_repeat_verify_cate_alias");
	});
	$(".tag_edit #edtName").blur(function(){
		no_repeat_ajax(this,"no_repeat_verify_tag_name");
	});
	$(".tag_edit #edtAlias").blur(function(){
		no_repeat_ajax(this,"no_repeat_verify_tag_alias");
	});
	if(no_repeat_act==='MemberNew' || no_repeat_act==='MemberEdt'){
		$("#edtName").blur(function(){
			no_repeat_ajax(this,"no_repeat_verify_mem_name");
		});
		$("#edtAlias").blur(function(){
			no_repeat_ajax(this,"no_repeat_verify_mem_alias");
		});
	}
	$("#edtTitle,#edtName,#edtAlias").focus(function(){
		$(this).next(".no_repeat_hide").remove();
	});
}); 
(function($){
    $.extend({
        no_repeat_url_get:function(){
            var aQuery = window.location.href.split("?");
            var aGET = new Array();
            if(aQuery.length > 1){
                var aBuf = aQuery[1].split("&");
                for(var i=0, iLoop = aBuf.length; i<iLoop; i++){
                    var aTmp = aBuf[i].split("=");
                    aGET[aTmp[0]] = aTmp[1];
                }
            }
            return aGET;
        },
    });
})(jQuery);