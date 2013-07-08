function ____XZZ(parent,data){hZZ.iZZ(this,parent);var self=this;this.____YZZ=data;this.nZZ();setTimeout(function(){self.DZZ();},(_____TZ-1)*u);____v.____T(G("/xhr/while_challenge_exists.php"),function(){this.DZZ();},{"id":this.____YZZ["id"]},this);}
____XZZ.prototype.nZZ=function(){var self=this;v._WZ(this.pZZ,"alert");this.TZZ=UZZ(this.pZZ);v._WZ(this.TZZ,"inner");var ___p=this.____YZZ;this.TZZ.innerHTML=""
+___p["owner"]+" has challenged you to a game ("+_bZ[jZ][___p["variant"]]._fZ
+" "+_____Z.____$Z(__I,___p["timing_initial"],___p["timing_increment"])
+" "+(___p["rated"]?"rated":"unrated")
+"; you play "+(___p["choose_colour"]?_$ZZZ.______X(_$ZZZ._RZZZ(___p["challenge_colour"])):"random"+")")
+"<br><br>";var ____ZZZ=_hZ("*a");this.TZZ.appendChild(____ZZZ);this.TZZ.appendChild(_hZ("% | "));var ___$ZZZ=_hZ("*a");this.TZZ.appendChild(___$ZZZ);____ZZZ.href="javascript:void(0)";____ZZZ.innerHTML="Accept";v.w(____ZZZ,"click",function(){___aZZZ.___bZZZ(___p["id"],function(____x){this.DZZ();if(____x!==false){_.a.___WZ(___p["id"]);}
else{this.EZZ();this.pZZ.innerHTML=""
+"There was an error while accepting the challenge."
+"  Most likely the other player cancelled it before"
+" the request completed.";setTimeout(function(){self.DZZ();},3000);}},this);},this);___$ZZZ.href="javascript:void(0)";___$ZZZ.innerHTML="Decline";v.w(___$ZZZ,"click",function(){___aZZZ.___cZZZ(___p["id"]);this.DZZ();},this);}