<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CopyAttendance');
define('ACCESS','view');
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
 UtilityManager::ifTeacherNotLoggedIn();
}
else{
    UtilityManager::ifNotLoggedIn();
}
$threshHold=$sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT');
$roleId=$sessionHandler->getSessionVariable('RoleId');  
if($threshHold==''){
    $threshHold=0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Copy Attendance</title>
<script language="javascript">
 var roleId="<?php echo $roleId; ?>";
</script>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');?>

<script language="javascript">

var topPos = 0;
var leftPos = 0;


//to stop special formatting
specialFormatting=0;

//array for scroller
var pausecontent=new Array();

//---------------------------------------------------------------------------------
//Init function for scroll
//
//Author : Dipanjan Bhattacharjee
// Created on : (25.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function scroll_init(msg){
  return;  
 var gMsg="";  
 if(msg.length >0){
    gMsg=msg;
  }  

   pausecontent=gMsg.split("+~+");
   //calls actual function to show the scroller
   //new pausescroller(pausecontent, "pscroller2", "someclass", 4000);
   arrNews=pausecontent;
   initTicker(pausecontent.length, 50, 4000, "");
}


// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="5%"','',false),
new Array('studentName','Name','width="18%"','',true) , 
new Array('rollNo','Roll No.','width="10%"','',true), 
new Array('universityRollNo','Univ. Roll No.','width="12%"','',true),
new Array('memberOfClass','Class Member','width="12%"','align="center"',false),
new Array('attendanceCode','Attendance Code','width="14%"','',false),
new Array('delivered','Last Delivered','width="12%"','align="right"',false),
new Array('attended','Last Attended','width="12%"','align="right"',false),
new Array('percentage','Percentage','width="15%"','align="right"',false)
//,new Array('attendance','','width="0%"','',false)      //for having (dailyAttendanceId~studentId) format
);

//recordsPerPage = <?php echo RECORDS_PER_PAGE_TEACHER;?>;
recordsPerPage =1000;

linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitDailyAttendanceListView.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
/*
addFormName    = 'AddBlock';   
editFormName   = 'EditBlock';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBlock';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'universityRollNo';
sortOrderBy    = 'ASC';

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



//this will be used in giveBulkAttendance function.If user changes values of dropdowns before
//hitting submit button then wrong values will go to database.To prevent that we will use these
//variables for temporary storage.
var sclass=0;
var ssub=0;
var sgroup=0;
var sfdate='';
var speriod=0;

//stores date field's value;
var cdate="";
var cclass="";
var csubject=""; 
var cgroup="";
var cdate="<?php echo date('Y-m-d'); ?>";
var serverDate="<?php echo date('Y-m-d'); ?>";   
function getData(){
     
      if(trim("<?php echo $threshHold; ?>") !="0"){
          var threshold=<?php echo $threshHold; ?>;
          var roleId=<?php echo $roleId; ?>;
          
          if(threshold==-1 && roleId==2)
          {
              messageBox("You can not take attendance because attendance has been freezed by the Admin.");
              document.getElementById('startDate').focus();
              return false; 
          }
          else if(threshold>0 && roleId==2)
          {
                var diff=dateDifferenceCalculation(document.getElementById('forDate').value,cdate,'-');
                if(diff > threshold){
                  messageBox("You can not take attendance older than "+threshold+" days");
                  document.getElementById('forDate').focus();
                  return false;
                  }
          }
      }
    
      if((document.getElementById('class').value != "") && (document.getElementById('subject').value != "") && (document.getElementById('group').value != "") && (document.getElementById('period').value != "")){
         
         if(!dateDifference(document.getElementById('forDate').value,serverDate,"-")){
           messageBox("<?php echo CHECK_ATTENDANCE_DATE; ?>");   
           document.getElementById('forDate').focus();  
         } 
         else{
              setGlobalEditFlag(0);
              getAttendanceData();  //checking overlap between daily and bulk attendace.If no overlap then call sendReq
          }
       }
      else{
           messageBox("<?php echo DAILY_SELECT_STUDENT_LIST; ?>");   
           document.getElementById('class').focus();
      } 

}

//--------------------------------------------------------------------------------------
//Purpose : To validate form input
//Author :Dipanjan Bhattacharjee
//Date : 156.07.2008
//--------------------------------------------------------------------------------------
function validateForm(frm){
    if(whetherToCopyAttendance==0){
        messageBox("<?php echo NO_DATA_SUBMIT ?>");
        return false;
    }
    
    if(document.getElementById('targetPeriodId').value==''){
        messageBox("Select target period");
        document.getElementById('targetPeriodId').focus();
        return false;
    }
    
       var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxCopyDailyAttendance.php';
       var employeeId='';
       if(roleId!=2){
           employeeId=document.getElementById('employeeId').value;
           if(employeeId==''){
               messageBox("Select teacher");
               document.getElementById('employeeId').focus();
               return false; 
           }
       } 

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
             forDate:(document.getElementById('forDate').value),
             classId:(document.getElementById('class').value),
             groupId:(document.getElementById('group').value),
             subjectId:(document.getElementById('subject').value),
             periodId:(document.getElementById('period').value),
             targetPeriodId:(document.getElementById('targetPeriodId').value),
             employeeId: employeeId
             },
            onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var ret=trim(transport.responseText); 
                     if("<?php echo SUCCESS;?>" == trim(ret)) { 
                         messageBox("<?php echo DAILY_ATTENDANCE_COPIED?>");
                         document.getElementById('results').innerHTML="";
                         document.getElementById('divButton').style.display = 'none'; 
                         document.getElementById('divButton1').style.display = 'none';
                         document.getElementById('attendanceSummeryTdId').innerHTML="";
                         return false;
                     } 
                     else {
                        messageBox(trim(ret));
                     }
              },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
    
}

//--------------------------------------------------------------------------------------
//Purpose:Check For Daily Attendance Overlap with Bulk Attendance
//Author:Dipanjan Bhattachaarjee
//Date : 16.07.2008
//--------------------------------------------------------------------------------------
var attendanceCodeInfo='';
var whetherToCopyAttendance=0;
function getAttendanceData() {
		 
      sendReq(listURL,divResultName,searchFormName,'',false);
      //attendance code information is fetched so that attendance summary can be shown to
      //user before saving
      if(j.totalRecords>0){
       whetherToCopyAttendance=1;   
      }
      else{
       whetherToCopyAttendance=0;
      }
      attendanceCodeInfo=j.attendanceCodeInfo;
      //generate attendance summery
      generateAttendanceSummery();
      
      document.getElementById('results').style.display='block'; 
      document.getElementById('divButton').style.display = 'block'; 
      document.getElementById('divButton1').style.display = '';

 }
 

//--------------------------------------------------------------------------------------
//Purpose:Gets period name for the specified date(day of week actually)
//Author:Dipanjan Bhattachaarjee
//Date : 4.08.2008
//--------------------------------------------------------------------------------------
var cpdate="<?php echo date('Y-m-d'); ?>";
function getPeriodNames() {
    
         document.getElementById('attendanceSummeryTdId').innerHTML='';
         var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAdjustedPeriodNames.php';
         if(cdate==document.getElementById('forDate').value && cclass==document.getElementById('class').value && csubject==document.getElementById('subject').value && cgroup==document.getElementById('group').value){
             return false;
         }
        if(!dateDifference(document.getElementById('forDate').value,cpdate,"-")){
           messageBox("<?php echo CHECK_ATTENDANCE_DATE; ?>");   
           document.getElementById('forDate').focus();  
           return false;
         }
                         
       document.searchForm.period.options.length=0;
       var objOption = new Option("Select Period","");
       document.searchForm.period.options.add(objOption); 
       
       document.getElementById('results').innerHTML="";
       document.getElementById('divButton').style.display = 'none'; 
       document.getElementById('divButton1').style.display = 'none';
       
       if(document.getElementById('class').value=="" || document.getElementById('subject').value=="" || document.getElementById('group').value==""){
           cclass=(document.getElementById('class').value==""?"":document.getElementById('class').value);
           csubject=(document.getElementById('subject').value==""?"":document.getElementById('subject').value);
           cgroup=(document.getElementById('group').value==""?"":document.getElementById('group').value);
           return false;
       }
       cdate=document.getElementById('forDate').value;
       cclass=document.getElementById('class').value;
       csubject=document.getElementById('subject').value; 
       cgroup=document.getElementById('group').value; 
       
       var timeTableLabelId='';
       var employeeId='';
       if(roleId!=2){
        timeTableLabelId=document.getElementById('timeTableLabelId').value;
        employeeId=document.getElementById('employeeId').value;
        if(timeTableLabelId=='' || employeeId==''){
            return false;
        }
       }
       
       new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                forDate:(document.getElementById('forDate').value),
                classId:(document.getElementById('class').value),
                subjectId:(document.getElementById('subject').value),
                groupId:(document.getElementById('group').value),
                startDate : document.getElementById('forDate').value,
                endDate   : document.getElementById('forDate').value,
                timeTableLabelId : timeTableLabelId,
                employeeId : employeeId,
                moduleName : "<?php echo MODULE; ?>"
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();                       
                    j = eval('('+trim(transport.responseText)+')'); 
                    var l=j.length;
                    if(l >0){
                      for(var i=0; i < l ; i++){  
                       var objOption = new Option(j[i].periodNumber+' ( '+j[i].periodTime+' )',j[i].periodId);
                       document.searchForm.period.options.add(objOption); 
                      } 
                    }  
                  
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>"); }
           });
 }
 
function getTargetPeriods(val){
   
   document.getElementById('results').innerHTML="";
   document.getElementById('divButton').style.display = 'none'; 
   document.getElementById('divButton1').style.display = 'none'; 
   document.getElementById('attendanceSummeryTdId').innerHTML="";
   
   var target=document.getElementById('targetPeriodId');
   target.options.length=1;
   if(val==''){
       return false;
   }
      var timeTableLabelId='';
       var employeeId='';
       if(roleId!=2){
        timeTableLabelId=document.getElementById('timeTableLabelId').value;
        employeeId=document.getElementById('employeeId').value;
        if(timeTableLabelId=='' || employeeId==''){
            return false;
        }
       }
       
       var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTargetPeriodNames.php';
       new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                forDate:(document.getElementById('forDate').value),
                classId:(document.getElementById('class').value),
                subjectId:(document.getElementById('subject').value),
                groupId:(document.getElementById('group').value),
                sourcePeriodId : val,
                startDate : document.getElementById('forDate').value,
                endDate   : document.getElementById('forDate').value,
                timeTableLabelId : timeTableLabelId,
                employeeId : employeeId,
                moduleName : "<?php echo MODULE; ?>"
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                hideWaitDialog(true);
                var ret=trim(transport.responseText);
                var j = eval('('+ret+')'); 
                var l=j.length;
                if(l >0){
                  for(var i=0; i < l ; i++){  
                   var objOption = new Option(j[i].periodNumber+' ( '+j[i].periodTime+' )',j[i].periodId);
                   target.options.add(objOption); 
                  } 
                }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>"); }
           }); 
} 
 
//--------------------------------------------------------------------------------------
//Purpose:Made all checkboxes having the same value of defaut check boxes
//Author:Dipanjan Bhattachaarjee
//Date : 4.08.2008
//-------------------------------------------------------------------------------------- 
function makeDefaultAttCode(){
 setGlobalEditFlag(1);    
 if(document.getElementById('defaultAttCode').value!=""){
 try{   
  var att=document.listFrm.attendanceCode.length;
  var dfValue=document.getElementById('defaultAttCode').value;
  for(var i=0; i < att ; i++){
    if(!document.listFrm.attendanceCode[ i ].disabled){  
     document.listFrm.attendanceCode[ i ].value=dfValue; 
    } 
  }
  generateAttendanceSummery();
 }
 catch(e){
 }   
 }
}

function makeAttendanceHelp(){
 var rollValue=trim(document.getElementById('rollNoTxt').value);
 if(rollValue==''){
     messageBox("Enter roll numbers");
     document.getElementById('rollNoTxt').focus();
     return false;
 }
 var dfValue=document.getElementById('shortAttCode').value;
 if(dfValue==''){
     messageBox("Select attendance code");
     document.getElementById('shortAttCode').focus();
     return false;
 }
 if(dfValue!=""){
  hiddenFloatingDiv('AttendanceShortCutDiv');  
 try{   
   var ele=document.getElementById('results').getElementsByTagName('SELECT')
   var cnt1=ele.length;
   rollNoValues=rollValue.split(',');
   var cnt2=rollNoValues.length;
   for(var j=0;j<cnt2;j++){
       for (var i=0; i<cnt1; i++) {
            if (ele[i].type.toUpperCase()=='SELECT-ONE' && ele[i].name=='attendanceCode' && !ele[i].disabled && ele[i].getAttribute('alt').toUpperCase()==trim(rollNoValues[j]).toUpperCase()){
                ele[i].value=dfValue;
            }
       }
   }
  }
  catch(e){
  }   
 }
}



//--------------------------------------------------------------------------------------
//Purpose:Made a dropdown disable and null when member of class is unchecked
//Author:Dipanjan Bhattachaarjee
//Date : 4.08.2008
//-------------------------------------------------------------------------------------- 
function mocAction(id){
 setGlobalEditFlag(1);
 //set tabindex of submit(lower) button 
 if(document.listFrm.mem.length-2 >1){  
  document.getElementById('imageField2').tabIndex=document.getElementById('mem'+(document.listFrm.attendanceCode.length-1)).tabIndex+1;   
 }
 else{
     document.getElementById('imageField2').tabIndex=document.getElementById('mem0').tabIndex+1;   
 } 
 
  //if memofclass is true
 if(document.getElementById('mem'+id).checked){
     if(document.getElementById('defaultAttCode').value!=""){ // if default value is set
        document.getElementById('attendanceCode'+id).value=document.getElementById('defaultAttCode').value
      }
    document.getElementById('attendanceCode'+id).options.length=document.getElementById('attendanceCode'+id).options.length-1; //remove last inserted blank values  
    document.getElementById('attendanceCode'+id).disabled=false;
 }
 else{ //if memofclass if false
    var objOption = new Option("","NULL");
    document.getElementById('attendanceCode'+id).options.add(objOption); // add a blank value
    document.getElementById('attendanceCode'+id).selectedIndex=document.getElementById('attendanceCode'+id).options.length-1;
    document.getElementById('attendanceCode'+id).disabled=true;
 }
 generateAttendanceSummery();
}


//--------------------------------------------------------------------------------------
//Purpose:set tabindex of submit(lower) button
//Author:Dipanjan Bhattachaarjee
//Date : 508.2008
//-------------------------------------------------------------------------------------- 
function attAction(){
 //set tabindex of submit(lower) button  
 document.getElementById('imageField2').tabIndex=document.getElementById('mem'+(document.listFrm.attendanceCode.length-1)).tabIndex+1;   
}

//--------------------------------------------------------------------------------------
//Purpose:to move tabs vertically insted of horizontally
//Author:Dipanjan Bhattachaarjee
//Date : 5.08.2008
//-------------------------------------------------------------------------------------- 
function dynamicTab(e,id,src){
  var attCount=document.listFrm.attendanceCode.length;
  var memCount=document.listFrm.mem.length;   
  if(memCount-2 > 1){
    if(src==1){  //if attCode 
    alert(e.keyCode);
      if(e.keyCode==0){ //tab code
      
       if(id < attCount){
          if(!document.getElementById('attendanceCode'+(id+1)).disabled){
            document.getElementById('attendanceCode'+(id+1)).focus();   
          } 
       }
      else{
          document.getElementById('imageField').focus();
      } 
     }
   }
  /* else{ //if memc
      if(e.keyCode==9){ //tab code
       if(id < memCount-2){
        document.getElementById('memc'+(id+1)).focus();   
       }
      else{
          document.getElementById('imageField').focus();
      } 
     }
   } */  
 } 
}



//--------------------------------------------------------------------------------------
//Purpose:to reset form fileds
//Author:Dipanjan Bhattachaarjee
//Date : 5.08.2008
//--------------------------------------------------------------------------------------  
function resetForm(){
 //document.getElementById('class').selectedIndex=0;
 //document.getElementById('subject').selectedIndex=0;
 //document.getElementById('group').selectedIndex=0;
 //document.getElementById('period').selectedIndex=0;
 //document.getElementById('defaultAttCode').selectedIndex=0;
 document.getElementById('results').style.display = 'none';
 document.getElementById('divButton').style.display = 'none'; 
 document.getElementById('divButton1').style.display = 'none';
}


function stopEnterKey(){
	// footerFocusFlag is defined in dynamicFooter.php
  if((!isMozilla) && (!footerFocusFlag)){  
    var ev = window.event;
    var thisKeyCode = ev.keyCode;
    if (thisKeyCode == '13') {
     return false;
    }
  }
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

var preSelectedDate='';
var selectedDate='';
function init(){
    
    document.onkeydown=stopEnterKey;
 
    //stores the date field's value in cdate variable
    cdate=document.getElementById('forDate').value;
    cclass=document.getElementById('class').value;
    csubject=document.getElementById('subject').value; 
    cgroup=document.getElementById('group').value; 
    
    document.getElementById('calImg').tabindex=1;
    document.getElementById('class').tabindex=2;
    document.getElementById('subject').tabindex=3;
    document.getElementById('group').tabindex=4;
    document.getElementById('period').tabindex=5;
    //document.getElementById('imageField').tabindex=6;
    document.getElementById('calImg').focus();
     
  if("<?php echo $roleId;?>"=="2"){ 
   document.getElementById('class').focus();
  }
  else{
    document.getElementById('timeTableLabelId').focus();
    autoPopulateEmployee(document.getElementById('timeTableLabelId').value);
  }
   //document.getElementById('calImg').onblur=getPeriodNames
   document.getElementById('calImg').onblur=refreshDropDowns
   
   if(preSelectedDate!=''){
     selectedDate=preSelectedDate;
   }
   else{
     selectedDate="<?php echo date('Y-m-d');?>";
   }   
  //*****Code to display class,subject & group by default selected******// 
}


function refreshDropDowns(){
    if(selectedDate!=document.getElementById('forDate').value){
       selectedDate=document.getElementById('forDate').value;
       getClassData();
       document.getElementById('subject').options.length=1;
       document.getElementById('group').options.length=1;
       document.getElementById('targetPeriodId').options.length=1;
       document.getElementById('period').options.length=1;
       document.getElementById('results').style.display = 'none';
       document.getElementById('divButton').style.display = 'none'; 
       document.getElementById('divButton1').style.display = 'none';
    }
}



//-------------------------------------------------------------------------------------- 
//Purpose:To set the scroller according to the subject selected
//Author:Dipanjan Bhattacharjee
//Date:03.09.2008
//-------------------------------------------------------------------------------------- 
function setScroller(val){
   return; 
   if(val!=0){
      if(arrNews.length > 0){  
       document.getElementById('ticker').innerHTML=document.getElementById('hi_'+val).value;
       Stop(); //calls function to stop
      } 
    }
}

//this function fetches class data based upon user selected dates
function getClassData(){
  var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAdjustedClass.php';
  var classEle=document.getElementById('class');
  classEle.options.length=1;
  document.getElementById('targetPeriodId').options.length=1;
  document.getElementById('attendanceSummeryTdId').innerHTML='';
  var timeTableLabelId='';
  var employeeId='';
  if(roleId!=2){
    timeTableLabelId=document.getElementById('timeTableLabelId').value;
    employeeId=document.getElementById('employeeId').value;
  }

 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 startDate : document.getElementById('forDate').value,
                 endDate   : document.getElementById('forDate').value,
                 timeTableLabelId : timeTableLabelId,
                 employeeId : employeeId,
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


function autoPopulateEmployee(timeTableLabelId){
    clearData(1);
    document.getElementById('targetPeriodId').options.length=1;
    
    var url ='<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetTeachers.php';
    
    if(timeTableLabelId==''){
      return false;
    }
    
     new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId: timeTableLabelId
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 

                     for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].employeeName,j[c].employeeId);
                         document.searchForm.employeeId.options.add(objOption);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

function clearData(mode){
     
     document.getElementById('results').innerHTML='';
     //document.getElementById('lectureDelivered').value="";
     document.getElementById('results').style.display='none';
     document.getElementById('divButton').style.display='none';
     document.getElementById('divButton1').style.display='none';
     
    if(mode==1){
        document.getElementById('employeeId').options.length=1;
        document.getElementById('class').options.length=1;
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
    }
    else if(mode==2){
        document.getElementById('class').options.length=1;
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
    }
    else if(mode==3){
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
    }
   else if(mode==4){
       document.getElementById('group').options.length=1;
   }
   
 //  blankValues(1); 
}
//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh 
// Created on : (12.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function groupPopulate(value) {
   var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAdjustedGroup.php';
   document.searchForm.group.options.length=0;
   var objOption = new Option("Select Group","");
   document.searchForm.group.options.add(objOption);
   document.getElementById('attendanceSummeryTdId').innerHTML='';
   document.getElementById('targetPeriodId').options.length=1;
   
   if(document.getElementById('subject').value==""){
       return false;
   }
   
   if(document.getElementById('class').value==""){
       return false;
   }
   var timeTableLabelId='';
   var employeeId='';
   if(roleId!=2){
    timeTableLabelId=document.getElementById('timeTableLabelId').value;
    employeeId=document.getElementById('employeeId').value;
    if(timeTableLabelId=='' || employeeId==''){
        return false;
    }
   }

 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 subjectId: document.getElementById('subject').value,
                 classId  : document.getElementById('class').value,
                 startDate : document.getElementById('forDate').value,
                 endDate   : document.getElementById('forDate').value,
                 timeTableLabelId : timeTableLabelId,
                 employeeId : employeeId,
                 moduleName : "<?php echo MODULE; ?>" 
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
				    hideWaitDialog();
                    var j = eval('('+transport.responseText+')'); 
					var r=1;
                    var tname='';

                    for(var c=0;c<j.length;c++){
						 var objOption = new Option(j[c].groupName,j[c].groupId);
                         document.searchForm.group.options.add(objOption);
					}
                     if(j.length==1){
                         document.searchForm.group.selectedIndex=1;
                         getPeriodNames(document.searchForm.group.value);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

function populateSubjects(classId){
    document.getElementById('subject').options.length=1;
    document.getElementById('group').options.length=1;
    document.getElementById('targetPeriodId').options.length=1;
    document.getElementById('attendanceSummeryTdId').innerHTML='';
    
    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAdjustedSubject.php';
    var timeTableLabelId='';
    var employeeId='';
    if(roleId!=2){
     timeTableLabelId=document.getElementById('timeTableLabelId').value;
     employeeId=document.getElementById('employeeId').value;
     if(timeTableLabelId=='' || employeeId==''){
         return false;
     }
    }
    if(classId==''){
      return false;
    }
    
     new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 classId             : classId,
                 startDate           : document.getElementById('forDate').value,
                 endDate             : document.getElementById('forDate').value,
                 attendanceType      : 2,
                 timeTableLabelId    : timeTableLabelId,
                 employeeId          : employeeId,
                 moduleName : "<?php echo MODULE; ?>" 
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    var j = eval('('+transport.responseText+')'); 
                    
                    for(var c=0;c<j.length;c++){
                      if(j[c].hasAttendance==1) {
                        var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                        document.searchForm.subject.options.add(objOption);
                      }
                    }
                    if(j.length==1){
                      document.searchForm.subject.selectedIndex=1;
                    }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

//this function is used to generate attendance summary
function generateAttendanceSummery(){
    var c=attendanceCodeInfo.length;
    for(var i=0;i<c;i++){
      attendanceCodeInfo[i]['attendanceCodeDescription']=0;
    }
    if((document.listFrm.mem.length-2) <= 1){     
       for(var m=0;m<c;m++){
          /*if(attendanceCodeInfo[m]['attendanceCodeId']==attCode){
            attendanceCodeInfo[m]['attendanceCodeDescription']++;
          }*/
       }
    }
   else{    
        var att=document.listFrm.attendanceCode.length;
        for(i=0; i < att ; i++){
           //generating summary of attendance options
           for(var m=0;m<c;m++){
            if(attendanceCodeInfo[m]['attendanceCodeId']==document.listFrm.attendanceCode[ i ].value){
               attendanceCodeInfo[m]['attendanceCodeDescription']++;
             }
            }
         }
   }
       
   var attSuggString='';
   for(var m=0;m<c;m++){
        if(attSuggString!=''){
             attSuggString +=' ,';
         }
         attSuggString +=' '+attendanceCodeInfo[m]['attendanceCodeName']+' : '+attendanceCodeInfo[m]['attendanceCodeDescription'];
   }
  if(attSuggString!=''){ 
   document.getElementById('attendanceSummeryTdId').innerHTML='<span><b>Total :  '+(document.listFrm.mem.length-2)+'&nbsp;&nbsp;'+attSuggString+'</b></span>';
  }
  else{
      document.getElementById('attendanceSummeryTdId').innerHTML='';
  }
}


function changeAttendanceCode(id){
 var ele=document.getElementById('attendanceCode'+id);
 var len=ele.length;
 if(len && !ele.disabled){
  if(ele.selectedIndex==-1){
   ele.selectedIndex=0;
  }
  else if(ele.selectedIndex==len-1){
   ele.selectedIndex=0;
  }
  else{
   ele.selectedIndex=ele.selectedIndex+1;
  }
   generateAttendanceSummery();
   setGlobalEditFlag(1)
   //return false;
 }
}


//this variable is used to detemine if anything has been modified or
//not after list is populated
var globalEditFlag=0;
function setGlobalEditFlag(value){
    globalEditFlag=value;
}

function getGlobalEditFlag(){
    return globalEditFlag;
}
//this function will check for unsaved data and alert user about it
function checkUnsavedData(e){
    if (getGlobalEditFlag()) {
          var evt = ( (!document.all) ? e : window.event);
          evt.returnValue = "<?php echo UNSAVED_DATA_ALERT; ?>";
          return false;
     }
}

window.onbeforeunload=checkUnsavedData;


function hiddenFloatingDiv(divId) 
{
    hideDropDowns(1);
    //document.getElementById(divId).innerHTML = originalDivHTML;
    document.getElementById(divId).style.visibility='hidden';
    //document.getElementById('dimmer').style.visibility = 'hidden';
    document.getElementById('modalPage').style.display = "none";
    makeMenuDisable('qm0',false);
    
    DivID = "";
}

function hideDropDowns(mode){
    //show/hide in search filter
    var frmObj1=document.forms['searchForm'].elements;
    var objLength=frmObj1.length;
    for(var i=0;i<objLength;i++){
        if(frmObj1[i].type=='select-multiple' || frmObj1[i].type=='select-one'){
          if(mode==0){ 
            frmObj1[i].style.display='none';
          }
          else{
            frmObj1[i].style.display='';
          }
        }
    }
    
    
    //show/hide in result divs
    var frmObj1=document.forms['listFrm'].elements;
    var objLength=frmObj1.length;
    for(var i=0;i<objLength;i++){
        if(frmObj1[i].type=='select-multiple' || frmObj1[i].type=='select-one'){
          if(mode==0){ 
            frmObj1[i].style.display='none';
          }
          else{
            frmObj1[i].style.display='';
          }
        }
    }
}

function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');   
      return false;
    }
    //document.getElementById('divHelpInfo').innerHTML=title;      
    document.getElementById('helpInfo').innerHTML= msg;   
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);
    
    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}


</script> 

</head>
<body>
<?php
//Purpose:Returns the lecture delivered box's value
//Author:Dipanjan Bhattacharjee
//Date:16.07.2008
function lectureDelivered($val){
 return $val;   
}

?>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listCopyAttendanceContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");    
?>
</body>
</html>