<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF INISTITUTES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PlacementComapanyMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Company Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                new Array('srNo','#','width="2%"','',false), 
                                new Array('companyName','Name','width="25%"','',true) , 
                                new Array('companyCode','Code','width="12%"','',true), 
                                new Array('landline','Landline','width="10%"','',true) , 
                                new Array('mobileNo','Mobile No.','width="10%"','',true) ,  
                                new Array('contactPerson','Contact Person','width="15%"','',true), 
                                new Array('emailId','Email Id','width="15%"','',true),
                                new Array('action','Action','width="5%"','align="center"',false)
                              );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Placement/Company/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddCompanyDiv'; // div container name  
editFormName   = 'EditCompanyDiv'; // div container name
winLayerWidth  = 650; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteCompany';
divResultName  = 'results';
page=1; //default page
sortField = 'companyName';
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
    var fieldsArray = new Array(
            new Array("companyName","<?php echo ENTER_PLACEMENT_COMPANY_NAME; ?>"),
            new Array("companyCode","<?php echo ENTER_PLACEMENT_COMPANY_CODE; ?>"),
            new Array("contactAddress","<?php echo ENTER_PLACEMENT_COMPANY_ADDRESS; ?>"),
            new Array("contactPerson","<?php echo ENTER_PLACEMENT_COMPANY_CONTACT_PERSON; ?>"),
            new Array("designation","<?php echo ENTER_PLACEMENT_COMPANY_PERSON_DESIGNATION; ?>"),
            //new Array("landline","<?php echo ENTER_PLACEMENT_COMPANY_LANDLINE; ?>"),
            new Array("mobileNo","<?php echo ENTER_PLACEMENT_COMPANY_MOBILE_NO; ?>"), 
            new Array("emailId","<?php echo ENTER_PLACEMENT_COMPANY_EMAIL_ID; ?>") 
            //new Array("remarks","<?php echo ENTER_PLACEMENT_COMPANY_REMARKS; ?>")
    );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
          if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
        }
        if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='companyName' ){ 
            messageBox("<?php echo ENTER_PLACEMENT_COMPANY_NAME_LENGTH; ?>");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
        }
        if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='companyCode' ){ 
            messageBox("<?php echo ENTER_PLACEMENT_COMPANY_CODE_LENGTH; ?>");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
        }
        if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<5 && fieldsArray[i][0]=='contactAddress' ){ 
            messageBox("<?php echo ENTER_PLACEMENT_COMPANY_ADDRESS_LENGTH; ?>");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
        }
        if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='contactPerson' ){ 
            messageBox("<?php echo ENTER_PLACEMENT_COMPANY_PERSON_LENGTH; ?>");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
        }
        if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='designation' ){ 
            messageBox("<?php echo ENTER_PLACEMENT_COMPANY_DESIGNATION_LENGTH; ?>");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
        }
		// if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<10 && fieldsArray[i][0]=='mobileNo' ){ 
		if(fieldsArray[i][0]=="mobileNo"){

		 
		   if(!isPhone(eval("frm."+(fieldsArray[i][0])+".value"))){  //if not valid email format
			   messageBox("<?php echo ENTER_VALID_MOBILE_NUMBER; ?>");
			   eval("frm."+(fieldsArray[i][0])+".focus();");
			   return false;
			}
		}
		if(fieldsArray[i][0]=="emailId"){
            if(!isEmail(eval("frm."+(fieldsArray[i][0])+".value"))){ //if not valid email format
                 messageBox("<?php echo ENTER_VALID_EMAIL;  ?>");
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
			}
		}
    }
    if(act=='Add') {
        addCompany();
        //return false;
    }
    else if(act=='Edit') {
        editCompany();
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
function addCompany() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Placement/Company/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 companyName: trim(document.AddCompany.companyName.value), 
                 companyCode: trim(document.AddCompany.companyCode.value),  
                 contactAddress: trim(document.AddCompany.contactAddress.value), 
                 contactPerson: trim(document.AddCompany.contactPerson.value),
                 designation: trim(document.AddCompany.designation.value), 
                 landline: trim(document.AddCompany.landline.value), 
                 mobileNo: trim(document.AddCompany.mobileNo.value),
                 emailId: trim(document.AddCompany.emailId.value),
                 industryType: (document.AddCompany.industryType[0].checked==true?1:0),
                 remarks: trim(document.AddCompany.remarks.value),
                 isActive: (document.AddCompany.isActive[0].checked==true?1:0)
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
                             hiddenFloatingDiv('AddCompanyDiv');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         }
                     }
                     else if("<?php echo PLACEMENT_COMPANY_NAME_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo PLACEMENT_COMPANY_NAME_ALREADY_EXIST ;?>"); 
                         document.AddCompany.companyName.focus();
                     } 
                     else if("<?php echo PLACEMENT_COMPANY_CODE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo PLACEMENT_COMPANY_CODE_ALREADY_EXIST ;?>"); 
                         document.AddCompany.companyCode.focus();
                     }
                     else if("<?php echo PLACEMENT_COMPANY_EMAIL_ID_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo PLACEMENT_COMPANY_EMAIL_ID_ALREADY_EXIST ;?>"); 
                         document.AddCompany.emailId.focus();
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
               
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


//--------------------------------------------------------   
//THIS FUNCTION IS USED TO DELETE AN INSTITUTE
//  id=universityId
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteCompany(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Placement/Company/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 companyId: id
             },
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
   document.AddCompany.reset();
   document.AddCompany.companyName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A INSTITUTE
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editCompany() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Placement/Company/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 companyId: (document.EditCompany.companyId.value), 
                 companyName: trim(document.EditCompany.companyName.value), 
                 companyCode: trim(document.EditCompany.companyCode.value),  
                 contactAddress: trim(document.EditCompany.contactAddress.value), 
                 contactPerson: trim(document.EditCompany.contactPerson.value),
                 designation: trim(document.EditCompany.designation.value), 
                 landline: trim(document.EditCompany.landline.value), 
                 mobileNo: trim(document.EditCompany.mobileNo.value),
                 emailId: trim(document.EditCompany.emailId.value),
                 industryType: (document.EditCompany.industryType[0].checked==true?1:0),
                 remarks: trim(document.EditCompany.remarks.value),
                 isActive: (document.EditCompany.isActive[0].checked==true?1:0)
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditCompanyDiv');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo PLACEMENT_COMPANY_NAME_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo PLACEMENT_COMPANY_NAME_ALREADY_EXIST ;?>"); 
                         document.EditCompany.companyName.focus();
                     } 
                     else if("<?php echo PLACEMENT_COMPANY_CODE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo PLACEMENT_COMPANY_CODE_ALREADY_EXIST ;?>"); 
                         document.EditCompany.companyCode.focus();
                     }
                     else if("<?php echo PLACEMENT_COMPANY_EMAIL_ID_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo PLACEMENT_COMPANY_EMAIL_ID_ALREADY_EXIST ;?>"); 
                         document.EditCompany.emailId.focus();
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
         
         document.EditCompany.reset();
         
         var url = '<?php echo HTTP_LIB_PATH;?>/Placement/Company/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 companyId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   if(trim(transport.responseText)==0) {
                       hiddenFloatingDiv('EditCompanyDiv');
                       messageBox("<?php echo PLACEMENT_COMPANY_NOT_EXIST; ?>");
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }
                   var j = trim(transport.responseText).evalJSON();                    
                   document.EditCompany.companyName.value=j.companyName;
                   document.EditCompany.companyCode.value=j.companyCode;
                   document.EditCompany.contactAddress.value=j.contactAddress;
                   document.EditCompany.contactPerson.value=j.contactPerson;
                   document.EditCompany.designation.value=j.designation;
                   document.EditCompany.landline.value=j.landline;
                   document.EditCompany.mobileNo.value=j.mobileNo;
                   document.EditCompany.emailId.value=j.emailId;
                   if(j.industryType==1){
                       document.EditCompany.industryType[0].checked=true;
                   }
                   else{
                      document.EditCompany.industryType[1].checked=true; 
                   }
                   document.EditCompany.remarks.value=j.remarks;
                   if(j.isActive==1){
                       document.EditCompany.isActive[0].checked=true;
                   }
                   else{
                      document.EditCompany.isActive[1].checked=true; 
                   }
                   
                   document.EditCompany.companyId.value=j.companyId;
                   document.EditCompany.companyName.focus();
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}


/* function to print university report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/Placement/companyReportPrint.php?'+qstr;
    window.open(path,"CompanyReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='<?php echo UI_HTTP_PATH;?>/Placement/companyReportCSV.php?'+qstr;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Placement/Company/listCompanyContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listCompany.php $ 
?>