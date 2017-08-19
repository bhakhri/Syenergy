<?php
//used for showing class wise grades
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH.'/helpMessage.inc.php');
define('MODULE','DutyLeavesAdvanced');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Duty Leaves Entry </title>
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

//to stop special formatting
specialFormatting=0;

recordsPerPage = <?php echo RECORDS_PER_PAGE_TEACHER;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;


var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function getData(){
    if(document.getElementById('classId').value==''){
        messageBox("<?php echo SELECT_CLASS; ?>");
        document.getElementById('classId').focus();
        return false;
    }
   if(document.getElementById('subject').value==''){
        messageBox("<?php echo SELECT_SUBJECT; ?>");
        document.getElementById('subject').focus();
        return false;
    } 
   if(document.getElementById('group').value==''){
        messageBox("<?php echo SELECT_GROUP; ?>");
        document.getElementById('group').focus();
        return false;
    } 
 /*To solve the problem Of "Thinking message not coming in IE" when asynchoronous:false*/
 showWaitDialog(true);
 window.setTimeout(getStudentList, 1);
 //getStudentList();
 return false;  
   
}


function populateSubjects(classId){
    document.getElementById('subject').options.length=1;
    document.getElementById('group').options.length=1;
    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAdjustedSubject.php';
    
    if(classId==''){
      return false;
    }
    
    new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 classId: classId,
                 startDate : document.getElementById('startDate').value,
                 endDate   : document.getElementById('endDate').value
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')');
                    var len=j.length;
                    for(var c=0;c<len;c++){
                      if(j[c].hasAttendance==1) {
                        var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                        document.searchForm.subject.options.add(objOption);
                      }
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}


function groupPopulate(value) {
   var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAdjustedGroup.php';
   document.searchForm.group.options.length=0;
   var objOption = new Option("Select Group","");
   document.searchForm.group.options.add(objOption); 
   
   if(document.getElementById('subject').value==""){
       return false;
   }
   if(document.getElementById('classId').value==""){
       return false;
   }
   

 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 subjectId : document.getElementById('subject').value,
                 classId   : document.getElementById('classId').value,
                 startDate : document.getElementById('startDate').value,
                 endDate   : document.getElementById('endDate').value
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')');
                    var len=j.length;
                    for(var c=0;c<len;c++){
                         var objOption = new Option(j[c].groupName,j[c].groupId);
                         document.searchForm.group.options.add(objOption);
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

function getStudentList(){
  var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxStudentDutyLeaveAdvancedList.php';
  document.getElementById('saveTr1').style.display='none';
  document.getElementById('saveTr2').style.display='none';
  var tableColumns = new Array(
                        new Array('srNo','#','width="1%" align="left"',false),
                        new Array('rollNo','Roll No.','width="8%" align="left"',true),
                        new Array('universityRollNo','Univ. Roll No.','width="12%" align="left"',true),
                        //new Array('regNo','Reg. No.','width="10%" align="left"',true), 
                        new Array('studentName','Name','width="15%" align="left"',true),
                        new Array('delivered','Delivered','width="8%" align="right"',true),
                        new Array('attended','Attended','width="8%" align="right"',true),
                        new Array('leavesTaken','Duty Leaves&nbsp;<input type="image" src="<?php echo IMG_HTTP_PATH?>/help_on.gif" name="helpOnOFF" style="margin-bottom:-5px;" class="Duty Leaves" value="<?php echo str_replace('"',"",HELP_DUTY_LEAVES_RESTRICATION); ?>" onClick="showHelpDetails(this.className,this.value);" />','width="10%" align="right"',false),
                        new Array('comments','Comments','width="24%" align="left"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','rollNo','ASC','results','','',true,'listObj1',tableColumns,'','','&classId='+document.getElementById('classId').value+'&subjectId='+document.getElementById('subject').value+'&groupId='+document.getElementById('group').value+'&studentRollNo='+trim(document.getElementById('rollNo').value));
 sendRequest(url, listObj1, ' ',false);

 if(listObj1.totalRecords>0){
      document.getElementById('saveTr1').style.display='';
      document.getElementById('saveTr2').style.display='';
 }

}

function checkDutyLeaveValidation(index){
     var dutyLeave = document.getElementById("leavesTaken"+index);
     var attended  = document.getElementById("hiddenAtt"+index);
     var delivered = document.getElementById("hiddenDel"+index);
    
    //check for numeric value
    var s = dutyLeave.value.toString();
    var fl=0;
    var l=s.length;
    for (var i = 0; i < l; i++){
     var c = s.charAt(i); 
     if(!isInteger(c) && c!='.'){
       dutyLeave.value=dutyLeave.value.replace(c,""); 
       fl=1; 
     }
   }
   if(fl){
      dutyLeave.focus();
      return false;
   }
   if( parseInt(parseInt(dutyLeave.value,10)+parseInt(attended.value,10),10) > parseInt(delivered.value,10) ){
      dutyLeave.className='inputboxRed';
   }
   else{
       dutyLeave.className='inputbox';
   }
}


function washOutData(){
    document.getElementById('results').innerHTML='&nbsp;';
    document.getElementById('saveTr1').style.display='none';
    document.getElementById('saveTr2').style.display='none';
}

function resetForm(){
 //document.getElementById('results').style.display='none';
 document.getElementById('results').innerHTML='&nbsp;';
 document.getElementById('saveTr1').style.display='none';
 document.getElementById('saveTr2').style.display='none';
}

function giveDutyLeaves(){
  
  /*validate input data*/
   var ele=document.getElementById('results').getElementsByTagName('INPUT');
   var len=ele.length;
   var dutyLeaves='';
   var comments='';
   var ids='';
   for(var i=0;i<len;i++){
       if(ele[i].type.toUpperCase()=='TEXT'){
           if(ele[i].name=='leavesTaken'){
               if(trim(ele[i].value)==''){
                   messageBox("<?php echo EMPTY_DUTY_LEAVE; ?>");
                   ele[i].focus();
                   return false;
               }
               if(!isNumeric(trim(ele[i].value))){
                   messageBox("<?php echo ENTER_DUTY_LEAVE_IN_NUMERIC; ?>");
                   ele[i].focus();
                   return false;
               }
              /*Check for (dl+att<=delivered)*/ 
              var dlId=ele[i].id.split('leavesTaken')[1];
              var dutyLeave = document.getElementById("leavesTaken"+dlId);
              var attended  = document.getElementById("hiddenAtt"+dlId);
              var delivered = document.getElementById("hiddenDel"+dlId); 
              if( parseInt(parseInt(dutyLeave.value,10)+parseInt(attended.value,10),10) > parseInt(delivered.value,10) ){
                  dutyLeave.className='inputboxRed';
                  messageBox("<?php echo DUTY_LEAVE_RESTRICTION;?>");
                  dutyLeave.focus();
                  return false;
               }
               else{
                   dutyLeave.className='inputbox';
               }
              /*Check for (dl+att<=delivered)*/ 
              if(dutyLeaves!=''){
                 dutyLeaves +=','; 
              }
              dutyLeaves +=trim(ele[i].value);
             
              if(ids!=''){
               ids +=',';
              }
             ids +=ele[i].alt;
           }
          if(ele[i].name=='leavesComment'){
             if(comments!=''){
                 comments +='!~@~!'; 
              }
             comments +=' '+trim(ele[i].value); 
          }
       }
   }
  /*validate input data*/
  
  var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/doStudentDutyLeaveAdvanced.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 ids              : ids,
                 dutyLeaves       : dutyLeaves,
                 comments         : comments,
                 classId          : document.getElementById('classId').value,
                 subjectId        : document.getElementById('subject').value,
                 groupId          : document.getElementById('group').value,
                 studentRollNo    : trim(document.getElementById('rollNo').value),
                 page             : listObj1.page
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)=="<?php echo SUCCESS; ?>") {
                        messageBox("<?php echo DUTY_LEAVES_GIVEN; ?>");
                        document.getElementById('results').innerHTML='&nbsp;';
                        document.getElementById('saveTr1').style.display='none';
                        document.getElementById('saveTr2').style.display='none';
                    }
                    else{
                        messageBox(trim(transport.responseText));
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });  
    
}

var topPos = 0;
var leftPos = 0;
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
  
window.onload=function(){
    document.searchForm.reset();
    var roll = document.getElementById("rollNo");
    autoSuggest(roll);
    document.getElementById('results').innerHTML='&nbsp;';
}
</script>

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/dutyLeaveEntryAdvancedContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");    
?>
</body>
</html>