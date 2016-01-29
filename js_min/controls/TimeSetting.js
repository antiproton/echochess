function __yZZZZ(parent){hZZ.iZZ(this,parent);this._______M=new h(this);this.style=__M;this.____aZ=0;this.____bZ=0;this.____gZ=false;this.____iZ=40;this.____hZ=0;this._______N=true;this._______O=false;this._______P=[__M,__H,__I,__G,__L,];this._______Q=[__M,__H,__I,__L,__G];this._rZ=new lZZ(this,function(){return this.style;},function(value){this.style=value;this.zZZZZZZ();});this.______u=new lZZ(this,function(){return this.____aZ;},function(value){this.____aZ=value;this.zZZZZZZ();});this.______v=new lZZ(this,function(){return this.____bZ;},function(value){this.____bZ=value;this.zZZZZZZ();});this._______R=new lZZ(this,function(){return this.____gZ;},function(value){this.____gZ=value;this.zZZZZZZ();});this._______S=new lZZ(this,function(){return this.____hZ;},function(value){this.____hZ=value;this.zZZZZZZ();});this._______T=new lZZ(this,function(){return this.____iZ;},function(value){this.____iZ=value;this.zZZZZZZ();});this.___D=new lZZ(this,function(){return this._______N;},function(value){this._______N=value;this.mZZ();});this.___B=new lZZ(this,function(){return this._______N;},function(value){this._______N=value;this.mZZ();});this.nZZ();}
__yZZZZ.prototype.nZZ=function(){var self=this;v._WZ(this.pZZ,"timesetting");this._______U=new __xZ(__FZ,{RZZ:220});v._rZ(this._______U.__KZ,{padding:5});this._______U.DZZ();this._______V=new _______V(this._______U.__KZ);this._______V.wZZZZZZ._zZZ(this,function(data,sender){this._______W();});this._______V._bZZZ._zZZ(this,function(){this._______X();});this.TZZ=UZZ(this.pZZ);v.w(this.pZZ,"click",function(){_.a.zZZ._P(self);self.click();});_.a.___JZ._zZZ(this,function(data){if(!_.a.zZZ.fZ(this._______U)&&!_.a.zZZ.fZ(this)){this._______X();}});_.a.___NZ._zZZ(this,function(){this._______X();});this.mZZ();}
__yZZZZ.prototype.mZZ=function(){if(this._______N&&!this._______O){v._WZ(this.pZZ,"configurable");}
else{v._YZ(this.pZZ,"configurable");}
if(this._______O){v._WZ(this.pZZ,"editing");}
else{v._YZ(this.pZZ,"editing");}
this.zZZZZZZ();}
__yZZZZ.prototype.zZZZZZZ=function(){this.TZZ.innerHTML=_____Z.____fZ(this.style,this.____aZ,this.____bZ,this.____gZ,this.____hZ,this.____iZ);}
__yZZZZ.prototype._______Y=function(){var _kZ=v._qZ(this.pZZ);var _______Z=[this.pZZ.offsetWidth,this.pZZ.offsetHeight];this._______V._rZ.BZZ(this.style);this._______V.______u.BZZ(this.____aZ);this._______V.______v.BZZ(this.____bZ);this._______V._______R.BZZ(this.____gZ);this._______V._______S.BZZ(this.____hZ);this._______V._______T.BZZ(this.____iZ);this._______U.EZZ();this._______U.__OZ(_kZ[_oZ]+Math.round(_______Z[_oZ]/2),_kZ[_pZ]+_______Z[_pZ]+5);if(this.style===__N){this._______V.______$Z.___G();}
else{this._______V.IZZZZZZ.___G();this._______V.IZZZZZZ.Select();}
this._______O=true;this.mZZ();}
__yZZZZ.prototype._______W=function(){if(this._______O){this._______U.DZZ();this.style=this._______V._rZ.__wZ();this.____aZ=this._______V.______u.__wZ();if(___tZ(this.style,this._______Q)){this.____bZ=this._______V.______v.__wZ();}
else{this.____bZ=0;}
if(___tZ(this.style,this._______P)){this.____gZ=this._______V._______R.__wZ();this.____hZ=this._______V._______S.__wZ();this.____iZ=this._______V._______T.__wZ();}
else{this.____gZ=false;}
this._______O=false;this._______M.t();this.mZZ();}}
__yZZZZ.prototype._______X=function(){this._______U.DZZ();this._______O=false;this.mZZ();}
__yZZZZ.prototype.click=function(){if(this._______O){this._______X();}
else{if(this._______N){this._______Y();}}}