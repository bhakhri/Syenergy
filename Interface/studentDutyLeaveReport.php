<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','studentDutyLeaveReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();


function createDutyLeaveStatusString(){
    global $globalDutyLeaveStatusArray;
    $returnString='<option value="-1">Select</option>';
    foreach($globalDutyLeaveStatusArray AS $key=>$value){
        $returnString .='<option value="'.$key.'" >'.$value.'</option>';
    }
    return '<select name="commonDutyLeaveStatus" id="commonDutyLeaveStatus" class="inputbox" style="width:100px;" onchange="changeDutyLeaveStatus(this.value);">'.$returnString.'</select>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Duty Leave Report </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/DutyLeave/ajaxStudentDutyLeaveReport.php';
searchFormName = 'dutyLeaveReportForm'; // name of the form which will be used for search
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'eventTitle';
sortOrderBy    = 'ASC';



// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

var serverDate="<?php echo date('Y-m-d');?>";
var tableHeadArray = new Array( new Array('srNo','#','width="2%"','',false),
                                //new Array('className','Class','width="15%"','',true) ,
								new Array('eventTitle','Event','width="8%"','',true) ,
                                new Array('subjectCode','Subject','width="10%"','',true),
                                new Array('employeeName','Teacher','width="10%"','',true),
                                new Array('dutyDate','Date','width="6%"','align="center"',true),
                                new Array('periodNumber','Period','width="5%"','',true),
								new Array('rejected','Status','width="8%"','',true) );

function validateData(){
   hideResults();
   if(document.getElementById('rollNo').value==''){
     messageBox("<?php echo ENTER_ROLL_NO_REG_NO_UNI_NO;?>");
     document.getElementById('rollNo').focus();
     return false;
   }
   document.getElementById("nameRow").style.display='';
   document.getElementById("nameRow2").style.display='';
   document.getElementById("resultRow").style.display='';
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);
   //sendReq(listURL,divResultName,'listForm','');
   return false;
}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}


function getClassList() {
     
     var url = '<?php echo HTTP_LIB_PATH;?>/DutyLeave/ajaxGetStudentClassList.php';
     
     document.dutyLeaveReportForm.classId.length = null;
     addOption(document.dutyLeaveReportForm.classId, '', 'Select');
     
     var rollNo = trim(document.dutyLeaveReportForm.rollNo.value);
     if(rollNo=='') {
       return false;  
     }
     new Ajax.Request(url,
     {
         method:'post',
         parameters: { rollNo: rollNo },
         asynchronous:false,
         onCreate: function(transport){
           //showWaitDialog(true);
         },
         onSuccess: function(transport){
           //hideWaitDialog(true);
           j = eval('('+ transport.responseText+')');
           len = j.length;
           document.dutyLeaveReportForm.classId.length = null;
           if(len>0) {
             addOption(document.dutyLeaveReportForm.classId, '-1', 'All');
             for(i=0;i<len;i++) {
               addOption(document.dutyLeaveReportForm.classId, j[i]['classId'], j[i]['className']);
             }
           }
		   else {
             addOption(document.dutyLeaveReportForm.classId, '', 'Select');
		   }
         },
         onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
      });
}


/* function to output data for printable version*/
function printReport() {
    var path='<?php echo UI_HTTP_PATH;?>/studentDutyLeaveReportPrint.php?'+generateQueryString('dutyLeaveReportForm')+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DutyLeaveConflictReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    var path='<?php echo UI_HTTP_PATH;?>/studentDutyLeaveReportCSV.php?'+generateQueryString('dutyLeaveReportForm')+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.location = path;
}

</script>
</head>
<body>
	<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/DutyLeave/studentDutyLeaveReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: listTeacherAttendanceReport.php $
?>