<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GroupCopy');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Copy Groups</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


//-------------------------------------------------------
//THIS FUNCTION IS USED TO fetch previous classes
//Author : Dipanjan Bhattacharjee
// Created on : (23.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function getPreviousClass(classId) {
         
         var sourceClassId=document.getElementById('sourceClassId');
         sourceClassId.options.length=1;
         
         if(classId==''){
             return false;
         }
         var url = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxGetPreviousClass.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 classId: classId
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var j = eval('(' + trim(transport.responseText) + ')');
                     var len=j.length;
                     for(i=0;i<len;i++){
                        addOption(sourceClassId, j[i].classId, j[i].className);
                     }
                     
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO fetch previous classes
//Author : Dipanjan Bhattacharjee
// Created on : (23.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function getActiveClassesWithNoGroups() {
         
         var targetClassId=document.getElementById('targetClassId');
         targetClassId.options.length=1;

         var url = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxActiveClassesWithNoGroups.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 1: 1
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var j = eval('(' + trim(transport.responseText) + ')');
                     var len=j.length;
                     for(i=0;i<len;i++){
                        addOption(targetClassId, j[i].classId, j[i].className);
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO copy groups
//Author : Dipanjan Bhattacharjee
// Created on : (23.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function copyGroups() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxCopyGroups.php';
         
         var sourceClassId=document.getElementById('sourceClassId').value;
         var targetClassId=document.getElementById('targetClassId').value;
         if(targetClassId==''){
             messageBox("<?php echo SELECT_TARGET_CLASS_FOR_GROUP_COPY; ?>");
             document.getElementById('targetClassId').focus();
             return false;
         }
         if(sourceClassId==''){
             messageBox("<?php echo SELECT_SOURCE_CLASS_FOR_GROUP_COPY; ?>");
             document.getElementById('sourceClassId').focus();
             return false;
         }
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                  sourceClassId : sourceClassId,
                  targetClassId : targetClassId
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         messageBox("<?php echo GROUP_COPY_SUCCESS;?>");
                         getActiveClassesWithNoGroups();
                         document.getElementById('sourceClassId').options.length=1;
                         return false;
                     }
                     else if("<?php echo SOURCE_CLASS_MISSING;?>" == trim(transport.responseText)){
                         messageBox("<?php echo SOURCE_CLASS_MISSING ;?>"); 
                         document.getElementById('sourceClassId').focus();
                     }
                     else if("<?php echo TARGET_CLASS_MISSING;?>" == trim(transport.responseText)){
                         messageBox("<?php echo TARGET_CLASS_MISSING ;?>"); 
                         document.getElementById('targetClassId').focus();
                     }
                     else if("<?php echo NO_PARENT_CLASS_FOUND;?>" == trim(transport.responseText)){
                         messageBox("<?php echo NO_PARENT_CLASS_FOUND ;?>"); 
                         document.getElementById('targetClassId').focus();
                     }
                     else if("<?php echo INVALID_PARENT_CLASS_RESTRICTION;?>" == trim(transport.responseText)){
                         messageBox("<?php echo INVALID_PARENT_CLASS_RESTRICTION ;?>"); 
                         document.getElementById('sourceClassId').focus();
                     }
                     else if("<?php echo SOURCE_CLASS_WITH_NO_GROUPS;?>" == trim(transport.responseText)){
                         messageBox("<?php echo SOURCE_CLASS_WITH_NO_GROUPS ;?>"); 
                         document.getElementById('sourceClassId').focus();
                     }
                     else if("<?php echo GROUP_ALREADY_ALLOCATED_TO_TARGET_CLASS;?>" == trim(transport.responseText)){
                         messageBox("<?php echo GROUP_ALREADY_ALLOCATED_TO_TARGET_CLASS ;?>"); 
                         document.getElementById('targetClassId').focus();
                     }
                     else if("<?php echo INSTITUTE_SESSION_INFO_MISSING_FOR_TARGET_CLASS;?>" == trim(transport.responseText)){
                         messageBox("<?php echo INSTITUTE_SESSION_INFO_MISSING_FOR_TARGET_CLASS ;?>"); 
                         document.getElementById('sourceClassId').focus();
                     }
                     else if("<?php echo SAME_CLASS_RESTRICTION;?>" == trim(transport.responseText)){
                         messageBox("<?php echo SAME_CLASS_RESTRICTION ;?>"); 
                         document.getElementById('sourceClassId').focus();
                     }
                     else if("<?php echo INVALID_PARENT_CLASS_RESTRICTION;?>" == trim(transport.responseText)){
                         messageBox("<?php echo INVALID_PARENT_CLASS_RESTRICTION ;?>"); 
                         document.getElementById('sourceClassId').focus();
                     }
                     else if("<?php echo SOURCE_CLASS_WITH_NO_GROUPS;?>" == trim(transport.responseText)){
                         messageBox("<?php echo SOURCE_CLASS_WITH_NO_GROUPS ;?>"); 
                         document.getElementById('sourceClassId').focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.getElementById('sourceClassId').focus();                         
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
	require_once(TEMPLATES_PATH . "/Group/copyGroupsContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: copyGroups.php $ 
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/12/09   Time: 14:59
//Updated in $/LeapCC/Interface
//Corrected messages
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 23/12/09   Time: 19:15
//Created in $/LeapCC/Interface
//Done group coping module
?>