<?php
//-------------------------------------------------------
// Purpose: To Delete Attendance
// Author : Dipanjan Bhattacharjee
// Created on : (15.04.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DeleteAttendance');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Delete Attendance</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


//to stop special formatting
specialFormatting=0;

recordsPerPage = <?php echo RECORDS_PER_PAGE_TEACHER;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

function validatetTimetableForm() {
 
	var fieldsArray = new Array(
                                new Array("timeTableLabelId","<?php echo SELECT_TIMETABLE; ?>"),
                                new Array("studentClass","<?php echo SELECT_CLASS; ?>"),
                                new Array("subject","<?php echo SELECT_SUBJECT; ?>")
                               );

    var len = fieldsArray.length;
	var frm = document.timeTableForm;

    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
	 getAttendanceData();
	 return false;
}

//to validate user input before deleting attendance data
function validatetInputData(){
    if(!(checkAttendance())){  //checkes whether any attendance checkboexes checked or not
     messageBox("<?php echo SELECT_ATTENDANCE_CHECKBOX; ?>");    
     document.getElementById('attList').focus();
     return false;
   }
   
   if(false===confirm("<?php echo DELETE_CONFIRM2;?>")) {
             return false;
   }
   else{
       deleteAttendance();
       return false;
   }
}

//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether a control is object or not
//--------------------------------------------------------------------------
function chkObject(id){
  obj = document.timeTableForm.elements[id];
  if(obj.length > 0) {
      return true;
  }
  else{
    return false;;    
  }
}

//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all attendance checkboxes
//--------------------------------------------------------------------------
function  selectAttendance(){
    //state:checked/not checked
    var state=document.getElementById('attList').checked;
    if(!chkObject('attendance')){
     document.timeTableForm.attendance.checked =state;
     return true;  
    }
    formx = document.timeTableForm; 
    var l=formx.attendance.length;
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        formx.attendance[ i ].checked=state;
    }
}

//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any attendance checkboxes selected or not
//-----------------------------------------------------------------------------------------
function checkAttendance(){
    var fl=0; 
    if(!chkObject('attendance')){
     if(document.timeTableForm.attendance.checked==true){
         fl=1;
     }
     return fl;
   }
    formx = document.timeTableForm; 
    var l=formx.attendance.length;
    
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        if(formx.attendance[ i ].checked==true){
            fl=1;
            break;
        }
    }
    return (fl);
}


//To delete attendance
function deleteAttendance(){
    
    var str='';
    formx = document.timeTableForm;
    var l=formx.attendance.length;
    
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        if(formx.attendance[ i ].checked==true){
          if(str!=''){
              str +=',';
          }  
          str +=formx.attendance[ i ].value;
        }
    } 
    
    var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxDeleteAttendance.php';
    new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 strId: str
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var ret = trim(transport.responseText); 
                    if("<?php echo SUCCESS?>"==ret){
                        messageBox("<?php echo ATTENDANCE_DATA_DELETED; ?>");
                    }
                    else{
                        messageBox(ret);
                    }
                    hideData(4);
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}


//to get the attendance list
function getAttendanceData(){
  var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxDeleteAttendanceList.php';
  
  var tableHeadArray = new Array(
                                new Array('srNo','#','width="1%"','',false),
                                new Array('deleteAtt','<input type=\"checkbox\" id=\"attList\" name=\"attList\" onclick=\"selectAttendance();\">','width="1%" align=\"left\"',false), 
                                //new Array('deleteAtt','','width="1%" align=\"left\"',false), 
                                new Array('employeeName','Teacher','width="10%" align="left"',true) , 
                                new Array('groupShort','Group','width="7%" align="left"',true),
                                new Array('periodNumber','Period','width="5%" align="left"',true),
                                new Array('fromDate','From','width="5%" align="center"',true) , 
                                new Array('toDate','To','width="5%" align="center"',true), 
                                new Array('attendanceType','Attendance Type','width="6%" align="left"',true)
                             );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','employeeName','ASC','results','','',true,'listObj',tableHeadArray,'','','&timeTableLabelId='+document.getElementById('timeTableLabelId').value+'&classId='+document.getElementById('studentClass').value+'&subjectId='+document.getElementById('subject').value);
 sendRequest(url, listObj, ' ',false);
 if(listObj.totalRecords>0){
  document.getElementById('buttonRow').style.display='';
 }
}


//To populate class dropdown based upon selection of timetable labels
function autoPopulateClass(value){
    //to hide previous data
    hideData(1);
    
    if(value==''){
        return false;
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetClasses.php';
    new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId: value
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                     j = eval('('+transport.responseText+')'); 

                     for(var c=0;c<j.length;c++){
                         addOption(document.getElementById('studentClass'),j[c].classId,j[c].className);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

//To populate subject+group dropdowns based upon selection of class
function autoPopulateSubjectGroups(value){
    
    //to hide previous data
    hideData(2);
    
    if(value==''){
        return false;
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetSubjectsGroups.php';

       
    new Ajax.Request(url,
    {
         method:'post',
         parameters: {
                       timeTableLabelId: document.getElementById('timeTableLabelId').value,
                       classId: value
         },
         onCreate: function () {
             showWaitDialog(true);
         },
         onSuccess: function(transport){

                hideWaitDialog(true);
         
                j = trim(transport.responseText).evalJSON();   
                len = j.subjectArr.length;
                 for(i=0;i<len;i++) { 
                  addOption(document.timeTableForm.subject, j.subjectArr[i].subjectId, j.subjectArr[i].subjectCode);
                }
                /*
                len = j.groupArr.length;
                 
                for(i=0;i<len;i++) { 
                 addOption(document.timeTableForm.studentGroup, j.groupArr[i].groupId, j.groupArr[i].groupShort);
                }
               */ 
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

//To hide data when there is change in timetable,class,subject or group dropdowns
function hideData(mode){
     if(mode==1){
        document.getElementById('studentClass').options.length=0;
        document.getElementById('subject').options.length=0;
        //document.getElementById('studentGroup').options.length=0;
        
        addOption(document.getElementById('studentClass'),'','Select');
        addOption(document.getElementById('subject'),'','Select');
        //addOption(document.getElementById('studentGroup'),'','Select');
        
        return false;
    }
    else if(mode==2){
       document.getElementById('subject').options.length=0;
       //document.getElementById('studentGroup').options.length=0; 
       
       addOption(document.getElementById('subject'),'','Select');
       //addOption(document.getElementById('studentGroup'),'','Select');
       
       return false;
    }
   document.getElementById('results').innerHTML='';
   document.getElementById('buttonRow').style.display='none';
   return false; 

}

window.onload=function(){
    document.timeTableForm.reset();
    document.getElementById('timeTableLabelId').selectedIndex=0;
    document.getElementById('timeTableLabelId').focus();
}

 

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/AdminTasks/deleteAttendanceContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: deleteAttendance.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 29/10/09   Time: 14:34
//Updated in $/LeapCC/Interface
//corrected problem related to paging
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Interface
//Added Role Permission Variables
//
//*****************  Version 2  *****************
//User: Administrator Date: 5/06/09    Time: 15:12
//Updated in $/LeapCC/Interface
//Corrected attendance deletion module's logic
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 14/04/09   Time: 17:21
//Created in $/LeapCC/Interface
//Created Attendance Delete Module
?>