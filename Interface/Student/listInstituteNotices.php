<?php 

//-------------------------------------------------------
//  This File contains the template file and data base file
//
//
// Author :Jaineesh
// Created on : 23-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentInstituteNotices');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
//require_once(BL_PATH . "/Student/initInstituteNotices.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Institute Notices </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(	new Array('srNo','#','width="3%"','align="left"',false), 
								new Array('noticeSubject','Subject','width="20%"','',true),
								new Array('departmentName','Department','width="15%"','',true),
								new Array('noticeText','Description','width="20%"','',true),
								new Array('visibleFromDate','Visible From','width="15%"','align="center"',true),
								new Array('visibleToDate','Visible To','width="15%"','align="center"',true),
								new Array ('Attachment','Attachment','width="10%"','align="center"',false),
								new Array('Detail','Action','width="4%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxDisplayNotices.php';
//listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxNoticesGetValues.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = '';   
editFormName   = '';
winLayerWidth  = 400; //  add/edit form width
winLayerHeight = 400; // add/edit form height
deleteFunction = '';
divResultName  = 'results';
page=1; //default page
sortField = 'visibleFromDate';
sortOrderBy    = 'DESC';

// ajax search results ---end ///

//This function Displays Div Window

function editWindow(id,dv,w,h) {
   
    //displayWindow(dv,w,h);
	height=screen.height/5;
	width=screen.width/4.5;
	displayFloatingDiv(dv,'', w, h, width,height);
    populateValues(id);   
}


//This function populates values in View Deatil form through ajax 

function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxNoticesGetValues.php';
		 
		 new Ajax.Request(url,
           {      
             method:'post',
             parameters: {noticeId: id},
				 
              onCreate: function() {
			 	showWaitDialog();
			 },
				 
			 onSuccess: function(transport){
				 
			      hideWaitDialog();
		          j= trim(transport.responseText).evalJSON();
	              document.getElementById("innerNotice").innerHTML = j.noticeSubject;
				  document.getElementById("innerDepartment").innerHTML = j.departmentName+' ('+j.abbr+')';
				  document.getElementById("visibleFromDate").innerHTML = customParseDate(j.visibleFromDate,"-");
				  document.getElementById("visibleToDate").innerHTML = customParseDate(j.visibleToDate,"-");
				  document.getElementById("innerText").innerHTML = j.noticeText;
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
    require_once(TEMPLATES_PATH . "/Student/instituteNoticesContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
	//-->
	</SCRIPT>
</body>

</html>


<?php 
//$History: listInstituteNotices.php $
//
//*****************  Version 10  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:27p
//Updated in $/LeapCC/Interface/Student
//added access defines
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 9/03/09    Time: 7:33p
//Updated in $/LeapCC/Interface/Student
//fixed bug nos.0001440, 0001433, 0001432, 0001423, 0001239, 0001406,
//0001405, 0001404
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/27/09    Time: 11:21a
//Updated in $/LeapCC/Interface/Student
//Gurkeerat: resolved alignment issue regarding issues 1226,1227
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/18/09    Time: 7:37p
//Updated in $/LeapCC/Interface/Student
//Remove administrator role from role type so that no new administrator
//can be made and Chalkpad will be administrator and Applied time
//validation so that start time can not be greater than end time.
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/17/09    Time: 7:34p
//Updated in $/LeapCC/Interface/Student
//fixed bug nos.0001093, 0001086, 0000672, 0001087
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/13/09    Time: 10:43a
//Updated in $/LeapCC/Interface/Student
//Gurkeerat: resolved issue 1035
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/12/09    Time: 4:49p
//Updated in $/LeapCC/Interface/Student
//fixed bug nos.0000969,0000965, 0000962, 0000963
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/10/09    Time: 7:22p
//Updated in $/LeapCC/Interface/Student
//fixed bug nos.0000966,0000970,0000967
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:15p
//Updated in $/LeapCC/Interface/Student
//modification code for cc student notice
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Student
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 11/05/08   Time: 4:29p
//Updated in $/Leap/Source/Interface/Student
//modified for download attachment
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 10/17/08   Time: 5:01p
//Updated in $/Leap/Source/Interface/Student
//modified
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 10/17/08   Time: 4:31p
//Updated in $/Leap/Source/Interface/Student
//remove the html tags through strip_tags function
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:10p
//Updated in $/Leap/Source/Interface/Student
//remove the spaces


?>