<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF INISTITUTES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InstituteMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Institute/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Institute Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
        new Array('srNo','#','width="2%"','',false), 
        new Array('instituteName','Name','width="20%"','',true) , 
        new Array('instituteCode','Code','width="8%"','',true), 
        new Array('instituteAbbr','Abbr.','width="8%"','',true) , 
        new Array('instituteWebsite','Website','width="19%"','',true) , 
        new Array('employeePhone','Ph. No','width="10%"','',true), 
        new Array('instituteEmail','Email','width="20%"','',true) , 
        new Array('action','Action','width="3%"','align="right"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Institute/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddInstituteDiv'; // div container name  
editFormName   = 'EditInstituteDiv'; // div container name
winLayerWidth  = 650; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteInstitute';
divResultName  = 'results';
page=1; //default page
sortField = 'instituteName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    //displayWindow(dv,w,h);
    //***As the Div is Huge so we have to incorporate this function.
    //Same functionality but can set left and top of the Div also***
    displayFloatingDiv(dv,'', 650,400,200,100);
    populateValues(id);   
}

 

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
    var fieldsArray = new Array(new Array("instituteName","<?php echo ENTER_INSTITUTE_NAME;  ?>"),
    new Array("instituteCode","<?php echo ENTER_INSTITUTE_CODE; ?>"),
    new Array("instituteAbbr","<?php echo ENTER_INSTITUTE_ABBR; ?>"), 
    new Array("instituteEmail","<?php echo ENTER_INSTITUTE_EMAIL; ?>"),
    new Array("instituteAddress1","<?php echo ENTER_INSTITUTE_ADDRESS1; ?>"),
    new Array("instituteAddress2","<?php echo ENTER_INSTITUTE_ADDRESS2; ?>"),
    new Array("instituteWebsite","<?php echo ENTER_INSTITUTE_WEBSITE; ?>"), 
    new Array("designationId","<?php echo SELECT_DESIGNATION; ?>"), 
    new Array("employeePhone","<?php echo ENTER_INSTITUTE_CONTACT_NO; ?>"), 
    new Array("country","<?php echo SELECT_COUNTRY; ?>"),
    new Array("states","<?php echo SELECT_STATE; ?>"), 
    new Array("city","<?php echo SELECT_CITY; ?>"),
    new Array("pin","<?php echo ENTER_PIN; ?>")
    //,new Array("instituteLogo","Select a logo")
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
        else if(fieldsArray[i][0]=="country" && eval("frm."+(fieldsArray[i][0])+".value")=="")  {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else if(fieldsArray[i][0]=="states" && eval("frm."+(fieldsArray[i][0])+".value")=="" )  {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else if(fieldsArray[i][0]=="city" && eval("frm."+(fieldsArray[i][0])+".value")=="")  {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else if(fieldsArray[i][0]=="instituteEmail"){
            if(!isEmail(eval("frm."+(fieldsArray[i][0])+".value"))) //if not valid email format
              {
                 alert("<?php  echo ENTER_VALID_EMAIL; ?>");
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }
        }
       else if(fieldsArray[i][0]=="instituteWebsite"){
            if(!checkDomain(eval("frm."+(fieldsArray[i][0])+".value"))) //if not valid website format
              {
                 //alert("Please enter a valid email"); //alert is coming from function.js file this time
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }
        }
       /*else if(fieldsArray[i][0]=="employeePhone"){
            if(!isPhone(eval("frm."+(fieldsArray[i][0])+".value"))) //if not valid phone format
              {
                 alert("<?php echo ENTER_VALID_PHONE_NO; ?>");  
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }
        }*/
       else if(fieldsArray[i][0]=="states"){
            if(eval("frm."+(fieldsArray[i][0])+".value")=="SELECT") //if state is not selected
              {
                 alert("<?php echo SELECT_STATE; ?>"); 
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }
        } 
       else if(fieldsArray[i][0]=="city"){
            if(eval("frm."+(fieldsArray[i][0])+".value")=="SELECT") //if city is not selected
              {
                 alert("<?php echo SELECT_CITY; ?>"); 
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }
        }
       else if(fieldsArray[i][0]=="pin" && !isAlphaNumericCustom(eval("frm."+(fieldsArray[i][0])+".value")) )  {
            messageBox("Allowed characters are a-z A-Z 0-9 ");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }   
       else if(fieldsArray[i][0]=="instituteName"){
            if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value"))) //if city is not selected
              {
                 messageBox("Allowed characters are a-z A-Z & .- "); 
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }
        }     
       else if(fieldsArray[i][0]=="instituteAddress1" || fieldsArray[i][0]=="instituteAddress2"){
         //no check
       } 
        else  {
               if (!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]!="instituteName"  && fieldsArray[i][0]!="instututeAddress1" && fieldsArray[i][0]!="instututeAddress2" && fieldsArray[i][0]!="instututeEmail" && fieldsArray[i][0]!="instututeWebsite" && fieldsArray[i][0]!="employeePhone" && fieldsArray[i][0]!="state" && fieldsArray[i][0]!="city") {
                //winAlert("Enter string",fieldsArray[i][0]);
                 messageBox("Allowed characters are a-z A-Z 0-9 ( ) - ");   
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
                }
            }
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
       
    
    if(act=='Add') {
        initAdd();
        addInstitute();
        //return false;
    }
    else if(act=='Edit') {
        if(document.EditInstitute.employeeId.value=="")  {
            messageBox("<?php echo ENTER_INSTITUTE_EMPLOYEE; ?>");
            document.EditInstitute.employeeId.focus();
            return false;
         }
         
        initEdit();
        editInstitute();
        //return false;
    }
 }
//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW INSTITUTE   
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addInstitute() {
         url = '<?php echo HTTP_LIB_PATH;?>/Institute/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {instituteName: (document.AddInstitute.instituteName.value), 
             instituteCode: (document.AddInstitute.instituteCode.value),  
             instituteAbbr: (document.AddInstitute.instituteAbbr.value), 
             instituteEmail: (document.AddInstitute.instituteEmail.value),
             instituteAddress1: (document.AddInstitute.instituteAddress1.value), 
             instituteAddress2: (document.AddInstitute.instituteAddress2.value), 
             instituteWebsite: (document.AddInstitute.instituteWebsite.value),
             employeeId: (document.AddInstitute.employee.value),
             employeePhone: (document.AddInstitute.employeePhone.value),
             designation: (document.AddInstitute.designationId.value),
             country: (document.AddInstitute.country.value),
             states: (document.AddInstitute.states.value),
             city: (document.AddInstitute.city.value),
             pin: (document.AddInstitute.pin.value)
             //,instituteLogo: (document.AddInstitute.instituteLogo.value)
             },
             onCreate: function() {
                 showWaitDialog(true); 
             },
             onSuccess: function(transport){

                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('AddInstituteDiv');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         }
                     }
                     else if("<?php echo INSTITUTE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                          messageBox("<?php echo INSTITUTE_ALREADY_EXIST ;?>"); 
                          document.AddInstitute.instituteCode.focus();
                     }
                     else if("<?php echo INSTITUTE_NAME_ALREADY_EXIST;?>" == trim(transport.responseText)){
                          messageBox("<?php echo INSTITUTE_NAME_ALREADY_EXIST ;?>"); 
                          document.AddInstitute.instituteName.focus();
                     }
                     else if("<?php echo INSTITUTE_ABBR_ALREADY_EXIST;?>" == trim(transport.responseText)){
                          messageBox("<?php echo INSTITUTE_ABBR_ALREADY_EXIST ;?>"); 
                          document.AddInstitute.instituteAbbr.focus();
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
               
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


//--------------------------------------------------------   
//THIS FUNCTION IS USED TO DELETE AN INSTITUTE
//  id=instituteId
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteInstitute(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Institute/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {instituteId: id},
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "AddInatitute" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddInstitute.reset();
   /*
   document.AddInstitute.instituteName.value = '';
   document.AddInstitute.instituteCode.value = '';   
   document.AddInstitute.instituteAbbr.value = '';
   document.AddInstitute.instituteEmail.value = '';
   document.AddInstitute.instituteAddress1.value = '';
   document.AddInstitute.instituteAddress2.value = '';
   document.AddInstitute.instituteWebsite.value = '';
   //document.AddInstitute.employee.value = '';
   document.AddInstitute.employeePhone.value = '';
   //document.AddInstitute.designation.value = '';
   document.AddInstitute.country.value = '';    
   document.AddInstitute.states.value = '';     
   document.AddInstitute.city.value = '';       
   document.AddInstitute.pin.value = '';        
   //document.AddInstitute.instituteLogo.value = '';
  */   
   document.AddInstitute.instituteName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A INSTITUTE
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editInstitute() {
         url = '<?php echo HTTP_LIB_PATH;?>/Institute/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {instituteId: (document.EditInstitute.instituteId.value), 
             instituteName: (document.EditInstitute.instituteName.value), 
             instituteCode: (document.EditInstitute.instituteCode.value),  
             instituteAbbr: (document.EditInstitute.instituteAbbr.value), 
             instituteEmail: (document.EditInstitute.instituteEmail.value),
             instituteAddress1: (document.EditInstitute.instituteAddress1.value), 
             instituteAddress2: (document.EditInstitute.instituteAddress2.value), 
             instituteWebsite: (document.EditInstitute.instituteWebsite.value),
             employeeId: (document.EditInstitute.employeeId.value),
             employeePhone: (document.EditInstitute.employeePhone.value),
             designation: (document.EditInstitute.designationId.value),
             country: (document.EditInstitute.country.value),
             states: (document.EditInstitute.states.value),
             city: (document.EditInstitute.city.value),
             pin: (document.EditInstitute.pin.value)
             //,instituteLogo: (document.EditInstitute.instituteLogo.value)
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditInstituteDiv');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo INSTITUTE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                          messageBox("<?php echo INSTITUTE_ALREADY_EXIST ;?>"); 
                          document.EditInstitute.instituteCode.focus();
                     }
                     else if("<?php echo INSTITUTE_NAME_ALREADY_EXIST;?>" == trim(transport.responseText)){
                          messageBox("<?php echo INSTITUTE_NAME_ALREADY_EXIST ;?>"); 
                          document.EditInstitute.instituteName.focus();
                     }
                     else if("<?php echo INSTITUTE_ABBR_ALREADY_EXIST;?>" == trim(transport.responseText)){
                          messageBox("<?php echo INSTITUTE_ABBR_ALREADY_EXIST ;?>"); 
                          document.EditInstitute.instituteAbbr.focus();
                     }  
                    else {
                        messageBox(trim(transport.responseText));                         
                     } 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditInatitute" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         var url = '<?php echo HTTP_LIB_PATH;?>/Institute/ajaxGetValues.php';
         document.EditInstitute.reset();
         document.EditInstitute.employeeId.value='';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {instituteId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     document.EditInstitute.reset()
                    //alert(transport.responseText);
                    //return false;
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditInstitute');
                        messageBox("<?php INSTITUTE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = trim(transport.responseText).evalJSON();                    
                  
                   //****where is the image path
                   //generate random no to remove cache
                   d = new Date();
                   rndNo = d.getTime();
                   document.EditInstitute.employeeId.options.length=1;
                   
                   if(j.employee!=''){
                   var elength=j.employee.length;
                       for(var i=0;i<elength;i++){
                         var objOption = new Option(j.employee[i].employeeName,j.employee[i].employeeId);
                         document.EditInstitute.employeeId.options.add(objOption);  
                       }
                   }
                   
                   imageLogoPath = '<img name="logo" src="<?php echo IMG_HTTP_PATH;?>/Institutes/'+j.edit[0].instituteLogo+'?'+rndNo+'" border="0" width="70" height="70" title="Close"/>';
                   document.getElementById('editLogoPlace').innerHTML = imageLogoPath;
                   document.getElementById('editLogoPlace').style.display = 'block';
                   // alert(document.getElementById('editLogoPlace').value);
                   document.EditInstitute.instituteName.value = j.edit[0].instituteName;
                   document.EditInstitute.instituteCode.value = j.edit[0].instituteCode;
                   document.EditInstitute.instituteAbbr.value = j.edit[0].instituteAbbr;
                   document.EditInstitute.instituteEmail.value =j.edit[0].instituteEmail;
                   document.EditInstitute.instituteAddress1.value =j.edit[0].instituteAddress1;
                   document.EditInstitute.instituteAddress2.value =j.edit[0].instituteAddress2;
                   document.EditInstitute.instituteWebsite.value =j.edit[0].instituteWebsite;
                   document.EditInstitute.employeeId.value = (j.edit[0].employeeId == -1 ? '' : j.edit[0].employeeId);
                   document.EditInstitute.employeePhone.value = j.edit[0].employeePhone;      
                   document.EditInstitute.designationId.value = (j.edit[0].designationId==null ? 'NULL' : j.edit[0].designationId);
                   document.EditInstitute.country.value =j.edit[0].countryId;
                   
                   document.EditInstitute.pin.value = j.edit[0].pin;
                   document.EditInstitute.instituteId.value =j.edit[0].instituteId;
                   
                   // populate states as per country id 
                   len = j.state.length;
                   document.EditInstitute.states.length = null;
                   // add option Select initially
                   addOption(document.EditInstitute.states, '', 'Select');
                   for(i=0;i<len;i++) { 
                     addOption(document.EditInstitute.states, j.state[i].stateId, j.state[i].stateName);
                   }
                   // now select the value
                   document.EditInstitute.states.value = j.edit[0].stateId;
                   
                   // populate cities as per state id
                   len = j.city.length;
                   document.EditInstitute.city.length = null;
                   // add option Select initially
                   addOption(document.EditInstitute.city, '', 'Select');
                   for(i=0;i<len;i++) { 
                     addOption(document.EditInstitute.city, j.city[i].cityId, j.city[i].cityName);
                   }
                   // now select the value
                   document.EditInstitute.city.value = j.edit[0].cityId;
                   
                   // set focus on name field
                   document.EditInstitute.instituteName.focus();

             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}


//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "states/city" select box depending upon which country/state is selected
//
//Author : Dipanjan Bhattacharjee
// Created on : (17.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------
//id:id 
//type:states/city
//target:taget dropdown box

function autoPopulate(val,type,frm)
{
   url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';

   if(frm=="Add"){
       if(type=="states"){
            document.AddInstitute.states.options.length=0;
            var objOption = new Option("SELECT","");
            document.AddInstitute.states.options.add(objOption); 
          
            var objOption = new Option("SELECT","");
            document.AddInstitute.city.options.length=0;
            document.AddInstitute.city.options.add(objOption); 
       }
      else{
            document.AddInstitute.city.options.length=0;
            var objOption = new Option("SELECT","");
            document.AddInstitute.city.options.add(objOption);
      } 
   }
   else{                        //for edit
        if(type=="states"){
            document.EditInstitute.states.options.length=0;
            var objOption = new Option("SELECT","");            
            document.EditInstitute.states.options.add(objOption); 
            
            document.EditInstitute.city.options.length=0;
            var objOption = new Option("SELECT","");            
            document.EditInstitute.city.options.add(objOption); 
       }
      else{
            document.EditInstitute.city.options.length=0;
            var objOption = new Option("SELECT","");          
            document.EditInstitute.city.options.add(objOption);
      } 
   }

new Ajax.Request(url,
           {
             method:'post',
             parameters: {type: type,id: val},
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                     j = eval('('+transport.responseText+')'); 

                     for(var c=0;c<j.length;c++){
                         if(frm=="Add"){
                             if(type=="states"){
                                 var objOption = new Option(j[c].stateName,j[c].stateId);
                                 document.AddInstitute.states.options.add(objOption); 
                             }
                            else{
                                 var objOption = new Option(j[c].cityName,j[c].cityId);
                                 document.AddInstitute.city.options.add(objOption); 
                            } 
                          }
                      else{
                            if(type=="states"){
                                 var objOption = new Option(j[c].stateName,j[c].stateId);
                                 document.EditInstitute.states.options.add(objOption); 
                             }
                            else{
                                 var objOption = new Option(j[c].cityName,j[c].cityId);
                                 document.EditInstitute.city.options.add(objOption); 
                            } 
                          }
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}
function initAdd() {
    document.getElementById('AddInstitute').onsubmit=function() {
        document.getElementById('AddInstitute').target = 'uploadTargetAdd';
    }
}
//window.onload=initAdd;
function initEdit() {
    document.getElementById('EditInstitute').onsubmit=function() {
        document.getElementById('EditInstitute').target = 'uploadTargetEdit';
    }
}

/* function to print institute report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/instituteReportPrint.php?'+qstr;
    window.open(path,"InstituteReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='instituteReportCSV.php?'+qstr;
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Institute/listInstituteContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<script language="javascript">
 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listInstitute.php $ 
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 09-12-24   Time: 12:20p
//Updated in $/LeapCC/Interface
//removed special character check from phone number
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 21/10/09   Time: 11:42
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//00001796,00001794,00001786,00001630
//
//*****************  Version 7  *****************
//User: Administrator Date: 8/06/09    Time: 14:13
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---> 1318 to 1329 ,Leap bugs4.doc(5 to 10,12,20)
//
//*****************  Version 6  *****************
//User: Administrator Date: 29/05/09   Time: 11:43
//Updated in $/LeapCC/Interface
//Done bug fixing------ Issues [28-May-09]Build# cc0007
//
//*****************  Version 5  *****************
//User: Administrator Date: 28/05/09   Time: 12:40
//Updated in $/LeapCC/Interface
//Corrected institute module
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 26/05/09   Time: 15:45
//Updated in $/LeapCC/Interface
//Fixed bugs-----Issues [26-May-09]1
//
//*****************  Version 3  *****************
//User: Administrator Date: 25/05/09   Time: 12:13
//Updated in $/LeapCC/Interface
//Corrected "Popup Div" problem
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
//*****************  Version 25  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:31p
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 24  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Interface
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 23  *****************
//User: Dipanjan     Date: 8/20/08    Time: 12:29p
//Updated in $/Leap/Source/Interface
//Added standard messages
//
//*****************  Version 22  *****************
//User: Dipanjan     Date: 8/08/08    Time: 3:06p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 21  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:03p
//Updated in $/Leap/Source/Interface
//Added onCreate() function in ajax code
//
//*****************  Version 20  *****************
//User: Dipanjan     Date: 7/24/08    Time: 5:03p
//Updated in $/Leap/Source/Interface
//Modified populateValues function to have 'NULL' to null
//
//*****************  Version 19  *****************
//User: Dipanjan     Date: 7/19/08    Time: 1:17p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 18  *****************
//User: Dipanjan     Date: 7/19/08    Time: 1:09p
//Updated in $/Leap/Source/Interface
//Corrected Javscript Error during addition
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 7/16/08    Time: 8:21p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 7/16/08    Time: 7:30p
//Updated in $/Leap/Source/Interface
//Fixes BugId:45
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 7/11/08    Time: 1:26p
//Updated in $/Leap/Source/Interface
//Modified img height during "EditDiv" population
//
//*****************  Version 14  *****************
//User: Pushpender   Date: 7/11/08    Time: 12:18p
//Updated in $/Leap/Source/Interface
//added the code to control cache while showing image on edit form screen
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 7/09/08    Time: 1:25p
//Updated in $/Leap/Source/Interface
//Modified javascript validation state and city selection
//
//*****************  Version 12  *****************
//User: Pushpender   Date: 7/08/08    Time: 6:00p
//Updated in $/Leap/Source/Interface
//Placed onCreate function for ajax states, changed the code to correct
//city, state populating problem, file upload issue fixed and few other
//changes
//
//*****************  Version 11  *****************
//User: Pushpender   Date: 7/07/08    Time: 8:17p
//Updated in $/Leap/Source/Interface
//changed values in AddFormName, EditFormName variables of JS
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 6/30/08    Time: 5:40p
//Updated in $/Leap/Source/Interface
//Added JavaScript code for making selection of Country-->State-->City
//mandatory
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 6/30/08    Time: 4:36p
//Updated in $/Leap/Source/Interface
//Added AjaxListing and AjaxSearch Functionality
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 6/26/08    Time: 2:55p
//Updated in $/Leap/Source/Interface
//Added AjaxEnabled Delete Functionality
//
//*****************  Version 7  *****************
//User: Pushpender   Date: 6/23/08    Time: 5:26p
//Updated in $/Leap/Source/Interface
//just refined and made changes for file upload, later on removed all
//
//*****************  Version 6  *****************
//User: Pushpender   Date: 6/20/08    Time: 7:45p
//Updated in $/Leap/Source/Interface
//made changes to upload files, but still need to test it
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/17/08    Time: 2:50p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/17/08    Time: 10:53a
//Updated in $/Leap/Source/Interface
//Modifying html Done
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/16/08    Time: 7:22p
//Updated in $/Leap/Source/Interface
//no change 
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:19p
//Updated in $/Leap/Source/Interface
//Modifying Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 7:29p
//Created in $/Leap/Source/Interface
//Initial Checkin
?>