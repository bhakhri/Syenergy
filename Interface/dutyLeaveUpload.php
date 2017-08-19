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
define('MODULE','DutyLeaveUpload');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Upload Duty Leave Entries </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


var  valShow=0;
function checkAllowdExtensions(value){
  var val=value.substring(value.lastIndexOf('.')+1,value.length);
  var str="xls";
  var extArr=str.split(",");
  var fl=0;
  var ln=extArr.length;
  for(var i=0; i <ln; i++){
      if(val.toUpperCase()==extArr[i].toUpperCase()){
          fl=1;
          break;
      }
  }
 if(fl){
   return true;
  }
 else{
  return false;
 }   
}


function initAdd() {
    
    if(trim(document.getElementById('labelId').value)==''){
        messageBox("<?php echo SELECT_TIME_TABLE; ?>");
        document.getElementById('labelId').focus();
        return false;
    }
    
    if(trim(document.getElementById('eventId').value)==''){
        messageBox("<?php echo SELECT_DUTY_LEAVE_EVENT?>");
        document.getElementById('eventId').focus();
        return false;
    }
    
    if(trim(document.getElementById('dutyLeaveFile').value)==''){
        messageBox("<?php echo SELECT_FILE_FOR_UPLOAD?>");
        document.getElementById('dutyLeaveFile').focus();
        return false;
    }
    if(!checkAllowdExtensions(trim(document.getElementById('dutyLeaveFile').value))){
        document.getElementById('dutyLeaveFile').focus();  
        messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
        return false;
    }
    showWaitDialog(true);
    document.getElementById('uploadForm').onsubmit=function() {
       document.getElementById('uploadForm').target = 'uploadTargetAdd';
    }
}


function getDutyEvent(val) {
         vanishData();
         document.uploadForm.eventId.options.length=1;
         if(val==''){
             return false;
         }
         var url = '<?php echo HTTP_LIB_PATH;?>/DutyLeave/getDutyEvents.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 labelId: val
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true); 
                    var j = eval('('+trim(transport.responseText)+')');
                    var len=j.length;
                    for(var i=0;i<len;i++){
                       addOption(document.uploadForm.eventId,j[i].eventId,j[i].eventTitle);
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
           
}

function fileUploadError(str){
  hideWaitDialog(true);
  if(str==0){ //inconsistency 
      window.location = '<?php echo UI_HTTP_PATH;?>/uploadDutyLeaveStatus.php?mode=0';
  }
  else if(str==1){ //success
      window.location = '<?php echo UI_HTTP_PATH;?>/uploadDutyLeaveStatus.php?mode=1';
  }
  else{
     alert(str); 
  }
}

function vanishData(){
    document.getElementById('dutyLeaveFile').value='';
}


window.onload = function () {
   getShowDetail(); 
}



function getShowDetail() {

   document.getElementById("idSubjects").innerHTML="Expand Sample Format for .xls file and instructions"; 
   document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"
   document.getElementById("showSubjectEmployeeList11").style.display='none';
   if(valShow==1) {
     document.getElementById("showSubjectEmployeeList11").style.display='';
     document.getElementById("idSubjects").innerHTML="Collapse Sample Format for .xls file and instructions";
     document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif"
     valShow=0;
   }
   else {
     valShow=1;  
   }
}


</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/DutyLeave/dutyLeaveUploadContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listEvent.php $ 
?>
