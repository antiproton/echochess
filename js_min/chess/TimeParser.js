var ____eZ=new(function(){this.HZZZZZ=/\d/;this.IZZZZZ=/[ywdhms]/i;this.JZZZZZ={"s":1,"m":60,"h":60,"d":24,"w":7,"y":52};this.KZZZZZ={};var LZZZZZ=1;for(var MZZZZZ in this.JZZZZZ){LZZZZZ*=this.JZZZZZ[MZZZZZ];this.KZZZZZ[MZZZZZ]=LZZZZZ;}
this.NZZZZZ="m";this.OZZZZZ="";this.PZZZZZ="";this.QZZZZZ=-1;this.RZZZZZ=[];this.___lZZ=null;this.SZZZZZ=0;})();____eZ.TZZZZZ=function(K,UZZZZZ){UZZZZZ=UZZZZZ||this.NZZZZZ;var eZZZZZ=true;this.VZZZZZ(K);this.T();this.V();while(!this._n()){this.SZZZZZ+=this.WZZZZZ(UZZZZZ);}
this.___lZZ=this.SZZZZZ;return this.SZZZZZ;}
____eZ.____$Z=function(time,XZZZZZ,UZZZZZ){if(___mZ(XZZZZZ)){XZZZZZ=true;}
UZZZZZ=UZZZZZ||null;var K="0";if(time>0){var KZZZZZ={};var YZZZZZ={};var ZZZZZZ=time;var _______,______$;var ______a=[];for(var MZZZZZ in this.KZZZZZ){______a.push({______b:MZZZZZ,______c:this.KZZZZZ[MZZZZZ]});}
______a.reverse();for(var O=0;O<______a.length;O++){KZZZZZ[______a[O].______b]=______a[O].______c;}
for(var MZZZZZ in KZZZZZ){_______=KZZZZZ[MZZZZZ];if(ZZZZZZ>=_______){______$=Math.floor(ZZZZZZ/_______);ZZZZZZ=ZZZZZZ%_______;YZZZZZ[MZZZZZ]=______$;}}
var ______d=[];for(var MZZZZZ in YZZZZZ){if(MZZZZZ===UZZZZZ&&time%KZZZZZ[MZZZZZ]===0&&!XZZZZZ){______d.push(YZZZZZ[MZZZZZ]);}
else{______d.push(YZZZZZ[MZZZZZ]+MZZZZZ);}}
K=______d.join("");}
return K;}
____eZ.______e=function(mtime,______f){var YZZZZZ=[];var time=Math.floor(mtime/u);var ______g=Math.floor((mtime%u)/(u/10));var ZZZZZZ=time;var ______h;var _v,K;var ______i;var ______j=true;var ______k=false;var display;var ______l=[24,60,60,1];var ______m=2;var ______n=2;for(var O=0;O<______l.length;O++){______i=______l[O];for(var _xZ=O+1;_xZ<______l.length;_xZ++){______i*=______l[_xZ];}
______h=ZZZZZZ%______i;_v=(ZZZZZZ-______h)/______i;K=""+_v;if(!______j){while(K.length<______n){K="0"+K;}}
if(_v>0||______k||O>=______l.length-______m){YZZZZZ.push(K);______j=false;}
ZZZZZZ=______h;if(_v>0){______k=true;}}
display=YZZZZZ.join(":");if(______f){display+="."+______g;}
return display;}
____eZ.VZZZZZ=function(K){this.OZZZZZ=K;this.RZZZZZ=K.split("");this.T();}
____eZ.T=function(){this.QZZZZZ=-1;this.PZZZZZ="";this.SZZZZZ=0;}
____eZ.WZZZZZ=function(UZZZZZ){var ______o=this.______p();var ______q=this.______r(UZZZZZ);var ______s=this.KZZZZZ[______q];return ______o*______s;}
____eZ.______p=function(){while(!this.match(this.HZZZZZ)&&!this._n()){this.V();}
var K="";var _v=0;if(!this._n()){while(this.match(this.HZZZZZ)){K+=this._u();}
if(K.length>0){_v=parseInt(K);}}
return _v;}
____eZ.______r=function(UZZZZZ){var ______q=UZZZZZ;while(!this.match(this.IZZZZZ)&&!this._n()){this.V();}
if(!this._n()){______q=this._u();}
return ______q.toLowerCase();}
____eZ.V=function(){this.QZZZZZ++;if(this._n()){this.PZZZZZ="";}
else{this.PZZZZZ=this.RZZZZZ[this.QZZZZZ];}}
____eZ._n=function(){return(this.QZZZZZ>=this.OZZZZZ.length);}
____eZ._u=function(){var K=this.PZZZZZ;this.V();return K;}
____eZ.X=function(K){return(this.PZZZZZ===K);}
____eZ.match=function(_z){return(_z.test(this.PZZZZZ));}