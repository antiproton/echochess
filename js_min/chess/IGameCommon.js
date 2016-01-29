function __TZZ(__SZZ,history,____TZ,____UZ){_pZZ.iZZ(this);this._JZZZ=new h(this);this.____ZZ=__TZZ.___$ZZ;this.___aZZ=_____d;this.___bZZ=null;this.___cZZ=null;this.___dZZ=null;this.___eZZ=WZ;this._WZZZ=null;this.___fZZ=null;this.___gZZ=null;this._cZ=YZ;this.___hZZ=_aZ;this.___iZZ=__D;this.___jZZ=null;this.___kZZ=QZ;this.___lZZ=null;this.___mZZ=null;this.___nZZ=null;this.___oZZ=null;this.___pZZ=null;this.___qZZ=null;this.___rZZ=1;this.___sZZ=0;this.___tZZ=600;this.___uZZ=0;this.___vZZ=__M;this.___wZZ=false;this.___xZZ=40;this.___yZZ=600;this.___zZZ=HZ;this.h=null;this.___AZZ=1;this.___BZZ=false;this.___CZZ=false;this.___DZZ=null;this.___EZZ=false;this.___FZZ=false;this.___GZZ=true;this.__YZZ=new __YZZ();this._XZZZ=new __YZZ();this.__WZZ=__SZZ||new __WZZ();this._HZZZ=history||new _HZZZ();this._____T=____UZ||new _____T();if(___x(____TZ)){this.___HZZ=____TZ;}
else if(____TZ){this.___HZZ=[____TZ,____TZ];}
else{this.___HZZ=[null,null];}
for(var O=0;O<this.___HZZ.length;O++){if(this.___HZZ[O]===null){this.___HZZ[O]=new ___HZZ();}}
this._HZZZ._TZZZ._zZZ(this,function(data){if(!this._HZZZ.___IZZ){if(data._vZZZ!==null){this.__YZZ._oZZZ(data._vZZZ._WZZZ);this.__WZZ._oZZZ(data._vZZZ._WZZZ);}
else{this.__YZZ._oZZZ(this._XZZZ._pZZZ());this.__WZZ.LZZZZ(this._XZZZ.__WZZ);}}});this.cZZZ();}
__TZZ.___$ZZ=0;__TZZ.___JZZ=1;__TZZ.___KZZ=2;__TZZ.prototype.cZZZ=function(){this.___LZZ=new lZZ(this,function(){return this.___aZZ;},function(value){this.___aZZ=value;});this.___MZZ=new lZZ(this,function(){return this.____ZZ;},function(value){this.____ZZ=value;});}
__TZZ.prototype.eZZZZ=function(fen){this._XZZZ._oZZZ(fen);this._oZZZ(fen);}
__TZZ.prototype._oZZZ=function(fen){this._HZZZ.aZ();this.__YZZ._oZZZ(fen);this.__WZZ._oZZZ(fen);this._HZZZ.___NZZ.BZZ(this.__YZZ.__ZZZ);this._HZZZ.___OZZ.BZZ(this.__YZZ.___PZZ);}
__TZZ.prototype.___QZZ=function(colour){if(this.time[colour]<1){var _RZZZ=_$ZZZ._RZZZ(colour);var result=this.___RZZ(_RZZZ)?_RZZZ:___SZZ;this._wZZZ(result,__x);}}
__TZZ.prototype.___TZZ=function(){}
__TZZ.prototype.___UZZ=function(){var fen=this.__YZZ._pZZZ();var ___VZZ=3;var _v=0;if(fen===this._XZZZ._pZZZ()){___VZZ--;}
this._HZZZ.___WZZ.___XZZ._U(function(_UZZZ){if(_UZZZ._WZZZ===fen){_v++;}});this.threefold_claimable=(_v>=___VZZ);}
__TZZ.prototype.___YZZ=function(colour){var ___ZZZ=0;var piece,__$ZZZ;for(var wZZZZ=0;wZZZZ<this.__YZZ.__WZZ.length;wZZZZ++){piece=this.__YZZ.__WZZ[wZZZZ];if(piece!==zZZZZ&&_$ZZZ.colour(piece)===colour){__$ZZZ=_$ZZZ.__aZZZ(_$ZZZ.type(piece),wZZZZ,colour);for(var _v=0;_v<__$ZZZ.length;_v++){if(this._vZZZ(wZZZZ,__$ZZZ[_v],vZZZZ,true).__bZZZ){___ZZZ++;}}}}
return ___ZZZ;}
__TZZ.prototype.__cZZZ=function(wZZZZ){var ___ZZZ=[];var __$ZZZ;var piece=this.__YZZ.__WZZ[wZZZZ];if(piece!==zZZZZ){__$ZZZ=_$ZZZ.__aZZZ(_$ZZZ.type(piece),wZZZZ,_$ZZZ.colour(piece));for(var _v=0;_v<__$ZZZ.length;_v++){if(this._vZZZ(wZZZZ,__$ZZZ[_v],vZZZZ,true).__bZZZ){___ZZZ.push(__$ZZZ[_v]);}}}
return ___ZZZ;}
__TZZ.prototype._vZZZ=function(fs,ts,promote_to,__dZZZ){promote_to=promote_to||vZZZZ;__dZZZ=__dZZZ||false;var colour=this.__YZZ.__ZZZ;var piece=new _aZZZ(this.__YZZ.__WZZ[fs]);var __eZZZ=new _aZZZ(this.__YZZ.__WZZ[ts]);var _UZZZ=new _vZZZ();_UZZZ.__fZZZ=fs;_UZZZ.__gZZZ=ts;if(_$ZZZ.__hZZZ(fs)&&_$ZZZ.__hZZZ(ts)&&piece._cZ!==zZZZZ&&piece.___VZ===colour){var _$ZZ=new __YZZ(this.__YZZ._pZZZ());var __iZZZ=_$ZZZ.__jZZZ(fs);var __kZZZ=_$ZZZ.__jZZZ(ts);var __lZZZ=_$ZZZ.__mZZZ(fs,colour);var __nZZZ=_$ZZZ.__mZZZ(ts,colour);var _RZZZ=_$ZZZ._RZZZ(colour);var __oZZZ=(!_$ZZZ.__pZZZ(this.__YZZ.__WZZ,fs,ts)&&(__eZZZ._cZ===zZZZZ||__eZZZ.___VZ!==colour));_UZZZ.tZZ._aZZZ=_WZZZ.____jZ[_$ZZZ.piece(piece._cZ,_____d)];_UZZZ.tZZ._nZZZ=_$ZZZ.__qZZZ(ts);if(piece._cZ!==_tZZZ&&piece._cZ!==GZZZZ){_UZZZ.tZZ.__rZZZ=_$ZZZ.__sZZZ(this.__YZZ.__WZZ,piece._cZ,colour,fs,ts);}
if(__eZZZ.___VZ===_RZZZ&&__eZZZ._cZ!==zZZZZ){_UZZZ.tZZ.XZZZZ=__tZZZ;_UZZZ.__uZZZ=this.__YZZ.__WZZ[ts];}
if(_$ZZZ.__vZZZ(piece._cZ,__iZZZ,__kZZZ)&&__oZZZ){_UZZZ.OZZZZ=true;_UZZZ.__wZZZ.push({_iZZZ:fs,__xZZZ:zZZZZ});_UZZZ.__wZZZ.push({_iZZZ:ts,__xZZZ:this.__YZZ.__WZZ[fs]});}
else if(piece._cZ===_tZZZ&&__oZZZ){var __yZZZ=_$ZZZ.__zZZZ(__lZZZ,__nZZZ);var __AZZZ=false;var _sZZZ=false;if(__yZZZ){_UZZZ.tZZ.__rZZZ=_$ZZZ.__BZZZ(fs);_UZZZ.tZZ.XZZZZ=__tZZZ;}
_UZZZ.tZZ._aZZZ="";if(_$ZZZ.__CZZZ(__nZZZ)){_sZZZ=true;if(promote_to>=__DZZZ&&promote_to<=vZZZZ){_UZZZ.__wZZZ.push({_iZZZ:ts,__xZZZ:_$ZZZ.piece(promote_to,colour)});_UZZZ.tZZ.__EZZZ=__FZZZ+_WZZZ.____jZ[_$ZZZ.piece(promote_to,_____d)];_UZZZ.__GZZZ=promote_to;__AZZZ=true;}}
if(__AZZZ||!_sZZZ){if(__eZZZ._cZ===zZZZZ){if(_$ZZZ.__HZZZ(__lZZZ,__nZZZ)){_$ZZ.__IZZZ=_$ZZZ.__mZZZ(__nZZZ-8,colour);_UZZZ.OZZZZ=true;}
else if(_$ZZZ.__JZZZ(__lZZZ,__nZZZ)){_UZZZ.OZZZZ=true;}
else if(__yZZZ&&ts===this.__YZZ.__IZZZ){_UZZZ.__wZZZ.push({_iZZZ:_$ZZZ.__KZZZ(fs,ts),__xZZZ:zZZZZ});_UZZZ.tZZ.XZZZZ=__tZZZ;_UZZZ.__uZZZ=_$ZZZ.piece(_tZZZ,_RZZZ);_UZZZ.OZZZZ=true;}}
else if(__yZZZ){_UZZZ.OZZZZ=true;}}
if(_UZZZ.OZZZZ){_UZZZ.__wZZZ.push({_iZZZ:fs,__xZZZ:zZZZZ});if(!_sZZZ){_UZZZ.__wZZZ.push({_iZZZ:ts,__xZZZ:this.__YZZ.__WZZ[fs]});}}}
else if((piece._cZ===GZZZZ||piece._cZ===__LZZZ)&&!this.__MZZZ(colour)){_UZZZ.__NZZZ=true;switch(this.___hZZ){case _$Z:{var __OZZZ=[0,7][colour];if(_$ZZZ.__QZ(fs)===__OZZZ&&_$ZZZ.__QZ(ts)===__OZZZ){__PZZZ=this.__YZZ.AZZZZ[colour];__QZZZ=null;var RZZZZ;if(piece._cZ===__LZZZ){RZZZZ=(_$ZZZ.__PZ(fs)<_$ZZZ.__PZ(ts))?_____g:_____f;}
else if(piece._cZ===GZZZZ){RZZZZ=(_$ZZZ.__PZ(fs)>_$ZZZ.__PZ(ts))?_____g:_____f;}
var __RZZZ=[5,3][RZZZZ];var __SZZZ=[6,2][RZZZZ];var __TZZZ=[7,0][RZZZZ];if(piece._cZ===__LZZZ){__QZZZ=fs;}
else{var __UZZZ=_$ZZZ.__VZZZ(_$ZZZ.__WZZZ([__TZZZ,__OZZZ]),__PZZZ,true);var wZZZZ;for(var O=0;O<__UZZZ.length;O++){wZZZZ=__UZZZ[O];if(this.__YZZ.__WZZ[wZZZZ]===_$ZZZ.piece(__LZZZ,colour)){__QZZZ=wZZZZ;break;}}}
if(__QZZZ!==null){var __XZZZ=_$ZZZ.__WZZZ([__SZZZ,__OZZZ]);var __YZZZ=_$ZZZ.__WZZZ([__RZZZ,__OZZZ]);var __ZZZZ=__PZZZ;var _$ZZZZ=__QZZZ;var _aZZZZ=_$ZZZ.__PZ(__PZZZ);var _bZZZZ=_$ZZZ.__PZ(__QZZZ);if(Math.abs(__TZZZ-__RZZZ)>Math.abs(__TZZZ-_aZZZZ)){__ZZZZ=__YZZZ;}
if(Math.abs(__TZZZ-__SZZZ)<Math.abs(__TZZZ-_bZZZZ)){_$ZZZZ=__XZZZ;}
var _cZZZZ=_$ZZZ.__VZZZ(_$ZZZZ,__ZZZZ,true);var _dZZZZ=0;var _eZZZZ=0;var _fZZZZ=0;var FZZZZ;for(var O=0;O<_cZZZZ.length;O++){wZZZZ=_cZZZZ[O];FZZZZ=this.__YZZ.__WZZ[wZZZZ];if(FZZZZ!==zZZZZ){if(FZZZZ===_$ZZZ.piece(__LZZZ,colour)){_eZZZZ++;}
else if(FZZZZ===_$ZZZ.piece(GZZZZ,colour)){_dZZZZ++;}
else{_fZZZZ++;break;}}}
if(_dZZZZ===1&&_eZZZZ===1&&_fZZZZ===0){var _gZZZZ=false;var _hZZZZ=_$ZZZ.__VZZZ(__PZZZ,__XZZZ);var _v;for(var O=0;O<_hZZZZ.length;O++){_v=_hZZZZ[O];if(_$ZZZ._iZZZZ(this.__YZZ.__WZZ,_v,_RZZZ).length>0){_gZZZZ=true;break;}}
if(!_gZZZZ){_UZZZ.tZZ._aZZZ="";_UZZZ.tZZ._nZZZ="";_UZZZ.tZZ.__rZZZ="";_UZZZ.tZZ.__EZZZ=NZZZZ.YZZZZ[RZZZZ];_UZZZ.__wZZZ.push({_iZZZ:__PZZZ,__xZZZ:zZZZZ});_UZZZ.__wZZZ.push({_iZZZ:__QZZZ,__xZZZ:zZZZZ});_UZZZ.__wZZZ.push({_iZZZ:__XZZZ,__xZZZ:_$ZZZ.piece(GZZZZ,colour)});_UZZZ.__wZZZ.push({_iZZZ:__YZZZ,__xZZZ:_$ZZZ.piece(__LZZZ,colour)});_UZZZ.OZZZZ=true;}}}}
break;}
default:{if(piece._cZ===GZZZZ&&__oZZZ){var ____LZ=new NZZZZ(fs,ts);if(____LZ.OZZZZ&&this.__YZZ.__NZZZ.__wZ(colour,____LZ.UZZZZ)){var _gZZZZ=false;var _hZZZZ=_$ZZZ.__VZZZ(fs,ts);var _v;for(var O=0;O<_hZZZZ.length;O++){_v=_hZZZZ[O];if(_$ZZZ._iZZZZ(this.__YZZ.__WZZ,_v,_RZZZ).length>0){_gZZZZ=true;break;}}
if(!_$ZZZ.__pZZZ(this.__YZZ.__WZZ,fs,____LZ.VZZZZ)&&!_gZZZZ){_UZZZ.tZZ._aZZZ="";_UZZZ.tZZ._nZZZ="";_UZZZ.tZZ.__EZZZ=____LZ.XZZZZ;_UZZZ.__wZZZ.push({_iZZZ:fs,__xZZZ:zZZZZ});_UZZZ.__wZZZ.push({_iZZZ:ts,__xZZZ:_$ZZZ.piece(GZZZZ,colour)});_UZZZ.__wZZZ.push({_iZZZ:____LZ.VZZZZ,__xZZZ:zZZZZ});_UZZZ.__wZZZ.push({_iZZZ:____LZ.WZZZZ,__xZZZ:_$ZZZ.piece(__LZZZ,colour)});_UZZZ.OZZZZ=true;}}}
break;}}}
if(_UZZZ.OZZZZ){var _jZZZZ;for(var O=0;O<_UZZZ.__wZZZ.length;O++){_jZZZZ=_UZZZ.__wZZZ[O];_$ZZ.DZZZZ(_jZZZZ._iZZZ,_jZZZZ.__xZZZ);}
var _kZZZZ=_$ZZZ._iZZZZ(_$ZZ.__WZZ,_$ZZ.AZZZZ[colour],_RZZZ);if(_kZZZZ.length===0){_UZZZ.__bZZZ=true;}}
if(_UZZZ.__bZZZ){var _lZZZZ=this.__YZZ;this.__YZZ=_$ZZ;if(colour===kZZZZ){this.__YZZ.___PZZ++;}
this.__YZZ.__ZZZ=_RZZZ;if(_UZZZ.__uZZZ!==null||piece._cZ===_tZZZ){this.__YZZ._____T=0;}
else{this.__YZZ._____T++;}
if(piece._cZ!==_tZZZ||!_$ZZZ.__HZZZ(__lZZZ,__nZZZ)){this.__YZZ.__IZZZ=null;}
if(piece._cZ===GZZZZ||_UZZZ.__NZZZ){for(_____i=0;_____i<8;_____i++){this.__YZZ.__NZZZ.BZZ(colour,_____i,false,_____$._____k);}}
else if(piece._cZ===__LZZZ){this.__YZZ.__NZZZ.BZZ(colour,_$ZZZ.__PZ(fs),false,_____$._____k);}
if(this.__MZZZ(_RZZZ)){_UZZZ.tZZ._mZZZZ=_nZZZZ;}
if(this._oZZZZ(_RZZZ)){_UZZZ.tZZ._mZZZZ=_pZZZZ;}
if(__dZZZ){this.__YZZ=_lZZZZ;}
else{this.___DZZ=null;this.___EZZ=false;if(this._oZZZZ(_RZZZ)){this._wZZZ(___lZZ._qZZZZ[colour],__q);}
else{if(!this._rZZZZ(_____d)&&!this._rZZZZ(kZZZZ)){this._wZZZ(__A,__t);}
if(this.___YZZ(_RZZZ)===0&&this._cZ!==XZ){this._wZZZ(__A,__u);}
if(this.__YZZ._____T>49){this._sZZZZ=true;}
this.___UZZ();}
_UZZZ._WZZZ=this.__YZZ._pZZZ();if(this._HZZZ._vZZZ(_UZZZ)){_UZZZ.R=true;this._JZZZ.t();}
else{this.__YZZ=_lZZZZ;}}}}
return _UZZZ;}
__TZZ.prototype.__MZZZ=function(colour,_$ZZ){return(_$ZZZ._iZZZZ(this.__YZZ.__WZZ,this.__YZZ.AZZZZ[colour],_$ZZZ._RZZZ(colour)).length>0);}
__TZZ.prototype._oZZZZ=function(colour,_$ZZ){return(this.__MZZZ(colour)&&this.___YZZ(colour)===0);}
__TZZ.prototype._rZZZZ=function(colour){var _tZZZZ=[];_tZZZZ[__DZZZ]=0;_tZZZZ[_uZZZZ]=0;var _vZZZZ=[0,0];var _wZZZZ=[0,0];var piece,_xZZZZ,_yZZZZ;for(var wZZZZ=0;wZZZZ<this.__YZZ.__WZZ.length;wZZZZ++){piece=this.__YZZ.__WZZ[wZZZZ];_xZZZZ=_$ZZZ.colour(piece);_yZZZZ=_$ZZZ.type(piece);if(_yZZZZ!==zZZZZ&&_yZZZZ!==GZZZZ){if(_xZZZZ===colour&&(_yZZZZ===_tZZZ||_yZZZZ===__LZZZ||_yZZZZ===vZZZZ)){return true;}
if(_yZZZZ===_uZZZZ){_vZZZZ[_xZZZZ]++;}
if(_yZZZZ===__DZZZ){_wZZZZ[_xZZZZ]++;}
_tZZZZ[_yZZZZ]++;}}
if((_vZZZZ[_____d]>0&&_vZZZZ[kZZZZ]>0)||(_tZZZZ[_uZZZZ]>0&&_tZZZZ[__DZZZ]>0)||(_tZZZZ[__DZZZ]>2&&_wZZZZ[colour]>0)){return true;}
return false;}
__TZZ.prototype._zZZZZ=function(){this._HZZZ._zZZZZ();}
__TZZ.prototype._wZZZ=function(result,result_details){this.___eZZ=UZ;this.___lZZ=result;this.___mZZ=result_details;this.___DZZ=null;this.___FZZ=false;this.___EZZ=false;}