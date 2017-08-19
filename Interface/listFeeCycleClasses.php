<?php
//-------------------------------------------------------
// Purpose: To generate Quota Seat Intake functionality
// Author : Parveen Sharma
// Created on : 27-01-09
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeCycleClasses');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Fee Cycle Class</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script type="text/javascript" language="javascript">
//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
recordsPerPage = 10000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeeCycleClasses/ajaxFeeCycleClassesGetValues.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
divResultName  = 'results';
page=1; //default page
sortField = 'className';
sortOrderBy    = 'ASC';                  
 
function refreshData() {
   var  url = '<?php echo HTTP_LIB_PATH;?>/FeeCycleClasses/ajaxFeeCycleClassesGetValues.php';  
   
   hideResults();
  
   if( document.getElementById('feeCycleId').value==""){
      messageBox ("<?php echo FEE_CYCLE;?>");
      document.getElementById('feeCycleId').focus();
      return false;
   } 
   
   var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%" align=\"center\"','align=\"center\"',false), 
                        new Array('className','Class Name','width="35%" align="left"',false),
                        new Array('mappedFeeCycle','Mapped To Fee Cycle','width="25%" align="left"',false),
                        new Array('classStatus','Class Status','width="15%" align="left"',false)
                       );

   document.getElementById("nameRow").style.display='';
   document.getElementById("nameRow2").style.display='';
   document.getElementById("resultRow").style.display='';
   
   //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
   listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','className','ASC','resultsDiv','','',true,'listObj1',tableColumns,'','','&feeCycleId='+document.getElementById('feeCycleId').value);
   sendRequest(url, listObj1, '',true);
   
   return false;
}

function doAll(){

    formx = document.allDetailsForm;
    if(formx.checkbox2.checked){
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
                formx.elements[i].checked=true;
            }
        }
    }
    else{
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox"){
                formx.elements[i].checked=false;
            }
        }
    }
}


function validateAddForm(frm) {

   var url = '<?php echo HTTP_LIB_PATH;?>/FeeCycleClasses/ajaxFeeCycleClassesAdd.php';
    
   if( document.getElementById('feeCycleId').value==""){
      messageBox ("<?php echo FEE_CYCLE;?>");
      document.getElementById('feeCycleId').focus();
      return false;
   }  
   
   saveClassId='';      
   cancelClassId='';
   
   formx = document.allDetailsForm;      
   for(var i=1;i<formx.length;i++) {  
      if(formx.elements[i].type=="hidden" && formx.elements[i].name=="chb2[]"){
         id = formx.elements[i].value;  
         if(eval("document.getElementById('chk_classId_"+id+"').checked")==true && eval("document.getElementById('txt_feeCycleId_"+id+"').value")==-1 ) {
            if(saveClassId=='') {  
              saveClassId=id; 
            }
            else {
              saveClassId = saveClassId +","+id;  
            }
         }
         else
          if(eval("document.getElementById('chk_classId_"+id+"').checked")==false && eval("document.getElementById('txt_feeCycleId_"+id+"').value")!=-1 ) {
            if(cancelClassId=='') {  
              cancelClassId=id; 
            }
            else {
              cancelClassId = cancelClassId +","+id;  
            }
         }
      } 
   } 
   
 //  if(saveClassId=='' && cancelClassId=='') {
 //    messageBox ("No option selected");    
 //     return false;
 //  }
   
   new Ajax.Request(url,
   {
     method:'post',
     parameters: {saveClassId: saveClassId,
                  cancelClassId: cancelClassId,
                  feeCycleId: document.getElementById('feeCycleId').value
                 },  
     asynchronous:false,                   
     onCreate: function () {
        showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText) || trim("<?php echo FEE_CYCLE_CLASS_ADDED_SUCCESSFULLY;?>") == trim(transport.responseText) || trim("<?php echo FEE_CYCLE_CLASS_UPDATE_SUCCESSFULLY;?>") == trim(transport.responseText) || trim("<?php echo FEE_CYCLE_CLASS_DELETE_SUCCESSFULLY;?>") == trim(transport.responseText)) { 
            messageBox(trim(transport.responseText)); 
            refreshData();
            return false;
        }
        else if(trim("<?php echo FEE_CYCLE;?>") == trim(transport.responseText)) {
           messageBox(trim(transport.responseText)); 
           return false; 
        }
        else if(trim("<?php echo FAILURE;?>") == trim(transport.responseText)) {
           messageBox(trim(transport.responseText)); 
           return false; 
        }
        else {
           var ret=trim(transport.responseText).split('~~');
           if(ret.length>0) {
              for(i=0;i<ret.length;i++) { 
                 id = "chk_classId_"+ret[i];
                 //alert(id);
                 eval("document.getElementById('"+id+"').className='inputboxRed'"); 
                 eval("document.getElementById('"+id+"').focus()");     
              }
              messageBox("<?php echo FEE_CYCLE_CLASS_DUPLICATE; ?>");     
           }
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}


function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}


function printReport() {
  
    path='<?php echo UI_HTTP_PATH;?>/listFeeCycleClassesReportPrint.php?&feeCycleId='+document.getElementById('feeCycleId').value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=900, height=500, top=100,left=50");
}

/* function to print all payment history to csv*/
function printReportCSV() {

    path='<?php echo UI_HTTP_PATH;?>/listFeeCycleClassesReportCSV.php?feeCycleId='+document.getElementById('feeCycleId').value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;  
    window.location=path;
}

function sendKeys(e,formname) {

    var ev = e||window.event;
    thisKeyCode = ev.keyCode;
    if (thisKeyCode == '13') {
       validateAddForm(this);
       return false;
    }
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FeeCycleClasses/listFeeCycleClassesContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
                