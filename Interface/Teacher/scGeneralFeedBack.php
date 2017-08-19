<?php

//-------------------------------------------------------
//  This File contains the template file and data base file
//
// Author :Jaineesh
// Created on : 29.07.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ProvideGeneralSurvey');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: General Survey </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

//function passes two parameters i.e dates to ajax file which returns table that contains data

function validateAddForm() {

	form = document.feedBackForm;
	totalQuestions = form.totalQuestions.value;
	totalFeedBackAnswers = form.totalFeedBackAnswers.value;

       for(i=0;i<totalQuestions;i++) {
		    thisRadio = "radio_"+i;
		    noSelected = true;
		    for(j=0;j<totalFeedBackAnswers;j++) {
                if(totalFeedBackAnswers==1) {
			      str = "form."+thisRadio+".checked";
                }
                else {
                   str = "form."+thisRadio+"["+j+"]"+".checked";
                }
        	    thisRadioSelected = eval(str);
			    if(thisRadioSelected ==  true) {
				    noSelected = false;
				    break;
			    }
		    }
		    x = i+1;
		    if(noSelected == true) {
			    alert(" No  answer selected for question no "+x);
			    return false;
		    }
	    }

	if (form.feedBackSurvey.value==0) {
		alert("Select FeedBack Survey ");
		form.feedBackSurvey.focus();
		return false;
	}

	addFeedBack();
	return false;

}

function addFeedBack() {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitGeneralFeedBackAdd.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: $('feedBackForm').serialize(true),

               OnCreate: function(){
                  showWaitDialog();
               },
               onSuccess: function(transport){
                     hideWaitDialog();

                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         flag = true;
                         messageBox(trim(transport.responseText));
						 document.feedBackForm.feedBackSurvey.value="0";
						 blankValues();
                         return false;
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function blankValues() {

	form = document.feedBackForm;
	totalQuestions = form.totalQuestions.value;
	totalFeedBackAnswers = form.totalFeedBackAnswers.value;
	document.getElementById('results').innerHTML='';

	for(i=0;i<totalQuestions;i++) {
		thisRadio = "radio_"+i;
		//noSelected = true;
		for(j=0;j<totalFeedBackAnswers;j++) {
			str = "form."+thisRadio+"["+j+"]"+".checked"+"=false";
			thisRadioSelected = eval(str);
			if(thisRadioSelected ==  true) {
				noSelected = true;
				break;
			}
		}
	}
}

function getSueveyLabel() {

         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGeneralFeedBackSurvey.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
				feedBackSurveyId: (document.getElementById('feedBackSurvey').value)
			 },

               OnCreate: function(){
                  showWaitDialog();
               },
               onSuccess: function(transport){
                     hideWaitDialog();
					 if("<?php echo 'Your feedback against this survey is finished'; ?>" == trim(transport.responseText)) {
						messageBox(trim(transport.responseText));
						document.feedBackForm.feedBackSurvey.value=0;
						blankValues();
						return false;
					 }
					 else {
						document.getElementById('results').innerHTML=trim(transport.responseText);
					 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/scGeneralFeedBackContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>


<?php
////$History: scGeneralFeedBack.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/01/09    Time: 17:44
//Updated in $/Leap/Source/Interface/Teacher
//Corrected breadcrumb
?>