<?php
//used for showing class wise grades
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ClassWiseGradeList');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
//require_once(BL_PATH . "/Teacher/StudentActivity/ajaxInitData.php");  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Marks </title>
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
//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function getData(){
    /*
    if(isEmpty(document.getElementById('studentRollNo').value)){
      if((document.getElementById('class').value != "") && (document.getElementById('subject').value != "") && (document.getElementById('group').value != "")){
          getClassGradeList();  
       }
      else{
           alert("<?php echo DISPLAY_GRADES_SELECT_STUDENT_LIST; ?>");
           document.getElementById('class').focus();
      } 
    }
   else{
       getClassGradeList();
       //document.getElementById('studentRollNo').value='';   
   } 
  */ 
    if((document.getElementById('classes').value != "") && (document.getElementById('subject').value != "") && (document.getElementById('group').value != "")){
          getClassGradeList();
		  document.getElementById('print').style.display = '';
       }
      else{
           alert("<?php echo DISPLAY_GRADES_SELECT_STUDENT_LIST; ?>");
           document.getElementById('classes').focus();
    } 
}

//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function  getClassGradeList(){
    url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxClassGradeList.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {classId:(document.getElementById('classes').value), 
                 subjectId: (document.searchForm.subject.value), 
                 groupId: (document.searchForm.group.value), 
                 studentRollNo: (trim(document.searchForm.studentRollNo.value)),
                 studentName: (trim(document.searchForm.studentName.value)),
                 sortField : document.getElementById('sorting').value,
                 sortBy : document.searchForm.ordering[0].checked==true?1:0 
                },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     document.getElementById('results').innerHTML=trim(transport.responseText)
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
    
    
}

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh 
// Created on : (12.03.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function groupPopulate(value) {
   url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGroupPopulate.php';
   document.searchForm.group.options.length=0;
  var objOption = new Option("Select Group","");
  document.searchForm.group.options.add(objOption); 
   
   if(document.getElementById('subject').value==""){
       return false;
   }

 new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 subjectId: document.getElementById('subject').value,
                 classId: document.getElementById('classes').value
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
				    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 

					 var r=1;
                     var tname='';

                     for(var c=0;c<j.length;c++){
						 
						 var objOption = new Option(j[c].groupName,j[c].groupId);
                         document.searchForm.group.options.add(objOption);
					 }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}



//to populate subjects based on choosen class
function populateSubjects(classId){
    document.getElementById('subject').options.length=1;
    document.getElementById('group').options.length=1;
    
    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetSubjects.php';
    
    if(classId==''){
      return false;
    }
    
     new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 classId: classId
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 
                    for(var c=0;c<j.length;c++){
                      if(j[c].hasMarks==1) {           
                        var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                        document.searchForm.subject.options.add(objOption);
                      }
                   }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

function printReport() {

	if((document.getElementById('classes').value == "") && (document.getElementById('subject').value == "") && (document.getElementById('group').value == "")){
	   alert("<?php echo DISPLAY_GRADES_SELECT_STUDENT_LIST; ?>");
	   document.getElementById('classes').focus();
    }

	form = document.searchForm;
    var sortField=document.getElementById('sorting').value;
    var sortBy = document.searchForm.ordering[0].checked==true?1:0 ;
	path='<?php echo UI_HTTP_PATH;?>/classWiseGradeReport.php?subjectId='+form.subject.value+'&groupId='+form.group.value+'&classId='+form.classes.value+'&studentName='+document.getElementById('studentName').value+'&studentRollNo='+document.getElementById('studentRollNo').value+'&subjectName='+form.subject[form.subject.selectedIndex].text+'&groupName='+form.group[form.group.selectedIndex].text+'&className='+form.classes[form.classes.selectedIndex].text+'&sortField='+sortField+'&sortBy='+sortBy;
   hideUrlData(path,true);
}

function printCSV() {
    if((document.getElementById('classes').value == "") && (document.getElementById('subject').value == "") && (document.getElementById('group').value == "")){
	   alert("<?php echo DISPLAY_GRADES_SELECT_STUDENT_LIST; ?>");
	   document.getElementById('classes').focus();
    }

	form = document.searchForm;
    var sortField=document.getElementById('sorting').value;
    var sortBy = document.searchForm.ordering[0].checked==true?1:0 ;
	path='<?php echo UI_HTTP_PATH;?>/classWiseGradeCSV.php?subjectId='+form.subject.value+'&groupId='+form.group.value+'&classId='+form.classes.value+'&studentName='+document.getElementById('studentName').value+'&studentRollNo='+document.getElementById('studentRollNo').value+'&subjectName='+form.subject[form.subject.selectedIndex].text+'&groupName='+form.group[form.group.selectedIndex].text+'&className='+form.classes[form.classes.selectedIndex].text+'&sortField='+sortField+'&sortBy='+sortBy;

	window.location = path;
}

//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO clear rollno when clas,subject or group is changed
//
//Author : Dipanjan Bhattacharjee
// Created on : (07.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function deleteRollNo(){
   //document.searchForm.studentRollNo.value=""; 
}

window.onload=function(){
    document.getElementById('classes').focus();
    var roll = document.getElementById("studentRollNo");
     autoSuggest(roll);
}
</script>

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listClasswiseGradeContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");    
?>
</body>
</html>
