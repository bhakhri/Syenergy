<?php
//used for showing class wise grades
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ClassWiseAttendanceList');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
//require_once(BL_PATH . "/Teacher/StudentActivity/ajaxInitData.php");  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Attendance </title>
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

var tableHeadArray = new Array(
new Array('srNo','Sr.No.','width="1%"','',false) ,
new Array('studentName','Name','width="15%"','align="left"',true) ,
new Array('rollNo','Roll No.','width="15%"','align="left" style="padding-left:5px;"',true) ,
new Array('universityRollNo','Univ. Roll No.','width="12%"','align="left" style="padding-left:5px;"',true) ,
new Array('subjectCode','Subject','width="10%"','align="left"',true) ,
//new Array('fromDate','Date(F)','width="10%"','align="center"',false),
//new Array('toDate','Date(T)','width="10%"','align="center"',false),
new Array('delivered','Delivered','width="10%"','align="right"',true),
new Array('attended','Attended','width="10%"','align="right"',true),
new Array('percentage','Percentage','width="10%"','align="right"',true),
new Array('shortAttendance','Att. Short ?','width="7%"','align="center"',true)
);

//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
recordsPerPage = 1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxClassAttendanceList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
/*
addFormName    = 'AddCity';   
editFormName   = 'EditCity';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteCity';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'firstName';
sortOrderBy = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
var cdate="<?php echo date('Y-m-d'); ?>";
function getData(){
    //calculate current date
    //var d=new Date();
    //var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));
    
    if(!dateDifference(document.getElementById('fromDate').value,document.getElementById('toDate').value,"-")){
     messageBox("<?php echo DATE_VALIDATION; ?>");   
     document.getElementById('fromDate').focus();
     return false;
    }
    if(!dateDifference(document.getElementById('toDate').value,cdate,"-")){
       messageBox("<?php echo DATE_VALIDATION2; ?>");   
       document.getElementById('toDate').focus();  
       return false;
    }  
   /* 
    if(isEmpty(document.getElementById('studentRollNo').value)){
      if((document.getElementById('class').value != "") && (document.getElementById('subject').value != "") && (document.getElementById('group').value != "")){
          sendReq(listURL,divResultName,searchFormName,'');    
       }
      else{
           messageBox("<?php echo DISPLAY_ATTENDANCE_SELECT_STUDENT_LIST;?>");
           document.getElementById('class').focus();
      } 
    }
   else{
       sendReq(listURL,divResultName,searchFormName,'');    
       //document.getElementById('studentRollNo').value='';   
   } 
  */ 
    if((document.getElementById('class').value != "") && (document.getElementById('subject').value != "") && (document.getElementById('group').value != "")){
          sendReq(listURL,divResultName,searchFormName,'');   
		  document.getElementById('saveDiv1').style.display='';
       }
      else{
           messageBox("<?php echo DISPLAY_ATTENDANCE_SELECT_STUDENT_LIST;?>");
           document.getElementById('class').focus();
      } 
}

//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO clear rollno when clas,subject or group is changed
//
//Author : Dipanjan Bhattacharjee
// Created on : (07.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function deleteRollNo(){
   vanishData(); 
  // document.searchForm.studentRollNo.value=""; 
}

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh 
// Created on : (12.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function groupPopulate(value) {
   //var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGroupPopulate.php';
   var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAdjustedGroup.php';
   document.searchForm.group.options.length=2;
   /*
   var objOption = new Option("Select Group","");
   document.searchForm.group.options.add(objOption); 
   */
   if(document.getElementById('subject').value==""){
       return false;
   }

 new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 subjectId  : document.getElementById('subject').value,
                 classId    : document.getElementById('class').value,
                 startDate  : document.getElementById('fromDate').value,
                 endDate    : document.getElementById('toDate').value,
                 moduleName : "<?php echo MODULE; ?>" 
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
				    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 

					 var r=1;
                     var tname='';

                     for(var c=0;c<j.length;c++){
						 
						 var objOption = new Option(j[c].groupName,j[c].groupId);
                         document.searchForm.group.options.add(objOption);
					 }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

//to populate subjects based on choosen class
function populateSubjects(classId){
    document.getElementById('subject').options.length=1;
    document.getElementById('group').options.length=2;
    
    //var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetSubjects.php';
    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAdjustedSubject.php';
    
    if(classId==''){
      return false;
    }
    
     new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 classId    : classId,
                 startDate  : document.getElementById('fromDate').value,
                 endDate    : document.getElementById('toDate').value,
                 moduleName : "<?php echo MODULE; ?>" 
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 

                    for(var c=0;c<j.length;c++){
                       if(j[c].hasAttendance==1) {         
                         var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                         document.searchForm.subject.options.add(objOption);
                       }
                    }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

//this function fetches class data based upon user selected dates
function getClassData(){
  var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAdjustedClass.php';
  var classEle=document.getElementById('class');
  classEle.options.length=1;

 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 startDate  : document.getElementById('fromDate').value,
                 endDate    : document.getElementById('toDate').value,
                 moduleName : "<?php echo MODULE; ?>" 
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    var j = eval('('+transport.responseText+')'); 
                    for(var c=0;c<j.length;c++){
                       var objOption = new Option(j[c].className,j[c].classId);
                       classEle.options.add(objOption);
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

var selectedDate1="<?php echo date('Y-m-d')?>";
var selectedDate2="<?php echo date('Y-m-d')?>";
function refreshDropDowns(){
    if(selectedDate1!=document.getElementById('fromDate').value || selectedDate2!=document.getElementById('toDate').value){
       selectedDate1=trim(document.getElementById('fromDate').value);
       selectedDate2=trim(document.getElementById('toDate').value);
       if(selectedDate1=='' || selectedDate2==''){
           return false;
       }
       getClassData();
       document.getElementById('subject').options.length=1;
       document.getElementById('group').options.length=2;
       document.getElementById('results').innerHTML = '';
       document.getElementById('saveDiv1').style.display = 'none'; 
    }
}

window.onload=function(){
    document.getElementById('class').focus();
    document.getElementById('calImg1').onblur=refreshDropDowns
    document.getElementById('calImg2').onblur=refreshDropDowns
    var roll = document.getElementById("studentRollNo");
    autoSuggest(roll);
    getClassData();
}

function sendKeys(eleName, e) {
    var ev = e||window.event;
    var thisKeyCode = ev.keyCode;
    if (thisKeyCode == '13') {
     var form = document.searchForm;
     eval('form.'+eleName+'.focus()');
     return false;
   }
}

function printReport() {
    if(!dateDifference(document.getElementById('fromDate').value,document.getElementById('toDate').value,"-")){
     messageBox("<?php echo DATE_VALIDATION; ?>");   
     document.getElementById('fromDate').focus();
     return false;
    }
    if(!dateDifference(document.getElementById('toDate').value,cdate,"-")){
       messageBox("<?php echo DATE_VALIDATION2; ?>");   
       document.getElementById('toDate').focus();  
       return false;
    }  
   /* 
    if(isEmpty(document.getElementById('studentRollNo').value)){
      if((document.getElementById('class').value != "") && (document.getElementById('subject').value != "") && (document.getElementById('group').value != "")){
          sendReq(listURL,divResultName,searchFormName,'');    
       }
      else{
           messageBox("<?php echo DISPLAY_ATTENDANCE_SELECT_STUDENT_LIST;?>");
           document.getElementById('class').focus();
      } 
    }
   else{
       sendReq(listURL,divResultName,searchFormName,'');    
       //document.getElementById('studentRollNo').value='';   
   } 
  */ 
    if((document.getElementById('class').value != "") && (document.getElementById('subject').value != "") && (document.getElementById('group').value != "")){
          form = document.searchForm;
          classId=document.getElementById('class').value;
          var reportType=document.searchForm.reportType[0].checked==true?1:0;
          var path='<?php echo UI_HTTP_PATH;?>/Teacher/listClassWiseAttendancePrint.php?subject='+form.subject.value+'&group='+form.group.value+'&class='+classId+'&studentRollNo='+document.getElementById('studentRollNo').value+'&studentName='+document.getElementById('studentName').value+'&fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&reportType='+reportType;
          window.open(path,"subjectToClassReport","status=1,menubar=1,scrollbars=1, width=700, height=400, top=150,left=150");
       }
    else{
           messageBox("<?php echo DISPLAY_ATTENDANCE_SELECT_STUDENT_LIST;?>");
           document.getElementById('class').focus();
    }
}

/* function to print all subject to class report*/
function printCourseToClassCSV() {

        if(!dateDifference(document.getElementById('fromDate').value,document.getElementById('toDate').value,"-")){
     messageBox("<?php echo DATE_VALIDATION; ?>");   
     document.getElementById('fromDate').focus();
     return false;
    }
    if(!dateDifference(document.getElementById('toDate').value,cdate,"-")){
       messageBox("<?php echo DATE_VALIDATION2; ?>");   
       document.getElementById('toDate').focus();  
       return false;
    }  
   /* 
    if(isEmpty(document.getElementById('studentRollNo').value)){
      if((document.getElementById('class').value != "") && (document.getElementById('subject').value != "") && (document.getElementById('group').value != "")){
          sendReq(listURL,divResultName,searchFormName,'');    
       }
      else{
           messageBox("<?php echo DISPLAY_ATTENDANCE_SELECT_STUDENT_LIST;?>");
           document.getElementById('class').focus();
      } 
    }
   else{
       sendReq(listURL,divResultName,searchFormName,'');    
       //document.getElementById('studentRollNo').value='';   
   } 
  */ 
    if((document.getElementById('class').value != "") && (document.getElementById('subject').value != "") && (document.getElementById('group').value != "")){
          form = document.searchForm;
          classId=document.getElementById('class').value;
          var reportType=document.searchForm.reportType[0].checked==true?1:0;
          var path='<?php echo UI_HTTP_PATH;?>/Teacher/listClassWiseAttendanceCSV.php?subject='+form.subject.value+'&group='+form.group.value+'&class='+classId+'&studentRollNo='+document.getElementById('studentRollNo').value+'&studentName='+document.getElementById('studentName').value+'&fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&reportType='+reportType;
          window.location=path;
    }
    else{
           messageBox("<?php echo DISPLAY_ATTENDANCE_SELECT_STUDENT_LIST;?>");
           document.getElementById('class').focus();
    }
}

function vanishData(){
    document.getElementById('results').innerHTML='';
    document.getElementById('saveDiv1').style.display='none';
}
</script>

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listClasswiseAttendanceContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");    
?>
</body>
</html>
