<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF events ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (4.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','AddEvent');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Manage Events</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeCSS("phpCalendar.css");  
?> 
</head>

<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
new Array('eventTitle','Event Title','width="121"','',true), 
new Array('shortDescription','Short Description','width="200"','',true) , 
new Array('startDate','Start Date','width="120"','align="center"',true),
new Array('endDate','End Date','width="120"','align="center"',true),
new Array('roleIds','Visible To','width="119"','',false),
new Array('actionString','Action','width="8%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Calendar/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddEvent';   
editFormName   = 'EditEvent';
winLayerWidth  = 525; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteEvent';
divResultName  = 'results';
page=1; //default page
sortField = 'eventTitle';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button



//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY EVENTLISTS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 
 var cdate=""; //current date
 var smonth=""; //selected month (used to pass selected month and year to displayQCalendar(smonth,syear) function to refresh it after
 var syear=""; //selected year  // add/edit/delete on event table;
function show_div(d,m,y){

	try {
		smonth=m;
		syear=y;
		
		if(m<10){
			var m1="0"+parseInt(m,10);
		}
		else{
			var m1=m;
		}
		if(d<10){
			var d1="0"+parseInt(d,10);
		}
		else{
		   var d1=d; 
		}

		cdate=y+"-"+m1+"-"+d1;
		 
		document.getElementById('eventDate').innerHTML="Events for : " + customParseDate(cdate,"-");
		document.getElementById('show_event_list').style.display = 'block';  
	  // document.searchForm.sdate.value=y+"-"+m+"-"+d;   

		sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
	 } catch(e) { }
}

//------------------------------------------------------------------------
//Purpose:Show all events for a month
//Author:Dipanjan Bhattacharjee
//Date:2.09.2008
//------------------------------------------------------------------------
/*
function show_all_events(){
  show_div(0,smonth,syear);  
}

*/
function hide_div(){
    
    document.getElementById('eventDate').innerHTML="";
    document.getElementById('show_event_list').style.display = 'none';  
}



//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id) {
 displayWindow('EditEvent',525,250);
 document.getElementById('roles2').style.display='';
// makeDDHide('roles2','d222','d333');
 document.getElementById('d111').style.zIndex=parseInt(document.getElementById('EditEvent').style.zIndex,10)+20;
 document.getElementById('d222').style.zIndex=parseInt(document.getElementById('EditEvent').style.zIndex,10)+10;
 document.getElementById('d333').style.zIndex=parseInt(document.getElementById('EditEvent').style.zIndex,10)+10;
 document.getElementById('d111').style.height='200px';
    //***As the Div is Huge so we have to incorporate this function.
    //Same functionality but can set left and top of the Div also***
    //displayFloatingDiv(dv,'', w, h, 200, 150)
    populateValues(id);    
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {

    var fieldsArray = new Array(
        new Array("eventTitle","<?php echo ENTER_EVENT_TITLE; ?>"),
        new Array("shortDescription","<?php echo ENTER_SHORT_DESC; ?> "),
        new Array("longDescription","<?php echo ENTER_LONG_DESC; ?>")
        //,new Array("roles","<?php echo SELECT_ROLE2; ?>")
    );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
          if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<1 && fieldsArray[i][0]=='eventTitle' ){
                alert("<?php echo EVENT_TITLE_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
        }
        /*
        else if(fieldsArray[i][0]=="roles" && eval("frm."+(fieldsArray[i][0])+".value")=="" )  {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
       */ 
       else if(act=='Add') {
        if(!dateDifference(eval("frm.startDate1.value"),eval("frm.endDate1.value"),'-') ) {
                alert("<?php echo DATE_VALIDATION1; ?>");
                eval("frm.startDate1.focus();");
                return false;
                break;
        }
       }
       else if(act=='Edit') {
        if(!dateDifference(eval("frm.startDate2.value"),eval("frm.endDate2.value"),'-') ) {
                alert("<?php echo DATE_VALIDATION1; ?>");
                eval("frm.startDate2.focus();");
                return false;
                break;
         }
       }  
       else  {
               if (!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php ENTER_ALPHABETS_NUMERIC; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
                }
            }
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
   }
        
    if(act=='Add') {
        if(eval("frm.roles1.value")=='') {
                alert("<?php echo SELECT_ROLE2; ?>");
                eval("frm.roles1.focus();");
                return false;
        }
        addEvent();
        return false;
    }
    else if(act=='Edit') {
        if(eval("frm.roles2.value")=='') {
                alert("<?php echo SELECT_ROLE2; ?>");
                eval("frm.roles2.focus();");
                return false;
        }
        editEvent();
        return false;
    } 
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD AN EVENT
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addEvent() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Calendar/ajaxInitAdd.php';
         
         var l=document.AddEvent.roles1.length;
         var selRoles="";
         for(var i=0 ; i < l ;i++){
             if(document.AddEvent.roles1.options[i].selected){
                 if(selRoles==""){
                     selRoles=document.AddEvent.roles1.options[i].value;
                 }
                else{
                      selRoles+="~"+document.AddEvent.roles1.options[i].value;
                }     
             }
         }
		
        selRoles="~"+selRoles+"~"; 
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {eventTitle: (document.AddEvent.eventTitle.value), 
             shortDescription: (document.AddEvent.shortDescription.value), 
             longDescription: (document.AddEvent.longDescription.value), 
             startDate: (document.AddEvent.startDate1.value), 
             endDate: (document.AddEvent.endDate1.value),
             roleIds:selRoles
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                        else if("<?php echo EVENT_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo EVENT_ALREADY_EXIST ;?>"); 
                         document.AddEvent.eventTitle.focus();
                        }  
                         else {
                             hiddenFloatingDiv('AddEvent');
                             //refreshes calendar
                             displayQCalendar(smonth,syear);   
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


//--------------------------------------------------------   
//THIS FUNCTION IS USED TO DELETE AN event
//  id=eventId
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteEvent(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         var url = '<?php echo HTTP_LIB_PATH;?>/Calendar/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {eventId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         //refreshes calendar
                         displayQCalendar(smonth,syear);
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     } 
                     else {
                        messageBox(trim(transport.responseText));                         
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }    
           
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "Addevent" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var d="<?php echo date('Y-m-d'); ?>";
function blankValues() {
	try {
	   document.AddEvent.eventTitle.value = '';
	   document.AddEvent.shortDescription.value = '';
	   document.AddEvent.longDescription.value = ''; 
	   document.getElementById('roles1').style.display='';
	   //var d=new Date();
	   
	   //var thisDate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));  
	   var thisDate=d; 
		var day=cdate.split("-");
		if(day[2]!="00"){ //if not for a specific date;
		 document.AddEvent.startDate1.value = cdate;
		 document.AddEvent.endDate1.value = cdate;
		}
		else{
		  document.AddEvent.startDate1.value = thisDate;
		  document.AddEvent.endDate1.value = thisDate;   
		}  

	   //clearing selected options
	   var l=document.AddEvent.roles1.length;
	   for(var i=0 ; i < l ;i++){
	   if(document.AddEvent.roles1.options[ i ].selected){
			 document.AddEvent.roles1.options[ i ].selected=false;
		 } 
	   }
	   document.AddEvent.eventTitle.focus();
	   
	   makeDDHide('d22','d33');
	   document.getElementById('d11').style.zIndex=parseInt(document.getElementById('AddEvent').style.zIndex,10)+20;
	   document.getElementById('d22').style.zIndex=parseInt(document.getElementById('AddEvent').style.zIndex,10)+10;
	   document.getElementById('d33').style.zIndex=parseInt(document.getElementById('AddEvent').style.zIndex,10)+10;
	   document.getElementById('d11').style.height='200px';
	} catch(e) { }
}

//used to close popuped div [over riding function defined in js file] 
function hiddenFloatingDiv(divId) 
{
    document.getElementById(divId).style.visibility='hidden';
    document.getElementById('modalPage').style.display = "none";
    makeMenuDisable('qm0',false);
    over=false;      
    DivID = "";
    if(document.getElementById('containfooter'))
    {
        document.getElementById('containfooter').style.display='';
    }
 try{  
  document.getElementById('d11').style.display='none';
  document.getElementById('d22').style.display='none';
  //document.getElementById('roles1').style.display='none';
 }
 catch(e){}
 try{ 
  document.getElementById('d111').style.display='none';
  document.getElementById('d222').style.display='none';
  //document.getElementById('roles2').style.display='none';
 }
 catch(e){}

}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT An Event
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editEvent() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Calendar/ajaxInitEdit.php';
         
         var l=document.EditEvent.roles2.length;
         var selRoles="";
         for(var i=0 ; i < l ;i++){
             if(document.EditEvent.roles2.options[ i ].selected){
                 if(selRoles==""){
                     selRoles=document.EditEvent.roles2.options[ i ].value;
                 }
                else{
                      selRoles+="~"+document.EditEvent.roles2.options[ i ].value;
                }     
             }
         }
        selRoles="~"+selRoles+"~"; 
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {eventId: (document.EditEvent.eventId.value),
             eventTitle: (document.EditEvent.eventTitle.value), 
             shortDescription: (document.EditEvent.shortDescription.value), 
             longDescription: (document.EditEvent.longDescription.value), 
             startDate: (document.EditEvent.startDate2.value), 
             endDate: (document.EditEvent.endDate2.value),
             roleIds:selRoles
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditEvent');
                         //refreshes calendar
                         displayQCalendar(smonth,syear);
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        
                         return false;
                         //location.reload();
                     }
                   else if("<?php echo EVENT_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo EVENT_ALREADY_EXIST ;?>"); 
                         document.EditEvent.eventTitle.focus();
                        }  
                    else {
                        messageBox(trim(transport.responseText));                         
                     } 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditEvent" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
        var url = '<?php echo HTTP_LIB_PATH;?>/Calendar/ajaxGetValues.php';
         
         //clearing selected options
           var l=document.EditEvent.roles2.length;
           for(var i=0 ; i < l ;i++){
           if(document.EditEvent.roles2.options[ i ].selected){
                 document.EditEvent.roles2.options[ i ].selected=false;
             } 
           }
   
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {eventId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditEvent');
                        messageBox("<?php echo EVENT_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        //return false;
                   }
                   
                    j = eval('('+trim(transport.responseText)+')');
                
                   document.EditEvent.eventTitle.value = j.eventTitle;
                   document.EditEvent.shortDescription.value = j.shortDescription;
                   document.EditEvent.longDescription.value = j.longDescription;
                   document.EditEvent.startDate2.value =j.startDate;
                   document.EditEvent.endDate2.value =j.endDate;
                   
                   var role=j.roleIds.split("~");
                   var l=document.EditEvent.roles2.length;
                   var m=role.length;
                   
                   for(var n =0 ; n < m ;n++){
                     for(var i=0 ; i < l ;i++){
                      if(document.EditEvent.roles2.options[ i ].value==role[n]){
                          document.EditEvent.roles2.options[ i ].selected=true;
                      }
                    }
                  }
                  
                   totalSelected('roles2','d333');
                   
                   document.EditEvent.eventTitle.focus();
                   document.EditEvent.eventId.value =j.eventId;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY Event Div
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function showEventDetails(id) {
     
    displayFloatingDiv('divEvent','', 525, 250, 200, 180)
    //displayWindow('divEvent',300,200);
    populateEventValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divAttendance" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateEventValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxGetEventDetails.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {eventId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('divEvent');
                        messageBox("This Event Record Doen Not Exists");
                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
          
				   }
                   var j = eval('('+trim(trim(transport.responseText))+')');
                   document.getElementById('eventTitle3').innerHTML = trim(j.eventTitle);
                   document.getElementById('shortDescription3').innerHTML = trim(j.shortDescription);
                   document.getElementById('longDescription3').innerHTML = trim(j.longDescription);
                   document.getElementById('startDate3').innerHTML = customParseDate(j.startDate,"-");
                   document.getElementById('endDate3').innerHTML = customParseDate(j.endDate,"-");
                   
                   
                   /*document.EventForm.eventTitle.value = trim(j.eventTitle);
                   document.EventForm.shortDescription.value = trim(j.shortDescription);
                   document.EventForm.longDescription.value = trim(j.longDescription);
                   document.EventForm.startDate.value = customParseDate(j.startDate,"-");
                   document.EventForm.endDate.value = customParseDate(j.endDate,"-");*/

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print city report*/
function printReport() {
   // var qstr="sdate="+trim(document.AddEvent.startDate1.value)+"&searchbox="+trim(document.AddEvent.endDate1.value);
	var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr +="&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/calendarReportPrint.php?'+qstr;
    window.open(path,"CalendarReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
   // var qstr="sdate="+trim(document.searchForm.sdate.value)+"&searchbox="+trim(document.searchForm.searchbox.value);
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
	qstr +="&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='<?php echo UI_HTTP_PATH;?>/calendarReportCSV.php?'+qstr;
}


function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){    
  var form = document.AddEvent;
 }
 else{
     var form = document.EditEvent;
 }
  eval('form.'+eleName+'.focus()');
  return false;
 }
}

var initialTextForMultiDropDowns='Click to select multiple items';
var selectTextForMultiDropDowns='items';
window.onload=function(){
   show_div(0,<?php echo date('m');?>,<?php echo date('Y');?>);
}
</script>

<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Calendar/listCalendarContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
// $History: listCalendar.php $ 
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 4/02/10    Time: 12:55
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//0002528,0002303,0002193,0001928,
//0001922,0001863,0001763,0001238,
//0001229,0001894,0002143
//
//*****************  Version 8  *****************
//User: Parveen      Date: 2/03/10    Time: 3:32p
//Updated in $/LeapCC/Interface
//access permission updated
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 4/01/10    Time: 19:01
//Updated in $/LeapCC/Interface
//Made UI changes
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 19/08/09   Time: 15:26
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---00001141,00001142
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/19/09    Time: 11:13a
//Updated in $/LeapCC/Interface
//Gurkeerart: fixed issue 1140
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 5/08/09    Time: 12:39
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//0000887 to 0000895,
//0000906 to 0000909
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 4/08/09    Time: 17:07
//Updated in $/LeapCC/Interface
//Corrected "Event Masters" as pointed by Kanav Sir
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:23p
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 11/03/08   Time: 11:50a
//Updated in $/Leap/Source/Interface
//Added "MANAGEMENT_ACCESS" variable as these files are used in admin as
//well as in management role
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 10/24/08   Time: 1:36p
//Updated in $/Leap/Source/Interface
//Added functionality for calendar event report print
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 9/24/08    Time: 12:46p
//Updated in $/Leap/Source/Interface
//Corrected javascript error thrown by Internet Explorer
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 9/02/08    Time: 2:01p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/21/08    Time: 12:09p
//Updated in $/Leap/Source/Interface
//Added Standard Messages
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/08/08    Time: 7:24p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/08/08    Time: 3:06p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:03p
//Updated in $/Leap/Source/Interface
//Added onCreate() function in ajax code
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/28/08    Time: 5:48p
//Updated in $/Leap/Source/Interface
//Modified delmiter from ~field to ~field~
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/05/08    Time: 12:28p
//Updated in $/Leap/Source/Interface
//Added SessionId in the code 
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/04/08    Time: 7:19p
//Updated in $/Leap/Source/Interface
//Created Calendar(event) module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/03/08    Time: 12:34p
//Created in $/Leap/Source/Interface
//Initial Checkin
?>