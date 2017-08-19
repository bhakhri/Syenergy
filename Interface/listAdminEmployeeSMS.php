<?php
//---------------------------------------------------------------------------
//  THIS FILE used for sending message(sms/email/dashboard) to employees
//
// Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
<title><?php echo SITE_NAME;?>: Send  SMS to Employees</title>
<?php 
//include js files for expandable divs
echo UtilityManager::includeJS("jquery-1.2.2.pack.js"); 
echo UtilityManager::includeJS("animatedcollapse.js");
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?> 
<script language="javascript">
var SMSML=<?php echo SMS_MAX_LENGTH; ?>;
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
 new Array('srNo','#','width="1%"','',false),
 new Array('emps','<input type=\"checkbox\" id=\"empList\" name=\"empList\" onclick=\"selectEmps();\">','width="2%"','align=\"left\"',false), 
 new Array('employeeName','Name','width="15%"','',true),
 new Array('employeeCode','Emp.Code','width="7%"','',true) ,
 new Array('designationName','Designation.','width="10%"','',true),
 new Array('branchCode','Branch','width="7%"','',true),
 new Array('roleName','Role','width="7%"','',true),
 new Array('qualification','Qual.','width="10%"','',true),
 new Array('dateOfJoining','DateofJoining','width="5%"','align="left"',true)
 );

recordsPerPage = <?php echo RECORDS_PER_PAGE_ADMIN_MESSAGE ;?>;

linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/AdminMessage/ajaxAdminEmployeeMessageList.php';
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



//-----------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO fetch data according to controls selected in first div
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

function getData(mode){
   //this function is used to build search criteria
   var modes=mode;
   if(document.getElementById('simageField1').style.display=='none'){
       modes=2;//this is done to tackle "enter" key press in second div
   }
   if(!createSearchCriteria(modes)){
       return false;
   }
   sendReq(listURL,divResultName,searchFormName,'',false);
   hide_div('showList',1);
   document.getElementById('divButton').style.display='block';
   
   if(document.getElementById('specific').style.display=='block'){
       animatedcollapse.hide('specific');
       moveListButton(2);
   }
}

//-------------------------------------------------------------------------------------
//Puppose:To build the query string based upon div selection
//Author:Dipanjan Bhattacharjee
//Date:05.09.2008
//-------------------------------------------------------------------------------------
function createSearchCriteria(mode){
 var roles="";var teachins="";var desigs="";var branchs="";var cities="";
 //"Search Criteria" generates from first div only
 document.searchForm.searchType.value=1; 
 var i=0;
 //Get selected role criteria
 var c=document.searchForm.role.length;
 for(i=0;i<c;i++){
    if(document.searchForm.role[ i ].selected){
      if(document.searchForm.role[ i ].value!=""){  
        if(roles==""){
            roles= document.searchForm.role[ i ].value;
        }
        else{
            roles=roles + "," + document.searchForm.role[ i ].value;
        }
      }  
    }  
 }
 document.searchForm.roleId.value=roles;
 
 //Get selected teaching criteria
 /*
 c=document.searchForm.teaching.length;
 for(i=0;i<c;i++){
    if(document.searchForm.teaching[ i ].selected){
      if(document.searchForm.teaching[ i ].value!=""){  
        if(teachins==""){
            teachins= document.searchForm.teaching[ i ].value;
        }
        else{
            teachins=teachins + "," + document.searchForm.teaching[ i ].value;
        }
      }  
    }  
 }
 document.searchForm.isTeaching.value=teachins;
 */
  //checking teaching status 
  if(document.searchForm.teaching[0].checked || document.searchForm.teaching[1].checked){ 
   document.searchForm.isTeaching.value=document.searchForm.teaching[0].checked ? "1" : "0";
  }
 
 //Get selected designations
 c=document.searchForm.designation.length;
 for(i=0;i<c;i++){
    if(document.searchForm.designation[ i ].selected){
      if(document.searchForm.designation[ i ].value!=""){  
        if(desigs==""){
            desigs= document.searchForm.designation[ i ].value;
        }
        else{
            desigs=desigs + "," + document.searchForm.designation[ i ].value;
        }
      }  
    }  
 }
 document.searchForm.designationId.value=desigs;
 
 //Get selected branches
 c=document.searchForm.branch.length;
 for(i=0;i<c;i++){
    if(document.searchForm.branch[ i ].selected){
      if(document.searchForm.branch[ i ].value!=""){  
        if(branchs==""){
            branchs= document.searchForm.branch[ i ].value;
        }
        else{
            branchs=branchs + "," + document.searchForm.branch[ i ].value;
        }
      }  
    }  
 }
 document.searchForm.branchId.value=branchs;
 
 //Get selected cities
 c=document.searchForm.city.length;
 for(i=0;i<c;i++){
    if(document.searchForm.city[ i ].selected){
      if(document.searchForm.city[ i ].value!=""){  
        if(cities==""){
            cities= document.searchForm.city[ i ].value;
        }
        else{
            cities=cities + "," + document.searchForm.city[ i ].value;
        }
      }  
    }  
 }
 document.searchForm.cityId.value=cities;
 if(mode==1){
     return true; //as there is no validation required in first div
 }
 if(mode==2){//"Search Criteria" generates from (first+second) divs
   document.searchForm.searchType.value=2;
   
   document.searchForm.dateOfBirthF.value="";
   document.searchForm.dateOfBirthT.value="";
   document.searchForm.dateOfJoiningT.value="";
   document.searchForm.dateOfJoiningF.value="";
   
   var fl=1;
   if(document.getElementById('birthYearF').value!="" || document.getElementById('birthMonthF').value!="" || document.getElementById('birthDateF').value!=""){
     if(document.getElementById('birthYearF').value==""){
       messageBox("Select Birth Year");document.getElementById('birthYearF').focus();fl=0;  
       return false;  
     }
    else if(document.getElementById('birthMonthF').value==""){
       messageBox("Select Birth Month");document.getElementById('birthMonthF').focus();fl=0;
       return false;    
     }   
    else if(document.getElementById('birthDateF').value==""){
       messageBox("Select Birth Date");document.getElementById('birthDateF').focus();fl=0; 
       return false;   
     }   
    else{
        document.searchForm.dateOfBirthF.value=document.getElementById('birthYearF').value + "-" + document.getElementById('birthMonthF').value + "-" + document.getElementById('birthDateF').value;
        fl=1;
    } 
   }
   if(document.getElementById('birthYearT').value!="" || document.getElementById('birthMonthT').value!="" || document.getElementById('birthDateT').value!=""){
     if(document.getElementById('birthYearT').value==""){
       messageBox("Select Birth Year");document.getElementById('birthYearT').focus();fl=0; 
       return false;   
     }
    else if(document.getElementById('birthMonthT').value==""){
       messageBox("Select Birth Month");document.getElementById('birthMonthT').focus();fl=0;
       return false;    
     }   
    else if(document.getElementById('birthDateT').value==""){
       messageBox("Select Birth Date");document.getElementById('birthDateT').focus();fl=0;
       return false;    
     }   
    else{
        document.searchForm.dateOfBirthT.value=document.getElementById('birthYearT').value + "-" + document.getElementById('birthMonthT').value + "-" + document.getElementById('birthDateT').value;
        fl=1;
    } 
   }
   
   /*
   if(document.getElementById('marriageYear').value!="" || document.getElementById('marriageMonth').value!="" || document.getElementById('marriageDate').value!=""){
     if(document.getElementById('marriageYear').value==""){
       messageBox("Select Marriage Year");document.getElementById('marriageYear').focus();fl=0;   
     }
    else if(document.getElementById('marriageMonth').value==""){
       messageBox("Select Marriage Month");document.getElementById('marriageMonth').focus();fl=0;   
     }   
    else if(document.getElementById('marriageDate').value==""){
       messageBox("Select Marriage Date");document.getElementById('marriageYear').focus();fl=0;   
     }   
    else{
        document.searchForm.dateOfMarriage.value=document.getElementById('marriageYear').value + "-" + document.getElementById('marriageMonth').value + "-" + document.getElementById('marriageDate').value;
        fl=1;
    } 
   }
  */ 
   if(document.getElementById('joiningYearF').value!="" || document.getElementById('joiningMonthF').value!="" || document.getElementById('joiningDateF').value!=""){
     if(document.getElementById('joiningYearF').value==""){
       messageBox("Select Joining Year");document.getElementById('joiningYearF').focus();fl=0;   
       return false; 
     }
    else if(document.getElementById('joiningMonthF').value==""){
       messageBox("Select Joining Month");document.getElementById('joiningMonthF').focus();fl=0; 
       return false;   
     }   
    else if(document.getElementById('joiningDateF').value==""){
       messageBox("Select Joining Date");document.getElementById('joiningDateF').focus();fl=0; 
       return false;   
     }   
    else{
        document.searchForm.dateOfJoiningF.value=document.getElementById('joiningYearF').value + "-" + document.getElementById('joiningMonthF').value + "-" + document.getElementById('joiningDateF').value;
        fl=1;
    } 
   }
   if(document.getElementById('joiningYearT').value!="" || document.getElementById('joiningMonthT').value!="" || document.getElementById('joiningDateT').value!=""){
     if(document.getElementById('joiningYearT').value==""){
       messageBox("Select Joining Year");document.getElementById('joiningYearT').focus();fl=0; 
       return false;   
     }
    else if(document.getElementById('joiningMonthT').value==""){
       messageBox("Select Joining Month");document.getElementById('joiningMonthT').focus();fl=0;  
       return false;  
     }   
    else if(document.getElementById('joiningDateT').value==""){
       messageBox("Select Joining Date");document.getElementById('joiningDateT').focus();fl=0;
       return false;    
     }   
    else{
        document.searchForm.dateOfJoiningT.value=document.getElementById('joiningYearT').value + "-" + document.getElementById('joiningMonthT').value + "-" + document.getElementById('joiningDateT').value;
        fl=1;
    } 
   }
   /*
   if(document.getElementById('leavingYear').value!="" || document.getElementById('leavingMonth').value!="" || document.getElementById('leavingDate').value!=""){
     if(document.getElementById('leavingYear').value==""){
       messageBox("Select Leaving Year");document.getElementById('leavingYear').focus();fl=0;   
     }
    else if(document.getElementById('leavingMonth').value==""){
       messageBox("Select Leaving Month");document.getElementById('leavingMonth').focus();fl=0;   
     }   
    else if(document.getElementById('leavingDate').value==""){
       messageBox("Select Leaving Date");document.getElementById('leavingDate').focus();fl=0;   
     }   
    else{
        document.searchForm.dateOfLeaving.value=document.getElementById('leavingYear').value + "-" + document.getElementById('leavingMonth').value + "-" + document.getElementById('leavingDate').value;
        fl=1;
    } 
   }
  */ 
  //checking marrital status 
  if(document.searchForm.isMarried[0].checked || document.searchForm.isMarried[1].checked){ 
   document.searchForm.married.value=document.searchForm.isMarried[0].checked ? "1" : "0";
  }
  
  if(fl==0){
      return false;
  }
  else{
      return true;
  }
  
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

if((document.listFrm.emps.length - 2) == 0){
   messageBox("<?php echo NO_DATA_SUBMIT; ?>");   
   return false;
 }     

if(trim(document.getElementById('msgSubject').value)==""){  
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
else if(!(checkEmps())){  //checkes whether any student/parent checkboxes selected or not
     alert("<?php echo EMPLOYEE_SELECT_ALERT; ?>");  
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
         url = '<?php echo HTTP_LIB_PATH;?>/AdminMessage/ajaxAdminSendEmployeeMessage.php';
         
         
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
         
         //var msgMedium=((document.getElementById('smsCheck').checked) ? document.getElementById('smsCheck').value: 0)+","+((document.getElementById('emailCheck').checked) ? document.getElementById('emailCheck').value: 0) ;

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {msgBody: (trim(document.getElementById('elm1').value)), 
             emp: (emp),
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



//********INITIALIZES EXPANDABLE DIVs***********
 //animatedcollapse.addDiv('general', 'fade=0,speed=400,group=employee')
 animatedcollapse.addDiv('specific', 'fade=0,speed=400,group=employee,persist=1,hide=1')
 animatedcollapse.init() ;
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
 
 document.getElementById('joiningYearT').selectedIndex=document.getElementById('joiningYearT').options.length-1; 
 document.getElementById('joiningMonthT').selectedIndex=document.getElementById('joiningMonthT').options.length-1; 
 document.getElementById('joiningDateT').selectedIndex=document.getElementById('joiningDateT').options.length-1; 
 
 document.getElementById('birthYearT').selectedIndex=document.getElementById('birthYearT').options.length-1; 
 document.getElementById('birthMonthT').selectedIndex=document.getElementById('birthMonthT').options.length-1; 
 document.getElementById('birthDateT').selectedIndex=document.getElementById('birthDateT').options.length-1; 
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/AdminMessage/listAdminEmployeeSMSContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>

</body>
</html>

<?php                              
// $History: listAdminEmployeeSMS.php $ 
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
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/08/08    Time: 4:05p
//Updated in $/Leap/Source/Interface
//Updated according to Kabir Sir's suggestion
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/05/08    Time: 12:11p
//Updated in $/Leap/Source/Interface
//Added employee search filter
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
?>