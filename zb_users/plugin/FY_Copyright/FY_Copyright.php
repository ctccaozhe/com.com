<?php


function FY_Copyright_main() {global $zbp;
$zbp->footer .= '<script>function fuckyou(){
 window.close();
 window.location="about:blank";
}
 function ck() {
 console.profile();
 console.profileEnd();
if(console.clear) { console.clear() };
 if (typeof console.profiles =="object"){
 return console.profiles.length > 0;
 }
}

if(typeof console.profiles =="object"&&console.profiles.length > 0){
fuckyou();
}
}
hehe();
window.onresize = function(){
if((window.outerHeight-window.innerHeight)>200)
fuckyou();
}
</script>
' . "\r\n";


	}




?>