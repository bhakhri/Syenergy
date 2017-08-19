<?php
//---------------------------------------------------------------------------
// THIS FILE is used for assigning survey to emps/student/parents
// Author : Dipanjan Bhattacharjee
// Created on : (13.01.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_CollegeGpaReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback College GPA Report (Advanced)</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
var topPos = 0;
var leftPos = 0;  
recordsPerPage = 1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'feedbackQuestion';
sortOrderBy = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


//To get Label on basis of timeTable
function getSurveyLabel(value) {
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitGetLabels.php';
    
    var form = document.allDetailsForm;
    form.labelId.length = 1;
    
    vanishData();
    if (value=='') {
        return false;
    }
    var pars = 'timeTableLabelId='+value;
   
    new Ajax.Request(url,
    {
        method:'post',
        parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + trim(transport.responseText) + ')');
            var len = j.length;
            for(i=0;i<len;i++) {
                addOption(form.labelId, j[i].feedbackSurveyId, j[i].feedbackSurveyLabel);
            }

        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}

function showReport() {
 
      var url='<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetCollegeGpaReport1.php';              
      
      vanishData();
      
      page=1;
      var timeTableLabelId=document.getElementById('timeTableLabelId').value;   
      var labelId=document.getElementById('labelId').value;
      
      if(timeTableLabelId==''){
          messageBox("<?php echo SELECT_TIME_TABLE;?>");
          document.getElementById('timeTableLabelId').focus();
          return false;
      }
      
      if(labelId==''){
          messageBox("<?php echo SELECT_ADV_LABEL_NAME;?>");
          document.getElementById('labelId').focus();
          return false;
      }
  
    
      new Ajax.Request(url,
      {
            method:'post',
            parameters: {timeTableLabelId: (document.getElementById('timeTableLabelId').value),
                         labelId: (document.getElementById('labelId').value)
                        },
            onCreate:function(transport){ showWaitDialog(true);},
            onSuccess: function(transport) {
                hideWaitDialog(true);
                
                var j= trim(transport.responseText).evalJSON();

                var tbHeadArray = new Array(new Array('srNo','#','width="3%"',''), 
                                            new Array('questionName','Question','width="25%"',''));
                cnt = j.optionValueArray.length;
                for(var i=0;i<cnt;i++) {
                  var optValue = 'optStudent'+(i+1);
                  var optText = j.optionValueArray[i].optionLabel;
                  tbHeadArray.push(new Array(optValue,optText,'width="3%" align="center"','align="center"'));
                }          
                tbHeadArray.push(new Array('weightedAvg','Weighted Avg.','width="3%"','align="center"')); 
                tbHeadArray.push(new Array('response','Response','width="3%"','align="center"')); 
                tbHeadArray.push(new Array('gpa', 'GPA','width="3%"','align="center"')); 
                
                document.getElementById('nameRow2').style.display='';
                
                printResultsNoSorting('resultsDiv', j.infoArray, tbHeadArray);
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       }); 
      return false;
      
}

function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');   
      return false;
    }
     if(document.getElementById('helpChk').checked == false) {
         return false;
     }
    //document.getElementById('divHelpInfo').innerHTML=title;      
    document.getElementById('helpInfo').innerHTML= msg;   
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);
    
    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}

function vanishData(){
   document.getElementById('resultsDiv').innerHTML='';
   document.getElementById('printRowId').style.display='none';
   document.getElementById('nameRow2').style.display='none';
}



/* function to print report*/
function printReport() {
    var timeTableLabelId=document.getElementById('timeTableLabelId').value;   
    var labelId=document.getElementById('labelId').value;
  
    if(timeTableLabelId==''){
      messageBox("<?php echo SELECT_TIME_TABLE;?>");
      document.getElementById('timeTableLabelId').focus();
      return false;
    }
  
   if(labelId==''){
      messageBox("<?php echo SELECT_ADV_LABEL_NAME;?>");
      document.getElementById('labelId').focus();
      return false;
   }
   var timeTableName=document.getElementById('timeTableLabelId').options[document.getElementById('timeTableLabelId').selectedIndex].text;
   var labelName=escape(document.getElementById('labelId').options[document.getElementById('labelId').selectedIndex].text);
   
   var qstr='timeTableLabelId='+timeTableLabelId+'&labelId='+labelId+'&timeTableName='+timeTableName+'&labelName='+labelName;
   var path='<?php echo UI_HTTP_PATH;?>/collegeGpaPrint.php?'+qstr;
   
   window.open(path,"CollegeGpaReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var timeTableLabelId=document.getElementById('timeTableLabelId').value;   
    var labelId=document.getElementById('labelId').value;
  
    if(timeTableLabelId==''){
      messageBox("<?php echo SELECT_TIME_TABLE;?>");
      document.getElementById('timeTableLabelId').focus();
      return false;
    }
  
   if(labelId==''){
      messageBox("<?php echo SELECT_ADV_LABEL_NAME;?>");
      document.getElementById('labelId').focus();
      return false;
   }
    var timeTableName=document.getElementById('timeTableLabelId').options[document.getElementById('timeTableLabelId').selectedIndex].text;
   var labelName=escape(document.getElementById('labelId').options[document.getElementById('labelId').selectedIndex].text);
   
   //var qstr='timeTableLabelId='+timeTableLabelId+'&labelName='+labelName+'&timeTableName='+timeTableName+'&labelId='+labelId;
  var qstr='timeTableLabelId='+timeTableLabelId+'&labelId='+labelId+'&timeTableName='+timeTableName+'&labelName='+labelName;
   window.location='collegeGpaReportCSV.php?'+qstr;
}


window.onload=function(){
   //makeDDHide('selectedSubjectId','studentSubjectD2','studentSubjectD3');
}

</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/FeedbackAdvanced/listFeedbackCollegeGpaContents1.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
    
</body>
</html>
<?php                              
// $History: listFeedbackCollegeGpaReport.php $ 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/10   Time: 17:16
//Created in $/LeapCC/Interface
//Created college gpa report for feedback modules
?>
