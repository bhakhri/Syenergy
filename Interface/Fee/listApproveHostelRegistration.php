<?php
//-------------------------------------------------------
// Purpose: To generate student payment history listfunctionality 
// Author : Nishu Bindal
// Created on : (08-may-2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
global $sessionHandler;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ApproveHostelRegistration');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>:Approve/Unapprove Hostel Registration</title>
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


<script type="text/javascript">
window.onload = function () {
  
}
</script>
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
  	  	  	  	
var tableHeadArray =  new Array(new Array('srNos','#','width="2%"','',false),
							   new Array('checkall','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"checkAll();\">','width="2%"','align=\"center\"',false),    
                               new Array('dateOfEntry','Apply Date','width="10%"','align="left"',true), 
                               new Array('studentName', 'Name','width="12%"','align="left"',true) , 
                               new Array('rollNo', 'Roll No.','width="10%"','align="left"',true), 
                               new Array('className', 'Class','width="10%"','align="center"',false),     
                                new Array('contactNo', 'Contact No','width="10%"','align="center"',false),                                                        
                               new Array('roomTypeArray', 'Hostel Room Type ','width="10%"','align="center"',false), 
                               new Array('registerStatus', 'Registration Status','width="10%"','align="center"',false),
                               new Array('showAction', 'Action&nbsp;<select name=\"commonRegistrationStatus\" id=\"commonRegistrationStatus\"  onchange=\"changeRegistrationValueStatus(this.value);\"  style=\"width:100px;\"><option value=\"0\">Select</option><option value=\"2\">Approve</option><option value=\"3\">Reject</option><option value=\"4\">Pending</option></select>','width="12%"','align="center"',false),
                               new Array('commentsStatus', 'Reason&nbsp;<input type=\"text\" style=\"width:120px;\" name=\"reason\" id=\"reason\" onchange=\"changeComments(this.value);\"/>','width="13%"','align="center"',true));                
                  	                 
                  	   

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Fee/ApproveHostelRegistration/ajaxApproveHostelRegistrationList.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
divResultName  = 'results';
page=1; //default page
sortField = 'receiptDate';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

function changeRegistrationValueStatus(val){
   formx = document.allDetailsForm;
   var inputs = document.getElementsByName('commonRegistrationStatus[]');
	  var n = inputs.length;
	  for(var i=0 ;i< n ;i++){
	  	  if(document.getElementsByName('commonRegistrationStatus[]').selectedIndex!='0'){	  	
	  	inputs[i].selectedIndex = val-1;
	  	}else{	  		
	  		inputs[i].selectedIndex='0';  
	  	}
	  }
 }

function changeComments(val){
	
  formx = document.allDetailsForm;
    for(var i=1;i<formx.length;i++){
      if(formx.elements[i].type=="textarea" && formx.elements[i].name=="reason[]") {
        if(formx.reason.value!=''){  
          formx.elements[i].value=formx.reason.value;
        }
        else {
          formx.elements[i].value='';  
        }
      }
    }
 
}


function validateAddForm(frm) {

	if(document.getElementById('fromDate').value=='' && document.getElementById('toDate').value!='') {
       messageBox("Select From Date");
       //eval("frm.fromDate.focus();");
       return false;
    }
    
    
    if(document.getElementById('fromDate').value!='' && document.getElementById('toDate').value=='') {
       messageBox("Select To Date");
       //eval("frm.fromDate.focus();");
       return false;
    }
    
    if(document.getElementById('fromDate').value!='' && document.getElementById('toDate').value!='') {
	  if(!dateDifference(document.getElementById('fromDate').value,document.getElementById('toDate').value,'-') ) {
	     messageBox("<?php echo DATE_VALIDATION;?>");
		 //eval("frm.fromDate.focus();");
		 return false;
	  }
    }
    
    sendReq(listURL,divResultName,'allDetailsForm','&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);     
    //document.getElementById('saveDiv').style.display='';
    document.getElementById('showTitle').style.display='';
    document.getElementById('showData').style.display='';
     document.getElementById('saveDiv').style.display='';
    document.getElementById('nameRow2').style.display='';
    
	return false;
}


function checkAll() {
    formx = document.allDetailsForm;
    for(var i=1;i<formx.length;i++){
      if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]") {
        if(formx.checkbox2.checked){  
          formx.elements[i].checked=true;
        }
        else {
          formx.elements[i].checked=false;  
        }
      }
    }
}

function approveHostelRegistration(){
	formx = document.allDetailsForm;
    
 var form = document.allDetailsForm;
    var url = '<?php echo HTTP_LIB_PATH;?>/Fee/ApproveHostelRegistration/ajaxAddStudentHostelRegistration.php';
    var pars =generateQueryString('allDetailsForm')
    
    new Ajax.Request(url,
    {
        method:'post',
        asynchronous : false,
        parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
           // var j = eval(trim(transport.responseText));
              var ret=trim(transport.responseText); 
                if(ret=="<?php echo SUCCESS;?>"){
                    messageBox("<?php echo SUCCESS;?>"); 
              		 return false;                 
                }else{
                	   messageBox(ret); 
              		 return false;                 
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
    require_once(TEMPLATES_PATH . "/Fee/ApproveHostelRegistration/listApproveHostelRegistrationContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>

