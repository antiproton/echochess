function ________G(){this.___bZZZZ=null;this.________H=!_.x();this.________I=false;this.________E=_.F();this.________J();this.________K();this.________L();this.________M();this._______u();_.a.___SZ._pZ.BZZ(2);}
________G.prototype.________K=function(){var self=this;this.________N=_hZ("#attach");if(this.________H){_.c.i._zZZ(this,function(){this.________H=false;v._rZ(this.________N,{display:"none"});});v.w(this.________N,"click",function(){self.________I=true;self.________E._lZZ._rZZ.HZZZ(self.________E._lZZ);self.________E._lZZ._rZZ.EZZZ(self.________E._lZZ);window.close();});}
else{v._rZ(this.________N,{display:"none"});}}
________G.prototype.____WZZ=function(){if(this.___bZZZZ!==null){this.___bZZZZ.__jZZ();}
this.___bZZZZ=new __sZZZZZ(_hZ("#table"));this.___bZZZZ.__JZZZZZ._zZZ(this,function(){this.________L();});this.___bZZZZ.___m._zZZ(this,function(){this.________L();});this.___bZZZZ._DZZZ._zZZ(this,function(){this.___bZZZZ._AZZZZZZ.sZZZZZZZ.__PZZZZZ.__PZZZZZ._fZZZZZ.___m._zZZ(this,function(data,sender){this.________L();});});this.___bZZZZ.__LZZZZZ._zZZ(this,function(data,sender){if(sender._gZZZZZZ.__wZ()){_.c.y(G("/livetable_detached?id="+data.sZZ));}
else{_.j.____s(function(){this.____WZZ();this.___bZZZZ._JZZZZZZ(data.sZZ);this.___bZZZZ.__DZZZZZ=data.___aZZZ;},this);}});}
________G.prototype.________L=function(){document.title=this.___bZZZZ.________q();}
________G.prototype.________J=function(){this.____WZZ();this.___bZZZZ._JZZZZZZ(_.Request["page"]["id"]);}
________G.prototype.________M=function(){v.w(window,"beforeunload",function(){if(!this.________I){if(this.________H){var CZZZ=this.________E._lZZ;CZZZ.___bZZZZ.________w();}}},this);}
________G.prototype._______u=function(){_.j.____l.BZZ(G("/xhr/updates.php"));_.j.____q();}
var ________O;_.g._zZZ(this,function(){________O=new ________G();});