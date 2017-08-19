<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PollReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Teacher's Poll Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
 // Section    teacher    day    period    room                               
var tableHeadArray = new Array(
                         new Array('srNo','#','width="2%"','',false),
                         new Array('employeeNameCode','Teacher','width="20%"','align="left"',true),
                         new Array('q1','Adorable<br>Teacher','width="12%"','align="center"',true),
                         new Array('q2','Dedicated<br>Teacher','width="12%"','align="center"',true), 
                         new Array('q3','Interactive<br>Teacher','width="12%"','align="center"',true),
                         new Array('q4','Ever-smiling<br>Teacher','width="12%"','align="center"',true),
                         new Array('q5','Charismatic Teacher<br>(based on personality)','width="15%"','align="center"',true),
                         new Array('total','Total','width="12%"','align="center"',true)
                     );

recordsPerPage = 5000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxPollReport.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
addFormName    = 'AddSubject';   
editFormName   = 'EditSubject';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteSubject';
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'total';
sortOrderBy    = 'DESC';

queryString = '';
// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


/* function to print Subject report*/
function printReport() {
    
    var qstr="&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/pollReportPrint.php?'+qstr;
    window.open(path,"PollReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    
    var qstr="&sortOrderBy="+sortOrderBy+"&sortField="+sortField;     
    path='<?php echo UI_HTTP_PATH;?>/pollReportCSV.php?'+qstr;
    window.location = path;  
}


</script>
</head>
<body>
<?php   
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/PollReport/pollReportContent.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>     
<SCRIPT LANGUAGE="JavaScript">
    <!--
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
</SCRIPT>  
?>
</body>
</html>
