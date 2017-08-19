<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentPendingDues');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Pending Dues Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
 // Section    teacher    day    period    room                               
var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('studentName','Student Name','width="15%"','align="left"',true), 
                               new Array('rollNo','<?php echo COLUMN_ROLL_NO ?>','width="8%"','align="left"',true), 
                               new Array('universityRollNo','<?php echo COLUMN_UNIV_ROLL_NO ?>','width="8%"','align="left"',true),
                               new Array('feeClassName','Class','width="12%"','align="left"',true),
                               new Array('studentMobileNo','Mobile','width="5%"','align="left"',false), 
                               new Array('imgSrc','Photo','width="5%" align="center"','align="center"',false), 
                               new Array('academicDues','Academic','width="5%"','align="right"',false), 
                               new Array('transportDues','Transport','width="5%"','align="right"',false), 
                               new Array('hostelDues','Hostel','width="5%"','align="right"',false), 
                               new Array('prevDues','Prev. Dues','width="5%"','align="right"',false), 
                               new Array('total','Total','width="5%"','align="right"',false));                     

recordsPerPage = 15000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/StudentPendingDues/ajaxInitList.php';
searchFormName = 'allDetailForm'; // name of the form which will be used for search
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'DESC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h){
    displayWindow(dv,w,h);
    populateValues(id);
}

function showMessageDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 400, 200)
    populateMessageValues(id);
}

function validateAddForm(frm) {

    if(document.getElementById('feeClassId').value==''){
       messageBox("<?php echo "Select Fee Class";?>");
       document.getElementById('feeClassId').focus();
       return false;
    }
    
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //sendReq(listURL,divResultName,'listForm','');
    return false;
}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}


function printReport() {
	var params = generateQueryString('allDetailForm');
    var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/studentPendingDuesReportPrint.php?'+qstr;
    window.open(path,"StudentPendingDuesReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");    
}

/* function to print all payment history to csv*/
function printReportCSV() {
    var params = generateQueryString('allDetailForm');
    var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;   
    path='<?php echo UI_HTTP_PATH;?>/studentPendingDuesReportCSV.php?'+qstr;
    window.location = path; 
}

 
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentPendingDues/listStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
