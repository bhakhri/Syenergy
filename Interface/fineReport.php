<?php
//-------------------------------------------------------
// Purpose: To generate assign subject to class from the database, and have add/edit/delete, search 
// functionality 
// Author : Saurabh Thukral
// Created on : (17.08.2012 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineList');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Fine Approval </title>
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
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(	new Array('checkAll','<input type="checkbox" name="checkbox2" value="checkbox" onClick="doAll()">','width="3%"','',false),
								new Array('srNo','#','width="2%" align=left','align=left',false), 
								new Array('className','Class','width=10% align=left','align=left',true),
								new Array('rollNo','Roll No.','width=8% align=left','align=left',true),                                
								new Array('studentName','Name','width=12% align=left','align=left',true), 
								new Array('fineCategoryName','Fine Category','width=12% align=left','align=left',true), 
								new Array('fineDate','Date','width=8% align=center','align=center',true), 
								new Array('issueEmployee','Fined By','width=10% align=left','align=left',true), 
								new Array('reason','Reason','width=12% align=left','align=left',true),
								new Array('statusReason','Status Reason','width=13% align=left','align=left',true),
								new Array('amount','Amount<span class="redColorBig">* </span>','width=9% align=right','align=right',true),
								new Array('imgStatus','Status','width=10% align=center','align=center',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxInitFineReportList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
divResultName  = 'results';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'ASC';

valShow = '0';

var queryString ='';
// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {

	displayFloatingDiv(dv,w,h);
    	populateValues(id);   
}

function statusReasonWindow(id,dv,w,h) {

	displayFloatingDiv(dv,w,h);
    	populateStatusReasonValues(id);   
}

function editAmountWindow(id,dv,w,h,amt) {
//alert(id);
	if(eval("document.getElementById('isSearchAproval"+id+"').value")=='0') {
      messageBox("Approval permission not available");  
	  return false;
	}

	displayFloatingDiv(dv,w,h);
	document.viewAmount.studentId.value = id;
	document.viewAmount.changeAmount.value = amt;
    //populateAmountValues(id,amt);   
    return false;
}


function populateValues(id) {
     url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxGetReason.php';
	 new Ajax.Request(url,
       {      
         method:'post',
         parameters: {fineStudentId: id},
			 
          onCreate: function() {
			showWaitDialog();
		 },
			 
		 onSuccess: function(transport){
			  hideWaitDialog();
		      j= trim(transport.responseText).evalJSON();
	          document.getElementById("innerReason").innerHTML = j.reason;
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

function populateStatusReasonValues(id) {
     url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxGetReason.php';
	 new Ajax.Request(url,
       {      
         method:'post',
         parameters: {fineStudentId: id},
          onCreate: function() {
			showWaitDialog();
		 },
		 onSuccess: function(transport){
			  hideWaitDialog();
		      j= trim(transport.responseText).evalJSON();
              if(j.statusReason=='') {
                j.statusReason = "<?php echo NOT_APPLICABLE_STRING;  ?>";
              }
	          document.getElementById("innerStatusReason").innerHTML = j.statusReason;
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

function getFineStudent(){
	queryString = '';  
	if(isEmpty(document.getElementById('status').value)){
       messageBox("<?php echo ENTER_SUBJECT_TO_CLASS?>");
	   //document.getElementById('saveDiv').style.display='none';
	   document.getElementById('saveDiv1').style.display='none';
	   document.getElementById('showTitle').style.display='none';
	   document.getElementById('showData').style.display='none';
	   document.getElementById('legend').style.display='none';
       document.getElementById('legend12').style.display='none';
	   document.getElementById('approve').style.display='none';
       //document.getElementById('approve12').style.display='none';
	   document.getElementById('results').innerHTML=" ";
	   document.listForm.classId.focus();
	   return false;
   }
   else{
	   //document.getElementById('saveDiv').style.display='';
	   //document.getElementById('saveDiv1').style.display='';
	   //document.getElementById('showTitle').style.display='';
	   //document.getElementById('showData').style.display='';
	   //document.getElementById('legend').style.display='';
	   //document.getElementById('legend12').style.display='';
	   //document.getElementById('approve').style.display='';
	  // document.getElementById('approve12').style.display='';
	   sendReq(listURL,divResultName,'listForm',''); 
	   queryString = generateQueryString('listForm');
   }      
		 
}

function clearText(){
	window.location.reload();
}


function insertForm() {
 
	 url = '<?php echo HTTP_LIB_PATH;?>/Fine/initFineUpdate.php';
	 var pars = generateQueryString('listForm');
	 var pars2 = generateQueryString('addStatusReason');
	 var totalPars = pars + '&' + pars2;

	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: totalPars,
		 onCreate: function() {
			 showWaitDialog(true); 
		 },
		 onSuccess: function(transport){

			 hideWaitDialog(true);
			 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {  
				 flag = true;
					 alert(trim(transport.responseText));
					 hiddenFloatingDiv('AddStatusReason');
					 clearText();
					 
					 return false;
			 } 
			 else {
				messageBox(trim(transport.responseText)); 
				document.getElementById('addForm').reset(); 
			 }
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function validateAddForm(frm, act) {
    
	var selected=0;
	formx = document.listForm;
   
	for(var i=1;i<formx.length;i++){
		if(formx.elements[i].type=="checkbox"){
		  if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]")) {
			id = formx.elements[i].value;
			if(eval("document.getElementById('isSearchAproval"+id+"').value")=='0') {
              messageBox("Approval permission not available");  
			  return false;
			}
		    selected++;
		  }
		}
	}
	if(selected==0){
		alert("<?php echo STUDENT_TO_FINE?>");
		return false;
	}
	var i = document.listForm.statusUpdate.options[document.listForm.statusUpdate.selectedIndex].text;
    if (i=="Unapprove"){
        i="Unapproval";
    } 
    else if (i=="Approve"){
        i="Approval";
    }
    else if (i=="Reject"){
        i="Rejection";
    }
	document.getElementById('divHeaderId2').innerHTML='Fine '+i+' Confirmation';
	displayWindow('AddStatusReason',400,400);
    //insertForm();
	return false;
}

function validateAddFineForm(frm, act) {
    
	if(document.getElementById("appproveReason").value=="") {
		alert("<?php echo SELECT_REASON?>");
		return false;
	}
    insertForm();
	return false;
}

function doAll(){

	formx = document.listForm;
	if(formx.checkbox2.checked){
		for(var i=1;i<formx.length;i++){
			if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
				formx.elements[i].checked=true;
			}
		}
	}
	else{
		for(var i=1;i<formx.length;i++){
			if(formx.elements[i].type=="checkbox"){
				formx.elements[i].checked=false;
			}
		}
	}
}

function checkSelect(){

	var selected=0;
	formx = document.listForm;
	for(var i=1;i<formx.length;i++){
		if(formx.elements[i].type=="checkbox"){
			if((formx.elements[i].checked) && (formx.elements[i].name!="checkbox2")){
				selected++;
			}
		}
	}
	if(selected==0)	{
		messageBox("<?php echo SUBJECT_TO_CLASS_ONE?>");
		return false;
	}
}

function printReport() {
    
  /* 
       //document.getElementById('approve12').style.display='none';
  path='<?php echo UI_HTTP_PATH;?>/displayFineApprovalReport.php?status='+document.getElementById('status').value+'&rollNo='+document.getElementById('rollNo').value+'&fineCategory='+document.getElementById('fineCategory').value+'&startDate='+document.getElementById('startDate').value+'&toDate='+document.getElementById('toDate').value+'&timeTable='+document.getElementById('timeTable').value+'&classId='+document.getElementById('classId').value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
  */
   path='<?php echo UI_HTTP_PATH;?>/displayFineApprovalReport.php?'+queryString+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
   window.open(path,"DisplayFineReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
/*    var 
    path='<?php echo UI_HTTP_PATH;?>/displayFineApprovalCSV.php?status='+document.getElementById('status').value+'&rollNo='+document.getElementById('rollNo').value+'&fineCategory='+document.getElementById('fineCategory').value+'&startDate='+document.getElementById('startDate').value+'&toDate='+document.getElementById('toDate').value+'&timeTable='+document.getElementById('timeTable').value+'&classId='+document.getElementById('classId').value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
*/
	path='<?php echo UI_HTTP_PATH;?>/displayFineApprovalCSV.php?'+queryString+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}


window.onload=function() {
	getFineStudent();
	document.listForm.reset();
    valShow = '1';
    
	var s=querySt('status');    
    if(s){
	if (s == 1) {
		document.getElementById("status").selectedIndex = 0;
	}
	if (s == 2) {
		document.getElementById("status").selectedIndex = 1;
	}
	if (s == 3) {
		document.getElementById("status").selectedIndex = 2;
	}
     getFineStudent();
	}
    var roll = document.getElementById("rollNo");
    roll.focus();
    autoSuggest(roll);
}


function editFineAmount() {
	     var url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxEditFineAmount.php';		 
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
				  studentId: (trim(document.viewAmount.studentId.value)),
				  changeAmount: (trim(document.viewAmount.changeAmount.value))
			 },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);

                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('ViewAmount');
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


function getShowDetail() {
   document.getElementById("showhideSeats").style.display='';
   document.getElementById("lblMsg").innerHTML="Please Click to Hide Advance Search";
   document.getElementById("showInfo").src = "http://localhost/LeapCC/Storage/Images/arrow-down.gif"
   if(valShow==0) {
     document.getElementById("showhideSeats").style.display='none';
     document.getElementById("lblMsg").innerHTML="Please Click to Show Advance Search"; 
     document.getElementById("showInfo").src = "http://localhost/LeapCC/Storage/Images/arrow-up.gif"
     valShow=1;
   }
   else {
     valShow=0;  
   }
}




function resetForm(){
		form = document.listForm;
		document.getElementById('saveDiv1').style.display='';
	    document.getElementById('showTitle').style.display='';
	    document.getElementById('showData').style.display='';
	    document.getElementById('legend').style.display='';
        document.getElementById('legend12').style.display='';
	    document.getElementById('approve').style.display='';			
}

function getAllDegree(str) {
    //alert(str);
    form = document.listForm;    
    
        
    searchClassStatus=-1;
    
    if(form.searchClassStatus[0].checked==true) {
      searchClassStatus =1;
    }
    if(form.searchClassStatus[1].checked==true) {
      searchClassStatus =3;
    }
    if(form.searchClassStatus[2].checked==true) {	
      searchClassStatus =4;
    }
    
      
    param = "searchClassStatus="+searchClassStatus+"&searchMode="+str; 
     
    
    var url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxInitFineReportList.php';
    
    new Ajax.Request(url,
    {
        method:'post',
        parameters: param, 
        asynchronous:false,
        onCreate: function(){
             showWaitDialog(true);
        },
        onSuccess: function(transport){ 
            hideWaitDialog(true);                        
            
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    }); 
}

function getShowSearch(val) {
   showStatus=''; 
   if(val=='') {
     showStatus='none';  
   } 
   
   for(i=1;i<=4;i++) {
     id = "searchDt"+i;
     eval("document.getElementById('"+id+"').style.display=showStatus");
   }
}

function getDateCheck() {
   document.getElementById("startDate").value="";    
   document.getElementById("toDate").value="";
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fine/listFineReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

