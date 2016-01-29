function _xZZZ(parent){hZZ.iZZ(this,parent,true);this._yZZZ=false;this._zZZZ=null;this._AZZZ=null;this._BZZZ=null;this.cZZZ();this.__VZZ();this.nZZ();this._CZZZ();}
_xZZZ.prototype.__VZZ=function(){this.___m=new h(this);this._DZZZ=new h(this);this._EZZZ=new h(this);this._FZZZ=new h(this);}
_xZZZ.prototype.cZZZ=function(){}
_xZZZ.prototype._CZZZ=function(){this._GZZZ=new __RZZ(this.__WZZ,this._HZZZ);this._IZZZ.NZZ.BZZ(this._GZZZ.__YZZ._pZZZ());this._GZZZ._JZZZ._zZZ(this,function(){this._IZZZ.NZZ.BZZ(this._GZZZ.__YZZ._pZZZ());});}
_xZZZ.prototype.nZZ=function(){var NZZZ,_KZZZ;var ___X;_KZZZ=UZZ(this.pZZ);v._rZ(_KZZZ,{padding:"1em 0"});this._LZZZ={__SZZ:UZZ(_KZZZ),_MZZZ:UZZ(_KZZZ)};this._NZZZ={__SZZ:UZZ(this._LZZZ.__SZZ),_MZZZ:UZZ(this._LZZZ._MZZZ)};for(var _F in this._LZZZ){NZZZ=this._LZZZ[_F];v._rZ(NZZZ,{cssFloat:"left"});}
___X=UZZ(_KZZZ);_KZZZ.appendChild(___X);v._rZ(___X,{clear:"both"});v._rZ(this._NZZZ._MZZZ,{paddingLeft:5});NZZZ=UZZ(this._NZZZ.__SZZ);this.__WZZ=new _fZZZ(NZZZ);this.__WZZ.__FZZ.BZZ(true);this.__WZZ._OZZZ.BZZ(false);NZZZ=UZZ(this._NZZZ.__SZZ);v._rZ(NZZZ,{marginTop:".3em",marginLeft:"1em"});this._PZZZ=new Button(NZZZ,"Flip board");this._PZZZ.kZZ._zZZ(this,function(){this.__WZZ._QZZZ.BZZ(_$ZZZ._RZZZ(this.__WZZ._QZZZ.__wZ()));});NZZZ=UZZ(this._NZZZ._MZZZ);this._HZZZ=new _SZZZ(NZZZ);this._HZZZ._TZZZ._zZZ(this,function(){var _UZZZ=this._HZZZ._VZZZ;if(_UZZZ!==null){this._IZZZ.NZZ.BZZ(_UZZZ._WZZZ);}
else{this._IZZZ.NZZ.BZZ(this._GZZZ._XZZZ._pZZZ());}
if(_UZZZ!==null&&_UZZZ._YZZZ!==null&&_UZZZ._ZZZZ!==null){this.$ZZZZ(_UZZZ);}
else if(_UZZZ==null&&this._zZZZ!==null&&this.aZZZZ!==null){this.bZZZZ();}});NZZZ=UZZ(this._NZZZ._MZZZ);this.cZZZZ=new cZZZZ(NZZZ);this.cZZZZ._HZZZ=this._HZZZ;_KZZZ=UZZ(this._NZZZ._MZZZ);NZZZ=UZZ(_KZZZ);var dZZZZ=new tZZ(NZZZ,"FEN");NZZZ=UZZ(_KZZZ);this._IZZZ=new _aZZ(NZZZ);this._IZZZ._bZZ._zZZ(this,function(data){if(data.XZZ!==data.YZZ){this._GZZZ.eZZZZ(this._IZZZ.NZZ.__wZ());}});this._IZZZ.RZZ.BZZ("100%");v._rZ(_KZZZ,{marginTop:"1em"});this.fZZZZ=new FZZ(this._NZZZ._MZZZ);NZZZ=UZZ(this.fZZZZ.pZZ);v._rZ(NZZZ,{marginTop:"1em"});this.gZZZZ=new tZZ(NZZZ,"Analysis time (1-5 seconds):");NZZZ=UZZ(this.fZZZZ.pZZ);this.hZZZZ=new __nZ(NZZZ,1,5,"1","5");NZZZ=UZZ(this.fZZZZ.pZZ);this.iZZZZ=new Button(NZZZ,"Analyse");this.iZZZZ.kZZ._zZZ(this,function(){var fen=this._GZZZ.__YZZ._pZZZ();var _UZZZ=this._HZZZ._VZZZ;var _$ZZ=new __YZZ(fen);this.iZZZZ.___E();this.iZZZZ.Text.BZZ("Please wait...");____v.____T(G("/xhr/analyse.php"),function(____x){var jZZZZ=____x["score"];if(_$ZZ.__ZZZ===kZZZZ){jZZZZ=-jZZZZ;}
this.iZZZZ.___F();this.iZZZZ.Text.BZZ("Analyse");if(_UZZZ!==null){if(____x["move"]!=="(none)"){this._HZZZ._VZZZ._YZZZ=this.lZZZZ(____x["move"]);}
this._HZZZ._VZZZ.mZZZZ=parseInt(jZZZZ);this._HZZZ._VZZZ.nZZZZ=____x["score_type"];this.$ZZZZ(_UZZZ);}
else{if(____x["move"]!=="(none)"){this._zZZZ=this.lZZZZ(____x["move"]);}
this._AZZZ=parseFloat(jZZZZ);this._BZZZ=____x["score_type"];this.bZZZZ();}},{"fen":fen,"movetime":this.hZZZZ.NZZ.__wZ()*1000},this);});_KZZZ=UZZ(this.fZZZZ.pZZ);this.fZZZZ.DZZ();v._rZ(_KZZZ,{marginTop:"1em"});NZZZ=UZZ(_KZZZ);this.oZZZZ=new tZZ(NZZZ);v._rZ(this.oZZZZ.pZZ,{fontWeight:"bold",fontSize:".9em"});NZZZ=UZZ(_KZZZ);this.pZZZZ=new tZZ(NZZZ);v._rZ(NZZZ,{marginTop:".6em"});NZZZ=UZZ(_KZZZ);this.qZZZZ=new tZZ(NZZZ);this.rZZZZ();this._yZZZ=true;this.mZZ();this._DZZZ.t();}
_xZZZ.prototype.mZZ=function(_PZZ){if(this._yZZZ){this.fZZ.t();}}
_xZZZ.prototype.rZZZZ=function(){}
_xZZZ.prototype.$ZZZZ=function(_UZZZ){var qZZ="(none)";if(_UZZZ._YZZZ!==null){qZZ=_UZZZ._YZZZ;}
this.oZZZZ.Text.BZZ("Analysis at move "+_UZZZ.sZZZZ()+":");this.pZZZZ.Text.BZZ("Best move: "+qZZ);switch(_UZZZ.nZZZZ){case"cp":{this.qZZZZ.Text.BZZ("Evaluation: "+this.tZZZZ(_UZZZ.mZZZZ));break;}
case"mate":{this.qZZZZ.Text.BZZ("Evaluation: mate in "+_UZZZ.mZZZZ);break;}}}
_xZZZ.prototype.bZZZZ=function(){var qZZ="(none)";if(this._zZZZ!==null){qZZ=this._zZZZ;}
this.oZZZZ.Text.BZZ("Analysis at starting position:");this.pZZZZ.Text.BZZ("Best move: "+qZZ);switch(this._BZZZ){case"cp":{this.qZZZZ.Text.BZZ("Evaluation: "+this.tZZZZ(this._AZZZ));break;}
case"mate":{this.qZZZZ.Text.BZZ("Evaluation: mate in "+this._AZZZ);break;}}}
_xZZZ.prototype.uZZZZ=function(){this.pZZZZ.Text.BZZ("");this.qZZZZ.Text.BZZ("");}
_xZZZ.prototype.lZZZZ=function(K){var promote_to=vZZZZ;var fs=_$ZZZ.wZZZZ(K.substr(0,2));var ts=_$ZZZ.wZZZZ(K.substr(2,2));var _sZZZ=K.substr(4,1);if(_sZZZ){promote_to=_$ZZZ.type(_WZZZ.xZZZZ(_sZZZ));}
var _UZZZ=this._GZZZ._vZZZ(fs,ts,promote_to,true);return _UZZZ.yZZZZ();}
_xZZZ.prototype.tZZZZ=function(K){var jZZZZ=parseFloat(K)/100;return(jZZZZ>0?"+"+jZZZZ:jZZZZ).toString();}
_xZZZ.prototype.__jZZ=function(){this._DZZ();this._FZZZ.t();}