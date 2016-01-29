var ______w={______x:function(______y){var ______z=15;var ______A=90;var ______B=______A-______z;if(______y<______z){return 1;}
else if(______y>______A){return 0.01;}
else{return(1/______B*(______y-______z));}},______C:function(style,____aZ,____bZ,____gZ,____hZ,____iZ){if(style!==__G&&style!==__H&&style!==__I&&style!==__L){____bZ=0;}
var ______D=____bZ*______w.______E;if(____gZ){______D+=____hZ*______w.______x(____iZ);}
var ______F=____aZ+______D;var ______G;for(var format in ______w.______H){______G=______w.______H[format];if(______F<=______G){return format;}}
return OZ;}};______w.______E=40;______w.______H={};______w.______H[NZ]=60;______w.______H[MZ]=600;______w.______H[QZ]=1800;______w.______H[RZ]=86400;