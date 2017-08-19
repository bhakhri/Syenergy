<?php
//-------------------------------------------------------
// THIS FILE UPLOAD MEDICAL LEAVE OF STUDENTS
// Author : Aditi Miglani
// Created on : 20 Sept 2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','MedicalLeaveUpload');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Upload Medical Leave Entries </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


var  valShow=0;

//This function checks the extensions of the file uploaded
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

    if(trim(document.getElementById('medicalLeaveFile').value)==''){
        messageBox("<?php echo SELECT_FILE_FOR_UPLOAD?>");
        document.getElementById('medicalLeaveFile').focus();
        return false;
    }
    if(!checkAllowdExtensions(trim(document.getElementById('medicalLeaveFile').value))){
        document.getElementById('medicalLeaveFile').focus();  
        messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
        return false;
    }
    showWaitDialog(true);
    document.getElementById('uploadForm').onsubmit=function() {
    document.getElementById('uploadForm').target = 'uploadTargetAdd';
    }
}

function fileUploadError(str){
    hideWaitDialog(true);
    if(str==0){ //inconsistency 
        window.location = '<?php echo UI_HTTP_PATH;?>/uploadMedicalLeaveStatus.php?mode=0';
    }
     else if(str==1){ //success
        window.location = '<?php echo UI_HTTP_PATH;?>/uploadMedicalLeaveStatus.php?mode=1';
     }
     else{
        alert(str); 
    }
}

function vanishData(){
    document.getElementById('medicalLeaveFile').value='';
}

window.onload = function () {
    getShowDetail(); 
}

function getShowDetail() {
   document.getElementById("sampleFormat").innerHTML="Expand Sample Format for .xls file and instructions"; 
   document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"
   document.getElementById("showSampleFormat").style.display='none';
   if(valShow==1) {
     document.getElementById("showSampleFormat").style.display='';
     document.getElementById("sampleFormat").innerHTML="Collapse Sample Format for .xls file and instructions";
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
    require_once(TEMPLATES_PATH . "/MedicalLeave/medicalLeaveUploadContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
