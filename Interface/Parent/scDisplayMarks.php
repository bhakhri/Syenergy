<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax functions used in "DisplayMarks" Form
//
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifParentNotLoggedIn();
//require_once(BL_PATH . "/ScParent/scInitDisplayMarks.php");
require_once(BL_PATH . "/ScParent/scInitData.php"); 
?>  

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Marks </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//require_once(CSS_PATH .'/tab-view.css'); 
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("ajax.js");  
echo UtilityManager::includeJS("tab-view.js");  

//pareses input and returns 0 if the input is blank
//Author: Arvind Singh Rawat
//Date:14.7.2008
function parseInput($input) {
    return ( (trim($input)!="" ? $input : 0 ) );
}

//pareses input and returns "-" if the input is blank
//Author: Arvind Singh Rawat
//Date:14.7.2008
function parseOutput($data){
     
     return ( (trim($data)!="" ? $data : NOT_APPLICABLE_STRING ) );  
    
}
function createBlankTD($i,$str='<td  valign="middle" align="center" class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
?> 

<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>

<script language="javascript">
recordsPerPage = '<?php echo RECORDS_PER_PAGE;?>';
linksPerPage = '<?php echo LINKS_PER_PAGE;?>';
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxDisplayMarks.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = '';   
editFormName   = '';
winLayerWidth  = 400; //  add/edit form width
winLayerHeight = 400; // add/edit form height
deleteFunction = '';
divResultName  = 'results';
page=1; //default page
sortField = 'subject';
sortOrderBy = 'ASC';

//this function is uded to refresh tab data based uplon selection of study periods
function refreshStudentData(studentId,classId){
    //get the data of attendance based upon selected study period
     var gradeData=refreshGradeData(studentId,classId);    
}                                                          

//this function fetches records corresponding to student grades/marks
function refreshGradeData(studentId,classId){
 url = '<?php echo HTTP_LIB_PATH;?>/ScParent/scAjaxStudentMarks.php';
  var tableColumns1 = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('subject','Subject','width="22%" align="left"',true),
                        new Array('testTypeName','Type','width="12%" align="left"',true), 
                        new Array('testDate','Date','width="8%" align="center"',true),
                        new Array('employeeName','Teacher','width="12%" align="left"',true),
                        new Array('periodName','Study Period','width="12%" align="left"',true),
                        new Array('testName','Test Name','width="12%" align="left"',true),
                        new Array('totalMarks','Max.Marks','width="10%" align="right" ',true),
                        new Array('obtained','Scored','width="10%" align="right"',true)
                       );
 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj2 = new initPage(url,recordsPerPage,linksPerPage,1,'','periodName','ASC','gradeResultDiv','','',true,'listObj2',tableColumns1,'','','&studentId='+studentId+'&rClassId='+classId);
 sendRequest(url, listObj2, '',true )
}

window.onload=function(){
   document.getElementById('studyPeriod').value = 0;
   refreshStudentData(<?php echo $studentDataArr[0]['studentId']; ?>,0);  
}

function printReport() {
    form = document.addForm;
    path='<?php echo UI_HTTP_PATH;?>/Parent/scDisplayMarksReport.php?rClassId='+document.getElementById('studyPeriod').value+'&sortOrderBy='+listObj2.sortOrderBy+'&sortField='+listObj2.sortField;
    window.open(path,"StudentMarksReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {   
      form=document.addForm;
      path='<?php echo UI_HTTP_PATH;?>/Parent/scDisplayMarksReportCSV.php?rClassId='+document.getElementById('studyPeriod').value+'&sortOrderBy='+listObj2.sortOrderBy+'&sortField='+listObj2.sortField;
      //window.open(path,"StudentMarksReportCSV","status=1,menubar=1,scrollbars=1");
      window.location = path;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/ScParent/scDisplayMarksContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php
function trim_output($str,$maxlength='250',$rep='...'){
   $ret=chunk_split($str,60);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep; 
   }
  return $ret;  
} 

////$History: scDisplayMarks.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/18/09    Time: 4:44p
//Created in $/LeapCC/Interface/Parent
//file added
//
//*****************  Version 9  *****************
//User: Parveen      Date: 8/18/09    Time: 1:23p
//Updated in $/Leap/Source/Interface/Parent
//formating & alingments
//bug fix 1097, 1096, 1056, 1049, 1048,
//1043, 1008 1042, 506  
//
//*****************  Version 8  *****************
//User: Parveen      Date: 8/17/09    Time: 7:02p
//Updated in $/Leap/Source/Interface/Parent
//bug fix  (file attachement & format updated)
//1041, 1097, 1040, 1041, 1105, 1106, 1109 
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/07/09    Time: 7:21p
//Updated in $/Leap/Source/Interface/Parent
//validation, features, conditions, formatting updated (bug Nos.
//331, 323, 334, 338,339, 348, 350, 351,352, 354, 380, 381,342, 349, 427,
//428, 429,430, 431, 432, 433, 434,435, 436,437, 432, 479, 480, 481,482,
//493, 494, 495, 498,501, 502,478, 477, 934, 924, 925)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/19/08   Time: 5:23p
//Updated in $/Leap/Source/Interface/Parent
//formatting settings
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/18/08   Time: 5:25p
//Updated in $/Leap/Source/Interface/Parent
//code update
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/17/08   Time: 5:04p
//Updated in $/Leap/Source/Interface/Parent
//study period add
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/17/08   Time: 11:16a
//Updated in $/Leap/Source/Interface/Parent
//study period added
//
//*****************  Version 2  *****************
//User: Arvind       Date: 9/23/08    Time: 11:20a
//Updated in $/Leap/Source/Interface/Parent
//corrected the path of template file 
//
//*****************  Version 1  *****************
//User: Arvind       Date: 9/17/08    Time: 11:27a
//Created in $/Leap/Source/Interface/Parent
//initial checkin
//
//*****************  Version 6  *****************
//User: Arvind       Date: 8/18/08    Time: 3:52p
//Updated in $/Leap/Source/Interface/Parent
//modified
//
//*****************  Version 5  *****************
//User: Arvind       Date: 8/14/08    Time: 11:18a
//Updated in $/Leap/Source/Interface/Parent
//modified
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/17/08    Time: 3:48p
//Updated in $/Leap/Source/Interface/Parent
//ifNotLoggedIn()  has been replaced by ifParentNotLoggedIn() 
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/17/08    Time: 12:05p
//Updated in $/Leap/Source/Interface/Parent
//Added Comments
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/16/08    Time: 10:55a
//Updated in $/Leap/Source/Interface/Parent
//added comments
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/14/08    Time: 6:03p
//Created in $/Leap/Source/Interface/Parent
//added new files for teacher activity module
//

?>
