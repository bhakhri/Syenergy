<?php
//---------------------------------------------------------------------------
//  THIS FILE used for sending message(sms/email/dashboard) to students
// Author : Dipanjan Bhattacharjee
// Created on : (20.01.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','SendStudentPerformanceMessageToParents');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Send student performance message to parents</title>
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

var tableHeadArray = new Array(
 new Array('srNo','#','width="2%"','',false),
 new Array('universityRollNo','Univ. Reg. No.','width="12%"','',true),
 new Array('rollNo','Roll No','width="8%"','',true) ,
 new Array('studentName','<input type=\"checkbox\" id=\"studentList\" name=\"studentList\" onclick=\"selectStudents();\">Student','width="22%"','align=\"left\"',true),
 new Array('fatherName','<input type=\"checkbox\" id=\"fatherList\" name=\"fatherList\" onclick=\"selectFathers();\">Father','width="22%"','align=\"left\"',true),
 new Array('motherName','<input type=\"checkbox\" id=\"motherList\" name=\"motherList\" onclick=\"selectMothers();\">Mother','width="22%"','align=\"left\"',true),
 new Array('guardianName','<input type=\"checkbox\" id=\"guardianList\" name=\"guardianList\" onclick=\"selectGuardians();\">Guardian','width="22%"','align=\"left\"',true));


recordsPerPage = <?php echo RECORDS_PER_PAGE_ADMIN_MESSAGE ;?>;

linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/AdminMessage/ajaxStudentPerformanceMessageList.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
/*
addFormName    = 'AddCity';
editFormName   = 'EditCity';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteCity';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'universityRollNo';
sortOrderBy = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button



//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether a control is object or not
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function chkObject(id){
  obj = document.listFrm.elements[id];
  if(obj.length > 0) {
      return true;
  }
  else{
    return false;;
  }
}


//--------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR help bar

// Author     :Gagan Gill
// Created on : (07.10.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------
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


//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all fathers checkboxes
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function  selectFathers(){

    //state:checked/not checked
    var state=document.getElementById('fatherList').checked;
    if(!chkObject('fathers')){
     if(!document.listFrm.fathers.disabled){
     document.listFrm.fathers.checked =state;
     }
     return true;
    }
    formx = document.listFrm;

    var l=formx.fathers.length;

    for(var i=0 ;i < l ; i++){
      if(!formx.fathers[ i ].disabled){
        formx.fathers[ i ].checked=state;
      }
    }

}


//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all fathers checkboxes
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function  selectStudents(){

    //state:checked/not checked
    var state=document.getElementById('studentList').checked;
    if(!chkObject('students')){
     if(!document.listFrm.students.disabled){
     document.listFrm.students.checked =state;
     }
     return true;
    }
    formx = document.listFrm;

    var l=formx.students.length;

    for(var i=0 ;i < l ; i++){
      if(!formx.students[ i ].disabled){
        formx.students[ i ].checked=state;
      }
    }

}

//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all mothers checkboxes
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function  selectMothers(){

    //state:checked/not checked
    var state=document.getElementById('motherList').checked;
    if(!chkObject('mothers')){
    if(!document.listFrm.mothers.disabled){
     document.listFrm.mothers.checked =state;
    }
     return true;
    }
    formx = document.listFrm;

    var l=formx.mothers.length;

    for(var i=0 ;i < l ; i++){
      if(!formx.mothers[ i ].disabled){
        formx.mothers[ i ].checked=state;
      }
    }

}

//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all guardians checkboxes
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function  selectGuardians(){

    //state:checked/not checked
    var state=document.getElementById('guardianList').checked;
    if(!chkObject('guardians')){
     if(!document.listFrm.guardians.disabled){
      document.listFrm.guardians.checked =state;
     }
     return true;
    }
    formx = document.listFrm;

    var l=formx.guardians.length;

    for(var i=0 ;i < l ; i++){
      if(!formx.guardians[ i ].disabled){
        formx.guardians[ i ].checked=state;
      }
    }

}


//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any father checkboxes selected or not
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
function checkFathers(){

    var fl=0;
    if(!chkObject('fathers')){
     if(document.listFrm.fathers.checked==true){
         fl=1;
     }
     return fl;
   }

    formx = document.listFrm;
    var l=formx.fathers.length;
    for(var i=0 ;i < l ; i++){
        if(formx.fathers[ i ].checked==true){
            fl=1;
            break;
        }
    }

    return (fl);
}


//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any mother checkboxes selected or not
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
function checkMothers(){

    var fl=0;
    if(!chkObject('mothers')){
     if(document.listFrm.mothers.checked==true){
         fl=1;
     }
     return fl;
   }

    formx = document.listFrm;
    var l=formx.mothers.length;
    for(var i=0 ;i < l ; i++){
        if(formx.mothers[ i ].checked==true){
            fl=1;
            break;
        }
    }

    return (fl);

}


//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any guardian checkboxes selected or not
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
function checkGuardians(){

    var fl=0;
    if(!chkObject('guardians')){
     if(document.listFrm.guardians.checked==true){
         fl=1;
     }
     return fl;
   }

    formx = document.listFrm;
    var l=formx.guardians.length;
    for(var i=0 ;i < l ; i++){
        if(formx.guardians[ i ].checked==true){
            fl=1;
            break;
        }
    }

    return (fl);

}

//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any guardian checkboxes selected or not
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
function checkStudents(){

    var fl=0;
    if(!chkObject('students')){
     if(document.listFrm.students.checked==true){
         fl=1;
     }
     return fl;
   }

    formx = document.listFrm;
    var l=formx.students.length;
    for(var i=0 ;i < l ; i++){
        if(formx.students[ i ].checked==true){
            fl=1;
            break;
        }
    }

    return (fl);

}

//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any father/mother/guardian checkboxes selected or not
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
function checkParents(){
  if(checkFathers() || checkMothers() || checkGuardians() || checkStudents()){
      return 1;
  }
  else{
      return 0;
  }
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO hide "showList" div
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function hide_div(id,mode){

    if(mode==2){
     document.getElementById(id).style.display='none';
    }
    else{
        document.getElementById(id).style.display='block';
    }
}



//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------

//This function Validates Form
var myQueryString;
var allStudentId;

function validateHeaderPart(){
    if(document.getElementById('timeTableLabelId').value==''){
        messageBox("<?php echo SELECT_TIME_TABLE; ?>");
        document.getElementById('timeTableLabelId').focus();
        return false;
    }
    if(document.getElementById('classId').value==''){
        messageBox("<?php echo SELECT_CLASS; ?>");
        document.getElementById('classId').focus();
        return false;
    }
    if(document.getElementById('groupId').value==''){
        messageBox("<?php echo SELECT_GROUP; ?>");
        document.getElementById('groupId').focus();
        return false;
    }
    if(document.getElementById('msgType').value==2){
     if(document.getElementById('testId').value==''){
        messageBox("<?php echo SELECT_TEST; ?>");
        document.getElementById('testId').focus();
        return false;
     }
    }

    return true;
}

function validateAddForm() {


   if(document.getElementById('msgType').value==''){
    messageBox("Select message type");
    document.getElementById('msgType').focus();
    return false;
   }

  if(document.getElementById('msgType').value==1){
    if(!dateDifference(document.getElementById('attendanceUpToDate').value,serverDate,'-')){
        messageBox("Date cannot be greater than current date");
        document.getElementById('attendanceUpToDate').focus();
        return false;
    }
  }

  if(!validateHeaderPart()){
      return false;
  }

  //document.getElementById('totalRecordsId').innerHTML='<b>0</b>';
  document.getElementById('sendToAllChk').checked=false;
  sendReq(listURL,divResultName,searchFormName, ' ',false);
  //document.getElementById('totalRecordsId').innerHTML='<b>'+j.totalRecords+'</b>';

  allStudentId=j.studentInfo; //all studentIds

    hide_div('showList',1);
    if((document.listFrm.fathers.length - 2) > 0){
       document.getElementById('divButton').style.display='block';
     }
    else{
           document.getElementById('divButton').style.display='none';
     }
}

function hideDetails() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d');?>";

function validateForm() {


if((document.listFrm.fathers.length - 2) == 0){
   messageBox("<?php echo NO_DATA_SUBMIT; ?>");
   return false;
 }

if(document.getElementById('msgType').value==''){
    messageBox("Select message type");
    document.getElementById('msgType').focus();
    return false;
}

if(document.getElementById('msgType').value==1){
    if(!dateDifference(document.getElementById('attendanceUpToDate').value,serverDate,'-')){
        messageBox("Date cannot be greater than current date");
        document.getElementById('attendanceUpToDate').focus();
        return false;
    }
}

//checkes whether any student/parent checkboxes selected or not[if "send to all" is not checked]
if(!(checkParents())){  //checkes whether any parent checkboxes selected or not
     alert("<?php echo SELECT_PARENT_MSG; ?>");
     document.getElementById('fatherList').focus();
     return false;
  }
else{
     sendMessage();; //sends the message to All
     return false;
    }
  }



//-------------------------------------------------------
//THIS FUNCTION IS USED TO send message
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function sendMessage() {
         var url = '<?php echo HTTP_LIB_PATH;?>/AdminMessage/sendStudentPerformanceMessage.php';

         //determines which student and parents are selected and their studentIds
         formx = document.listFrm;
         var father="";  //get studentIds when student checkboxes are selected
         var mother="";  //get studentIds when student checkboxes are selected
         var guardian="";  //get studentIds when student checkboxes are selected
         var student="";  //get studentIds when student checkboxes are selected

         if((document.listFrm.fathers.length - 2)<=1){
           father=(document.listFrm.fathers[2].checked ? document.listFrm.fathers[2].value : "0" );
           mother=(document.listFrm.mothers.checked ? document.listFrm.mothers.value : "0" );
           guardian=(document.listFrm.guardians.checked ? document.listFrm.guardians.value : "0" );
           student=(document.listFrm.students.checked ? document.listFrm.students.value : "0" );
        }
        else{
         var m=formx.fathers.length;
         for(var k=2 ; k < m ; k++){ //started from 2 for two dummy fields.
            if(formx.fathers[ k ].checked==true){
                if(father==""){
                    father= formx.fathers[ k ].value;
                }
               else{
                    father+="," + formx.fathers[ k ].value;
               }
            }
         }

         for(k=0 ; k < m-2 ; k++){ //started from 2 for two dummy fields.
            if(formx.mothers[ k ].checked==true){
                if(mother==""){
                    mother= formx.mothers[ k ].value;
                }
               else{
                    mother+="," + formx.mothers[ k ].value;
               }
            }
         }

         for(k=0 ; k < m-2 ; k++){ //started from 2 for two dummy fields.
            if(formx.guardians[ k ].checked==true){
                if(guardian==""){
                    guardian= formx.guardians[ k ].value;
                }
               else{
                    guardian+="," + formx.guardians[ k ].value;
               }
            }
         }

         for(k=0 ; k < m-2 ; k++){ //started from 2 for two dummy fields.
            if(formx.students[ k ].checked==true){
                if(student==""){
                    student= formx.students[ k ].value;
                }
               else{
                    student+="," + formx.students[ k ].value;
               }
            }
         }


        }

        if(document.getElementById('msgType').value==2){
            var testId=document.getElementById('testId').value;
        }
        else{
            var testId=-1;
        }

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
             student: (student),
             father: (father),
             mother: (mother),
             guardian: (guardian),
             msgMode : document.getElementById('msgType').value,
             attendanceUpToDate:(document.getElementById('attendanceUpToDate').value),
             dutyLeaves : document.allDetailsForm.dutyLeaves[0].checked==true?1:0,
             testId     : testId
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     var ret=trim(transport.responseText).split('!~!~!');
                     var eStr='';
                     fl = 0;
                     if("<?php echo SUCCESS;?>" == ret[0]) {
                       type="";
                       flag = true;
                       if(ret[1]!=''){
                         eStr ='\nSMS not sent to these parents :\n'+ret[1];
                         type="p";
                         fl = 1;
                       }
                       else {
                          ret[1]=-1;
                       }
                       if(ret[2]!=''){
                         eStr ='\nSMS not sent to these parents :\n'+ret[2];
                         type="p";
                         fl = 1;
                       }
                       else {
                          ret[2]=-1;
                       }
                       if(ret[3]!=''){
                         eStr ='\nSMS not sent to these parents :\n'+ret[3];
                         type="p";
                         fl = 1;
                       }
                       else {
                          ret[3]=-1;
                       }
                       if(ret[4]!=''){
                         eStr +='\nEmail not sent to these parents :\n'+ret[4];
                         type="p";
                         fl = 1;
                       }
                       else {
                          ret[4]=-1;
                       }
                       if(ret[5]!=''){
                         eStr ='\nSMS not sent to these parents :\n'+ret[5];
                         type="p";
                         fl = 1;
                       }
                       else {
                          ret[5]=-1;
                       }
                       if(ret[6]!=''){
                         eStr +='\nEmail not sent to these parents :\n'+ret[6];
                         type="p";
                         fl = 1;
                       }
                       else {
                          ret[6]=-1;
                       }
                       if(ret[7]!=''){
                         eStr ='\nSMS not sent to these students :\n'+ret[7];
                         type="s";
                         fl = 1;
                       }
                       else {
                          ret[7]=-1;
                       }
                       if(ret[8]!=''){
                         eStr +='\nEmail not sent to these students :\n'+ret[8];
                         type="s";
                         fl = 1;
                       }
                       else {
                          ret[8]=-1;
                       }
                       if(fl==1){
                         if(confirm("<?php echo MESSAGE_NOT_SEND; ?>")){
                           window.location = "<?php echo UI_HTTP_PATH ?>/detailsAdminMessageDocument.php?type="+type+"&reportType=s&smsFatherIds="+ret[1]+"&emailFatherIds="+ret[2]+"&smsMotherIds="+ret[3]+"&emailMotherIds="+ret[4]+"&smsGuardianIds="+ret[5]+"&emailGuardianIds="+ret[6]+"&smsStudentIds="+ret[7]+"&emailStudentIds="+ret[8];
                         }
                       }
                       else {
                         messageBox("<?php echo MSG_SENT_OK; ?>"+eStr);
                       }
                     }
                     else {
                        messageBox(ret[0]);
                     }
                     resetForm();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//---------------------------------------------------------------------------------
//purspose:to show date options when msgmedium is dashboard
//Author: Jaineesh
//Date: 20.01.2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------
function dateDivShow()
{

  if(document.getElementById('dashBoardCheck').checked){
      document.getElementById('dateDiv').style.display='block';
      document.getElementById('startDate').focus();
  }
 else{
     document.getElementById('dateDiv').style.display='none';
 }

}


//---------------------------------------------------------------------------------
//purspose:to show sms div  when msgmedium is sms
//Author: Jaineesh
//Date: 20.01.2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------
function smsDivShow()
{
  if(document.getElementById('smsCheck').checked){
      document.getElementById('smsDiv').style.display='block';
  }
 else{
     document.getElementById('smsDiv').style.display='none';
 }
}

function subjectDivShow(){
}


//----------------------------------------------------------------------------------------------------------------
//Pupose:Calculates  sms chars and no of smses
//Author: Dipanjan Bhattacharjee
//Date : 5.08.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function smsCalculation(value,limit,target){

 var temp1=value;
 var nos=1;    //no of sms limit://length of a sms
 if(tinyMCE.get('elm1').getContent()!=""){
  document.getElementById('sms_char').value=(parseInt(temp1.length)+1-3);
 }
 else{
  document.getElementById('sms_char').value=0;
 }
 while(temp1.length > (limit+2)){
     temp1=temp1.substr(limit);
     nos=nos+1;
 }
document.getElementById(target).value=nos;
}


//----------------------------------------------------------------------------------------------------------------
//Pupose:To reset form after data submission
//Author: Jaineesh
//Date : 20.01.2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function resetForm(){
 document.getElementById('divButton').style.display='none';
 document.getElementById('results').innerHTML="";
 hide_div('showList',2);

}

function toggleAttendanceDateDisply(val){
    document.getElementById('timeTableLabelId').selectedIndex=0;
    document.getElementById('classId').options.length=1;
    document.getElementById('groupId').options.length=1;
    document.getElementById('testId').options.length=1;
    document.getElementById('testId').disabled=true;

    if(val==1){
        document.getElementById('uptoTd1').style.display='';
        document.getElementById('uptoTd2').style.display='';
        document.getElementById('uptoTd3').style.display='';
        document.getElementById('uptoTd4').style.display='';
        document.getElementById('uptoTd5').style.display='';
        document.getElementById('uptoTd6').style.display='';
        document.getElementById('testId').disabled=true;
    }
    else{
        document.getElementById('uptoTd1').style.display='none';
        document.getElementById('uptoTd2').style.display='none';
        document.getElementById('uptoTd3').style.display='none';
        document.getElementById('uptoTd4').style.display='none';
        document.getElementById('uptoTd5').style.display='none';
        document.getElementById('uptoTd6').style.display='none';
        if(val==2){
        document.getElementById('testId').disabled=false;
        }
    }
   document.getElementById('divButton').style.display='none';
   document.getElementById('results').innerHTML="";
   hide_div('showList',2);
}


function getClasses(val){
    var cls=document.getElementById('classId');
    cls.options.length=1;
    document.getElementById('groupId').options.length=1;
    document.getElementById('testId').options.length=1;
    hide_div('showList',2);
    if(val==''){
        return false;
    }

    var url ='<?php echo HTTP_LIB_PATH;?>/AdminMessage/ajaxGetClasses.php';

    new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId: val
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    var j = eval('('+transport.responseText+')');
                    var len=j.length;
                    for(var c=0;c<len;c++){
                         var objOption = new Option(j[c].className,j[c].classId);
                         cls.options.add(objOption);
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}


function getGroups(val1,val2){
    var grp=document.getElementById('groupId')
    grp.options.length=1;
    document.getElementById('testId').options.length=1;
    hide_div('showList',2);
    if(val1=='' || val2==''){
        return false;
    }

    var url ='<?php echo HTTP_LIB_PATH;?>/AdminMessage/ajaxGetGroups.php';

    new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId : val1,
                 classId          : val2
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    var j = eval('('+transport.responseText+')');
                    var len=j.length;
                    for(var c=0;c<len;c++){
                         var objOption = new Option(j[c].groupName,j[c].groupId);
                         grp.options.add(objOption);
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

function getTests(val1,val2,val3){
    var test=document.getElementById('testId');
    test.options.length=1;
    hide_div('showList',2);
    if(val1=='' || val2=='' || val3==''){
        return false;
    }

    if(document.getElementById('msgType').value!=2){
        return false;
    }
    var url ='<?php echo HTTP_LIB_PATH;?>/AdminMessage/ajaxGetTests.php';

    new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId : val1,
                 classId          : val2,
                 groupId          : val3
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    var j = eval('('+transport.responseText+')');
                    var len=j.length;
                    for(var c=0;c<len;c++){
                         var objOption = new Option((j[c].testTypeName+'-'+j[c].testIndex),j[c].testId);
                         test.options.add(objOption);
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}





window.onload=function(){
  document.allDetailsForm.reset();
  document.getElementById('msgType').selectedIndex=0;
  document.getElementById('msgType').focus();
}

</script>

</head>
<body>
	<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/AdminMessage/listStudentPerformanceMessageContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>

</body>
</html>

<?php
// $History: listStudentPerformanceMessage.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 22/03/10   Time: 13:51
//Updated in $/LeapCC/Interface
//Modified search filter in "Send student performance message to parents"
//module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 20/03/10   Time: 17:45
//Updated in $/LeapCC/Interface
//Corrected title and breadcrumb of the page
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 20/03/10   Time: 17:35
//Created in $/LeapCC/Interface
//Created "Sent Student Information Message To Parents" module
?>