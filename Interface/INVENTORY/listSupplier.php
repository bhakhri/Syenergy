<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Offense ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Gurkeerat Sidhu
// Created on : (06.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SupplierMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Supplier Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                        new Array('srNo','#','width="4%"','align="left"',false), 
                        new Array('companyName','Company Name','width="15%"','align="left"',true),
                        new Array('supplierCode','Supplier Code','width="10%"','align="left"',true),
                        new Array('address','Address','width="15%"','align="left"',true),
                        new Array('countryName','Country','width="10%"','align="left"',true),
                        new Array('stateName','State','width="10%"','align="left"',true),
                        new Array('cityName','City','width="10%"','align="left"',true),
                        new Array('contactPerson','Contact Person','width="10%"','align="left"',true),
                        new Array('contactPersonPhone','Contact Person Phone','width="10%"','align="left"',true),
                        new Array('companyPhone','Company Phone','width="10%"','align="left"',true),
                        new Array('action','Action','width="5%"','align="right"',false)
                       );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/Supplier/ajaxInitSupplierList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddSupplierDetail';   
editFormName   = 'EditSupplierDetail';
winLayerWidth  = 370; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteSupplier';
divResultName  = 'results';
page=1; //default page
sortField = 'companyName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
// ajax search results ---end ///

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Gurkeerat Sidhu
// Created on : (06.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id,dv,w,h) {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Supplier';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues(id);   
}
//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Gurkeerat Sidhu
// Created on : (06.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
    
   
    var fieldsArray = new Array(new Array("companyName","<?php echo ENTER_COMPANY_NAME ?>"),
                                new Array("supplierCode","<?php echo ENTER_SUPPLIER_CODE ?>"),
                                new Array("address","<?php echo ENTER_ADDRESS1 ?>"),
                                new Array("countryId","<?php echo SELECT_COUNTRY ?>"),
                                new Array("stateId","<?php echo SELECT_STATE ?>"),
                                new Array("cityId","<?php echo SELECT_CITY ?>"),
                                new Array("contactPerson","<?php echo ENTER_CONTACT_PERSON_NAME ?>"),
                                new Array("contactPersonPhone","<?php echo ENTER_CONTACT_PERSON_PHONE ?>"),
                                new Array("companyPhone","<?php echo ENTER_COMPANY_PHONE ?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else if(fieldsArray[i][0]=="contactPersonPhone"){
            if(!isPhone(eval("frm."+(fieldsArray[i][0])+".value"))) { //if not valid phone format
                 messageBox("<?php echo VALID_PHONE ?>"); 
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }
        }
        else if(fieldsArray[i][0]=="companyPhone"){
            if(!isPhone(eval("frm."+(fieldsArray[i][0])+".value"))) { //if not valid phone format
                 messageBox("<?php echo VALID_PHONE ?>"); 
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }
        }
   }
  
    if(act=='Add') {
        addSupplier();
        return false;
    }
    else if(act=='Edit'){
		editSupplier();
        return false;
    }
}

/*function emptySlotId() {
	document.getElementById('offenseId').value='';
}*/

//-------------------------------------------------------
//THIS FUNCTION addSupplier() IS USED TO ADD NEW SUPPLIER
//
//Author : Gurkeerat Sidhu
// Created on : (06.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addSupplier() {
		
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/Supplier/ajaxInitSupplierAdd.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                companyName:            trim(document.AddSupplierDetail.companyName.value),
                supplierCode:           trim(document.AddSupplierDetail.supplierCode.value),
                address:                trim(document.AddSupplierDetail.address.value),
                countryId:              trim(document.AddSupplierDetail.countryId.value),
                stateId:                trim(document.AddSupplierDetail.stateId.value),
                cityId:                 trim(document.AddSupplierDetail.cityId.value),
                contactPerson:          trim(document.AddSupplierDetail.contactPerson.value),
                contactPersonPhone:     trim(document.AddSupplierDetail.contactPersonPhone.value),
                companyPhone:           trim(document.AddSupplierDetail.companyPhone.value)
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('AddSupplierDetail');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
					 
					 else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo COMPANY_EXIST; ?>'){
							document.AddSupplierDetail.companyName.value="";
							document.AddSupplierDetail.companyName.focus();
						}
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A SUPPLIER
//  id=supplierId
//Author : Gurkeerat Sidhu
// Created on : (06.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteSupplier(id) {
    
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/Supplier/ajaxInitSupplierDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {supplierId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
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

//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "ADDSUPPLIER" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (06.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
   document.getElementById('supplierId').value='';
   document.getElementById('divHeaderId').innerHTML='&nbsp; Add Supplier'; 
   document.AddSupplierDetail.stateId.value='';
   document.AddSupplierDetail.stateId.length=0;
   addOption(document.AddSupplierDetail.stateId,'','Select');
   document.AddSupplierDetail.cityId.value='';
   document.AddSupplierDetail.cityId.length=0;
   addOption(document.AddSupplierDetail.cityId,'','Select');
   document.AddSupplierDetail.reset();
   document.AddSupplierDetail.companyName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A SUPPLIER DETAIL
//
//Author : Gurkeerat Sidhu
// Created on : (06.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editSupplier() {
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/Supplier/ajaxInitSupplierEdit.php';
          
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
					supplierId:             (document.EditSupplierDetail.supplierId.value),
					companyName:            trim(document.EditSupplierDetail.companyName.value),
                    supplierCode:           trim(document.EditSupplierDetail.supplierCode.value),
                    address:                trim(document.EditSupplierDetail.address.value),
                    countryId:              trim(document.EditSupplierDetail.countryId.value),
                    stateId:                trim(document.EditSupplierDetail.stateId.value),
                    cityId:                 trim(document.EditSupplierDetail.cityId.value),
                    contactPerson:          trim(document.EditSupplierDetail.contactPerson.value),
                    contactPersonPhone:     trim(document.EditSupplierDetail.contactPersonPhone.value),
                    companyPhone:           trim(document.EditSupplierDetail.companyPhone.value)
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditSupplierDetail');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
						 return false;
                     }
                   else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo COMPANY_EXIST; ?>'){
							document.EditSupplierDetail.companyName.value="";
							document.EditSupplierDetail.companyName.focus();
						}
                        
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EDITOFFENSEs" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (06.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
    
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/Supplier/ajaxSupplierGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {supplierId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditSupplierDetail');
                        messageBox("<?php echo SUPPLIER_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);           
                   }
                   j = eval('('+trim(transport.responseText)+')');

                  
                   document.EditSupplierDetail.companyName.value	= j.edit[0].companyName;
                   document.EditSupplierDetail.supplierCode.value    = j.edit[0].supplierCode;
                   document.EditSupplierDetail.address.value   = j.edit[0].address;
                   document.EditSupplierDetail.countryId.value   = j.edit[0].countryId; 
                   // populate states as per country id 
                   len = j.state.length;
                   document.EditSupplierDetail.stateId.length = null;
                   // add option Select initially
                   addOption(document.EditSupplierDetail.stateId, '', 'Select');
                   for(i=0;i<len;i++) { 
                     addOption(document.EditSupplierDetail.stateId, j.state[i].stateId, j.state[i].stateName);
                   }
                   // now select the value
                   document.EditSupplierDetail.stateId.value = j.edit[0].stateId; 
                   // populate cities as per state id
                   len = j.city.length;
                   document.EditSupplierDetail.cityId.length = null;
                   // add option Select initially
                   addOption(document.EditSupplierDetail.cityId, '', 'Select');
                   for(i=0;i<len;i++) { 
                     addOption(document.EditSupplierDetail.cityId, j.city[i].cityId, j.city[i].cityName);
                   }
                   // now select the value
                   document.EditSupplierDetail.cityId.value = j.edit[0].cityId;
                   document.EditSupplierDetail.contactPerson.value   = j.edit[0].contactPerson;
                   document.EditSupplierDetail.contactPersonPhone.value   = j.edit[0].contactPersonPhone;
                   document.EditSupplierDetail.companyPhone.value   = j.edit[0].companyPhone;
                   document.EditSupplierDetail.supplierId.value		= j.edit[0].supplierId;
                   document.EditSupplierDetail.companyName.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}




//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "states/city" select box depending upon which country/state is selected
//
//Author : Gurkeerat Sidhu
// Created on : (06.05.2009)
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
            document.AddSupplierDetail.stateId.options.length=0;
            var objOption = new Option("Select","");
            document.AddSupplierDetail.stateId.options.add(objOption); 
            
            var objOption = new Option("Select","");
            document.AddSupplierDetail.cityId.options.length=0;
            document.AddSupplierDetail.cityId.options.add(objOption); 
       }
      else {
            document.AddSupplierDetail.cityId.options.length=0;
            var objOption = new Option("Select","");
            document.AddSupplierDetail.cityId.options.add(objOption); 
      } 
   }
   else{
        if(type=="states"){
            document.EditSupplierDetail.stateId.options.length=0;
            var objOption = new Option("Select","");            
            document.EditSupplierDetail.stateId.options.add(objOption); 
            
            document.EditSupplierDetail.cityId.options.length=0;
            var objOption = new Option("Select","");            
            document.EditSupplierDetail.cityId.options.add(objOption); 
       }
      else {
           document.EditSupplierDetail.cityId.options.length=0;
           var objOption = new Option("Select","");          
           document.EditSupplierDetail.cityId.options.add(objOption);
      } 
   }
 
 new Ajax.Request(url,
           {
             method:'post',
             parameters: {type: type,id: val},
             
             onCreate: function() {
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                  hideWaitDialog(true);
                    j = eval('('+transport.responseText+')'); 

                    for(var c=0;c<j.length;c++){
                         if(frm=="Add"){
                             if(type=="states"){
                                var objOption = new Option(j[c].stateName,j[c].stateId);
                                 document.AddSupplierDetail.stateId.options.add(objOption); 
                             }
                            else{
                                 var objOption = new Option(j[c].cityName,j[c].cityId);
                                 document.AddSupplierDetail.cityId.options.add(objOption); 
                            } 
                          }
                      else{
                            if(type=="states"){
                                 var objOption = new Option(j[c].stateName,j[c].stateId);
                                 document.EditSupplierDetail.stateId.options.add(objOption); 
                             }
                            else{
                                 var objOption = new Option(j[c].cityName,j[c].cityId);
                                 document.EditSupplierDetail.cityId.options.add(objOption); 
                            } 
                          }
                     }
                     
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           }); 
}

</script>

</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(INVENTORY_TEMPLATES_PATH . "/Supplier/listSupplierContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	
</body>
</html>
<script language="javascript">
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>