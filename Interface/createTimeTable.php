<?php
//-------------------------------------------------------
// Purpose: To generate time table functionality
// Author : Rajeev Aggarwal
// Created on : (30.07.2008 )
// Completed By: Pushpender Kumar
// Completion date: 20.9.2008
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/TimeTable/initList.php");
define('MODULE','CreateTimeTable');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Create Time Table</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

function validatetTimetableForm() {
 
	var fieldsArray = new Array(new Array("periodSlotId","Please select period slot"),
                                new Array("timeTableLabelId","Please select time table"),
                                new Array("teacher","Please select teacher"),
                                new Array("studentClass","Please select class"),
                                new Array("subject","Please select subject"),
                                new Array("studentGroup","Please select group") );

    var len = fieldsArray.length;
	var frm = document.timeTableForm;

    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
	 addTimeTable();
	 return false;
}

function showTimeTable(){

   url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxGetValues.php';
   
   if(document.timeTableForm.studentClass.value!='' && document.timeTableForm.subject.value!='' && document.timeTableForm.teacher.value!='' && document.timeTableForm.studentGroup.value!='' && document.timeTableForm.timeTableLabelId.value!='' ) {
   new Ajax.Request(url,
   {
	 method:'post',
	 parameters: $('timeTableForm').serialize(true),
	 onCreate:function(transport){ showWaitDialog(true);},
	 onSuccess: function(transport){
			hideWaitDialog(true);
            //alert(transport.responseText);
			j = trim(transport.responseText).evalJSON();   
			len = j.timeTableArr.length;

			lenk = j.jsonPeriodArr.length;
			if(len>0){
				for(i=0;i<lenk;i++) { 
					for(dayweek=1;dayweek<8;dayweek++)
					{
						abc= "roomPeriod" + j.jsonPeriodArr[i].periodId+dayweek;
                        tIdabc= "timeTableId" + j.jsonPeriodArr[i].periodId+dayweek;
						eval("document.getElementById('"+abc+"').selectedIndex=0");
                        eval("document.getElementById('"+abc+"').style.borderColor='';");
                        eval("document.getElementById('"+tIdabc+"').value='';");
					}
					
				}

                for(i=0;i<len;i++) {
                        
                        for(h=0;h<lenk;h++) {
                         
                             if(j.timeTableArr[i].periodId == j.jsonPeriodArr[h].periodId ) {
                                 //alert(j.timeTableArr[i].periodId +'==='+j.jsonPeriodArr[h].periodId);
                                    abc= "roomPeriod" + j.timeTableArr[i].periodId+j.timeTableArr[i].daysOfWeek;
                                    tIdabc= "timeTableId" + j.timeTableArr[i].periodId+j.timeTableArr[i].daysOfWeek;
                                    eval("document.timeTableForm."+abc+".value=j.timeTableArr[i].roomId");
                                    eval("document.timeTableForm."+abc+".style.borderColor='#B90000';");
                                    eval("document.timeTableForm."+tIdabc+".value=j.timeTableArr[i].timeTableId");                                      }
                        }
                }
			}
			else{

				for(i=0;i<lenk;i++) { 
					for(dayweek=1;dayweek<8;dayweek++)
					{
						abc= "roomPeriod" + j.jsonPeriodArr[i].periodId+dayweek;
                        tIdabc= "timeTableId" + j.jsonPeriodArr[i].periodId+dayweek;
						eval("document.getElementById('"+abc+"').selectedIndex=0");
                        eval("document.getElementById('"+abc+"').style.borderColor='';");
                        eval("document.getElementById('"+tIdabc+"').value='';");
					}
					
				}

			}
	 },
	 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   }); 
   } 
}

function addTimeTable() {
   url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxInitAdd.php';
  
   new Ajax.Request(url,
   {
	 method:'post',
	 parameters: $('timeTableForm').serialize(true),
     onCreate: function () {
         showWaitDialog(true);
     },
	 onSuccess: function(transport){
			
			hideWaitDialog(true);
			 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {  
				 
				 flag = true;
				 if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
					 document.getElementById('timeTableForm').reset(); 
				 }
				 else {
					  document.getElementById('timeTableForm').reset();
                     // location.reload();
					 //return false;
				 }
			 } 
			 else {
				messageBox(trim(transport.responseText)); 
				 
				//document.getElementById('addForm').reset(); 
			 }
	   },
	 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}
function doAll()
{
   formx = document.listForm;
   if(formx.checkbox2.checked){

	  for(var i=1;i<formx.length;i++){

	   	if(formx.elements[i].type=="checkbox"){

				formx.elements[i].checked=true;
		}
	  }
   }
   else
   {
	  for(var i=1;i<formx.length;i++){

		if(formx.elements[i].type=="checkbox"){

			formx.elements[i].checked=false;
		}
	  }
   }
}
 
function autoPopulate(val,type,frm) {
   url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';
   document.timeTableForm.studentGroup.options.length=0;
   var objOption = new Option("SELECT","");
   document.timeTableForm.studentGroup.options.add(objOption); 
   if(frm=="Add"){
       if(type=="subject"){
            document.timeTableForm.subject.options.length=0;
            var objOption = new Option("SELECT","");
            document.timeTableForm.subject.options.add(objOption); 
       }
   }
   type = "subjectTimeTable";
   new Ajax.Request(url,
   {
	     method:'post',
	     parameters: {type: type,id: val},
         onCreate: function () {
             showWaitDialog(true);
         },
	     onSuccess: function(transport){

			    hideWaitDialog(true);
                //alert(transport.responseText);
			    j = trim(transport.responseText).evalJSON();   
			    len = j.subjectArr.length;
			    document.timeTableForm.subject.length = null;
			    // add option Select initially
			    addOption(document.timeTableForm.subject, '', 'Select');
			    for(i=0;i<len;i++) { 
			     addOption(document.timeTableForm.subject, j.subjectArr[i].subjectId, j.subjectArr[i].subjectCode);
			    }
			    /*  len = j.groupArr.length;
			        document.timeTableForm.studentGroup.length = null;
			        // add option Select initially
			        addOption(document.timeTableForm.studentGroup, '', 'Select');
			        for(i=0;i<len;i++) { 
			          addOption(document.timeTableForm.studentGroup, j.groupArr[i].groupId, j.groupArr[i].groupShort);
                    }
		        */
	     },
	     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       }); 
}

function getGroups() {
    url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';
    if(document.timeTableForm.subject.value=="") {
       return false;
    }
    type = "groupTimeTable";
    new Ajax.Request(url,
    {
         method:'post',
         parameters: { type: type,
                       id: document.timeTableForm.studentClass.value,
                       subject: document.timeTableForm.subject.value
                     },
         onCreate: function () {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                hideWaitDialog(true);
                j = trim(transport.responseText).evalJSON();   
                len = j.groupArr.length;
                document.timeTableForm.studentGroup.length = null;
                // add option Select initially
                addOption(document.timeTableForm.studentGroup, '', 'Select');
                for(i=0;i<len;i++) { 
                 addOption(document.timeTableForm.studentGroup, j.groupArr[i].groupId, j.groupArr[i].groupShort);
              }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       }); 
}


function roomSelected(id) {
    if(isEmpty(eval('document.getElementById("roomPeriod'+id+'").value'))) {
        eval('document.getElementById("tempValue").value=document.getElementById("timeTableId'+id+'").value;');
        eval('document.getElementById("timeTableId'+id+'").value ="";');
    }
    else {
        if(isEmpty(eval('document.getElementById("timeTableId'+id+'").value'))) {
            eval('document.getElementById("timeTableId'+id+'").value = document.getElementById("tempValue").value;');
        }
    }
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TimeTable/timetableContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: createTimeTable.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 7/15/09    Time: 3:08p
//Updated in $/LeapCC/Interface
//role permission added
//
//*****************  Version 7  *****************
//User: Parveen      Date: 4/07/09    Time: 12:47p
//Updated in $/LeapCC/Interface
//getTimeTableClassGroups function added 
//
//*****************  Version 6  *****************
//User: Pushpender   Date: 3/06/09    Time: 7:36p
//Updated in $/LeapCC/Interface
//Made changes to allow different period slot
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 3/06/09    Time: 12:22p
//Updated in $/LeapCC/Interface
//called different function for subject and group population
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 12/17/08   Time: 6:57p
//Updated in $/LeapCC/Interface
//added validation for Period Slot
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 12/17/08   Time: 4:52p
//Updated in $/LeapCC/Interface
//just corrected indentation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 13  *****************
//User: Pushpender   Date: 10/07/08   Time: 5:43p
//Updated in $/Leap/Source/Interface
//Added the functionality for Time Table Labels
//
//*****************  Version 12  *****************
//User: Pushpender   Date: 9/22/08    Time: 5:46p
//Updated in $/Leap/Source/Interface
//added js code to highlight room select box n optimized little bit
//
//*****************  Version 10  *****************
//User: Pushpender   Date: 9/20/08    Time: 6:50p
//Updated in $/Leap/Source/Interface
//added action while admin clicks on Cancel after saving time table
//entries
//
//*****************  Version 9  *****************
//User: Pushpender   Date: 9/20/08    Time: 3:55p
//Updated in $/Leap/Source/Interface
//optimized the code
//
//*****************  Version 8  *****************
//User: Pushpender   Date: 9/20/08    Time: 11:42a
//Updated in $/Leap/Source/Interface
//modifed roomSelected function
//
//*****************  Version 7  *****************
//User: Pushpender   Date: 9/19/08    Time: 8:25p
//Updated in $/Leap/Source/Interface
//added roomSelected function
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 9/10/08    Time: 7:05p
//Updated in $/Leap/Source/Interface
//updated reload of form on cancel
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/26/08    Time: 2:23p
//Updated in $/Leap/Source/Interface        
//changed timetable folder name
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/25/08    Time: 4:07p
//Updated in $/Leap/Source/Interface
//updated refresh screen for room
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 8/22/08    Time: 12:31p
//Updated in $/Leap/Source/Interface
//added print report 
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/13/08    Time: 2:40p
//Updated in $/Leap/Source/Interface
//updated time table validations and error messages
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/01/08    Time: 4:22p
//Created in $/Leap/Source/Interface
//intial checkin
 
?>