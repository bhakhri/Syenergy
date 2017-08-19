<?php 

//-------------------------------------------------------
//  This File contains the template file and data base file
//
//
// Author :Jaineesh
// Created on : 22-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifParentNotLoggedIn();
require_once(BL_PATH . "/ScParent/scInitData.php"); 
//require_once(BL_PATH . "/ScParent/scInitTimeTable.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Time Table </title>
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
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

//this function is uded to refresh tab data based uplon selection of study periods
function refreshStudentData(studentId,classId){
    //get the data of time table based upon selected study period
    var timeTableData=refreshTimeTableData(studentId,classId);
}

//this function fetches records corresponding to student time table
function refreshTimeTableData(studentId,classId){
  currentClassId = "<?php echo $studentDataArr[0]['classId']?>";     
  url = '<?php echo HTTP_LIB_PATH;?>/ScParent/scAjaxStudentTimeTable.php';
  new Ajax.Request(url,
   {
     method:'post',
     parameters: {
         currentClassId: (currentClassId),studentId: (studentId),classId: (classId)
         },
     onCreate: function() {
         showWaitDialog();
     },
     onSuccess: function(transport){
             hideWaitDialog();
             document.getElementById('timeTableResultDiv').innerHTML=trim(transport.responseText);
      },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
   });
} 

window.onload=function(){
  refreshStudentData(<?php echo $studentDataArr[0]['studentId']; ?>,<?php echo $studentDataArr[0]['classId']; ?>);  
}


function printReport() {
    form = document.addForm;
    path='<?php echo UI_HTTP_PATH;?>/Parent/scDisplayTimeTableReport.php?currentClassId='+<?php echo $studentDataArr[0]['classId']; ?>+'&rClassId='+document.getElementById('studyPeriod').value;
    window.open(path,"StudentTimeTableReport","status=1,menubar=1,scrollbars=1, width=900");
}

/*function printCSV() {   
      form=document.addForm;
      path='<?php echo UI_HTTP_PATH;?>/Parent/scDisplayTimeTableReport.php?currentClassId='+<?php echo $studentDataArr[0]['classId']; ?>+'&classId='+document.getElementById('studyPeriod').value;
      window.open(path,"StudentTimeTableReportCSV","status=1,menubar=1,scrollbars=1");
} */

 </script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/ScParent/scTimeTableContents.php");
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

//History: $


?>
