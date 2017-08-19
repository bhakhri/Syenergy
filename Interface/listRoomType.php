<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Offense ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Gurkeerat Sidhu
// Created on : (19.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoomTypeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Room Type Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

winLayerWidth  = 340; //  add/edit form width
winLayerHeight = 250; // add/edit form height

// ajax search results ---end ///

function getRoomTypeData(){         
  url = '<?php echo HTTP_LIB_PATH;?>/RoomType/ajaxInitRoomTypeList.php';
  var value = document.searchForm.searchbox.value;
  
  var tableColumns = new Array(
                        new Array('srNo','#','width="4%" align="left"',false), 
                        new Array('roomType','Room Type','width="40%" align="left"',true),
                        new Array('abbr','Abbreviation','width="40%" align="left"',true),
                        new Array('action','Action','width="8%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','roomType','ASC','RoomTypeResultDiv','RoomTypeActionDiv','',true,'listObj',tableColumns,'editWindow','deleteRoomType','&searchbox='+trim(value));
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
//Author : Gurkeerat Sidhu
// Created on : (19.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id,dv,w,h) {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Room Type';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues(id);   
}
//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Gurkeerat Sidhu
// Created on : (19.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
    
   
    var fieldsArray = new Array(new Array("roomType","<?php echo ENTER_ROOM_TYPE1 ?>"),
    new Array("abbr","<?php echo ENTER_ABBR ?>"));

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
  
    if(document.getElementById('roomTypeId').value=='') {
        addRoomType();
        return false;
    }
    else{
		editRoomType();
        return false;
    }
}


//-------------------------------------------------------
//THIS FUNCTION addRoomType() IS USED TO ADD NEW Room Type
//
//Author : Gurkeerat Sidhu
// Created on : (19.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addRoomType() {
		
         url = '<?php echo HTTP_LIB_PATH;?>/RoomType/ajaxInitRoomTypeAdd.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                roomType:   trim(document.RoomTypeDetail.roomType.value),
                abbr:           trim(document.RoomTypeDetail.abbr.value)
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
                             hiddenFloatingDiv('RoomTypeActionDiv');
                             getRoomTypeData();
                             return false;
                         }
                     }
					 
					 else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo ROOM_TYPE_EXIST; ?>'){
							document.RoomTypeDetail.roomType.value="";
							document.RoomTypeDetail.roomType.focus();
						}
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A PERIOD SLOT
//  id=roomTypeId
//Author : Gurkeerat Sidhu
// Created on : (19.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteRoomType(id) {
    
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/RoomType/ajaxInitRoomTypeDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {roomTypeId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         getRoomTypeData(); 
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
// Created on : (19.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Add Room Type';
	document.RoomTypeDetail.roomType.value = '';
    document.RoomTypeDetail.abbr.value = '';
	document.getElementById('roomTypeId').value='';
	document.RoomTypeDetail.roomType.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A Room Type
//
//Author : Gurkeerat Sidhu
// Created on : (19.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editRoomType() {
         url = '<?php echo HTTP_LIB_PATH;?>/RoomType/ajaxInitRoomTypeEdit.php';
          
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
					roomTypeId: (document.RoomTypeDetail.roomTypeId.value),
					roomType:   trim(document.RoomTypeDetail.roomType.value),
                    abbr:           trim(document.RoomTypeDetail.abbr.value)
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('RoomTypeActionDiv');
                         getRoomTypeData();
						 return false;
                       }
                   else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo ROOM_TYPE_EXIST; ?>'){
							document.RoomTypeDetail.roomType.value="";
							document.RoomTypeDetail.roomType.focus();
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
// Created on : (19.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/RoomType/ajaxRoomTypeGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {roomTypeId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('RoomTypeActionDiv');
                        messageBox("<?php echo ROOM_TYPE_NOT_EXIST; ?>");
                        getRoomTypeData();           
                   }
                   j = eval('('+trim(transport.responseText)+')');

                  
                   document.RoomTypeDetail.roomType.value	= j.roomType;
                   document.RoomTypeDetail.abbr.value   = j.abbr;
                   document.RoomTypeDetail.roomTypeId.value		= j.roomTypeId;
                   document.RoomTypeDetail.roomType.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
	sortField = listObj.sortField;
	sortOrderBy = listObj.sortOrderBy;

	path='<?php echo UI_HTTP_PATH;?>/displayRoomTypeReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayDesignationTempReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"DisplayRoomTypeReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

function printCSV() {
	sortField = listObj.sortField;
	sortOrderBy = listObj.sortOrderBy;
    path='<?php echo UI_HTTP_PATH;?>/displayRoomTypeCSV.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}

window.onload=function(){
        //loads the data
        //document.searchBox1.reset();
        getRoomTypeData();
}

/*function printReport() {
	path='<?php echo UI_HTTP_PATH;?>/displayPeriodsReport.php';
    window.open(path,"DisplayPeriodsReport","status=1,menubar=1,scrollbars=1, width=900, height=700");
}*/

</script>

</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/RoomType/listRoomTypeContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	
</body>
</html>
