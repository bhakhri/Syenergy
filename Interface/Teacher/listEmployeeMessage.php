<?php
//---------------------------------------------------------------------------
//  THIS FILE used for sending message(sms/email/dashboard) to employees
//
// Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeMessageMaster');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Send Message to Colleagues</title>
<?php 
//include js files for expandable divs
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeJS("tiny_mce/tiny_mce.js"); 
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
       theme_advanced_buttons2 : "search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",

    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left"
    //theme_advanced_statusbar_location : "bottom",
    //theme_advanced_resizing : false
 });


        

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
 new Array('srNo','#','width="1%"','align="left"',false),
 new Array('emps','<input type=\"checkbox\" id=\"empList\" name=\"empList\" onclick=\"selectEmps();\">','width="2%"','align=\"left\"',false), 
 new Array('employeeName','Name','width="15%"','align="left"',true),
 new Array('employeeCode','Emp. Code','width="7%"','align="left"',true) ,
 new Array('designationName','Designation','width="10%"','align="left"',true),
 new Array('branchCode','Branch','width="7%"','align="left"',true),
 new Array('roleName','Role','width="7%"','align="left"',true),
 new Array('qualification','Qual.','width="6%"','align="left"',true),
 new Array('dateOfJoining','Date of Joining','width="10%" align="center"','align="center"',true)
 );

recordsPerPage = <?php echo RECORDS_PER_PAGE_ADMIN_MESSAGE ;?>;

linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxAdminEmployeeMessageList.php';
searchFormName = 'employeeDetailsForm'; // name of the form which will be used for search
/*
addFormName    = 'AddCity';   
editFormName   = 'EditCity';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteCity';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'employeeName';
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
function  selectEmps(){
    
    //state:checked/not checked
    var state=document.getElementById('empList').checked;
    if(!chkObject('emps')){
     document.listFrm.emps.checked =state;
     return true;  
    }
    formx = document.listFrm; 
    var l=formx.emps.length;
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        formx.emps[ i ].checked=state;
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
function checkEmps(){
    
    var fl=0; 
    if(!chkObject('emps')){
     if(document.listFrm.emps.checked==true){
         fl=1;
     }
     return fl;
   }
    formx = document.listFrm; 
    var l=formx.emps.length;
    
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        if(formx.emps[ i ].checked==true){
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



//This function Validates Form 
function validateEmployeeList(frm) {
    
    
    if(!isEmpty(document.employeeDetailsForm.birthYearF.value) || !isEmpty(document.employeeDetailsForm.birthMonthF.value) || !isEmpty(document.employeeDetailsForm.birthDateF.value)){
        
        if(isEmpty(document.employeeDetailsForm.birthYearF.value)){
           
           messageBox("Please select date of birth year");
           document.allDetailsdocument.employeeDetailsForm.birthYearF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.birthMonthF.value)){
           
           messageBox("Please select date of birth month");
           document.allDetailsdocument.employeeDetailsForm.birthMonthF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.birthDateF.value)){
           
           messageBox("Please select date of birth date");
           document.allDetailsdocument.employeeDetailsForm.birthDateF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.birthYearT.value) || !isEmpty(document.employeeDetailsForm.birthMonthT.value) || !isEmpty(document.employeeDetailsForm.birthDateT.value)){
        
        if(isEmpty(document.employeeDetailsForm.birthYearT.value)){
           
           messageBox("Please select date of birth year");
           document.allDetailsdocument.employeeDetailsForm.birthYearT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.birthMonthT.value)){
           
           messageBox("Please select date of birth month");
           document.allDetailsdocument.employeeDetailsForm.birthMonthT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.birthDateT.value)){
           
           messageBox("Please select date of birth date");
           document.allDetailsdocument.employeeDetailsForm.birthDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.employeeDetailsForm.birthYearF.value) && !isEmpty(document.employeeDetailsForm.birthMonthF.value) && !isEmpty(document.employeeDetailsForm.birthDateF.value) && !isEmpty(document.employeeDetailsForm.birthYearT.value) && !isEmpty(document.employeeDetailsForm.birthMonthT.value) && !isEmpty(document.employeeDetailsForm.birthDateT.value)){
    
        dobFValue = document.employeeDetailsForm.birthYearF.value+"-"+document.employeeDetailsForm.birthMonthF.value+"-"+document.employeeDetailsForm.birthDateF.value

        dobTValue = document.employeeDetailsForm.birthYearT.value+"-"+document.employeeDetailsForm.birthMonthT.value+"-"+document.employeeDetailsForm.birthDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of birth cannot be greater than To Date of birth");
           document.allDetailsdocument.employeeDetailsForm.birthYearF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.birthYearF.value) && !isEmpty(document.employeeDetailsForm.birthMonthF.value) && !isEmpty(document.employeeDetailsForm.birthDateF.value) && !isEmpty(document.employeeDetailsForm.birthYearT.value) && !isEmpty(document.employeeDetailsForm.birthMonthT.value) && !isEmpty(document.employeeDetailsForm.birthDateT.value)){
    
        dobFValue = document.employeeDetailsForm.birthYearF.value+"-"+document.employeeDetailsForm.birthMonthF.value+"-"+document.employeeDetailsForm.birthDateF.value

        dobTValue = document.employeeDetailsForm.birthYearT.value+"-"+document.employeeDetailsForm.birthMonthT.value+"-"+document.employeeDetailsForm.birthDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of birth cannot be greater than To Date of birth");
           document.allDetailsdocument.employeeDetailsForm.birthYearF.focus();
           return false;
        }
    }

    // Joining Date                                
    if(!isEmpty(document.employeeDetailsForm.joiningYearF.value) || !isEmpty(document.employeeDetailsForm.joiningMonthF.value) || !isEmpty(document.employeeDetailsForm.joiningDateF.value)){
        
        if(isEmpty(document.employeeDetailsForm.joiningYearF.value)){
           
           messageBox("Please select date of joining year");
           document.allDetailsdocument.employeeDetailsForm.joiningYearF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.joiningMonthF.value)){
           
           messageBox("Please select date of joining month");
           document.allDetailsdocument.employeeDetailsForm.joiningMonthF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.joiningDateF.value)){
           
           messageBox("Please select date of joining date");
           document.allDetailsdocument.employeeDetailsForm.joiningDateF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.joiningYearT.value) || !isEmpty(document.employeeDetailsForm.joiningMonthT.value) || !isEmpty(document.employeeDetailsForm.joiningDateT.value)){
        
        if(isEmpty(document.employeeDetailsForm.joiningYearT.value)){
           
           messageBox("Please select date of joining year");
           document.allDetailsdocument.employeeDetailsForm.joiningYearT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.joiningMonthT.value)){
           
           messageBox("Please select date of joining month");
           document.allDetailsdocument.employeeDetailsForm.joiningMonthT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.joiningDateT.value)){
           
           messageBox("Please select date of joining date");
           document.allDetailsdocument.employeeDetailsForm.joiningDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.employeeDetailsForm.joiningYearF.value) && !isEmpty(document.employeeDetailsForm.joiningMonthF.value) && !isEmpty(document.employeeDetailsForm.joiningDateF.value) && !isEmpty(document.employeeDetailsForm.joiningYearT.value) && !isEmpty(document.employeeDetailsForm.joiningMonthT.value) && !isEmpty(document.employeeDetailsForm.joiningDateT.value)){
    
        dobFValue = document.employeeDetailsForm.joiningYearF.value+"-"+document.employeeDetailsForm.joiningMonthF.value+"-"+document.employeeDetailsForm.joiningDateF.value

        dobTValue = document.employeeDetailsForm.joiningYearT.value+"-"+document.employeeDetailsForm.joiningMonthT.value+"-"+document.employeeDetailsForm.joiningDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of joining cannot be greater than To Date of joining");
           document.allDetailsdocument.employeeDetailsForm.joiningYearF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.joiningYearF.value) && !isEmpty(document.employeeDetailsForm.joiningMonthF.value) && !isEmpty(document.employeeDetailsForm.joiningDateF.value) && !isEmpty(document.employeeDetailsForm.joiningYearT.value) && !isEmpty(document.employeeDetailsForm.joiningMonthT.value) && !isEmpty(document.employeeDetailsForm.joiningDateT.value)){
    
        dobFValue = document.employeeDetailsForm.joiningYearF.value+"-"+document.employeeDetailsForm.joiningMonthF.value+"-"+document.employeeDetailsForm.joiningDateF.value


        dobTValue = document.employeeDetailsForm.joiningYearT.value+"-"+document.employeeDetailsForm.joiningMonthT.value+"-"+document.employeeDetailsForm.joiningDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of joining cannot be greater than To Date of joining");
           document.allDetailsdocument.employeeDetailsForm.joiningYearF.focus();
           return false;
        }
    }
    
    // Leaving Date                                
    if(!isEmpty(document.employeeDetailsForm.leavingYearF.value) || !isEmpty(document.employeeDetailsForm.leavingMonthF.value) || !isEmpty(document.employeeDetailsForm.leavingDateF.value)){
        
        if(isEmpty(document.employeeDetailsForm.leavingYearF.value)){
           
           messageBox("Please select date of leaving year");
           document.allDetailsdocument.employeeDetailsForm.leavingYearF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.leavingMonthF.value)){
           
           messageBox("Please select date of leaving month");
           document.allDetailsdocument.employeeDetailsForm.leavingMonthF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.leavingDateF.value)){
           
           messageBox("Please select date of leaving date");
           document.allDetailsdocument.employeeDetailsForm.leavingDateF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.leavingYearT.value) || !isEmpty(document.employeeDetailsForm.leavingMonthT.value) || !isEmpty(document.employeeDetailsForm.leavingDateT.value)){
        
        if(isEmpty(document.employeeDetailsForm.leavingYearT.value)){
           
           messageBox("Please select date of leaving year");
           document.allDetailsdocument.employeeDetailsForm.leavingYearT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.leavingMonthT.value)){
           
           messageBox("Please select date of leaving month");
           document.allDetailsdocument.employeeDetailsForm.leavingMonthT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.leavingDateT.value)){
           
           messageBox("Please select date of leaving date");
           document.allDetailsdocument.employeeDetailsForm.leavingDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.employeeDetailsForm.leavingYearF.value) && !isEmpty(document.employeeDetailsForm.leavingMonthF.value) && !isEmpty(document.employeeDetailsForm.leavingDateF.value) && !isEmpty(document.employeeDetailsForm.leavingYearT.value) && !isEmpty(document.employeeDetailsForm.leavingMonthT.value) && !isEmpty(document.employeeDetailsForm.leavingDateT.value)){
    
        dobFValue = document.employeeDetailsForm.leavingYearF.value+"-"+document.employeeDetailsForm.leavingMonthF.value+"-"+document.employeeDetailsForm.leavingDateF.value

        dobTValue = document.employeeDetailsForm.leavingYearT.value+"-"+document.employeeDetailsForm.leavingMonthT.value+"-"+document.employeeDetailsForm.leavingDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of leaving cannot be greater than To Date of leaving");
           document.allDetailsdocument.employeeDetailsForm.leavingYearF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.leavingYearF.value) && !isEmpty(document.employeeDetailsForm.leavingMonthF.value) && !isEmpty(document.employeeDetailsForm.leavingDateF.value) && !isEmpty(document.employeeDetailsForm.leavingYearT.value) && !isEmpty(document.employeeDetailsForm.leavingMonthT.value) && !isEmpty(document.employeeDetailsForm.leavingDateT.value)){
    
        dobFValue = document.employeeDetailsForm.leavingYearF.value+"-"+document.employeeDetailsForm.leavingMonthF.value+"-"+document.employeeDetailsForm.leavingDateF.value

        dobTValue = document.employeeDetailsForm.leavingYearT.value+"-"+document.employeeDetailsForm.leavingMonthT.value+"-"+document.employeeDetailsForm.leavingDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of leaving cannot be greater than To Date of leaving");
           document.allDetailsdocument.employeeDetailsForm.leavingYearF.focus();
           return false;
        }
    }
    
    //showHide("hideAll");
    document.getElementById('academic1').style.display='none';
    document.getElementById('academic2').style.display='none';
    document.getElementById('address1').style.display='none';
    document.getElementById('misc1').style.display='none';
    document.getElementById('misc2').style.display='none';
    
    document.getElementById('academic').innerHTML='Expand';
    document.getElementById('address').innerHTML='Expand';
    document.getElementById('miscEmployee').innerHTML='Expand';
    
    sendReq(listURL,divResultName,searchFormName,'',false);
    hide_div('showList',1);
    document.getElementById('divButton').style.display='block';      
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

if((document.listFrm.emps.length - 2) == 0){
   messageBox("<?php echo NO_DATA_SUBMIT; ?>");
   return false;
 }     

/*
if(document.getElementById('emailCheck').checked && (trim(document.getElementById('msgSubject').value)=="") ){  
      messageBox("Subject can not be empty for sending E-mail");    
      document.getElementById('msgSubject').focus();
      return false;
 }
*/
if(trim(document.getElementById('msgSubject').value)==""){  
      messageBox("<?php echo EMPTY_SUBJECT; ?>");    
      document.getElementById('msgSubject').focus();
      return false;
 }  
else if(isEmpty(tinyMCE.get('elm1').getContent()))
    {
        messageBox("<?php echo EMPTY_MSG_BODY; ?>");
        try{ 
         tinyMCE.execInstanceCommand("elm1", "mceFocus");
        }
        catch(e){
        }
        return false;
    }
else if(!(document.getElementById('smsCheck').checked) && !(document.getElementById('emailCheck').checked) && !(document.getElementById('dashBoardCheck').checked)  ) {
       
       alert("<?php echo SELECT_MSG_MEDIUM ; ?>");
       document.getElementById('dashBoardCheck').focus(); 
       return false;
    }
else if(document.getElementById('dashBoardCheck').checked && !dateDifference(document.getElementById('startDate').value,document.getElementById('endDate').value,"-")){  
      messageBox("'Visible To' Date can not be smaller than 'Visible From' Date");    
      document.getElementById('startDate').focus();
      return false;
 }

else if(!(checkEmps())){  //checkes whether any student/parent checkboxes selected or not
     alert("<?php echo COLLEAGUE_EMPLOYEE_SELECT_ALERT; ?>");
     document.getElementById('empList').focus();
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
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxAdminSendEmployeeMessage.php';
         
         
         //determines which student and parents are selected and their studentIds
         formx = document.listFrm; 
         var emp="";  //get studentIds when student checkboxes are selected
         
        if((document.listFrm.emps.length - 2)<=1){
           emp=(document.listFrm.emps[2].checked ? document.listFrm.emps[2].value : "0" );   
         }
        else{ 
         var m=formx.emps.length;
         for(var k=2 ; k < m ; k++){ //started from 2 for two dummy fields.
            if(formx.emps[ k ].checked==true){
                if(emp==""){
                    emp= formx.emps[ k ].value;
                }
               else{
                    emp+="," + formx.emps[ k ].value; 
               } 
            }
         }
        }  
         //determines message medium
         //var msgMedium=((document.getElementById('smsCheck').checked) ? document.getElementById('smsCheck').value: 0)+","+((document.getElementById('emailCheck').checked) ? document.getElementById('emailCheck').value: 0)+","+((document.getElementById('dashBoardCheck').checked) ? document.getElementById('dashBoardCheck').value: 0) ;
         
         var msgMedium=((document.getElementById('smsCheck').checked) ? document.getElementById('smsCheck').value: 0)+","+((document.getElementById('emailCheck').checked) ? document.getElementById('emailCheck').value: 0)+","+((document.getElementById('dashBoardCheck').checked) ? document.getElementById('dashBoardCheck').value: 0) ;

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {msgBody: (tinyMCE.get('elm1').getContent()), 
             emp: (emp),
             msgMedium: (msgMedium),
             msgSubject:(trim(document.getElementById('msgSubject').value)),
             visibleFrom:(document.getElementById('startDate').value),
             visibleTo:(document.getElementById('endDate').value),
             nos:(trim(document.getElementById('sms_no').value)) 
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 var ret=trim(transport.responseText).split('!~!~!');
                 var eStr='';
                 fl = 0;
                 if("<?php echo SUCCESS;?>" == ret[0]) {                     
                   flag = true;
                   if(ret[1]!=''){
                     eStr +='\nEmail not sent to these employees :\n'+ret[1];  
                     fl = 1;
                   }
                   else {
                      ret[1]=-1; 
                   }
                   if(fl==1){
                     if(confirm("<?php echo MESSAGE_NOT_SEND; ?>")){  
                       window.location = "<?php echo UI_HTTP_PATH ?>/Teacher/detailsTeacherMessageDocument.php?type=e&emailEmployeeIds="+ret[1];
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
      //document.getElementById('dateDiv').style.display='block';
      document.getElementById('dateDiv').style.visibility='visible';
      document.getElementById('startDate').focus();
  }
 else{
     //document.getElementById('dateDiv').style.display='none';
     document.getElementById('dateDiv').style.visibility='hidden';
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


//----------------------------------------------------------------------------------------------------------------
//Pupose:Delete rollNo from Emp name field upon changing emp category
//Author: Dipanjan Bhattacharjee
//Date : 19.07.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function deleteName(){
    document.getElementById('empName').value="";
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
 //document.getElementById('class').selectedIndex=0;   
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
 //document.getElementById('msgSubject').value=""; 
 document.getElementById('msgSubject').focus(); 
 
 //document.getElementById('elm1').focus();
}


//********INITIALIZES EXPANDABLE DIVs***********
 //animatedcollapse.addDiv('general', 'fade=0,speed=400,group=employee')
 //animatedcollapse.addDiv('specific', 'fade=0,speed=400,group=employee,persist=1,hide=1')
 //animatedcollapse.init() ;
//********INITIALIZES EXPANDABLE DIVs***********

 
 
//Purpose:To move show list button from one div to another div
var dMode=1; //global variable used to show/hide div
function moveListButton(mode){
 if(mode==1){
     document.getElementById('toggleButton').src="<?php echo IMG_HTTP_PATH;?>/minus.gif";
     dMode=2;
     animatedcollapse.show('specific');
     document.getElementById('simageField1').style.display='none';
     document.getElementById('toggleButton').title="Collapse"
 }
 else{
     document.getElementById('toggleButton').src="<?php echo IMG_HTTP_PATH;?>/plus.gif";
     dMode=1;
     animatedcollapse.hide('specific');
     document.getElementById('simageField1').style.display='block'
     document.getElementById('toggleButton').title="Expand"
 }
} 

window.onload=function(){
 document.getElementById('msgSubject').focus();  
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listEmployeeMessageContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>

</body>
</html>

<?php                              
// $History: listEmployeeMessage.php $ 
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 3/09/09    Time: 17:47
//Updated in $/LeapCC/Interface/Teacher
//Corrected problem of tiny mce and word file copy-paste
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Interface/Teacher
//Added Role Permission Variables
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/08/09    Time: 2:57p
//Updated in $/LeapCC/Interface/Teacher
//notSendMessageDetail functionality added
//
//*****************  Version 3  *****************
//User: Administrator Date: 29/05/09   Time: 18:30
//Updated in $/LeapCC/Interface/Teacher
//Added "SMS" restriction codes
//
//*****************  Version 2  *****************
//User: Administrator Date: 14/05/09   Time: 16:38
//Updated in $/LeapCC/Interface/Teacher
//Corrected javascript code
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/03/09    Time: 18:49
//Created in $/LeapCC/Interface/Teacher
//Added the functioanality "Send Message to Colleagues" In Teacher
//Section
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/03/09    Time: 18:13
//Created in $/SnS/Interface/Teacher
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:08a
//Updated in $/SnS/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 20/01/09   Time: 12:11
//Created in $/SnS/Interface
//Added Sns System to VSS(Leap for Chitkara International School)
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 12/15/08   Time: 5:41p
//Updated in $/LeapCC/Interface
//added define('MANAGEMENT_ACCESS',1) Parameter
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/12/08   Time: 16:08
//Updated in $/LeapCC/Interface
//Added "Visible From" and "Visible To" fields
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/08/08    Time: 4:05p
//Updated in $/Leap/Source/Interface
//Updated according to Kabir Sir's suggestion
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/05/08    Time: 12:11p
//Updated in $/Leap/Source/Interface
//Added employee search filter
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/01/08    Time: 6:42p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/21/08    Time: 3:51p
//Updated in $/Leap/Source/Interface
//Added Standard Messages
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/18/08    Time: 11:21a
//Updated in $/Leap/Source/Interface
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/11/08    Time: 4:25p
//Updated in $/Leap/Source/Interface
?>