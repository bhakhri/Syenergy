<?php
//for testing purpose
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AdmittedStudentReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Admitted Student Report</title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
 // Section    teacher    day    period    room

var tableHeadArray = new Array(
                         new Array('srNo',              '#','width="2%"','',false),
                         new Array('studentName',       'Name','width="10%" align="left"','align="left"',true),
                         new Array('fatherName',        "Father's Name",'width="10%" align="left"','align="left"',true),
                         new Array('dateOfBirth',       'DOB','width="8%" align="center"','align="center"',true),
                         new Array('compExamRank',      'CET/AIEEE<br>Rank','width="8%" align="left"','align="left"',true),
                         new Array('compExamRollNo',    'CET/AIEEE<br>Roll No.','width="8%" align="left"','align="left"',true),
                         new Array('acd1',              '10th<br><span style="font-size:9px">(%age)</span>','width="6%" align="right"','align="right"',false),
                         new Array('acd2',              '10+2<br><span style="font-size:9px">(%age)</span>','width="6%" align="right"','align="right"',false),
                         new Array('studentGender',     "Gender",'width="9%" align="center"','align="center"',true),
                         new Array('quotaName1',         'Category','width="8%" align="left"','align="left"',true),
                         new Array('managementCategory1','Mgmt. Quota<br><span style="font-size:9px">(this will be entered at admission time)</span>','width="8%" align="center"','align="center"',false),
                         new Array('permAddress',       'Perm. Address','width="18%" align="left"','align="left"',false),
                         new Array('contactNo',         'Contact Nos.','width="12%" align="left"','align="left"',false)
                     );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/StudentReports/admittedStudentList.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'DESC';
queryString = '';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


function validateAddForm(frm) {

    if(document.allDetailsForm.classId.value=='') {
      messageBox("<?php echo SELECT_CLASS; ?>");
      document.allDetailsForm.classId.focus();
      return false;
    }

    page=1;
    queryString = generateQueryString('allDetailsForm');

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

    path='<?php echo UI_HTTP_PATH;?>/admittedStudentReportPrint.php?'+queryString+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"AmittedStudentReport","status=1,menubar=1,scrollbars=1, width=900, height=500, top=100,left=50");
}

/* function to print all payment history to csv*/
function printReportCSV() {

    path='<?php echo UI_HTTP_PATH;?>/admittedStudentReportCSV.php?'+queryString+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.location=path;
}


</script>
</head>
<body>
<?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listAdmittedStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
