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
define('MODULE','PlacementUploadStudentDetail');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Upload Student Details </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
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
    
    if(trim(document.getElementById('placementDriveId').value)==''){
        messageBox("<?php echo SELECT_PLACEMENT_DRIVE?>");
        document.getElementById('placementDriveId').focus();
        return false;
    }
    if(trim(document.getElementById('studentFile').value)==''){
        messageBox("<?php echo SELECT_FILE_FOR_UPLOAD?>");
        document.getElementById('studentFile').focus();
        return false;
    }
    if(!checkAllowdExtensions(trim(document.getElementById('studentFile').value))){
        document.getElementById('studentFile').focus();  
        messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
        return false;
    }
    showWaitDialog(true);
    document.getElementById('uploadForm').onsubmit=function() {
       document.getElementById('uploadForm').target = 'uploadTargetAdd';
    }
}

function getData() {
	  value = document.getElementById('placementDriveId').value;

	  if(value=='') {
        return false;
	  }
	    var url = '<?php echo HTTP_LIB_PATH;?>/Placement/Drive/ajaxGetCriteriaMarks.php'; 
   new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 placementDriveId: (value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var j=trim(transport.responseText);
					 document.getElementById('summeryDiv').style.display='';
 					 if(j == 1) {
					   document.getElementById('td1').innerHTML = '<b>BE/ B.Tech (%)</b>';
					   document.getElementById('showMarks').innerHTML = '<b>BE/ B.Tech</b>';
					   document.getElementById('marksIds').value = '1';
					 }
					 else {
                       document.getElementById('td1').innerHTML= '<b>Graduation (%)</b>';
					   document.getElementById('showMarks').innerHTML = '<b>Graduation</b>';
  					   document.getElementById('marksIds').value = '2';
					 }
				   }, 
			 
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           }); 
		}

  
function fileUploadError(str){
  hideWaitDialog(true);
  if(str==0){ //inconsistency 
      window.location = '<?php echo UI_HTTP_PATH;?>/Placement/uploadStudentStatus.php?mode=0';
  }
  else if(str==1){ //success
      window.location = '<?php echo UI_HTTP_PATH;?>/Placement/uploadStudentStatus.php?mode=1';
  }
  else{
     alert(str); 
  }
  
}

function vanishData(){
    document.getElementById('studentFile').value='';
}


window.onload=function(){
  getData();
}

</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Placement/Student/studentDetailUploadContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listEvent.php $ 
?>