//************************************************************************************
// Copyright (C) 2006, Massimo Beatini
//
// This software is provided "as-is", without any express or implied warranty. In 
// no event will the authors be held liable for any damages arising from the use 
// of this software.
//
// Permission is granted to anyone to use this software for any purpose, including 
// commercial applications, and to alter it and redistribute it freely, subject to 
// the following restrictions:
//
// 1. The origin of this software must not be misrepresented; you must not claim 
//    that you wrote the original software. If you use this software in a product, 
//    an acknowledgment in the product documentation would be appreciated but is 
//    not required.
//
// 2. Altered source versions must be plainly marked as such, and must not be 
//    misrepresented as being the original software.
//
// 3. This notice may not be removed or altered from any source distribution.
//
//************************************************************************************

//
// global variables
//
var isMozilla;
var objDiv = null;
var originalDivHTML = "";
var DivID = "";
var over = false;



//
// dinamically add a div to 
// dim all the page
//
function buildDimmerDiv()
{

   /*
    var className="";
	if(isMozilla){
      className='dimmer_ff';
	 }
    else{
	   className='dimmer_ie';
    }
	document.write('<div id="dimmer" class="'+className+'" style="width:100%; height:100%"></div>');
    
   */
   document.write('<div id="modalPage" style="width:100%; height:100%"><div class="modalBackground" id="modalPage2"><!--[if lte IE 6.5]><iframe id="specialIframe"></iframe><![endif]--></div></div>');
}


//
//
//
var zIndex=1000; //this will used for z-index of pop-upped divs
var clientHeight="";//calculates the client Height
var clientWidth="";//calculates the client width

function displayFloatingDivNew(divId, title, width, height, left, top,mask) {
    DivID = divId;

      var pageWidth = 0, pageHeight = 0;
      if( typeof( window.innerWidth ) == 'number' ) {
        //Non-IE
        pageWidth = window.innerWidth;
        pageHeight = window.innerHeight;
      } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
        //IE 6+ in 'standards compliant mode'
        pageWidth = document.documentElement.clientWidth;
        pageHeight = document.documentElement.clientHeight;
      } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
        //IE 4 compatible
        pageWidth = document.body.clientWidth;
        pageHeight = document.body.clientHeight;
      }

      left = (pageWidth - width)/2;
      top = (pageHeight - height)/2;


    //calculates the client Height and if blanck then assign value
    if(clientHeight =="" && clientWidth ==""){
     clientHeight=document.body.clientHeight;
     clientWidth=document.body.scrollWidth;
    }
    clientHeight=document.body.scrollHeight;
    clientWidth=document.body.scrollWidth;
    if(!mask){
        //document.getElementById('dimmer').style.visibility = "visible";
        document.getElementById('modalPage').style.display = "block";
        document.getElementById('modalPage').style.height=clientHeight+'px';
        document.getElementById('modalPage').style.width=clientWidth+'px';
        document.getElementById('modalPage2').style.display = "block";
        document.getElementById('modalPage2').style.height=clientHeight+'px';
        document.getElementById('modalPage2').style.width=clientWidth+'px';
        makeMenuDisable('qm0',true);
     }
    try{
       //to fix IE6's z-index problem
       document.getElementById('specialIframe').style.width = '100%';
       document.getElementById('specialIframe').style.height = height + 'px';
       document.getElementById('specialIframe').style.left = left + 'px';    
    }
    catch(e){
    }

    if(!mask) {
      over = false;
    }
    else {
      over = true;
    }
 
    document.getElementById(divId).className = 'dimming';
    document.getElementById(divId).style.zIndex=++zIndex; //z-index incremented by one
    
    document.getElementById(divId).style.visibility = "visible";
    document.getElementById(divId).style.display = 'block';


    if(winWDP==0 || winHDP==0){
        winWDP = (isMozilla)? window.innerWidth-20 : document.documentElement.clientWidth-20;
        winHDP = (isMozilla)? window.innerHeight : document.documentElement.clientHeight;
    }
    //sets divs left and top
    document.getElementById(divId).style.top=(winHDP-document.getElementById(divId).offsetHeight)/4+'px';
    document.getElementById(divId).style.left=(winWDP-document.getElementById(divId).offsetWidth)/2.25+'px';

}

function displayFloatingDiv(divId, title, width, height, left, top,mask) {
    DivID = divId;

      var pageWidth = 0, pageHeight = 0;
      if( typeof( window.innerWidth ) == 'number' ) {
        //Non-IE
        pageWidth = window.innerWidth;
        pageHeight = window.innerHeight;
      } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
        //IE 6+ in 'standards compliant mode'
        pageWidth = document.documentElement.clientWidth;
        pageHeight = document.documentElement.clientHeight;
      } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
        //IE 4 compatible
        pageWidth = document.body.clientWidth;
        pageHeight = document.body.clientHeight;
      }

      left = (pageWidth - width)/2;
      top = (pageHeight - height)/2;


    //calculates the client Height and if blanck then assign value
    if(clientHeight =="" && clientWidth ==""){
     clientHeight=document.body.clientHeight;
     clientWidth=document.body.scrollWidth;
    }
    clientHeight=document.body.scrollHeight;
    clientWidth=document.body.scrollWidth;
    if(!mask){
        //document.getElementById('dimmer').style.visibility = "visible";
      
		document.getElementById('modalPage').style.display = "block";
        document.getElementById('modalPage').style.height=clientHeight+'px';
        document.getElementById('modalPage').style.width=clientWidth+'px';
        document.getElementById('modalPage2').style.display = "block";
        document.getElementById('modalPage2').style.height=clientHeight+'px';
        document.getElementById('modalPage2').style.width=clientWidth+'px';
		makeMenuDisable('qm0',true);

     }

    try{
       //to fix IE6's z-index problem
       document.getElementById('specialIframe').style.width = '100%';
       document.getElementById('specialIframe').style.height = height + 'px';
       document.getElementById('specialIframe').style.left = left + 'px';    
    }
    catch(e){
    }

    if(!mask) {
      over = false;
    }
    else {
      over = true;
    }

                                                                
                                                                
//    document.getElementById('modalPage2').style.height='849px';
    
    

 //   window.status=document.getElementById('modalPage').style.height;

    
    //it is the id of the menu(although can not be seen in html,generated dynamically)
    //makeMenuDisable('qm0',true);
    

   // document.getElementById(divId).style.width = width + 'px';
    //document.getElementById(divId).style.height = height + 'px';
    //document.getElementById(divId).style.left = left + 'px';

    //var w=document.getElementById(divId).style.width.split("px");
    //document.getElementById(divId).style.left =((window.screen.width- w[0])/2) + 'px';
    
    //document.getElementById(divId).style.top = top + 'px';
    
    document.getElementById(divId).className = 'dimming';
    document.getElementById(divId).style.zIndex=++zIndex; //z-index incremented by one
    
    document.getElementById(divId).style.visibility = "visible";
    document.getElementById(divId).style.display = 'block';


    if(winWDP==0 || winHDP==0){
        winWDP = (isMozilla)? window.innerWidth-20 : document.documentElement.clientWidth-20;
        winHDP = (isMozilla)? window.innerHeight : document.documentElement.clientHeight;
    }
    //sets divs left and top
    document.getElementById(divId).style.top=(winHDP-document.getElementById(divId).offsetHeight)/2+'px';
    document.getElementById(divId).style.left=(winWDP-document.getElementById(divId).offsetWidth)/2+'px';

}


/*****************/
//Purpose:To disable/enable all elements of the menu when popup div open/close
//Author:Dipanjan Bhattacharjee
//Date:28.08.2008
/******************/
function makeMenuDisable(id,disable){
 var nodesToDisable = {button :'', input :'', optgroup :'',
    option :'', select :'', textarea :'',span : '', a: ''};

    var node, nodes;
    var div = document.getElementById(id);
    if (!div) return;

    nodes = div.getElementsByTagName('*');
    if (!nodes) return;

    var i = nodes.length;
    while (i--){
     node = nodes[i];
     if ( node.nodeName && node.nodeName.toLowerCase() in nodesToDisable ){
         node.disabled = disable;
         node.style.cursor=(disable ? "default":"pointer");
        }
     }
	 if(document.getElementById('containfooter'))
	{
		 document.getElementById('containfooter').style.display='none';
	}
}


//
//
//
function hiddenFloatingDiv(divId) 
{
	//document.getElementById(divId).innerHTML = originalDivHTML;
    document.getElementById(divId).style.visibility='hidden';
	//document.getElementById('dimmer').style.visibility = 'hidden';
    document.getElementById('modalPage').style.display = "none";
    makeMenuDisable('qm0',false);
    over=false;      
	DivID = "";
	if(document.getElementById('containfooter'))
	{
		document.getElementById('containfooter').style.display='';
	}
}

//
function MouseDown(e) 
{
    if (over)
    {
        if (isMozilla) {
            objDiv = document.getElementById(DivID);
            X = e.layerX;
            Y = e.layerY;
            return false;
        }
        else {
            objDiv = document.getElementById(DivID);
            objDiv = objDiv.style;
            X = event.offsetX;
            Y = event.offsetY;
        }
    }
}

//
function MouseMove(e) 
{
  if(over){
	if (objDiv) {
        if (isMozilla) {
            objDiv.style.top = (e.pageY-Y) + 'px';
            objDiv.style.left = (e.pageX-X) + 'px';
            return false;
        }
        else 
        {
            objDiv.pixelLeft = event.clientX-X + document.body.scrollLeft;
            objDiv.pixelTop = event.clientY-Y + document.body.scrollTop;
            return false;
        }
    }
  }
}

//
function MouseUp() 
{
    objDiv = null;
}


//
function init()
{
    // check browser
    isMozilla = (document.all) ? 0 : 1;
    if (isMozilla) 
    {
//        document.captureEvents(Event.MOUSEDOWN | Event.MOUSEMOVE | Event.MOUSEUP);
    }
  //  document.onmousedown = MouseDown;
   // document.onmousemove = MouseMove;
    //document.onmouseup = MouseUp;

    // add the div
    // used to dim the page
	buildDimmerDiv();

}





//Purpose: escape key handler[closes pop up divs when escape key(27) is pressed]
//Author:Dipanjan Bhattacharjee
//Date:30.08.2008

  document.onkeypress=escapeHandler;


  function escapeHandler(e){
   var evt = ( (!document.all) ? e : window.event);
   if(evt.keyCode==27){
	   if(DivID!=""){
        hiddenFloatingDiv(DivID); 
       }
   }
 }


// call init
init();

//***********************for dragging/moving "Help" Divs************************
/**************************************************
* dom-drag.js
* 09.25.2001
* www.youngpup.net
* Script featured on Dynamic Drive (http://www.dynamicdrive.com) 12.08.2005
**************************************************
* 10.28.2001 - fixed minor bug where events
* sometimes fired off the handle, not the root.
**************************************************/
var Drag = {
obj : null,
init : function(o, oRoot, minX, maxX, minY, maxY, bSwapHorzRef, bSwapVertRef, fXMapper, fYMapper)
{
o.onmousedown	= Drag.start;
o.hmode			= bSwapHorzRef ? false : true ;
o.vmode			= bSwapVertRef ? false : true ;
o.root = oRoot && oRoot != null ? oRoot : o ;
if (o.hmode  && isNaN(parseInt(o.root.style.left  ))) o.root.style.left   = "0px";
if (o.vmode  && isNaN(parseInt(o.root.style.top   ))) o.root.style.top    = "0px";
if (!o.hmode && isNaN(parseInt(o.root.style.right ))) o.root.style.right  = "0px";
if (!o.vmode && isNaN(parseInt(o.root.style.bottom))) o.root.style.bottom = "0px";
o.minX	= typeof minX != 'undefined' ? minX : null;
o.minY	= typeof minY != 'undefined' ? minY : null;
o.maxX	= typeof maxX != 'undefined' ? maxX : null;
o.maxY	= typeof maxY != 'undefined' ? maxY : null;
o.xMapper = fXMapper ? fXMapper : null;
o.yMapper = fYMapper ? fYMapper : null;
o.root.onDragStart	= new Function();
o.root.onDragEnd	= new Function();
o.root.onDrag		= new Function();
},
start : function(e)
{
var o = Drag.obj = this;
e = Drag.fixE(e);
var y = parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
var x = parseInt(o.hmode ? o.root.style.left : o.root.style.right );
o.root.onDragStart(x, y);
o.lastMouseX	= e.clientX;
o.lastMouseY	= e.clientY;
if (o.hmode) {
if (o.minX != null)	o.minMouseX	= e.clientX - x + o.minX;
if (o.maxX != null)	o.maxMouseX	= o.minMouseX + o.maxX - o.minX;
} else {
if (o.minX != null) o.maxMouseX = -o.minX + e.clientX + x;
if (o.maxX != null) o.minMouseX = -o.maxX + e.clientX + x;
}
if (o.vmode) {
if (o.minY != null)	o.minMouseY	= e.clientY - y + o.minY;
if (o.maxY != null)	o.maxMouseY	= o.minMouseY + o.maxY - o.minY;
} else {
if (o.minY != null) o.maxMouseY = -o.minY + e.clientY + y;
if (o.maxY != null) o.minMouseY = -o.maxY + e.clientY + y;
}
document.onmousemove	= Drag.drag;
document.onmouseup		= Drag.end;
return false;
},
drag : function(e)
{
e = Drag.fixE(e);
var o = Drag.obj;
var ey	= e.clientY;
var ex	= e.clientX;
var y = parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
var x = parseInt(o.hmode ? o.root.style.left : o.root.style.right );
var nx, ny;
if (o.minX != null) ex = o.hmode ? Math.max(ex, o.minMouseX) : Math.min(ex, o.maxMouseX);
if (o.maxX != null) ex = o.hmode ? Math.min(ex, o.maxMouseX) : Math.max(ex, o.minMouseX);
if (o.minY != null) ey = o.vmode ? Math.max(ey, o.minMouseY) : Math.min(ey, o.maxMouseY);
if (o.maxY != null) ey = o.vmode ? Math.min(ey, o.maxMouseY) : Math.max(ey, o.minMouseY);
nx = x + ((ex - o.lastMouseX) * (o.hmode ? 1 : -1));
ny = y + ((ey - o.lastMouseY) * (o.vmode ? 1 : -1));
if (o.xMapper)		nx = o.xMapper(y)
else if (o.yMapper)	ny = o.yMapper(x)
Drag.obj.root.style[o.hmode ? "left" : "right"] = nx + "px";
Drag.obj.root.style[o.vmode ? "top" : "bottom"] = ny + "px";
Drag.obj.lastMouseX	= ex;
Drag.obj.lastMouseY	= ey;
Drag.obj.root.onDrag(nx, ny);
return false;
},
end : function()
{
document.onmousemove = null;
document.onmouseup   = null;
Drag.obj.root.onDragEnd(	parseInt(Drag.obj.root.style[Drag.obj.hmode ? "left" : "right"]), 
parseInt(Drag.obj.root.style[Drag.obj.vmode ? "top" : "bottom"]));
Drag.obj = null;
},
fixE : function(e)
{
if (typeof e == 'undefined') e = window.event;
if (typeof e.layerX == 'undefined') e.layerX = e.offsetX;
if (typeof e.layerY == 'undefined') e.layerY = e.offsetY;
return e;
}
};
