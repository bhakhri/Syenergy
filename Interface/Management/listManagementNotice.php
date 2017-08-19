<?php 
//-------------------------------------------------------
//  This File contains list of Notice For Management Role
//
//
// Author :Rajeev Aggarwal
// Created on : 15-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifManagementNotLoggedIn();
require_once(BL_PATH . "/Management/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Notice Detail </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), new Array('noticeSubject','Subject','width="30%"','',true), new Array('departmentName','Department','width="20%"','',true) , new Array('visibleFromDate','Visible From','width="10%"','',true),new Array('visibleToDate','Visible To','width="10%"','',true),new Array('noticeAttachment','Attachment','width="8%"','align="center"',false), new Array('details','Detail','width="4%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage   = <?php echo LINKS_PER_PAGE;?>;
listURL		   = '<?php echo HTTP_LIB_PATH;?>/Management/ajaxInstituteNoticeList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddNoticeDiv';   
editFormName   = 'EditNoticeDiv';
winLayerWidth  = 600; //  add/edit form width
winLayerHeight = 400; // add/edit form height
deleteFunction = 'return deleteNotice';
divResultName  = 'results';
page=1; //default page
sortField = 'noticeSubject';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
 
 var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button       
//This function Displays Div Window

function showNoticeDetails(id,dv,w,h) {

	displayFloatingDiv(dv,'', w, h, 200, 190)
    populateNoticeValues(id);

}

function populateNoticeValues(id) {
	 
	 url = '<?php echo HTTP_LIB_PATH;?>/Management/ajaxGetNoticeDetails.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {noticeId: id},
		 onCreate: function() {
			 showWaitDialog();
		 },
		 onSuccess: function(transport){
				
				hideWaitDialog();
				if(trim(transport.responseText)==0) {
					hiddenFloatingDiv('divNotice');
					messageBox("This Notice Record Does Not Exists");
			   }
			   j = eval('('+trim(transport.responseText)+')');

			   document.getElementById('noticeSubject').innerHTML = trim(j.noticeSubject);
			   document.getElementById('noticeDepartment').innerHTML = trim(j.departmentName+' ('+j.abbr+')');
			  document.getElementById('noticeText').innerHTML = trim(j.noticeText);
			  document.getElementById('visibleToDate').innerHTML=customParseDate(j.visibleToDate,"-");
			  document.getElementById('visibleFromDate').innerHTML=customParseDate(j.visibleFromDate,"-");

			   
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}
function  download(str){    
	
	var address="<?php echo IMG_HTTP_PATH;?>/Notice/"+str;
	window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}
</script>
</head>
<body>
<?php
	function trim_output($str,$maxlength,$mode=1,$rep='...'){
	
		$ret=($mode==2?chunk_split($str,30):$str);
		if(strlen($ret) > $maxlength){
		
			$ret=substr($ret,0,$maxlength).$rep; 
		}
		return $ret;  
	}
	require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Management/listNoticeContents.php");
	require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php 
// $History: listManagementNotice.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:00p
//Updated in $/LeapCC/Interface/Management
//Updated as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Management
//
?>