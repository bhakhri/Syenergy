<?php
//-------------------------------------------------------
// Purpose: To design the Student Fee Concession Mapping
//
//
// Author :Ankur Aggarwal
// Created on : 25-Aug-2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ScholarStatusDetails');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Status Detail</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 //This function Validates Form 
//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
recordsPerPage = 1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/StudentDetailUpload/ajaxInitStatusList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'studentName';
sortOrderBy = 'ASC';
queryString ='';
   

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('studentName','Student Name','width="15%"','',true), 
                               new Array('universityRollNo','University Roll No.','width="12%"','',true),
                               new Array('imgSrc','Photo','width="5%" align="center"','align="center"',false), 
                               new Array('isDayScholar','Day Scholar','width="3%"','align="center"',false),
                               new Array('isHostler','Hostler','width="3%"','align="center"',false),
                               new Array('isOther','Other','width="3%"','align="center"',false));

function getStatus(id,str) {  
    
    var ret = eval("document.getElementById('student_"+id+"').value").split('!~~!');
    var studentName = ret[0];
    var univRollNo  = ret[1];

    if(str=='H') {
       if(eval("document.getElementById('dayScholar_"+id+"').checked==true") ) {
         eval("document.getElementById('hostler_"+id+"').checked=false");
	     messageBox("Please Unselect Day Scholar of "+studentName+"("+univRollNo+")");
       }
       else if(eval("document.getElementById('other_"+id+"').checked==true") ) {
         eval("document.getElementById('hostler_"+id+"').checked=false");
         messageBox("Please Unselect Other of "+studentName+"("+univRollNo+")");
       }
    }
    else if(str=='D') {  
       if(eval("document.getElementById('hostler_"+(id)+"').checked==true")) {
          eval("document.getElementById('dayScholar_"+id+"').checked=false");
	      messageBox("Please Unselect Hostler of "+studentName+"("+univRollNo+")");
       }
       else if(eval("document.getElementById('other_"+(id)+"').checked==true")) {
          eval("document.getElementById('dayScholar_"+id+"').checked=false");
          messageBox("Please Unselect Other of "+studentName+"("+univRollNo+")");
       }
    }
    else if(str=='O') {
       if(eval("document.getElementById('hostler_"+(id)+"').checked==true")) {
          eval("document.getElementById('other_"+id+"').checked=false");
          messageBox("Please Unselect Hostler of "+studentName+"("+univRollNo+")");
       }
       else if(eval("document.getElementById('dayScholar_"+(id)+"').checked==true")) {
          eval("document.getElementById('other_"+id+"').checked=false");
          messageBox("Please Unselect Day Scholar of "+studentName+"("+univRollNo+")");
       }
    }
}
	  
	                       
                               
function hideResults() {
   document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function showList() {
    hideResults();
    queryString = ''; 
    if(document.getElementById('classId').value=='') {
      messageBox("Select Class");  
      document.getElementById('classId').focus();
      return false;  
    } 
    queryString = generateQueryString('listForm'); 
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,true);
    
    return false;
}


function saveCategory() {
    url = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/StudentDetailUpload/ajaxInitEdit.php';
    new Ajax.Request(url,
    {
      method:'post',
      parameters: $('listForm').serialize(true),
      onCreate: function() {
         showWaitDialog(true);
      },
      onSuccess: function(transport){
      hideWaitDialog(true);
         if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
           messageBox(trim(transport.responseText));
           flag = true;
           sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,true);   
           return false;
         }
         else {
           messageBox(trim(transport.responseText));  
         }
      },
      onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    }); 
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/RegistrationForm/StudentDetailUpload/listScholarStatusDetails.php"); 
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
