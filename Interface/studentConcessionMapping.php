<?php
//-------------------------------------------------------
// Purpose: To design the Student Fee Concession Mapping
//
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentFeeConcessionMapping');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Fee Concession Mapping</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 //This function Validates Form 
//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
recordsPerPage = 1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/StudentFeeConcessionMapping/ajaxInitList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'studentName';
sortOrderBy = 'ASC';
queryString ='';
   

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%"','align=\"left\"',false),  
                               new Array('studentName','Student Name','width="15%"','',true), 
                               new Array('rollNo','Roll No.','width="12%"','',true), 
                               new Array('universityRollNo','Univ. No.','width="12%"','',true),
                               new Array('regNo','Reg. No.','width="12%"','',true),
                               new Array('imgSrc','Photo','width="5%" align="center"','align="center"',false), 
                               new Array('concessionCategory','Fee Concession Category','width="37%"','align="left"',false));

function doAll(){

   var formx = document.listForm;
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

function getSelectConcession() {
   
    var form = document.listForm; 

    // Select Concession Array
    var dtArray=new Array();            
    dtArray.splice(0,dtArray.length); //empty the array  
    

    // Select Student Array
    var stArray=new Array();
    stArray.splice(0,stArray.length); //empty the array  

    // Check Fee Concession Category Name     
    var totalConcessionId = form.elements['sConcessionId[]'].length;
    countConcession=0;
    for(var i=0;i<totalConcessionId;i++) {
      if(form.elements['sConcessionId[]'][i].selected == true) {
         countConcession++;
         dtArray.push(form.elements['sConcessionId[]'][i].value);  
      }
    } 
    
    if(countConcession==0) {
      messageBox("Please Select Fee Concession Category Name");  
      document.getElementById('sConcessionId').focus();
      return false;  
    }
    
    if(typeof form.elements['chb[]'] === "undefined") {
      return false;
    }
    
   
    // Check Student List
    /*
    var totalStudentId = form.elements['chb[]'].length;
    for(var i=0;i<totalStudentId;i++) {
      if(form.elements['chb[]'][i].checked == true) {
         countStudent++;
         stArray.push(form.elements['chb[]'][i].value);    
      }
    }
    */
    
    var totalStudentId=0;
    countStudent=0;
    for(var i=0;i<form.length;i++){
       if(form.elements[i].type=="checkbox" && form.elements[i].name=="chb[]" && form.elements[i].checked == true) {
         countStudent++;
         //stArray.push(form.elements['chb[]'][i].value);  
         stArray.push(form.elements[i].value);  
         totalStudentId++;
       }
    }
    
    if(countStudent==0) {
      messageBox("Please select atleast one record!");  
      return false;  
    }
    
    var stu = stArray.length;
    var con = dtArray.length;
    var id ='';
    
    
    for(var i=0;i<stu;i++) {
       id = stArray[i];
       var str = "concessionCategory"+id+"[]";
       var totalConcessionId = eval("form.elements['"+str+"'].length");  
       for(var j=0;j<totalConcessionId;j++) {
          id = eval("form.elements['"+str+"'][j].value");   
          for(var k=0;k<con;k++) {
            if(dtArray[k]==id) {
              eval("form.elements['"+str+"'][j].selected = true");  
              break;
            }  
          }
       }
    }

}                   
                               
function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameConcessionRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function showList() {
    hideResults();
    queryString = ''; 
    if(document.getElementById('feeClassId').value=='') {
      messageBox("Select Fee Class");  
      document.getElementById('feeClassId').focus();
      return false;  
    } 
    queryString = generateQueryString('listForm'); 
    document.getElementById('nameConcessionRow').style.display=''; 
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,true);
    
    return false;
}

function getClassConcession() {
  
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentFeeConcessionMapping/ajaxGetClassConcession.php';      
    
    document.listForm.sConcessionId.length = null;                  
    
    if(document.listForm.feeClassId.value=='') {
      return false;  
    }
    
    new Ajax.Request(url,
    {
         method:'post',
         parameters: {feeClassId: document.listForm.feeClassId.value },
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + transport.responseText + ')');
            
            document.listForm.sConcessionId.length = null;                  
            for(i=0;i<len;i++) { 
              addOption(document.listForm.sConcessionId, j[i].categoryId, j[i].categoryName);
            }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });  
}

function saveCategory() {
   
    url = '<?php echo HTTP_LIB_PATH;?>/StudentFeeConcessionMapping/ajaxInitAdd.php';
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
           flag = true;
           messageBox(trim(transport.responseText));
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

function printReport() {

    path='<?php echo UI_HTTP_PATH;?>/studentConcessionMappingReportPrint.php?'+queryString;
    a = window.open(path,"StudentConcessionMappingReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printReportCSV() {

    path='<?php echo UI_HTTP_PATH;?>/studentConcessionMappingReportCSV.php?'+queryString;
    window.location=path; 
    //a = window.open(path,"StudentPercentageWiseAttendanceReport","status=1,menubar=1,scrollbars=1, width=900");
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentFeeConcessionMapping/listStudentContents.php"); 
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>