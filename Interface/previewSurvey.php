<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Rajeev Aggarwal
// Created on : (19.05.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PreviewSurvey');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Preview Feedback Survey</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

recordsPerPage = <?php echo RECORDS_PER_PAGE_TEACHER;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

function validateAddForm() {
     
   if(document.getElementById('surveyType').value==''){
     
		messageBox("Select survey type");
        document.getElementById('surveyType').focus();
        return false;
   }
   if(document.getElementById('sourceSurvey').value==''){
     
		messageBox("Select Survey Source");
        document.getElementById('sourceSurvey').focus();
        return false;
   }
   var name = document.getElementById('surveyType');
   var name1 = document.getElementById('sourceSurvey');
	
	path='<?php echo UI_HTTP_PATH;?>/previewSurveyReportPrint.php?surveyTypeId='+document.getElementById('surveyType').value+'&surveyType='+name.options[name.selectedIndex].text+'&sourceSurvey='+document.getElementById('sourceSurvey').value+'&surveyName='+name1.options[name1.selectedIndex].text;
	//alert(path);
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=700, height=510, top=150,left=120");
  
}
 
function getSourceSurvey(value){

	document.getElementById('sourceSurvey').options.length=0;  
    addOption(document.getElementById('sourceSurvey'),'','Select');
    
    if(value==''){
        return false;
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxGetSourceSurveys.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                   surveyType: value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var ret=trim(transport.responseText).split('~');
                     
                     var j = eval(ret[0]);
                     var len=j.length;
                     for(var i=0;i<len;i++){
                         addOption(document.getElementById('sourceSurvey'),j[i].feedbackSurveyId,j[i].feedbackSurveyLabel)
                     } 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
    
}
 
function getData(){
    if(document.getElementById('surveyType').value==''){
        messageBox("Select survey type");
        document.getElementById('surveyType').focus();
        return false;
    }
    if(document.getElementById('sourceSurvey').value==''){
        messageBox("Select survey source");
        document.getElementById('sourceSurvey').focus();
        return false;
    }
     
    
     
}
 
 

window.onload=function(){
    document.getElementById('surveyType').selectedIndex=0;
    document.getElementById('surveyType').focus();
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/FeedBack/previewSurveyContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: previewSurvey.php $ 
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/21/09    Time: 6:33p
//Created in $/LeapCC/Interface
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/19/09    Time: 4:51p
//Created in $/Leap/Source/Interface
//Added Preview survey related function.
?>