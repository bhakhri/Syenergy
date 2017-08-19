<?php 
//-------------------------------------------------------
//  This File contains the template file and data base file
//
//
// Author :Aditi Miglani
// Created on : 14-Sept-2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TranscriptReport');
define('ACCESS','view');
global $sessionHandler; 
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId=='4') { 
  UtilityManager::ifStudentNotLoggedIn();
}
else if($roleId=='3') { 
  UtilityManager::ifParentNotLoggedIn();  
  
}
else {
  UtilityManager::ifNotLoggedIn(); 
}
UtilityManager::headerNoCache();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Transcript Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
specialFormatting=0;
// ajax search results ---start///

recordsPerPage = 5000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/TranscriptReport/initStudentGradeCard.php';
searchFormName = 'listForm'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'subjectCode';
sortOrderBy    = 'DESC';
// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
var id='';
var roleId = "<?php echo $sessionHandler->getSessionVariable('RoleId'); ?>";
var studentId = "<?php echo $sessionHandler->getSessionVariable('StudentId'); ?>";
var ttRollNo = '';

window.onload=function() {
   if(roleId==3 || roleId==4) {
      validateAddForm();
      return false;
   }
   document.getElementById('enterRollNo').style.display=''; 
}
/*
var tableHeadArray = new Array(new Array('srNo','#','width="50"','',false), 
                              new Array('subjectCode','Course Code','align="left" width="15%"','align="left"',true) , 
                              new Array('subjectName','Course Name ','align="left" width="40%"','align="left"',true), 
                              new Array('credits','Credits','align="center" width="12%"','align="center"',true),
                              new Array('gradeLabel','Grade','align="center" width="15%"','align="center"',true),
                              new Array('periodName','Study Period','align="left" width="15%"','align="left"',true));
*/

function printReport() {

    form = document.listForm;
    var id=trim(document.getElementById('rollNo').value);

    if(roleId ==3 || roleId ==4) {
      id = studentId;
    }
    path='<?php echo UI_HTTP_PATH;?>/transcriptReportPrint.php?id='+id+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    hideUrlData(path,true);
}

/* function to print all subject to class report*/
function printCSV() {

    form = document.listForm;
    var id=trim(document.getElementById('rollNo').value);

    if(roleId ==3 || roleId ==4) {
      id = studentId;
    }
    path='<?php echo UI_HTTP_PATH;?>/transcriptReportCSV.php?id='+id+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.location=path;
}

function clearText(){
    document.getElementById('saveDiv').style.display='none';
    document.getElementById('saveDiv1').style.display='none';         
    document.getElementById('showTitle').style.display='none';         
    document.getElementById('showData').style.display='none';
    document.getElementById('showFName').style.display='none';   
    document.getElementById('showCGPA').style.display='none'; 
    document.getElementById('results').innerHTML="";
    document.getElementById('CurrentCGPA').innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>";
    document.getElementById('FatherName').innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>"; 
    document.getElementById('StudentName').innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>";   
}
 
function showText(){
    document.getElementById('saveDiv').style.display='';
    document.getElementById('saveDiv1').style.display='';         
    document.getElementById('showTitle').style.display='';         
    document.getElementById('showData').style.display='';
    document.getElementById('showFName').style.display='';   
    //document.getElementById('showCGPA').style.display=''; 
    document.getElementById('results').innerHTML="";
    //document.getElementById('CurrentCGPA').innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>";
    document.getElementById('FatherName').innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>"; 
    document.getElementById('StudentName').innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>"; 
} 
 
function validateAddForm() {
    
    clearText();

    var id=trim(document.getElementById('rollNo').value);

    if(roleId ==3 || roleId ==4) {
      id = studentId;
    }
    else {
       if(trim(document.getElementById('rollNo').value) =='') {
	      messageBox ("<?php echo ENTER_ROLLNO;?>");
	      document.getElementById('rollNo').focus();
	      return false;
       } 
       ttRollNo = trim(document.getElementById('rollNo').value); 
    }

    var url = '<?php echo HTTP_LIB_PATH;?>/TranscriptReport/initStudentGradeCard.php'; 
    new Ajax.Request(url,
    {
         method:'post',
         asynchronous : false,
         parameters: {rollNo: id},
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
           hideWaitDialog(true);
           if("<?php echo INVALID_ROLL_NO;?>" == trim(transport.responseText)) { 
             messageBox(trim(transport.responseText));  
             document.getElementById('rollNo').focus();
             return false;
           }
           showText();
           //var j= trim(transport.responseText).evalJSON();  
           var ret=trim(transport.responseText).split('!~~!');
           
           //printResults('results', j.info, j.page, j.totalRecords, tableHeadArray, 'listForm');
           document.getElementById('CurrentCGPA').innerHTML= ret[0]; 
           document.getElementById('FatherName').innerHTML=  ret[1];  
           document.getElementById('StudentName').innerHTML= ret[2];  
           document.getElementById('results').innerHTML= ret[3];  
           document.getElementById('studentId').value=  ret[4];
           id= ret[4];
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

function printReport() {
    path='<?php echo UI_HTTP_PATH;?>/transcriptReportPrint.php?rollNo='+ttRollNo;   
    window.open(path,"transcriptReportPrint","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
    path='<?php echo UI_HTTP_PATH;?>/transcriptReportCSV.php?rollNo='+ttRollNo;   
    window.location = path;
}

function printTranscriptReport() {
    
    if(trim(document.getElementById('txtTotal').value)=='') {
       messageBox ("Enter the Total Study Period to Print");
       document.getElementById('txtTotal').focus();
       return false;
    }

    if(trim(document.getElementById('txtTotal').value)==0) {
       messageBox ("Enter the value for Total Study Period to Print grerate than zero");
       document.getElementById('txtTotal').focus();
       return false;
    }
    
    if(!isNumeric(trim(document.getElementById('txtTotal').value))) {
       messageBox ("Enter the numeric value for Total Study Period to Print");
       document.getElementById('txtTotal').focus();
       return false;
    }
    
    printHeader='0';
    if(document.listForm.printHeader[1].checked==true) {
      printHeader='1';
    }
    printStudyPeriod = trim(document.getElementById('txtTotal').value);
    path='<?php echo UI_HTTP_PATH;?>/transcriptDetailPrint.php?rollNo='+ttRollNo+"&printHeader="+printHeader+"&printStudyPeriod="+printStudyPeriod;
   hideUrlData(path,true);
}


</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TranscriptReport/listTranscriptContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
