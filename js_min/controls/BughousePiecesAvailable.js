function _____UZ(parent){hZZ.iZZ(this,parent);this.width=2;this.height=3;this._____VZ=[_tZZZ,__DZZZ,_uZZZZ,__LZZZ,vZZZZ];this.colour=_____d;this._____WZ=[];this._____XZ=[];for(var O=0;O<this._____VZ.length;O++){this._____XZ[this._____VZ[O]]=0;}
this._____YZ=new h(this);this.__XZZ=new h(this);this._____ZZ=_fZZZ.____$ZZ;this._dZZZ={};this.____aZZ();this.____bZZ=45;this.____cZZ=G("/img/board/piece");this.piece_style=_.a.___GZ.___QZ.__CZZ.__wZ();this.____dZZ=new lZZ(this,function(){return this.____bZZ;},function(value){this.____bZZ=value;this.mZZ();});this.__CZZ=new lZZ(this,function(){return this.piece_style;},function(value){this.piece_style=value;this.mZZ();});this.___VZ=new lZZ(this,function(){return this.colour;},function(value){this.colour=value;this.mZZ();});_.a.___GZ.___QZ.__wZZ._zZZ(this,function(data,sender){this.__CZZ.BZZ(sender.__CZZ.__wZ());});this.nZZ();}
_____UZ.prototype.nZZ=function(){var self=this;v._rZ(this.pZZ,{border:"1px solid #cbcbcb"});this.__SZZ=UZZ(this.pZZ);v._rZ(this.__SZZ,{position:"absolute"});var type,_____yZ,____eZZ,____fZZ,____gZZ;for(var O=0;O<this._____VZ.length;O++){type=this._____VZ[O];____eZZ=UZZ(this.__SZZ);____fZZ=UZZ(____eZZ);____gZZ=UZZ(____eZZ);_____yZ={FZZ:____eZZ,pZZ:____fZZ,____hZZ:____gZZ,_cZ:type};v._rZ(____eZZ,{position:"absolute"});v._rZ(____fZZ,{position:"absolute"});v._rZ(____gZZ,{fontSize:9,color:"#263ebc",position:"absolute",top:0,right:0,});v.w(____fZZ,"mousedown",(function(wZZZZ){return function(r){self.____iZZ(wZZZZ,r);};})(_____yZ));v.w(____fZZ,"mouseup",(function(wZZZZ){return function(r){self.____jZZ(wZZZZ,r);};})(_____yZ));v.w(window,"mousemove",function(r){self.____kZZ(r);});____gZZ.innerHTML="3";this._____WZ.push(_____yZ);}
this.mZZ();}
_____UZ.prototype.mZZ=function(){var width=this.width*this.____bZZ;var height=this.height*this.____bZZ;v._rZ(this.pZZ,{width:width,height:height});v._rZ(this.__SZZ,{width:width,height:height});var ___p=0;var ___i=0;var type,piece,_____yZ,____lZZ;for(var O=0;O<this._____WZ.length;O++){_____yZ=this._____WZ[O];type=this._____VZ[O];piece=_$ZZZ.piece(type,this.colour);____lZZ=this._____XZ[type];if(___i>=this.width){___p++;___i=0;}
v._rZ(_____yZ.FZZ,{display:(____lZZ>0)?"block":"none",top:___p*this.____bZZ,left:___i*this.____bZZ,width:this.____bZZ,height:this.____bZZ});v._rZ(_____yZ.pZZ,{width:this.____bZZ,height:this.____bZZ,backgroundImage:v._CZ(this.____cZZ+"/"+this.piece_style+"/"+this.____bZZ+"/"+_WZZZ.____yZ(piece)+".png")});_____yZ.____hZZ.innerHTML=____lZZ;___i++;}}
_____UZ.prototype.aZ=function(){for(var O=0;O<this._____VZ.length;O++){this._____XZ[this._____VZ[O]]=0;}
this.mZZ();}
_____UZ.prototype._P=function(piece){if(_$ZZZ.colour(piece)===this.colour){this._____XZ[_$ZZZ.type(piece)]++;this.mZZ();}}
_____UZ.prototype._S=function(piece){if(_$ZZZ.colour(piece)===this.colour){this._____XZ[_$ZZZ.type(piece)]--;this.mZZ();}}
_____UZ.prototype.__PZ=function(wZZZZ){return(wZZZZ%this.width);}
_____UZ.prototype.__QZ=function(wZZZZ){return((wZZZZ-this.__PZ(wZZZZ))/this.width);}
_____UZ.prototype.____mZZ=function(_____yZ,wZZZZ){var __PZ,__QZ;var ____BZ=this.__QZ(wZZZZ);var ____CZ=this.__PZ(wZZZZ);__PZ=this.____bZZ*____CZ;__QZ=this.____bZZ*((this.height-1)-____BZ);v._rZ(_____yZ.FZZ,{top:__QZ,left:__PZ});}
_____UZ.prototype.____nZZ=function(_____yZ){v._rZ(_____yZ.pZZ,{top:0,left:0});}
_____UZ.prototype.____oZZ=function(_____yZ,__PZ,__QZ){var _kZ=v._qZ(_____yZ.FZZ);v._rZ(_____yZ.pZZ,{top:__QZ-_kZ[_pZ],left:__PZ-_kZ[_oZ]});}
_____UZ.prototype.____aZZ=function(){this._dZZZ={____pZZ:false,____qZZ:null,_qZZ:false,____rZZ:false,____sZZ:false,_aZZZ:null,_mZZZ:null,____tZZ:0,____uZZ:0};this.____vZZ=null;}
_____UZ.prototype.____iZZ=function(_____yZ,r){r.preventDefault();if(this.____wZZ(r)&&this._____XZ[_____yZ._cZ]>0){var _kZ=v._qZ(_____yZ.FZZ);this.____xZZ(_____yZ);if(!this._dZZZ._qZZ){var data={_aZZZ:_$ZZZ.piece(_____yZ._cZ,this.colour),_bZZZ:false};this.__XZZ.t(data);if(!data._bZZZ){this._dZZZ._qZZ=true;this._dZZZ.____pZZ=true;this._dZZZ._mZZZ=_____yZ;this._dZZZ._aZZZ=_$ZZZ.piece(_____yZ._cZ,this.colour);this._dZZZ.____tZZ=r.pageX-_kZ[_oZ];this._dZZZ.____uZZ=r.pageY-_kZ[_pZ];}}}}
_____UZ.prototype.____kZZ=function(r){r.preventDefault();if(this._dZZZ.____pZZ){this._dZZZ.____sZZ=true;}
if(this._dZZZ._qZZ&&this._dZZZ.____sZZ){this.____oZZ(this._dZZZ._mZZZ,r.pageX-this._dZZZ.____tZZ,r.pageY-this._dZZZ.____uZZ);}}
_____UZ.prototype.____jZZ=function(_____yZ,r){r.preventDefault();var ____yZZ=this._dZZZ._mZZZ;this._dZZZ.____pZZ=false;this.____vZZ=null;if(this._dZZZ.____sZZ){if(!this.____wZZ(r)){this._____YZ.t({_aZZZ:this._dZZZ._aZZZ,h:r,_dZZZ:this._dZZZ});}
this.____nZZ(____yZZ);this.____aZZ();this.____zZZ(____yZZ);}
else{this.____aZZ();}}
_____UZ.prototype.____xZZ=function(_____yZ){v._rZ(_____yZ.pZZ,{zIndex:_fZZZ.____AZZ});}
_____UZ.prototype.____zZZ=function(_____yZ){v._rZ(_____yZ.pZZ,{zIndex:_fZZZ.____BZZ});}
_____UZ.prototype.____wZZ=function(r){var __PZ=r.pageX;var __QZ=r.pageY;var _kZ=v._qZ(this.__SZZ);var top=_kZ[_pZ]+1;var right=_kZ[_oZ]+this.____CZZ()-1;var bottom=_kZ[_pZ]+this.____DZZ()-1;var left=_kZ[_oZ]+1;return!(__PZ<left||__PZ>right||__QZ<top||__QZ>bottom);}
_____UZ.prototype.____EZZ=function(_____yZ){v._rZ(_____yZ.FZZ,{backgroundColor:"#808080"});}
_____UZ.prototype.____FZZ=function(_____yZ){v._rZ(_____yZ.FZZ,{backgroundColor:"inherit"});}
_____UZ.prototype.______V=function(____CZ,____BZ){return(____BZ*this.width+____CZ);}
_____UZ.prototype.____CZZ=function(){return this.____bZZ*this.width;}
_____UZ.prototype.____DZZ=function(){return this.____bZZ*this.height;}
_____UZ.prototype._CZZ=function(){if(this._dZZZ.____rZZ){this.____FZZ(this._dZZZ._mZZZ);this.____aZZ();}}