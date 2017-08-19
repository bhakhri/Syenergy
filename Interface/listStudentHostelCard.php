<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
 include_once(BL_PATH ."/StudentReports/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Hostel Card </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 




</head>
<body>
    <?php 
    //require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/studentHostelCard.php");
    //require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php
  //$History: listStudentHostelCard.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/17/08    Time: 4:13p
//Created in $/Leap/Source/Interface
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/15/08    Time: 2:51p
//Updated in $/Leap/Source/Interface
//remove bread crum
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/10/08    Time: 5:15p
//Updated in $/Leap/Source/Interface
//modification for print report
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/10/08    Time: 10:53a
//Created in $/Leap/Source/Interface
//student report for admit card, buspass, hostel card, identity card,
//library card, photo gallery

?>
