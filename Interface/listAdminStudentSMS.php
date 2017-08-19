<?php
//---------------------------------------------------------------------------
//  THIS FILE used for sending message(sms/email/dashboard) to students
//
// Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Send SMS to Students</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
var SMSML=<?php echo SMS_MAX_LENGTH; ?>;

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
 new Array('srNo','#','width="3%"','',false),
 new Array('students','<input type=\"checkbox\" id=\"studentList\" name=\"studentList\" onclick=\"selectStudents();\">','width="3%"','align=\"left\"',false), 
 new Array('studentName','Name','width="20%"','',true),
 new Array('rollNo','R. No','width="15%"','',true) ,
 new Array('degreeAbbr','Degree','width="8%"','',true) ,
 new Array('branchCode','Branch','width="8%"','',true) ,
 new Array('periodName','Study Period','width="8%"','',true) 
 );

recordsPerPage = <?php echo RECORDS_PER_PAGE_ADMIN_MESSAGE ;?>;

linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/AdminMessage/ajaxAdminStudentMessageList.php';
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
// Created on : (21.07.2008)
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
// Created on : (21.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------

function getData(){
    if(trim(document.getElementById('studentRollNo').value)!="")
    {
        sendReq(listURL,divResultName,searchFormName,'',false);
        hide_div('showList',1);
        document.getElementById('divButton').style.display='block';
    }
   else if((document.getElementById('class').value != "") ){
        sendReq(listURL,divResultName,searchFormName,'',false);
        hide_div('showList',1);
        document.getElementById('divButton').style.display='block';
    }
   else{
       messageBox("<?php echo SELECT_STUDENT_SELECT_ALERT; ?>");    
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateForm() {

if((document.listFrm.students.length - 2) == 0){
   messageBox("<?php echo NO_DATA_SUBMIT; ?>");        
   return false;
 }     

/*if(document.getElementById('emailCheck').checked && (trim(document.getElementById('msgSubject').value)=="") ){  
      messageBox("Subject can not be empty for sending E-mail");    
      document.getElementById('msgSubject').focus();
      return false;
 }
*/ 
if(trim(document.getElementById('msgSubject').value)=="") {  
      messageBox("<?php echo EMPTY_SUBJECT; ?>");  
      document.getElementById('msgSubject').focus();
      return false;
 } 
else if(isEmpty(document.getElementById('elm1').value))   
    {
        messageBox("<?php echo EMPTY_MSG_BODY; ?>");   
        document.getElementById('elm1').focus();
        return false;
    }

/*
else if(!(document.getElementById('smsCheck').checked) && !(document.getElementById('emailCheck').checked)  ) {

       alert("Please Select a Message Medium");
       document.getElementById('smsCheck').focus(); 
       return false;
    }
*/    
/*
else if(document.getElementById('dashBoardCheck').checked && !dateDifference(document.getElementById('startDate').value,document.getElementById('endDate').value,"-")){  
      messageBox("'Visible To' Date can not be smaller than 'Visible From' Date");    
      document.getElementById('startDate').focus();
      return false;
 }
*/  
else if(!(checkStudents())){  //checkes whether any student/parent checkboxes selected or not
     alert("<?php echo STUDENT_SELECT_ALERT; ?>");    
     document.getElementById('studentList').focus();
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function sendMessage() {
         url = '<?php echo HTTP_LIB_PATH;?>/AdminMessage/ajaxAdminSendStudentMessage.php';
         
         
         //determines which student and parents are selected and their studentIds
         formx = document.listFrm; 
         var student="";  //get studentIds when student checkboxes are selected
         
        if((document.listFrm.students.length - 2)<=1){
           student=(document.listFrm.students[2].checked ? document.listFrm.students[2].value : "0" );   
         }
        else{ 
         var m=formx.students.length;
         for(var k=2 ; k < m ; k++){ //started from 2 for two dummy fields.
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
         //determines message medium
         //var msgMedium=((document.getElementById('smsCheck').checked) ? document.getElementById('smsCheck').value: 0)+","+((document.getElementById('emailCheck').checked) ? document.getElementById('emailCheck').value: 0)+","+((document.getElementById('dashBoardCheck').checked) ? document.getElementById('dashBoardCheck').value: 0) ;
         
         //var msgMedium=((document.getElementById('smsCheck').checked) ? document.getElementById('smsCheck').value: 0)+","+((document.getElementById('emailCheck').checked) ? document.getElementById('emailCheck').value: 0) ;

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {msgBody: (trim(document.getElementById('elm1').value)),   
             student: (student),
             msgMedium: ("1,0"),
             msgSubject:(trim(document.getElementById('msgSubject').value)),
             //visibleFrom:(document.getElementById('startDate').value),
             //visibleTo:(document.getElementById('endDate').value),
             nos:(trim(document.getElementById('sms_no').value)) 
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                        messageBox("<?php echo MSG_SENT_OK; ?>");      
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
                     resetForm(); //it is not called because there is paging
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//---------------------------------------------------------------------------------
//purspose:to show date options when msgmedium is dashboard
//Author: Dipanjan Bhattacharjee
//Date: 21.07.2008
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
//purspose:to show subject options when msgmedium is email
//Author: Dipanjan Bhattacharjee
//Date: 21.07.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.  
//
//---------------------------------------------------------------------------------
function subjectDivShow()
{
  if(document.getElementById('emailCheck').checked){
      document.getElementById('subjectDiv').style.display='block';
      document.getElementById('msgSubject').focus();
  }
 else{
     document.getElementById('subjectDiv').style.display='none';
 }   
}


//---------------------------------------------------------------------------------
//purspose:to show sms div  when msgmedium is sms
//Author: Dipanjan Bhattacharjee
//Date: 5.08.2008
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


//Pupose:Delete rollNo from studentRollNo field upon changing class,subject or group
//Author: Dipanjan Bhattacharjee
//Date : 19.07.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function deleteRollNo(){
    document.getElementById('studentRollNo').value="";
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function resetForm(){
 //document.getElementById('class').selectedIndex=0;   
 //document.getElementById('studentRollNo').selectedIndex=0;
 //tinyMCE.get('elm1').setContent("");
 //document.getElementById('sms_no').value=1;
 document.getElementById('divButton').style.display='none';
 document.getElementById('results').innerHTML="";
 //document.getElementById('dashBoardCheck').checked=false;
 //document.getElementById('emailCheck').checked =false;
 //document.getElementById('smsCheck').checked=false;
 //document.getElementById('dateDiv').style.display='none';
 //document.getElementById('subjectDiv').style.display='none';  
 //document.getElementById('smsDiv').style.display='none';
 
  document.getElementById('msgSubject').focus(); 
 
 //document.getElementById('elm1').focus();
}

window.onload=function(){
 document.getElementById('msgSubject').focus();   
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/AdminMessage/listAdminStudentSMSContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>

</body>
</html>

<?php                              
// $History: listAdminStudentSMS.php $ 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/01/08    Time: 6:42p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/28/08    Time: 5:44p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/21/08    Time: 3:51p
//Updated in $/Leap/Source/Interface
//Added Standard Messages
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/18/08    Time: 11:21a
//Updated in $/Leap/Source/Interface
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/11/08    Time: 4:25p
//Created in $/Leap/Source/Interface
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/11/08    Time: 3:05p
//Created in $/Leap/Source/Interface
?>