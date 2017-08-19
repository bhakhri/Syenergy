<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FinalResult');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);  
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2) {
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
<title><?php echo SITE_NAME;?>: Final Result Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Subject/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddSubject';   
editFormName   = 'EditSubject';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteSubject';
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'ASC';

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button       


function validateAddForm(frm) {
	
    form = document.frmFinalResult;
    
    if(document.getElementById("classId").value=='') {
      messageBox("Select Class");  
      document.getElementById("classId").focus();
      return false; 
    }
    
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
	refreshAttendanceData();
    return false;
}



function refreshAttendanceData() {    
    
        changeStatus(); 
        var url='<?php echo HTTP_LIB_PATH;?>/FinalResult/initFinalResult.php';
        new Ajax.Request(url,
        {
          method:'post',
          parameters:{classId:classId},
          onCreate: function() {
            showWaitDialog(true);
          },
          onSuccess: function(transport){
             hideWaitDialog(true);
             if(trim(transport.responseText)==false) {
                messageBox("<?php echo "No Data Found"; ?>");  
             }
             else {                                                                              
                var ret=trim(transport.responseText).split('!~~!~~!');
                j0 = ret[0];              
                j1 = ret[1];
                document.getElementById("nameRow").style.display='';
                document.getElementById("nameRow2").style.display='';
                document.getElementById("resultRow").style.display='';
                document.getElementById('resultsDiv').innerHTML=j1;
             }
          },
          onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
        });
}

function changeStatus(){
   document.getElementById("nameRow").style.display='none';
   document.getElementById("nameRow2").style.display='none';
   document.getElementById("resultRow").style.display='none';
   document.getElementById('resultsDiv').innerHTML="";
}

</script>
</head>
<body>
<?php 
	require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/FinalResult/finalResultReportContents.php");
	require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>

