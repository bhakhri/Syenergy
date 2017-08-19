<?php
//-----------------------------------------------------------------------------
//  To generate add student to bussines logics    
//
// Author :Abhay Kant
// Created on : 22.6.2011
// Copyright 2010-2011: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BlockStudent');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>:Block Students </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?> 
<script language="javascript">
var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                               new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="5%" align=\"center\" ','align=\"center\"  valign="middle"',false),
                               new Array('rollNo','Roll No.','width="12%"','',true), 
                               new Array('studentName','Name','width="15%"','',true), 
                               new Array('isStatus','Status','width="10%"','align="center"',true), 
                               new Array('message','Message','width="40%"','',true),                               
                               new Array('action1','Unblock','width="10%"','align="center"',false));
	
//This function Validates Form 
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL='<?php echo HTTP_LIB_PATH;?>/BlockStudent/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddSubject';   
editFormName   = 'EditSubject';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBlockStudent';
divResultName  = 'results';
page=1; //default page
sortField = 'rollNo';
sortOrderBy = 'ASC';
 
queryString='';
queryString1 = '';

function doAll() {

   try {
     var formx = document.allDetailsForm;
     if(document.getElementById('checkbox2').value=='on'){
        for(var i=0;i<document.getElementsByName('chb[]').length;i++){
         document.getElementsByName('chb[]')[i].checked=true;
        }
     }
     if(document.getElementById('checkbox2').checked==false){
          for(var i=0;i<document.getElementsByName('chb[]').length;i++){   
           document.getElementsByName('chb[]')[i].checked=false;
        }
     }
   } catch (e) {}
   
}


function validateAddForm() {
   
   var queryString = '';
   var queryString1 = '';

   var mail_check= 0; 
   var rollNo  = trim(document.getElementById('rollNo').value);
   var message = trim(document.getElementById('message').value);
   if(document.getElementById('mail_check').checked==true)	{
	 mail_check=1;
   }
   
    if(true == isEmpty(rollNo)) {
       messageBox("<?php echo ENTER_ROLLNO ?>");
       document.getElementById('rollNo').focus();
       document.getElementById('rollNo').className='inputboxRed';
       return false;
    }
    
    if(true == isEmpty(message)) {
       messageBox("<?php echo ENTER_MESSAGE_TEXT ?>");
       document.getElementById('message').focus(); 
       document.getElementById('message').className='inputboxRed';
       return false;  
    }
    
    if(document.getElementById('rollNo').value.indexOf(",,")!=-1){
	   messageBox("<?php echo 'Wrong String' ?>");
       document.getElementById('rollNo').className='inputboxRed';
	   return false;
	}
    
    addStudent();   
    return false;
}


function addStudent() {
    
     var mail_check= 0; 
     var rollNo  = trim(document.getElementById('rollNo').value);
     var message = trim(document.getElementById('message').value);
     if(document.getElementById('mail_check').checked==true)    {
       mail_check=1;
     }
    
     url = '<?php echo HTTP_LIB_PATH;?>/BlockStudent/ajaxBlockStudent.php';
     
	 new Ajax.Request(url,
     {
         method:'post',
         parameters:{mail_check: mail_check,
                     rollNo: rollNo, 
			         blkmessage: message
			        },
         onCreate: function() {
		   showWaitDialog(true);
		 },
		 onSuccess: function(transport){
             hideWaitDialog(true);
             if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                     //flag = true;
                 alert("Student syenergy Blocked");
	             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
              } 
              else {
                  messageBox(trim(transport.responseText)); 
              }
         },
		  onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}


function deleteSubject(id) {
    
    url = '<?php echo HTTP_LIB_PATH;?>/BlockStudent/ajaxInitDelete.php';
        
    if(false===confirm("Do you want to delete this record?")) {
       return false;
    }
    else {   
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {blockId: id},
             onCreate: function() {
			    showWaitDialog(true);
		     },
		     onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     return false;
                 }
                 else {
                     messageBox(trim(transport.responseText));
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
    }    
}


//Function for unblocking student selected via checkbox
function unBlock(){
    
    url = '<?php echo HTTP_LIB_PATH;?>/BlockStudent/ajaxInitDelete.php';  

    if(false===confirm("Do you want to unblock selected Ids?")) {
          return false;
    }
    else {
        var unBlockId="";
	    var counter=document.getElementsByName('chb[]').length;
        
        blockId='0';
 	    for(var i=0;i<counter;i++){
 	       if(document.getElementsByName('chb[]')[i].checked==true) {
    	     blockId=blockId+","+document.getElementsByName('chb[]')[i].value;
 	       }
        }
        
	    new Ajax.Request(url,
        {
             method:'post',
             parameters: {blockId: blockId},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     return false;
                 }
                 else {
                     messageBox(trim(transport.responseText));
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }   
}


function printReport() {
    
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    
    var path='<?php echo UI_HTTP_PATH;?>/listBlockedStudentPrint.php?'+qstr;
    window.open(path,"CountryReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listBlockStudentReportCSV.php?'+qstr;
    window.location = path;
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/BlockStudent/listBlockStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
</body>
</html>

