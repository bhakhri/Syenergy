<?php
//-------------------------------------------------------
// Purpose: To generate student list functionality 
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BulkFineStudentMaster');
define('ACCESS','view');
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==1){
 UtilityManager::ifNotLoggedIn();
}
else if($roleId==2){
 UtilityManager::ifTeacherNotLoggedIn();
}
else if($roleId==5){
  UtilityManager::ifManagementNotLoggedIn();
}
else{
  UtilityManager::ifNotLoggedIn();  
}
$showTitle = "none";
$showData  = "none";
$showPrint = "none";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Student Bulk Fine Master</title>
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
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                               new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%" align=\"center\"','align=\"center\"',false),   
                               new Array('universityRollNo','URoll No.','width="8%"','',true),
                               new Array('rollNo','Roll No.','width="8%"','',true), 
                               new Array('studentName','Name','width="15%"','',true), 
                               new Array('className','Class','width="15%"','',true),
                               new Array('studentMobileNo','Mobile','width="8%"','',true), 
                               new Array('imgSrc','Photo','width="5%" align="center"','align="center"',false));
                               //new Array('studentFine','Fine','width="5%" align="right"','align="right"',false)

recordsPerPage =500;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxInitBulkFineList.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
//addFormName    = 'AddState';   
//editFormName   = 'EditState';
//winLayerWidth  = 315; //  add/edit form width
//winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteState';
divResultName  = 'results';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'ASC';
var queryString='';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
        displayWindow(dv,w,h);
        populateValues(id);
}

function validateAddForm() {
 

	/* START: search filter */
    amount = ''; 
    document.getElementById("fineCategoryId").selectedIndex=0;  
    //document.getElementById("dueStatus").selectedIndex=0;  
    document.getElementById("fineDate1").value="<?php echo date('Y-m-d'); ?>";
    document.getElementById("remarksTxt").value="";  
    remarksTxt = '';
    document.getElementById('fineResultDiv').innerHTML='';
    page=1;

	//showHide("hideAll");
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    document.getElementById("fineRow").style.display=''; 
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
	/* END: search filter*/

}



/* function to print all student report*/
function printReport() {
	 
	queryString += '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	 
    qtr = "<?php echo $queryString?>";
	//if(qtr!='')
		//queryString = qtr;

//alert(queryString);
	form = document.allDetailsForm;
	path='<?php echo UI_HTTP_PATH;?>/searchStudentReportPrint.php?listStudent=1&'+queryString;
	//alert(path);
	window.open(path,"MissedAttendanceReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

//populate list
window.onload=function(){
   getRoleInstituteClass();  
}

function getRoleInstituteClass() {
	
	var url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxFineRoleList.php'; 
	document.getElementById('searchAllClassId').value='';
	new Ajax.Request(url,
    {
      method:'post',
      asynchronous:false,      
      onCreate: function() {
                 showWaitDialog(true);
             },
	     onSuccess: function(transport){
         hideWaitDialog(true);
         var ret=trim(transport.responseText).split('!~~!!~~!'); 
		 
		 var j0 = eval(ret[0]);
         var j1 = eval(ret[1]);
         var j2= eval(ret[2]);
        
		document.allDetailsForm.fineCategoryId.length = null; 
		addOption(document.allDetailsForm.fineCategoryId,'', 'Select');      
		for(i=0;i<j0.length;i++) { 
		  addOption(document.allDetailsForm.fineCategoryId, j0[i].fineCategoryId, j0[i].fineCategoryName);
		}
		
		searchAllClassId = "";
		document.allDetailsForm.fineClassId.length = null; 
		addOption(document.allDetailsForm.fineClassId,'', 'All');      

		document.allDetailsForm.hiddenFineClassId.length = null; 
		addOption(document.allDetailsForm.hiddenFineClassId,'', 'All');      
		for(i=0;i<j1.length;i++) { 
          str = j1[i].classId+"~"+j1[i].instituteId;
		  addOption(document.allDetailsForm.hiddenFineClassId, str, j1[i].className);
		  addOption(document.allDetailsForm.fineClassId, j1[i].classId, j1[i].className);
		  if(searchAllClassId!="") {
            searchAllClassId += ","; 
		  }
		  searchAllClassId += j1[i].classId;
		}
		document.getElementById('searchAllClassId').value=searchAllClassId;

		document.allDetailsForm.fineInstituteId.length = null; 
		addOption(document.allDetailsForm.fineInstituteId,'', 'All');      
		for(i=0;i<j2.length;i++) { 
		  addOption(document.allDetailsForm.fineInstituteId, j2[i].instituteId, j2[i].instituteCode);
		}
      },
      onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}

function getClass() {

   fineInstituteId = document.allDetailsForm.fineInstituteId.value;
   find='';
   searchAllClassId = "";

   document.allDetailsForm.fineClassId.length = null; 
   addOption(document.allDetailsForm.fineClassId,'', 'All'); 

   var len= document.getElementById('hiddenFineClassId').options.length;
   var t=document.getElementById('hiddenFineClassId');

   for(k=1;k<len;k++) { 
	 str = t.options[k].value;
	 rval=str.split('~');
 	 if( (rval[1] == fineInstituteId) || (fineInstituteId=='')  ) {      
	   find='1';
	   addOption(document.getElementById('fineClassId'), rval[0], t.options[k].text);
	   if(searchAllClassId!="") {
         searchAllClassId += ","; 
	   }
	   searchAllClassId += rval[0];
	 }
	 else if(find=='1') {
	   break;
	 }
   } 
   document.getElementById('searchAllClassId').value=searchAllClassId;
}


function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');   
      return false;
    }
	 if(document.getElementById('helpChk').checked == false) {
		 return false;
	 }
    //document.getElementById('divHelpInfo').innerHTML=title;      
    document.getElementById('helpInfo').innerHTML= msg;   
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);
    
    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}

function doAll(){
    formx = document.allDetailsForm;
    if(formx.checkbox2.checked){
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
                formx.elements[i].checked=true;
            }
        }
    }
    else{
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox"  && formx.elements[i].name=="chb[]"){
                formx.elements[i].checked=false;
            }
        }
    }
}

function validateAddForm1() {
    
    var url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxInitStudentBulkFineAdd.php';      
    
    var formx = document.allDetailsForm;
    
    document.getElementById('fineResultDiv').innerHTML='';
     
    var cdate="<?php echo date('Y-m-d'); ?>"; 
    var fieldsArray = new Array(
        new Array("fineCategoryId","<?php echo ENTER_FINE_CATEGORY; ?>"),
        new Array("fineAmount","<?php echo ENTER_FINE_AMOUNT; ?>"),
        new Array("remarksTxt","<?php echo ENTER_FINE_REASON; ?>")
    );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("formx."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("formx."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            if(document.allDetailsForm.fineAmount.value) {
                 reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
                 if (!reg.test(document.allDetailsForm.fineAmount.value)){
                    messageBox("<?php echo ENTER_FINE_AMOUNT_TO_NUM; ?>");
                    document.allDetailsForm.fineAmount.focus();
                    return false;
                 }
            }
            if(trim(eval("formx."+(fieldsArray[i][0])+".value")).length<10 && fieldsArray[i][0]=='remarksTxt' ) {
                messageBox("<?php echo FINE_REASON_LENGTH;?>"); 
                eval("formx."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            } 
        }
    }
        
    if(!dateDifference(document.allDetailsForm.fineDate1.value,cdate,"-")){
       messageBox("<?php echo FINE_DATE_VALIDATION;?>"); 
       document.allDetailsForm.fineDate1.focus();
       return false;
    }
 
    var selected=0;
    var studentCheck='';
    for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox"){
            if((formx.elements[i].checked) && (formx.elements[i].name!="checkbox2")){
                if(studentCheck=='') {
                   studentCheck=formx.elements[i].value; 
                }
                else {
                    studentCheck = studentCheck + ',' +formx.elements[i].value; 
                }
                selected++;
            }
        }
    }
    if(selected==0)    {
       alert("Please select atleast one record!");
       return false;
    }
    
    new Ajax.Request(url,
    {
      method:'post',
      asynchronous:false,      
      parameters:{studentId: studentCheck, 
                  amount: trim(document.allDetailsForm.fineAmount.value), 
                  fineCategoryId: document.allDetailsForm.fineCategoryId.value, 
                  fineDate1: document.allDetailsForm.fineDate1.value, 
                  remarksTxt: trim(document.allDetailsForm.remarksTxt.value), 
                  //dueStatus: trim(document.allDetailsForm.dueStatus.value),
                  sortField : sortField,
                  sortOrderBy : sortOrderBy
                 },
      onCreate: function() {
                 showWaitDialog(true);
             },
      onSuccess: function(transport){
         hideWaitDialog(true);
         var ret=trim(transport.responseText).split('!~~!!~~!');  
         var j0 = trim(ret[0]);     
         var j1 = trim(ret[1]);    
         $chk=0;
         if("<?php echo SUCCESS;?>" == j0 && j1=='') {
            messageBox("Fine assigned successfully");  
            hideResults();
            $chk=1;
            //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
			return false;
         }
         else if("<?php echo FAILURE;?>" == j0 && j1=='') {
            messageBox("<?php echo FAILURE; ?>");  
			return false;
         }
         if(j1!='') {
            $chk=1;  
            document.getElementById('fineResultDiv').innerHTML=j1; 
            displayWindow('divNotFineAlreadyInfo',600,400); 
         }
         if($chk==0) {
           messageBox("<?php echo FAILURE ;?>");
         } 
      },
      onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}

function hideResults() {
	 form = document.allDetailsForm;
	 form.fineAmount.value='';
	 form.remarksTxt.value='';
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
    document.getElementById('fineRow').style.display='none';
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fine/listBulkFineStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>