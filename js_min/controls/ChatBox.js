function ____GZZ(parent){hZZ.iZZ(this,parent);this.____HZZ=new h(this);this.width=null;this.___A=true;this.___B=new lZZ(this,function(){return this.___A;},function(value){this.___A=value;this.mZZ();});this.RZZ=new lZZ(this,function(){return this.width;},function(value){this.width=value;this.mZZ();});this.nZZ();}
____GZZ.prototype.nZZ=function(){this.____IZZ=UZZ(this.pZZ);this.____JZZ=UZZ(this.____IZZ);v._rZ(this.____JZZ,{fontSize:11,color:"#303030",border:"1px solid #cbcbcb",height:80,padding:5,overflowX:"hidden",overflowY:"auto",backgroundColor:"#ffffff"});this.____KZZ=UZZ(this.____IZZ);this.____LZZ=new _aZZ(this.____KZZ);this.____MZZ=new Button(this.____KZZ,"Send");this.____LZZ.RZZ.BZZ(200);v._rZ(this.____KZZ,{marginTop:1});v.w(this.pZZ,"click",function(){this.____LZZ.___G();},this);this.____MZZ.kZZ._zZZ(this,function(){this.____NZZ();});this.____LZZ._cZZ._zZZ(this,function(r){if(r.keyCode===13){this.____NZZ();}});this.mZZ();}
____GZZ.prototype.mZZ=function(){if(this.width!==null){v._rZ(this.pZZ,{width:this.width});v._rZ(this.____LZZ.oZZ,{width:this.width-70});v._rZ(this.____MZZ.oZZ,{width:55});}
else{v._rZ(this.pZZ,{width:"auto"});}
this.____LZZ.___B.BZZ(this.___A);this.____MZZ.___B.BZZ(this.___A);}
____GZZ.prototype.____OZZ=function(){var __kZ=UZZ(this.____JZZ);var ____PZZ;var ____QZZ;for(var O=0;O<arguments.length;O++){____PZZ=arguments[O];if(___z(____PZZ)){____QZZ=_hZ("*span");__kZ.appendChild(____QZZ);____QZZ.innerHTML=____PZZ;}
else{__kZ.appendChild(____PZZ);}}
v._rZ(__kZ,{paddingBottom:3});this.____JZZ.scrollTop=this.____JZZ.scrollHeight;}
____GZZ.prototype.____NZZ=function(){this.____HZZ.t({____RZZ:this.____LZZ.NZZ.__wZ()});this.____LZZ.NZZ.BZZ("");this.____LZZ.oZZ.focus();}