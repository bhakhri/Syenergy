<?php 
//-------------------------------------------------------
// Author :Parveen Sharma
// Created on : 03-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CoursewiseResourceReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Coursewise Resource Report </title>

<style type="text/css">
a.whiteClass:hover{
    color:#FFFFFF;
}
</style>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array( new Array('srNo','#','width="4%"','valign="center"',false), 
                                new Array('subject','Course','width="12%"','valign="center"',true) , 
                                new Array('description','Description','width="20%"','valign="center"',true), 
                                new Array('resourceName','Type','width="10%"','valign="center"',true), 
                                new Array('postedDate','Date','width="8%"','valign="center" align="center"',true),
                                new Array('resourceLink','Link','width="8%"','valign="center"',false),
                                new Array('attachmentLink','Attachment','width="8%"','valign="center" align="center"',false),
                                new Array('employeeName','Teacher Name','width="12%"','valign="center" align="left"',true)); 

 //This function Validates Form 
listURL = '<?php echo HTTP_LIB_PATH;?>/StudentReports/scAjaxCoursewiseResource.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'resourceForm'; // name of the form which will be used for search
addFormName    = 'AddResource';   
editFormName   = 'EditResource';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteResource';
page=1; //default page
sortField = 'subject';
sortOrderBy    = 'DESC';
 //This function Validates Form 


function validateAddForm(frm) {
	form = document.resourceForm;
    if (form.subjectId.value == 'Select' || form.subjectId.value == '') {
        messageBox("<?php echo SELECT_COURSE; ?>");
        form.subjectId.focus();
        return false;
    }
    
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //openResourseLists(frm.name,'class','Asc');    
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function printReport() {
	form = document.resourceForm;
	path='<?php echo UI_HTTP_PATH;?>/scCourseWiseResourceReportPrint.php?subjectId='+form.subjectId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.open(path,"ResourcesReport","status=1,menubar=1,scrollbars=1, width=900");
}

function  download(str){    
    var address="<?php echo IMG_HTTP_PATH;?>/CourseResource/"+escape(str);
    window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/scCourseWiseResourceReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
 
 function trim_output($str,$maxlength='250',$rep='...'){
   $ret=chunk_split($str,60);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep; 
   }
  return $ret;  
}


//$History: scCourseWiseResourceReport.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 4/02/10    Time: 12:55
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//0002528,0002303,0002193,0001928,
//0001922,0001863,0001763,0001238,
//0001229,0001894,0002143
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/05/09   Time: 5:18p
//Updated in $/LeapCC/Interface
//RESOLVED ISSUES 2196,2195,2194,2191,2192
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/17/09    Time: 12:30p
//Updated in $/LeapCC/Interface
//role permission added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/17/09    Time: 12:07p
//Created in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 31/01/09   Time: 15:38
//Created in $/SnS/Interface
//Added "Coursewise resource report" module
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/10/08   Time: 9:36a
//Updated in $/Leap/Source/Interface
//on chanage event added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/10/08   Time: 9:33a
//Updated in $/Leap/Source/Interface
//code review
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/04/08   Time: 11:33a
//Updated in $/Leap/Source/Interface
//sorting format setting
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/04/08   Time: 10:40a
//Updated in $/Leap/Source/Interface
//html tags & formatting settings
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/03/08   Time: 4:19p
//Updated in $/Leap/Source/Interface
//ajax file link modify
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/03/08   Time: 4:12p
//Created in $/Leap/Source/Interface
//coursewise resource added
//
//


?>
