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
define('MODULE','ChangeStudentBranch');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Change Student Branch </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                               new Array('srNo','#','width="1%"','',false), 
                               new Array('studentName','Name','width="15%"','',true) , 
                               new Array('rollNo','Roll No.','width="8%"','',true) , 
                               new Array('universityRollNo','Univ. Roll No.','width="12%"','',true), 
                               new Array('newClassId','New Class / Branch','width="15%"','',false)
                              )

//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
recordsPerPage = 1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/ChangeStudentBranch/ajaxStudentList.php';
searchFormName = 'studentForm'; // name of the form which will be used for search
/*
  addFormName    = 'AddCity';   
  editFormName   = 'EditCity';
  winLayerWidth  = 315; //  add/edit form width
  winLayerHeight = 250; // add/edit form height
  deleteFunction = 'return deleteCity';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

var serverDate="<?php echo date('Y-m-d');?>";

function validateData(){
  /*  
   if(document.getElementById('labelId').value==''){
     messageBox("<?php echo SELECT_TIME_TABLE_LABEL;?>");
     document.getElementById('labelId').focus();
     return false;
   }
  */ 
   if(document.getElementById('classId').value==''){
     messageBox("<?php echo SELECT_CLASS;?>");
     document.getElementById('classId').focus();
     return false;
   }
   page=1; //default page
   sortField = 'studentName';
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
   document.getElementById('saveTrId').style.display='';
}

function hideResults(){
    document.getElementById('results').innerHTML='';
    document.getElementById('saveTrId').style.display='none';
}

function doBranchChange() {
   /* 
    if(document.getElementById('labelId').value==''){
     messageBox("<?php echo SELECT_TIME_TABLE_LABEL;?>");
     document.getElementById('labelId').focus();
     return false;
    }
   */ 
    if(document.getElementById('classId').value==''){
     messageBox("<?php echo SELECT_CLASS;?>");
     document.getElementById('classId').focus();
     return false;
    }
   
    var c1 = document.getElementById('results').getElementsByTagName('SELECT');
    var len=c1.length;
    var inputString='';
    for(var i=0;i<len;i++){
       if(c1[i].name=='newClass'){
           if(c1[i].value==''){
               messageBox("<?php echo SELECT_NEW_CLASS_BRANCH; ?>");
               c1[i].focus();
               return false;
           }
           if(inputString!=''){
               inputString +=',';
           }
           inputString +=c1[i].id+'_'+c1[i].value;
       } 
    }
    
    if(inputString==''){
       messageBox("<?php echo NO_DATA_SUBMIT; ?>");
       return false;
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/ChangeStudentBranch/doBranchChange.php';
    var pars = 'inputString='+inputString+'&classId='+document.getElementById('classId').value;
    new Ajax.Request(url,
    {
        method:'post',
        parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var ret = trim(transport.responseText);
            if(ret=="<?php echo SUCCESS;?>"){
                messageBox("<?php echo BRANCH_CHANGED_SUCCESSFULLY?>");
                hideResults();
            }
            else{
                messageBox(ret);
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
	require_once(TEMPLATES_PATH . "/ChangeStudentBranch/changeStudentBranchContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listTeacherAttendanceReport.php $ 
?>