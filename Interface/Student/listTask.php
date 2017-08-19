<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Training ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (04.03.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TaskMaster');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
//require_once(BL_PATH . "/Discipline/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Task Manager </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height

// ajax search results ---end ///

function getTask(){
  url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitTaskList.php';
  var value=document.searchForm.searchbox.value;
    
 var tableColumns = new Array(
                        new Array('srNo','#','width="3%" align="left"',false), 
                        new Array('title','Title','width="15%" align="left"',true),
						new Array('shortDesc','Short Description','width="15%" align="left"',true),
						new Array('dueDate','Date','width="10%" align="center"',true),
						new Array('daysPrior','Priority Days','width="10%" align="right"',true),
						new Array('status','Status','width="10%" align="center"',true),
						new Array('Result','Prior Date','width="10%" align="center"',true),
                        new Array('action','Action','width="4%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','title','ASC','TaskResultDiv','TaskActionDiv','',true,'listObj',tableColumns,'editWindow','deleteTask','&searchbox='+trim(value));
 sendRequest(url, listObj, '')
}
// ajax search results ---end ///

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Jaineesh
// Created on : (05.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id,dv,w,h) {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Task';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues(id);   
}
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
            
       /* else  if (!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]=='title' ) {
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
                }*/

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
  
    if(document.getElementById('taskId').value=='') {
        //alert('add slot');
		addTask();
        return false;
    }
    else{
		//alert('edit slot');
        editTask();
        return false;
    }
}

function emptySlotId() {
	document.getElementById('offenseId').value='';
}


//-------------------------------------------------------
//THIS FUNCTION addDocument() IS USED TO ADD NEW TASK
//
//Author : Jaineesh
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addTask() {
		
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitTaskAdd.php';

		 /*if (document.TaskDetail.reminder[0].checked) {
			reminder = document.TaskDetail.reminder[0].value;
		 }
		 else {
			reminder = document.TaskDetail.reminder[1].value;
		 }*/
		         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
				title:				trim(document.TaskDetail.title.value),
				shortDesc:			trim(document.TaskDetail.shortDesc.value),
				dueDate:			(document.TaskDetail.dueDate.value),
				//reminder:			reminder,
				dashboard:			(document.TaskDetail.dashboard.checked?1:0),
				sms:				(document.TaskDetail.sms.checked?2:0),
				daysPrior:			(document.TaskDetail.daysPrior.value),
				status:				(document.TaskDetail.status.value)

             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('TaskActionDiv');
                             getTask();
                             return false;
                         }
                     }
					 
					else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo TITLE_ALREADY_EXIST; ?>'){
							document.TaskDetail.title.focus();
						}
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A DOCUMENT
//  id=documentId
//Author : Jaineesh
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteTask(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitTaskDelete.php';
		
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {taskId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         getTask(); 
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

//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "TRAINING" DIV
//
//Author : Jaineesh
// Created on : (22.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
	
	document.getElementById('divHeaderId').innerHTML='&nbsp; Add Task';
	/*if (document.TaskDetail.reminder[0].checked == true) {
		document.getElementById('showDashboard').style.display='';
		document.getElementById('dashboard').checked=true;
		document.getElementById('sms').checked=false;
	}*/
	//document.getElementById('trStatus').style.display = '';
	document.TaskDetail.dueDate.value = "<?php echo date('Y-m-d') ?>";
	document.TaskDetail.taskId.value = '';
	document.TaskDetail.title.value = '';
	document.getElementById('dashboard').checked = true;
	document.getElementById('sms').checked = false;
	document.TaskDetail.shortDesc.value = '';
	document.TaskDetail.daysPrior.value=0;
	document.TaskDetail.status.value=0;
	document.TaskDetail.title.focus();
}

//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "TRAINING" DIV
//
//Author : Jaineesh
// Created on : (22.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------
function checkDashboard() {
	
	if (document.TaskDetail.reminder[1].checked == true) {
		document.getElementById('showDashboard').style.display='none';
		document.getElementById('dashboard').checked=false;
		document.getElementById('sms').checked=false;

	}
	if (document.TaskDetail.reminder[0].checked == true) {
		document.getElementById('showDashboard').style.display='';
		document.getElementById('dashboard').checked=true;
	}
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
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitTaskEdit.php';
         
		/* if (document.TaskDetail.reminder[0].checked) {
			reminder = document.TaskDetail.reminder[0].value;
		 }
		 else {
			reminder = document.TaskDetail.reminder[1].value;
		 }*/

         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
					taskId:				trim(document.TaskDetail.taskId.value),
					title:				trim(document.TaskDetail.title.value),
					shortDesc:			trim(document.TaskDetail.shortDesc.value),
					dueDate:			(document.TaskDetail.dueDate.value),
					//reminder:			reminder,
					dashboard:			(document.TaskDetail.dashboard.checked?1:0),
					sms:				(document.TaskDetail.sms.checked?2:0),
					daysPrior:			(document.TaskDetail.daysPrior.value),
					status:				(document.TaskDetail.status.value)
              },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('TaskActionDiv');
                         getTask();
						 //emptySlotId();
                         return false;
                     }
                   else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo TITLE_ALREADY_EXIST; ?>'){
							document.TaskDetail.title.value="";
							document.TaskDetail.title.focus();
						}
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EDITDOCUMENT" DIV
//
//Author : Jaineesh
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxTaskGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {taskId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('TaskActionDiv');
                        messageBox("<?php echo TASK_NOT_EXIST; ?>");
                        getTask();           
                   }

				/*   else if ("<?php echo OFFENSE_CONSTRAINT ;?>" == trim(transport.responseText)) {
					    hiddenFloatingDiv('OffenseActionDiv');
						messageBox("<?php echo OFFENSE_CONSTRAINT ;?>"); 
						getOffenseData();
				   }*/

                   j = eval('('+trim(transport.responseText)+')');
				   
                   document.TaskDetail.taskId.value				= j.taskId;
				   document.TaskDetail.title.value				= j.title;
				   document.TaskDetail.shortDesc.value			= j.shortDesc;
				   document.TaskDetail.dueDate.value			= j.dueDate;

				   if (j.reminderOptions == "1,0") {
					document.getElementById('dashboard').checked=true;
					document.getElementById('sms').checked=false;
				   }
				   if (j.reminderOptions == "0,2") {
					document.getElementById('dashboard').checked=false;
					document.getElementById('sms').checked=true;
				   }

				   if (j.reminderOptions == "1,2") {
					document.getElementById('dashboard').checked=true;
					document.getElementById('sms').checked=true;
				   }
			   
				   document.TaskDetail.daysPrior.value			= j.daysPrior;
				   document.TaskDetail.status.value = j.status;
                   document.TaskDetail.title.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

window.onload=function(){
        //loads the data
        getTask();    
}

</script>

</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/Student/listTaskContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	
</body>
</html>
<?php 
// $History: listTask.php $ 
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/21/09    Time: 7:28p
//Updated in $/LeapCC/Interface/Student
//fixed bugs during self testing
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 9/03/09    Time: 10:07a
//Updated in $/LeapCC/Interface/Student
//fixed bug nos.0001389, 0001387, 0001386, 0001380, 0001383 and export to
//excel
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/20/09    Time: 6:42p
//Created in $/LeapCC/Interface/Student
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 4/09/09    Time: 10:12a
//Updated in $/SnS/Interface/Student
//modified in design template
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 3/27/09    Time: 6:53p
//Updated in $/SnS/Interface/Student
//fixed bugs
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/24/09    Time: 4:46p
//Updated in $/SnS/Interface/Student
//modified in task
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/20/09    Time: 6:10p
//Updated in $/SnS/Interface/Student
//modified for task
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/20/09    Time: 10:59a
//Created in $/SnS/Interface/Student
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/19/09    Time: 4:41p
//Updated in $/SnS/Interface
//add new room if hostel room is different
//new task module in student
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/19/09    Time: 2:53p
//Updated in $/SnS/Interface
//check prior days should be in integer
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/18/09    Time: 6:23p
//Created in $/SnS/Interface
//new file for task
//
?>