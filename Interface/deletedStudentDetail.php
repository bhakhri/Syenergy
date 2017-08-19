<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
include_once(BL_PATH ."/StudentReports/initDeletedStudentInformation.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");
$queryString =  $_SERVER['QUERY_STRING'];
function parseInput($input) {
    return ( (trim($input)!="" ? $input : 0 ) );
}

function parseOutput($data){

     return ( (trim($data)!="" ? $data : NOT_APPLICABLE_STRING ) );

}
function createBlankTD($i,$str='<td  valign="middle" align="center" class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
?>
<script language="javascript">
//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
//linksPerPage = <?php echo LINKS_PER_PAGE;?>;

var filePath = "<?php echo IMG_HTTP_PATH;?>"

var tableHeadArray = new Array(new Array('srNo','#','width="4%"','valign="top"',false),
new Array('subjectCode','Course','width="12%"','valign="top"',true) ,
new Array('description','Description','width="20%"','valign="top"',true),
new Array('resourceName','Type','width="10%"','valign="top"',true),
new Array('postedDate','Date','width="8%"','valign="top"',true),
new Array('resUrl','Link','width="8%"','valign="top"',false),
new Array('attFile','Attachment','width="8%"','valign="top" align="center"',false),
new Array('employeeName','Teacher Name','width="12%"','valign="top" align="left"',true)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxCourseResourceList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
divResultName  = 'resultResource';
page=1; //default page
sortField = 'subjectCode';
sortOrderBy = 'ASC';

/*function totalFunction(value){

	getGroup(value);
	getTransferredMarks(value);
}*/

/****************************************************************/
//Overriding tabClick() function of tab-view.js

/****************************************************************/
var tabNumber=0;  //Determines the current tab index
function tabClick() {

        var idArray = this.id.split('_');
        showTab(this.parentNode.parentNode.id,idArray[idArray.length-1].replace(/[^0-9]/gi,''));
        tabNumber=(idArray[idArray.length-1].replace(/[^0-9]/gi,''));

        //refresshes data for this tab
        totalFunction(document.getElementById('studentId').value,tabNumber);
    }


//Global variables for classId countres for different tabs
var gcId=-1;
var tmdId=-1;
var offId=-1;
var rsId=-1;


function totalFunction(Id,tabIndex) {

	if(tabIndex==2 && Id!=gcId) {
     //get the data of course based upon selected study period
     var groupData=getAttendance(Id);
     gcId=Id;
     return;
    }

	if(tabIndex==3 && Id!=rsId) {
     //get the data of course based upon selected study period
     var resourceData=getTestMarks(Id);
     rsId=Id;
     return;
    }

	if(tabIndex==4 && Id!=tmdId) {
     //get the data of course based upon selected study period
     var transferredMarksData=getFinalResult(Id);
     tmdId=Id;
     return;
    }

}

function getAttendance(Id) {

url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxInitDeletedStudentAttendanceDetail.php';

		var tbHeadArray =	new Array(new Array('srNo','#','width="5%"',false),
							new Array('className','Class Name','width="12%"',true),
							new Array('subjectName','Subject Name','width="15%"',true),
							new Array('subjectCode','Subject Code','width="15%"',true),
							new Array ('delivered','Lecture Delivered','width="15%" align="right"',true),
							new Array('attended','Lecture Attended','width="15%" align="right"',true),
							new Array('Percentage','%age','width="15%" align="right"',false));

		listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','className','ASC','results','','',true,'listObj1',tbHeadArray,'','','&studentId='+Id);
		sendRequest(url, listObj1, '')
}

function getTestMarks(Id) {

url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxInitDeletedStudentTestMarks.php';


			 var tbHeadArray =	new Array(new Array('srNo','#','width="3%"',false),
								new Array('className','Class Name','width="12%"',true),
								new Array('subjectName','Subject Name','width="15%"',true),
								new Array('subjectCode','Subject Code','width="15%"',true),
								new Array('testType','Test Type','width="12%" align="left"',true),
								new Array('totalMarks','Max Marks','width="10%" align="right"',true),
								new Array('obtained','Marks Scored','width="10%" align="right"',true),
								new Array('marksObtained','%age','width="10%" align="right"',false));

		  listObj2 = new initPage(url,recordsPerPage,linksPerPage,1,'','className','ASC','testMarksResults','','',true,'listObj2',tbHeadArray,'','','&studentId='+Id);
		 sendRequest(url, listObj2, '')
}

function getFinalResult(Id){

  url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxInitDeletedStudentFinalResult.php';
  var tableColumns = new Array(
                            new Array('srNo','#','width="2%" valign="middle"',false),
							new Array('className','Class Name','width="12%"',true),
							new Array('subjectName','Subject Name','width="15%"',true),
							new Array('subjectCode','Subject Code','width="15%"',true),
							new Array('conductingAuthority','Conducting Authority','width="15%" valign="middle"',true),
							new Array('maxMarks','Max Marks','width="10%" align="right"',true),
                            new Array('marksScored','Marks Scored','width="10%"  align="right"',true)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj9 = new initPage(url,recordsPerPage,linksPerPage,1,'','className','ASC','resultResource','','',true,'listObj9',tableColumns,'','','&studentId='+Id);
 sendRequest(url, listObj9, '')
}

function listPage(path){

	window.location=path;
}
window.onload = function(){

    //totalFunction(document.getElementById('semesterDetail').value);
    if("<?php echo $REQUEST_DATA['tabIndex']; ?>"!="") {
        if("<?php echo $REQUEST_DATA['tabIndex']; ?>"==4) {
          showTab('dhtmlgoodies_tabView1',4);
          totalFunction(document.getElementById('semesterDetail').value,4);
        }
    }
}

function printAttendanceReport() {
	var studentId = document.getElementById('studentId').value;
	path='<?php echo UI_HTTP_PATH;?>/deleteStudentAttendanceReportPrint.php?studentId='+studentId;
	a = window.open(path,"StudentAttendanceReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printTestMarksReport() {
	var studentId = document.getElementById('studentId').value;
	path='<?php echo UI_HTTP_PATH;?>/deleteStudentTestMarks.php?studentId='+studentId;
	a = window.open(path,"StudentTestMarksReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printFinalResultReport() {
	var studentId = document.getElementById('studentId').value;
	path='<?php echo UI_HTTP_PATH;?>/deleteStudentFinalResult.php?studentId='+studentId;
	a = window.open(path,"StudentFinalResultReport","status=1,menubar=1,scrollbars=1, width=900");
}

function  download(id){
 var address="<?php echo HTTP_LIB_PATH;?>/forceDownload.php?fileId="+id+"&callingModule=ResourceDownload";
 //window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
 window.location=address;
}

</script>
<title><?php echo SITE_NAME;?>: Student Information </title>
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
    require_once(TEMPLATES_PATH . "/StudentReports/deletedStudentInformationContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php
  //$History: $
//
//
?>