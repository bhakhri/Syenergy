<?php 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
global $sessionHandler;
//$classId= $sessionHandler->getSessionVariable('ClassId');
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 5){
  UtilityManager::ifManagementNotLoggedIn();
}
else{
  UtilityManager::ifNotLoggedIn();
}
UtilityManager::headerNoCache(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display All Notices </title>
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
listURL = '<?php echo HTTP_LIB_PATH;?>/Notice/ajaxDisplayNotices.php';
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
         url = '<?php echo HTTP_LIB_PATH;?>/Notice/ajaxNoticesGetValues.php';
		 
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
    require_once(TEMPLATES_PATH . "/Notice/instituteNoticesContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
	//-->
	</SCRIPT>
</body>
</html>
