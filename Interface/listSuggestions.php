<?php
//-------------------------------------------------------
// Purpose: To generate student list for subject centric
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : (06.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentSuggestion');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Suggestions</title>
<style>
   .imgLinkRemove{ cursor: default; }
</style>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php 
	require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
 
?> 
<?php
function createBlankTD($i,$str='<td valign="middle" align="center"  class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
?>
<script language="javascript">
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

// ajax search results ---start//

winLayerWidth  = 520; //  add/edit form width
winLayerHeight = 250; // add/edit form height
//this function is uded to refresh tab data based uplon selection of study periods
function refreshMessageData(){
    
	//get the data of student grade card based upon selected study period
    var inboxData=refreshInboxData();
}
//this function fetches records corresponding to student grades detail
function refreshInboxData(){
	
  url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxInitSuggextionList.php';
  //var value=document.getElementById('searchbox').value;
   	 	
   var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
						new Array('userName','User','width="12%" align="left"',true),                
						new Array('suggestionOn','Given On','width="7%" align="left"',true),
                        new Array('suggestionSubjectId','Subject','width="15%" align="left"',true),
						new Array('suggestionText','Suggestion','width="40%" align="left"',true),
						new Array('repliedOn','Read On','width="7%" align="left"',true),
                        new Array('action1','Action','width="4%" align="center"',false)
                       );


 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','messageId','ASC','LeaveTypeResultDiv','StudentTeacherActionDiv','',true,'listObj',tableColumns,'editWindow','deleteStudentTeacher','');
 sendRequest(url, listObj, '')
}

window.onload=function(){
  
	refreshMessageData();  
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO DISPLAY EDIT DIVS
//
// id : id of the div
// dv : name of the form
// w : width of the div
// h : height of the div
// Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv) {

	 
    document.getElementById('divHeaderId').innerHTML='&nbsp; Suggestion Detail';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues(id);   
}

function populateValues(id) {

	 url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxGetSuggestionValues.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {suggestionId: id},
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
		 hideWaitDialog(true);
		 if(trim(transport.responseText)==0) {

			hiddenFloatingDiv('LeaveTypeActionDiv');
			messageBox("<?php echo LEAVE_TYPE_NOT_EXIST; ?>");
			getStudentTeacherData();           
		 }
	     j = eval('('+trim(transport.responseText)+')');

		 //alert(j.suggestionText);
	     document.getElementById('senderName').innerHTML = trim(j.userName);
	     document.getElementById('senderDate').innerHTML = trim(j.suggestionOn);
	     document.getElementById('senderSubject').innerHTML = trim(j.suggestionSubjectId);
		 document.getElementById('senderSuggestion').innerHTML = trim(j.suggestionText);
		 //hiddenFloatingDiv('StudentTeacherActionDiv');
		 
		 //refreshMessageData(); 

		 },
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Index/suggestionContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: listSuggestions.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/04/09    Time: 12:46p
//Updated in $/LeapCC/Interface
//Gurkeerat: corrected title of page
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/08/09    Time: 11:14a
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/15/09    Time: 11:45a
//Created in $/LeapCC/Interface
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/02/09    Time: 1:04p
//Created in $/SnS/Interface
//Intial checkin
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 2/27/09    Time: 10:58a
//Updated in $/Leap/Source/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 2/11/09    Time: 2:54p
//Created in $/Leap/Source/Interface
//Intial checkin
?>
