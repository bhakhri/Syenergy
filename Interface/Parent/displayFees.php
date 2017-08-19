<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax functions used in "display fees details" Form
//
//
// Author :Arvind Singh Rawat
// Created on : 08-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ParentDisplayFeeDetails');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn();
require_once(BL_PATH . "/Parent/initData.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Fee Details </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 
/*
 var tableColumns = new Array(new Array('srNo','#','width="2%" valign="middle"',false), 
                              new Array('receiptNo','Receipt No','width="12%" valign="middle"',true),
                              new Array('receiptDate','Receipt Date','width="11%" valign="middle"',true) , 
                              new Array('totalFeePayable','Total fees (Rs)','width="12%" valign="middle" align="right"',true), 
                              new Array('discountedFeePayable','Payable (Rs)','width="11%" valign="middle" align="right"',true), 
                              new Array('totalAmountPaid','Paid (Rs)','width="10%" valign="middle" align="right"',true), 
                              new Array('paymentInstrument1','Payment Instrument','width="15%" valign="middle" align="right"',false), 
                              new Array('receiptStatus1','Receipt Status','width="13%" valign="middle" align="right"',false), 
                              new Array('instrumentStatus1','Instrument Status','width="15%" valign="middle" align="right"',false));
*/

                              
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
//listURL = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxStudentFees.php';
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentFees.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = '';   
editFormName   = '';
winLayerWidth  = 400; //  add/edit form width
winLayerHeight = 400; // add/edit form height
deleteFunction = '';
divResultName  = 'results';
page=1; //default page
sortField = 'receiptNo';
sortOrderBy    = 'ASC';


//this function fetches records corresponding to student fees detail
function refreshFeesResultData(studentId,classId){
    
  //url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxStudentFees.php';
  var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentFees.php';
 /* var tableColumns = new Array(
                            new Array('srNo','#','width="2%" valign="middle"',false), 
                            new Array('receiptNo','Receipt No.','width="11%" valign="middle"',true),
                            new Array('receiptDate','Receipt Date','width="12%" align="center" valign="middle"',true) , 
                            new Array('periodName','Study Period','width="12%" align="center" valign="middle"',true) , 
                            new Array('totalFeePayable','Total Fee(Rs.)','width="13%" valign="middle" align="right"',true), 
                            new Array('discountedFeePayable','Payable(Rs.)','width="12%" valign="middle" align="right"',true), 
                            new Array('totalAmountPaid','Paid(Rs.)','width="11%" valign="middle" align="right"',true), 
                            new Array('paymentInstrument1','Payment<br>Instrument','width="10%" valign="middle" align="left"',false), 
                            new Array('receiptStatus1','Receipt<br>Status','width="9%" valign="middle" align="left"',false), 
                            new Array('instrumentStatus1','Instrument<br>Status','width="15%" valign="middle" align="left"',false)
                       );
 */
 var tableColumns = new Array(new Array('srNos',               '#','width="2%"','',false),
                               new Array('receiptDate',         'Receipt<br>Date','width="9%"','align="center"',true),  
                               new Array('receiptNo',           'Receipt','width="10%"','align="left"',true),
                               new Array('studentName',         'Name','width="12%"','',true) , 
                               new Array('rollNo',              'Roll No.','width="10%"','',true), 
                               new Array('className',           'Fee Class','width="15%"','',true),  
                               new Array('cycleName',           'Fee Cycle','width="9%"','',true),  
                               new Array('installmentCount',    'Installment','width="11%"','',true), 
                               new Array('discountedFeePayable','Payable<br>(Rs.)','width="10%"','align="right"',false), 
                               new Array('amountPaid',          'Paid<br>(Rs.)','width="8%"','align="right"',false), 
                               new Array('previousDues',        'Outstanding<br>(Rs.)','width="13%"','align="right"',false),
                               new Array('instStatus',          'Instrument','width="12%"','align="left"',false), 
                               new Array('retStatus',           'Status','width="12%"','align="left"',false));  
 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj6 = new initPage(url,recordsPerPage,linksPerPage,1,'','receiptNo','ASC','results','','',true,'listObj6',tableColumns,'','','&studentId='+studentId+'&rClassId='+classId);
 sendRequest(url, listObj6, '')
}
/*
function printReport() {
    var qstr=qstr+"&sortOrderBy="+listObj6.sortOrderBy+"&sortField="+listObj6.sortField+'&studentId='+document.getElementById('tStudentId').value+'&rClassId='+document.getElementById('studyPeriod').value+'&studentName='+document.getElementById('tStudentName').value;
    path='<?php echo UI_HTTP_PATH;?>/Parent/feeDetailReport.php?'+qstr;
    window.open(path,"StudentsFeeDetailReportPrint","status=1,menubar=1,scrollbars=1, width=900");
}
*/

/* function to print Subject report*/
function printReport() {
    var qstr = "&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/feeReportPrint.php?'+qstr;
    hideUrlData(path,true);
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr = "&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/feeReportCSV.php?'+qstr;
    window.location = path;  
}
window.onload=function(){
   refreshFeesResultData(<?php echo $studentDataArr[0]['studentId']; ?>,<?php echo $studentDataArr[0]['classId']; ?>);  
}

</script>

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Parent/displayFeesContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
<script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
</body>
</html>


<?php 
//History: $


?>
