<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ApproveStudentMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Approve Student Registration</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('studentName','Student Name','width="12%"','',true) , 
                               new Array('className','Class','width="25%"','',true), 
                               new Array('emailId','Email','width="10%"','',true) , 
                               new Array('phNo','Ph.No.','width="10%"','',true) , 
                               new Array('address','Address','width="25%"','',true) , 
                               new Array('actionString','Action','width="10%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/ApproveStudent/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
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


function fetchData(){
    if(document.getElementById('classId').value==''){
        messageBox("<?php echo SELECT_CLASS;?>");
        document.getElementById('classId').focus();
        return false; 
    }
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
  document.getElementById('buttonDivId').style.display='';
}

function vanishData(){
  document.getElementById('buttonDivId').style.display='none';
  document.getElementById(divResultName).innerHTML='';
}

function saveData() {
         var url = '<?php echo HTTP_LIB_PATH;?>/ApproveStudent/changeStudentStatus.php';
         
         var ele=document.getElementById(divResultName).getElementsByTagName('select');
         var eleLength=ele.length;
         if(eleLength==0){
             messageBox("<?php echo NO_DATA_SUBMIT;?>");
             return false;
         }
         
         var studentString='';
         for(var i=0;i<eleLength;i++){
             if(studentString!=''){
                 studentString +=',';
             }
             studentString +=ele[i].id+'_'+ele[i].value;
         }
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                   studentString : studentString
                 },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         messageBox("<?php echo SUCCESS; ?>");
                         vanishData();
                         document.getElementById('classId').selectedIndex=0;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/ApproveStudent/listApproveStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 