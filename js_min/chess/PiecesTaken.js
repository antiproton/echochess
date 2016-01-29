function ___HZZ(parent){this._tZZZZ=[];}
___HZZ.prototype._P=function(piece){var uZZZZZ=-1;var vZZZZZ;for(var O=0;O<this._tZZZZ.length;O++){vZZZZZ=this._tZZZZ[O];if(vZZZZZ<piece&&vZZZZZ>uZZZZZ){uZZZZZ=O;}}
this._tZZZZ.splice(uZZZZZ+1,0,piece);}
___HZZ.prototype._S=function(piece){for(var O=0;O<this._tZZZZ.length;O++){if(this._tZZZZ[O]===piece){this._tZZZZ.splice(O,1);break;}}}
___HZZ.prototype.wZZZZZ=function(piece){for(var O=0;O<this._tZZZZ.length;O++){if(this._tZZZZ[O]===piece){return true;}}
return false;}
___HZZ.prototype.aZ=function(){this._tZZZZ=[];}