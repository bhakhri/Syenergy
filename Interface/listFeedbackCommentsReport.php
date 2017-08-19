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
define('MODULE','ADVFB_CommentsReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Comments Report (Advanced)</title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">


var tableHeadArray = new Array(
                               new Array('srNo','#','width="2%"','align="left"',false),
                               new Array('className','Class','width="25%"','align="left"',true) ,
                               new Array('subjectCode','Subject','width="10%"','align="left"',true),
                               new Array('employeeName','Employee','width="15%"','align="left"',true) ,
                               new Array('comments','Comments','width="10%"','align="left"',true)
                              );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxFeedbackCommentsReport.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
/*
addFormName    = 'AddCity';
editFormName   = 'EditCity';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteCity';
*/
divResultName  = 'reportDiv';
page=1; //default page
sortField = 'className';
sortOrderBy    = 'ASC';


// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


//To get Label on basis of timeTable
function getSurveyLabel(value) {
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitGetSurveyLabels.php';

    var form = document.allDetailsForm;
    form.labelId.length = 1;
    form.subjectId.length = 1;

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

function getClasss(value1,value2) {
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitGetClasss.php';

    var form = document.allDetailsForm;
    form.classId.length = 1;
    form.subjectId.length = 1;

    vanishData();
    if (value1=='' || value2=='') {
        return false;
    }

    new Ajax.Request(url,
    {
        method:'post',
        asynchronous:true,
        parameters: {
                 timeTableLabelId : value1,
                 labelId          : value2
        },
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            if(trim(transport.responseText)==-1){
                document.getElementById('classId').disabled=true;
                document.getElementById('subjectId').disabled=true;
                tableHeadArray = new Array(
                                            new Array('srNo','#','width="2%"','align="left"',false),
                                            new Array('feedbackSurveyLabel','Label','width="15%"','align="left"',true) ,
                                            new Array('feedbackCategoryName','Category','width="15%"','align="left"',true),
                                            new Array('comments','Comments','width="38%"','align="left"',true),
														 new Array('rollNo','Roll No.','width="10%"','align="left"',true),
														 new Array('studentName','Student Name','width="30%"','align="left"',true)
                                          );
                sortField = 'feedbackSurveyLabel';
                return false;
            }
            else{
                document.getElementById('classId').disabled=false;
                document.getElementById('subjectId').disabled=false;
                tableHeadArray = new Array(
                                            new Array('srNo','#','width="2%"','align="left"',false),
                                            new Array('className','Class','width="18%"','align="left"',true) ,
                                            new Array('subjectCode','Subject','width="8%"','align="left"',true),
                                            new Array('employeeName','Employee','width="15%"','align="left"',true) ,
                                            new Array('comments','Comments','width="30%"','align="left"',true),
														 new Array('rollNo','Roll No','width="10%"','align="left"',true),
														 new Array('studentName','Student Name','width="30%"','align="left"',true)
                                          );
                sortField = 'className';
            }

            var j = eval('(' + trim(transport.responseText) + ')');
            var len = j.length;
            for(i=0;i<len;i++) {
                addOption(form.classId, j[i].classId, j[i].className);
            }

        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}

function getSubjects(value1,value2,value3) {
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitGetSubjects.php';

    var form = document.allDetailsForm;
    form.subjectId.length = 1;

    vanishData();
    if (value1=='' || value2=='' || value3=='') {
        return false;
    }

    new Ajax.Request(url,
    {
        method:'post',
        asynchronous:true,
        parameters: {
                 timeTableLabelId : value1,
                 labelId          : value2,
                 classId          : value3
        },
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + trim(transport.responseText) + ')');
            var len = j.length;
            for(i=0;i<len;i++) {
                addOption(form.subjectId, j[i].subjectId, j[i].subjectCode);
            }

        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}


function showReport() {

  var timeTableLabelId=document.getElementById('timeTableLabelId').value;
  var labelId=document.getElementById('labelId').value;
  var classId=document.getElementById('classId').value;
  var subjectId=document.getElementById('subjectId').value;

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

  if(classId=='' && !document.getElementById('classId').disabled){
      messageBox("<?php echo SELECT_CLASS;?>");
      document.getElementById('classId').focus();
      return false;
  }

  vanishData();
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
  document.getElementById('printRowId').style.display='';
}

function vanishData(){
   document.getElementById('reportDiv').innerHTML='';
   document.getElementById('printRowId').style.display='none';
}



/* function to print report*/
function printReport() {
    var timeTableLabelId=document.getElementById('timeTableLabelId').value;
    var labelId=document.getElementById('labelId').value;
    var classId=document.getElementById('classId').value;
    var subjectId=document.getElementById('subjectId').value;

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

   if(classId=='' && !document.getElementById('classId').disabled){
      messageBox("<?php echo SELECT_CLASS;?>");
      document.getElementById('classId').focus();
      return false;
   }

   var timeTableName=document.getElementById('timeTableLabelId').options[document.getElementById('timeTableLabelId').selectedIndex].text;
   var labelName=escape(document.getElementById('labelId').options[document.getElementById('labelId').selectedIndex].text);
   var subjectName=escape(document.getElementById('subjectId').options[document.getElementById('subjectId').selectedIndex].text);
   var className=escape(document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text);

   var qstr='timeTableLabelId='+timeTableLabelId+'&labelId='+labelId+'&subjectId='+subjectId+'&classId='+classId+'&timeTableName='+timeTableName+'&labelName='+labelName+'&subjectName='+subjectName+'&className='+className;
   var path='<?php echo UI_HTTP_PATH;?>/feedbackCommentsReportPrint.php?'+qstr;

   window.open(path,"FeedbackCommentsReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");

}


/* function to output data to a CSV*/
function printCSV() {
    var timeTableLabelId=document.getElementById('timeTableLabelId').value;
    var labelId=document.getElementById('labelId').value;
    var classId=document.getElementById('classId').value;
    var subjectId=document.getElementById('subjectId').value;

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

   if(classId=='' && !document.getElementById('classId').disabled){
      messageBox("<?php echo SELECT_CLASS;?>");
      document.getElementById('classId').focus();
      return false;
   }

   var qstr='timeTableLabelId='+timeTableLabelId+'&labelId='+labelId+'&subjectId='+subjectId+'&classId='+classId;
   window.location='feedbackCommentsReportCSV.php?'+qstr;
}

</script>
</head>
<body>
	<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/FeedbackAdvanced/listFeedbackCommentsReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: listFeedbackCommentsReport.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/05/10    Time: 1:03p
//Updated in $/LeapCC/Interface
//Corrected title of the page
?>