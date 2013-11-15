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

        if(document.movemember.puid.length)
		{
        myOption = -1;
        for (i=document.movemember.puid.length-1; i > -1; i--) {
                if (document.movemember.puid[i].checked) {
                        myOption = i; i = -1;
                }
        }

        if (myOption == -1) {
                alert("Du skal vælge et medlem");
                return false;
        }
		} else {
        	document.movemember.puid.checked = true;
		}


	if (document.movemember.elements['newdivision'].selectedIndex == 0) {
		alert ('Du skal angive den nye afdeling');
		document.movemember.elements['newdivision'].focus();
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


function checksearchvalid()
{
	if (document.layers||document.getElementById||document.all)

	if (document.searchmember.elements['divisionexists'].selectedIndex == 0) {
		alert ('Du skal angive den nuv&aelig;rende afdeling');
		document.searchmember.elements['divisionexists'].focus();
		return false;
	}

	if(document.searchmember.srch.value.length < 2)
	{
		alert("Du skal angive medlemsnummer, navn eller email");
		document.searchmember.srch.focus();
		return false;
	}

	return true;
} // checksearchvalid


function checksearchdata(){
	if (document.layers||document.getElementById||document.all)
	return checksearchvalid()
	else
	return true
} // checksearchdata


String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}
String.prototype.ltrim = function() {
	return this.replace(/^\s+/,"");
}
String.prototype.rtrim = function() {
	return this.replace(/\s+$/,"");
}
