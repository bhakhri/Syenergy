<?php
//-------------------------------------------------------
//  THIS FILE used to send email to students
//
// Author : Dipanjan Bhattacharjee
// Created on : (19.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
<title><?php echo SITE_NAME;?>: Send Email </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeJS("tiny_mce/tiny_mce.js"); 
?> 
<script language="javascript">
tinyMCE.init({
        mode : "textareas",
        theme : "advanced",


       // Theme options
       theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|formatselect,fontselect,fontsizeselect",
       theme_advanced_buttons2 : "search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code|,forecolor,backcolor",

    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left"
    //theme_advanced_statusbar_location : "bottom",
    //theme_advanced_resizing : true
});



// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
 new Array('srNo','#','width="3%"','',false),
 new Array('students','<input type=\"checkbox\" id=\"studentList\" name=\"studentList\" onclick=\"selectStudents();\">','width="3%"','align=\"left\"',false), 
 new Array('studentName','Name','width="25%"','',true) ,
 new Array('rollNo','R. No','width="15%"','',true) ,
 new Array('universityRollNo','Univ. R. No.','width="15%"','',true)
 );

recordsPerPage = <?php echo RECORDS_PER_PAGE_TEACHER;?>;

linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxStudentEmailList.php';
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


//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all student checkboxes
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
    
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        if(formx.students[ i ].checked==true){
            fl=1;
            break;
        }
    }
    
    return (fl);
    
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO hide "showList" div
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
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
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------

function getData(){
    if(trim(document.getElementById('studentRollNo').value)!="")
    {
         sendReq(listURL,divResultName,searchFormName,'',false);  
         hide_div('showList',1); 
    }
   else if((document.getElementById('class').value != "") && (document.getElementById('subject').value != "") && (document.getElementById('group').value != "") ){
        sendReq(listURL,divResultName,searchFormName,'',false);
        hide_div('showList',1); 
    }
   else{
       messageBox("<?php echo STUDENT_EMAIL_SELECT_STUDENT_LIST; ?>");
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateForm() {
 if((document.listFrm.students.length - 2) == 0){
   messageBox("<?php echo NO_DATA_SUBMIT; ?>");
   return false;
 }   
    
 if(isEmpty(trim(document.getElementById('msgSubject').value)))
    {
        messageBox("<?php echo EMPTY_SUBJECT; ?>");
        document.getElementById('msgSubject').focus();
        return false;
    }
 else if(isEmpty(tinyMCE.get('elm1').getContent()))
    {
        messageBox("<?php echo EMPTY_MSG_BODY; ?>");
        document.getElementById('elm1').focus();
        return false;
    }
  else if(!(checkStudents())){  //checkes whether any student/parent checkboxes selected or not
     messageBox("<?php echo SELECT_STUDENT_EMAIL; ?>");
     document.getElementById('studentList').focus();
     return false;
  } 
  else{
     sendEmail(); //sends the message
     return false;
  }  
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO send Email
//
//Author : Dipanjan Bhattacharjee
// Created on : (19.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function sendEmail() {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxSendStudentEmail.php';

         //determines which student are selected and their studentIds
         formx = document.listFrm; 
         var student="";  //get studentIds when student checkboxes are selected
         
        if((document.listFrm.students.length - 2)<=1){
           student=(document.listFrm.students[2].checked ? document.listFrm.students[2].value : "0" );   
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
       }
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {msgBody: (tinyMCE.get('elm1').getContent()),
             msgSubject : (document.getElementById('msgSubject').value), 
             student: (student)
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


//Pupose:Delete rollNo from studentRollNo field upon changing class,subject or group
//Author: Dipanjan Bhattacharjee
//Date : 19.07.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function deleteRollNo(){
    document.getElementById('studentRollNo').value="";
}



function resetForm(){
 //document.getElementById('msgSubject').focus();
 //document.getElementById('msgSubject').value=""; 
 //tinyMCE.get('elm1').setContent("");
 //document.getElementById('class').selectedIndex=0;
 //document.getElementById('subject').selectedIndex=0;
 //document.getElementById('group').selectedIndex=0;
 //document.getElementById('studentRollNo').value="";
 document.getElementById('msgSubject').focus();
 hide_div('showList',2);  //hide the main div  
}

window.onload=function(){
  document.getElementById('msgSubject').focus();  
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listStudentEmailContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
// $History: listStudentEmail.php $ 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Teacher
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 9/01/08    Time: 5:36p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 8/22/08    Time: 3:27p
//Updated in $/Leap/Source/Interface/Teacher
//Added Standard Messages
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 8/18/08    Time: 12:06p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/12/08    Time: 5:29p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/11/08    Time: 5:38p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/09/08    Time: 2:59p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/09/08    Time: 1:39p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:04p
//Updated in $/Leap/Source/Interface/Teacher
//Added onCreate() function in ajax code
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 7/25/08    Time: 6:37p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/25/08    Time: 11:52a
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/24/08    Time: 7:57p
//Updated in $/Leap/Source/Interface/Teacher
//Changed header.php and footer.php paths to the original paths
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/21/08    Time: 6:52p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/19/08    Time: 6:26p
//Updated in $/Leap/Source/Interface/Teacher
?>