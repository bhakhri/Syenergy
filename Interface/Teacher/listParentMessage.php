<?php
//---------------------------------------------------------------------------
//  THIS FILE used for sending message(sms/email/dashboard) to parents
//
// Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SendMessageToParents');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Send Message </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeJS("tiny_mce/tiny_mce.js"); 
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?>

<script language="javascript">
var SMSML=<?php echo SMS_MAX_LENGTH; ?>;
tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        plugins : "paste",
        theme_advanced_buttons3_add : "pastetext,pasteword,selectall",
        paste_auto_cleanup_on_paste : true,
        paste_preprocess : function(pl, o) {
            // Content string containing the HTML from the clipboard
            //alert(o.content);
        },
        paste_postprocess : function(pl, o) {
            // Content DOM node containing the DOM structure of the clipboard
            //alert(o.node.innerHTML);
        },
        setup : function(ed) {
        ed.onKeyUp.add(function(ed, e) {
          smsCalculation("'"+removeHTMLTags(tinyMCE.get('elm1').getContent())+"'",SMSML,'sms_no');
         }
        );
      },

       // Theme options
       theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|formatselect,fontselect,fontsizeselect",
       theme_advanced_buttons2 : "bullist,numlist,|outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,cleanup,code,|,forecolor,backcolor",

    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left"
    //theme_advanced_statusbar_location : "bottom",
    //theme_advanced_resizing : true
});



// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
 new Array('srNo','#','width="3%"','',false),
 new Array('studentName','Student','width="15%"','align="left" style="padding-left:5px"',true) ,
 new Array('rollNo','Roll No.','width="8%"','',true) ,
 new Array('universityRollNo','Univ. Roll No.','width="10%"','',true),
 new Array('fatherName','<input type=\"checkbox\" id=\"fatherList\" name=\"fatherList\" onclick=\"selectFathers();\">Father','width="10%"','align=\"left\"',true), 
 new Array('motherName','<input type=\"checkbox\" id=\"motherList\" name=\"motherList\" onclick=\"selectMothers();\">Mother','width="10%"','align=\"left\"',true), 
 new Array('guardianName','<input type=\"checkbox\" id=\"guardianList\" name=\"guardianList\" onclick=\"selectGuardians();\">Guardian','width="10%"','align=\"left\"',true)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE_TEACHER;?>;

linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxParentMessageList.php';
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


//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether a control is object or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (24.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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


//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all fathers checkboxes
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
//THIS FUNCTION IS USED TO select/deselect all mothers checkboxes
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
//THIS FUNCTION IS USED TO check whether any father/mother/guardian checkboxes selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
function checkParents(){
  if(checkFathers() || checkMothers() || checkGuardians()){
      return 1;
  }
  else{
      return 0;
  }   
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO hide "showList" div
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------

function getData(){
    if(trim(document.getElementById('studentRollNo').value)!="")
    {
         sendReq(listURL,divResultName,searchFormName,'',false);  
         hide_div('showList',1);
         document.getElementById('divButton').style.display='block'; 
    }
   else if((document.getElementById('class').value != "") && (document.getElementById('subject').value != "") && (document.getElementById('group').value != "") ){
        sendReq(listURL,divResultName,searchFormName,'',false);  
        hide_div('showList',1);
        document.getElementById('divButton').style.display='block'; 
    }
   else{
       messageBox("<?php echo PARENT_MSG_SELECT_STUDENT_LIST; ?>");
       document.getElementById('class').focus();
   } 
    
}




//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateForm() {

if((document.listFrm.fathers.length - 2) == 0){
   messageBox("<?php echo NO_DATA_SUBMIT; ?>");
   return false;
 }     

if(trim(document.getElementById('msgSubject').value)=="") {  
      messageBox("<?php echo EMPTY_SUBJECT; ?>");
      document.getElementById('msgSubject').focus();
      return false;
 } 
else if(isEmpty(tinyMCE.get('elm1').getContent()))
    {
        messageBox("<?php echo EMPTY_MSG_BODY; ?>");
        tinyMCE.execInstanceCommand("elm1", "mceFocus"); 
        return false;
    }
else if(!(document.getElementById('smsCheck').checked) && !(document.getElementById('emailCheck').checked) && !(document.getElementById('dashBoardCheck').checked) ) {

       messageBox("<?php echo SELECT_MSG_MEDIUM; ?>"); 
       document.getElementById('dashBoardCheck').focus(); 
       return false;
    }
else if(document.getElementById('dashBoardCheck').checked && !dateDifference(document.getElementById('startDate').value,document.getElementById('endDate').value,"-")){  
      messageBox("<?php echo PARENT_MSG_DATE_VALIDATION; ?>");    
      document.getElementById('startDate').focus();
      return false;
 } 
else if(!(checkParents())){  //checkes whether any parent checkboxes selected or not
     alert("<?php echo SELECT_PARENT_MSG; ?>");
     document.getElementById('fatherList').focus();
     return false;
  } 
else{
     sendMessage(); //sends the message
     return false;
  }  
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO send message
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function sendMessage() {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxSendParentMessage.php';
         
         //determines which student and parents are selected and their studentIds
         formx = document.listFrm; 
         var father="";  //get studentIds when student checkboxes are selected
         var mother="";  //get studentIds when student checkboxes are selected
         var guardian="";  //get studentIds when student checkboxes are selected

         if((document.listFrm.fathers.length - 2)<=1){
           father=(document.listFrm.fathers[2].checked ? document.listFrm.fathers[2].value : "0" );   
           mother=(document.listFrm.mothers.checked ? document.listFrm.mothers.value : "0" );   
           guardian=(document.listFrm.guardians.checked ? document.listFrm.guardians.value : "0" );   
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
         
        }  //determines message medium
        
        //alert("F="+father);
        //alert("M="+mother);
        //alert("G="+guardian);

         var msgMedium=((document.getElementById('smsCheck').checked) ? document.getElementById('smsCheck').value: 0)+","+((document.getElementById('emailCheck').checked) ? document.getElementById('emailCheck').value: 0)+","+((document.getElementById('dashBoardCheck').checked) ? document.getElementById('dashBoardCheck').value: 0) ;

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {msgBody: (tinyMCE.get('elm1').getContent()), 
             father: (father),
             mother: (mother),
             guardian: (guardian),
             msgMedium: (msgMedium),
             msgSubject:(trim(document.getElementById('msgSubject').value)),
             visibleFrom:(document.getElementById('startDate').value),
             visibleTo:(document.getElementById('endDate').value),
             nos:(trim(document.getElementById('sms_no').value)) 
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
                       flag = true;
                       if(ret[1]!=''){
                         eStr ='\nSMS not sent to these parents :\n'+ret[1];  
                         fl = 1;
                       }
                       else {
                          ret[1]=-1; 
                       }
                       if(ret[2]!=''){
                         eStr +='\nEmail not sent to these parents :\n'+ret[2];  
                         fl = 1;
                       }
                       else {
                          ret[2]=-1; 
                       }
                       if(ret[3]!=''){
                         eStr +='\nEmail not sent to these parents :\n'+ret[3];  
                         fl = 1;
                       }
                       else {
                          ret[3]=-1; 
                       }
                       if(fl==1){
                         if(confirm("<?php echo MESSAGE_NOT_SEND; ?>")){  
                           window.location = "<?php echo UI_HTTP_PATH ?>/Teacher/detailsTeacherMessageDocument.php?type=p&emailFatherIds="+ret[1]+"&emailMotherIds="+ret[2]+"&emailGuardianIds="+ret[3];
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
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//---------------------------------------------------------------------------------
//purspose:to show date options when msgmedium is dashboard
//Author: Dipanjan Bhattacharjee
//Date: 21.07.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.  
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
//purspose:to show subject options when msgmedium is email
//Author: Dipanjan Bhattacharjee
//Date: 21.07.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.  
//
//---------------------------------------------------------------------------------
function subjectDivShow()
{
 /*   
  if(document.getElementById('emailCheck').checked){
      document.getElementById('subjectDiv').style.display='block';
      document.getElementById('msgSubject').focus();
  }
 else{
     document.getElementById('subjectDiv').style.display='none';
 } 
 */  
}


//---------------------------------------------------------------------------------
//purspose:to show sms div  when msgmedium is sms
//Author: Dipanjan Bhattacharjee
//Date: 5.08.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.  
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

//Pupose:Delete rollNo from studentRollNo field upon changing class,subject or group
//Author: Dipanjan Bhattacharjee
//Date : 19.07.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function deleteRollNo(){
    document.getElementById('studentRollNo').value="";
    document.getElementById('results').innerHTML='';
    document.getElementById('divButton').style.display='none';
}

//----------------------------------------------------------------------------------------------------------------
//Pupose:Calculates  sms chars and no of smses
//Author: Dipanjan Bhattacharjee
//Date : 5.08.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
//Author: Dipanjan Bhattacharjee
//Date : 5.08.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function resetForm(){
 //document.getElementById('studentRollNo').value="";   
 //document.getElementById('class').selectedIndex=0;   
 //document.getElementById('subject').selectedIndex=0;
 //document.getElementById('group').selectedIndex=0;
 //document.getElementById('studentRollNo').value="";
 //tinyMCE.get('elm1').setContent("");
 //document.getElementById('sms_no').value=1;
 document.getElementById('divButton').style.display='none';
 document.getElementById('results').innerHTML="";
// document.getElementById('dashBoardCheck').checked=false;
// document.getElementById('emailCheck').checked =false;
// document.getElementById('smsCheck').checked=false;
// document.getElementById('dateDiv').style.display='none';
 //document.getElementById('subjectDiv').style.display='none';  
 //document.getElementById('smsDiv').style.display='none';
 //document.getElementById('msgSubject').value="";
 document.getElementById('msgSubject').focus();
 
 //document.getElementById('elm1').focus();
}


function populateSubjects(classId){
    document.getElementById('subject').options.length=1;
    document.getElementById('group').options.length=1;
    
    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetSubjects.php';
    
    if(classId==''){
      return false;
    }
    
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

function groupPopulate(value) {
   url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGroupPopulate.php';
   document.searchForm.group.options.length=0;
   var objOption = new Option("Select Group","");
   document.searchForm.group.options.add(objOption); 
   
   if(document.getElementById('subject').value==""){
       return false;
   }
   if(document.getElementById('class').value==""){
       return false;
   }
   

 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 subjectId: document.getElementById('subject').value,
                 classId  : document.getElementById('class').value
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
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

window.onload=function(){
   document.getElementById('msgSubject').focus();
   var roll = document.getElementById("studentRollNo");
   autoSuggest(roll);
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listParentMessageContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
// $History: listParentMessage.php $ 
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 29/01/10   Time: 15:56
//Updated in $/LeapCC/Interface/Teacher
//Added checks---Subjects will be fetched based on selected class and
//groups will be fetched based on selected class and subject in 
//Send Message to Student and Parent modules
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 28/01/10   Time: 11:31
//Updated in $/LeapCC/Interface/Teacher
//Added "Univ. Roll No." column in student list display
//
//*****************  Version 8  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface/Teacher
//added code for autosuggest functionality
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 3/09/09    Time: 17:47
//Updated in $/LeapCC/Interface/Teacher
//Corrected problem of tiny mce and word file copy-paste
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 19/08/09   Time: 15:28
//Updated in $/LeapCC/Interface/Teacher
//Done bug fixing
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Interface/Teacher
//Added Role Permission Variables
//
//*****************  Version 4  *****************
//User: Administrator Date: 1/07/09    Time: 18:28
//Updated in $/LeapCC/Interface/Teacher
//removed alert
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/08/09    Time: 2:57p
//Updated in $/LeapCC/Interface/Teacher
//notSendMessageDetail functionality added
//
//*****************  Version 2  *****************
//User: Administrator Date: 29/05/09   Time: 18:30
//Updated in $/LeapCC/Interface/Teacher
//Added "SMS" restriction codes
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Teacher
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 9/01/08    Time: 5:36p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 8/22/08    Time: 3:27p
//Updated in $/Leap/Source/Interface/Teacher
//Added Standard Messages
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/19/08    Time: 4:43p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/18/08    Time: 12:06p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/12/08    Time: 5:29p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/09/08    Time: 2:59p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/09/08    Time: 1:39p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/06/08    Time: 6:50p
//Updated in $/Leap/Source/Interface/Teacher
//Done modifications as discussed in the demo session
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:04p
//Updated in $/Leap/Source/Interface/Teacher
//Added onCreate() function in ajax code
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/25/08    Time: 6:37p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/25/08    Time: 11:52a
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/24/08    Time: 7:57p
//Updated in $/Leap/Source/Interface/Teacher
//Changed header.php and footer.php paths to the original paths
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/21/08    Time: 6:53p
//Created in $/Leap/Source/Interface/Teacher
?>