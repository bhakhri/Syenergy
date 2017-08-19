<?php 

//-------------------------------------------------
// Purpose: This file shows a list of Notifications
// Author :Kavish Manjkhola
// Created on : 22-Oct-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Notifications');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Notifications </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                               new Array('message','Message','width="25%"','',true),
                               new Array('publishDateTime','Publish Date Time','width="15%"','align="center"',true),
							   new Array('action1','Action','width="10%"','align="center"',false)
							   );
listURL='<?php echo HTTP_LIB_PATH;?>/Notifications/ajaxInitNotificationsList.php';
divResultName = 'resultDiv';
fetchedData='';//global variable, used for passing (fetched and sorted) data to popup window
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'searchForm'; // name of the form which will be used for search
winLayerWidth  = 315;
winLayerHeight = 250;
page=1; //default page
sortField = 'publishDateTime';
sortOrderBy = 'DESC';

/*window.onload = function () {
	deleteNotifications();
}*/

/*function deleteNotifications() {
	
	var timeLimit = 10;		// this variable denotes the no of days the notice can be viewed after it was viewed first.
	
	url = '<?php echo HTTP_LIB_PATH;?>/Notifications/ajaxInitNotificationsDelete.php';

	new Ajax.Request(url,
		{
			method:'post',
			parameters: {
				timeLimit : timeLimit
			},
			onCreate: function() {
				showWaitDialog(true);
			},
			onSuccess: function(transport){
				hideWaitDialog(true);
				if("<?php echo DELETE;?>"==trim(transport.responseText)) {
					sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
					return false;
				}
				else {
					messageBox(trim(transport.responseText));                         
				}
			},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
}*/


function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}


</script>
</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/Notifications/listNotificationsContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
	?>

</body>
<script language="javascript">
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
	changeColor(currentThemeId);
</script>
</html>