<?php
//-----------------------------------------------------------------------------
//  To generate Studnet Grade Card functionality      
//
//
// Author :Parveen Sharma
// Created on : 06-03-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentGradeCardReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Student Grade Card Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
var allSemester='';
var allTrimester='';
var tableHeadArray = new Array(
                     new Array('srNo','#','width="3%"','',false), 
                     new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%"','align=\"left\"',false), 
                     //new Array('checkAll','<input type="checkbox" id="checkbox2" name="checkbox2" value="checkbox" onClick="doAll()">','width="3%"','',false),
                     new Array('rollNo','Roll No.','width="10%"','align="left"',true), 
                     new Array('studentName','Name','width="10%"','align="left"',true), 
                     new Array('fatherName','Father`s Name','width="15%"','align="left"',true),  
                     new Array('DOB','Date of Birth','width="12%"','align="center"',true),
                     //new Array('programme','Branch','width="15%"','align="left"',true),
                     new Array('studentMobileNo','Contact No.','width=15%','align="left"',true),
                     new Array('permAddress','Perm. Address','width="20%"','align="left"',false),
                     new Array('corrAddress','Corr. Address','width="20%"','align="left"',false)
                  );

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/GradeCardReport/scInitStudentGradeCardReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'allDetailsForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'Asc';
 //This function Validates Form 
 
var myQueryString;
var studentCheck;
var semesterCheck;

function validateAddForm() {
   
   page=1;
   
   if(trim(document.getElementById('batchId').value)==""){
      messageBox("<?php echo SELECT_BATCH; ?>");
      document.getElementById('batchId').focus();
      return false;
   }   
    
   if(trim(document.getElementById('degreeId').value)==""){
       messageBox("<?php echo SELECT_DEGREE; ?>");
       document.getElementById('degreeId').focus();
       return false;
   }   
   
   if(trim(document.getElementById('branchId').value)==""){
       messageBox("<?php echo SELECT_BRANCH; ?>");
       document.getElementById('branchId').focus();
       return false;
   } 
   
   if(document.getElementById('semesterId').value=='') {
       alert("Please select atleast one semester!");
       return false;
   }
   
   var form = document.allDetailsForm;   
   var semesterCheck = getCommaSeprated("semesterId");
   
   document.getElementById("nameRow").style.display='';
   document.getElementById("nameRow2").style.display='';
   document.getElementById("resultRow").style.display='';
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}

function getDegreeData() {

    document.allDetailsForm.degreeId.length = null;  
    addOption(document.allDetailsForm.degreeId, '', 'Select');
    
    document.allDetailsForm.branchId.length = null;  
    addOption(document.allDetailsForm.branchId, '', 'Select');
    
    if(document.getElementById('batchId').value=='') {
       return;
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/GradeCardReport/scAjaxGetDegree.php';
    
    new Ajax.Request(url,
    {
         method:'post',
         parameters: { batchId:  document.getElementById('batchId').value },  
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + transport.responseText + ')');
            len = j.length;
            
            document.allDetailsForm.degreeId.length = null;  
            addOption(document.allDetailsForm.degreeId, '', 'Select'); 
            for(i=0;i<len;i++) { 
              addOption(document.allDetailsForm.degreeId, j[i].degreeId, j[i].degreeCode);    
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });   
}


function getBranchData() {

    document.allDetailsForm.branchId.length = null;  
    addOption(document.allDetailsForm.branchId, '', 'Select');
    
    if(document.getElementById('batchId').value=='') {
       return false;
    }
    
    if(document.getElementById('degreeId').value=='') {
       return false;
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/GradeCardReport/scAjaxGetBranch.php';
    
    new Ajax.Request(url,
    {
         method:'post',
         parameters: { batchId:  document.getElementById('batchId').value,
                       degreeId:  document.getElementById('degreeId').value
                     },  
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + transport.responseText + ')');
            len = j.length;
            
            document.allDetailsForm.branchId.length = null;  
            addOption(document.allDetailsForm.branchId, '', 'Select'); 
            for(i=0;i<len;i++) { 
              addOption(document.allDetailsForm.branchId, j[i].branchId, j[i].branchCode);    
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });   
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
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
                formx.elements[i].checked=false;
            }
        }
    }
}

function hideDetails() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}


function hideDetails2() {
   document.getElementById("resultRow").style.display='none';
   document.getElementById('nameRow').style.display='none';
   document.getElementById('nameRow2').style.display='none';  
   
   frm = document.allDetailsForm;
   frm.batchId.length = null;
   addOption(frm.batchId, '', 'Select');
   
   frm.degreeId.length = null;
   addOption(frm.degreeId, '', 'Select');
   
   frm.semesterId.length = null;
}

function hideDetails1() {

    document.allDetailsForm.semesterId.length = null;        
    document.getElementById("degreeId").selectedIndex=0;
    hideDetails();
}


// Course Trimester
function getTrimesterData() {

    hideDetails();
    
    allSemester = "";
    allTrimester = "";   
    document.allDetailsForm.semesterId.length = null;   
    if(document.getElementById('degreeId').value=='') {
       return;
    }
    
    if(document.getElementById('branchId').value=='') {
       return;
    }
    
    if(document.getElementById('batchId').value=='') {
       return;
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/GradeCardReport/scAjaxGetStudyPeriod.php';
    
    new Ajax.Request(url,
    {
         method:'post',
         parameters: { degreeId: document.getElementById('degreeId').value,
                       branchId:  document.getElementById('branchId').value,  
                       batchId:  document.getElementById('batchId').value
                     },  
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + transport.responseText + ')');
            len = j.length;
            document.allDetailsForm.semesterId.length = null;   
            if(len > 0) {
              for(i=0;i<len;i++) { 
                addOption(document.allDetailsForm.semesterId, j[i].studyPeriodId, j[i].periodName);
                if(allSemester=="") {
                  allSemester = trim(j[i].studyPeriodId);
                }
                else {
                  allSemester = allSemester +","+trim(j[i].studyPeriodId);  
                }
                allTrimester = allSemester;
              }
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}


function printReport3() {                    
    
       var form = document.allDetailsForm;   
       var trimesterCheck = getCommaSeprated("semesterId");
       
       var selected=0;
       studentCheck='';
        
       formx = document.allDetailsForm;
       for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox"  && (formx.elements[i].name=="chb[]")){
                if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]")){
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
         alert("Please select atleast one student record");    
         return false;
       }
        
       s=0;
       a=0;
       sc=0;
       bc=0;
       sessiondate=0;
       reexamChk=0;
       specializationChk=0;
       
       if(document.allDetailsForm.reexamChk.checked==true) {
         reexamChk = 1;
       }
       if(document.allDetailsForm.specializationChk.checked==true) {
         specializationChk = 1;
       }
       if(document.allDetailsForm.signature.checked==true) {
         s = 1;
       }
       if(document.allDetailsForm.address.checked==true) {
         a = 1;
       }
       if(document.allDetailsForm.sessionChk.checked==true) {
         sc = 1;
       }
       
       if(document.allDetailsForm.branchChk.checked==true) {
         bc = 1;
       }     
             
       if(document.allDetailsForm.sessionDateChk.checked==true) {
         sessiondate = 1;
       }                          
           
       printTri = 2;
       if(document.allDetailsForm.printTri[0].checked==true) {
         printTri = 1;
       }              
       else {
         printTri = 2;
       }            

       var reapparMsg=escape(trim(document.allDetailsForm.reapparMsg.value));
       var headValue = escape(trim(document.allDetailsForm.headValue.value));
       
       query = '&studentId='+studentCheck+'&sessiondate='+sessiondate+'&signature='+s+'&branchChk='+bc+'&address='+a;
       query = query + '&trimester='+trimesterCheck+'&allTrimester='+allTrimester+'&sessionChk='+sc+'&reexamChk='+reexamChk+'&specializationChk='+specializationChk;
       query = query + '&printTri='+printTri+'&reapparMsg='+reapparMsg+'&headValue='+headValue;
       path='<?php echo UI_HTTP_PATH;?>/studentGradeCardPrint.php?degreeId='+document.getElementById('degreeId').value+'&batchId='+document.getElementById('batchId').value+query;
       
     // Include CGPA Details
    /* 
       cd=0;
       if(document.allDetailsForm.cgpaDetails.checked==true) {
         cd = 1;
       } 
       path='<?php echo UI_HTTP_PATH;?>/scStudentGradeCardPrint.php?studentId='+studentCheck+'&signature='+s+'&address='+a+'&trimester='+trimesterCheck+'&cgpaDetails='+cd+'&allTrimester='+allTrimester+'&degreeId='+document.getElementById('degreeId').value+'&batchId='+document.getElementById('batchId').value;
    */    
       
       //window.location=path;
       window.open(path,"StudentGradeCardReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printReport() {                    
    
   var form = document.allDetailsForm;   
   var semesterCheck = getCommaSeprated("semesterId");
   
   var selected=0;
   studentCheck='';
    
   formx = document.allDetailsForm;
   for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox"  && (formx.elements[i].name=="chb[]")){
            if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]")){
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
     alert("Please select atleast one student record");    
     return false;
   }
    
   var authorized = escape(trim(document.getElementById('authName').value));
   var designation = escape(trim(document.getElementById('authDesignation').value));
   
   query='';
   if("<?php echo GRADE_CARD_DESIGN_FORMAT; ?>"=='2') {
      var showHeader=0;
      if(document.allDetailsForm.showHeader.checked==true) {
        showHeader = 1;
      }   
      query = query+'&gradeDate='+document.getElementById('gradePrintDate').value+'&showHeader='+showHeader;    
      query = query+'&placeCity='+trim(document.getElementById('placeCity').value);
   }
   else {  
       var bc=0;
       if(document.allDetailsForm.branchChk.checked==true) {
         bc = 1;
       }  
       
       var hd=0;
       if(document.allDetailsForm.headerChk.checked==true) {
         hd = 1;
       }  
       
       var gpaChk=0;
       if(document.allDetailsForm.gpaChk.checked==true) {
         gpaChk = 1;
       }  
       
       var cgpaChk=0;
       if(document.allDetailsForm.cgpaChk.checked==true) {
         cgpaChk = 1;
       }  
       
       stuChk=0;
       if(document.allDetailsForm.studentDetailChk.checked==true) {
         stuChk = 1;
       }  
       
       titleChk=0;
       if(document.allDetailsForm.titleChk.checked==true) {
         titleChk = 1;
       }  
       
       authAlign='left';
       if(document.allDetailsForm.authAlign[1].checked==true) {
         authAlign='right';
       }
       query = '&authAlign='+authAlign+'&headerChk='+hd+'&branchChk='+bc;
       query = query+'&gpaChk='+gpaChk+'&cgpaChk='+cgpaChk+"&stuChk="+stuChk+"&titleChk="+titleChk;
   }
  
   query = query+'&studentId='+studentCheck+'&allSemester='+allSemester+'&semester='+semesterCheck+'&authorized='+authorized;
   query = query+'&designation='+designation;
   path='<?php echo UI_HTTP_PATH;?>/studentGradeCardPrint.php?branchId='+document.getElementById('branchId').value+'&degreeId='+document.getElementById('degreeId').value+'&batchId='+document.getElementById('batchId').value+query;
   
   //window.location=path;
   window.open(path,"StudentGradeCardReport","status=1,menubar=1,scrollbars=1, width=900");
}


</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    if(GRADE_CARD_DESIGN_FORMAT=='2') {
      require_once(TEMPLATES_PATH . "/GradeCardReport/studentGradeCardContents2.php");
    }
    else if(GRADE_CARD_DESIGN_FORMAT=='3') {
      require_once(TEMPLATES_PATH . "/GradeCardReport/studentGradeCardContents3.php");
    }
    else {
      require_once(TEMPLATES_PATH . "/GradeCardReport/studentGradeCardContents.php");  
    }
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
