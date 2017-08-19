<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF BUSSTOP ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TransportStuffMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/TransportStuff/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Transport Staff Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
    new Array('srNo','#','width="2%"','',false),
    new Array('name','Staff','width="12%"','',true),
    new Array('stuffCode','Code','width="5%"','',true),
    new Array('joiningDate','Joining Date','width="8%"','align="center"',true),
    new Array('stuffType','Type','width="8%"','align="left"',true),
    new Array('dlNo','License','width="8%"','',true) , 
    new Array('dlIssuingAuthority','Authority','width="10%"','align="left"',true), 
    new Array('dlExpiryDate','Expiry Date','width="8%"','align="center"',true), 
    new Array('action','Action','width="5%"','align="right"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/TransportStuff/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddTransportStuff';   
editFormName   = 'EditTransportStuff';
winLayerWidth  = 360; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteTransportStuff';
divResultName  = 'results';
page=1; //default page
sortField = 'name';
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
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d'); ?>";

function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
      new Array("stuffName","<?php echo ENTER_STUFF_NAME; ?>"),
      new Array("stuffCode","<?php echo ENTER_STUFF_CODE; ?>"),
      new Array("dlNo","<?php echo ENTER_DRIVING_LICENSE; ?>"),
      new Array("dlAuthority","<?php echo ENTER_DRIVING_LICENSE_AUTHORITY; ?>"),
      new Array("stuffType","<?php echo SELECT_STUFF_TYPE; ?>")
    );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            if(!isAlphabetCharacters(trim(eval("frm."+(fieldsArray[i][0])+".value"))) && fieldsArray[i][0]=='stuffName' ){
                messageBox("<?php echo ENTER_ALPHABETS; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
             }
            if((eval("frm."+(fieldsArray[i][0])+".value.length"))<3 && fieldsArray[i][0]=='stuffName' ) {
                messageBox("<?php echo STUFF_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if((eval("frm."+(fieldsArray[i][0])+".value.length"))<2 && fieldsArray[i][0]=='stuffCode' ) {
                messageBox("<?php echo STUFF_CODE_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if((eval("frm."+(fieldsArray[i][0])+".value.length"))<4 && fieldsArray[i][0]=='dlNo' ) {
                messageBox("<?php echo DL_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
         
        }
     
    }
    
    if(act=='Add') {
      if(!dateDifference(document.getElementById('join1').value,serverDate,'-')){
         messageBox("<?php echo JOINING_DATE_VALIDATION; ?>");
         document.getElementById('join1').focus();
         return false; 
      }  
        addTransportStuff();
        return false;
    }
    else if(act=='Edit') {
      if(!dateDifference(document.getElementById('join2').value,serverDate,'-')){
         messageBox("<?php echo JOINING_DATE_VALIDATION; ?>");
         document.getElementById('join2').focus();
         return false; 
      }    
        editTransportStuff();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A BUS
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addTransportStuff() {
         url = '<?php echo HTTP_LIB_PATH;?>/TransportStuff/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 stuffName:     trim(document.AddTransportStuff.stuffName.value),
                 stuffCode:     trim(document.AddTransportStuff.stuffCode.value),
                 dlNo:          trim(document.AddTransportStuff.dlNo.value),
                 dlAuthority:   trim(document.AddTransportStuff.dlAuthority.value),
                 dlExp:         (document.AddTransportStuff.dlExp1.value),
                 join:          (document.AddTransportStuff.join1.value),
                 stuffType:     (document.AddTransportStuff.stuffType.value)
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
                             hiddenFloatingDiv('AddTransportStuff');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     } 
                     else if("<?php echo STUFF_CODE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo STUFF_CODE_ALREADY_EXIST ;?>"); 
                       document.AddTransportStuff.stuffCode.focus();
                     }  
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A BUSSTOP
//  id=busStopId
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteTransportStuff(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/TransportStuff/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {stuffId: id},
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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "addTransportStuff" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddTransportStuff.reset();  
   document.AddTransportStuff.stuffName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A BUSSTOP
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editTransportStuff() {
         url = '<?php echo HTTP_LIB_PATH;?>/TransportStuff/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
              stuffId:      (document.EditTransportStuff.stuffId.value),
              stuffName:    trim(document.EditTransportStuff.stuffName.value),
              stuffCode:    trim(document.EditTransportStuff.stuffCode.value),
              dlNo:         trim(document.EditTransportStuff.dlNo.value),
              dlAuthority:  trim(document.EditTransportStuff.dlAuthority.value),
              dlExp:        (document.EditTransportStuff.dlExp2.value),
              join:         (document.EditTransportStuff.join2.value),
              stuffType:    (document.EditTransportStuff.stuffType.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditTransportStuff');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                   else if("<?php echo STUFF_CODE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo STUFF_CODE_ALREADY_EXIST ;?>"); 
                       document.EditTransportStuff.stuffCode.focus();
                     }  
                     else {
                        messageBox(trim(transport.responseText));                         
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditTransportStuff" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/TransportStuff/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {stuffId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditTransportStuff');
                        messageBox("<?php echo STUFF_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                   }

                   j = eval('('+transport.responseText+')');
                  
                   document.EditTransportStuff.stuffName.value = j.name;
                   document.EditTransportStuff.stuffCode.value = j.stuffCode;
                   document.EditTransportStuff.dlNo.value = j.dlNo;
                   document.EditTransportStuff.dlAuthority.value = j.dlIssuingAuthority;
                   document.EditTransportStuff.dlExp2.value = j.dlExpiryDate;
                   document.EditTransportStuff.join2.value = j.joiningDate;
                   document.EditTransportStuff.stuffType.value=j.stuffType;
                   
                   document.EditTransportStuff.stuffId.value =j.stuffId;
                   
                   document.EditTransportStuff.stuffName.focus();
             },
              onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/TransportStuff/listTransportStuffContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
    //This function will populate list
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>

<?php 
// $History: listTransportStuff.php $ 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:35
//Created in $/LeapCC/Interface
//Added Files for bus modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:00
//Created in $/Leap/Source/Interface
//Added Bus Modules
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:08a
//Updated in $/SnS/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/02/09   Time: 11:44
//Updated in $/SnS/Interface
//Modified look and feel
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 16:46
//Created in $/SnS/Interface
//Created module Transport Stuff Master
?>