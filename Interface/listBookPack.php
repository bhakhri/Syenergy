<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BookPack');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Book Pack Master</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(
                                new Array('srNo','#','width="2%"','',false), 
                                new Array('students','<input type="checkbox" name="allStudents" id="allStudents" onclick="selectAllStudents(this.checked);" />','width="3%"','',false),
                                new Array('studentName','Name','width="30%"','',true), 
                                new Array('rollNo','Roll No.','width="15%"','',true), 
                                new Array('universityRollNo','Univ. Roll No.','width="15%"','',true),
                                new Array('universityRegNo','Univ. Regn. No.','width="15%"','',true)
                               );

//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
recordsPerPage = 2000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/BookPack/ajaxInitList.php';
searchFormName = 'searchForm2'; // name of the form which will be used for search
/*
addFormName    = 'AddMapping';   
editFormName   = 'EditMapping';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteMapping';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}

function selectAllStudents(state){
    var students=document.getElementById(divResultName).getElementsByTagName('INPUT');
    var cnt=students.length;
    
    for(var i=0;i<cnt;i++){
        if(students[i].name=='students'){
            students[i].checked=state;
        }
    }  
}

function validateForm() {
    if(document.searchForm2.timeTableId.value==''){
       messageBox("<?php echo SELECT_TIME_TABLE; ?>");
       document.searchForm2.timeTableId.focus();
       return false;
    }
    
    var classId=document.searchForm2.classId;
    if(classId.value==''){
        messageBox("<?php echo SELECT_CLASS; ?>");
        classId.focus();
        return false;
    }
    
    var studentIds='';
    var notSelectedStudentIds='';
    var students=document.getElementById(divResultName).getElementsByTagName('INPUT');
    var cnt=students.length;
    if(cnt==0){
       messageBox("<?php echo NO_DATA_SUBMIT; ?>");
       return false;
    }
    var fl=0;
    for(var i=0;i<cnt;i++){
        if(students[i].name=='students'){
            if(students[i].checked==true){
                fl=1;
                if(studentIds!=''){
                    studentIds +=',';
                }
                studentIds +=students[i].value;
            }
            else{
                if(notSelectedStudentIds!=''){
                    notSelectedStudentIds +=',';
                }
                notSelectedStudentIds +=students[i].value;
            }
        }
    }
    
    if(fl==0){
       if(!confirm("No books will be packed for students of this class\nAre you sure?")){
           return false;
       }
    }
    
     var url = '<?php echo HTTP_LIB_PATH;?>/BookPack/doBooksPack.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             classId               : classId.value,
             studentIds            : studentIds,
             notSelectedStudentIds : notSelectedStudentIds
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     vanishData();
                     if(fl==0){
                         messageBox("No books are packed for students of this class");
                     }
                     else{
                         messageBox("Books packed successfully");    
                     }
                     document.searchForm2.timeTableId.selectedIndex=0;
                     document.searchForm2.classId.options.length=1;
                 }
                 else {
                    messageBox(trim(transport.responseText));
                 }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
       });
    
}

function fetchData() {
   if(document.searchForm2.timeTableId.value==''){
       messageBox("<?php echo SELECT_TIME_TABLE; ?>");
       document.searchForm2.timeTableId.focus();
       return false;
   }
   var classId=document.searchForm2.classId;
   if(classId.value==''){
        messageBox("<?php echo SELECT_CLASS; ?>");
        classId.focus();
        return false;
   }
   
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
   document.getElementById('saveTrId').style.display='';
   return false;
}

function getClasses(value){
    
    document.searchForm2.classId.options.length=1;
    if(value==''){
        return false
    }
     
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetClass.php';
    var pars = 'timeTable='+document.searchForm2.timeTableId.value;
    
    new Ajax.Request(url,
       {
         method:'post',
         parameters: pars,
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
               hideWaitDialog(true);
                var j = eval('(' + trim(transport.responseText) + ')');
                var len = j.length;
                for(i=0;i<len;i++) {
                    addOption(document.searchForm2.classId, j[i].classId, j[i].className);
                }
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });

      // hideResults();
}

function vanishData(){
    document.getElementById(divResultName).innerHTML='';
    document.getElementById('saveTrId').style.display='none';
}

</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/BookPack/listBookPackContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>