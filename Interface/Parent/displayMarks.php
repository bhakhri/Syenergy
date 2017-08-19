<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax functions used in "DisplayMarks" Form
//
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ParentDisplayMarks');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn();
//require_once(BL_PATH . "/ScParent/scInitDisplayMarks.php");
require_once(BL_PATH . "/Parent/initData.php"); 
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
listURL = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxDisplayMarks.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = '';   
editFormName   = '';
winLayerWidth  = 400; //  add/edit form width
winLayerHeight = 400; // add/edit form height
deleteFunction = '';
divResultName  = 'results';
page=1; //default page
sortField = 'studyPeriod';
sortOrderBy = 'ASC';

//this function is uded to refresh tab data based uplon selection of study periods
function refreshStudentData(studentId,classId){
    //get the data of attendance based upon selected study period
     var gradeData=refreshGradeData(studentId,classId);    
}                                                          

//this function fetches records corresponding to student grades/marks
function refreshGradeData(studentId,classId){
 url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxStudentMarks.php';
  var tableColumns1 = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('studyPeriod','Study Period','width="12%" align="left"',true),
                        new Array('subjectName','Subject','width="20%" align="left"',true),
                        new Array('testTypeName','Type','width="12%" align="left"',true), 
                        new Array('testDate','Date','width="8%" align="center"',true),
                        new Array('employeeName','Teacher','width="12%" align="left"',true),
                        new Array('testName','Test Name','width="12%" align="left"',true),
                        new Array('totalMarks','Max. Marks','width="12%" align="right" ',true),
                        new Array('obtainedMarks','Scored','width="10%" align="right"',true)
                       );
 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj2 = new initPage(url,recordsPerPage,linksPerPage,1,'','studyPeriod','ASC','gradeResultDiv','','',true,'listObj2',tableColumns1,'','','&studentId='+studentId+'&rClassId='+classId);
 sendRequest(url, listObj2, '',true )
}

window.onload=function(){
   document.getElementById('studyPeriod').value = "<?php echo $REQUEST_DATA['rClass']; ?>" != ''?"<?php echo $REQUEST_DATA['rClass']; ?>":<?php echo $studentDataArr[0]['classId']; ?>;
   refreshStudentData(<?php echo $studentDataArr[0]['studentId']; ?>,document.getElementById('studyPeriod').value);  
}

function printReport() {
    form = document.addForm;
    path='<?php echo UI_HTTP_PATH;?>/Parent/displayMarksReport.php?rClassId='+document.getElementById('studyPeriod').value+'&sortOrderBy='+listObj2.sortOrderBy+'&sortField='+listObj2.sortField;
    hideUrlData(path,true);
}

function printCSV() {   
      form=document.addForm;
      path='<?php echo UI_HTTP_PATH;?>/Parent/displayMarksReportCSV.php?rClassId='+document.getElementById('studyPeriod').value+'&sortOrderBy='+listObj2.sortOrderBy+'&sortField='+listObj2.sortField;
      //window.open(path,"StudentMarksReportCSV","status=1,menubar=1,scrollbars=1");
      window.location = path;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Parent/displayMarksContents.php");
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

//$History: displayMarks.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 10/15/09   Time: 5:48p
//Updated in $/LeapCC/Interface/Parent
//added access rights
//
//*****************  Version 4  *****************
//User: Parveen      Date: 9/03/09    Time: 5:48p
//Updated in $/LeapCC/Interface/Parent
//condition & formating updated issue fix (1426, 1384, 1263, 1074)
//

?>
