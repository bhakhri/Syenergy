<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF INISTITUTES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UniversityMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/University/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: University Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
new Array('universityName','Name','width="18%"','',true) , 
new Array('universityCode','Code','width="12%"','',true), 
new Array('universityAbbr','Abbr.','width="8%"','',true) , 
new Array('universityWebsite','Website','width="18%"','',true) ,  
new Array('contactPerson','Contact Person','width="15%"','',true), 
new Array('contactNumber','Ph. No.','width="15%"','',true), 
new Array('universityEmail','Email','width="20%"','',true) , 
new Array('action','Action','width="10%"','align="right"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/University/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddUniversityDiv'; // div container name  
editFormName   = 'EditUniversityDiv'; // div container name
winLayerWidth  = 650; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteUniversity';
divResultName  = 'results';
page=1; //default page
sortField = 'universityName';
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    //displayWindow(dv,w,h);
    //***As the Div is Huge so we have to incorporate this function.
    //Same functionality but can set left and top of the Div also***
    displayFloatingDiv(dv,'', 650,375,200,100);
    populateValues(id);   
}

 

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
    var fieldsArray = new Array(new Array("universityName","<?php echo ENTER_UNIVERSITY_NAME; ?>"),
    new Array("universityCode","<?php echo ENTER_UNIVERSITY_CODE; ?>"),
    new Array("universityAbbr","<?php echo ENTER_UNIVERSITY_ABBR; ?>"),
    new Array("universityEmail","<?php echo ENTER_UNIVERSITY_EMAIL; ?>"),
    new Array("universityAddress1","<?php echo ENTER_UNIVERSITY_ADDRESS1; ?>"),
    new Array("universityAddress2","<?php echo ENTER_UNIVERSITY_ADDRESS2; ?>"),
    new Array("universityWebsite","<?php echo ENTER_UNIVERSITY_WEBSITE; ?>"), 
    new Array("contactPerson","<?php echo ENTER_UNIVERSITY_CONTACT_PERSON; ?>"), 
    new Array("contactNumber","<?php echo ENTER_UNIVERSITY_CONTACT_NO; ?>"), 
    new Array("designation","<?php echo SELECT_DESIGNATION; ?>"),
    new Array("country","<?php echo SELECT_COUNTRY; ?>"),
    new Array("states","<?php echo SELECT_STATE; ?>"), 
    new Array("city","<?php echo SELECT_CITY; ?>"),
    new Array("pin","<?php echo ENTER_PIN; ?>")
    //,new Array("universityLogo","Select a logo")
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
        /*
        else if(fieldsArray[i][0]=="designation" && eval("frm."+(fieldsArray[i][0])+".value")=="NULL")  {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        */
        else if(fieldsArray[i][0]=="universityEmail"){
            if(!isEmail(eval("frm."+(fieldsArray[i][0])+".value"))) //if not valid email format
              {
                 alert("<?php echo ENTER_VALID_EMAIL;  ?>");
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }
        }
       else if(fieldsArray[i][0]=="universityWebsite"){
            if(!checkDomain(eval("frm."+(fieldsArray[i][0])+".value"))) //if not valid website format
              {
                 //alert("Please enter a valid email"); //alert is coming from function.js file this time
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }
        } 
       else if(fieldsArray[i][0]=="contactNumber"){
            if(!isPhone(eval("frm."+(fieldsArray[i][0])+".value"))) //if not valid phone format
              {
                 alert("<?php echo ENTER_VALID_PHONE_NO; ?>"); 
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }
        }
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
                 alert("<?php echo SELECT_CITY ; ?>"); 
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }
        }
       else if(fieldsArray[i][0]=="universityName"){
            if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value"))) //if city is not selected
              {
                 alert("Allowed characters are a-z A-Z & . - ");
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }
        }       
       else if(fieldsArray[i][0]=="universityAddress1" || fieldsArray[i][0]=="universityAddress2"){
         //no check
       } 
        else  {
               if (!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value")) &&  fieldsArray[i][0]!="universityName" && fieldsArray[i][0]!="instututeAddress1" && fieldsArray[i][0]!="instututeAddress2" && fieldsArray[i][0]!="instututeEmail" && fieldsArray[i][0]!="instututeWebsite" && fieldsArray[i][0]!="contactNumber" && fieldsArray[i][0]!="state" && fieldsArray[i][0]!="city") {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("Allowed characters are a-z A-Z () - "); 
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
        addUniversity();
        //return false;
    }
    else if(act=='Edit') {
        initEdit();
        editUniversity();
        //return false;
    }
 }

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW INSTITUTE   
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addUniversity() {
         url = '<?php echo HTTP_LIB_PATH;?>/University/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {universityName: (document.AddUniversity.universityName.value), 
             universityCode: (document.AddUniversity.universityCode.value),  
             universityAbbr: (document.AddUniversity.universityAbbr.value), 
             universityEmail: (document.AddUniversity.universityEmail.value),
             universityAddress1: (document.AddUniversity.universityAddress1.value), 
             universityAddress2: (document.AddUniversity.universityAddress2.value), 
             universityWebsite: (document.AddUniversity.universityWebsite.value),
             contactPerson: (document.AddUniversity.contactPerson.value),
             contactNumber: (document.AddUniversity.contactNumber.value),
             designation: (document.AddUniversity.designation.value),
             country: (document.AddUniversity.country.value),
             states: (document.AddUniversity.states.value),
             city: (document.AddUniversity.city.value),
             pin: (document.AddUniversity.pin.value)
             //,universityLogo: (document.AddUniversity.universityLogo.value)
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
                             hiddenFloatingDiv('AddUniversityDiv');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         }
                     }
                     else if("<?php echo UNIVERSITY_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo UNIVERSITY_ALREADY_EXIST ;?>"); 
                         document.AddUniversity.universityCode.focus();
                        } 
                     else if("<?php echo UNIVERSITY_ABBR_ALREADY_EXIST;?>" == trim(transport.responseText)){
                      messageBox("<?php echo UNIVERSITY_ABBR_ALREADY_EXIST ;?>"); 
                      document.AddUniversity.universityAbbr.focus();
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
//  id=universityId
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteUniversity(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/University/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {universityId: id},
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddUniversity.reset();
   document.AddUniversity.universityLogo.value=''; 
   document.AddUniversity.states.options.length = 1;     
   document.AddUniversity.city.options.length = 1;   
   document.AddUniversity.universityName.value = '';
   document.AddUniversity.universityCode.value = '';   
   document.AddUniversity.universityAbbr.value = '';
   document.AddUniversity.universityEmail.value = '';
   document.AddUniversity.universityAddress1.value = '';
   document.AddUniversity.universityAddress2.value = '';
   document.AddUniversity.universityWebsite.value = '';
   document.AddUniversity.contactPerson.value='';
   document.AddUniversity.contactNumber.value='';
   document.AddUniversity.designation.value = '';
   document.AddUniversity.country.value = '';    
   document.AddUniversity.states.value = '';     
   document.AddUniversity.city.value = '';       
   document.AddUniversity.pin.value = '';        
   document.AddUniversity.universityLogo.value = '';
     
   document.AddUniversity.universityName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A INSTITUTE
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editUniversity() {
         url = '<?php echo HTTP_LIB_PATH;?>/University/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {universityId: (document.EditUniversity.universityId.value), 
             universityName: (document.EditUniversity.universityName.value), 
             universityCode: (document.EditUniversity.universityCode.value),  
             universityAbbr: (document.EditUniversity.universityAbbr.value), 
             universityEmail: (document.EditUniversity.universityEmail.value),
             universityAddress1: (document.EditUniversity.universityAddress1.value), 
             universityAddress2: (document.EditUniversity.universityAddress2.value), 
             universityWebsite: (document.EditUniversity.universityWebsite.value),
             contactPerson: (document.EditUniversity.contactPerson.value),
             contactNumber: (document.EditUniversity.contactNumber.value),
             designation: (document.EditUniversity.designation.value),
             country: (document.EditUniversity.country.value),
             states: (document.EditUniversity.states.value),
             city: (document.EditUniversity.city.value),
             pin: (document.EditUniversity.pin.value)
             //,universityLogo: (document.EditUniversity.universityLogo.value)
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditUniversityDiv');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo UNIVERSITY_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo UNIVERSITY_ALREADY_EXIST ;?>"); 
                         document.EditUniversity.universityCode.focus();
                        }
                    else if("<?php echo UNIVERSITY_ABBR_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo UNIVERSITY_ABBR_ALREADY_EXIST ;?>"); 
                         document.EditUniversity.universityAbbr.focus();
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         
         document.EditUniversity.reset();
         document.EditUniversity.universityLogo.value='';
         
         url = '<?php echo HTTP_LIB_PATH;?>/University/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {universityId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    //alert(transport.responseText);
                    //return false;
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditUniversity');
                        messageBox("<?php echo UNIVERSITY_NOT_EXIST;  ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = trim(transport.responseText).evalJSON();                    
                  
                   //****where is the image path
                   //****where is the image path
                   //generate random no to remove cache
                   d = new Date();
                   rndNo = d.getTime();
                   
                   imageLogoPath = '<img name="logo" src="<?php echo IMG_HTTP_PATH;?>/University/'+j.edit[0].universityLogo+'?'+rndNo+'" border="0" width="70" height="70" title="Close"/>';

                                      
                   document.getElementById('editLogoPlace').innerHTML = imageLogoPath;
                   document.getElementById('editLogoPlace').style.display = 'block';
                   // alert(document.getElementById('editLogoPlace').value);
                   document.EditUniversity.universityName.value = j.edit[0].universityName;
                   document.EditUniversity.universityCode.value = j.edit[0].universityCode;
                   document.EditUniversity.universityAbbr.value = j.edit[0].universityAbbr;
                   document.EditUniversity.universityEmail.value =j.edit[0].universityEmail;
                   document.EditUniversity.universityAddress1.value =j.edit[0].universityAddress1;
                   document.EditUniversity.universityAddress2.value =j.edit[0].universityAddress2;
                   document.EditUniversity.universityWebsite.value =j.edit[0].universityWebsite;
                   document.EditUniversity.contactPerson.value = j.edit[0].contactPerson;
                   document.EditUniversity.contactNumber.value = j.edit[0].contactNumber;      
                   document.EditUniversity.designation.value = (j.edit[0].designationId==null ? 'NULL' : j.edit[0].designationId);
                   document.EditUniversity.country.value =j.edit[0].countryId;
                   
                   document.EditUniversity.pin.value = j.edit[0].pin;
                   document.EditUniversity.universityId.value =j.edit[0].universityId;
                   
                   // populate states as per country id 
                   len = j.state.length;
                   document.EditUniversity.states.length = null;
                   // add option Select initially
                   addOption(document.EditUniversity.states, '', 'Select');
                   for(i=0;i<len;i++) { 
                     addOption(document.EditUniversity.states, j.state[i].stateId, j.state[i].stateName);
                   }
                   // now select the value
                   document.EditUniversity.states.value = j.edit[0].stateId;
                   
                   // populate cities as per state id
                   len = j.city.length;
                   document.EditUniversity.city.length = null;
                   // add option Select initially
                   addOption(document.EditUniversity.city, '', 'Select');
                   for(i=0;i<len;i++) { 
                     addOption(document.EditUniversity.city, j.city[i].cityId, j.city[i].cityName);
                   }
                   // now select the value
                   document.EditUniversity.city.value = j.edit[0].cityId;
                   
                   // set focus on name field
                   document.EditUniversity.universityName.focus();
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}


//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "states/city" select box depending upon which country/state is selected
//
//Author : Dipanjan Bhattacharjee
// Created on : (17.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
            document.AddUniversity.states.options.length=0;
            var objOption = new Option("SELECT","");
            document.AddUniversity.states.options.add(objOption); 
          
            var objOption = new Option("SELECT","");
            document.AddUniversity.city.options.length=0;
            document.AddUniversity.city.options.add(objOption); 
       }
      else{
            document.AddUniversity.city.options.length=0;
            var objOption = new Option("SELECT","");
            document.AddUniversity.city.options.add(objOption);
      } 
   }
   else{                        //for edit
        if(type=="states"){
            document.EditUniversity.states.options.length=0;
            var objOption = new Option("SELECT","");            
            document.EditUniversity.states.options.add(objOption); 
            
            document.EditUniversity.city.options.length=0;
            var objOption = new Option("SELECT","");            
            document.EditUniversity.city.options.add(objOption); 
       }
      else{
            document.EditUniversity.city.options.length=0;
            var objOption = new Option("SELECT","");          
            document.EditUniversity.city.options.add(objOption);
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
                                 document.AddUniversity.states.options.add(objOption); 
                             }
                            else{
                                 var objOption = new Option(j[c].cityName,j[c].cityId);
                                 document.AddUniversity.city.options.add(objOption); 
                            } 
                          }
                      else{
                            if(type=="states"){
                                 var objOption = new Option(j[c].stateName,j[c].stateId);
                                 document.EditUniversity.states.options.add(objOption); 
                             }
                            else{
                                 var objOption = new Option(j[c].cityName,j[c].cityId);
                                 document.EditUniversity.city.options.add(objOption); 
                            } 
                          }
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}
function initAdd() {
    document.getElementById('AddUniversity').onsubmit=function() {
        document.getElementById('AddUniversity').target = 'uploadTargetAdd';
    }
}
//window.onload=initAdd;
function initEdit() {
    document.getElementById('EditUniversity').onsubmit=function() {
        document.getElementById('EditUniversity').target = 'uploadTargetEdit';
    }
}


/* function to print university report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/universityReportPrint.php?'+qstr;
    window.open(path,"UniversityReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='universityReportCSV.php?'+qstr;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/University/listUniversityContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listUniversity.php $ 
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 21/10/09   Time: 11:42
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//00001796,00001794,00001786,00001630
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 20/10/09   Time: 18:11
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//00001812
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 10/20/09   Time: 1:02p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0001811, 0001800, 0001798, 0001795, 0001793, 0001782,
//0001800, 0001813
//
//*****************  Version 6  *****************
//User: Administrator Date: 5/06/09    Time: 12:35
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---1272 to 1281
//
//*****************  Version 5  *****************
//User: Administrator Date: 4/06/09    Time: 15:22
//Updated in $/LeapCC/Interface
//Corrected bugs
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
//*****************  Version 19  *****************
//User: Dipanjan     Date: 11/06/08   Time: 11:16a
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 18  *****************
//User: Dipanjan     Date: 8/20/08    Time: 12:10p
//Updated in $/Leap/Source/Interface
//Added messages.
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:47p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 8/08/08    Time: 3:06p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:03p
//Updated in $/Leap/Source/Interface
//Added onCreate() function in ajax code
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 7/24/08    Time: 5:03p
//Updated in $/Leap/Source/Interface
//Modified populateValues function to have 'NULL' to null
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 7/17/08    Time: 10:58a
//Updated in $/Leap/Source/Interface
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 7/11/08    Time: 1:25p
//Updated in $/Leap/Source/Interface
//Added random number code to control cache
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 7/09/08    Time: 1:52p
//Updated in $/Leap/Source/Interface
//Added Image upload functionality
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