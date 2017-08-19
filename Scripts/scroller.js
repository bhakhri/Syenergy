/***********************************************
* Pausing up-down scroller- © Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
***********************************************/

function pausescroller(content, divId, divClass, delay){
this.content=content //message array content
this.tickerid=divId //ID of ticker div to display information
this.delay=delay //Delay between msg change, in miliseconds.
this.mouseoverBol=0 //Boolean to indicate whether mouse is currently over scroller (and pause it if it is)
this.hiddendivpointer=1 //index of message array for hidden div
document.write('<div id="'+divId+'" class="'+divClass+'" style="position: relative; overflow: hidden"><div class="innerDiv" style="position: absolute; width: 100%" id="'+divId+'1">'+content[0]+'</div><div class="innerDiv" style="position: absolute; width: 100%; visibility: hidden" id="'+divId+'2">'+content[1]+'</div></div>')
var scrollerinstance=this
if (window.addEventListener) //run onload in DOM2 browsers
window.addEventListener("load", function(){scrollerinstance.initialize()}, false)
else if (window.attachEvent) //run onload in IE5.5+
window.attachEvent("onload", function(){scrollerinstance.initialize()})
else if (document.getElementById) //if legacy DOM browsers, just start scroller after 0.5 sec
setTimeout(function(){scrollerinstance.initialize()}, 500)
}

// -------------------------------------------------------------------
// initialize()- Initialize scroller method.
// -Get div objects, set initial positions, start up down animation
// -------------------------------------------------------------------

pausescroller.prototype.initialize=function(){
this.tickerdiv=document.getElementById(this.tickerid)
this.visiblediv=document.getElementById(this.tickerid+"1")
this.hiddendiv=document.getElementById(this.tickerid+"2")
this.visibledivtop=parseInt(pausescroller.getCSSpadding(this.tickerdiv))
//set width of inner DIVs to outer DIV's width minus padding (padding assumed to be top padding x 2)
this.visiblediv.style.width=this.hiddendiv.style.width=this.tickerdiv.offsetWidth-(this.visibledivtop*2)+"px"
this.getinline(this.visiblediv, this.hiddendiv)
this.hiddendiv.style.visibility="visible"
var scrollerinstance=this
document.getElementById(this.tickerid).onmouseover=function(){scrollerinstance.mouseoverBol=1}
document.getElementById(this.tickerid).onmouseout=function(){scrollerinstance.mouseoverBol=0}
if (window.attachEvent) //Clean up loose references in IE
window.attachEvent("onunload", function(){scrollerinstance.tickerdiv.onmouseover=scrollerinstance.tickerdiv.onmouseout=null})
setTimeout(function(){scrollerinstance.animateup()}, this.delay)
}


// -------------------------------------------------------------------
// animateup()- Move the two inner divs of the scroller up and in sync
// -------------------------------------------------------------------

pausescroller.prototype.animateup=function(){
var scrollerinstance=this
if (parseInt(this.hiddendiv.style.top)>(this.visibledivtop+5)){
this.visiblediv.style.top=parseInt(this.visiblediv.style.top)-5+"px"
this.hiddendiv.style.top=parseInt(this.hiddendiv.style.top)-5+"px"
setTimeout(function(){scrollerinstance.animateup()}, 50)
}
else{
this.getinline(this.hiddendiv, this.visiblediv)
this.swapdivs()
setTimeout(function(){scrollerinstance.setmessage()}, this.delay)
}
}

// -------------------------------------------------------------------
// swapdivs()- Swap between which is the visible and which is the hidden div
// -------------------------------------------------------------------

pausescroller.prototype.swapdivs=function(){
var tempcontainer=this.visiblediv
this.visiblediv=this.hiddendiv
this.hiddendiv=tempcontainer
}

pausescroller.prototype.getinline=function(div1, div2){
div1.style.top=this.visibledivtop+"px"
div2.style.top=Math.max(div1.parentNode.offsetHeight, div1.offsetHeight)+"px"
}

// -------------------------------------------------------------------
// setmessage()- Populate the hidden div with the next message before it's visible
// -------------------------------------------------------------------

pausescroller.prototype.setmessage=function(){
var scrollerinstance=this
if (this.mouseoverBol==1) //if mouse is currently over scoller, do nothing (pause it)
setTimeout(function(){scrollerinstance.setmessage()}, 100)
else{
var i=this.hiddendivpointer
var ceiling=this.content.length
this.hiddendivpointer=(i+1>ceiling-1)? 0 : i+1
this.hiddendiv.innerHTML=this.content[this.hiddendivpointer]
this.animateup()
}
}

pausescroller.getCSSpadding=function(tickerobj){ //get CSS padding value, if any
if (tickerobj.currentStyle)
return tickerobj.currentStyle["paddingTop"]
else if (window.getComputedStyle) //if DOM2
return window.getComputedStyle(tickerobj, "").getPropertyValue("padding-top")
else
return 0
}





// ======================
//      News Ticker
// ======================
// Usage: 
//      initTicker(Number To Show, Character Delay (in ms), String Delay (in ms), Link Page)
// Example:
//      initTicker(3, 500, 5000, "news.aspx")
    var arrNews = new Array("", "", "", "", "", "", "");
    var arrIDs = new Array("", "", "", "", "", "");
    
    var chrPos = 0;         // Starting character position
    var strPos = 0;         // Starting string position
    
    var chrPeriod = 100;    // Delay between characters = 0.1 secs
    var strPeriod = 500;   // Delay between strings = 2 secs
    
    var chrTimer = null;  // Timer to show next character
    var strTimer = null;  // Timer to show next string

    var MainLoop = 5;       // Number of strings in main loop
    var RunningTimer = "c"  // To flag which timer is running: c / s
    var NewsPage = ""; // The news page to link to for each item
    
    function ShowNextChr(){
        var currStr = arrNews[strPos];
        
        if(chrPos <= currStr.length){
            RunningTimer = "c";
            var tickerobj = document.getElementById('ticker');
            
            if(arrIDs[strPos] == "DEADLINKACTIVE")
                tickerobj.innerHTML = currStr.substr(0, chrPos); 
            else
                tickerobj.innerHTML =  currStr.substr(0, chrPos);
            chrPos++;   // Increment chr position
            chrTimer = setTimeout("ShowNextChr()", chrPeriod);  // call this function again
        }
        else        // We've reached the end of the current string, so stop showing chrs, and move to next string
        {
            RunningTimer = "s";
            chrPos = 0;     // Reset back to starting character position as we will move to next string now
            strTimer = setTimeout("ShowNextStr()", strPeriod);
        }
    }
    function Pause()
    {
     if (RunningTimer=="c")      // If char timer running
        {
        
            clearTimeout(chrTimer); // Cancel the current timer
            chrPos = arrNews[strPos].length;    // Set char position to final char in current string
            var currStr = arrNews[strPos];  // The current string
           if(chrPos <= currStr.length)     // As long as we have not passed the end of the string
           {
             RunningTimer = "c";
             var tickerobj = document.getElementById('ticker');
             tickerobj.innerHTML =  currStr.substr(0, chrPos) ;   // write from beginning to current chr position
             chrPos++;   // Increment chr position
             //chrTimer = setTimeout("ShowNextChr()", chrPeriod);  // call this function again
           }
      }     
    }
    
    function Stop(){
     clearTimeout(chrTimer);
     RunningTimer = "";   
    }
    function ShowNextStr()
    {
        // Check if position is at end of available items or end of items to show
        if ((strPos < arrNews.length -1) && (strPos < MainLoop - 1))
            strPos++;       // Go to next string in array
        else
            strPos = 0;     // Reset to starting position

        chrTimer = setTimeout("ShowNextChr()", chrPeriod);  // In either case, start showing chrs again
    }
    
    function showPrev()
    {
        // This function is different from ShowNext, since when Previous is clicked, 
        // the user is taken directly to the string *before* the current one,
        // and not the previous character. In case it's clicked on the first string
        // nothing will happen.
        
        // Clear both timers
        clearTimeout(chrTimer);
        clearTimeout(strTimer);
        
        if(strPos==0)
         strPos=MainLoop;    
        if (strPos > 0) 
            strPos--;

        
        chrPos = 0;          
        ShowNextChr();    
    }

    function showNext()     // called when the Next button is clicked
    {
        // Check which is the running timer currrenty
        if (RunningTimer=="c")      // If char timer running
        {
            clearTimeout(chrTimer); // Cancel the current timer
            chrPos = arrNews[strPos].length;    // Set char position to final char in current string
            ShowNextChr();      // Show the next (i.e. till last) char of current string
        }
        else    // If str timer running
        {
            clearTimeout(strTimer);     // Cancel the current timer
            if (strPos < arrNews.length-1)    // Check that we are not past the number of total items in the array
                strPos++;       // Go to next string
            else
                strPos = 0;     // Go back to first string
            chrPos = 0;     // In either case, set char position back to first char of string
            ShowNextChr();  // Show the next char
        }
    }
    
    function initTicker(mainloop, chrdelay, strdelay, newspage)
    {
        MainLoop = mainloop;
        chrPeriod = chrdelay;
        strPeriod = strdelay;
        NewsPage = newspage;
        //document.write("<span id='ticker' class='text_scroll'></span><div id=\"headerimg\" > <a href='javascript:showPrev()'><img src='"+imagePathURL+"/Tickerprevious.gif' border=0></a><a href='javascript:Pause()'><img src='"+imagePathURL+"/Tickerpause.gif' border=0></a><a href='javascript:showNext()'><img src='"+imagePathURL+"/Tickerplay.gif' border=0></a></div>");
        document.getElementById('headertextbox').innerHTML="<span id='ticker' class='text_scroll'></span><div id=\"headerimg\" > <a href='javascript:showPrev()'><img src='"+imagePathURL+"/Tickerprevious.gif' border=0></a><a href='javascript:Pause()'><img src='"+imagePathURL+"/Tickerpause.gif' border=0></a><a href='javascript:showNext()'><img src='"+imagePathURL+"/Tickerplay.gif' border=0></a></div>";
        chrTimer = setTimeout("ShowNextChr()", chrPeriod);      // Start the timer
    }