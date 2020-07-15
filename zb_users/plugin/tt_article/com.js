$(document).ready(function() { 
// 全选/取消全部 
$("#checkAllChange").click(function() { 
if (this.checked == true) { 
$(".Articleid").each(function() { 
this.checked = true; 
}); 
} else { 
$(".Articleid").each(function() { 
this.checked = false; 
}); 
} 
}); 
// 全选 
$("#checkAll").click(function() { 
$(".Articleid").each(function() { 
this.checked = true; 
}); 
}); 
// 取消全部 
$("#removeAll").click(function() { 
$(".Articleid").each(function() { 
this.checked = false; 
}); 
}); 
// 反选 
$("#reverse").click(function() { 
$(".Articleid").each(function() { 
if (this.checked == true) { 
this.checked = false; 
} else { 
this.checked = true; 
} 
}) 
}); 
//批量删除 
$("#delAll").click(function() { 
  var ids = '';
  $(".Articleid").each(function() {
    if ($(this).is(':checked')) {
      ids += ',' + $(this).val(); //逐个获取id
    }
  });
  ids = ids.substring(1); // 对id进行处理，去除第一个逗号
  if (ids.length == 0) {
    alert('请选择要删除的文章选项');
  } else {
    if (confirm("确定删除？删除后将无法恢复。")) {
     
		$.ajax({
		type:'post',
		async:true,
		url:bloghost + "zb_users/plugin/tt_article/article_all.php?act=del&"+new Date(),
		data:{
		postid:ids
		},
		dataType:'json',
		success:function(data){
			if(data.s="ok"){
			 $(".Articleid").each(function() {
				if ($(this).is(':checked')) {
				  $(this).parent().parent().remove(); //逐个删除
				}
			  });
			  alert('删除成功'); 	
			}
		},
		error:function(data){
		   alert("失败！");   
           location.href = window.location.href;
		}
		});	  
	  
	  
    }
  }




}); 
//批量更改分类、状态、作者、置顶
$("#tt_article_All").click(function() { 
  var ids = '';
  var cateid = $("#cmbCateID").val();//分类
  var cmbStatus = $("#cmbStatus").val();//状态
  var cmbMem = $("#cmbMem").val();//作者
  var cmbtop = $("#cmbIsTop").val();//是否置顶
  var cmbtop_status = $("#cmbtop_status").val();//置顶类型
  $(".Articleid").each(function() {
    if ($(this).is(':checked')) {
      ids += ',' + $(this).val(); //逐个获取id
    }
  });
  ids = ids.substring(1); // 对id进行处理，去除第一个逗号
  if (ids.length == 0) {
    alert('请选择要更改的文章选项');
  } else {
    if (confirm("确定更改么？")) {
     
		$.ajax({
		type:'post',
		async:true,
		url:bloghost + "zb_users/plugin/tt_article/article_all.php?act=all&"+new Date(),
		data:{
		postid:ids,
		cateid:cateid,
		cmbStatus:cmbStatus,
		cmbMem:cmbMem,
		cmbtop:cmbtop,
		cmbtop_status:cmbtop_status,
		},
		dataType:'json',
		success:function(data){
			if(data.s="ok"){
			alert('更改成功'); 	
			//location.href = bloghost+'zb_users/plugin/tt_article/tt_article_ArticleMng.php';
			location.href = window.location.href;
			}	  
		},
		error:function(data){
		   alert("更改失败！");
          // location.href = bloghost+'zb_users/plugin/tt_article/tt_article_ArticleMng.php';		   
           location.href = window.location.href;		   
		}
		});	  
	  
	  
    }
  }




}); 
}); 
