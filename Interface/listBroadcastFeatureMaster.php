<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Country Form
//
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BroadcastFeature');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Broadcast Feature Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 

<script type="text/javascript">
 
 // ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
                               new Array('countryName','Feature Title','width="25%"','',true) , 
                               new Array('countryCode','Feature Description','width="20%"','',true), 
                               new Array('nationalityName','From Date','width="15%"','',true), 
           				       new Array('countryName','To Date','width="15%"','',true) , 
                               new Array('countryCode','Menu Path','width="30%"','',true), 
                               new Array('nationalityName','Attachement','width="15%"','',true),
                               new Array('action','Action','width="7%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/BroadcastFeature/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddCountry';   
editFormName   = 'EditCountry';
winLayerWidth  = 300; //  add/edit form width
winLayerHeight = 200; // add/edit form height
deleteFunction = 'return deleteCountry';
divResultName  = 'results';
page=1; //default page
sortField = 'featureTitle';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
 
 var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button       
//This function Displays Div Window
/*
function editWindow(id,dv,w,h) { 
   // displayWindow(dv,w,h);
   // populateValues(id);   
}

//This function Validates Form 

function validateAddForm(frm, act) {
        
   
    var fieldsArray = new Array(new Array("countryName","<?php echo ENTER_COUNTRY_NAME;?>"),
                                new Array("countryCode","<?php echo ENTER_COUNTRY_CODE;?>"),
                                new Array("nationalityName","<?php echo ENTER_NATIONALITY;?>"));
                                
    if(act=='Add') {
       document.addCountry.countryName.value = trim(document.addCountry.countryName.value);
       document.addCountry.countryCode.value = trim(document.addCountry.countryCode.value);
       document.addCountry.nationalityName.value = trim(document.addCountry.nationalityName.value);
    }
    else {
       document.editCountry.countryName.value = trim(document.editCountry.countryName.value);
       document.editCountry.countryCode.value = trim(document.editCountry.countryCode.value);
       document.editCountry.nationalityName.value = trim(document.editCountry.nationalityName.value);
    }                                

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
		    if((eval("frm."+(fieldsArray[i][0])+".value.length"))<3 && fieldsArray[i][0]=='countryName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo COUNTRY_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
         if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value")) && (fieldsArray[i][0]=='countryName' || fieldsArray[i][0]=='nationalityName') ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_ALPHABETS_CHAR;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
                      
       }
    }
    if(act=='Add') {
        addCountry();
        return false;
    }
    else if(act=='Edit') {
        editCountry();    
        return false;
    }
}

//This function adds form through ajax 

                                                                 
function addCountry() {
         url = '<?php echo HTTP_LIB_PATH;?>/Country/ajaxInitAdd.php';
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {countryName: (document.addCountry.countryName.value), countryCode: (document.addCountry.countryCode.value), nationalityName: (document.addCountry.nationalityName.value)},
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
                             hiddenFloatingDiv('AddCountry');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
               
             },
			  onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function blankValues() {
   document.addCountry.countryCode.value = '';
   document.addCountry.countryName.value = '';
   document.addCountry.nationalityName.value = ''; 
   document.addCountry.countryName.focus();
}

//This function edit form through ajax                   

function editCountry() {
         url = '<?php echo HTTP_LIB_PATH;?>/Country/ajaxInitEdit.php';
           
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {countryId: (document.editCountry.countryId.value), countryName: (document.editCountry.countryName.value), nationalityName: (document.editCountry.nationalityName.value), countryCode: (document.editCountry.countryCode.value)},
            onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
            
                     hideWaitDialog(true);
                 
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditCountry');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                     else {
                         messageBox(trim(transport.responseText));
                     }
              
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//This function calls delete function through ajax

function deleteCountry(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Country/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {countryId: id},
             onCreate: function() {
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


//This function populates values in edit form through ajax 

function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Country/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {countryId: id},
            onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
          
                    hideWaitDialog(true);
                    j = eval('('+transport.responseText+')');
                     
                   document.editCountry.countryCode.value = j.countryCode;
                   
                   document.editCountry.countryName.value = j.countryName;
                   document.editCountry.nationalityName.value = j.nationalityName; 
                   
                   document.editCountry.countryId.value = j.countryId;
               
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/listCountryPrint.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"CountryReport","status=1,menubar=1,scrollbars=1, width=900");
}


function printReportCSV() {
    
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listCountryCSV.php?'+qstr;
    window.location = path;
}
*/
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/BroadcastFeature/listBroadcastFeatureContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
</body>
</html>

<?php 



?>
