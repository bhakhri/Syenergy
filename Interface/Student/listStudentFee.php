<?php 

//-------------------------------------------------------
//  This File contains the template file and data base file
//
//
// Author :Jaineesh
// Created on : 23-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentShowFeePaymentHistory');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
//require_once(BL_PATH . "/Student/initStudentFee.php");
//require_once(BL_PATH . "/Student/ajaxFeesList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Fee </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

//var tableHeadArray = new Array(new Array('srNo','#','width="4%`"','',false), new Array('receiptNo','Receipt No.','width="20%"','',false) , new Array('receiptDate','Receipt Date','width="20%"','',false), new Array('totalFeePayable','Total Fees Payable','width="10%"','',false) , new Array('totalAmountPaid','Amount Paid','width="10%"','',false),new Array('receiptStatus','Receipt Status','width="10%"','',false),new Array('paymentInstrument','Payment Instrument','width="10%"','',false),new Array('instrumentStatus','Instrument Status','width="10%"','',false) );
var tableHeadArray = new Array(new Array('srNos',               '#','width="2%"','',false),
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

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentFees.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = '';   
editFormName   = '';
//winLayerWidth  = 315; //  add/edit form width
//winLayerHeight = 250; // add/edit form height
deleteFunction = '';
divResultName  = 'results';
page=1; //default page
sortField = 'receiptNo';
sortOrderBy  = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);
}

/* function to print Subject report*/
function printReport() {
    var qstr = "&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/feeReportPrint.php?'+qstr;
    window.open(path,"SubjectReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr = "&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/feeReportCSV.php?'+qstr;
    window.location = path;  
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/studentTotalFeeContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
    <SCRIPT LANGUAGE="JavaScript">
    <!--
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
</SCRIPT> 
</body>
</html>


<?php 
//$History: listStudentFee.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:27p
//Updated in $/LeapCC/Interface/Student
//added access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Student
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:10p
//Updated in $/Leap/Source/Interface/Student
//remove the spaces


?>