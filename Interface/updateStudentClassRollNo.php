<?php
//-------------------------------------------------------
// Purpose: To generate assign time table label to attendance from the database, and have add/edit/delete, search 
// functionality 
//
// Author : Jaineesh
// Created on : (06.05.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentClassRollNo');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Update Student Class/RollNo.</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?>
<script language="javascript">

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
       displayWindow(dv,w,h);
       populateValues(id);
}

function validateForm() {
	
	if(trim(document.getElementById('criteria').value) == '') {
		messageBox("Select criteria");
		document.getElementById('criteria').focus();
		return false;
	}
	if(trim(document.getElementById('criteria').value) == 1) {
		if(trim(document.getElementById('rollNo').value) == '') {
			messageBox("Enter Roll No.");
			document.getElementById('rollNo').focus();
			return false;
		}
	}
	if(trim(document.getElementById('criteria').value) == 2) {
		if(trim(document.getElementById('rollNo').value) == '') {
			messageBox("Enter University Roll No.");
			document.getElementById('rollNo').focus();
			return false;
		}
	}
	if(trim(document.getElementById('criteria').value) == 3) {
		if(trim(document.getElementById('rollNo').value) == '') {
			messageBox("Enter Registration No.");
			document.getElementById('rollNo').focus();
			return false;
		}
	}
     

    getClasses();
	return false;
}
    


function getClasses() {

    if(document.updateStudentForm.chkClasses[0].checked) {
      chkClasses=1; 
    } 
    else {
      chkClasses=0;   
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/UpdateStudentClass/ajaxGetClasses.php';
    
    document.updateStudentForm.newClass.length = null;
    addOption(document.updateStudentForm.newClass, '', 'Select');
    
    new Ajax.Request(url,
    {
         method:'post',
          parameters:{chkClasses : chkClasses,
                      criteria   : document.updateStudentForm.criteria.value,    
                      rollNo     : trim(document.updateStudentForm.rollNo.value)  
                     },
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
           hideWaitDialog(true);
            
           var ret=trim(transport.responseText).split('!~!~!');  
           document.updateStudentForm.newClass.length = null;
           addOption(document.updateStudentForm.newClass, '', 'Select');
           if(ret.length > 0 ) {
              var j = eval('(' + ret[0] + ')');
              var currentClassId = eval('(' + ret[1] + ')');
              len = j.length;
              document.updateStudentForm.newClass.length = null;
              addOption(document.updateStudentForm.newClass, '', 'Select');
              for(i=0;i<len;i++) { 
                if(j[i].classId!=currentClassId) {
                  addOption(document.updateStudentForm.newClass, j[i].classId, j[i].className);
                }
              }
           }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
    
    getCurrentClass();
}

function getCurrentClass(){
 
        url = '<?php echo HTTP_LIB_PATH;?>/UpdateStudentClass/ajaxUpdateStudentClass.php';

     
     
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {criteria    : document.updateStudentForm.criteria.value,    
                      rollNo    : trim(document.updateStudentForm.rollNo.value)
                     },
         onCreate: function() {
                     //showWaitDialog();
          },
         onSuccess: function(transport){
                 //hideWaitDialog(true);
                 var criteria = transport.responseText.split('~');
                 var criteriaa = criteria[0];
                 var criteriab = criteria[1];
                if(trim(criteriaa)==0) {
                    if(trim(criteriab)==1) {
                        document.getElementById('showTitle').style.display='None';
                        document.getElementById('showData').style.display='None';
                        messageBox("<?php echo ROLLNO_NOT_EXIST; ?>");
                        document.getElementById('rollNo').focus();
                        return false;
                    }
                    if(trim(criteriab)==2) {
                        document.getElementById('showTitle').style.display='None';
                        document.getElementById('showData').style.display='None';
                        messageBox("<?php echo UNIVERSITY_ROLLNO_NOT_EXIST; ?>");
                        document.getElementById('rollNo').focus();
                        return false;
                    }
                    if(trim(criteriab)==3) {
                        document.getElementById('showTitle').style.display='None';
                        document.getElementById('showData').style.display='None';
                        messageBox("<?php echo REGNO_NOT_EXIST; ?>");
                        document.getElementById('rollNo').focus();
                        return false;
                    }
                }
                j = eval('('+trim(transport.responseText)+')');
                document.getElementById('currentClass').value=j.className;
                document.getElementById('classId').value=j.classId;
                document.getElementById('studentId').value=j.studentId;
                document.getElementById('newRollNo').value=document.getElementById('rollNo').value;
                document.getElementById('userId').value=j.userId;
                document.getElementById('showTitle').style.display='';
                document.getElementById('showData').style.display='';
        },
         onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }   
       });
}

function getCriteria() {
	if(document.getElementById('criteria').value ==1) {
		document.getElementById('showTitle').style.display='None';
		document.getElementById('showData').style.display='None';
		document.getElementById('criteriaHeading').innerHTML = 'Roll No.';
	}
	else if(document.getElementById('criteria').value == 2) {
		document.getElementById('showTitle').style.display='None';
		document.getElementById('showData').style.display='None';
		document.getElementById('criteriaHeading').innerHTML = 'University Roll No.';
	}
	else if(document.getElementById('criteria').value == 3) {
		document.getElementById('showTitle').style.display='None';
		document.getElementById('showData').style.display='None';
		document.getElementById('criteriaHeading').innerHTML = 'Registration No.';
	}
}
function clearText(){

  //  document.getElementById('saveDiv').style.display='none';
    document.getElementById('rollNo').value='';
	document.getElementById('newRollNo').value='';
	document.getElementById('reason').value='';
	document.getElementById('newClass').value='';
	document.getElementById('showData').style.display='none';
	document.getElementById('showTitle').style.display='none';

}

function insertForm() {
 
	 url = '<?php echo HTTP_LIB_PATH;?>/UpdateStudentClass/updateStudentClass.php';
	 
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: $('#updateStudentForm').serialize(true),
		 onCreate: function() {
			 showWaitDialog();
		 },
		 onSuccess: function(transport){

			 hideWaitDialog();
			 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {  
				 flag = true;
					 alert(trim(transport.responseText));
					 clearText();
					 return false;
			 }
			 else {
					str = trim(transport.responseText);
					messageBox(trim(str));
					//document.getElementById('listForm').reset(); 
			 }
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

 
function validateAddForm(frm, act) {
    
	if(document.getElementById('reason').value == "") {
			messageBox("<?php echo SELECT_REASON; ?>");
			return false;
		}
	
	if(document.getElementById('newRollNo').value == "") {
		if(document.getElementById('userName').checked == 1) {
			messageBox("<?php echo SELECT_NEW_ROLLNO; ?>");
			return false;
		}
	}

        document.getElementById('newRollNo').value = trim(document.getElementById('newRollNo').value);
	
        if(trim(document.getElementById('newRollNo').value)!='') {
           if(!isAlphaNumericCustom(trim(document.getElementById('newRollNo').value),'a-z,0-9,&-_./+,{}[]()')) {
                 messageBox("<?php echo STUDENT_NEW_ROLL_NO; ?>");
                 document.getElementById('newRollNo').focus();
                 return false; 	
                 }
          }
    insertForm();
	return false;
}



window.onload=function(){
 document.updateStudentForm.criteria.focus();
 getCriteria();
 //var roll = document.getElementById("rollNo");
 //autoSuggest(roll);
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/UpdateStudentClass/updateStudentClassRollNoContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: updateStudentClassRollNo.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/04/10    Time: 11:50
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Fixed bugs---
//0003231,0003230,0003229,0003228,0003227,0003225,0003224,0003156
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/14/09    Time: 5:34p
//Created in $/LeapCC/Interface
//copy from sc
//
//
?>
