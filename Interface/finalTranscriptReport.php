<?php 
//-------------------------------------------------------
//  This File contains the template file and data base file
// Author :Ipta Thakur
// Created on : 02-nov-2011
// Copyright 2011-2012: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FinalTranscriptReport');
define('ACCESS','view');
global $sessionHandler; 
$roleId = $sessionHandler->getSessionVariable('RoleId');
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
listURL = '<?php echo HTTP_LIB_PATH;?>/FinalTranscriptReport/initFinalTranscriptReport.php';
searchFormName = 'listForm'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'universityRollNo';
sortOrderBy    = 'DESC';
// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
var id='';
var roleId = "<?php echo $sessionHandler->getSessionVariable('RoleId'); ?>";
var studentId = "<?php echo $sessionHandler->getSessionVariable('StudentId'); ?>";

window.onload=function() {
   validateAddForm();
      return false;
   }
   document.getElementById('enterRollNo').style.display=''; 
}
var tableHeadArray = new Array(new Array('srNo','#','width="50"','',false), 
                               new Array('subjectCode','Course Code','align="left" width="15%"','align="left"',true) , 
                               new Array('subjectName','Course Name ','align="left" width="40%"','align="left"',true), 
                               new Array('credits','Credits','align="center" width="12%"','align="center"',true),
                               new Array('gradeLabel','Grade','align="center" width="15%"','align="center"',true),
                               new Array('Final Grade','finalPoint','align="center" width="15%"','align="center"',true),
                               new Array('Letter Grade','finalGrade','align="center" width="15%"','align="center"',true));
function printReport() {

    form = document.listForm;
    var id=trim(document.getElementById('rollNo').value);
path='<?php echo UI_HTTP_PATH;?>/transcriptReportPrint.php?id='+id+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"TranscriptReport","status=1,menubar=1,scrollbars=1, width=700, height=400, top=150,left=150");
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
}
    else {
       if(trim(document.getElementById('rollNo').value) =='') {
	  messageBox ("<?php echo ENTER_ROLLNO;?>");
	  document.getElementById('rollNo').focus();
	  return false;
       }   
    }

    var url = '<?php echo HTTP_LIB_PATH;?>/FinalTranscriptReport/initFinalTranscriptReport.php'; 
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
           var j= trim(transport.responseText).evalJSON();  
           
           printResults('results', j.info, j.page, j.totalRecords, tableHeadArray, 'listForm');
                 
           document.getElementById('CurrentCGPA').innerHTML= j.CurrentCGPA; 
           document.getElementById('FatherName').innerHTML= j.FatherName;  
           document.getElementById('StudentName').innerHTML=j.StudentName;  
           document.getElementById('studentId').value= j.Id;
           id=j.Id;
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FinalTranscriptReport/listFinalTranscriptContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>               


