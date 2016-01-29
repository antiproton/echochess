function _______q(parent){hZZ.iZZ(this,parent);this.________={};this._______r=null;this._______s=2000;this.nZZ();this._______t=new lZZ(this,function(){return this._______s;},function(value){this._______s=value;this._______u();});this._______u();}
_______q.prototype.nZZ=function(){this._______v=UZZ(this.pZZ);this._______w=UZZ(this._______v);this._______x=UZZ(this._______v);v._rZ(this._______v,{height:300,borderWidth:1,borderStyle:"solid",borderColor:"#9f9f9f"});v._rZ(this._______w,{backgroundColor:"#f0f0f0"});this._______y=UZZ(this._______x);v._rZ(this._______x,{width:"100%",height:"100%",overflowY:"scroll"});this.mZZ();}
_______q.prototype.mZZ=function(){}
_______q.prototype._______z=function(){if(this._______r!==null){clearInterval(this._______r);}}
_______q.prototype._______u=function(){var self=this;this._______z();this._______A();this._______r=setInterval(function(){self._______A();},this._______s);}
_______q.prototype._______B=function(___p){var _Q=new _______C(___p);this._______y.appendChild(_Q.pZZ);_Q.nZZ();}
_______q.prototype._______D=function(){v._BZ(this._______y);}
_______q.prototype._______A=function(){____v.____T(G("/xhr/table_list.php"),function(____x){if(____x!==null){var ___n;this._______D();for(var O=0;O<____x.length;O++){___p=____x[O];this._______B(___p);}}},{________:this.________},this);}