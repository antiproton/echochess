function ___dZZZ(parent){hZZ.iZZ(this,parent);this.width=300;this.___eZZZ=1;this.___fZZZ=new lZZ(this,function(){return this.___eZZZ;},function(value){this.___eZZZ=value;this.mZZ();});this.nZZ();}
___dZZZ.prototype.nZZ=function(){v._rZ(this.pZZ,{position:"absolute",display:"none",width:this.width,borderStyle:"solid",borderWidth:1,borderColor:"#bfbfbf",borderRadius:3,boxShadow:"1px 2px 1px 0px rgba(0, 0, 0, .4)",backgroundColor:"#ffffff"});this.____IZZ=UZZ(this.pZZ);v._rZ(this.____IZZ,{textAlign:"center",fontSize:11,padding:6});this.___gZZZ=UZZ(this.____IZZ);this.___hZZZ=UZZ(this.____IZZ);this.___iZZZ=UZZ(this.____IZZ);v._rZ(this.___gZZZ,{fontSize:13,fontWeight:"bold"});v._rZ(this.___hZZZ,{marginTop:10,marginBottom:10});var ___jZZZ="";___jZZZ+="Your opponent has left the game."
this.___hZZZ.innerHTML=___jZZZ;this.___kZZZ=new Button(this.___iZZZ,"Force resignation");this.___lZZZ=new Button(this.___iZZZ,"Wait");this.___lZZZ.kZZ._zZZ(this,function(){this.DZZ();});this.mZZ();}
___dZZZ.prototype.mZZ=function(){v._rZ(this.pZZ,{zIndex:this.___eZZZ});}
___dZZZ.prototype.___mZZZ=function(__PZ,__QZ){var height=this.pZZ.offsetHeight;if(height===0){height=100;}
v._rZ(this.pZZ,{top:__QZ-Math.round(height/2),left:__PZ-Math.round(this.width/2)});}