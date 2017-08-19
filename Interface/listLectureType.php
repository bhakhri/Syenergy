<?php
//-------------------------------------------------------
// Purpose: To generate the list of lecture type from the database, and have add/edit/delete, search 
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : (11.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','LectureTypeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Lecture/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Lecture Type Master </title>
<?php 
	require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','align="left"',false), 
new Array('lectureName','Lecture Type','width="90%"','',true) , 
new Array('action','Action','width="8%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Lecture/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddLectureType';   
editFormName   = 'EditLectureType';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteLectureType';
divResultName  = 'results';
page=1; //default page
sortField = 'lectureName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {

	displayWindow(dv,w,h);
    populateValues(id);
}

function validateAddForm(frm, act) {

	var fieldsArray = new Array(new Array("lectureType","<?php echo ENTER_LECTURE_TYPE;?>"));
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='lectureType' ) {
                alert("<?php echo LECTURETYPE_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo LECTURETYPE_SPECIAL_CHARACTER;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
        }
    }
    if(act=='Add') {
        addLectureType();
        return false;
    }
    else if(act=='Edit') {
        editLectureType();
        return false;
    }
}

function addLectureType() {

	url = '<?php echo HTTP_LIB_PATH;?>/Lecture/ajaxInitAdd.php';
		  
    new Ajax.Request(url,
    {
        method:'post',
        parameters: {lectureType: (document.AddLectureType.lectureType.value)},
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
					 hiddenFloatingDiv('AddLectureType');
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

function deleteLectureType(id) {
	 
    if(false===confirm("<?php echo LECTURETYPE_DELETE;?>")) {
         return false;
    }
    else {   
        
		url = '<?php echo HTTP_LIB_PATH;?>/Lecture/ajaxInitDelete.php';
        new Ajax.Request(url,
        {
             method:'post',
             parameters: {lecturetypeId: id},
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

function blankValues() {
   document.AddLectureType.lectureType.value = '';
   document.AddLectureType.lectureType.focus();
}

function editLectureType() {  
         url = '<?php echo HTTP_LIB_PATH;?>/Lecture/ajaxInitEdit.php';
        
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {lectureTypeId: (document.EditLectureType.lectureTypeId.value), lectureType: (document.EditLectureType.lectureType.value)},
             onCreate: function() {
                 showWaitDialog(true); 
             },
             onSuccess: function(transport){

                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditLectureType');
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

function populateValues(id) {
	 
         url = '<?php echo HTTP_LIB_PATH;?>/Lecture/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {lectureTypeId: id},
             onCreate: function() {
                 showWaitDialog(true); 
             },
             onSuccess: function(transport){

                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditLectureType');
                        messageBox('<?php echo LECTURE_TYPE_NOEXISTS ?>');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                   }
                   j = eval('('+trim(transport.responseText)+')');
				    
                   document.EditLectureType.lectureType.value = j.lectureName;
                   document.EditLectureType.lectureTypeId.value = j.lectureTypeId;
                   document.EditLectureType.lectureType.focus();
                
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Lecture/listLecturetypeContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script language="javascript">
 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>    
</body>
</html>
<?php
// $History: listLectureType.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/28/09    Time: 12:39p
//Updated in $/LeapCC/Interface
//Gurkeerat: resolved issue 1324
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/27/09    Time: 7:16p
//Updated in $/LeapCC/Interface
//Gurkeerat: resolved issue 1322
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/08/09    Time: 11:14a
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 8/27/08    Time: 2:59p
//Updated in $/Leap/Source/Interface
//updated formatting
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 8/25/08    Time: 11:53a
//Updated in $/Leap/Source/Interface
//updated with centalized messages
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 8/07/08    Time: 3:36p
//Updated in $/Leap/Source/Interface
//changed messages
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 8/05/08    Time: 3:32p
//Updated in $/Leap/Source/Interface
//updated ajax with oncreate function
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 7/17/08    Time: 11:33a
//Updated in $/Leap/Source/Interface
//updated issue no 0000062,0000061,0000070

?>
