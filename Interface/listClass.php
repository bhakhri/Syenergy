<?php
//-------------------------------------------------------
// Purpose: To generate the list of class from the database, and have add/edit/delete, search 
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : (30.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/Class/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Class Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), new Array('className','Class Name','width="55%"','',true) , new Array('degreeDuration','Duration','width="8%"','',true), new Array('isActive','Status','width="7%"','',true) , new Array('action','Action','width="5%"','align="right"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Class/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddClass';   
editFormName   = 'EditClass';
winLayerWidth  = 415; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteClass';
divResultName  = 'results';
page=1; //default page
sortField = 'ClassName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
	displayFloatingDiv(dv,'',500,250,300,100)
    populateValues(id); 
}

function validateAddForm(frm, act) {
    
   var fieldsArray = new Array(new Array("batch","Please select Batch"),new Array("university","Please select University"),new Array("degree","Please select Degree"),new Array("branch","Please select Branch"),new Array("studyperiod","Please select Study Period"),new Array("degreeDuration","Enter degree duration"),new Array("className","Enter class name"));
    

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
            //unsetAlertStyle(fieldsArray[i][0]);
            /*if((eval("frm."+(fieldsArray[i][0])+".value.length"))<3 && fieldsArray[i][0]=='degreeDuration' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("Degree duration can not be less than 3 characters");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }  */          
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]=='degreeDuration' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("Special characters are not allowed");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            else {
                unsetAlertStyle(fieldsArray[i][0]);
            }
        }
     
    }
    if(act=='Add') {
        addClass();
        return false;
    }
    else if(act=='Edit') {
        editClass();
        return false;
    }
}
function addClass() {
         url = '<?php echo HTTP_LIB_PATH;?>/Class/ajaxInitAdd.php';
		 
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {batch: (document.addClass.batch.value), university: (document.addClass.university.value), degree: (document.addClass.degree.value), branch: (document.addClass.branch.value), studyperiod: (document.addClass.studyperiod.value), degreeDuration: (document.addClass.degreeDuration.value), className: (document.addClass.className.value), classDescription: (document.addClass.classDescription.value), radioactive: (document.addClass.radioactive.value)},
             onSuccess: function(transport){
               if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                  showWaitDialog(true);
               }
               else {
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('AddClass');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
               }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function deleteClass(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Class/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {classId: id},
             onSuccess: function(transport){
               if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                  showWaitDialog(true);
               }
               else {
                     hideWaitDialog(true);
                     messageBox(trim(transport.responseText));
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
               }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }    
           
}
function blankValues() {
   document.addClass.batch.value = '';
   document.addClass.university.value = '';
   document.addClass.degree.value = '';
   document.addClass.branch.value = '';
   document.addClass.studyperiod.value = '';
   document.addClass.degreeDuration.value = '';
   document.addClass.className.value = '[batch] '+'<?php echo CLASS_SEPRATOR ?>'+' [university] '+'<?php echo CLASS_SEPRATOR ?>'+' [degree] '+'<?php echo CLASS_SEPRATOR ?>'+' [branch] '+'<?php echo CLASS_SEPRATOR ?>'+' [studyperiod]';
   document.addClass.classDescription.value = '';
    
   
   document.addClass.batch.focus();
}
function editClass() {  
         url = '<?php echo HTTP_LIB_PATH;?>/Class/ajaxInitEdit.php';
        
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {classId: (document.editClass.classId.value), batch: (document.editClass.batch.value), university: (document.editClass.university.value), degree: (document.editClass.degree.value), branch: (document.editClass.branch.value), studyperiod: (document.editClass.studyperiod.value), degreeDuration: (document.editClass.degreeDuration.value), className: (document.editClass.className.value), classDescription: (document.editClass.classDescription.value), radioactive: (document.editClass.radioactive.value)},
             onSuccess: function(transport){
               if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                  showWaitDialog(true);
               }
               else {
				  
                     hideWaitDialog(true);
                     messageBox(trim(transport.responseText));
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditClass');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
               }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function populateValues(id) {
 
         url = '<?php echo HTTP_LIB_PATH;?>/Class/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {classId: id},
             onSuccess: function(transport){
               if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                   
                  showWaitDialog(true);
               }
               else {
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditClass');
                        messageBox('Class does not exist');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                   j = eval('('+trim(transport.responseText)+')');
				   //alert(transport.responseText);
					//alert(j.degreeId);
				
                   document.editClass.batch.value = j.batchId;
                   document.editClass.university.value = j.universityId;
				   document.editClass.degree.value = j.degreeId;
				   document.editClass.branch.value = j.branchId;
				   document.editClass.studyperiod.value = j.studyPeriodId;
                   document.editClass.degreeDuration.value = j.degreeDuration;
				   document.editClass.classDescription.value = j.classDescription;
				   document.editClass.className.value = j.className;
                   document.editClass.classId.value = j.classId;
				   document.editClass.radioactive.value = j.isActive;
					 
			   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function checkValues()
{
	var batch = document.addClass.batch.options[document.addClass.batch.selectedIndex].text;
	var university = document.addClass.university.options[document.addClass.university.selectedIndex].text;
	var degree = document.addClass.degree.options[document.addClass.degree.selectedIndex].text;
	var branch = document.addClass.branch.options[document.addClass.branch.selectedIndex].text;
var studyperiod = document.addClass.studyperiod.options[document.addClass.studyperiod.selectedIndex].text;
	var classname;
	if(batch!="Select Batch")
		classname = batch+" <?php echo CLASS_SEPRATOR ?> ";
	else
		classname = "[batch] - ";

	if(university!="Select University")
		classname += university+" <?php echo CLASS_SEPRATOR ?> ";
	else
		classname += "[university] "+" <?php echo CLASS_SEPRATOR ?> ";

	if(degree!="Select Degree")
		classname += degree+" <?php echo CLASS_SEPRATOR ?> ";
	else
		classname += "[degree] "+" <?php echo CLASS_SEPRATOR ?> ";

	if(branch!="Select Branch")
		classname += branch+" <?php echo CLASS_SEPRATOR ?> ";
	else
		classname += "[branch] "+" <?php echo CLASS_SEPRATOR ?> ";

	if(studyperiod!="Select Study Period")
		classname += studyperiod;
	else
		classname += "[studyperiod]";

	//alert(classname);

	document.addClass.className.value=classname;
} 
function checkValuesEdit()
{
	var batch = document.editClass.batch.options[document.editClass.batch.selectedIndex].text;
	var university = document.editClass.university.options[document.editClass.university.selectedIndex].text;
	var degree = document.editClass.degree.options[document.editClass.degree.selectedIndex].text;
	var branch = document.editClass.branch.options[document.editClass.branch.selectedIndex].text;
var studyperiod = document.editClass.studyperiod.options[document.editClass.studyperiod.selectedIndex].text;
	var classname;
	if(batch!="Select Batch")
		classname = batch+" <?php echo CLASS_SEPRATOR ?> ";
	else
		classname = "[batch] "+" <?php echo CLASS_SEPRATOR ?> ";

	if(university!="Select University")
		classname += university+" <?php echo CLASS_SEPRATOR ?> ";
	else
		classname += "[university] "+" <?php echo CLASS_SEPRATOR ?> ";

	if(degree!="Select Degree")
		classname += degree+" <?php echo CLASS_SEPRATOR ?> ";
	else
		classname += "[degree] "+" <?php echo CLASS_SEPRATOR ?> ";

	if(branch!="Select Branch")
		classname += branch+" <?php echo CLASS_SEPRATOR ?> ";
	else
		classname += "[branch] "+" <?php echo CLASS_SEPRATOR ?> ";

	if(studyperiod!="Select Study Period")
		classname += studyperiod;
	else
		classname += "[studyperiod]";

	

	document.editClass.className.value=classname;
} 
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Class/listClassContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: listClass.php $
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
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/16/08    Time: 5:54p
//Updated in $/Leap/Source/Interface
//updated the bug no 0000074,0000073,0000072
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/12/08    Time: 1:08p
//Updated in $/Leap/Source/Interface
//added "Class seprator" constant
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/12/08    Time: 12:17p
//Updated in $/Leap/Source/Interface
//updated class status to "active","future","Past","unused"
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/11/08    Time: 5:16p
//Updated in $/Leap/Source/Interface
//file updated with dependency constraint and edit module
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/02/08    Time: 10:58a
//Created in $/Leap/Source/Interface
//intial checkin
?>
