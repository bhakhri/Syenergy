<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Change Password Form
//
//
// Author :Jaineesh
// Created on : 09-09-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentChangePassword');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();

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
   
    var fieldsArray = new Array(new Array("currentPassword","<?php echo ENTER_CURRENT_PASSWORD;?>"),new Array("newPassword","<?php echo ENTER_NEW_PASSWORD;?>"),new Array("confirmPassword","<?php echo ENTER_NEW_PASSWORD_AGAIN;?>"));

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
		 /*if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_ALPHABETS;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }*/
	    
        
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
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitAddPassword.php';
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {currentPassword: (document.addPassword.currentPassword.value), userPassword: (document.addPassword.newPassword.value)},
             onCreate: function() {
			 	showWaitDialog();
			 },
			 onSuccess: function(transport){
              
                     hideWaitDialog();
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
							 flag = true;
							 messageBox("<?php echo PASSWORD_CHANGED; ?>");
							 hiddenFloatingDiv('ChangePassword');
							 if("?php echo CURRENT_PROCESS_FOR ?>" == "cc") {
							 location.href="index.php";
							 return false;
							 }
							 if("?php echo CURRENT_PROCESS_FOR ?>" == "sc") {
							 location.href="scIndex.php";
							 return false;
							 }
                     }
                     else {
                        messageBox(trim(transport.responseText)); 
						if (trim(transport.responseText) == "<?php echo OLD_PASSWORD_CHECK ?>") {
							document.addPassword.currentPassword.value='';
							document.addPassword.currentPassword.focus();
						}
						//alert(transport.responseText);
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
      redirectURL("<?php echo UI_HTTP_PATH; ?>"+"/Student/index.php");
    }
    else if("<?php echo CURRENT_PROCESS_FOR;?>" == "sc"){
        redirectURL("<?php echo UI_HTTP_PATH; ?>"+"/Student/scIndex.php");
    }  
}
</script>

</head>
<body>
    <?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/Student/studentChangePasswordContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
//$History: changeStudentPassword.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:27p
//Updated in $/LeapCC/Interface/Student
//added access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Student
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:13p
//Updated in $/Leap/Source/Interface/Student
//remove the space
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/23/08    Time: 11:37a
//Updated in $/Leap/Source/Interface/Student
//fixed bug 
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/19/08    Time: 2:58p
//Updated in $/Leap/Source/Interface/Student
//fixed bug
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 9/16/08    Time: 7:07p
//Updated in $/Leap/Source/Interface/Student
//modifcation for alphabetic characters
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/12/08    Time: 2:18p
//Created in $/Leap/Source/Interface/Student
//used file for student password
//
//*****************  Version 2  *****************
//User: Arvind       Date: 9/09/08    Time: 6:18p
//Updated in $/Leap/Source/Interface/Parent
//modify
?>