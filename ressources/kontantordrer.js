var DHTML = (document.getElementById || document.all || document.layers);

function getObj(name)
{
  if (document.getElementById)
  {
  	this.obj = document.getElementById(name);
	this.style = document.getElementById(name).style;
  }
  else if (document.all)
  {
	this.obj = document.all[name];
	this.style = document.all[name].style;
  }
  else if (document.layers)
  {
   	this.obj = document.layers[name];
   	this.style = document.layers[name];
  }
}


function checkvalid()
{
	if (document.layers||document.getElementById||document.all)


	myOption = -1;
	if (document.memberform.puid.length == undefined)
	{
		if (document.memberform.puid.checked) {
			myOption = i;
			}
	} else{
		for (i=document.memberform.puid.length-1; i > -1; i--) {
			if (document.memberform.puid[i].checked) {
				myOption = i; i = -1;
			}
		}
	}
	if (myOption == -1) {
		alert("Du skal markere et medlem");
		return false;
	}

quant = 0;
for (var i = 0; i<document.memberform.length; i++)
{
	if (document.memberform.elements[i].name == 'quant[]')
	{
			if (document.memberform.elements[i].selectedIndex > 0)
			{
				quant = 1;
			}
	}
}

	if (quant == 0) {
		alert("Du skal markere antal");
		return false;
	}

	return true;
} // checkvalid


function checkdata(){
	if (document.layers||document.getElementById||document.all)
	return checkvalid()
	else
	return true
} // checkdata



String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}
String.prototype.ltrim = function() {
	return this.replace(/^\s+/,"");
}
String.prototype.rtrim = function() {
	return this.replace(/\s+$/,"");
}
