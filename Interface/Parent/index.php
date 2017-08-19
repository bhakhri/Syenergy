<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax functions used in "DashBoard" index Module
//
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

//used for showing Parent dashboard
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ParentAlerts');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn();
//require_once(BL_PATH . "/Parent/initDashboard.php");
require_once(BL_PATH . "/Student/initDashboardStudent.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Home </title>
<?php 
    //require_once(TEMPLATES_PATH .'/Student/jsCssHeader.php'); 
    require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?>
</head>
<body>
<?php
function trim_output($str,$maxlength,$mode=1,$rep='...'){
   $ret=($mode==2?chunk_split($str,30):$str);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep;
   }
  return $ret;
}


function trim_output2($str,$maxlength) {
    if (strlen($str) > $maxlength) {
        $str = substr($str, 0, $maxlength);
        $str .= '...';
    }
    return $str;
}
 ?>
<script language="javascript">
//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY Notice Div
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function showNoticeDetails(id,dv,w,h) {
    //displayWindow('divNotice',600,600);
    displayFloatingDiv(dv,'', w, h, 200, 180)
    populateNoticeValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divNotice" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateNoticeValues(id) {
    
    url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxGetNoticeDetails.php'; 
    new Ajax.Request(url,
    {
     method:'post',
     parameters: {noticeId: id},
     onCreate: function() {
         showWaitDialog();
     },
     onSuccess: function(transport){
             hideWaitDialog();
            if(trim(transport.responseText)==0) {
                hiddenFloatingDiv('divNotice');
                messageBox("This Notice Record Doen Not Exists");
                //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                //return false;
           }
            j = eval('('+trim(transport.responseText)+')');
             
          document.getElementById('noticeSubject').innerHTML = trim(j.noticeSubject);
          document.getElementById('noticeDepartment').innerHTML = trim(j.departmentName+' ('+j.abbr+')');
          
          document.getElementById('noticeText').innerHTML = trim(j.noticeText);
          document.getElementById('visibleToDate').innerHTML=customParseDate(j.visibleToDate,"-");
          document.getElementById('visibleFromDate').innerHTML=customParseDate(j.visibleFromDate,"-");

     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

	function showEventDetails(id,dv,w,h) {
	 
	height=screen.height/5;
	width=screen.width/4.5;
	displayFloatingDiv(dv,'', w, h, width,height);
    populateEventValues(id); 
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divAttendance" DIV
//
//Author : Jaineesh
// Created on : (04.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateEventValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxEventsGetValues.php';
		 new Ajax.Request(url,
           {      
             method:'post',
             parameters: {eventId: id},
				 
              onCreate: function() {
			 	showWaitDialog();
			 },
			 onSuccess: function(transport){
			      hideWaitDialog();
		          j= trim(transport.responseText).evalJSON();
	              document.getElementById("innerEvent").innerHTML = j.eventTitle;
				  document.getElementById("innerDescription").innerHTML = j.shortDescription;
				  document.getElementById("longDescription").innerHTML = j.longDescription;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function showAdminDetails(id,dv,w,h) {
	 
	height=screen.height/5;
	width=screen.width/4.5;
	displayFloatingDiv(dv,'', w, h, width,height);
    populateAdminValues(id); 
}

//This function populates values in View Deatil form through ajax 

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divAdmin" DIV
//
//Author : Jaineesh
// Created on : (04.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	function populateAdminValues(id) {
		 url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxAdminGetValues.php';
        
		 new Ajax.Request(url,
		   {      
			 method:'post',
			 parameters: {messageId: id},
				 
			  onCreate: function() {
				showWaitDialog();
			 },
			 onSuccess: function(transport){
				  hideWaitDialog();
				  //j= trim(transport.responseText).evalJSON();
				  j = eval('('+transport.responseText+')');
                  
				  document.getElementById("innerAdmin").innerHTML = j.message;
                  document.getElementById("innerSubject").innerHTML = j.subject;
                  document.getElementById("visibleMessageFromDate").innerHTML = customParseDate(j.visibleFromDate,"-");
                  document.getElementById("visibleMessageToDate").innerHTML = customParseDate(j.visibleToDate,"-");
			 },
			 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		   });
	}


//function to show Task Details /////

//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Jaineesh
// Created on : (05.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
    
   
    var fieldsArray = new Array(new Array("title","<?php echo ENTER_TITLE ?>"), 
								new Array("shortDesc","<?php echo ENTER_SHORT_DESC?>"),
								new Array("daysPrior","<?php echo ENTER_DAYS_PRIOR?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }


	//var d=new Date();
    
         
       /* else if((eval("frm."+(fieldsArray[i][0])+".value.length"))<2 && fieldsArray[i][0]=='testTypeName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo TESTTYPE_NAME_LENGTH ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }         */ 
            
        else  if (!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]=='title' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_STRING ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
                }

		else  if (!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]=='shortDesc' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_STRING ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
                }

		else if (fieldsArray[i][0]=='daysPrior'){
			if (!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))){ 
			//winAlert("Enter string",fieldsArray[i][0]);
			messageBox("<?php echo ENTER_NUMBER ?>");
			document.TaskDetail.daysPrior.value="";
			eval("frm."+(fieldsArray[i][0])+".focus();");
			return false;
			break;
			}
        }
		
		else if ((document.getElementById("dashboard").checked == false) && (document.getElementById("sms").checked == false)) {
			alert("One checkbox should be checked");
			return false;
		}
            
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
  
        editTask();
        return false;
    }
var ftd='';std='';jtd='';ctd='';
var globalTdId = '';
function showTaskDetails(tdid,id,dv,w,h) {
    jtd= document.getElementById('tasksTdId_'+tdid);
	ftd= document.getElementById('tasksTdId__'+tdid);
    std= document.getElementById('tasksTdId___'+tdid);
	ctd= document.getElementById('tasksTdId____'+tdid);
	globalTdId = tdid;
	height=screen.height/5;
	width=screen.width/4.5;
	displayFloatingDiv(dv,'', w, h, width,height);
    populateTaskValues(id); 
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A DOCUMENT
//
//Author : Jaineesh
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editTask() {
         url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxInitTaskDashboardEdit.php';
        
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
					taskId:				trim(document.getElementById('taskId').value),
					title:				trim(document.getElementById('title').value),
					shortDesc:			trim(document.getElementById('shortDesc').value),
					dueDate:			(document.getElementById('dueDate').value),
					
					//reminder:			reminder,
					dashboard:			(document.getElementById('dashboard').checked?1:0),
					sms:				(document.getElementById('sms').checked?2:0),
					daysPrior:			(document.getElementById('daysPrior').value),
					status:				(document.getElementById('status').value)
              },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
					 var ret=trim(transport.responseText).split('~');
                     if("<?php echo SUCCESS;?>" == ret[0]) {
                         hiddenFloatingDiv('ViewTasks');
						 //alert(document.getElementById('status').value);
						 //alert(Nan(document.getElementById('dueDate').value) - Nan(document.getElementById('daysPrior').value));

						 if(document.getElementById('status').value == 0) {
							jtd.style.color = "red";
							currentId = 'statusLink'+globalTdId;
							ftd.innerHTML="<a href=\"#\" name=\"bubble\" onclick=\"showTaskDetails("+globalTdId+",'"+document.getElementById('taskId').value+"','ViewTasks',350,250);return false;\"  title='"+trim(document.getElementById('shortDesc').value)+"' id='"+currentId+"' style=\"color:red\">"+trim(document.getElementById('title').value)+"</a>";
							std.innerHTML=ret[1];
							std.style.color="red";
							//ctd.innerHTML = '<img src="" alt="">';
							//alert(ctd.innerHTML);
							//ctd.innerHTML="<img src='<?php echo IMG_HTTP_PATH; ?>/deactive.gif' border=\"0\" alt=\"Pending\" title=\"Pending\" width=\"10\" height=\"10\" style=\"cursor:pointer\" onclick=\"changeStatus("+globalTdId+","+document.getElementById('taskId').value+","+document.getElementById('status').value+")>";
							//alert(ctd.innerHTML);
							ctd.innerHTML='<img src="<?php echo IMG_HTTP_PATH; ?>/deactive.gif" border="0" alt="Pending" title="Pending" width="10" height="10" style="cursor:pointer" onclick="changeStatus('+globalTdId+','+document.getElementById('taskId').value+','+document.getElementById('status').value+')">';
						 }
						 else {
							 
							jtd.style.color = "black";
							currentId = 'statusLink'+globalTdId;
							ftd.innerHTML="<a href=\"#\" name=\"bubble\" onclick=\"showTaskDetails("+globalTdId+",'"+document.getElementById('taskId').value+"','ViewTasks',350,250);return false;\"  id='"+currentId+"' title='"+trim(document.getElementById('shortDesc').value)+"' style=\"color:black\">"+trim(document.getElementById('title').value)+"</a>";
							std.innerHTML=ret[1];
							std.style.color="black";
							ctd.innerHTML='<img src="<?php echo IMG_HTTP_PATH; ?>/active.gif" border="0" alt="Completed" title="Completed" width="10" height="10" style="cursor:pointer" onclick="changeStatus('+globalTdId+','+document.getElementById('taskId').value+','+document.getElementById('status').value+')">';

							//ctd.innerHTML="<img src='<?php echo IMG_HTTP_PATH; ?>/active.gif' border=\"0\" alt=\"Pending\" title=\"Pending\" width=\"10\" height=\"10\" style=\"cursor:pointer\" onclick=\"changeStatus("+globalTdId+","+document.getElementById('taskId').value+","+document.getElementById('status').value+")>";
							

						 }
						 jtd='';std='';ftd='';ctd='';
                        
                     }
                   else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo TITLE_ALREADY_EXIST; ?>'){
							document.getElementById('title').value="";
							document.getElementById('title').focus();
						}
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divNotice" DIV
//
//Author : Jaineesh
// Created on : (04.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function populateTaskValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxTaskGetValues.php';
		 
		 new Ajax.Request(url,
           {      
             method:'post',
             parameters: {taskId: id},
				 
              onCreate: function() {
			 	showWaitDialog();
			 },
				 
			 onSuccess: function(transport){
				 
			      hideWaitDialog();
		          j= trim(transport.responseText).evalJSON();
					
					document.getElementById('taskId').value			= j.taskId;

					document.getElementById('title').value			= j.title;

					document.getElementById('shortDesc').value		= j.shortDesc;
					document.getElementById('dueDate').value		= j.dueDate;
					if (j.reminderOptions == "1,0") {
					
					document.getElementById('dashboard').checked=true;
					document.getElementById('sms').checked=false;
				   }
				   if (j.reminderOptions == "0,2") {
					document.getElementById('dashboard').checked=false;
					document.getElementById('sms').checked=true;
				   }

				   if (j.reminderOptions == "1,2") {
					//document.getElementById('showDashboard').style.display = '';
					document.getElementById('dashboard').checked=true;
					document.getElementById('sms').checked=true;
				   }
			   
				   document.getElementById('daysPrior').value			= j.daysPrior;
				   document.getElementById('status').value			= j.status;	
			 
                   document.getElementById('title').focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A DOCUMENT
//
//Author : Jaineesh
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function changeStatus(tdid,id,status) {
	
	var ftd='';std='';jtd='';ctd='';
	var globalTdId = '';

	jtd= document.getElementById('tasksTdId_'+tdid);
	ftd= document.getElementById('tasksTdId__'+tdid);
    std= document.getElementById('tasksTdId___'+tdid);
	ctd= document.getElementById('tasksTdId____'+tdid);
	globalTdId = tdid;


         url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxInitTaskStatusChange.php';
		 
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
							taskId:	id,
							status: status
              },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
				 
                     if("<?php echo SUCCESS;?>" ==  trim(transport.responseText)) {
                         hiddenFloatingDiv('ViewTasks');
						 

						 if(status == 0) {
							jtd.style.color = "black";
							ftd.style.color = "black";
							currentId = 'statusLink'+globalTdId;
							eval("document.getElementById('"+currentId+"').style.color = 'black'");
							std.style.color = "black";
							//ctd.innerHTML='<img src="<?php echo IMG_HTTP_PATH; ?>/active.gif" border="0" alt="Completed" title="Completed" width="10" height="10" style="cursor:pointer" onclick="changeStatus('+globalTdId+','+id+','+1+')"/>';

							ctd.innerHTML = "<img src='<?php echo IMG_HTTP_PATH; ?>/active.gif' border=\"0\" alt=\"Completed\" title=\"Completed\" width=\"10\" height=\"10\" style=\"cursor:pointer\" onclick=\"changeStatus("+globalTdId+",'"+id+"','"+1+"')\">";
						 }
						 else {
							jtd.style.color = "red";
							ftd.style.color = "red";
							std.style.color="red";
							currentId = 'statusLink'+globalTdId;
							eval("document.getElementById('"+currentId+"').style.color = 'red'");
							//ctd.innerHTML='<img src="<?php echo IMG_HTTP_PATH; ?>/deactive.gif" border="0" alt="Pending" title="Pending" width="10" height="10" style="cursor:pointer" onclick="changeStatus("'+globalTdId+'","'+id+'","'+0+'")"/>';

							ctd.innerHTML="<img src='<?php echo IMG_HTTP_PATH; ?>/deactive.gif' border=\"0\" alt=\"Pending\" title=\"Pending\" width=\"10\" height=\"10\" style=\"cursor:pointer\" onclick=\"changeStatus("+globalTdId+",'"+id+"','"+0+"')\">";
						 }
						 jtd='';std='';ftd='';ctd='';

                         //getTask();
						 //emptySlotId();
						 //document.getElementById('taskId').value = '';
                         //return false;
                     }
                    
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}

//This function Displays Div Window
function editWindow(id,dv,w,h) {
    //displayWindow(dv,w,h);
    height=screen.height/7;
    width=screen.width/4;
    displayFloatingDiv(dv,'', w, h, width,height);
    populateValues(id);   
}


//This function populates values in View Deatil form through ajax 
function populateValues(id) {
   //url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxNoticesGetValues.php';
   url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxGetNoticeDetails.php';   
   new Ajax.Request(url,
   {      
       method:'post',
       parameters: {noticeId: id},
       onCreate: function() {
       showWaitDialog();
     },
     onSuccess: function(transport){
       hideWaitDialog();
       j = eval('('+transport.responseText+')');
       document.getElementById("subjectNotice").innerHTML = j.noticeSubject;
       document.getElementById("noticeDepartment").innerHTML = j.departmentName+' ('+j.abbr+')';        
       document.getElementById("innerNotice").innerHTML = j.noticeText;
       document.getElementById('startDateDiv').innerHTML=customParseDate(j.visibleFromDate,"-");
       document.getElementById('endDateDiv').innerHTML=customParseDate(j.visibleToDate,"-");
     },
     onFailure: function(){ alert('Something went wrong...') }
   });
}


function editEventWindow(id,dv,w,h) {
    //displayWindow(dv,w,h);
    height=screen.height/7;
    width=screen.width/3.5;
    displayFloatingDiv(dv,'', w, h, width,height);
    populateEventValues(id);   
}

function populateEventValues(id) {
     url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxEventsGetValues.php';
     new Ajax.Request(url,
     {      
         method:'post',
         parameters: {eventId: id},
          onCreate: function() {
             showWaitDialog();
         },
         onSuccess: function(transport){
            hideWaitDialog();
            j = eval('('+transport.responseText+')');
            document.getElementById("titleEvents").innerHTML = j.eventTitle;
            document.getElementById("innerShortDescription").innerHTML = j.shortDescription;  
            document.getElementById("innerEvents").innerHTML = j.longDescription;
            document.getElementById('startDateDiv1').innerHTML=customParseDate(j.startDate,"-");
            document.getElementById('endDateDiv1').innerHTML=customParseDate(j.endDate,"-");
         },
         onFailure: function(){ alert('Something went wrong...') }
       });
}


//This function Displays Div Window

function showTeacherDetails(id,dv,w,h) {
    height=screen.height/5;
    width=screen.width/4.5;
    displayFloatingDiv(dv,'', w, h, width,height);
    populateTeacherValues(id); 
}

function populateTeacherValues(id) {
     
     var url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxCommentsGetValues.php';
     new Ajax.Request(url,
       {      
         method:'post',
         parameters: {commentId: id},
          onCreate: function() {
            showWaitDialog();
         },
         onSuccess: function(transport){
           hideWaitDialog();
           j = eval('('+trim(transport.responseText)+')');
           //alert(trim(transport.responseText));
           document.getElementById("employeeNameComments").innerHTML = j.employeeName; 
           document.getElementById('startDateDiv2').innerHTML=customParseDate(j.visibleFromDate,"-");
           document.getElementById('endDateDiv2').innerHTML=customParseDate(j.visibleToDate,"-");
           document.getElementById('innerCommentsDiv').innerHTML=j.comments;
         },
         onFailure: function(){ alert('Something went wrong...') }
       });
}

function  download(str){    
//location.href = "<?php echo HTTP_LIB_PATH.'/';?>/Notice/noticeDownload.php?path="+str; 
//location.href="<?php echo IMG_HTTP_PATH.'/';?>Notice/"+str;
var address="<?php echo IMG_HTTP_PATH;?>/Notice/"+str;
window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}
//For autosuggest
function changeDefaultTextOnClick()
{
    if(document.getElementById('menuLookup').value=="Menu Lookup..")
    {
        document.getElementById('menuLookup').value="";
        document.getElementById('menuLookup').className="text_class";
    }
}
function changeDefaultTextOnBlur()
{
    if(document.getElementById('menuLookup').value=="")
    {
        document.getElementById('menuLookup').className="fadeMenuText"; 
        document.getElementById('menuLookup').value="Menu Lookup..";
    }
}
//This script throws a ajax request to populate autosuggest menu
function getMenuLookup()
{
    document.getElementById('menuLookupContainer').style.display="none";
    if(document.getElementById('menuLookup').value.length>1)
    {
        url = '<?php echo HTTP_LIB_PATH;?>/menuLookup.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {txt: document.getElementById('menuLookup').value},
             onCreate: function() {
                 
                // showWaitDialog(true);
             },
             onSuccess: function(transport){
                    // hideWaitDialog(true);
                    if((transport.responseText)!="") {
                        var display="<ul style='list-style:none'>";
                        var obj=transport.responseText.evalJSON() 
                        if(obj)
                        {
                            var objSize=10;
                            if(obj.length<10)
                            {
                                objSize=obj.length;
                            }
                            
                            for(var arrayIndex=0;arrayIndex<objSize;arrayIndex++)
                            {
                                display+="<li style='padding:3px'><a href='"+obj[arrayIndex]['link']+"'>"+obj[arrayIndex]['data']+"</a></li>";
                            }       
                        }
                        display+="</ul>";
                        document.getElementById('menuLookupContainer').style.display="";
                        document.getElementById('menuLookupContainer').style.display="block";
                        document.getElementById('menuLookupContainer').innerHTML=display;
                        return false;
                    }
             },
             onFailure: function(){ 
                 //messageBox("<?php echo TECHNICAL_PROBLEM;?>") 
             }
           });  
     }
}
// Autosuggest ends
 </script>
<?php
    require_once(TEMPLATES_PATH . "/header.php");
    //require_once(TEMPLATES_PATH . "/Parent/indexDashboardContents.php");    
    require_once(TEMPLATES_PATH . "/Student/indexDashboardStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
<script language="javascript">
window.onload=function(){enableTooltips()};
//document.getElementById('div_Alerts').style.width="280px";
</script>
</body>
</html>
