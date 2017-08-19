<?php
//-------------------------------------------------------
// Purpose: To generate the list of states from the database, and have add/edit/delete, search 
// functionality 
//
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisplayPhotoGallery');
define('ACCESS','view');
global $sessionHandler; 
$roleId = $sessionHandler->getSessionVariable('RoleId');

if($roleId=='2') { 
  UtilityManager::ifTeacherNotLoggedIn();    
}
else if($roleId=='3') { 
  UtilityManager::ifParentNotLoggedIn();  
}
else if($roleId=='4') { 
  UtilityManager::ifStudentNotLoggedIn();
}
else if($roleId=='5') { 
  UtilityManager::ifManagementNotLoggedIn();
}
else {
  UtilityManager::ifNotLoggedIn(); 
}
UtilityManager::headerNoCache();
//require_once(BL_PATH . "/States/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title><?php echo SITE_NAME;?>: Display Photo Gallery </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/PhotoGalleryReport/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddPhoto';   
editFormName   = 'AddPhoto';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deletePhoto';
divResultName  = 'results';
page=1; //default page
sortOrderBy    = 'ASC';

// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

function getPhotoGallery() {  
    
    url  = '<?php echo HTTP_LIB_PATH;?>/PhotoGalleryReport/ajaxInitList.php';  
    new Ajax.Request(url,
    {
      method:'post',
      asynchronous:false,
      onCreate: function() {
        showWaitDialog(true);
      },
      onSuccess: function(transport){
         hideWaitDialog(true);
         if(trim(transport.responseText)==false) {
           messageBox("<?php echo INCORRECT_FORMAT?>");  
         }
         else {
           document.getElementById('resultsDiv').innerHTML=trim(transport.responseText);
         }
       },
       onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
     }); 
     
}

function getPhotoGalleryList(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}

function populateValues(id) {

  
    url = '<?php echo HTTP_LIB_PATH;?>/PhotoGalleryReport/ajaxGetValues.php';
    if("<?php echo $roleId; ?>"!='') {
      roleId="<?php echo $roleId; ?>";  
    }
    new Ajax.Request(url,
    {
      method:'post',
      parameters: {photoGalleryId:id,
                   roleId: roleId
		          },
      asynchronous:false,
      onCreate: function() {
        showWaitDialog(true);
      },
      onSuccess: function(transport){
         hideWaitDialog(true);
         if(trim(transport.responseText)==false) {
           messageBox("<?php echo INCORRECT_FORMAT?>");  
         }
         else {
           document.getElementById('photoResultsDiv').innerHTML=trim(transport.responseText);
         }
       },
       onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
     }); 
     
}

window.onload=function() { 
   getPhotoGallery();
   return false;
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/PhotoGalleryReport/listPhotoGalleryContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
