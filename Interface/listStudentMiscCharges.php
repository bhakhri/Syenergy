<?php
//-------------------------------------------------------
// Purpose: To generate student list functionality 
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentMiscCharges');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
$showTitle = "none";
$showData  = "none";
$showPrint = "none";
//require_once(BL_PATH . "/Student/initList.php");
$queryString =  $_SERVER['QUERY_STRING'];
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Student Misc Charges</title>
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
                               new Array('studentName','Name','width="15%"','',true), 
                               new Array('rollNo','Roll No.','width="8%"','',true), 
                               new Array('universityRollNo','Univ. No.','width="8%"','',true),
                               new Array('regNo','Reg. No.','width="8%"','',true),
                               new Array('activeClassName','Active Class','width="12%"','',true),
                               new Array('imgSrc','Photo','width="5%" align="center"','align="center"',false),
                               new Array('miscChargesValue','Amount','width="5%"','',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/StudentMiscCharges/ajaxInitList.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
//addFormName    = 'AddState';   
//editFormName   = 'EditState';
//winLayerWidth  = 315; //  add/edit form width
//winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteState';
divResultName  = 'results';
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

function getMiscCharges() {
                        
    var chk=0;
    if(document.allDetailsForm.amountCheck[1].checked==true) {
      chk=1;  
    }    
    
    var formx = document.allDetailsForm;
    var obj=formx.getElementsByTagName('INPUT');
    var total=obj.length;
    for(var i=0;i<total;i++) {
      if(obj[i].type.toUpperCase()=='HIDDEN' && obj[i].name.indexOf('miscStudentId[]')>-1) {
         // blank value check 
         id =obj[i].value;
         if(chk==1) {
           eval("document.getElementById('miscChargesId"+id+"').value=trim(document.getElementById('miscCharges').value)");   
         }
         else if(eval("document.getElementById('miscUpdated"+id+"').value")=='') {
           eval("document.getElementById('miscChargesId"+id+"').value=trim(document.getElementById('miscCharges').value)");
         }
      }
    } 
    return false;
}

function validateAddForm() {
 
	/* START: search filter */

	queryString = '';
	form = document.allDetailsForm;
    
    document.getElementById("miscChargesHidden").style.display='none';
    document.getElementById("miscCharges").value='';
    
    if(document.getElementById('feeClassId').value==''){
       messageBox("<?php echo "Select Fee Class";?>");
       document.getElementById('feeClassId').focus();
       return false;
    }
    
    if(document.getElementById('feeHead').value==''){
       messageBox("<?php echo SELECT_FEEHEAD;?>");
       document.getElementById('feeHead').focus();
       return false;
    }

    queryString = generateQueryString('allDetailsForm'); 
    
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,true);    
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
    document.getElementById("miscChargesHidden").style.display='';
	/* END: search filter*/
    
    return false;
}

/* function to print all student report*/
function printReport() {
	 
	queryString += '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	 
    qtr = "<?php echo $queryString?>";
	//if(qtr!='')
		//queryString = qtr;

//alert(queryString);
	form = document.allDetailsForm;
    
    var alumniStudent=1;
    if(form.alumniStudent[0].checked==true){
       alumniStudent=1; 
    }
    else if(form.alumniStudent[1].checked==true){
       alumniStudent=2; 
    }
    else{
       alumniStudent=3; 
    }
    
    queryString += '&alumniStudent='+alumniStudent;
    
	path='<?php echo UI_HTTP_PATH;?>/searchStudentReportPrint.php?listStudent=1&'+queryString;
	//alert(path);
	window.open(path,"MissedAttendanceReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to print student profile report*/
function printStudentReport(studentId,classId) {
	
	var form = document.allDetailsForm;
	path='<?php echo UI_HTTP_PATH;?>/studentPrint.php?studentId='+studentId+'&classId='+classId;
	window.open(path,"MissedAttendanceReport","status=1,menubar=1,scrollbars=1, width=700, height=500, top=100,left=50");
}

/* function to print all student report*/
function printStudentCSV() {

	queryString += '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    qtr = "<?php echo $queryString?>";
	if(qtr!='')
		queryString = qtr;
	
    var alumniStudent=1;
    form = document.allDetailsForm;
    if(form.alumniStudent[0].checked==true){
       alumniStudent=1; 
    }
    else if(form.alumniStudent[1].checked==true){
       alumniStudent=2; 
    }
    else{
       alumniStudent=3; 
    }
    
    queryString += '&alumniStudent='+alumniStudent;
    
	window.location='searchStudentReportCSV.php?'+queryString;

	/*queryString += '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    qtr = "<?php echo $queryString?>";
	if(qtr!='')
		queryString = qtr;

	document.getElementById('generateCSV').href='searchStudentReportCSV.php?queryString='+queryString;
	document.getElementById('generateCSV1').href='searchStudentReportCSV.php?queryString='+queryString;
	*/
}
//populate list
window.onload=function(){

	//alert("<?php echo $queryString?>");
   if("<?php echo $queryString?>"!=''){
       sendReq(listURL,divResultName,searchFormName,"<?php echo $queryString?>");
       document.getElementById("nameRow").style.display='';
       document.getElementById("nameRow2").style.display='';
       document.getElementById("resultRow").style.display='';
   }
   var roll = document.getElementById("rollNo");
 autoSuggest(roll);
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

function vanishData(){
    document.getElementById("nameRow").style.display='none';
    document.getElementById("nameRow2").style.display='none';
    document.getElementById("resultRow").style.display='none';
}

function addStudentMiscCharges() {
                 
      var url = '<?php echo HTTP_LIB_PATH;?>/StudentMiscCharges/addMiscCharges.php';
      
      /*
        if(document.getElementById('feeCycle').value==''){
          messageBox("<?php echo SELECT_FEECYCLE;?>");
          document.getElementById('feeCycle').focus();
          return false;
        }
      */
      
      if(document.getElementById('feeClassId').value==''){
         messageBox("Select Fee Class");
         document.getElementById('feeClassId').focus();
         return false;
      } 
   
      if(document.getElementById('feeHead').value==''){
         messageBox("<?php echo SELECT_FEEHEAD;?>");
         document.getElementById('feeHead').focus();
         return false;
      }
   
      var errorMsg = '';
      var studentString='';
      formx = document.allDetailsForm;   
      for(var i=1;i<formx.length;i++) {  
         if(formx.elements[i].type=="hidden" && formx.elements[i].name=="miscStudentId[]") {
            id = formx.elements[i].value;   
            /*if(eval("document.getElementById('miscChargesId"+id+"').value")!='') {
              if(parseFloat(eval("document.getElementById('miscChargesId"+id+"').value"))<0){
                eval("document.getElementById('miscChargesId"+id+"').className='inputboxRed'");
                errorMsg=1;
              }
              else if(!isDecimal(eval("document.getElementById('miscChargesId"+id+"').value"))){
                eval("document.getElementById('miscChargesId"+id+"').className='inputboxRed'");
                errorMsg=1;                 
              }
            } */
            if(studentString!='') {  
              studentString = studentString + "!~!~!";
            }
            studentString = studentString+id+"_"+eval("document.getElementById('miscChargesId"+id+"').value"); 
         } 
     } 
     
    
     if(errorMsg==1) {
       messageBox("Please enter numeric value and values greater than or equal to zero");   
       return false;
     }
     
     //params = generateQueryString('allDetailsForm');  
     new Ajax.Request(url,
     {
         method:'post',
         parameters: {
             //feeCycle : document.getElementById('feeCycle').value, 
             feeHead : document.getElementById('feeHead').value, 
             feeClassId : document.getElementById('feeClassId').value, 
             studentString : studentString
         },
         asynchronous:false,                                        
         onSuccess: function(transport){
           if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
              showWaitDialog(true);
           }
           else {
             hideWaitDialog(true);
             messageBox(trim(transport.responseText)); 
             if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {   
                sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                //location.reload();
                return false;
             } 
           }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

function getFeeCylceClasses() {
     
     var url = '<?php echo HTTP_LIB_PATH;?>/FeeHeadValues/ajaxGetFeeCycleClasses.php';   
     
     document.allDetailsForm.feeClassId.length = null;
     addOption(document.allDetailsForm.feeClassId, '', 'Select');
     feeCycleId = document.allDetailsForm.feeCycle.value;
     
     new Ajax.Request(url,
     {
         method:'post',
         parameters: { feeCycleId: feeCycleId },
         asynchronous:false,
         onCreate: function(transport){
           //showWaitDialog(true);
         },
         onSuccess: function(transport){
           //hideWaitDialog(true);
           j = eval('('+ transport.responseText+')');
           len = j.length;
           document.allDetailsForm.feeClassId.length = null;
           addOption(document.allDetailsForm.feeClassId, '', 'Select');
           for(i=0;i<len;i++) {
             addOption(document.allDetailsForm.feeClassId, j[i]['classId'], j[i]['className']);                  
           }
         },
         onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
      });
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentMiscCharges/listStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
