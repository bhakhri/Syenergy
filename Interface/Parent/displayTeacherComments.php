<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax functions used in "displayTeacherComments" Form
//
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH.'/HtmlFunctions.inc.php');  
define('MODULE','ParentTeacherComments');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn();
//require_once(BL_PATH . "/Parent/initDisplayComments.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Teacher Comments  </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 

function trim_output($str,$maxlength,$mode=1,$rep='...'){
   $ret=($mode==2?chunk_split($str,30):$str);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep; 
   }
  return $ret;  
}
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('subject','Subject','width="20%"','align="left"',true),
                               new Array('comments','Comments','width="40%" ','align="left"',true),
                               new Array('postedOn','Posted On','width="10%"','align="center"',true),
                               new Array('employeeName','Teacher Name','width="15%"','align="left"',true),
                               new Array('Action',' Action','width="5%"','align="center"',false));
                               
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxInitDisplayComments.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = '';   
editFormName   = '';
winLayerWidth  = 400; //  add/edit form width
winLayerHeight = 400; // add/edit form height
deleteFunction = '';
divResultName  = 'results';
page=1; //default page
sortField = 'postedOn';
sortOrderBy    = 'DESC';

// ajax search results ---end ///

//This function Displays Div Window

function editWindow(id,dv,w,h) {
   
    //displayWindow(dv,w,h);
	height=screen.height/5;
	width=screen.width/3;
	displayFloatingDiv(dv,'', w, h, width,height);
    populateValues(id);   
}

function populateValues(id) {
     
     url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxCommentsGetValues.php';
	 new Ajax.Request(url,
       {      
         method:'post',
         parameters: {commentId: id},
          onCreate: function() {
			showWaitDialog();
		 },
		 onSuccess: function(transport){
           hideWaitDialog();
           j = eval('('+trim(transport.responseText)+')');
           //alert(trim(transport.responseText));
           document.getElementById("employeeNameComments").innerHTML = j.employeeName; 
           document.getElementById('startDate').innerHTML=customParseDate(j.visibleFromDate,"-");
           document.getElementById('endDate').innerHTML=customParseDate(j.visibleToDate,"-");
           document.getElementById('innerComments').innerHTML=j.comments;
         },
         onFailure: function(){ alert('Something went wrong...') }
       });
}


</script>

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Parent/displayTeacherCommentsContents.php");
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
