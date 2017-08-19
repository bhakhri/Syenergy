<?php
//-------------------------------------------------------
//  THIS FILE used to send email to students
//
// Author : Dipanjan Bhattacharjee
// Created on : (19.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Send Bulk SMS </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//echo UtilityManager::includeJS("tiny_mce/tiny_mce.js"); 
?> 
<script language="javascript">
/*    No Need to use tiny editor for sending SMSs
tinyMCE.init({
        mode : "textareas",
        theme : "advanced",


       // Theme options
       theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
       theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",

    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true
});

*/

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var SMSML=<?php echo SMS_MAX_LENGTH; ?>;

var tableHeadArray = new Array(
 new Array('srNo','#','width="3%"','',false),
 new Array('studentName','<input type=\"checkbox\" id=\"studentList\" name=\"studentList\" onclick=\"selectStudents();\">Student','width="15%"','align=\"left\"',true), 
 new Array('rollNo','R. No','width="8%"','',true) ,
 new Array('universityRollNo','Univ. R. No.','width="8%"','',true),
 new Array('fatherName','<input type=\"checkbox\" id=\"fatherList\" name=\"fatherList\" onclick=\"selectFathers();\">Father','width="10%"','align=\"left\"',true), 
 new Array('motherName','<input type=\"checkbox\" id=\"motherList\" name=\"motherList\" onclick=\"selectMothers();\">Mother','width="10%"','align=\"left\"',true), 
 new Array('guardianName','<input type=\"checkbox\" id=\"guardianList\" name=\"guardianList\" onclick=\"selectGuardians();\">Guardian','width="10%"','align=\"left\"',true)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE_TEACHER;?>;

linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxBulkSMSList.php';
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
//THIS FUNCTION IS USED TO select/deselect all student checkboxes
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function  selectStudents(){
    
    //state:checked/not checked
    var state=document.getElementById('studentList').checked;
    if(!chkObject('students')){
     document.listFrm.students.checked =state;
     return true;  
    }
    formx = document.listFrm; 
    var l=formx.students.length;
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        formx.students[ i ].checked=state;
    }
    
}


//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any student checkboxes selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
    
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        if(formx.students[ i ].checked==true){
            fl=1;
            break;
        }
    }
    
    return (fl);
    
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
// Created on : (8.7.2008)
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
// Created on : (8.7.2008)
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
       messageBox("<?php echo BULK_SMS_SELECT_STUDENT_LIST; ?>");
       document.getElementById('class').focus();
   } 
    
}




//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateForm() {
 if((document.listFrm.students.length - 2) == 0){
   messageBox("<?php echo NO_DATA_SUBMIT; ?>");
   return false;
 }
if(isEmpty(trim(document.getElementById('msgSubject').value))){
       messageBox("<?php echo EMPTY_SUBJECT; ?>");
       document.getElementById('msgSubject').focus();
       return false;
  }      
else if(isEmpty(trim(document.getElementById('elm1').value))){
       messageBox("<?php echo EMPTY_MSG_BODY; ?>");
       document.getElementById('elm1').focus();
       return false;
  }
else if(!(checkStudents()) && !(checkParents())){  //checkes whether any student/parent checkboxes selected or not
     messageBox("<?php echo SELECT_STUDENT_PARENT_SMS; ?>");
     document.getElementById('studentList').focus();
     return false;
 } 
  else{
     sendSMS(); //sends the message
     return false;
  }  
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO send SMS
//
//Author : Dipanjan Bhattacharjee
// Created on : (19.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function sendSMS() {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxSendBulkSMS.php';

         //determines which student are selected and their studentIds
         formx = document.listFrm; 
         var father="";  //get studentIds when father checkboxes are selected
         var mother="";  //get studentIds when mother checkboxes are selected
         var guardian="";  //get studentIds when guardian checkboxes are selected
         var student="";  //get studentIds when student checkboxes are selected
         
         if((document.listFrm.students.length - 2)<=1){
           student=(document.listFrm.students[2].checked ? document.listFrm.students[2].value : "0" );   
           father=(document.listFrm.fathers.checked ? document.listFrm.fathers.value : "0" );   
           mother=(document.listFrm.mothers.checked ? document.listFrm.mothers.value : "0" );   
           guardian=(document.listFrm.guardians.checked ? document.listFrm.guardians.value : "0" );   
         }
        else{ 
         var m=formx.students.length;
         for(var k=2 ; k < m ; k++){  //started from 2 for two dummy fields.
            if(formx.students[ k ].checked==true){
                if(student==""){
                    student= formx.students[ k ].value;
                }
               else{
                    student+="," + formx.students[ k ].value; 
               } 
            }
         }
         
         var n=formx.fathers.length;
         for(k=0 ; k < n ; k++){ //started from 2 for two dummy fields.
            if(formx.fathers[ k ].checked==true){
                if(father==""){
                    father= formx.fathers[ k ].value;
                }
               else{
                    father+="," + formx.fathers[ k ].value; 
               } 
            }
         }
         
         for(k=0 ; k < n ; k++){ //started from 2 for two dummy fields.
            if(formx.mothers[ k ].checked==true){
                if(mother==""){
                    mother= formx.mothers[ k ].value;
                }
               else{
                    mother+="," + formx.mothers[ k ].value; 
               } 
            }
         }
         
         for(k=0 ; k < n ; k++){ //started from 2 for two dummy fields.
            if(formx.guardians[ k ].checked==true){
                if(guardian==""){
                    guardian= formx.guardians[ k ].value;
                }
               else{
                    guardian+="," + formx.guardians[ k ].value; 
               } 
            }
         }
        }
        
        //alert("S="+student);
        //alert("F="+father);
        //alert("M="+mother);
        //alert("G="+guardian);


         new Ajax.Request(url,
           {
             method:'post',
             parameters: {msgBody: (trim(document.getElementById('elm1').value)),
             msgSubject: (trim(document.getElementById('msgSubject').value)),
             father: (father),
             mother: (mother),
             guardian: (guardian),
             student: (student),
             nos:(trim(document.getElementById('sms_no').value))
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                         messageBox("<?php echo MSG_SENT_OK; ?>");
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
                     resetForm();
              },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//----------------------------------------------------------------------------------------------------------------
//Pupose:Delete rollNo from studentRollNo field upon changing class,subject or group
//Author: Dipanjan Bhattacharjee
//Date : 19.07.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function deleteRollNo(){
    document.getElementById('studentRollNo').value="";
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
 if(document.getElementById('elm1').value!=""){
  document.getElementById('sms_char').value=(parseInt(temp1.length));
 } 
 else{
  document.getElementById('sms_char').value=0;   
 } 
 while(temp1.length > (limit)){
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
 //document.getElementById('class').selectedIndex=0;   
 //document.getElementById('subject').selectedIndex=0;
 //document.getElementById('group').selectedIndex=0;
 //document.getElementById('studentRollNo').selectedIndex=0;
 //document.getElementById('elm1').value="";
 //document.getElementById('sms_no').value=1;
 document.getElementById('divButton').style.display='none';
 document.getElementById('results').innerHTML="";
 //document.getElementById('msgSubject').value="";
 document.getElementById('msgSubject').focus();         
 hide_div('showList',2);     
 
}


window.onload=function(){
 document.getElementById('msgSubject').focus();         
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listBulkSMSContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
// $History: listBulkSMS.php $ 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Teacher
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 9/01/08    Time: 5:36p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 8/28/08    Time: 7:48p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 8/22/08    Time: 3:27p
//Updated in $/Leap/Source/Interface/Teacher
//Added Standard Messages
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 8/19/08    Time: 4:43p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/18/08    Time: 12:06p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/12/08    Time: 5:29p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/09/08    Time: 5:42p
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