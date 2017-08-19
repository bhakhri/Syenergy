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
define('MODULE','CityMaster');
define('ACCESS','view');
//UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/City/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: City Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


function test() {
         url = 'ajaxTest.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     alert(transport.responseText);

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}



</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
//	require_once(TEMPLATES_PATH . "/City/listCityContents.php");
?>
<input type="button" onclick="test()" value="check" />
<?php
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
// $History: test.php $ 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:58
//Created in $/LeapCC/Interface
//Added "Print" and "Export to excell" in subject and subjectType modules
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 11/05/08   Time: 5:34p
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 8/27/08    Time: 7:27p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 8/08/08    Time: 12:49p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:03p
//Updated in $/Leap/Source/Interface
//Added onCreate() function in ajax code
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 6/28/08    Time: 2:35p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 6/28/08    Time: 1:48p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 6/28/08    Time: 12:57p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/28/08    Time: 11:23a
//Updated in $/Leap/Source/Interface
//Added AjaxList Functionality
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/25/08    Time: 2:26p
//Updated in $/Leap/Source/Interface
//*********Solved The Problem********
//Open 2 browsers opening city Masters page. On one page, delete a city.
//On the second page, the deleted city is still visible since editing was
//done on first page. Now, click on the Edit button corresponding to the
//deleted city in the second page which was left untouched. Provide the
//new city Code and click Submit button.A blank popup is displayed. It
//should rather display "The city you are trying to edit no longer
//exists".
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/25/08    Time: 11:37a
//Updated in $/Leap/Source/Interface
//Added AjaxEnabled Delete Functionality
//Added deleteCity() function
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/18/08    Time: 11:52a
//Updated in $/Leap/Source/Interface
//adding constraints done
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/12/08    Time: 6:14p
//Updated in $/Leap/Source/Interface
?>