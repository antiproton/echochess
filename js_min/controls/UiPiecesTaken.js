function ____xZZZ(parent){hZZ.iZZ(this,parent,true);this.______KZ=_____d;this.____bZZ=20;this.___MZZZ="/board/piece";this.piece_style=_.a.___GZ.___QZ.__CZZ.__wZ();this._QZZZ=new lZZ(this,function(){return this.______KZ;},function(value){this.______KZ=value;this.mZZ();});this.___HZZ=[];this.____yZZZ=[];this.____zZZZ=[];this.____AZZZ=[];this.____BZZZ=[];this.____CZZZ();this.____DZZZ();this._yZZZ=false;this.nZZ();}
____xZZZ.prototype.nZZ=function(){this.border=UZZ(this.pZZ);this.TZZ=UZZ(this.border);v._rZ(this.TZZ,{margin:"0 auto"});var _____c=[_____d,kZZZZ];var colour,type;var _____VZ=[__DZZZ,_uZZZZ,__LZZZ,vZZZZ];this.____EZZZ=[];this.____FZZZ=[];this.____GZZZ=[];var ____HZZZ,____IZZZ,__kZ;for(var O=0;O<_____c.length;O++){colour=_____c[O];this.____EZZZ[colour]=UZZ(this.TZZ);v._rZ(this.____EZZZ[colour],{paddingTop:6});____HZZZ=UZZ(this.____EZZZ[colour]);____IZZZ=UZZ(this.____EZZZ[colour]);this.____GZZZ[colour]=UZZ(this.____EZZZ[colour]);v._rZ(this.____GZZZ[colour],{textAlign:"center",fontSize:11,padding:"4px 0 4px 0"});this.____FZZZ[colour]=[];for(var _xZ=0;_xZ<_____VZ.length;_xZ++){type=_____VZ[_xZ];this.____FZZZ[colour][type]=[];for(var ___y=0;___y<2;___y++){__kZ=UZZ(____IZZZ);this.____FZZZ[colour][type][___y]=__kZ;this.____BZZZ.push(__kZ);}}
___X(____IZZZ);this.____FZZZ[colour][_tZZZ]=[];for(var ___y=0;___y<8;___y++){__kZ=UZZ(____HZZZ);this.____FZZZ[colour][_tZZZ][___y]=__kZ;this.____BZZZ.push(__kZ);}
___X(____HZZZ);}
for(var O=0;O<this.____BZZZ.length;O++){v._rZ(this.____BZZZ[O],{cssFloat:"left"});}
this._yZZZ=true;this.mZZ();}
____xZZZ.prototype.mZZ=function(){v._rZ(this.TZZ,{width:this.____bZZ*8});for(var O=0;O<this.____BZZZ.length;O++){v._rZ(this.____BZZZ[O],{height:this.____bZZ});}
var _D=[];for(var O=0;O<this.____zZZZ.length;O++){_D.push(this.____zZZZ[O]);}
this.aZ();for(var O=0;O<_D.length;O++){this._P(_D[O]);}
this.____JZZZ();}
____xZZZ.prototype.aZ=function(){this.____KZZZ();this.____CZZZ();this.____DZZZ();this.____JZZZ();}
____xZZZ.prototype._P=function(FZZZZ){var piece=new _aZZZ(FZZZZ);var ____LZZZ=this.____LZZZ(piece.___VZ);if(piece._cZ===_tZZZ){this.____MZZZ(this.____FZZZ[____LZZZ][piece._cZ][this.____yZZZ[FZZZZ]],FZZZZ);this.____yZZZ[FZZZZ]++;}
else{if(this.____yZZZ[FZZZZ]>=2){var ____NZZZ=_$ZZZ.piece(_tZZZ,piece.___VZ);this.____MZZZ(this.____FZZZ[____LZZZ][_tZZZ][this.____yZZZ[____NZZZ]],FZZZZ);this.____yZZZ[____NZZZ]++;}
else{this.____MZZZ(this.____FZZZ[____LZZZ][piece._cZ][this.____yZZZ[FZZZZ]],FZZZZ);this.____yZZZ[FZZZZ]++;}}
this.___HZZ[FZZZZ]++;this.____zZZZ.push(FZZZZ);this.____AZZZ[piece.___VZ]+=_aZZZ.tZZZZZ[piece._cZ];this.____JZZZ();}
____xZZZ.prototype._S=function(FZZZZ){if(this.___HZZ[FZZZZ]>0){var piece=new _aZZZ(FZZZZ);this.___HZZ[FZZZZ]--;}}
____xZZZ.prototype.____MZZZ=function(UZZ,FZZZZ){var width=(FZZZZ===zZZZZ?0:this.____bZZ);var _____LZZ=(FZZZZ===zZZZZ?"none":_.a.___YZ(this.___MZZZ+"/"+this.piece_style+"/"+this.____bZZ+"/"+_WZZZ.____yZ(FZZZZ)+".png"));v._rZ(UZZ,{width:width,backgroundImage:_____LZZ});}
____xZZZ.prototype.____LZZZ=function(colour){return(this.______KZ===_____d?colour:_$ZZZ._RZZZ(colour));}
____xZZZ.prototype.____KZZZ=function(){for(var O=0;O<this.____BZZZ.length;O++){this.____MZZZ(this.____BZZZ[O],zZZZZ);}}
____xZZZ.prototype.____CZZZ=function(){var _____c=[_____d,kZZZZ];var ____OZZZ=[_tZZZ,__DZZZ,_uZZZZ,__LZZZ,vZZZZ];var type,colour;for(var O=0;O<_____c.length;O++){colour=_____c[O];for(var _xZ=0;_xZ<____OZZZ.length;_xZ++){type=____OZZZ[_xZ];this.___HZZ[_$ZZZ.piece(type,colour)]=0;this.____yZZZ[_$ZZZ.piece(type,colour)]=0;}}
this.____zZZZ=[];}
____xZZZ.prototype.____DZZZ=function(){var _____c=[_____d,kZZZZ];var type,colour;for(var O=0;O<_____c.length;O++){colour=_____c[O];this.____AZZZ[colour]=0;}}
____xZZZ.prototype.____JZZZ=function(){var _____c=[_____d,kZZZZ];var colour,____LZZZ;var K="";for(var O=0;O<_____c.length;O++){colour=_____c[O];____LZZZ=this.____LZZZ(colour);if(this.____AZZZ[colour]>0){K=this.____AZZZ[colour];}
this.____GZZZ[____LZZZ].innerHTML=K;}}