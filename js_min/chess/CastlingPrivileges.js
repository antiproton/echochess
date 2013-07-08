function _____$(){this._____a=[];this._____b=[];var _____c=[_____d,kZZZZ];var _____e=[_____f,_____g];var RZZZZ,colour;for(var _____h=0;_____h<_____c.length;_____h++){colour=_____c[_____h];for(var ___pZ=0;___pZ<_____e.length;___pZ++){RZZZZ=_____e[___pZ];if(!(colour in this._____a)){this._____a[colour]=[];}
this._____a[colour][RZZZZ]=false;}
for(var _____i=0;_____i<8;_____i++){if(!(colour in this._____b)){this._____b[colour]=[];}
this._____b[colour][_____i]=false;}}}
_____$._____j=0;_____$._____k=1;_____$._____l=[[_____m,_____n],[_____o,_____p]];_____$._____q=[[_____r,_____s,_____t,_____u,_____v,_____w,_____x,_____y],[_____z,_____A,_____B,_____C,_____D,_____E,_____F,_____G]];_____$._____H=[];_____$._____H[0]=_____g;_____$._____H[7]=_____f;_____$._____I=[7,0];_____$.prototype.Reset=function(){var _____c=[_____d,kZZZZ];var _____e=[_____f,_____g];var RZZZZ,colour;for(var _____h=0;_____h<_____c.length;_____h++){colour=_____c[_____h];for(var ___pZ=0;___pZ<_____e.length;___pZ++){RZZZZ=_____e[___pZ];this._____a[colour][RZZZZ]=false;}
for(var _____i=0;_____i<8;_____i++){this._____b[colour][_____i]=false;}}}
_____$.prototype.BZZ=function(colour,_Y,_____J,_____K){_____K=_____K||_____$._____j;switch(_____K){case _____$._____j:{this._____a[colour][_Y]=_____J;this._____b[colour][_____$._____I[_Y]]=_____J;break;}
case _____$._____k:{this._____b[colour][_Y]=_____J;if(_Y in _____$._____H){this._____a[colour][_____$._____H[_Y]]=_____J;}
break;}}}
_____$.prototype.__wZ=function(colour,_Y,_____K){_____K=_____K||_____$._____j;switch(_____K){case _____$._____j:{return this._____a[colour][_Y];}
case _____$._____k:{return this._____b[colour][_Y];}}}
_____$.prototype._____L=function(K){this.Reset();if(K!==_____M){var _s=K.split("");var _y,_____N,_____O;var colour,_____K,_Y;for(var O=0;O<_s.length;O++){_y=_s[O];_____N=_y.toLowerCase();_____O=_y.toUpperCase();colour=(_y===_____O)?_____d:kZZZZ;_____K=(_____P.indexOf(_____N)!==-1)?_____$._____k:_____$._____j;switch(_____K){case _____$._____j:{_Y=(_____o+_____p).indexOf(_____N);break;}
case _____$._____k:{_Y=_____P.indexOf(_____N);break;}}
this.BZZ(colour,_Y,true,_____K);}}}
_____$.prototype._____Q=function(){var _____c=[_____d,kZZZZ];var _____e=[_____f,_____g];var colour,RZZZZ,_y;var _____R=[7,0];var _____S=[[],[]];for(var O=0;O<_____c.length;O++){colour=_____c[O];for(var _____i=0;_____i<8;_____i++){if(this._____b[colour][_____i]){_y=_____$._____q[colour][_____i];if(_____i in _____$._____H){_y=_____$._____l[colour][_____$._____H[_____i]];}
_____S[colour].push(_y);}}}
var K=_____S[_____d].join("")+_____S[kZZZZ].join("");if(K==""){K=_____M;}
return K;}