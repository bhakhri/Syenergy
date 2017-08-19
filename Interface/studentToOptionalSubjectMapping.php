<?php
//-------------------------------------------------------
// Purpose: To do the mapping of students to optional subjects
// functionality 
//
// Author : Arvind Singh Rawat Aggarwal
// Created on : (28.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignOptionalSubjectToStudents');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/TimeTable/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student to Optional Subject Mapping</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

function validatetoptionalSubjectMapping() {
    var fieldsArray = new Array(new Array("studentClass","<?php echo SELECT_CLASS;?>"),new Array("subject","<?php echo SELECT_SUBJECT;?>"),new Array("studentGroup","<?php echo SELECT_GROUP;?>") );
    var len = fieldsArray.length;
	var frm = document.optionalSubjectMapping;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
	    }
	} 
	 showStudents();
	  return false;
}

function showStudents(){

   url = '<?php echo HTTP_LIB_PATH;?>/OptionalSubjectMapping/ajaxInitList.php';
   var checkBoxCount;
   new Ajax.Request(url,
   {
	 method:'post',
	 parameters: $('optionalSubjectMapping').serialize(true),
	 onCreate:function(transport){ showWaitDialog(true);},
	 onSuccess: function(transport){
			hideWaitDialog(true);        
            j = trim(transport.responseText).evalJSON();
	        var tbHeadArray = new Array(new Array('checkAll','<input type="checkbox" name="checkbox2" value="checkbox" onClick="doAll()">','width="3%"','',false),new Array('srNo','#','width="3%"','',false), new Array('firstName','First Name','width="20%"','',true) ,new Array('lastName','Last Name','width="20%"','',true), new Array('rollNo','Roll No','width="20%"','align="left"',true), new Array('regNo','Registration No','width="37%"','align="left"',true));   
            printResultsNoSorting('results', j.info, tbHeadArray);
            document.getElementById('submitButton').style.display="block";
            checkBoxCount=j.checkedBoxes;
             document.getElementById('checkBoxCount').value=checkBoxCount;
         },
	 onFailure: function(){ alert('Something went wrong...') }
   });
}

function validateAddForm(frm, act) {
    var selected=0;
    formx = document.optionalSubjectMapping;
    for(var i=1;i<formx.length;i++)
    {
        if(formx.elements[i].type=="checkbox")
        {
            if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]"))
            {selected++;}
        }
    }
    if(selected==0)
    {
        alert("<?php echo SUBJECT_TO_CLASS_ONE?>");
        return false;
    }
    addData();
    return false;
}

function addData() {
   url = '<?php echo HTTP_LIB_PATH;?>/OptionalSubjectMapping/initAdd.php';
   new Ajax.Request(url,
   {
	 method:'post',
	 parameters: $('optionalSubjectMapping').serialize(true),
	 onSuccess: function(transport){
	   if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
		  showWaitDialog(true);
	   }
	   else {
             hideWaitDialog(true);
			 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {  
				 
				 flag = true;
                 
				 messageBox("<?php echo SUCCESS;?>") 
					    
                 document.getElementById('results').innerHTML="";
                 document.getElementById('submitButton').style.display="none";
                     return false;
	
            } 
       }
	 },
	 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function doAll()
{
   formx = document.optionalSubjectMapping;
   if(formx.checkbox2.checked){
        for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox"){
            formx.elements[i].checked=true;
		}
	  }
   }
   else
   {
	  for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox"){
            formx.elements[i].checked=false;
		}
	  }
   }
}

function autoPopulate(val,type,frm)
{
   url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';
   if(frm=="Add"){
       if(type=="subjectOptional"){
            document.optionalSubjectMapping.subject.options.length=0;
            var objOption = new Option("SELECT","");
            document.optionalSubjectMapping.subject.options.add(objOption); 
       }
}
   
new Ajax.Request(url,
{
	 method:'post',
	 parameters: {type: type,id: val},
	 onSuccess: function(transport){
	   if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
		   
		  showWaitDialog(true);
	   }
	   else {
			hideWaitDialog(true);
			j = trim(transport.responseText).evalJSON();   
			len = j.subjectArr.length;
			//alert(len);
			//alert(j.subjectArr);
			document.optionalSubjectMapping.subject.length = null;
			// add option Select initially
			addOption(document.optionalSubjectMapping.subject, '', 'Select');
			for(i=0;i<len;i++) { 
			 addOption(document.optionalSubjectMapping.subject, j.subjectArr[i].subjectId, j.subjectArr[i].subjectName);
	   }
            len = j.groupArr.length;
			document.optionalSubjectMapping.studentGroup.length = null;
			addOption(document.optionalSubjectMapping.studentGroup, '', 'Select');
			for(i=0;i<len;i++) { 
			 addOption(document.optionalSubjectMapping.studentGroup, j.groupArr[i].groupId, j.groupArr[i].groupName);
			}
		  }
	 },
	 onFailure: function(){ alert('Something went wrong...') }
   }); 
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/OptionalSubjectMapping/optionalSubjectContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: studentToOptionalSubjectMapping.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Interface
//Define Module, Access  Added
//
//*****************  Version 4  *****************
//User: Arvind       Date: 9/11/08    Time: 1:11p
//Updated in $/Leap/Source/Interface
//added common messages
//
//*****************  Version 3  *****************
//User: Arvind       Date: 8/29/08    Time: 3:27p
//Updated in $/Leap/Source/Interface
//formatted the code
//
//*****************  Version 1  *****************
//User: Arvind       Date: 8/28/08    Time: 8:05p
//Created in $/Leap/Source/Interface
//added a new file for student to optional subject mapping
?>