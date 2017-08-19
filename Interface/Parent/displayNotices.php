<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax functions used in "DisplayNotices" Form
//
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ParentInstituteNotices');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn();
//require_once(BL_PATH . "/Parent/initDisplayNotices.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Institute Notices</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('noticeSubject','Subject','width="15%"','',true),
                               new Array('departmentName','Department','width="20%"','',true), 
                               new Array('noticeText',  'Description','width="25%"','',true), 
                               new Array('visibleFromDate', 'Visible From','width="11%"','align="center"',true),
                               new Array('visibleToDate',   'Visible To','width="11%"','align="center"',true),
                               new Array('noticeAttachment','Attachment','width="10%"','align="center"',false),
                               new Array('Edit','Action','width="10%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxInitDisplayNotices.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = '';   
editFormName   = '';
winLayerWidth  = 520; //  add/edit form width
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
    height=screen.height/7;
    width=screen.width/4;
    displayFloatingDiv(dv,'', w, h, width,height);
    populateValues(id);   
}


//This function populates values in View Deatil form through ajax 

function populateValues(id) {
   
   //url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxNoticesGetValues.php';
    url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxGetNoticeDetails.php';  
         
   new Ajax.Request(url,
   {      
       method:'post',
       parameters: {noticeId: id},
       onCreate: function() {
       showWaitDialog();
     },
     onSuccess: function(transport){
       hideWaitDialog();
       j = eval('('+transport.responseText+')');
       document.getElementById("subjectNotice").innerHTML = j.noticeSubject;
       document.getElementById("noticeDepartment").innerHTML = j.departmentName+' ('+j.abbr+')';
       document.getElementById("innerNotice").innerHTML = j.noticeText;
       document.getElementById('startDate').innerHTML=customParseDate(j.visibleFromDate,"-");
       document.getElementById('endDate').innerHTML=customParseDate(j.visibleToDate,"-");
     },
     onFailure: function(){ alert('Something went wrong...') }
   });
}
function  download(str){    
//location.href = "<?php echo HTTP_LIB_PATH.'/';?>/Notice/noticeDownload.php?path="+str; 
//location.href="<?php echo IMG_HTTP_PATH.'/';?>Notice/"+str;
var address="<?php echo IMG_HTTP_PATH;?>/Notice/"+str;
window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}
</script>

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Parent/displayNoticesContents.php");
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
//History: $


?>
