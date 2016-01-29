var _WZZZ={____jZ:["_","P","N","B","R","Q","K",null,null,"p","n","b","r","q","k"],____kZ:{"_":zZZZZ,"P":____lZ,"N":____mZ,"B":____nZ,"R":____oZ,"Q":____pZ,"K":____qZ,"p":____rZ,"n":____sZ,"b":____tZ,"r":____uZ,"q":____vZ,"k":____wZ},____xZ:[[_____m,_____n],[_____o,_____p]],xZZZZ:function(piece){return _WZZZ.____kZ[piece];},____yZ:function(piece){return _WZZZ.____jZ[piece];},JZZZZ:function(_$ZZ){var _s=[];var ____zZ=_$ZZ.split(____AZ);var ____BZ,____CZ,O,_xZ,_v;var _____i,wZZZZ;for(____BZ=7;____BZ>-1;____BZ--){_____i=____zZ[____BZ].split("");O=0;____CZ=0;while(O<8){wZZZZ=_____i[____CZ];if(____DZ.indexOf(wZZZZ)!==-1){_s.push(_WZZZ.xZZZZ(wZZZZ));O++;}
else if(____EZ.indexOf(wZZZZ)!==-1){_v=parseInt(wZZZZ);for(_xZ=0;_xZ<_v;_xZ++){_s.push(zZZZZ);O++;}}
else{O++;}
____CZ++;}}
return _s;},____FZ:function(_s){var _$ZZ=[];var ____zZ=[];for(var O=56;O>-1;O-=8){____zZ.push(_s.slice(O,O+8));}
var _v,____GZ,V,piece;for(var ____BZ in ____zZ){_v=0;____GZ="";for(var _xZ=0;_xZ<____zZ[____BZ].length;_xZ++){piece=____zZ[____BZ][_xZ];V=null;if(_xZ<7){V=____zZ[____BZ][_xZ+1];}
if(piece===zZZZZ){_v++;if(V!==zZZZZ){____GZ+=_v;_v=0;}}
else{____GZ+=_WZZZ.____yZ(piece);}}
_$ZZ.push(____GZ);}
return _$ZZ.join(____AZ);},IZZZZ:function(fen){return fen.split(____HZ);},____IZ:function(_s){return _s.join(____HZ);},____JZ:function(K){if(K==_____M){return ____KZ;}
var ____LZ=____KZ;var _v;for(var colour=_____d;colour<=kZZZZ;colour++){for(var RZZZZ=_____f;RZZZZ<=_____g;RZZZZ++){_v=(K.indexOf(this.____xZ[colour][RZZZZ])===-1)?0:1;____LZ=_$ZZZ.____MZ(____LZ,colour,RZZZZ,_v);}}
return ____LZ;},____NZ:function(_v){if(_v==____KZ){return _____M;}
var ____LZ="";for(var colour=_____d;colour<=kZZZZ;colour++){for(var RZZZZ=_____f;RZZZZ<=_____g;RZZZZ++){if(_$ZZZ.____OZ(_v,colour,RZZZZ)){____LZ+=_WZZZ.____xZ[colour][RZZZZ];}}}
return ____LZ;},____PZ:function(colour){return colour===kZZZZ?____QZ:____RZ;},____SZ:function(K){return K===____QZ?kZZZZ:_____d;}};