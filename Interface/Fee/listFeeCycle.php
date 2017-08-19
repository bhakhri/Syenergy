<?php
//-------------------------------------------------------
// Purpose: To generate the list of fee cycle from the database, and have add/edit/delete, search 
// functionality 
//
// Author :Nishu Bindal
// Created on : (3.feb.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeCycleMasterNew');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fee Cycle Master New</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">



var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
				new Array('cycleName','Name','width=15%','',true), 
				new Array('cycleAbbr','Abbr.','width="10%"','',true), 
				new Array('feeCycleFromToDate','Fee Cycle','width="14"','align=center',true), 			
				new Array('academicFromToDate','Academic','width="14%"','align=center',true), 
				new Array('hostelFromToDate','Hostel','width="14%"','align=center',true),
				new Array('transportFromToDate','Transport','width="14%"','align=center',true), 			
				new Array('status','Status','width="8%"','align=center',true), 
				new Array('action','Action','width="3%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeCycle/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFeeCycle';   
editFormName   = 'EditFeeCycle';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteFeeCycle';
divResultName  = 'results';
page=1; //default page
sortField = 'cycleName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Nishu Bindal
// Created on : (3.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Nishu Bindal
// Created on : (3.feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
        var fieldsArray = new Array(new Array("cycleName","<?php echo ENTER_FEECYCLE_NAME;?>"),
                                    new Array("cycleAbbr","<?php echo ENTER_FEECYCLE_ABBR;?>"),
                                    new Array("fromDate","Select Fee Cycle From Date"),
                                    new Array("toDate","Select Fee Cycle To Date"),
                                    new Array("academicFromDate","Select Academic From Date"),
                                    new Array("academicToDate","Select Academic To Date"),
                                    new Array("hostelFromDate","Select Hostel From Date"),
                                    new Array("hostelToDate","Select Academic To Date"),
                                    new Array("transportFromDate","Select Transport From Date"),
                                    new Array("transportToDate","Select Transport To Date")
                                   );

        var len = fieldsArray.length;
        for(i=0;i<len;i++) {
          id= fieldsArray[i][0];  
          if(i>=2 && act=='Edit') {
            id = id + trim("1");
          }
          if(isEmpty(eval("frm."+id+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+id+".focus();");
            return false;
          }
        }
    
        if(act=='Add') {
            if(!dateDifference(eval("frm.fromDate.value"),eval("frm.toDate.value"),'-') ) {
                messageBox("Fee Cycle <?php echo DATE_VALIDATION;?>");
                eval("frm.fromDate.focus();");
                return false;
            } 
            
		    if(!dateDifference(eval("frm.academicFromDate.value"),eval("frm.academicToDate.value"),'-') ) {
                messageBox("Academic <?php echo DATE_VALIDATION;?>");
                eval("frm.academicFromDate.focus();");
                return false;
            } 
		    if(!dateDifference(eval("frm.hostelFromDate.value"),eval("frm.hostelToDate.value"),'-') ) {
                messageBox("Hostel <?php echo DATE_VALIDATION;?>");
                eval("frm.hostelFromDate.focus();");
                return false;
             } 
		     if(!dateDifference(eval("frm.transportFromDate.value"),eval("frm.transportToDate.value"),'-') ) {
                messageBox("Transport <?php echo DATE_VALIDATION;?>");
                eval("frm.transportFromDate.focus();");
                return false;
            } 
        }
        else if(act=='Edit') {
            if(!dateDifference(eval("frm.fromDate1.value"),eval("frm.toDate1.value"),'-') ) {
                    messageBox("Fee Cycle <?php echo DATE_VALIDATION;?>");
                    eval("frm.fromDate1.focus();");
                    return false;
             } 
            if(!dateDifference(eval("frm.academicFromDate1.value"),eval("frm.academicToDate1.value"),'-') ) {
                messageBox("Academic <?php echo DATE_VALIDATION;?>");
                eval("frm.academicFromDate1.focus();");
                return false;
            } 
            if(!dateDifference(eval("frm.hostelFromDate1.value"),eval("frm.hostelToDate1.value"),'-') ) {
                messageBox("Hostel <?php echo DATE_VALIDATION;?>");
                eval("frm.hostelFromDate1.focus();");
                return false;
             } 
            if(!dateDifference(eval("frm.transportFromDate1.value"),eval("frm.transportToDate1.value"),'-') ) {
                messageBox("Transport <?php echo DATE_VALIDATION;?>");
                eval("frm.transportFromDate1.focus();");
                return false;
            } 
        }  
       
        if(act=='Add') {
            addFeeCycle();
            return false;
        }
        else if(act=='Edit') {
            editFeeCycle();
            return false;
        }
}

//-------------------------------------------------------
//THIS FUNCTION addFeeCycle() IS USED TO ADD NEW HOSTEL ROOM
//
//Author : Nishu bindal
// Created on : (3.feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addFeeCycle() {
		active ='';
		if(document.getElementById('active').checked){
			active=1;
		}
		else{
			active =0;
		}
         url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeCycle/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	cycleName: (document.addFeeCycle.cycleName.value), 
				cycleAbbr: (document.addFeeCycle.cycleAbbr.value), 
				fromDate: (document.addFeeCycle.fromDate.value), 
				toDate: (document.addFeeCycle.toDate.value),
				academicFromDate: (document.addFeeCycle.academicFromDate.value), 
				academicToDate: (document.addFeeCycle.academicToDate.value),
				hostelFromDate: (document.addFeeCycle.hostelFromDate.value), 
				hostelToDate: (document.addFeeCycle.hostelToDate.value),
				transportFromDate: (document.addFeeCycle.transportFromDate.value), 
				transportToDate: (document.addFeeCycle.transportToDate.value),
				active: active
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
                             hiddenFloatingDiv('AddFeeCycle');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=='<?php echo CYCLE_ABBR_EXIST ?>'){
							document.addFeeCycle.cycleAbbr.focus();	
						}
						else {
							document.addFeeCycle.cycleName.focus();
						}
                     }
               
             },
			  onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION DELETEFeeCycle() IS USED TO DELETE THE SPECIFIED RECORD 
//FROM  SPECIFIED FILE THROUGH ID
//
//Author : Nishu Bindal
// Created on : (3.feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteFeeCycle(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeCycle/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeCycleId: id},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
               
                     hideWaitDialog(true);
                 //   messageBox(trim(transport.responseText));
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                        else {
                         messageBox(trim(transport.responseText));
                     }
              
             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }    
}

//-------------------------------------------------------
//THIS FUNCTION blanValues() IS USED TO BLANK VALUES OF TEXT BOXES 
//
//Author : Nishu bindal
// Created on : (3.feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function blankValues() {
   document.addFeeCycle.cycleName.value = '';
   document.addFeeCycle.cycleAbbr.value = '';
   document.addFeeCycle.fromDate.value = '';
   document.addFeeCycle.toDate.value = '';
   document.addFeeCycle.academicFromDate.value = '';
   document.addFeeCycle.academicToDate.value= '';
   document.addFeeCycle.hostelFromDate.value= '';
   document.addFeeCycle.hostelToDate.value= '';
   document.addFeeCycle.transportFromDate.value= '';
   document.addFeeCycle.transportToDate.value= '';
   document.addFeeCycle.cycleName.focus();
}

 //-------------------------------------------------------
//THIS FUNCTION IS USED TO UPDATE THE EXISTING RECOORD
// 
//Author :Nishu Bindal
// Created on : (3.feb.2012)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editFeeCycle() {
	active = '';
	if(document.getElementById('editActive').checked){
		active = 1;
	}
	else{
		active = 0;
	}
         url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeCycle/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeCycleId: (document.editFeeCycle.feeCycleId.value), 
			cycleName: (document.editFeeCycle.cycleName.value), 
			cycleAbbr: (document.editFeeCycle.cycleAbbr.value), 
			fromDate1: (document.editFeeCycle.fromDate1.value), 
			toDate1: (document.editFeeCycle.toDate1.value), 
			academicFromDate1: (document.editFeeCycle.academicFromDate1.value), 
			academicToDate1: (document.editFeeCycle.academicToDate1.value),
			hostelFromDate1: (document.editFeeCycle.hostelFromDate1.value), 
			hostelToDate1: (document.editFeeCycle.hostelToDate1.value),
			transportFromDate1: (document.editFeeCycle.transportFromDate1.value), 
			transportToDate1: (document.editFeeCycle.transportToDate1.value),
			active : active},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
               
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     	 hideWaitDialog(true);
                 	 messageBox(trim(transport.responseText));
                         hiddenFloatingDiv('EditFeeCycle');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                      
                     }
                        else {
                         messageBox(trim(transport.responseText));
						 if (trim(transport.responseText)=='<?php echo CYCLE_ABBR_EXIST ?>'){
							document.editFeeCycle.cycleAbbr.focus();	
						}
						else {
							document.editFeeCycle.cycleName.focus();
						}
                     }
               
             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO populate the values 
 // during editing the record
// 
//Author : Nishu Bindal
// Created on : (3.feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeCycle/ajaxGetValues.php';
          new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeCycleId: id},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
             onSuccess: function(transport){
              
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditFeeCycle');
                        messageBox('Cycle Name does not exist');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                    }
                    j = eval('('+trim(transport.responseText)+')');  
                   document.editFeeCycle.cycleName.value = j.cycleName;
                   document.editFeeCycle.cycleAbbr.value = j.cycleAbbr;
                   document.editFeeCycle.fromDate1.value = j.fromDate;
                   document.editFeeCycle.toDate1.value = j.toDate;
                   document.editFeeCycle.feeCycleId.value = j.feeCycleId;
		   document.editFeeCycle.academicFromDate1.value = j.academicFromDate;
		   document.editFeeCycle.academicToDate1.value = j.academicToDate;
		   document.editFeeCycle.hostelFromDate1.value = j.hostelFromDate;
		   document.editFeeCycle.hostelToDate1.value = j.hostelToDate;
		   document.editFeeCycle.transportFromDate1.value = j.transportFromDate; 
		   document.editFeeCycle.transportToDate1.value = j.transportToDate;
                   if(j.status == 1){
                   	document.getElementById('editActive').checked= true;
                   }
                   else{
                   	document.getElementById('editInActive').checked= true;
                   }
                   document.editFeeCycle.cycleName.focus();
               
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
   path='<?php echo UI_HTTP_PATH;?>/displayFeeCycleReport.php?searchbox='+(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
   window.open(path,"DisplayFeeCycleReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
    
    path='<?php echo UI_HTTP_PATH;?>/displayFeeCycleCSV.php?searchbox='+(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}
function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){    
  var form = document.addFeeCycle;
 }
 else{
     var form = document.editFeeCycle;
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
    require_once(TEMPLATES_PATH . "/Fee/FeeCycle/listFeeCycleContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
	//-->
	</SCRIPT>
</body>
</html>

