<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Subject Form
//
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PartyMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();


//require_once(BL_PATH . "/Subject/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Create Suppliers </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                        new Array('srNo','#','width="4%"align="left"',false), 
                        new Array('partyName','Name','width="12%"','align="left"',true),
                        new Array('partyCode','Code','width="12%"','align="left"',true),
						new Array('partyAddress','Address','width="20%"','align="left"',true),
						new Array('partyPhones','Phones','width="15%"','align="right"',true),
						new Array('partyFax','Fax','width="15%"','align="right"',true),
						new Array('action','Action','width="10%"','align="center"',false)
                       );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/PartyMaster/ajaxInitPartyList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'PartyActionDiv';   
editFormName   = 'PartyActionDiv';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteParty';
divResultName  = 'results';
page=1; //default page
sortField = 'partyName';
sortOrderBy    = 'ASC';

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      
//This function Displays Div Window
// ajax search results ---end ///
//This function Displays Div Window
function editWindow(id,dv,w,h) {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Party';
    displayWindow(dv,w,h);
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
    
   
    var fieldsArray = new Array(new Array("partyName","<?php echo ENTER_PARTY_NAME ?>"),
								new Array("partyCode","<?php echo ENTER_PARTY_CODE ?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        /*else if((fieldsArray[i][0]=='abbr') && !isAlphaNumericCustom(trim(eval("frm."+(fieldsArray[i][0])+".value")),"-/") ) {
                alert("<?php echo ENTER_ALPHABETS_NUMERIC2; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            } */
     }
  
    if(document.getElementById('partyId').value=='') {
		if(!isPhone(document.getElementById('partyPhones').value)) {
			messageBox("<?php echo NOT_VALID_PHONE ?>");
			document.getElementById('partyPhones').focus();
			return false;
		}
		if(!isPhone(document.getElementById('partyFax').value)) {
			messageBox("<?php echo NOT_VALID_FAX ?>");
			document.getElementById('partyFax').focus();
			return false;
		}
        addParty();
        return false;
    }
    else{
		if(!isPhone(document.getElementById('partyPhones').value)) {
			messageBox("<?php echo NOT_VALID_PHONE ?>");
			document.getElementById('partyPhones').focus();
			return false;
		}
		if(!isPhone(document.getElementById('partyFax').value)) {
			messageBox("<?php echo NOT_VALID_FAX ?>");
			document.getElementById('partyFax').focus();
			return false;
		}
		editParty();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addItemCategory() IS USED TO ADD NEW Item CATEGORY
//
//Author : Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addParty() {
		
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/PartyMaster/ajaxInitPartyAdd.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                partyName:   trim(document.PartyDetail.partyName.value),
                partyCode:   trim(document.PartyDetail.partyCode.value),
				partyAddress:   trim(document.PartyDetail.partyAddress.value),
				partyPhones:   trim(document.PartyDetail.partyPhones.value),
				partyFax:   trim(document.PartyDetail.partyFax.value)
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
                             hiddenFloatingDiv('PartyActionDiv');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
					 
					 else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo PARTY_NAME_ALREADY_EXIST; ?>'){
							document.PartyDetail.partyName.focus();
						}
						if (trim(transport.responseText)=='<?php echo PARTY_CODE_ALREADY_EXIST; ?>'){
							document.PartyDetail.partyCode.focus();
						}

                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A ITEM CATEGORY
//  id=categoryId
//Author : Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteParty(id) {
    
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/PartyMaster/ajaxInitPartyDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {partyId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "ADDPERIODSLOT" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Add Party';
	document.PartyDetail.partyName.value = '';
    document.PartyDetail.partyCode.value = '';
	document.PartyDetail.partyAddress.value = '';
	document.PartyDetail.partyPhones.value = '';
	document.PartyDetail.partyFax.value = '';
	document.getElementById('partyId').value='';
	document.PartyDetail.partyName.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A ITEM CATEGORY
//
//Author : Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editParty() {
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/PartyMaster/ajaxInitPartyEdit.php';
          
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
					partyId:		(document.PartyDetail.partyId.value),
					partyName:		trim(document.PartyDetail.partyName.value),
                    partyCode:		trim(document.PartyDetail.partyCode.value),
					partyAddress:	trim(document.PartyDetail.partyAddress.value),
					partyPhones:	trim(document.PartyDetail.partyPhones.value),
					partyFax:		trim(document.PartyDetail.partyFax.value)
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('PartyActionDiv');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
						 return false;
                       }
                   else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo PARTY_NAME_ALREADY_EXIST; ?>'){
							document.PartyDetail.partyName.focus();
						}
						if (trim(transport.responseText)=='<?php echo PARTY_CODE_ALREADY_EXIST; ?>'){
							document.PartyDetail.partyCode.focus();
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
// Created on : (08.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/PartyMaster/ajaxPartyGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {partyId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('PartyActionDiv');
                        messageBox("<?php echo PARTY_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);         
                   }
                   j = eval('('+trim(transport.responseText)+')');
                  
                   document.PartyDetail.partyName.value		= j.partyName;
                   document.PartyDetail.partyCode.value		= j.partyCode;
				   document.PartyDetail.partyAddress.value  = j.partyAddress;
				   document.PartyDetail.partyPhones.value	= j.partyPhones;
				   document.PartyDetail.partyFax.value		= j.partyFax;
		           document.PartyDetail.partyId.value		= j.partyId;
                   document.PartyDetail.partyName.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printPartyReport() {
	
	//sortField = listObj.sortField;
	// = listObj.sortOrderBy;

	var path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayPartyReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"itemCategoryReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

/* function to output data to a CSV*/
function printCSV() {

	//sortField = listObj.sortField;
//	sortOrderBy = listObj.sortOrderBy;

    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayPartyReportCSV.php?'+qstr;
	window.location = path;
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(INVENTORY_TEMPLATES_PATH . "/PartyMaster/listPartyContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
    <!--
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
</SCRIPT>        
</body>
</html>
