<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentRegistrationReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
global $sessionHandler;
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn();
}
else{
  UtilityManager::ifNotLoggedIn();
}
UtilityManager::headerNoCache(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Student Registration Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
 // Section    teacher    day    period    room                               

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('className','Class Name','width="20%"','',true),  
                               new Array('universityRollNo','Roll No.','width="10%"','',true),
                               new Array('studentName','Student Name','width="15%"','',true), 
                               new Array('fatherName',"Father's Name",'width="15%"','',true), 
                               new Array('studentMobileNo',"Mobile No.",'width="12%"','',true),
                               new Array('employeeName1','Mentor Name','width="11%"','align="left"',true) , 
                               new Array('registrationDate','Date of Reg.','width="11%"','align="center"',true) ,
                                 new Array('feeStatus','Fee Status','width="11%"','align="center"',true) , 
                               new Array('checkAll','Status<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="11%"','align=\"center\"',false),   
                               new Array('action1',"Details",'width="10%" align="center"','align="center"',false));
                               
// new Array('attend',"Hold Attendance",'width="200"','',true)                               
recordsPerPage = 1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/Mentees/ajaxInitList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'className';
sortOrderBy    = 'ASC';
queryString='';

// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h){
    displayWindow(dv,w,h);
    populateValues(id);
}

function showMentorshipDetails(mentorshipId,studentId,classId) {
     height=screen.height/7;
    width=screen.width/3.5;
    displayFloatingDiv('divMentorship','', 600,600, width,height);
    //displayWindow('divNotice',600,600);
    populateMentorshipValues(mentorshipId,studentId,classId);   
}

function populateMentorshipValues(mentorshipId,studentId,classId) {
	 currentMentorshipId = mentorshipId;

      url = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/ScTeacherActivity/scAjaxGetMentorshipCommentsDetail.php';
	  var val =1;//document.getElementById("searchbox").value;
	  tableColumns = new Array(
				new Array('srNo','#','width="2%" align="center"',false), 
				new Array('commentDate', 'Comment Date','width="20%" align="center"',false),
                new Array('employeeNameCode','Mentor Name','width="25%" align="left"',false),
				new Array('comments','Comments','width="42%" align="left"',false),
				new Array('action1','Action','width="5%" align="center"',false)
			        );
	 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
     str = "mentorshipId="+mentorshipId+"&studentId="+studentId+"&classId="+classId;
	 listObj4 = new initPage(url,recordsPerPage,linksPerPage,1,'','','ASC','mentorshipCommentDiv','','',true,'listObj4',tableColumns,'','deleteMentorship','&searchbox='+val+'&'+str);

	 sendRequest(url, listObj4, '', true);
}


function showMessageDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 400, 200)
    populateMessageValues(id);
}

function getUpdateStatus(id) {
   str = "lbl"+id; 
   if(eval("document.getElementById('chb_"+id+"').checked")){  
     eval("document.getElementById('"+str+"').innerHTML='<b>Approve</b>'"); 
     getAllow(1,id);
   }
   else {
     eval("document.getElementById('"+str+"').innerHTML='Unapprove'");   
     getAllow(0,id);
   }
}

function doAll(){

   var isAllowRegistration='0'; 
   var studentChk='0'; 
   formx = document.listForm;
   if(formx.checkbox2.checked){
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
               formx.elements[i].checked=true;
               str = "lbl"+formx.elements[i].value; 
               studentChk =studentChk+ ","+formx.elements[i].value;
               isAllowRegistration='1';
               eval("document.getElementById('"+str+"').innerHTML='<b>Approve</b>'");
            }
        }
    }
    else{
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){  
              formx.elements[i].checked=false;
              str = "lbl"+formx.elements[i].value; 
              studentChk =studentChk+ ","+formx.elements[i].value;
              isAllowRegistration='0';
              eval("document.getElementById('"+str+"').innerHTML='Unapprove'");
            }
        }
    }
    if(studentChk!='0') {
      getAllow(isAllowRegistration,studentChk);  
    }
}

function getAllow(isAllowRegistration,studentChk) {
    
    url = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/Mentees/ajaxInitAllow.php';
    new Ajax.Request(url,
    {
      method:'post',
      parameters: { isAllowRegistration: isAllowRegistration,
                    studentChk : studentChk
                  },
      asynchronous:false,              
      onSuccess: function(transport){
       if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
          showWaitDialog(true);
       }
       else {
         hideWaitDialog(true);
         if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
  
         } 
         else {
           messageBox(trim(transport.responseText)); 
         }
       }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divNotice" DIV
//
//Author : Parveen Sharma
// Created on : (27.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateMessageValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/SMSReports/ajaxGetMessageDetails.php';   
         new Ajax.Request(url,
         {      
             method:'post',
             parameters: {messageId: id},
              onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                hideWaitDialog();
                j = eval('('+transport.responseText+')');
                document.getElementById('message').innerHTML= j.message;  
             },
             onFailure: function(){ alert('Something went wrong...') }
           });
}

function validateRegistrationForm() {
    
    var queryString='';       
    var str = '';
    var mentorName='';
    <?php
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId!=2){   
    ?>
       /* 
        if(document.getElementById('mentorName').value==""){
          messageBox("Please Select Mentor Name");
          document.getElementById('mentorName').focus();
          return false;
        } 
       */
       mentorName = document.getElementById('mentorName').value;  
    <?php
    }
    else {
    ?>    
       mentorName = "<?php echo $sessionHandler->getSessionVariable('UserId'); ?>";  
       str = '&mentorName='+mentorName;  
    <?php
    } 
    ?>
    
    var regId='3';
    if(document.listForm.registered[0].checked==true) {   
      regId='1';
    }    
    else if(document.listForm.registered[1].checked==true) {   
      regId='2';
    }    
   
    queryString = 'mentorName='+mentorName+'&studentName='+trim(document.getElementById('studentName').value);
    queryString = queryString+'&rollNo='+trim(document.getElementById('rollNo').value)+'&registered='+regId;
    queryString = queryString+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display=''; 
    document.getElementById("resultRow").style.display='';
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+str,false);
    //sendReq(listURL,divResultName,'listForm','');
    return false;
}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none'; 
}


function printReport() {
   
    var queryString='';       
    var str = '';
    var mentorName='';
    <?php
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');

    if($roleId!=2){   
    ?>
      /*  if(document.getElementById('mentorName').value==""){
          messageBox("Please Select Mentor Name");
          document.getElementById('mentorName').focus();
          return false;
        } 
      */  
        mentorName = document.getElementById('mentorName').value;  
    <?php
    }
    else {
    ?>    
       mentorName = "<?php echo $sessionHandler->getSessionVariable('UserId'); ?>";  
       str = '&mentorName='+mentorName;  
    <?php
    } 
    ?>
    
    var regId='3';
    if(document.listForm.registered[0].checked==true) {   
      regId='1';
    }    
    else if(document.listForm.registered[1].checked==true) {   
      regId='2';
    }    
   
    queryString = 'mentorName='+mentorName+'&studentName='+trim(document.getElementById('studentName').value);
    queryString = queryString+'&rollNo='+trim(document.getElementById('rollNo').value)+'&registered='+regId;
    queryString = queryString+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    
    path='<?php echo UI_HTTP_PATH;?>/RegistrationForm/registrationReportPrint.php?'+queryString;
    hideUrlData(path,true);
}

/* function to print all payment history to csv*/
function printReportCSV() {
    var queryString='';       
    var str = '';
    var mentorName='';
    <?php
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId!=2){   
    ?>
      /*
        if(document.getElementById('mentorName').value==""){
          messageBox("Please Select Mentor Name");
          document.getElementById('mentorName').focus();
          return false;
        } 
      */  
        mentorName = document.getElementById('mentorName').value;  
    <?php
    }
    else {
    ?>    
       mentorName = "<?php echo $sessionHandler->getSessionVariable('UserId'); ?>";  
       str = '&mentorName='+mentorName;  
    <?php
    } 
    ?>
    
    var regId='3';
    if(document.listForm.registered[0].checked==true) {   
      regId='1';
    }    
    else if(document.listForm.registered[1].checked==true) {   
      regId='2';
    }    
   
    queryString = 'mentorName='+mentorName+'&studentName='+trim(document.getElementById('studentName').value);
    queryString = queryString+'&rollNo='+trim(document.getElementById('rollNo').value)+'&registered='+regId;
    queryString = queryString+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;    
    path='<?php echo UI_HTTP_PATH;?>/RegistrationForm/registrationReportCSV.php?'+queryString;  
    window.location=path;
}

function getCurrentMentor(labelId) {
        
    form = document.listForm;

    form.mentorName.length = null; 
    addOption(form.mentorName, '', 'Select');

    var url = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/ChangeMentor/ajaxGetCurrentMentor.php';
    new Ajax.Request(url,
    {
         method:'post',
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + trim(transport.responseText) + ')');
            form.mentorName.length = null; 
            addOption(form.mentorName, '', 'All');
             var len=j.length;
             for(i=0;i<len;i++){
                instituteCode = j[i].instituteCode; 
                if(j[i].isTeaching=='1') {
                  str = j[i].employeeName1+" (Teaching - "+instituteCode+") ";  
                }
                else {
                  str = j[i].employeeName1+" (Non Teaching - "+instituteCode+") ";  
                } 
                addOption(form.mentorName, j[i].userId, str);
             }
         },
         onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}

window.onload=function(){
  <?php
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId!=2) {   
      echo "getCurrentMentor();";
    }
    else {
      echo "validateRegistrationForm();";
    }
  ?>
}

function openUrl(id){
   var w=window.open("RegistrationForm/scStudentDetail.php?mod=1&id="+id,"_parent");   //opens details(default) page
}


function mentorshipBlankValues() {
	document.MentorshipForm.mentorshipComments.value = '';
    document.MentorshipForm.mentorshipComments.focus();
}


function deleteMentorshipComment(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
         url = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/ScTeacherActivity/ajaxInitMentorshipDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {mentorshipCommentId:id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport) {
                     hideWaitDialog(true);
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                    // sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
						populateMentorshipValues(currentMentorshipId);
                         return false;
                     }
                 else {
                       messageBox(trim(transport.responseText));                         
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }
  }

function addComments() {
	  
 url = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/ScTeacherActivity/ajaxInitMentorshipCommentsAdd.php';
 new Ajax.Request(url,
   {
     method:'post',
     parameters: {comments:document.MentorshipForm.mentorshipComments.value, 
                  mentorshipId: currentMentorshipId
                 },
     onCreate: function() {
         showWaitDialog(true);
     },
     onSuccess: function(transport) {
             hideWaitDialog(true);
             if("<?php echo SUCCESS;?>"==trim(transport.responseText)) {
                //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
				mentorshipBlankValues();
				populateMentorshipValues(currentMentorshipId);
                return false;
             }
         else {
               messageBox(trim(transport.responseText));                         
           }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });

}


 function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("mentorshipComments","<?php echo ENTER_MENTORSHIP_COMMENTS; ?>")
		                    	);

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            //unsetAlertStyle(fieldsArray[i][0]);
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<1 && fieldsArray[i][0]=='mentorshipComments' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_MENTORSHIP_COMMENTS; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            } 
	    }
    }
    if(act=='Add') {
	   addComments();
       return false;
    }
   
}
</script>
</head>
<body>
<?php   
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    require_once(TEMPLATES_PATH . "/header.php");
    if($roleId==2){ 
      require_once(TEMPLATES_PATH . "/RegistrationForm/Mentees/listRegistrationTeacherContent.php");
    }
    else {
       require_once(TEMPLATES_PATH . "/RegistrationForm/Mentees/registrationDetailReport.php");  
    }
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
