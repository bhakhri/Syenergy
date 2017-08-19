<?php
//used for showing teacher dashboard
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SearchStudentDisplay');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
//require_once(BL_PATH . "/Teacher/StudentActivity/ajaxInitData.php");  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Info </title>
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

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
                                                                               
var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
new Array('studentName','Name','width="20%"','',true) , 
new Array('rollNo','Roll No.','width="10%"','',true), 
new Array('universityRollNo','Univ. Roll No.','width="12%"','',true), 
new Array('cityName','City','width="15%"','',true) ,
new Array('className','Class','width="15%"','',true) ,
new Array('degreeAbbr','Degree','width="10%"','',false) ,
new Array('branchCode','Branch','width="10%"','',false) ,
new Array('batchName','Batch','width="8%"','',false) ,
new Array('details','Details','width="10%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE_TEACHER;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/StudentActivity/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
/*
addFormName    = 'AddBlock';   
editFormName   = 'EditBlock';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBlock';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'firstName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

function openUrl(id){
 //alert(id);
 var qstr="&class="+document.getElementById('class').value+"&subject="+document.getElementById('subject').value;
 qstr=qstr+"&group="+document.getElementById('group').value+"&studentRollNo="+trim(document.getElementById('studentRollNo').value)+"&studentNameFilter="+trim(document.getElementById('studentNameFilter').value)
 qstr=qstr+"&page="+page+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
 hideUrlData("studentDetail.php?id="+id+qstr,"_parent",false)
 // var w=window.open("studentDetail.php?id="+id+qstr,"_parent");   //opens details(default) page
  
}


//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function getData(){
   //if( (document.getElementById('class').value != "" && document.getElementById('subject').value != "" && document.getElementById('group').value != "") || document.getElementById('studentNameFilter').value != "" || document.getElementById('studentRollNo').value != ""){
   if( document.getElementById('class').value != "" && document.getElementById('subject').value != "" && document.getElementById('group').value != "" ){
     // document.getElementById('printDiv1').style.display='none';  
      sendReq(listURL,divResultName,searchFormName,'');  
    //  document.getElementById('printDiv1').style.display='block';
   }
  else{
       messageBox("<?php echo SEARCH_STUDENT_SELECT_STUDENT_LIST; ?>");
       document.getElementById('class').focus();
  }
}


/* function to print all student report*/
function printReport() {
   if( document.getElementById('class').value != "" && document.getElementById('subject').value != "" && document.getElementById('group').value != "" ){
    }
   else{
       messageBox("<?php echo SEARCH_STUDENT_SELECT_STUDENT_LIST; ?>");
       document.getElementById('class').focus();
       return false;
    }
  
    //var queryString = '&subject='+sortOrderBy+'&section='+sortField+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //alert(queryString);
    var qstr="&class="+document.getElementById('class').value+"&subject="+document.getElementById('subject').value;
    qstr=qstr+"&className="+document.getElementById('class').options[document.getElementById('class').selectedIndex].text;
    qstr=qstr+"&subjectName="+document.getElementById('subject').options[document.getElementById('subject').selectedIndex].text;
    qstr=qstr+"&group="+document.getElementById('group').value+"&studentRollNo="+trim(document.getElementById('studentRollNo').value)+"&studentNameFilter="+trim(document.getElementById('studentNameFilter').value);
    qstr=qstr+"&groupName="+document.getElementById('group').options[document.getElementById('group').selectedIndex].text;
    qstr=qstr+"&studentNameFilter="+trim(document.getElementById('studentNameFilter').value);
        
    qstr=qstr+"&page="+page+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;

    path='<?php echo UI_HTTP_PATH;?>/Teacher/searchStudentReportPrint.php?listStudent=1'+qstr;
    if( (document.getElementById('subject').value != "" && document.getElementById('group').value != "") || document.getElementById('class').value != "" || document.getElementById('studentNameFilter').value != "" || document.getElementById('studentRollNo').value != ""){
     window.open(path,"StudentReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
   else{
       messageBox("<?php echo SEARCH_STUDENT_SELECT_STUDENT_LIST; ?>");
       document.getElementById('class').focus();
   }  
    
}

/* function to print all student report*/
function printStudentCSV() {
    if( document.getElementById('class').value != "" && document.getElementById('subject').value != "" && document.getElementById('group').value != "" ){
    }
    else{
       messageBox("<?php echo SEARCH_STUDENT_SELECT_STUDENT_LIST; ?>");
       document.getElementById('class').focus();
       return false;
    }
    
    var qstr="&class="+document.getElementById('class').value+"&subject="+document.getElementById('subject').value;
    qstr=qstr+"&className="+document.getElementById('class').options[document.getElementById('class').selectedIndex].text;
    qstr=qstr+"&subjectName="+document.getElementById('subject').options[document.getElementById('subject').selectedIndex].text;
    qstr=qstr+"&group="+document.getElementById('group').value+"&studentRollNo="+trim(document.getElementById('studentRollNo').value)+"&studentNameFilter="+trim(document.getElementById('studentNameFilter').value);
    qstr=qstr+"&groupName="+document.getElementById('group').options[document.getElementById('group').selectedIndex].text;
    qstr=qstr+"&studentNameFilter="+trim(document.getElementById('studentNameFilter').value);
        
    qstr=qstr+"&page="+page+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    
    if( (document.getElementById('subject').value != "" && document.getElementById('group').value != "") || document.getElementById('class').value != "" || document.getElementById('studentNameFilter').value != "" || document.getElementById('studentRollNo').value != ""){
     //document.getElementById('generateCSV').href='searchStudentReportCSV.php?queryString='+qstr;
     window.location='searchStudentReportCSV.php?queryString='+qstr;
    }
   else{
       messageBox("<?php echo SEARCH_STUDENT_SELECT_STUDENT_LIST; ?>");
       document.getElementById('class').focus();
   }   
}

//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO get parameters from query string
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function getParams(clas,group,subject,rollNo,name){
  var fl=1;
  if(clas!="" || group!="" || subject!="" || rollNo !="" || name!=""){
    document.getElementById('studentRollNo').value=trim(rollNo);  
    document.getElementById('studentNameFilter').value=trim(name);
    document.getElementById('class').value=clas;
    populateSubjects(clas);
    document.getElementById('subject').value=subject;
    groupPopulate(subject);  
    document.getElementById('group').value=group;
 }
 else{
     fl=0;
 }
 if(fl==1){
   sendReq(listURL,divResultName,searchFormName,'page=<?php echo trim($REQUEST_DATA['page']); ?>&sortOrderBy=<?php echo trim($REQUEST_DATA['sortOrderBy']); ?>&sortField=<?php echo trim($REQUEST_DATA['sortField']); ?>');
   //document.getElementById('printDiv1').style.display='block';
 }
}


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
                      if(j[c].hasAttendance==1) {
                        var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                        document.searchForm.subject.options.add(objOption);
                      }
                    }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

function groupPopulate(value) {
   url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGroupPopulate.php';
   document.searchForm.group.options.length=0;
   var objOption = new Option("Select Group","");
   document.searchForm.group.options.add(objOption); 
   
   if(document.getElementById('subject').value==""){
       return false;
   }
   if(document.getElementById('class').value==""){
       return false;
   }
   

 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 subjectId: document.getElementById('subject').value,
                 classId  : document.getElementById('class').value
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


function washoutData(){
   // document.getElementById('printDiv1').style.display='none';
    document.getElementById('results').innerHTML='';
    
}
window.onload=function(){
    document.getElementById('class').focus();
    var roll = document.getElementById("studentRollNo");
    
    autoSuggest(roll);
    getParams("<?php print($REQUEST_DATA['class']); ?>","<?php print($REQUEST_DATA['group']); ?>","<?php print($REQUEST_DATA['subject']); ?>","<?php print($REQUEST_DATA['studentRollNo']); ?>","<?php print($REQUEST_DATA['studentNameFilter']); ?>");
    
}
</script>

</head>
<body>
<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/StudentActivity/searchStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");    
?>
</body>
</html>