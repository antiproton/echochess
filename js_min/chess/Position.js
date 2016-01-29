function __YZZ(fen){fen=fen||null;this.__NZZZ=new _____$();this.__WZZ=[];this.AZZZZ=[];this.__ZZZ=_____d;this.__IZZZ=null;this._____T=0;this.___PZZ=1;if(fen===null){this._oZZZ(xZZZZZ);}
else{this._oZZZ(fen);}}
__YZZ.prototype.DZZZZ=function(wZZZZ,FZZZZ){this.__WZZ[wZZZZ]=FZZZZ;if(_$ZZZ.type(FZZZZ)===GZZZZ){this.AZZZZ[_$ZZZ.colour(FZZZZ)]=wZZZZ;}}
__YZZ.prototype._oZZZ=function(K){var fen=_WZZZ.IZZZZ(K);this.__ZZZ=_WZZZ.____SZ(fen[yZZZZZ]);this.__NZZZ._____L(fen[zZZZZZ]);this.__IZZZ=(fen[AZZZZZ]===_____M)?null:_$ZZZ.wZZZZ(fen[AZZZZZ]);this._____T=0;if(fen[BZZZZZ]){this._____T=parseInt(fen[BZZZZZ]);}
this.___PZZ=1;if(fen[CZZZZZ]){this.___PZZ=parseInt(fen[CZZZZZ]);}
var __SZZ=_WZZZ.JZZZZ(fen[KZZZZ]);for(var wZZZZ=0;wZZZZ<__SZZ.length;wZZZZ++){this.DZZZZ(wZZZZ,__SZZ[wZZZZ]);}}
__YZZ.prototype._pZZZ=function(){return _WZZZ.____IZ([_WZZZ.____FZ(this.__WZZ),_WZZZ.____PZ(this.__ZZZ),this.__NZZZ._____Q(),(this.__IZZZ===null)?_____M:_$ZZZ.__qZZZ(this.__IZZZ),this._____T.toString(),this.___PZZ.toString()]);}