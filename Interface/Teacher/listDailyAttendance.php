<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DailyAttendance');
define('ACCESS','view');
//require_once(BL_PATH . "/Teacher/TeacherActivity/initDailyAttendanceList.php");
//require_once(BL_PATH . "/Teacher/TeacherActivity/initDailyAttendanceLastTaken.php");
global $sessionHandler;  
$threshHold=$sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT');
$roleId=$sessionHandler->getSessionVariable('RoleId');  
if($threshHold==''){
    $threshHold=0;
}
if($roleId==2){
   UtilityManager::ifTeacherNotLoggedIn();
}
else{
   UtilityManager::ifNotLoggedIn();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Daily Attendance</title>
<style type="text/css">
a.whiteClass:hover{
    color:#FFFFFF;
}
</style>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');?>

<script language="javascript">
var roleId="<?php echo $roleId; ?>";  
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
new Array('studentName','Name','width="21%"','',true) ,
new Array('rollNo','Roll No.','width="10%"','',true),
new Array('universityRollNo','Univ. Roll No.','width="10%"','',true),
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
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitDailyAttendanceList.php';
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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

document.getElementById('warningMsg').innerHTML="";
document.getElementById('warningRow').style.display="none";

  //hide attendance shortcut div
  hiddenFloatingDiv('AttendanceShortCutDiv');
  document.getElementById('rollNoTxt').value='';
  document.getElementById('shortAttCode').value='';
  
  threshold=<?php echo $threshHold; ?>;
  roleId=<?php echo $roleId; ?>;
  
  
      if(trim("<?php echo $threshHold; ?>") !="0"){
          
          var diff;
          
          if(threshold==-1 && roleId==2)
          {
msg = "You cannot mark attendance as it has been frozen by the administrator. Please contact your administrator if you still need to mark this attendance";
	document.getElementById('warningRow').style.display="";
    	document.getElementById('warningMsg').innerHTML=msg;
               
              messageBox(msg);
              document.getElementById('forDate').focus();
              return false; 
          }
          else if(threshold>0 && roleId==2)
          {
              diff=dateDifferenceCalculation(document.getElementById('forDate').value,cdate,'-');
                if(diff > threshold){
                  msg = "You cannot mark attendance older than "+threshold+" days";
  		  document.getElementById('warningRow').style.display="";
	      	  document.getElementById('warningMsg').innerHTML=msg;
                 messageBox(msg);
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
              checkBulkAttendance();  //checking overlap between daily and bulk attendace.If no overlap then call sendReq
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
    //number of percentage checkboxes
    var l=(document.listFrm.mem.length-2); //subtracting 2 for two dummy fields
    if(l == 0){
        messageBox("<?php echo NO_DATA_SUBMIT; ?>");
        return false;
    }

if(document.getElementById('topicsId').value==""){
        messageBox("<?php echo SELECT_TOPICS_TAUGHT; ?>");
        document.getElementById('topicsId').focus();
        return false;
    }

    /*
    if(trim(document.getElementById('commentTxt').value)==""){
	    messageBox("<?php echo ENTER_YOUR_COMMENTS; ?>");
	    document.getElementById('commentTxt').focus();
	    return false;
    }
    */

 var atCode=document.listFrm.mem.length-2; //2 should be subtracted and num of mem===num of others
 for(var k=0 ; k <atCode ; k++){
  if(document.listFrm.attendanceCode[ k ].value == ""){
    messageBox("<?php echo CHECK_ATTENDANCE_CODE; ?>");
    document.listFrm.attendanceCode[ k ].focus();
    return false;
  }
 }
    setGlobalEditFlag(0);
    giveDailyAttendance();
    return false;
}

//--------------------------------------------------------------------------------------
//Purpose:For Bulk Attendance
//Author:Dipanjan Bhattachaarjee
//Date : 16.07.2008
//--------------------------------------------------------------------------------------

function giveDailyAttendance() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxDailyAttendance.php';
         if(trim("<?php echo $threshHold; ?>") !="0"){
          threshold=<?php echo $threshHold; ?>;
          roleId=<?php echo $roleId; ?>;
          if(threshold==-1 && roleId==2)
          {
              messageBox("You cannot mark attendance as it has been frozen by the administrator. Please contact your administrator if you still need to mark this attendance");
              document.getElementById('forDate').focus();
              return false; 
          }
          else if(threshold>0 && roleId==2)
          {
                var diff=dateDifferenceCalculation(document.getElementById('forDate').value,cdate,'-');
                if(diff > threshold){
                  messageBox("You cannot mark attendance older than "+threshold+" days");
                  document.getElementById('forDate').focus();
                  return false;
                  }
          }
      }

        var c=attendanceCodeInfo.length;
        for(var i=0;i<c;i++){
          attendanceCodeInfo[i]['attendanceCodeDescription']=0;
        }

         document.getElementById('class').value=sclass ; document.getElementById('subject').value=ssub;
         document.getElementById('group').value=sgroup ; document.getElementById('period').value=speriod;
         document.getElementById('forDate').value=sfdate ;

         var i=0;
         var studentId="";
         var attendanceId="";
         var memc="";
         var attCode="";

         if((document.listFrm.mem.length-2) <= 1){
           var arr=document.listFrm.attendance.value.split("~");
           studentId=arr[1];
           attendanceId=arr[0];
           memc=(document.listFrm.mem[2].checked ? "1" : "0" );
           attCode=document.listFrm.attendanceCode.value;
           for(var m=0;m<c;m++){
              if(attendanceCodeInfo[m]['attendanceCodeId']==attCode){
                attendanceCodeInfo[m]['attendanceCodeDescription']++;
              }
           }
         }
        else{
         //detecting studentId and attendanceId(previous records)
         var studentatt=document.listFrm.attendance.length; //hidden field
         for(i=0; i <studentatt ; i++){
             var arr=document.listFrm.attendance[ i ].value.split("~");
             if(studentId==""){
                 studentId=arr[1]; //studentId
             }
            else{
                studentId=studentId+","+arr[1]; //studentId
            }
           if(attendanceId==""){
                 attendanceId=arr[0]; //attendanceId
             }
            else{
                attendanceId=attendanceId + "," + arr[0]; //attendanceId
            }
         }
         //detecting memberofclass list
         var member=document.listFrm.mem.length;
         for(i=2; i < member ; i++){    //started from 2  for two dummy fields
             if(memc==""){
                 memc=(document.listFrm.mem[ i ].checked ? "1" : "0" );
             }
            else{
                 memc=memc + "," + ( document.listFrm.mem[ i ].checked ? "1" : "0" );
            }
         }
        //detecting attendanceCode list
         var att=document.listFrm.attendanceCode.length;
         for(i=0; i < att ; i++){
             //generating summary of attendance options
             for(var m=0;m<c;m++){
              if(attendanceCodeInfo[m]['attendanceCodeId']==document.listFrm.attendanceCode[ i ].value){
                attendanceCodeInfo[m]['attendanceCodeDescription']++;
              }
             }

             if(attCode==""){
                 attCode=document.listFrm.attendanceCode[ i ].value;
             }
            else{
                 attCode=attCode + "," + document.listFrm.attendanceCode[ i ].value;
            }
         }
       }

       var attSuggString='';
       for(var m=0;m<c;m++){
           if(attSuggString!=''){
               attSuggString +=' ,';
           }
           attSuggString +=' '+attendanceCodeInfo[m]['attendanceCodeName']+' :'+attendanceCodeInfo[m]['attendanceCodeDescription'];
        }

       if(attSuggString!=''){
           var ret=confirm("Total "+attSuggString+"\nAre you sure ?");
           if(!ret){ //if user presses canncel ,then do not save the data
               return false;
           }
       }

	   //this is used to seperate an array with ~
	   form = document.searchForm;

		 totaltopics = form.elements['topicsId[]'].length;
			var name = document.getElementById('topicsId');
			selectedTopic='';
			countTopic=0;
			for(i=0;i<totaltopics;i++) {
				if (form.elements['topicsId[]'][i].selected == true) {
					if (selectedTopic != '') {
						selectedTopic += '~';
					}
					countTopic++;
					selectedTopic += form.elements['topicsId[]'][i].value;
				}
			}
			selectedTopic ='~'+selectedTopic+'~';

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
             parameters: {studentIds: (studentId),
             attendanceIds: (attendanceId),
             attendanceCodeIds: (attCode),
             memofclass: (memc),
             forDate:(document.getElementById('forDate').value),
             classId:(document.getElementById('class').value),
             groupId:(document.getElementById('group').value),
             subjectId:(document.getElementById('subject').value),
             periodId:(document.getElementById('period').value),
			 subjectTopicId : selectedTopic,
			 taught:(document.getElementById('taught').value),
             comments:trim(document.getElementById('commentTxt').value),
             employeeId: employeeId
             },
            onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                      var ret=trim(transport.responseText).split('~');
                      if("<?php echo SUCCESS;?>" == trim(ret[0])) {
                         flag = true;
                         if(ret.length>0){
                          //messageBox("<?php echo DAILY_ATTENDANCE_TAKEN;?>"+"\n"+trim(ret[1]));
                          messageBox("<?php echo DAILY_ATTENDANCE_TAKEN;?>");
                         }
                         else{
                             messageBox("<?php echo DAILY_ATTENDANCE_TAKEN;?>");
                         }
                         //sendReq(listURL,divResultName,searchFormName,'');
                         var d=new Date();
                         var cpdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));

                         //document.getElementById('forDate').value=cpdate;
                         resetForm();
                         cdate=document.getElementById('forDate').value;
                         cclass=document.getElementById('class').value;
                         csubject=document.getElementById('subject').value;
                         cgroup=document.getElementById('group').value;

                     }
                     else {
                        messageBox(trim(ret[0]));
                     }
                     document.getElementById('rollNoTxt').value='';
                     document.getElementById('shortAttCode').value='';
					 document.getElementById('attendanceSummeryTdId').innerHTML='';
					 document.getElementById('attendanceStatus').innerHTML='';
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
function checkBulkAttendance() {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitBulkAttendanceCheck.php';
         //resetForm();

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
             forDate:(document.getElementById('forDate').value),
             classId :(document.getElementById('class').value),
             subjectId:(document.getElementById('subject').value),
             groupId:(document.getElementById('group').value),
			 taught:(document.getElementById('taught').value)
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                     if(trim(transport.responseText) != 0){ //if overlaping occurs
                      messageBox("<?php echo DAILY_ATTENDANCE_RESTRICTION; ?> ( "+document.getElementById('forDate').value+" )");
                    }
                   else{
                      //if no overlap between daily and bulk attendace occurs
                      sclass=trim(document.getElementById('class').value) ; ssub=trim(document.getElementById('subject').value);
                      sgroup=trim(document.getElementById('group').value) ; speriod=trim(document.getElementById('period').value);
                      sfdate=document.getElementById('forDate').value;
                      sendReq(listURL,divResultName,searchFormName,'',false);

					  if(trim(j.newAttendance) == true){
						document.getElementById('attendanceStatus').innerHTML="<?php echo 'NEW ATTENDANCE' ?>";
					  }
					  else{
						  document.getElementById('attendanceStatus').innerHTML="<?php echo 'OLD ATTENDANCE' ?>";
					  }
                      //attendance code information is fetched so that attendance summary can be shown to
                      //user before saving
                      attendanceCodeInfo=j.attendanceCodeInfo;
                      //generate attendance summery
                      generateAttendanceSummery();

					  //used to select topic in the selecion box
					  if (j.topicSubjectId == null || j.topicSubjectId == "" ) {
						  document.getElementById('taught').value=j.topicTaughtId;
						  //document.getElementById('topicsId').value=j.topicsId;
						  document.getElementById('defaultAttCode').value="";
						  //document.getElementById('commentTxt').value=j.topicsComments;
						  document.getElementById('results').style.display='block';
						  document.getElementById('divButton').style.display = 'block';
                          document.getElementById('divButton1').style.display = '';
                          //fetch previous topics taught values
                          fetchPreviuosTopicsTaught();
						  return false;
					  }
					  else {
						  arr = j.topicSubjectId.split('~');
						  arrLen = arr.length;
						  topicLen =document.getElementById('topicsId').length;

						  if(topicLen) {
							  for(i=0;i<topicLen;i++) {
								for(m=0;m<arrLen;m++) {
								  if(document.getElementById('topicsId')[i].value == arr[m] ) {
										document.getElementById('topicsId').options[i].selected=true;
								  }
								}
							  }
						  }
					  }



                      document.getElementById('taught').value=j.topicTaughtId;
					  document.getElementById('commentTxt').value=j.topicsComments;
					  document.getElementById('defaultAttCode').value="";
                      document.getElementById('results').style.display='block';
                      document.getElementById('divButton').style.display = 'block';
                      document.getElementById('divButton1').style.display = '';

	
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>"); }
           });
 }

function fetchPreviuosTopicsTaught(){
         var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetPreviousTopicsTaught.php';
         var topic=document.getElementById('topicsId');
         var topicLen =topic.options.length;
         for(i=0;i<topicLen;i++){
             topic.options[i].selected=false;
         }
         new Ajax.Request(url,
           {
             method:'post',
             asynchoronous:false,
             parameters: {
                 classId    :  document.getElementById('class').value,
                 group      :  document.getElementById('group').value,
                 subject    :  document.getElementById('subject').value,
                 period     :  document.getElementById('period').value,
                 forDate    :  document.getElementById('forDate').value,
				 employeeId :  document.getElementById('employeeId').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   if(trim(transport.responseText)==0) {
                     return false;
                   }
                   var k = eval('('+trim(transport.responseText)+')');
                   var arr = k.subjectTopicId.split('~');
                   var arrLen = arr.length;

                   if(topicLen) {
                      for(i=0;i<topicLen;i++) {
                         for(m=0;m<arrLen;m++) {
                            if(topic[i].value == arr[m] ) {
                                 topic.options[i].selected=true;
                            }
                          }
                       }
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });

}


//--------------------------------------------------------------------------------------
//Purpose:Gets period name for the specified date(day of week actually)
//Author:Dipanjan Bhattachaarjee
//Date : 4.08.2008
//--------------------------------------------------------------------------------------
var cpdate="<?php echo date('Y-m-d'); ?>";
function getPeriodNames() {
        document.getElementById('warningMsg').innerHTML="";
        document.getElementById('warningRow').style.display="none";
        document.getElementById('commentTxt').value='';
         //var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetPeriodNames.php';
         document.getElementById('attendanceSummeryTdId').innerHTML='';
		 document.getElementById('attendanceStatus').innerHTML='';
         var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAdjustedPeriodNames.php';
         //alert(cclass);
         if(cdate==document.getElementById('forDate').value && cclass==document.getElementById('class').value && csubject==document.getElementById('subject').value && cgroup==document.getElementById('group').value){
             return false;
         }
        //var d=new Date();
        //var cpdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));
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
                       //var objOption = new Option(j[i].periodNumber,j[i].periodId);
                       //var objOption = new Option(j[i].periodNumber+' ( '+j[i].slotAbbr+' )',j[i].periodId);
                       var objOption = new Option(j[i].periodNumber+' ( '+j[i].periodTime+' )',j[i].periodId);
                       document.searchForm.period.options.add(objOption);
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
     document.getElementById('warningRow').style.display="none";
    document.getElementById('warningMsg').innerHTML="";

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
    //alert(e.keyCode);
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

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Dipanjan Bhattacharjee
// Created on : (15.01.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function topicPopulate(value)
{

   url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxAutoPopulateTopic.php';
   document.searchForm.topicsId.options.length=0;
  // var objOption = new Option("Select","");
  // document.searchForm.topicsId.options.add(objOption);

   if(document.getElementById('subject').value==""){
       return false;
   }
  var employeeId='';
  if(roleId!=2){
    employeeId=document.getElementById('employeeId').value;
    if(employeeId==''){
        return false;
    }
  }

 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 subjectId: document.getElementById('subject').value,
                 employeeId : employeeId
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
				    hideWaitDialog();
                    if(trim(transport.responseText)==0){
                      messageBox("<?php echo EMPTY_TOPICS_TAUGHT; ?>");
                    }
                    j = eval('('+transport.responseText+')');

                     var r=1;
                     var tname='';

                     for(var c=0;c<j.length;c++){

                        /* if(j[c].topicsTaughtId!=-1){
                           if(trim(j[c].topicAbbr) != trim(tname)){
                               tname=trim(j[c].topicAbbr);
                               r=1;
                           }
                           else{
                               r++;
                           }
                           var topic=j[c].topicAbbr + '-' + r;
                         }
                         else{
                             var topic=j[c].topicAbbr
                         }*/
                         //var topic=j[c].topicAbbr;
                         //var objOption = new Option(topic,j[c].subjectTopicId+j[c].topicsTaughtId);
						 var objOption = new Option(j[c].topic,j[c].subjectTopicId);
                         document.searchForm.topicsId.options.add(objOption);
					 }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
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
 document.getElementById('attendanceStatus').innerHTML='';
 document.getElementById('attendanceSummeryTdId').innerHTML='';
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

   //*****Code to display class,subject & group by default selected******//
   var qClassId=-1;qSubjectId=-1;qGroupId=-1;qPeriodId=-1;qFromDate=-1;qCnt=0;
   if(trim(window.location.search)!=''){
     var qstr=trim(window.location.search);
     if(qstr.lastIndexOf('classId')!=-1 && qstr.lastIndexOf('subjectId')!=-1 && qstr.lastIndexOf('groupId')!=-1 && qstr.lastIndexOf('periodId')!=-1 && qstr.lastIndexOf('fromDate')!=-1){

       qClassId    =  trim(querySt('classId'));
       qSubjectId  =  trim(querySt('subjectId'));
       qGroupId    =  trim(querySt('groupId'));
       qPeriodId   =  trim(querySt('periodId'));
       qFromDate   =  trim(querySt('fromDate'));
       if(qFromDate && qFromDate!=''){
        document.getElementById('forDate').value= qFromDate;
       }
       if(qClassId && qClassId!=''){
         getClassData();
         document.getElementById('class').value= qClassId;
         populateSubjects(qClassId);
         qCnt++;
       }
       if(qSubjectId && qSubjectId!=''){
         document.getElementById('subject').value= qSubjectId;
         topicPopulate(1);
         qCnt++;
       }
       if(qGroupId && qGroupId!=''){
          groupPopulate(1);
          document.getElementById('group').value= qGroupId;
          qCnt++;
       }

       if(qFromDate && qFromDate!=''){
          document.getElementById('forDate').value= qFromDate;
          preSelectedDate=qFromDate;
          if(!dateDifference(qFromDate,cpdate,"-")){
           messageBox("<?php echo CHECK_ATTENDANCE_DATE; ?>");
           document.getElementById('forDate').focus();
           return false;
          }
          document.getElementById('forDate').value= qFromDate;
          getPeriodNames();
          qCnt++;
       }
       if(qPeriodId && qPeriodId!=''){
          document.getElementById('period').value= qPeriodId;
          qCnt++;
       }
      if(qCnt==5){
          getData();//to fetch student data
      }
     }
   }
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
       document.getElementById('period').options.length=1;
       document.getElementById('defaultAttCode').selectedIndex=0;
       document.getElementById('results').style.display = 'none';
       document.getElementById('divButton').style.display = 'none';
       document.getElementById('divButton1').style.display = 'none';
       document.getElementById('commentTxt').value='';
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
  document.getElementById('commentTxt').value='';
  var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAdjustedClass.php';
  var classEle=document.getElementById('class');
  classEle.options.length=1;
  document.getElementById('attendanceSummeryTdId').innerHTML='';
  document.getElementById('attendanceStatus').innerHTML='';
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
    document.getElementById('commentTxt').value='';
    clearData(1);

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
	 document.getElementById('attendanceStatus').innerHTML='';
     document.getElementById('results').innerHTML='';
     //document.getElementById('lectureDelivered').value="";
     document.getElementById('commentTxt').value="";
     document.getElementById('results').style.display='none';
     document.getElementById('divButton').style.display='none';
     document.getElementById('divButton1').style.display='none';
     document.getElementById('topicsId').selectedIndex=-1;

    if(mode==1){
        document.getElementById('employeeId').options.length=1;
        document.getElementById('class').options.length=1;
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
        document.getElementById('topicsId').options.length=0;
    }
    else if(mode==2){
        document.getElementById('class').options.length=1;
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
        document.getElementById('topicsId').options.length=0;
    }
    else if(mode==3){
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
        document.getElementById('topicsId').options.length=0;
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function groupPopulate(value) {

   document.getElementById('warningMsg').innerHTML="";
   document.getElementById('warningRow').style.display="none";

    document.getElementById('commentTxt').value='';
   //url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGroupPopulate.php';
   var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAdjustedGroup.php';
   document.searchForm.group.options.length=0;
   var objOption = new Option("Select Group","");
   document.searchForm.group.options.add(objOption);
   document.getElementById('attendanceSummeryTdId').innerHTML='';
   document.getElementById('attendanceStatus').innerHTML='';

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
                    j = eval('('+transport.responseText+')');

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
    document.getElementById('warningMsg').innerHTML="";
    document.getElementById('warningRow').style.display="none";
    document.getElementById('commentTxt').value='';
    document.getElementById('subject').options.length=1;
    document.getElementById('group').options.length=1;
    document.searchForm.topicsId.options.length=0;
    document.getElementById('attendanceSummeryTdId').innerHTML='';
	document.getElementById('attendanceStatus').innerHTML='';
    //var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetSubjects.php';
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
                    j = eval('('+transport.responseText+')');

                    for(var c=0;c<j.length;c++){
                      if(j[c].hasAttendance==1) {
                        var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                        document.searchForm.subject.options.add(objOption);
                      }
                    }
                    if(j.length==1){
                      document.searchForm.subject.selectedIndex=1;
				  	  topicPopulate(document.searchForm.subject.value);
                    }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}


//to get the attendance history list
function getAttendanceHistory(){

  var timeTableLabelId='';
  var employeeId='';
  var employeeName='';
  if(roleId!=2){
     timeTableLabelId=document.getElementById('timeTableLabelId').value;
     employeeId=document.getElementById('employeeId').value;
     if(timeTableLabelId==''){
         messageBox("<?php echo SELECT_TIME_TABLE;?>");
         document.getElementById('timeTableLabelId').focus();
         hideWaitDialog(true);
         return false;
     }
     if(employeeId==''){
         messageBox("<?php echo SELECT_TEACHER;?>");
         document.getElementById('employeeId').focus();
         hideWaitDialog(true);
         return false;
     }
     employeeName=document.getElementById('employeeId').options[document.getElementById('employeeId').selectedIndex].text;
  }
  hideDropDowns(0); //hides drop downs
  document.getElementById('historyResults').innerHTML='';
  //var recordsPerPage2=<?php echo RECORDS_PER_PAGE;?>;
  var recordsPerPage2=100;
  var url = '<?php echo HTTP_LIB_PATH;?>//Teacher/TeacherActivity/ajaxAttendanceHistoryList.php';
  /*
  var tableHeadArray = new Array(
                                new Array('srNo','#','width="1%"','',false),
                                new Array('groupShort','Group','width="6%" align="left"',true),
                                new Array('subjectCode','Subject','width="8%" align="left"',true),
                                new Array('className','Class','width="12%" align="left"',true),
                                new Array('periodNumber','Period','width="5%" align="left"',true),
                                new Array('fromDate','From','width="5%" align="center"',true) ,
                                new Array('toDate','To','width="5%" align="center"',true),
                                new Array('attendanceType','Att. Type','width="6%" align="left"',true),
                                new Array('lectureDelivered','Lectures','width="4%" align="left"',true)
                             );
 */
 var tableHeadArray = new Array(
                                new Array('srNo','#','width="1%"','',false),
                                new Array('employeeName','Teacher','width="8%" align="left"',true),
                                new Array('subjectCode','Subject','width="10%" align="left"',true),
                                new Array('groupShort','Group','width="8%" align="left"',true),
                                new Array('className','Class','width="10%" align="left"',true),
                                new Array('periodNumber','Period','width="12%" align="left"',true),
                                new Array('fromDate','From','width="5%" align="center"',true) ,
                                new Array('toDate','To','width="5%" align="center"',true),
                                //new Array('attendanceType','Att. Type','width="6%" align="left"',true),
                                new Array('attendanceType','Type','width="7%" align="left"',true),
                                //new Array('lectureDelivered','Lec. taken','width="7%" align="right"',true),
                                new Array('lectureDelivered','Lec.','width="7%" align="right"',true),
                                new Array('topic','Topics','width="10%" align="left"',true),
                                new Array('actionString','Action','width="1%" align="center"',false)
                             );
 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage2,linksPerPage,1,'','employeeName','ASC','historyResults','','',true,'listObj',tableHeadArray,'','','&classId='+document.getElementById('class').value+'&subjectId='+document.getElementById('subject').value+'&groupId='+document.getElementById('group').value+'&timeTableLabelId='+timeTableLabelId+'&employeeId='+employeeId+'&attType=2');
 sendRequest(url, listObj, ' ',false);

 if(roleId==2){
  var empName="<?php echo $sessionHandler->getSessionVariable('EmployeeName');?>";
 }
 else{
  var empName=employeeName;
 }
 document.getElementById('divHeaderId3').innerHTML='Attendance history of '+empName;
 displayWindow('AttendanceHistoryDiv','850','320');
}



//to show all options for a teacher
function getAttendanceOptions() {

       document.getElementById('warningMsg').innerHTML="";
       document.getElementById('warningRow').style.display="none";

       var timeTableLabelId='';
       var employeeId='';
       if(roleId!=2){
        timeTableLabelId=document.getElementById('timeTableLabelId').value;
        employeeId=document.getElementById('employeeId').value;
        if(timeTableLabelId==""){
             messageBox("<?php echo SELECT_TIME_TABLE_LABEL;?>");
             document.getElementById('timeTableLabelId').focus();
             return false;
        }
        if(employeeId==""){
             messageBox("Select teacher");
             document.getElementById('employeeId').focus();
             return false;
        }
       }

        hideDropDowns(0); //hides drop downs
        var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAllOptions.php';
        document.getElementById('attendanceOptionsResults').innerHTML;

        if(!dateDifference(document.getElementById('forDate').value,cpdate,"-")){
           messageBox("<?php echo CHECK_ATTENDANCE_DATE; ?>");
           document.getElementById('forDate').focus();
           hideDropDowns(1); //show drop downs
           return false;
         }

       new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                forDate:(document.getElementById('forDate').value),
                type :2,
                startDate:(document.getElementById('forDate').value),
                endDate:(document.getElementById('forDate').value),
                timeTableLabelId : timeTableLabelId,
                employeeId : employeeId
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')');
                    var l=j.length;
                    var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
                    var str='';
                    //str='<tr class="rowheading"><td class="" style="border:1px solid black" colspan="4" align="left">As On '+customParseDate(document.getElementById('forDate').value,'-')+'</td></tr>';
                    document.getElementById('divHeaderId4').innerHTML='Attendance Schedule  As On '+customParseDate(document.getElementById('forDate').value,'-');
					str +='<tr><td colspan=4><b><font color="brown">Click on the Link below for which you need to take the attendance</th>';
                    str +='<tr class="rowheading"><td class="searchhead_text reportBorder" align="center">Period</td><td class="searchhead_text reportBorder" align="center">Group</td><td class="searchhead_text reportBorder" align="center">Subject</td><td class="searchhead_text reportBorder" align="center">Class</td></tr>';
                    if(l>0){
                        for(var i=0; i <l;i++){
                           bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';

						   str +='<tr '+bg+'>';
						   str +='<td class="reportBorder" align="center" width="10%" nowrap><U><font color="blue"><a title="Click to see attendance details" style="cursor:pointer" onclick="setAttendanceOptions('+j[i].classId+','+j[i].subjectId+','+j[i].groupId+','+j[i].periodId+');return false;">'+j[i].periodNumber+'</font></a></td>';
                           str +='<td class="reportBorder" align="center" width="10%" nowrap><U><font color="blue"><a title="Click to see attendance details" style="cursor:pointer" onclick="setAttendanceOptions('+j[i].classId+','+j[i].subjectId+','+j[i].groupId+','+j[i].periodId+');return false;">'+j[i].groupName+'</font></a></td>';
                           str +='<td class="reportBorder" align="center" width="10%" nowrap><U><font color="blue"><a title="Click to see attendance details" style="cursor:pointer" onclick="setAttendanceOptions('+j[i].classId+','+j[i].subjectId+','+j[i].groupId+','+j[i].periodId+');return false;">'+j[i].subjectCode+'</font></a></td>';
                           str +='<td class="reportBorder" align="center" width="10%" nowrap><U><font color="blue"><a title="Click to see attendance details" style="cursor:pointer" onclick="setAttendanceOptions('+j[i].classId+','+j[i].subjectId+','+j[i].groupId+','+j[i].periodId+');return false;">'+j[i].className+'</font></a></td></tr>';
                        }
                    }
                    else{
                       str +='<tr><td class="reportBorder" align="left" width="100%" colspan="4" nowrap>'+noDataFoundVar+'</td></tr>';
                    }
                    document.getElementById('attendanceOptionsResults').innerHTML='<table border="0" cellpadding="2" cellspacing="0" width="100%">'+str+'</table>';
                    displayWindow('AttendanceHelpDiv','350','150');

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>"); }
           });
 }


//this function is used to print attendance history report
function printReport(){
    var className='';
    var subjectName='';
    var groupName='';
    if(document.getElementById('class').selectedIndex>0){
     className=document.getElementById('class').options[document.getElementById('class').selectedIndex].text;
    }
    if(document.getElementById('subject').selectedIndex>0){
     subjectName=document.getElementById('subject').options[document.getElementById('subject').selectedIndex].text;
    }
    if(document.getElementById('group').selectedIndex>0){
     groupName=document.getElementById('group').options[document.getElementById('group').selectedIndex].text;
    }

   var timeTableLabelId='';
   var employeeId='';
   var timeTableLabel='';
   var employeeName='';
   if(roleId!=2){
    timeTableLabelId=document.getElementById('timeTableLabelId').value;
    employeeId=document.getElementById('employeeId').value;
    if(timeTableLabelId==""){
         messageBox("<?php echo SELECT_TIME_TABLE_LABEL;?>");
         document.getElementById('timeTableLabelId').focus();
         return false;
    }
    timeTableLabel=document.getElementById('timeTableLabelId').options[document.getElementById('timeTableLabelId').selectedIndex].text;
    if(employeeId==""){
         messageBox("Select teacher");
         document.getElementById('employeeId').focus();
         return false;
    }
    employeeName=document.getElementById('employeeId').options[document.getElementById('employeeId').selectedIndex].text;
   }
    var qstr='&classId='+document.getElementById('class').value+'&subjectId='+document.getElementById('subject').value+'&groupId='+document.getElementById('group').value+'&attType=2'+'&className='+className+'&subjectName='+subjectName+'&groupName='+groupName;
    qstr=qstr+"&sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField;
    qstr=qstr+"&timeTableLabelId="+timeTableLabelId+"&employeeId="+employeeId;
    qstr=qstr+"&timeTableLabel="+timeTableLabel+"&employeeName="+employeeName;
    path='<?php echo UI_HTTP_PATH;?>/Teacher/attendanceHistoryPrint.php?'+qstr;
    window.open(path,"AttendanceHistoryReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

//this function is used to make CSV version of attendance history report
function printCSV(){

   var timeTableLabelId='';
   var employeeId='';
   if(roleId!=2){
    timeTableLabelId=document.getElementById('timeTableLabelId').value;
    employeeId=document.getElementById('employeeId').value;
    if(timeTableLabelId==""){
         messageBox("<?php echo SELECT_TIME_TABLE_LABEL;?>");
         document.getElementById('timeTableLabelId').focus();
         return false;
    }
    if(employeeId==""){
         messageBox("Select teacher");
         document.getElementById('employeeId').focus();
         return false;
    }
   }
    var qstr='&classId='+document.getElementById('class').value+'&subjectId='+document.getElementById('subject').value+'&groupId='+document.getElementById('group').value+'&attType=2';
    qstr=qstr+"&sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField;
    qstr=qstr+"&timeTableLabelId="+timeTableLabelId+"&employeeId="+employeeId;
    var path='<?php echo UI_HTTP_PATH;?>/Teacher/attendanceHistoryCSV.php?'+qstr;
    window.location=path;
}

//to set attenance options
function setAttendanceOptions(classId,subjectId,groupId,periodId){
    setGlobalEditFlag(0);
    document.getElementById('class').value=classId;
    populateSubjects(classId);
    document.getElementById('subject').value=subjectId;
    if(document.getElementById('subject').options.length>2){
     topicPopulate(subjectId);
    }
    groupPopulate(subjectId);
    document.getElementById('group').value=groupId;
    if(document.getElementById('group').options.length>2){
     getPeriodNames();
    }
    document.getElementById('period').value=periodId;
    hiddenFloatingDiv('AttendanceHelpDiv');
    getData();
}


//this function is used to delete attendance records
function deleteAttendanceData(attendanceId,employeeName){
 if(attendanceId==''){
     messageBox("Invalid selection");
     return false;
 }
 if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
    return false;
 }
 hiddenFloatingDiv('AttendanceHistoryDiv');

 var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxDeleteAttendanceData.php';
 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 attendanceId : attendanceId,
				 employeeName : employeeName,
                 moduleName       : "<?php echo MODULE;?>"
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                     setGlobalEditFlag(0);
                     resetForm();
                     messageBox("Selected attendance record deleted");
                    }
                    else{
                        messageBox(trim(transport.responseText));
                    }

             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}


function editAttendance(classId,subjectId,groupId,periodId,dateValues){
    setGlobalEditFlag(0);
    document.getElementById('forDate').value=dateValues;
    refreshDropDowns();
    document.getElementById('class').value=classId;
    populateSubjects(classId);
    document.getElementById('subject').value=subjectId;
    if(document.getElementById('subject').options.length>2){
     topicPopulate(subjectId);
    }
    groupPopulate(subjectId);
    document.getElementById('group').value=groupId;
    if(document.getElementById('group').options.length>2){
     getPeriodNames();
    }
    document.getElementById('period').value=periodId;
    getData();
    hiddenFloatingDiv('AttendanceHistoryDiv');
}


function openAttendanceShortCutDiv(){
    hideDropDowns(0);
    document.getElementById('rollNoTxt').value='';
    //document.getElementById('shortAttCode').value='';
    if(document.getElementById('shortAttCode').options.length>0){
        document.getElementById('shortAttCode').selectedIndex=0;
    }
    displayWindow('AttendanceShortCutDiv','900','400');
    document.getElementById('rollNoTxt').focus();
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


function hiddenFloatingDiv(divId){
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
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listDailyAttendanceContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>

</body>
</html>
