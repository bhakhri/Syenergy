<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Training ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelRoomType');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Discipline/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Hostel Room Type Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage	= <?php echo RECORDS_PER_PAGE;?>;
linksPerPage	= <?php echo LINKS_PER_PAGE;?>;
sortField		= 'roomType';
sortOrderBy		= 'ASC';
winLayerWidth	= 340; //  add/edit form width
winLayerHeight	= 250; // add/edit form height

// ajax search results ---end ///
//var page = 1;
function getHostelRoomTypeData(){
  url = '<?php echo HTTP_LIB_PATH;?>/HostelRoomType/ajaxInitHostelRoomTypeList.php';
  //var value=document.getElementById('searchbox').value;
  var value=trim(document.searchForm.searchbox.value);
  
 var tableColumns = new Array(
                        new Array('srNo','#','width="3%" align="left"',false), 
                        new Array('roomType','Hostel Room Type','width="40%" align="left"',true),
						new Array('roomAbbr','Abbr.','width="40%" align="left"',true),
                        new Array('action','Action','width="2%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array

  listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','roomType','ASC','HostelRoomTypeResultDiv','HostelRoomTypeDiv','',true,'listObj',tableColumns,'editWindow','deleteHostelRoomType','&searchbox='+trim(value));
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
// Created on : (21.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id,dv,w,h) {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Hostel Room Type';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues(id);   
}
//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
    
   
    var fieldsArray = new Array(new Array("roomType","<?php echo ENTER_ROOM_TYPE ?>"), 
								new Array("roomAbbr","<?php echo ENTER_ROOM_ABBR?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }

         
       else if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<2 && fieldsArray[i][0]=='roomType' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo HOSTEL_ROOM_TYPE ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            
        }
  
    if(document.getElementById('hostelRoomTypeId').value=='') {
        //alert('add slot');
		addHostelRoomType();
        return false;
    }
    else{
		//alert('edit slot');
        editHostelRoomType();
        return false;
    }
}


//-------------------------------------------------------
//THIS FUNCTION addDocument() IS USED TO ADD NEW Hostel room type
//
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addHostelRoomType() {
		
         url = '<?php echo HTTP_LIB_PATH;?>/HostelRoomType/ajaxInitHostelRoomTypeAdd.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
				roomType:			trim(document.HostelRoomDetail.roomType.value),
				roomAbbr:			trim(document.HostelRoomDetail.roomAbbr.value)
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
                             hiddenFloatingDiv('HostelRoomTypeDiv');
                             getHostelRoomTypeData();
                             return false;
                         }
                     }
					else {
						messageBox(trim(transport.responseText));
                        if (trim(transport.responseText)=='<?php echo HOSTELROOM_TYPE_ABBR_EXIST; ?>'){
							document.HostelRoomDetail.roomAbbr.focus();
						}
						else if (trim(transport.responseText)=='<?php echo HOSTELROOM_TYPE_EXIST; ?>'){
							document.HostelRoomDetail.roomType.focus();
						}
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A DOCUMENT
//  id=documentId
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteHostelRoomType(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/HostelRoomType/ajaxInitHostelRoomTypeDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {hostelRoomTypeId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         getHostelRoomTypeData(); 
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
//THIS FUNCTION IS USED TO CLEAN UP THE "TRAINING" DIV
//
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Add Hostel Room Type';
	document.HostelRoomDetail.roomType.value = '';
	document.HostelRoomDetail.roomAbbr.value = '';
	document.HostelRoomDetail.hostelRoomTypeId.value = '';
	document.HostelRoomDetail.roomType.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A DOCUMENT
//
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editHostelRoomType() {
         url = '<?php echo HTTP_LIB_PATH;?>/HostelRoomType/ajaxInitHostelRoomTypeEdit.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
					hostelRoomTypeId:		(document.HostelRoomDetail.hostelRoomTypeId.value),
					roomType:				trim(document.HostelRoomDetail.roomType.value),
					roomAbbr:				trim(document.HostelRoomDetail.roomAbbr.value)
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('HostelRoomTypeDiv');
                         getHostelRoomTypeData();
						 //emptySlotId();
                         return false;
                     }
                   else {
						messageBox(trim(transport.responseText));
                        if (trim(transport.responseText)=='<?php echo HOSTELROOM_TYPE_ABBR_EXIST; ?>'){
							document.HostelRoomDetail.roomAbbr.focus();
						}
						else if (trim(transport.responseText)=='<?php echo HOSTELROOM_TYPE_EXIST; ?>'){
							document.HostelRoomDetail.roomType.focus();
						}
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EDITDOCUMENT" DIV
//
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/HostelRoomType/ajaxHostelRoomTypeGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {hostelRoomTypeId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('HostelRoomTypeDiv');
                        messageBox("<?php echo HOSTEL_ROOM_TYPE_NOT_EXIST; ?>");
                        getHostelRoomTypeData();           
                   }

				/*   else if ("<?php echo OFFENSE_CONSTRAINT ;?>" == trim(transport.responseText)) {
					    hiddenFloatingDiv('OffenseActionDiv');
						messageBox("<?php echo OFFENSE_CONSTRAINT ;?>"); 
						getOffenseData();
				   }*/

                   j = eval('('+trim(transport.responseText)+')');
				   //alert(trim(transport.responseText));

                  //alert(j.employeeCode);
                   document.HostelRoomDetail.roomType.value			= j.roomType;
				   document.HostelRoomDetail.roomAbbr.value			= j.roomAbbr;
				   document.HostelRoomDetail.hostelRoomTypeId.value = j.hostelRoomTypeId;
                   document.HostelRoomDetail.roomType.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
	var path='<?php echo UI_HTTP_PATH;?>/displayHostelRoomTypeReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"HostelRoomtypeReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
    }
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayHostelRoomTypeCSV.php?'+qstr;
	window.location = path;
}


window.onload=function(){
        //loads the data
        getHostelRoomTypeData();
}

</script>

</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/HostelRoomType/listHostelRoomTypeContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	
</body>
</html>
<?php 
// $History: listHostelRoomType.php $ 
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/24/09    Time: 10:39a
//Updated in $/LeapCC/Interface
//fixed bugs
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/10/09    Time: 10:18a
//Updated in $/LeapCC/Interface
//give print & export to excel facility
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/11/09    Time: 6:32p
//Updated in $/LeapCC/Interface
//fixed issue nos.0000093,0000094,0000096
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/08/09    Time: 6:58p
//Updated in $/LeapCC/Interface
//Fixed bug Nos.1303,1304,1305,1306,1307,1308,1310,1311,1312,1313,1314,13
//15,1316,1317 of Issues [05-June-09].doc
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/23/09    Time: 1:08p
//Updated in $/LeapCC/Interface
//modified in length of sr.no.
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/22/09    Time: 2:09p
//Updated in $/LeapCC/Interface
//modified in name
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/22/09    Time: 11:47a
//Created in $/LeapCC/Interface
//
?>