function fuckyou(){
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
function hehe(){
if( (window.console && (console.firebug || console.table && /firebug/i.test(console.table()) )) || (typeof opera == 'object' && typeof opera.postError == 'function' && console.profile.length > 0)){
 fuckyou();
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