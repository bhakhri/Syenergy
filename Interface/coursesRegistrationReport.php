<?php 
// This file generate a list Student Test Wise Marks Report
//
// Author :Parveen Sharma
// Created on : 04-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
//include_once(BL_PATH ."/Student/initStudentInformation.php"); 
define('MODULE','COMMON');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();  
$queryString =  $_SERVER['QUERY_STRING'];       
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Courses Registration Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

 //This function Validates Form 
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
sortField = 'universityRollNo';
sortOrderBy  = 'ASC';
allSubjectId = '';
queryString1 = '';
var viewType=1;    
allTerm='';

btnBack='';

function validateForm(frm) {

     var fieldsArray = new Array(new Array("classId","<?php echo SELECT_CLASS;?>"), 
                                 new Array("termClassId","<?php echo "Select Term";?>") 
                                );
     var len = fieldsArray.length; 
      
     frm = document.allDetailsForm;   

     for(i=0;i<len;i++) {
         if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
         }
     }
     
     document.getElementById('resultsDiv').innerHTML='';
     document.getElementById("pagingDiv").innerHTML = '';
     document.getElementById("pagingDiv1").innerHTML = '';
     
     page=1;
     showReport(page);    
     return false;
}

function showReport(page) {
  
     queryString1='';
    
     var url='<?php echo HTTP_LIB_PATH;?>/StudentReports/initCourseRegistrationReport.php';
     form = document.allDetailsForm;   
     
     classId=form.classId.value;
     termClassId=form.termClassId.value;
     
     sortOrderBy1='ASC';
     if(document.allDetailsForm.sortOrderBy1[1].checked==true) {
          sortOrderBy1='DESC';
     }
     sortOrderBy = sortOrderBy1;
     sortField =  document.getElementById('sortField1').value;
     
     subjectId='';
     if(document.getElementById('subjectId').value!='') {
       subjectId = getCommaSeprated("subjectId");
     }
     
     termClassId1=termClassId; 
     if(termClassId=='all') {
       termClassId=allTerm;
     }
     
     if(form.incAll.checked) {
       incAll = 1;  
     }
     else {
       incAll = 0;  
     }
       
     //queryString = generateQueryString('allDetailsForm');   
     queryString1 = "classId="+classId+"&termClassId="+termClassId+"&subjectId="+subjectId+"&incAll="+incAll;  
     queryString1 = queryString1+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField+"&termClassId1="+termClassId1;
     
     new Ajax.Request(url,
     {
         method:'post',
         parameters:{classId: classId,
                     termClassId : termClassId,
                     subjectId: subjectId,
                     termClassId1: termClassId1,
                     incAll: incAll,
                     sortOrderBy: sortOrderBy,
                     sortField : sortField,
                     page: page
                    },
         asynchronous:true,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
             hideWaitDialog(true);
             if(trim(transport.responseText)==false) {
                messageBox("<?php echo INCORRECT_FORMAT?>");  
             }
             else {
                var ret=trim(transport.responseText).split('!~~!');
                var j0 = ret[0];
                var j1 = ret[1];
                
                if(j1=='') {
                  totalRecords = 0;
                }
                else {
                  totalRecords = j1; 
                }
                document.getElementById("nameRow").style.display='';
                document.getElementById("nameRow2").style.display='';
                document.getElementById("resultRow").style.display='';
                document.getElementById("pageRow").style.display='';    
                document.getElementById('resultsDiv').innerHTML=j0;
                //document.getElementById('resultsDiv').innerHTML=trim(transport.responseText);
                
                pagingData='';
                document.getElementById("pagingDiv").innerHTML = pagingData;
                document.getElementById("pagingDiv1").innerHTML = pagingData;
                
                totalPages = totalRecords / parseInt("<?php echo RECORDS_PER_PAGE; ?>");
                completePages = parseInt(totalRecords / parseInt("<?php echo RECORDS_PER_PAGE; ?>"));
                if (totalPages > completePages) {
                    completePages++;
                }
                if (totalRecords > 0) {
                    pagingData = pagination2(page, totalRecords, parseInt("<?php echo RECORDS_PER_PAGE; ?>"), parseInt("<?php echo LINKS_PER_PAGE; ?>"));
                    document.getElementById("pagingDiv").innerHTML = pagingData;
                    document.getElementById("pagingDiv1").innerHTML = "<b>Total Records&nbsp;:&nbsp;</b>"+totalRecords; 
                }
             }
          },
          onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
        });

}

function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/courseRegistrationReportPrint.php?'+queryString1;
    window.open(path,"StudentCourseRegistrationReportPrint","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

function printReportCSV() {
    
    path='<?php echo UI_HTTP_PATH;?>/courseRegistrationReportPrintCSV.php?'+queryString1;
    window.location=path;
}

function printStudentReport(rId,cId,sId) {
  
   path='<?php echo UI_HTTP_PATH;?>/courseRegistrationReport.php?rid='+rId+'&cid='+cId+'&sid='+sId;
   window.open(path,"StudentCourseRegistrationReportPrint","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50"); 
}


function printForm() {
  
   path='<?php echo UI_HTTP_PATH;?>/courseRegistrationReport.php';
   window.open(path,"StudentCourseRegistrationReportPrint","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50"); 
}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
    document.getElementById('resultsDiv').innerHTML='';
    document.getElementById("pagingDiv").innerHTML = '';
    document.getElementById("pagingDiv1").innerHTML = '';
    document.getElementById("pageRow").style.display='none';
}


function deleteStudent(id) {
    if(false===confirm("Do you want to delete this record?")) {
         return false;
    }
    else {   
     url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxInitRegistrationDelete.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {id: id},
         onSuccess: function(transport){
           if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
              showWaitDialog(true);
           }
            else {
                 hideWaitDialog(true);
              // messageBox(trim(transport.responseText));
                 if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                   showReport(page);  
                   return false;
                 }
                 else {
                   messageBox(trim(transport.responseText));
                 }
           }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
     } 
}


function getTerm() {
   
   allTerm='';
   
   document.allDetailsForm.termClassId.length = null;
   addOption(document.allDetailsForm.termClassId, '', 'Select'); 
    
   if(document.getElementById("classId").value=='') {
      return false; 
   } 
   classId = document.getElementById("classId").value;
  
   var url ='<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxGetTerms.php';
        
   new Ajax.Request(url,
    {
             method:'post',
             asynchronous:false,
             parameters: {
                 classId: classId
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                hideWaitDialog();
                j = eval('('+transport.responseText+')'); 
                if(j.length>0) {
                  var objOption = new Option('All','all');
                  document.allDetailsForm.termClassId.options.add(objOption);  
                }
                for(var c=0;c<j.length;c++) {
                   periodValue = "Term-"+romanNumerals(j[c].periodValue);
                   var objOption = new Option(periodValue,j[c].classId);
                   document.allDetailsForm.termClassId.options.add(objOption);
                   if(allTerm=='') {
                    allTerm=j[c].classId;
                   }
                   else {
                     allTerm=allTerm+","+j[c].classId;  
                   }
                }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

window.onload=function(){
    
    if("<?php echo $queryString?>"!='') {
      form = document.allDetailsForm;   
      page = "<?php echo $REQUEST_DATA['page'] ?>";
      form.classId.value="<?php echo $REQUEST_DATA['currentClassId']; ?>";
      
      document.allDetailsForm.sortField1.value="<?php echo $REQUEST_DATA['sortField']; ?>";
      
      if("<?php echo $REQUEST_DATA['sortOrderBy']; ?>"=='DESC') {
        document.allDetailsForm.sortOrderBy1[1].checked=true;
      }
      else {
        document.allDetailsForm.sortOrderBy1[0].checked=true; 
      }
      getTerm();
      document.allDetailsForm.termClassId.selectedIndex=1;
      showReport(page);  
    }
}

    
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listCoursesRegistrationContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

//$History: coursesRegistrationReport.php $

?>
