<?php 
//-------------------------------------------------------
//  This File contains Student Internal Marks Foxpro Report
//
// Author :Parveen Sharma
// Created on : 28-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InternalMarksFoxproReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Student Internal Marks Foxpro Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 
//This function Validates Form 
//var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initStudentList.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window
recordsPerPage = 10000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'internalMarksFoxproFrm'; // name of the form which will be used for search
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
page=1; //default page
sortField = 'universityRollNo';
sortOrderBy    = 'ASC';


/*
var tableHeadArray = new Array(
                     new Array('srNo','#','width="2%"','',false), 
                     new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%"','align=\"left\"',false), 
                     new Array('universityRollNo','Univ. Roll No','width="13%"','',true),
                     new Array('firstName','Student Name','width="16%"','',true),
                     new Array('corrCityId','City','width="15%"','',true),
                     new Array('studentEmail','Email','width="12%"','',true),
                  // new Array('classId','Degree','width="14%"','',true),
                  // new Array('studyPeriod','Period','width="8%"','',true),
                     new Array('studentMobileNo','Mobile','width="10%"','',false));   
*/
                     
/*
function refreshFoxProMarksData() {
      
      url='<?php echo HTTP_LIB_PATH;?>/StudentReports/initStudentList.php';  
      
      tableColumns = new Array(new Array('srNo','#', 'width="2%"  align="left"',false), 
                               new Array('checkAll', '<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%" align=\"left\"',false), 
                               new Array('universityRollNo','Univ. Roll No.','width="12%" align="left"',true), 
                               new Array('studentName','Student Name','width="15%" align="left"',true),
                               new Array('fatherName',"Father's Name",'width="15%" align="left"',true));
                                   
      listSubject='';
      cnt = document.internalMarksFoxproFrm.subjectId.length;
      
      if(cnt>0) {
         for(var i=0;i<cnt;i++) {
            tableColumns.push(new Array('sub'+document.internalMarksFoxproFrm.subjectId.options[i].value,document.internalMarksFoxproFrm.subjectId.options[i].text,'width="5%" align="right"',false));
            if(listSubject=='') {
              listSubject = document.internalMarksFoxproFrm.subjectId.options[i].value;
            }
            else {
              listSubject = listSubject + ','+document.internalMarksFoxproFrm.subjectId.options[i].value;
            }
         }          
      }         
      classId = (document.internalMarksFoxproFrm.degree.value);
      //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
      listObj4 = new initPage(url,recordsPerPage,linksPerPage,1,'','universityRollNo','ASC','resultsDiv','','',true,'listObj4',tableColumns,'','','&classId='+classId);
      sendRequest(url, listObj4, '',true);
         
      document.getElementById("nameRow").style.display='';
      document.getElementById("nameRow2").style.display='';
      document.getElementById("resultRow").style.display='';
}
*/


function refreshFoxProMarksData() {
    
     url='<?php echo HTTP_LIB_PATH;?>/StudentReports/initStudentList.php';      
     hideResults();
     page=1;
     queryString = '';
     form = document.internalMarksFoxproFrm; 
     
     timeTable = form.timeTable.value;
     classId = form.degree.value;
     
     queryString = 'timeTable='+timeTable+'&classId='+classId+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&page='+page;
          
     new Ajax.Request(url,
     {
          method:'post',
          asynchronous:false,
          parameters: queryString, 
          onCreate: function() {
              showWaitDialog(true);
          },
          onSuccess: function(transport){
             hideWaitDialog(true);
             if(trim(transport.responseText)==false) {
                messageBox("<?php echo INCORRECT_FORMAT?>");  
             }
             else {
               document.getElementById('resultsDiv').innerHTML=trim(transport.responseText);
               document.getElementById("nameRow").style.display='';
               document.getElementById("nameRow2").style.display='';
               document.getElementById("resultRow").style.display='';
             }
     },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
     });
} 

function validateAddForm(frm) {
    
    document.getElementById('resultsDiv').innerHTML=''; 
    
    if(document.getElementById("timeTable").value == '') {
       messageBox("<?php echo SELECT_TIME_TABLE; ?>");  
       return false;
    }
    
    if(document.getElementById("degree").value == '') {
       messageBox("<?php echo SELECT_DEGREE; ?>");  
       return false;
    }
    
    hideResults();
    refreshFoxProMarksData();
    return false;
}

function doAll(){
    formx = document.internalMarksFoxproFrm;
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

function resetStudyPeriod() {
	//document.internalMarksFoxproFrm.studyPeriodId.selectedIndex = 0;
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function getGenerateFoxpro() {
    
    var formx = document.internalMarksFoxproFrm;
 
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
    
    var classId = document.internalMarksFoxproFrm.degree.value;   
    var degree=formx.degree.value;
    var degreeName = document.getElementById('degree'); 
    
    var degreeName=degreeName.options[degreeName.selectedIndex].text;
    var studentId=studentCheck;
    
    var collegeCode = document.getElementById('collegeCode').value;
    var streamCode = document.getElementById('streamCode').value;
    var branchCode = document.getElementById('branchCode').value;
    
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initStudentFoxproMarksReport.php';
    new Ajax.Request(url,
    {
      method:'post',
      
      parameters: {
         degree: trim(degree),
         degreeName: trim(degreeName),
         classId: trim(classId),
         studentId: trim(studentId), 
         collegeCode: trim(collegeCode),
         streamCode: trim(streamCode),
         branchCode: trim(branchCode),
         sortField : sortField,
         sortOrderBy:sortOrderBy
      },
      onCreate: function() {
         showWaitDialog(true);
      },
      onSuccess: function(transport){
         hideWaitDialog(true);
         if(trim(transport.responseText)==false) {
            messageBox("<?php echo INCORRECT_FORMAT?>");  
         }
         else if("<?php echo FOXPRO_LIST_EMPTY;?>" == trim(transport.responseText)) {
            messageBox("<?php echo FOXPRO_LIST_EMPTY?>");  
         }
         else if("<?php echo NOT_WRITEABLE_FOLDER; ?>" == trim(transport.responseText)) {
            messageBox("<?php echo NOT_WRITEABLE_FOLDER?>");  
         } else {
            //return false;
            url =  trim(transport.responseText);
				x = Math.random() * Math.random();
            window.location.href = "<?php echo HTTP_LIB_PATH;?>/download.php?t=fpr&u="+url+"&x="+x;  
			//window.location = "<?php echo HTTP_PATH.'/Templates/Xml/';?>"+trim(transport.responseText);
         }
      },
      onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}

function getLabelClass(){
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetLabelFoxproClass.php';
    
    form = document.internalMarksFoxproFrm;
    var timeTable = form.timeTable.value;
    var pars = 'timeTable='+timeTable;
    
    document.internalMarksFoxproFrm.degree.length = null; 
    document.internalMarksFoxproFrm.subjectId.length = null;
    addOption(document.internalMarksFoxproFrm.degree, '', 'Select');
    
     new Ajax.Request(url,
     {
         method:'post',
         parameters: pars,
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
               hideWaitDialog(true);
                var j = eval('(' + transport.responseText + ')');
                len = j.length;

                document.internalMarksFoxproFrm.degree.length = null;                  
                if(len>0) { 
                  for(i=0;i<len;i++) { 
                    addOption(document.internalMarksFoxproFrm.degree, j[i].classId, j[i].className);
                  }
                }
                else {
                  addOption(document.internalMarksFoxproFrm.degree, '', 'Select'); 
                }
                // now select the value                                     
                document.internalMarksFoxproFrm.degree.value = j[0].classId;
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
       getClassSubjects();
}

function getClassSubjects() {
    
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassGetFoxproSubjects.php';
    form = document.internalMarksFoxproFrm;
    
    form.subjectId.length = null;
    
    var degree = form.degree.value;
 
    var pars = '&degree='+degree;
    
    if(degree == '') {
      return false;
    }
    new Ajax.Request(url,
    {
         method:'post',
         parameters: pars,
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
               hideWaitDialog(true);
                var j = eval('(' + transport.responseText + ')');
                len = j.length;
                document.internalMarksFoxproFrm.subjectId.length = null;
                for(i=0;i<len;i++) { 
                    addOption(document.internalMarksFoxproFrm.subjectId, j[i].subjectId, j[i].subjectCode);
                }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

window.onload=function(){
   //loads the data
   document.internalMarksFoxproFrm.timeTable.focus();
   getLabelClass();
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/internalMarksFoxproContent.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
//$History: internalMarksFoxproReport.php $
//
//*****************  Version 15  *****************
//User: Parveen      Date: 3/02/10    Time: 5:12p
//Updated in $/LeapCC/Interface
//validation & condition format updated 
//
//*****************  Version 14  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 13  *****************
//User: Parveen      Date: 2/05/10    Time: 1:03p
//Updated in $/LeapCC/Interface
//Time Table Label base format updated
//
//*****************  Version 12  *****************
//User: Parveen      Date: 12/23/09   Time: 12:09p
//Updated in $/LeapCC/Interface
//is_write function added
//
//*****************  Version 11  *****************
//User: Parveen      Date: 12/17/09   Time: 1:18p
//Updated in $/LeapCC/Interface
//sorting format updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 11/04/09   Time: 3:36p
//Updated in $/LeapCC/Interface
//college, stream, branch code columns added
//
//*****************  Version 9  *****************
//User: Parveen      Date: 10/29/09   Time: 4:04p
//Updated in $/LeapCC/Interface
//studyCode, centerCode, branchCode added
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 5/11/09    Time: 11:32a
//Updated in $/LeapCC/Interface
//Changed window.open to window.location to generate foxpro report
//
//*****************  Version 7  *****************
//User: Parveen      Date: 5/02/09    Time: 2:20p
//Updated in $/LeapCC/Interface
//INCORRECT_FORMAT condition update
//
//*****************  Version 6  *****************
//User: Parveen      Date: 5/01/09    Time: 1:14p
//Updated in $/LeapCC/Interface
//alignment settings
//
//*****************  Version 5  *****************
//User: Parveen      Date: 4/30/09    Time: 7:22p
//Updated in $/LeapCC/Interface
//code update
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/30/09    Time: 4:43p
//Updated in $/LeapCC/Interface
//download file code update
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/30/09    Time: 4:21p
//Updated in $/LeapCC/Interface
//file download function added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/30/09    Time: 3:49p
//Updated in $/LeapCC/Interface
//message settings
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/30/09    Time: 2:18p
//Created in $/LeapCC/Interface
//file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/29/09    Time: 4:13p
//Updated in $/LeapCC/Interface
//print code update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/29/09    Time: 11:28a
//Created in $/LeapCC/Interface
//file added
//

?>
