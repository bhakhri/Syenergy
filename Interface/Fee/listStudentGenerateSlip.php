<?php
//-------------------------------------------------------
// Purpose: To generate student list functionality 
//
// Author : Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GenerateStudentSlip');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
$showTitle = "none";
$showData  = "none";
$showPrint = "none";
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Generate Student Slip</title>
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
var topPos = 0;
var leftPos = 0;



recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'allDetailsForm'; // name of the form which will be used for search

divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'ASC';
var queryString='';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
        displayWindow(dv,w,h);
        populateValues(id);
}




function getClass() { 
	form = document.allDetailsForm;
	if(trim(document.getElementById('rollNo').value)==''){
		form.classId.length = null;
		addOption(form.classId, '', 'Select');
		messageBox("<?php echo ENTER_NAME_ROLLNO;?>");
		document.getElementById('rollNo').focus();
		return false;
	}
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/GenerateStudentSlip/getClasses.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: {	rollNo: trim(document.getElementById('rollNo').value)
			},
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.classId.length = null;
			if(j==0 || len == 0) {
				addOption(form.classId, '', 'Select');
				return false;
			}
			else{
				for(i=0;i<len;i++) {
					addOption(form.classId, j[i].classId, j[i].className);
				}
			}
			
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function resetResult(){
	
	document.getElementById('resultsDiv').innerHTML=''; 	
	
	 document.getElementById("nameRow").style.display='none';
        document.getElementById("nameRow2").style.display='none';
        document.getElementById("resultRow").style.display='none';
         document.getElementById("resultRow0").style.display='none';
        document.getElementById("resultRow1").style.display='none'; 
        document.getElementById("resultRow2").style.display='none'; 
        
       // document.getElementById("adhocChargesHidden").style.display='none';
      
        
}


function validateAddForm() {

	/* START: search filter */
    var url = '<?php echo HTTP_LIB_PATH;?>/Fee/GenerateStudentSlip/ajaxInitList.php';  
	
    var queryString = '';
	var form = document.allDetailsForm;
    
  if(trim(document.getElementById('rollNo').value)==''){
       messageBox("<?php echo ENTER_NAME_ROLLNO;?>");
       document.getElementById('rollNo').focus();
       return false;
    }
    
    if(trim(document.getElementById('classId').value)==""){
      messageBox("<?php echo SELECT_CLASS; ?>");
      document.getElementById('classId').focus();
      return false;
    } 
    
    queryString = generateQueryString('allDetailsForm'); 
    
    new Ajax.Request(url,
    {
        method:'post',
        parameters:{ classId: (document.getElementById('classId').value), 
                     rollNo: trim(document.getElementById('rollNo').value)
                   },
            onCreate:function(transport){ showWaitDialog(true);},
            onSuccess: function(transport) {
                hideWaitDialog(true);
                if("<?php echo SELECT_FEE_CLASS;?>" == trim(transport.responseText)) { 
                   messageBox(trim(transport.responseText));  
                   document.getElementById('feeClassId').focus();
                   return false;
                }
                if("<?php echo ENTER_NAME_ROLLNO;?>" == trim(transport.responseText)) { 
                   messageBox(trim(transport.responseText));  
                   document.getElementById('studentRoll').focus();
                   return false;
                }
                
                if("<?php echo FEE_HEAD_NOT_DEFINE;?>" == trim(transport.responseText)) { 
                   messageBox(trim(transport.responseText));  
                   return false;
                }
                
                if("<?php echo STUDENT_NOT_EXIST;?>" == trim(transport.responseText)) { 
                   messageBox(trim(transport.responseText));  
                   document.getElementById('rollNo').focus();
                   return false;
                }            
               
               document.getElementById('resultsDiv').innerHTML=trim(transport.responseText);
               document.getElementById("nameRow").style.display='';
               document.getElementById("nameRow2").style.display='';
               document.getElementById("resultRow").style.display='';
               document.getElementById("resultRow0").style.display='';
                document.getElementById("resultRow1").style.display='';
               
       },
       onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
     }); 
       
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


function generateSlip(){
  
  var isSelected=0;  
  var formx = document.allDetailsForm;
  for(var i=0;i<formx.length;i++){
    if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]" && formx.elements[i].checked==true){
      isSelected=1;
      break;
    }
  }
  
  if(isSelected==0) {
     messageBox("Please select a fee head");   
     return false;   
  }
    
  if(trim(document.getElementById("comments").value)=='') {
     messageBox("Please enter reason for generate a student fee slip");   
     document.getElementById("comments").focus(); 
     return false;   
  }
  	if(document.getElementById("paymentDD").checked ==true){
  		if(trim(document.getElementById("ddNo").value)=='') {
	     messageBox("Please Enter DD No.");   
	     document.getElementById("ddNo").focus(); 
	     return false;   
	   }
	  if(trim(document.getElementById("ddDate").value)=='') {
	     messageBox("Please Enter DD Date");   
	     document.getElementById("ddDate").focus(); 
	     return false;   
	   }
	  if(trim(document.getElementById("ddBankName").value)=='') {
	     messageBox("Please Enter Bank Name");   
	     document.getElementById("ddBankName").focus(); 
	     return false;   
	   }  		
  	}
  queryString = generateQueryString('allDetailsForm'); 	
  path='<?php echo HTTP_LIB_PATH;?>/Fee/GenerateStudentSlip/studentFeeSlip.php?'+queryString;
  window.open(path,"Fee Slip","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


window.onload=function(){

	//alert("<?php echo $queryString?>");
   if("<?php echo $queryString?>"!=''){
       sendReq(listURL,divResultName,searchFormName,"<?php echo $queryString?>");
       document.getElementById("nameRow").style.display='';
       document.getElementById("nameRow2").style.display='';
       document.getElementById("resultRow").style.display='';
       document.getElementById("resultRow0").style.display='';
        document.getElementById("resultRow1").style.display='';
       
   }
   var roll = document.getElementById("rollNo");
   autoSuggest(roll);
}



function vanishData(){ 
    try {  
	       
        document.getElementById("nameRow").style.display='none';
        document.getElementById("nameRow2").style.display='none';

        document.getElementById("resultRow").style.display='none';
         document.getElementById("resultRow0").style.display='none';
        document.getElementById("resultRow1").style.display='none'; 
        document.getElementById("resultRow2").style.display='none';        
     
        
	form = document.allDetailsForm; 
	form.classId.length = null;
	addOption(form.classId, '', 'Select');	
    } catch(e){ }
}

function getDDdetails(){
	
	if(document.getElementById("paymentDD").checked==true){
		document.getElementById("ddDetails").style.display='';
		
	}else{
		document.getElementById("ddDetails").style.display='none';
		document.getElementById("ddBankName").value='';
		document.getElementById("ddNo").value='';
		document.getElementById("ddDate").value=''	;	
	}
	
	return false;
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fee/GenerateStudentSlip/listStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
