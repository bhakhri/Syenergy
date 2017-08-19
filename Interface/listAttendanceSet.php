<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF Attendance sets
// Author : Dipanjan Bhattacharjee
// Created on : (28.12.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AttendanceSetMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Attendance Set Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
new Array('srNo','#','width="2%"','align="left"',false), 
new Array('attendanceSetName','Set Name','width="60%"','align="left"',true) , 
new Array('evaluationCriteriaId','Criteria','width="30%"','align="left"',true), 
new Array('action','Action','width="3%"','align="center"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/AttendanceSet/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddAttendanceSet';   
editFormName   = 'AddAttendanceSet';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteAttendanceSet';
divResultName  = 'results';
page=1; //default page
sortField = 'attendanceSetName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button



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
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    document.getElementById('divHeaderId1').innerHTML='&nbsp;Edit Attendance Set';
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
                        new Array("setName","<?php echo ENTER_ATTENDANCE_SET_NAME;?>")
                      );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<1 && fieldsArray[i][0]=='setName' ) {
                messageBox("<?php echo ATTENDANCE_SET_NAME_LENGTH;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
        }
     
    }
    if(document.AttendanceSet.setCondition[0].checked==false && document.AttendanceSet.setCondition[1].checked==false){
       messageBox("<?php echo ATTENDANCE_SET_CONDITION_MISSING;?>");
       document.AttendanceSet.setCondition[0].focus();
    }
    
    if(trim(document.AttendanceSet.setId.value)==''){
        addAttendanceSet();
        return false;
    }
    else if(trim(document.AttendanceSet.setId.value)!=''){
        editAttendanceSet();
        return false;
    }
    else{
        messageBox("Invalid Operations");
        return false;
    }
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO ADD A NEW ATTENDANCE SET
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function addAttendanceSet() {
         var url = '<?php echo HTTP_LIB_PATH;?>/AttendanceSet/ajaxAttendanceSetOperations.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                  setName       :  trim(document.AttendanceSet.setName.value), 
                  setCondition  :  (document.AttendanceSet.setCondition[0].checked==true?1:0),
                  modeType      :  1
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
                         else {
                             hiddenFloatingDiv('AddAttendanceSet');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                     else if("<?php echo ATTENDANCE_SET_NAME_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ATTENDANCE_SET_NAME_ALREADY_EXIST ;?>"); 
                         document.AttendanceSet.setName.focus();
                     }
                     else if("<?php echo ATTENDANCE_SET_NAME_MAX_CHECK;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ATTENDANCE_SET_NAME_MAX_CHECK ;?>"); 
                         document.AttendanceSet.setName.focus();
                     }
                     else if("<?php echo ATTENDANCE_SET_NAME_LENGTH;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ATTENDANCE_SET_NAME_LENGTH ;?>"); 
                         document.AttendanceSet.setName.focus();
                     }
                     else if("<?php echo INVALID_ATTENDANCE_SET_CRITERIA;?>" == trim(transport.responseText)){
                         messageBox("<?php echo INVALID_ATTENDANCE_SET_CRITERIA ;?>"); 
                         document.AttendanceSet.setCondition[0].focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.AttendanceSet.setName.focus(); 
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO DELETE AN ATTENDANCE SET
// id=Id
// Author : Dipanjan Bhattacharjee
// Created on : (28.12.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function deleteAttendanceSet(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         var url = '<?php echo HTTP_LIB_PATH;?>/AttendanceSet/ajaxAttendanceSetOperations.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 setId    : id,
                 modeType : 3
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true); 
                    if("<?php echo DELETE;?>"==trim(transport.responseText) || "<?php echo ATTENDANCE_SET_NOT_EXIST;?>"==trim(transport.responseText)) {
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
// THIS FUNCTION IS USED TO CLEAN UP THE "popup" DIV
// Author : Dipanjan Bhattacharjee
// Created on : (28.12.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function blankValues() {
   document.getElementById('divHeaderId1').innerHTML='&nbsp;Add Attendance Set';
   document.AttendanceSet.reset();
   document.AttendanceSet.setId.value='';
   document.AttendanceSet.setCondition[0].checked=true;
   document.AttendanceSet.setName.focus();
}


//-------------------------------------------------------
// THIS FUNCTION IS USED TO EDIT AN ATTENDANCE SET
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function editAttendanceSet() {
         var url = '<?php echo HTTP_LIB_PATH;?>/AttendanceSet/ajaxAttendanceSetOperations.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                  setId         :  (document.AttendanceSet.setId.value), 
                  setName       :  trim(document.AttendanceSet.setName.value), 
                  setCondition  :  (document.AttendanceSet.setCondition[0].checked==true?1:0),
                  modeType      :  2
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('AddAttendanceSet');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else if("<?php echo ATTENDANCE_SET_NAME_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ATTENDANCE_SET_NAME_ALREADY_EXIST ;?>"); 
                         document.AttendanceSet.setName.focus();
                     }
                     else if("<?php echo ATTENDANCE_SET_NAME_MAX_CHECK;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ATTENDANCE_SET_NAME_MAX_CHECK ;?>"); 
                         document.AttendanceSet.setName.focus();
                     }
                     else if("<?php echo ATTENDANCE_SET_NAME_LENGTH;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ATTENDANCE_SET_NAME_LENGTH ;?>"); 
                         document.AttendanceSet.setName.focus();
                     }
                     else if("<?php echo INVALID_ATTENDANCE_SET_CRITERIA;?>" == trim(transport.responseText)){
                         messageBox("<?php echo INVALID_ATTENDANCE_SET_CRITERIA ;?>"); 
                         document.AttendanceSet.setCondition[0].focus();
                     }
                     else if("<?php echo EVALUATION_CRITERIA_RESTRICTION;?>" == trim(transport.responseText)){
                         messageBox("<?php echo EVALUATION_CRITERIA_RESTRICTION ;?>"); 
                         document.AttendanceSet.setCondition[0].focus();
                     } 
                     else {
                        messageBox(trim(transport.responseText));
                        document.AttendanceSet.setName.focus();
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO POPULATE "popup" DIV
// Author : Dipanjan Bhattacharjee
// Created on : (29.12.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function populateValues(id) {
         document.AttendanceSet.reset();
         document.AttendanceSet.setCondition[0].checked=false;
         document.AttendanceSet.setCondition[1].checked=false;
         url = '<?php echo HTTP_LIB_PATH;?>/AttendanceSet/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 setId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('AddAttendanceSet');
                        messageBox("<?php echo ATTENDANCE_SET_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                   var j = eval('('+trim(transport.responseText)+')');
                   
                   document.AttendanceSet.setName.value = j.attendanceSetName;
                   if(j.evaluationCriteriaId==1){
                    document.AttendanceSet.setCondition[0].checked = true;
                   }
                   else if(j.evaluationCriteriaId==2){
                       document.AttendanceSet.setCondition[1].checked=true
                   }
                   document.AttendanceSet.setId.value = j.attendanceSetId;
                   document.AttendanceSet.setName.focus();

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


window.onload=function(){
    document.searchForm.reset();
}


function printReport() {
   var qstr="searchbox="+(document.searchForm.searchbox.value);
   qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
   path='<?php echo UI_HTTP_PATH;?>/listAttendanceSetPrint.php?'+qstr;
   try{
     var a=window.open(path,"AttendanceSetPrint","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
    }
    //window.open(path,"DisplayGroupReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    var qstr="searchbox="+(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listAttendanceSetCSV.php?'+qstr;
    window.location = path;
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/AttendanceSet/listAttendanceSetContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
    <script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listAttendanceSet.php $ 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 29/12/09   Time: 13:38
//Created in $/LeapCC/Interface
//Added  "Attendance Set Module"
?>