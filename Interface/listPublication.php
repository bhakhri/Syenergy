<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PublicationMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Country/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Publication Master</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script type="text/javascript">
 
 // ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
                               new Array('publicationName','Publication Name','width="35%"','',true) ,  
                               new Array('action','Action','width="7%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Publication/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddPublication';   
editFormName   = 'EditPublication';
winLayerWidth  = 300; //  add/edit form width
winLayerHeight = 200; // add/edit form height
deleteFunction = 'return deletePublication';
divResultName  = 'results';
page=1; //default page
sortField = 'publicationName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
 
 var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button       
//This function Displays Div Window

function editWindow(id,dv,w,h) { 
    displayWindow(dv,w,h);
    populateValues(id);   
}

//This function Validates Form 


function validateAddForm(frm, act) {
        
   
    var fieldsArray = new Array(new Array("publicationName","<?php echo ENTER_PUBLICATION_NAME;?>"));
                               
    if(act=='Add') {
       document.addPublicationForm.publicationName.value = trim(document.addPublicationForm.publicationName.value);
   
    }
    else {
       document.editPublicationForm.publicationName.value = trim(document.editPublicationForm.publicationName.value);
     
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
		    if((eval("frm."+(fieldsArray[i][0])+".value.length"))<3 && fieldsArray[i][0]=='publicationName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo PUBLICATION_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
   /*       if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value")) && (fieldsArray[i][0]=='countryName' || fieldsArray[i][0]=='nationalityName') ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_ALPHABETS_CHAR;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
     */                   
       }
    }
    if(act=='Add') {
        addPublication();
        return false;
    }
    else if(act=='Edit') {
        editPublication();    
        return false;
    }
}

//This function adds form through ajax 

                                                                 
function addPublication() {
         url = '<?php echo HTTP_LIB_PATH;?>/Publication/ajaxInitAdd.php';
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {publicationName: (document.addPublicationForm.publicationName.value)},
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
                             hiddenFloatingDiv('AddPublication');
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
   document.addPublicationForm.publicationName.value = '';
   document.addPublicationForm.publicationName.focus();
}

//This function edit form through ajax                   

function editPublication() {
         url = '<?php echo HTTP_LIB_PATH;?>/Publication/ajaxInitEdit.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {publicationId: (document.editPublicationForm.publicationId.value), publicationName:(document.editPublicationForm.publicationName.value)},
            onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
            
                     hideWaitDialog(true);
                 
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditPublication');
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

function deletePublication(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Publication/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {publicationId: id},
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
         url = '<?php echo HTTP_LIB_PATH;?>/Publication/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {publicationId: id},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
          
                    hideWaitDialog(true);
                    j = eval('('+transport.responseText+')');
                     
                   document.editPublicationForm.publicationName.value = j.publicationName;
                   
                   document.editPublicationForm.publicationId.value = id;
               
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/listPublicationPrint.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"CountryReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listPublicationCSV.php?'+qstr;
    window.location = path;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Publication/listPublicationContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
</body>
</html>


