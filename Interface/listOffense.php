<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Offense ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (22.12.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','OffenseMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Offense Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage	= <?php echo RECORDS_PER_PAGE;?>;
linksPerPage	= <?php echo LINKS_PER_PAGE;?>;

winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height

// ajax search results ---end ///

function getOffenseData(){
  url = '<?php echo HTTP_LIB_PATH;?>/Offense/ajaxInitOffenseList.php';
  var value=document.searchForm.searchbox.value;
  
  var tableColumns = new Array(
                        new Array('srNo','#','width="3%" align="left"',false), 
                        new Array('offenseName','Name','width="30%" align="left"',true),
                        new Array('offenseAbbr','Abbr.','width="25%" align="left"',true),
						new Array('offenseDesc','Desc.','width="25%" align="left"',true),
	                    new Array('studentCount','Student Count','width="35%" align="right"',true),
                        new Array('action','Action','width="10%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','offenseName','ASC','OffenseResultDiv','OffenseActionDiv','',true,'listObj',tableColumns,'editWindow','deleteOffense','&searchbox='+trim(value));
 sendRequest(url, listObj, '')

}
// ajax search results ---end ///

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Jaineesh
// Created on : (22.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id,dv,w,h) {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Offense';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues(id);   
}
//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Jaineesh
// Created on : (15.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
    
   
    var fieldsArray = new Array(new Array("offenseName","<?php echo ENTER_OFFENSE_NAME ?>"),new Array("offenseAbbr","<?php echo ENTER_OFFENSE_ABBR ?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
         
        else if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<2 && fieldsArray[i][0]=='offenseName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo OFFENSE_NAME_LENGTH ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            
        else  if (!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]=='offenseName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_OFFENSE_ALPHABETS_NUMERIC ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
                }
        }
  
    if(document.getElementById('offenseId').value=='') {
        //alert('add slot');
		addOffense();
        return false;
    }
    else{
		//alert('edit slot');
        editOffense();
        return false;
    }
}

function emptySlotId() {
	document.getElementById('offenseId').value='';
}

//-------------------------------------------------------
//THIS FUNCTION addPeriods() IS USED TO ADD NEW Periods
//
//Author : Jaineesh
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addOffense() {
		
         url = '<?php echo HTTP_LIB_PATH;?>/Offense/ajaxInitOffenseAdd.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                offenseName:   trim(document.OffenseDetail.offenseName.value), 
                offenseAbbr:   trim(document.OffenseDetail.offenseAbbr.value),
			    offenseDesc:   trim(document.OffenseDetail.offenseDesc.value)
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
                             hiddenFloatingDiv('OffenseActionDiv');
                             getOffenseData();
                             return false;
                         }
                     }
					 else {
						messageBox(trim(transport.responseText));
                      if("<?php echo OFFENSE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         document.OffenseDetail.offenseAbbr.focus();
                        } 
                     else {
                         document.OffenseDetail.offenseName.focus();
                       }
					 }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A PERIOD SLOT
//  id=periodSlotId
//Author : Jaineesh
// Created on : (15.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteOffense(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Offense/ajaxInitOffenseDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {offenseId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         getOffenseData(); 
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
//Author : Jaineesh
// Created on : (22.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Add Offense';
	document.OffenseDetail.offenseName.value = '';
	document.OffenseDetail.offenseAbbr.value = '';
	document.getElementById('offenseId').value='';
	document.OffenseDetail.offenseName.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A OFFENSE
//
//Author : Jaineesh
// Created on : (22.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editOffense() {
         url = '<?php echo HTTP_LIB_PATH;?>/Offense/ajaxInitOffenseEdit.php';
                  
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
					offenseId: (document.OffenseDetail.offenseId.value),
					offenseName:   trim(document.OffenseDetail.offenseName.value), 
					offenseAbbr :   trim(document.OffenseDetail.offenseAbbr.value),
					offenseDesc:   trim(document.OffenseDetail.offenseDesc.value)
   
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('OffenseActionDiv');
                         getOffenseData();
						 //emptySlotId();
                         return false;

                     }
					else {
					 messageBox(trim(transport.responseText));
					 if("<?php echo OFFENSE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         document.OffenseDetail.offenseAbbr.focus();
                        } 
                     else {

                         document.OffenseDetail.offenseName.focus();
                     }
					}
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EDITOFFENSEs" DIV
//
//Author : Jaineesh
// Created on : (22.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Offense/ajaxGetOffenseValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {offenseId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('OffenseActionDiv');
                        messageBox("<?php echo OFFENSE_NOT_EXIST; ?>");
                        getOffenseData();           
                   }

				   else if ("<?php echo OFFENSE_CONSTRAINT ;?>" == trim(transport.responseText)) {
					    hiddenFloatingDiv('OffenseActionDiv');
						messageBox("<?php echo OFFENSE_CONSTRAINT ;?>"); 
						getOffenseData();
				   }

                   j = eval('('+trim(transport.responseText)+')');
                   
                   document.OffenseDetail.offenseName.value			= j.offenseName;
                   document.OffenseDetail.offenseAbbr.value			= j.offenseAbbr;
                   document.OffenseDetail.offenseId.value			= j.offenseId;
                   document.OffenseDetail.offenseDesc.value			= j.offenseDesc;
                   document.OffenseDetail.offenseName.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


window.onload=function(){
        //loads the data
        getOffenseData();    
}

function printReport() {
	
	sortField = listObj.sortField;
	sortOrderBy = listObj.sortOrderBy;

	var path='<?php echo UI_HTTP_PATH;?>/displayOffenseReport.php?searchbox='+trim(document.searchForm.searchbox_h.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"OffenseReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

function printStudentCount(id) {
	var path='<?php echo UI_HTTP_PATH;?>/studentOffenceCountPrint.php?id='+id;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"OffenseReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

/* function to output data to a CSV*/
function printCSV() {
	sortField = listObj.sortField;
	sortOrderBy = listObj.sortOrderBy;

    var qstr="searchbox="+trim(document.searchForm.searchbox_h.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayOffenseCSV.php?'+qstr;
	window.location = path;
}

</script>

</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/Offense/listOffenseContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listOffense.php $
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 10/05/09   Time: 6:31p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001684, 0001689, 0001688, 0001687, 0001685, 0001686,
//0001683, 0001629 and report for academic head privileges
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 10/03/09   Time: 6:08p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001681, 0001680, 0001679, 0001678, 0001677, 0001676,
//0001675, 0001666, 0001665, 0001664, 0001631, 0001614, 0001682, 0001610
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 9/21/09    Time: 7:28p
//Updated in $/LeapCC/Interface
//fixed bugs during self testing
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/27/09    Time: 3:20p
//Updated in $/LeapCC/Interface
//Gurkeerat: Resolved issue 1292
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 7/08/09    Time: 1:16p
//Updated in $/LeapCC/Interface
//fixed issues nos.0000356,0000357,0000444,0000445
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/24/09    Time: 3:03p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000258,0000260,0000265,0000270,0000255
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/02/09    Time: 3:22p
//Updated in $/LeapCC/Interface
//make less width of sr. no.
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 5/27/09    Time: 3:04p
//Updated in $/LeapCC/Interface
//In IE list was not coming, modified in code to show list in IE
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/25/08   Time: 4:36p
//Updated in $/LeapCC/Interface
//modified for data constraint
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/22/08   Time: 5:34p
//Created in $/LeapCC/Interface
//new file for offense add, edit or delete
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/22/08   Time: 5:13p
//Created in $/Leap/Source/Interface
//new file to add student offences, edit & delete
//
//*********Modify By satinder 8oct10 5:00pm
?>