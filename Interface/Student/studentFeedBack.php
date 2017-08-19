<?php 

//-------------------------------------------------------
//  This File contains the template file and data base file
//
// Author :Jaineesh
// Created on : 23.07.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentTeacherFeedBack');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
require_once(BL_PATH . "/Student/studentFeedBack.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feed Back Details </title>
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
      

	if (form.teacherName.value==0) {
		alert("Select Teacher Name");
		form.teacherName.focus();	
		return false;
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
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitFeedBackAdd.php';
		 
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
	document.getElementById('results').innerHTML='';
	document.feedBackForm.teacherName.value="0";
	document.feedBackForm.feedBackSurvey.value="0";

}

function getSueveyLabel() {
	
	url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxFeedBackSurvey.php';
		 
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
					 document.getElementById('results').innerHTML=trim(transport.responseText);
					 return false;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function duplicateCheck() {

	if (document.feedBackForm.feedBackSurvey.value!="0") {
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxDuplicateFeedBackAdd.php';
	}
	else {
		alert ("Please First Select Feed Back Survey");
		document.feedBackForm.teacherName.value="0";
		document.feedBackForm.feedBackSurvey.focus();
		return false;
	}
		 
         new Ajax.Request(url,
           {
             method:'post',
             parameters: $('feedBackForm').serialize(true),
             
               OnCreate: function(){
                  showWaitDialog();
               },
               onSuccess: function(transport){
                     hideWaitDialog();

					 if("<?php echo "Duplicate Record" ?>" == trim(transport.responseText)){
                       alert("You have already submitted your feedback for this teacher");
					   document.feedBackForm.teacherName.value="0";
					   return false;
                  }
               
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
 
window.onload = function(){
	document.feedBackForm.teacherName.value="0";
	document.feedBackForm.feedBackSurvey.value="0";
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/studentFeedBackContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>


<?php 

////$History: studentFeedBack.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:27p
//Updated in $/LeapCC/Interface/Student
//added access defines
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/27/09    Time: 11:02a
//Updated in $/LeapCC/Interface/Student
//copy from sc and modifications in the files as per requirement of CC
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:16p
//Updated in $/LeapCC/Interface/Student
//modification code for student feedback
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/24/08   Time: 1:39p
//Updated in $/Leap/Source/Interface/Student
//modified the code for selection of teacher
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/20/08   Time: 10:19a
//Created in $/Leap/Source/Interface/Student
//file is used to give student feed back for their teacher
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 11/14/08   Time: 5:25p
//Updated in $/Leap/Source/Interface/Student
//modified for semster wise report
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 11/13/08   Time: 5:53p
//Updated in $/Leap/Source/Interface/Student
//modified code for semester wise detail
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 11/03/08   Time: 7:04p
//Updated in $/Leap/Source/Interface/Student
//modified for semester wise attendance report
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 10/21/08   Time: 1:46p
//Updated in $/Leap/Source/Interface/Student
//modified for paging
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 10/20/08   Time: 6:20p
//Updated in $/Leap/Source/Interface/Student
//modified for date format
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 10/20/08   Time: 4:51p
//Updated in $/Leap/Source/Interface/Student
//modified 
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:10p
//Updated in $/Leap/Source/Interface/Student
//remove the spaces
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 9/29/08    Time: 4:22p
//Updated in $/Leap/Source/Interface/Student
//modified for csv
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/26/08    Time: 4:44p
//Updated in $/Leap/Source/Interface/Student
//put csv code
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/24/08    Time: 7:07p
//Updated in $/Leap/Source/Interface/Student
//modified for csv
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/19/08    Time: 12:23p
//Updated in $/Leap/Source/Interface/Student
//change file name according to sc
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 9/17/08    Time: 1:59p
//Updated in $/Leap/Source/Interface/Student
//show attendance of student for sc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/13/08    Time: 5:39p
//Created in $/Leap/Source/Interface/Student
//student attendance for sc
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 9/13/08    Time: 12:07p
//Updated in $/Leap/Source/Interface/Student
//modify for date
//
//*****************  Version 6  *****************
//User: Administrator Date: 9/05/08    Time: 7:28p
//Updated in $/Leap/Source/Interface/Student
//bugs fixation
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/29/08    Time: 11:01a
//Updated in $/Leap/Source/Interface/Student
//modification in template
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/18/08    Time: 5:33p
//Updated in $/Leap/Source/Interface/Student
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/14/08    Time: 3:50p
//Updated in $/Leap/Source/Interface/Student
//modified for print
//
//*****************  Version 2  *****************
//User: Administrator Date: 7/28/08    Time: 7:03p
//Updated in $/Leap/Source/Interface/Student
//modified for attendance 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/25/08    Time: 1:04p
//Created in $/Leap/Source/Interface/Student
//contain the student attendance data base function & template files
//

?>