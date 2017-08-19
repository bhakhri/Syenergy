<?php
//---------------------------------------------------------------------------
// THIS FILE used for sending message(sms/email/dashboard) to employees
// Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeHierarchy');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Employee Hierarchy</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
 new Array('srNo','#','width="1%"','',false),
 new Array('emps','<input type=\"checkbox\" id=\"empList\" name=\"empList\" onclick=\"selectEmps();\">','width="2%"','align=\"left\"',false), 
 new Array('employeeName','Emp. Name','width="15%"','align="left"',true),
 new Array('employeeCode','Emp. Code','width="7%"','align="left"',true) ,
 new Array('designationName','Designation','width="10%"','align="left"',true),
 new Array('branchCode','Branch','width="5%"','align="left"',true),
 new Array('roleName','Role','width="5%"','align="left"',true),
 new Array('superiorEmployee','Superior Employee','width="15%"','align="left"',true)
);

recordsPerPage = 5000;

linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Appraisal/Hierarchy/employeeList.php';
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
  //obj = document.listFrm.elements[id];
  obj = document.employeeDetailsForm.elements[id];
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
     //document.listFrm.emps.checked =state;
     document.employeeDetailsForm.emps.checked =state;
     return true;  
    }
    //formx = document.listFrm; 
    formx = document.employeeDetailsForm; 
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
     //if(document.listFrm.emps.checked==true){
     if(document.listFrm.employeeDetailsForm.checked==true){
         fl=1;
     }
     return fl;
   }
    //formx = document.listFrm; 
    formx = document.employeeDetailsForm; 
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
    if(document.getElementById('supEmployeeId').value==''){
        messageBox("<?php echo SELECT_SUPERIOR_EMPLOYEE; ?>");
        document.getElementById('supEmployeeId').focus();
        return false;
    }
    
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
    
    return false;
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

var serverDate="<?php echo date('Y-m-d');?>";

function validateForm() {                                                 

var deletionFlag=0;    
//if((document.listFrm.emps.length - 2) == 0){
if((document.employeeDetailsForm.emps.length - 2) == 0){
   messageBox("<?php echo NO_DATA_SUBMIT; ?>");
   return false;
}     
if(!(checkEmps())){
     deletionFlag=1;
     if(!confirm("<?php echo HIERARCHY_DELETION_ALERT; ?>")){
         return false;
     } 
} 

 if(document.getElementById('supEmployeeId').value==''){
     messageBox("<?php echo SELECT_SUPERIOR_EMPLOYEE; ?>");
     document.getElementById('supEmployeeId').focus();
     return false;
 }
 if(deletionFlag==0){
   if(!confirm("<?php echo EMPLYEE_HIERARCHY_WILL_CHANGE;?>")){
        return false;
    }
 }
 doHierarchy(deletionFlag); 
 return false;
}


function doHierarchy(deletionFlag) {
         var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/Hierarchy/doEmployeeHierarchy.php';
         
         
         //determines which student and parents are selected and their studentIds
         //formx = document.listFrm; 
         formx = document.employeeDetailsForm; 
         var emp="";  //get studentIds when student checkboxes are selected
         
         var emp2='';
         
        //if((document.listFrm.emps.length - 2)<=1){
        if((document.employeeDetailsForm.emps.length - 2)<=1){
           //emp=(document.listFrm.emps[2].checked ? document.listFrm.emps[2].value : "0" );
           emp=(document.employeeDetailsForm.emps[2].checked ? document.employeeDetailsForm.emps[2].value : "0" );
           emp2=document.employeeDetailsForm.emps[2].value;
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
            if(emp2!=''){
                emp2 +=',';
            }
            emp2 +=formx.emps[ k ].value;
          }
        }  

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 supEmployeeId : document.getElementById('supEmployeeId').value,
                 emp: (emp),
                 emp2: (emp2)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                        flag = true;
                        if(deletionFlag==0){
                         messageBox("<?php echo HIERARCHY_DONE; ?>");
                        }
                        else{
                         messageBox("<?php echo HIERARCHY_DELETION_DONE; ?>");   
                        }
                        resetForm();
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


//----------------------------------------------------------------------------------------------------------------
//Pupose:To reset form after data submission
//Author: Dipanjan Bhattacharjee
//Date : 5.08.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function resetForm(){
 document.getElementById('divButton').style.display='none';
 document.getElementById('results').innerHTML="";
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Appraisal/Hierarchy/listEmployeeHierarchyContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>

</body>
</html>

<?php                              
// $History: listAdminEmployeeMessage.php $ 
?>