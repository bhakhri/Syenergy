<?php
//---------------------------------------------------------------------------
// THIS FILE is used for assigning survey to emps/student/parents
// Author : Dipanjan Bhattacharjee
// Created on : (13.01.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
die('This report is closed now.Please go to Feedback Label Wise Survey Report (Advanced)');
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_AssignSurveyMasterReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

$blank_string='None';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Assign Survey Report (Advanced)</title>
<style type="text/css">
a.whiteClass:hover{
    color:#FFFFFF;
}
</style>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//echo UtilityManager::includeCSS2(); 
//echo UtilityManager::javaScriptFile2();
?> 
<script language="javascript">
recordsPerPage = <?php echo RECORDS_PER_PAGE ;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


//-------------------------------------------------------
//THIS FUNCTION IS USED TO hide "showList" div
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function hide_div(id,mode){
    if(mode==2){
     document.getElementById(id).style.display='none';
    }
    else{
        document.getElementById(id).style.display='';
    }
}

//This function Validates Form 
var myQueryString;
var allStudentId;

function validateCommonStudentList() {
   
    myQueryString = '';
   
    if(!isEmpty(document.allDetailsForm.birthYearF.value) || !isEmpty(document.allDetailsForm.birthMonthF.value) || !isEmpty(document.allDetailsForm.birthDateF.value)){
        if(isEmpty(document.allDetailsForm.birthYearF.value)){
           messageBox("<?php echo SELECT_BIRTH_DAY_YEAR; ?>");
           document.allDetailsForm.birthYearF.focus();
           return false;
        }
        if(isEmpty(document.allDetailsForm.birthMonthF.value)){
           messageBox("<?php echo SELECT_BIRTH_DAY_MONTH; ?>");
           document.allDetailsForm.birthMonthF.focus();
           return false;
        }
        if(isEmpty(document.allDetailsForm.birthDateF.value)){
           messageBox("<?php echo SELECT_BIRTH_DAY_DATE; ?>");
           document.allDetailsForm.birthDateF.focus();
           return false;
        }
    }

    if(!isEmpty(document.allDetailsForm.birthYearT.value) || !isEmpty(document.allDetailsForm.birthMonthT.value) || !isEmpty(document.allDetailsForm.birthDateT.value)){
        if(isEmpty(document.allDetailsForm.birthYearT.value)){
           messageBox("<?php echo SELECT_BIRTH_DAY_YEAR; ?>");
           document.allDetailsForm.birthYearT.focus();
           return false;
        }
        if(isEmpty(document.allDetailsForm.birthMonthT.value)){
           messageBox("<?php echo SELECT_BIRTH_DAY_MONTH; ?>");  
           document.allDetailsForm.birthMonthT.focus();
           return false;
        }
        if(isEmpty(document.allDetailsForm.birthDateT.value)){
           messageBox("<?php echo SELECT_BIRTH_DAY_DATE; ?>");  
           document.allDetailsForm.birthDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.allDetailsForm.birthYearF.value) && !isEmpty(document.allDetailsForm.birthMonthF.value) && !isEmpty(document.allDetailsForm.birthDateF.value) && !isEmpty(document.allDetailsForm.birthYearT.value) && !isEmpty(document.allDetailsForm.birthMonthT.value) && !isEmpty(document.allDetailsForm.birthDateT.value)){
        dobFValue = document.allDetailsForm.birthYearF.value+"-"+document.allDetailsForm.birthMonthF.value+"-"+document.allDetailsForm.birthDateF.value
        dobTValue = document.allDetailsForm.birthYearT.value+"-"+document.allDetailsForm.birthMonthT.value+"-"+document.allDetailsForm.birthDateT.value
        if(dateCompare(dobFValue,dobTValue)==1){
           messageBox("<?php echo RESTRICTION_BIRTH_DAY; ?>");  
           document.allDetailsForm.birthYearF.focus();
           return false;
        }
    }

    /* admission date*/
    if(!isEmpty(document.allDetailsForm.admissionYearF.value) || !isEmpty(document.allDetailsForm.admissionMonthF.value) || !isEmpty(document.allDetailsForm.admissionDateF.value)){
        if(isEmpty(document.allDetailsForm.admissionYearF.value)){
           messageBox("<?php echo SELECT_ADMISSION_DAY_YEAR ?>");  
           document.allDetailsForm.admissionYearF.focus();
           return false;
        }
        if(isEmpty(document.allDetailsForm.admissionMonthF.value)){
           messageBox("<?php echo SELECT_ADMISSION_DAY_MONTH ?>");  
           document.allDetailsForm.admissionMonthF.focus();
           return false;
        }
        if(isEmpty(document.allDetailsForm.admissionDateF.value)){
           messageBox("<?php echo SELECT_ADMISSION_DAY_DATE ?>");  
           document.allDetailsForm.admissionDateF.focus();
           return false;
        }
    }

    if(!isEmpty(document.allDetailsForm.admissionYearT.value) || !isEmpty(document.allDetailsForm.admissionMonthT.value) || !isEmpty(document.allDetailsForm.admissionDateT.value)){
        if(isEmpty(document.allDetailsForm.admissionYearT.value)){
           messageBox("<?php echo SELECT_ADMISSION_DAY_YEAR ?>");
           document.allDetailsForm.admissionYearT.focus();
           return false;
        }
        if(isEmpty(document.allDetailsForm.admissionMonthT.value)){
           messageBox("<?php echo SELECT_ADMISSION_DAY_MONTH ?>");   
           document.allDetailsForm.admissionMonthT.focus();
           return false;
        }
        if(isEmpty(document.allDetailsForm.admissionDateT.value)){
           messageBox("<?php echo SELECT_ADMISSION_DAY_DATE ?>");    
           document.allDetailsForm.admissionDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.allDetailsForm.admissionYearF.value) && !isEmpty(document.allDetailsForm.admissionMonthF.value) && !isEmpty(document.allDetailsForm.admissionDateF.value) && !isEmpty(document.allDetailsForm.admissionYearT.value) && !isEmpty(document.allDetailsForm.admissionMonthT.value) && !isEmpty(document.allDetailsForm.admissionDateT.value)){
        doaFValue = document.allDetailsForm.admissionYearF.value+"-"+document.allDetailsForm.admissionMonthF.value+"-"+document.allDetailsForm.admissionDateF.value
        doaTValue = document.allDetailsForm.admissionYearT.value+"-"+document.allDetailsForm.admissionMonthT.value+"-"+document.allDetailsForm.admissionDateT.value
        if(dateCompare(doaFValue,doaTValue)==1){
           messageBox("<?php echo RESTRICTION_ADMISSION_DAY; ?>");      
           document.allDetailsForm.admissionYearF.focus();
           return false;
        }
    }
    showHide("hideAll");
    //show student list
    getStudentList(myQueryString);
    
}


function getStudentList(queryString){
  document.getElementById('printRowId').style.display='none';  
  studentListUrl = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxStudentFeedbackStatus.php'; 
  var tableColumns = new Array(
                         new Array('srNo','#','width="1%"','',false),
                         new Array('studentName','Name','width="12%"',true),
                         new Array('rollNo','Roll No.','width="5%"',true) ,
                         new Array('className','Class','width="12%"',true) ,
                         new Array('status','Completed','width="5%"',false) ,
                         new Array('isBlocked','Blocked','width="10%"',false) 
                      );
                      
  var frmName='allDetailsForm';

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(studentListUrl,recordsPerPage,linksPerPage,page,frmName,'studentName','ASC','studentResultsDiv','','',true,'listObj',tableColumns,'','');
 sendRequest(studentListUrl, listObj, queryString,false);
 document.getElementById('printRowId').style.display='';
}

function makeStudentUnblock(){
    if(trim(document.studentUnBlockForm.unblockStatus.value)==''){
        messageBox("Enter reason for unblocking");
        document.studentUnBlockForm.unblockStatus.focus();
        return false;
    }
     var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/doStudentUblock.php';
     new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 id      :  document.studentUnBlockForm.uId.value, 
                 reason  :  trim(document.studentUnBlockForm.unblockStatus.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        hiddenFloatingDiv('studentUnBlockDiv'); 
                        messageBox("This student is unblocked");
                        sendRequest(studentListUrl, listObj, 'page='+listObj.page+'&sortOrderBy='+listObj.sortOrderBy+'&sortField='+listObj.sortField,false);
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.studentUnBlockForm.unblockStatus.focus(); 
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}
                                          

function populateStudentValue(id) {
  document.studentUnBlockForm.reset();
  document.studentUnBlockForm.uId.value='';
  var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetStudentValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {id: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        //hiddenFloatingDiv('studentUnBlockDiv');
                        messageBox("<?php echo STUDENT_NOT_EXIST; ?>");
                        sendRequest(studentListUrl, listObj, 'page='+listObj.page+'&sortOrderBy='+listObj.sortOrderBy+'&sortField='+listObj.sortField,false);
                        //return false;
                   }
                   var j = eval('('+trim(transport.responseText)+')');
                   document.studentUnBlockForm.uId.value = j.userId;
                   document.getElementById('divHeaderId').innerHTML='&nbsp;Unblock '+trim(j.studentName)
                   displayWindow('studentUnBlockDiv',320,250);
                   document.studentUnBlockForm.unblockStatus.value="";
                   document.studentUnBlockForm.unblockStatus.focus();

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function hideDetails() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}


//----------------------------------------------------------------------------------------------------------------
//Pupose:To reset form after data submission
//Author: Dipanjan Bhattacharjee
//Date : 5.08.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function resetForm1(){
 hide_div('studentDisplayRowId',2);
 hide_div('studentShowList',2);
}

/* function to print report*/
function printReport() {
    var qstr=generateQueryString('allDetailsForm');
    qstr=qstr+"&sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField;
    path='<?php echo UI_HTTP_PATH;?>/assignSurveyReportPrint.php?'+qstr;
    window.open(path,"AssignSurveyReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr=generateQueryString('allDetailsForm');
    qstr=qstr+"&sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField;
    window.location='assignSurveyReportCSV.php?'+qstr;
}


window.onload=function(){
   //makeDDHide('selectedSubjectId','studentSubjectD2','studentSubjectD3');
}

</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/FeedbackAdvanced/listAssignSurveyReportsContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php                              
// $History: listAssignSurveyReport.php $ 
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/02/10   Time: 10:12
//Updated in $/LeapCC/Interface
//Removed sorting on "Completed" column
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/02/10    Time: 18:05
//Created in $/LeapCC/Interface
//Created the repoort for showing student status for feedbacks
?>