<?php 

//-------------------------------------------------------
//  This File contains the template file and data base file
//
//
// Author :Jaineesh
// Created on : 22-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentTeacherComments');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
//require_once(BL_PATH . "/Student/initTeacherComments.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Teacher Comments </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 

<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
								new Array('srNo','#','width="3%"','style="padding-left:15px"',false), 
								new Array('employeeName',' Teacher Name','width="20%"','',false), 
								new Array('teacherComment','Comments','width="20%"','',false),
								//new Array('Attachment','Attachment','width="20%"','align="center"',false),
								new Array('Detail','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxDisplayTeacherMessages.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = '';
editFormName   = '';
winLayerWidth  = 400; //  add/edit form width
winLayerHeight = 400; // add/edit form height
deleteFunction = '';
divResultName  = 'results';
page=1; //default page
sortField = 'comments';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

//This function Displays Div Window

function editWindow(id,dv,w,h) {
   
    //displayWindow(dv,w,h);
	height=screen.height/5;
	width=screen.width/3;
	displayFloatingDiv(dv,'', w, h, width,height);
    populateValues(id);   
}


//This function populates values in View Deatil form through ajax 

	function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxTeacherGetValues.php';

		 new Ajax.Request(url,
           {      
             method:'post',
             parameters: {commentId: id},
				 
              onCreate: function() {
			 	showWaitDialog();
			 },
			 onSuccess: function(transport){
				 
			      hideWaitDialog();
		          j= trim(transport.responseText).evalJSON();
	              document.getElementById("innerNotice").innerHTML = j.comments;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

</script>

<?php 
	function trim_output($str,$maxlength,$mode=1,$rep='...'){
		$ret=($mode==2?chunk_split($str,30):$str);

		if(strlen($ret) > $maxlength){
			$ret=substr($ret,0,$maxlength).$rep;
		}
		return $ret;
	}
?>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/teacherCommentsContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</SCRIPT>
</body>
</html>


<?php 
//$History: listTeacherComments.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:27p
//Updated in $/LeapCC/Interface/Student
//added access defines
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/18/09    Time: 7:37p
//Updated in $/LeapCC/Interface/Student
//Remove administrator role from role type so that no new administrator
//can be made and Chalkpad will be administrator and Applied time
//validation so that start time can not be greater than end time.
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/21/09    Time: 11:35a
//Updated in $/LeapCC/Interface/Student
//show pop up on dashboard in event, notices, admin messages, teacher
//messages
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Student
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 11/05/08   Time: 4:29p
//Updated in $/Leap/Source/Interface/Student
//modified for download attachment
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 10/21/08   Time: 6:14p
//Updated in $/Leap/Source/Interface/Student
//modified
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:10p
//Updated in $/Leap/Source/Interface/Student
//remove the spaces


?>