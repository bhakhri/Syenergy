<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF HOSTEL VISITOR ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Gurkeerat Sidhu
// Created on : (18.4.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelVisitor');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/HostelVisitor/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Visitor Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','align="left"',false), 
new Array('visitorName','Visitor Name','width=15%','',true) , 
new Array('toVisit','To Visit','width=10%','',true), 
new Array('address','Address','width=15%','',true), 
new Array('dateOfVisit','Date of Visit','width=12%','align="center"',true), 
new Array('timeOfVisit','Time','width=10%','align="center"',false),
new Array('purpose','Purpose','width=15%','',true),
new Array('contactNo','Contact No.','width=12%','align=right',true),
new Array('relation','Relation','width=10%','',true),
new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/HostelVisitor/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddHostelVisitor';   
editFormName   = 'EditHostelVisitor';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteHostelVisitor';
divResultName  = 'results';
page=1; //default page
sortField = 'visitorName';
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
//Author : Gurkeerat Sidhu
// Created on : (18.4.2009)
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
//Author : Gurkeerat Sidhu
// Created on : (18.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var cdate="<?php echo date('Y-m-d'); ?>";
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("visitorName","<?php echo ENTER_VISITOR_NAME ?>"),
    new Array("address","<?php echo ENTER_ADDRESS ?>"),
    new Array("timeOfVisit","<?php echo ENTER_TIME ?>"),
    new Array("toVisit","<?php echo ENTER_TO_VISIT ?>"),
    new Array("purpose","<?php echo ENTER_PURPOSE ?>"),
    new Array("contactNo","<?php echo ENTER_CONTACT ?>"),
    new Array("relation","<?php echo SELECT_RELATION ?>")
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
        else {
            //unsetAlertStyle(fieldsArray[i][0]);
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='visitorName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo VISITOR_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if(fieldsArray[i][0]=='timeOfVisit' && !isTime2(eval("frm."+(fieldsArray[i][0])+".value"))) {
                alert("<?php echo ENTER_SCHEDULE_TIME_NUM1; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
            if((fieldsArray[i][0]!='timeOfVisit' && fieldsArray[i][0]!='purpose' && fieldsArray[i][0]!='address' && fieldsArray[i][0]!='toVisit' && fieldsArray[i][0]!='contactNo') && !isAlphaNumeric(trim(eval("frm."+(fieldsArray[i][0])+".value"))) ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_ALPHABETS_NUMERIC; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }

			if(fieldsArray[i][0]=="contactNo"){
             if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))) {
                messageBox("<?php echo ENTER_NUMBER ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
             }
            }

			if(fieldsArray[i][0]=="visitorName"){
             if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value"))) {
                messageBox("<?php echo ENTER_ALPHABETS ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
             }
            }

			if(fieldsArray[i][0]=="toVisit"){
             if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value"))) {
                messageBox("<?php echo ENTER_ALPHABETS ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
             }
            }
            
            if((fieldsArray[i][0]=='purpose' || fieldsArray[i][0]=='address') && !isAlphaNumericCustom(trim(eval("frm."+(fieldsArray[i][0])+".value"))," #,/\\'.\n") ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_ALPHABETS_NUMERIC1; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
        }
    }
    
    if(act=='Add') {
        if(!dateDifference(document.AddHostelVisitor.visitDate1.value,cdate,'-')){
           messageBox("<?php echo FUTURE_DATE_VALIDATION; ?>");
           document.AddHostelVisitor.visitDate1.focus();
           return false;
        }  
        addHostelVisitor();
        return false;
    }
    else if(act=='Edit') {
        if(!dateDifference(document.EditHostelVisitor.visitDate2.value,cdate,'-')){
           messageBox("<?php echo FUTURE_DATE_VALIDATION; ?>");
           document.EditHostelVisitor.visitDate2.focus();
           return false;
        }  
        editHostelVisitor();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A VISITOR
//
//Author : Gurkeerat Sidhu
// Created on : (18.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addHostelVisitor() {
         url = '<?php echo HTTP_LIB_PATH;?>/HostelVisitor/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {  visitorName:               (document.AddHostelVisitor.visitorName.value), 
                            toVisit:                   (document.AddHostelVisitor.toVisit.value), 
                            address:                   (document.AddHostelVisitor.address.value), 
                            dateOfVisit:               (document.AddHostelVisitor.visitDate1.value), 
                            timeOfVisit:               (document.AddHostelVisitor.timeOfVisit.value),
                            purpose:                   (document.AddHostelVisitor.purpose.value),
                            contactNo:                 (document.AddHostelVisitor.contactNo.value),
                            relation:                  (document.AddHostelVisitor.relation.value) 
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
                             hiddenFloatingDiv('AddHostelVisitor');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=='<?php echo HOURS_LIMIT ?>'){
							//document.addHostel.hostelName.value='';
							document.AddHostelVisitor.timeOfVisit.focus();	
						}
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A VISITOR
//  id=visitorId
//Author : Gurkeerat Sidhu
// Created on : (18.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteHostelVisitor(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/HostelVisitor/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {visitorId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "addHostelVisitor" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (18.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.getElementById('visitorId').value='';
   document.getElementById('divHeaderId').innerHTML='&nbsp; Add Hostel Visitor'; 
   document.AddHostelVisitor.reset();
   document.AddHostelVisitor.visitorName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A HOSTEL VISITOR
//
//Author : Gurkeerat Sidhu
// Created on : (18.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editHostelVisitor() {
         url = '<?php echo HTTP_LIB_PATH;?>/HostelVisitor/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {  visitorId:                 (document.EditHostelVisitor.visitorId.value),
                            visitorName:               (document.EditHostelVisitor.visitorName.value), 
                            toVisit:                   (document.EditHostelVisitor.toVisit.value), 
                            address:                   (document.EditHostelVisitor.address.value), 
                            dateOfVisit:               (document.EditHostelVisitor.visitDate2.value), 
                            timeOfVisit:               (document.EditHostelVisitor.timeOfVisit.value),
                            purpose:                   (document.EditHostelVisitor.purpose.value),
                            contactNo:                 (document.EditHostelVisitor.contactNo.value),
                            relation:                  (document.EditHostelVisitor.relation.value) 
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditHostelVisitor');
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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditHostelVisitor" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (18.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/HostelVisitor/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {visitorId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditHostelVisitor');
                        messageBox("<?php echo VISITOR_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');
                   
                   document.EditHostelVisitor.visitorName.value = j.visitorName;
                   document.EditHostelVisitor.toVisit.value = j.toVisit;
                   document.EditHostelVisitor.address.value = j.address;
                   document.EditHostelVisitor.visitDate2.value = j.dateOfVisit;
                   document.EditHostelVisitor.timeOfVisit.value = j.timeOfVisit.substring(0,5);
                   document.EditHostelVisitor.purpose.value = j.purpose;
                   document.EditHostelVisitor.contactNo.value = j.contactNo;
                   document.EditHostelVisitor.relation.value = j.relation;
                   document.EditHostelVisitor.visitorId.value = j.visitorId;
                   document.EditHostelVisitor.visitorName.focus();
             },
              onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print hostel visitor report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/hostelVisitorReportPrint.php?'+qstr;
    try{
    window.open(path,"HostelVisitorReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
    }
}

function printCSV() {
    path='<?php echo UI_HTTP_PATH;?>/displayHostelVisitorReportCSV.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}

function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){    
  var form = document.AddHostelVisitor;
 }
 else{
     var form = document.EditHostelVisitor;
 }
  eval('form.'+eleName+'.focus()');
  return false;
 }
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/HostelVisitor/listHostelVisitorContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
</body>
</html>

