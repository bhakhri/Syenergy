<?php
//used for showing subject wise performance report
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignmentReport');
define('ACCESS','view');
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
    UtilityManager::ifTeacherNotLoggedIn();
}
else{
 UtilityManager::ifNotLoggedIn();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Assignment Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');?>
<script language="javascript">
var roleId=<?php echo $roleId; ?>;


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/assignmentReportList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
divResultName  = 'resultsDiv';
//page=1; //default page
//sortField = 'cityName';
//sortOrderBy    = 'ASC';

function checkInputData(){
    if(document.getElementById('timeTableLabelId').value==''){
        messageBox("<?php echo SELECT_TIME_TABLE;?>");
        document.getElementById('timeTableLabelId').focus();
        return false;
    }
    if(document.getElementById('classId').value==''){
        messageBox("<?php echo SELECT_CLASS;?>");
        document.getElementById('classId').focus();
        return false;
    }
    return true;
}

function getListData(){
    if(!checkInputData()){
       return false;     
    }
    page=1; //default page    
    sortOrderBy    = 'ASC';
    if(roleId==2){
        tableHeadArray = new Array(
                                new Array('srNo','#','width="2%"','',false),
                                new Array('subjectCode','Subject','width="5%"','',true),
                                new Array('groupName','Group','width="5%"','',true),
                                new Array('topicTitle','Topic','width="15%"','',true),
                                new Array('topicDescription','Description','width="30%"','',true),
                                new Array('assignedOn','Assigned','width="5%"','align="center"',true),
                                new Array('totalAssigned','Total','width="5%"','align="right"',true)
                               );
        sortField = 'subjectCode';
    }
    else{
        tableHeadArray = new Array(
                                new Array('srNo','#','width="2%"','',false),
                                new Array('employeeName','Teacher','width="12%"','',true),
                                new Array('subjectCode','Subject','width="7%"','',true),
                                new Array('groupName','Group','width="7%"','',true),
                                new Array('topicTitle','Topic','width="15%"','',true),
                                new Array('topicDescription','Description','width="30%"','',true),
                                new Array('assignedOn','Assigned','width="7%"','align="center"',true),
                                new Array('totalAssigned','Total','width="5%"','align="right"',true)
                               );
        sortField = 'employeeName';
    }
    
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    document.getElementById('printTRId').style.display='';
}

//this function fetches class data based upon user selected dates
function getClassData(value){
  var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAssignmentClass.php';
  var classEle=document.getElementById('classId');
  classEle.options.length=1;
  document.getElementById('subjectId').options.length=1;
  document.getElementById('groupId').options.length=1;
  vanishData();
  
  if(value==''){
      return false;
  }

 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTableLabelId : value
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')'); 
                    var len=j.length;
                    for(var c=0;c<len;c++){
                       var objOption = new Option(j[c].className,j[c].classId);
                       classEle.options.add(objOption);
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

//this function fetches subject data based upon user selected dates
function getSubjectData(value1,value2){
  var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAssignmentSubject.php';
  var subjectEle=document.getElementById('subjectId');
  subjectEle.options.length=1;
  document.getElementById('groupId').options.length=1;
  vanishData();
  
  if(value1=='' || value2==''){
      return false;
  }

 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTableLabelId : value1,
                 classId          : value2
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')'); 
                    var len=j.length;
                    for(var c=0;c<len;c++){
                       var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                       subjectEle.options.add(objOption);
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

//this function fetches subject data based upon user selected dates
function getGroupData(value1,value2,value3){
  var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAssignmentGroup.php';
  var groupEle=document.getElementById('groupId');
  groupEle.options.length=1;
  vanishData();
  
  if(value1=='' || value2=='' || value3=='' || value3=='-1'){
      return false;
  }

 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTableLabelId : value1,
                 classId          : value2,
                 subjectId        : value3
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')'); 
                    var len=j.length;
                    for(var c=0;c<len;c++){
                       var objOption = new Option(j[c].grpName,j[c].groupId);
                       groupEle.options.add(objOption);
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

function vanishData(){
  document.getElementById('printTRId').style.display='none';
  document.getElementById(divResultName).innerHTML='';
}

/* function to print Assignment report*/
function printReport() {
    var qstr=generateQueryString('searchForm');
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    qstr +='&timeTableLabelName='+document.getElementById('timeTableLabelId').options[document.getElementById('timeTableLabelId').selectedIndex].text;
    qstr +='&className='+document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
    qstr +='&subjectName='+document.getElementById('subjectId').options[document.getElementById('subjectId').selectedIndex].text;
    qstr +='&groupName='+document.getElementById('groupId').options[document.getElementById('groupId').selectedIndex].text;
    
    var path='<?php echo UI_HTTP_PATH;?>/assignmentReportPrint.php?'+qstr;
    hideUrlData(path,true);
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr=generateQueryString('searchForm');
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='assignmentReportCSV.php?'+qstr;
}


</script>

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/assignmentReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");    
?>
</body>
</html>