<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Change Password Form
//
//
// Author :Arvind Singh Rawat
// Created on : 09-Sept-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ChangePassword');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);  
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Change Password Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script type="text/javascript">

 var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button       
//This function Displays Div Window
//This function Validates Form 

function validateAddForm(frm, act) {
   
    var fieldsArray = new Array(new Array("currentPassword","<?php echo ENTER_CURRENT_PASSWORD;?>"),
                                new Array("newPassword","<?php echo ENTER_NEW_PASSWORD;?>"),
                                new Array("confirmPassword","<?php echo ENTER_NEW_PASSWORD_AGAIN;?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else { 
			if((eval("frm."+(fieldsArray[i][0])+".value.length"))<6 && fieldsArray[i][0]=='newPassword' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo PASSWORD_LENGTH_CHARACTERS;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
		 
	    
        
		}
    }
	if(trim(document.addPassword.newPassword.value) != trim(document.addPassword.confirmPassword.value)){
		messageBox("<?php echo PASSWORD_CHARACTERS_VALUE_CHECK ;?>");
		return false;
		
	}        
	addChangePassword();
    return false;
    
}

//This function adds form through ajax 

                                                                 
function addChangePassword() {
         url = '<?php echo HTTP_LIB_PATH;?>/AdminChangePassword/ajaxInitAdd.php';
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {currentPassword: (document.addPassword.currentPassword.value), userPassword: (document.addPassword.newPassword.value)},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
              
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
							 flag = true;
							 messageBox("<?php echo PASSWORD_CHANGED; ?>");
							
							 hiddenFloatingDiv('ChangePassword');
								if("<?php echo CURRENT_PROCESS_FOR;?>" == "cc"){   
								redirectURL("<?php echo UI_HTTP_PATH; ?>"+"/index.php");
								}
								else if("<?php echo CURRENT_PROCESS_FOR;?>" == "sc"){
								redirectURL("<?php echo UI_HTTP_PATH; ?>"+"/Parent/scIndex.php");
								}  
							 return false;
                     }
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
               
             },
			  onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function blankValues() {
   document.addPassword.currentPassword.value = '';
   document.addPassword.newPassword.value = '';
   document.addPassword.confirmPassword.value = '';
   
   document.addPassword.currentPassword.focus();
}
window.onload=function popUpDiv(){
displayWindow('ChangePassword',350,200);
blankValues();
}

//overriding default functions
function hiddenFloatingDiv(divId){
    
    document.getElementById(divId).style.visibility='hidden';
    document.getElementById('modalPage').style.display = "none";
    DivID = "";
    
    if("<?php echo CURRENT_PROCESS_FOR;?>" == "cc"){   
      redirectURL("<?php echo UI_HTTP_PATH; ?>"+"/Parent/index.php");
    }
    else if("<?php echo CURRENT_PROCESS_FOR;?>" == "sc"){
        redirectURL("<?php echo UI_HTTP_PATH; ?>"+"/Parent/scIndex.php");
    }  
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/AdminChangePassword/listChangePasswordContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
//$History: changePassword.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 4/07/10    Time: 3:17p
//Updated in $/LeapCC/Interface
//management access right added
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/21/09    Time: 6:16p
//Updated in $/LeapCC/Interface
//Gurkeerat: resolved issue 1199
//
//*****************  Version 3  *****************
//User: Administrator Date: 3/06/09    Time: 17:22
//Updated in $/LeapCC/Interface
//Done these modifications :
//
//1. My Time Table in Teacher: Add a link in the cell of Period/Day in My
//Time Table of teacher module, that takes the teacher to Daily
//Attendance interface and sets the value in Class, Subject,  and group
//DDMs from the time table. however, teacher will need to select Date and
//Period manually.
//
//2. Student Info in Teacher: Please add just "And/Or" between Name and
//Roll No search text boxes.
//
//3. Department wise Employee Selection in send messages links in teacher
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/22/08   Time: 5:39p
//Created in $/LeapCC/Interface
//Intial Checkin
?>
