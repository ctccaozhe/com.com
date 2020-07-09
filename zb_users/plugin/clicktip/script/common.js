$(function(){
	var keyindex = "0";
	$("body").click(function(e){
		var key = new Array("富强","民主","文明","和谐","自由","平等","公正","法治","爱国","敬业","诚信","友善");
		var $i = $("<span/>").text(key[keyindex]);
		keyindex = (keyindex + 1) % key.length;
		var x = e.pageX, y = e.pageY;
		$i.css({
			"z-index":9999999999,
			"top":y - 20,
			"left":x,
			"position":"absolute",
			"font-size":"14px",
			"color":"#004c98",
			"font-family":"microsoft yahei",
			"font-style":"normal"
		});
		$("body").append($i);
		$i.animate({"top":y-200,"opacity":0},1000,function(){$i.remove();});
	});
});