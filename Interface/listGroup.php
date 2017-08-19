<?php
//-------------------------------------------------------
// Purpose: To generate the list of hostel from the database, and have add/edit/delete, search
// functionality
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GroupMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/Group/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Group Master </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(	new Array('srNo','#','width="2%"','',false),
								new Array('groupName','Group Name','width=15%','',true),
								new Array('groupShort','Short Name','width="12%"','',true),
								new Array('parentGroup','Parent Group','width="10%"','',true),
								new Array('groupTypeName','Group Type','width="10%"','',true),
								new Array('className','Class','width="15%"','',true), new Array('action','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddGroup';
editFormName   = 'EditGroup';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 200; // add/edit form height
deleteFunction = 'return deleteGroup';
divResultName  = 'results';
page=1; //default page
sortField = 'groupName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);
}

//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {


    var fieldsArray = new Array(	new Array("degree","<?php echo CHOOSE_DEGREE_NAME ?>"),
									//new Array("batch","<?php echo CHOOSE_BATCH_NAME?>"),
									//new Array("studyPeriod","<?php echo CHOOSE_PERIOD_NAME ?>"),
									new Array("groupName","<?php echo ENTER_GROUP_NAME ?>"),
									new Array("groupShort","<?php echo ENTER_GROUP_SHORT ?>"),
									new Array("groupTypeName","<?php echo CHOOSE_GROUP_TYPE ?>"));

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
            /*if((eval("frm."+(fieldsArray[i][0])+".value.length"))<3 && fieldsArray[i][0]=='stateName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("State Name can not be less than 3 characters");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
             if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]=='capacity')
             {
                alert("Enter only number");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;

             } */
            if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]!='groupName' &&  fieldsArray[i][0]!='groupShort' && fieldsArray[i][0]!='groupTypeName' && fieldsArray[i][0]!='degree') {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_ALPHABETS ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
    }
    if(act=='Add') {
        addGroup();
        return false;
    }
    else if(act=='Edit') {
        editGroup();
        return false;
    }
}

function checkParentGroup() {
	groupUrl = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxInitGroupName.php';

		 new Ajax.Request(groupUrl,
		   {
			 method:'post',
			 parameters: {	optional: (document.getElementById('optional').checked?1:0),
							degree: (document.addGroup.degree.value)
						 },
			  onCreate: function(transport){
				 // showWaitDialog(true);
			 },
			 onSuccess: function(transport){
				   // hideWaitDialog(true);
					j = eval('('+ transport.responseText+')');

					len = j.length;
					document.addGroup.parentGroup.length = null;
					addOption(document.addGroup.parentGroup, '', 'Select');
					for(i=0;i<len;i++) {
						addOption(document.addGroup.parentGroup, j[i].groupId, j[i].groupName);
					}
		   },
		   onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
		   });
	}

function checkEditParentGroup() {
	groupUrl = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxInitGroupName.php';

		 new Ajax.Request(groupUrl,
		   {
			 method:'post',
			  parameters: {	optional: (document.getElementById('editOptional').checked?1:0),
							degree: (document.editGroup.degree.value)
						 },
			  onCreate: function(transport){
				 // showWaitDialog(true);
			 },
			 onSuccess: function(transport){
				   // hideWaitDialog(true);
					j = eval('('+ transport.responseText+')');

					len = j.length;
					document.editGroup.parentGroup.length = null;
					addOption(document.editGroup.parentGroup, '', 'Select');
					for(i=0;i<len;i++) {
						addOption(document.editGroup.parentGroup, j[i].groupId, j[i].groupName);
					}
		   },
		   onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
		   });
	}


//-------------------------------------------------------
//THIS FUNCTION addOptionalSubject() IS USED TO ADD NEW GROUP TYPE
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function checkStatus() {
	if(document.getElementById('optional').checked == true) {
		if(document.addGroup.degree.value == '') {
			messageBox("<?php echo SELECT_CLASS ?>");
			document.addGroup.degree.focus();
			document.addGroup.optional.checked=false;
			return false;
		}
		else {
			checkOptionalSubject();
		}
	}
	else {
		document.getElementById('optionalSubject').disabled = true;
		document.addGroup.optionalSubject.length = null;
		addOption(document.addGroup.optionalSubject, '', 'Select');
		getGroupName();
	}
}

//-------------------------------------------------------
//THIS FUNCTION addOptionalSubject() IS USED TO ADD NEW GROUP TYPE
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function checkEditStatus() {
	if(document.editGroup.editOptional.checked == true) {
		if(document.editGroup.degree.value == '') {
			messageBox("<?php echo SELECT_CLASS ?>");
			document.editGroup.degree.focus();
			document.editGroup.editOptional.checked=false;
			return false;
		}
		else {
			document.editGroup.optionalSubject.disabled = false;
			checkOptionalSubjectInEdit();
		}
	}
	else {
		document.editGroup.optionalSubject.disabled = true;
		document.editGroup.optionalSubject.length = null;
		addOption(document.editGroup.optionalSubject, '', 'Select');
		getGroupName();
	}
}
//-------------------------------------------------------
//THIS FUNCTION is used to disable optional subject  in add
//
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function disableOptional(){
document.addGroup.optional.checked = false;
document.addGroup.optionalSubject.disabled = true;
}
//-------------------------------------------------------
//THIS FUNCTION is used to disable optional subject  in edit
//
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function disableEditOptional(){
document.editGroup.editOptional.checked = false;
document.editGroup.optionalSubject.disabled = true;
checkEditStatus();
}

//-------------------------------------------------------
//THIS FUNCTION addOptionalSubject() IS USED TO ADD NEW GROUP TYPE
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function checkOptionalSubject() {
	document.getElementById('optionalSubject').disabled = false;
	groupUrl = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxInitSubjectName.php';

		 new Ajax.Request(groupUrl,
		   {
			 method:'post',
			 asynchronous:false,
			 parameters: {	optional: (document.getElementById('optional').checked?1:0),
							degree: (document.getElementById('degree').value)
						 },
			  onCreate: function(transport){
				 // showWaitDialog(true);
			 },
			 onSuccess: function(transport){
				   // hideWaitDialog(true);
					j = eval('('+ transport.responseText+')');
					//j = transport.responseText;
					//alert(j[0][');
					len = j.length;
					//alert(len);
					document.addGroup.optionalSubject.length = null;
					addOption(document.addGroup.optionalSubject, '', 'Select');

					for(i=0;i<len;i++) {
						addOption(document.addGroup.optionalSubject, j[i]['subjectId'], j[i]['subjectCode']);
					}
		   },
		   onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
		   });
	}

	//-------------------------------------------------------
//THIS FUNCTION addOptionalSubject() IS USED TO ADD NEW GROUP TYPE
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function checkOptionalSubjectInEdit() { 
	//document.editGroup.optionalSubject.disabled = false;
	groupUrl = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxInitSubjectName.php';

		 new Ajax.Request(groupUrl,
		   {
			 method:'post',
			 parameters: {	optional: (document.getElementById('editOptional').checked?1:0),
							degree: (document.editGroup.degree.value)
						 },
			  onCreate: function(transport){
				 // showWaitDialog(true);
			 },
			 onSuccess: function(transport){
				   // hideWaitDialog(true);
					j = eval('('+ transport.responseText+')');
					//j = transport.responseText;
					//alert(j[0][');
					len = j.length;
					//alert(len);
					document.editGroup.optionalSubject.length = null;
					addOption(document.editGroup.optionalSubject, '', 'Select');

					for(i=0;i<len;i++) {
						addOption(document.editGroup.optionalSubject, j[i]['subjectId'], j[i]['subjectCode']);
					}
		   },
		   onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
		   });
	}


	//-------------------------------------------------------
//THIS FUNCTION addOptionalSubject() IS USED TO ADD NEW GROUP TYPE
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function checkEditOptionalSubject(classId,optionId,optionalSubjectId) {
	document.editGroup.optionalSubject.disabled = false;
	groupUrl = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxInitSubjectName.php';

		 new Ajax.Request(groupUrl,
		   {
			 method:'post',
			 parameters: {	optional: optionId,
							degree: classId
						 },
			  onCreate: function(transport){
				 // showWaitDialog(true);
			 },
			 onSuccess: function(transport){
				   // hideWaitDialog(true);
					j = eval('('+ transport.responseText+')');
					len = j.length;
					document.editGroup.optionalSubject.length = null;
					addOption(document.editGroup.optionalSubject, '', 'Select');

					for(i=0;i<len;i++) {
						addOption(document.editGroup.optionalSubject, j[i]['subjectId'], j[i]['subjectCode']);
					}

					document.editGroup.optionalSubject.value = optionalSubjectId;

		   },
		   onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
		   });
	}


/*function checkEditParentGroup() {
	if (document.getElementById('editOptional').checked == true) {
		document.editGroup.parentGroup.disabled = true;
		document.editGroup.parentGroup.value='';
	}
	else {
		document.editGroup.parentGroup.disabled = false ;
	}
}*/

//-------------------------------------------------------
//THIS FUNCTION addGroup() IS USED TO ADD NEW GROUP TYPE
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


function addGroup() { 
         url = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxInitAdd.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	degree: (document.addGroup.degree.value),
							//batch: (document.addGroup.batch.value),
							//studyPeriod: (document.addGroup.studyPeriod.value),
							groupName: (document.addGroup.groupName.value),
							groupShort: (document.addGroup.groupShort.value),
							optional:	(document.addGroup.optional.checked?1:0),
							optionalSubject : (document.addGroup.optionalSubject.value),
							parentGroup: (document.addGroup.parentGroup.value),
							groupTypeName: (document.addGroup.groupTypeName.value)
						},

               onCreate: function() {
                  showWaitDialog(true);
               },

             onSuccess: function(transport){
                      hideWaitDialog(true);
                      if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        flag = true;
                        getGroupName();
                        if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")){
                           blankValues();
                         }
                         else {
                                hiddenFloatingDiv('AddGroup');
								sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                       //      location.reload();
								return false;
                         }
                     }
                    else {
                        messageBox(trim(transport.responseText));

                        if (trim(transport.responseText)=="<?php echo CLASS_NOT_EXIST ?>"){
                            document.addGroup.degree.focus();
							return false;
                        }
						if (trim(transport.responseText)=="<?php echo PARENTCLASS_NOT_EXIST ?>"){
                            document.addGroup.degree.focus();
							return false;
                        }
                        if (trim(transport.responseText)=="<?php echo GROUP_NAME_EXIST ?>"){
							//document.addGroup.groupName.value='';
							document.addGroup.groupName.focus();
						}
						if (trim(transport.responseText)=="<?php echo OPTIONAL_SUBJECT_NOT_FOUND ?>"){
							//document.editGroup.groupName.value='';
							document.addGroup.optionalSubject.focus();
						}
						else {
							//document.addGroup.groupShort.value='';
							document.addGroup.groupShort.focus();
						}
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function getGroupName(){ //alert("getGroupName");
    var degree = document.addGroup.degree.value;
	 if (degree == '') {
		document.addGroup.parentGroup.length = null;
		addOption(document.addGroup.parentGroup, '', 'Select');
		 return false;
	 }
    groupUrl = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxInitGroupName.php';

         new Ajax.Request(groupUrl,
           {
             method:'post',
             parameters: 'degree='+degree,
			 asynchronous:false,
              onCreate: function(transport){
                 // showWaitDialog(true);
             },
             onSuccess: function(transport){
                   // hideWaitDialog(true);
                    j = eval('('+ transport.responseText+')');
					len = j.length;
					document.addGroup.parentGroup.length = null;
					addOption(document.addGroup.parentGroup, '', 'Select');
					for(i=0;i<len;i++) {
						addOption(document.addGroup.parentGroup, j[i].groupId, j[i].groupName);
					}
           },
           onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

function getGroupNameEdit(groupId,optionId,parentGroupId){ 
    groupUrl = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxInitGroupName.php';

         new Ajax.Request(groupUrl,
           {
             method:'post',
			 asynchronous:false,
             parameters: {id:groupId,
						  optionalId:optionId,
						 degree: (document.editGroup.degree.value)
						

						  },
              onCreate: function(transport){
                  //showWaitDialog(true);
             },
             onSuccess: function(transport){
                    //hideWaitDialog(true);

                    var j = eval('('+ transport.responseText+')');
					len = j.length;
					document.editGroup.parentGroup.length = null;
					addOption(document.editGroup.parentGroup, '', 'Select');
					for(i=0;i<len;i++) {
						addOption(document.editGroup.parentGroup, j[i].groupId, j[i].groupName);
					}
					document.editGroup.parentGroup.value = parentGroupId;
           },
           onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}
function getEditGroupName(){ 
    var degree = document.editGroup.degree.value;
	 if (degree == '') {
		document.editGroup.parentGroup.length = null;
		addOption(document.editGroup.parentGroup, '', 'Select');
		 return false;
	 }
    groupUrl = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxInitGroupName.php';

         new Ajax.Request(groupUrl,
           {
             method:'post',
             parameters: 'degree='+degree,
			 asynchronous:false,
              onCreate: function(transport){
                 // showWaitDialog(true);
             },
             onSuccess: function(transport){
                   // hideWaitDialog(true);
                    j = eval('('+ transport.responseText+')');
					len = j.length;
					document.editGroup.parentGroup.length = null;
					addOption(document.editGroup.parentGroup, '', 'Select');
					for(i=0;i<len;i++) {
						addOption(document.editGroup.parentGroup, j[i].groupId, j[i].groupName);
					}
           },
           onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}
//-------------------------------------------------------
//THIS FUNCTION DELETEGROUP() IS USED TO DELETE THE SPECIFIED RECORD
//FROM  SPECIFIED FILE THROUGH ID
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteGroup(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {

         url = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {groupId: id},

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
//THIS FUNCTION blankValues() IS USED TO BLANK VALUES OF TEXT BOXES
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function blankValues() {
    document.addGroup.degree.value = '';
    //document.addGroup.batch.value = '';
    //document.addGroup.studyPeriod.value = '';
    document.addGroup.groupName.value = '';
    document.addGroup.groupShort.value = '';
    document.addGroup.groupTypeName.value = '';
    document.addGroup.parentGroup.value='';
	document.addGroup.optionalSubject.length = null;
	document.getElementById('optionalSubject').disabled = true;
	addOption(document.addGroup.optionalSubject, '', 'Select');
	document.addGroup.optional.checked=false;
	document.getElementById('parentGroup').disabled = false;
    document.addGroup.degree.focus();
}

 //-------------------------------------------------------
//THIS FUNCTION IS USED TO UPDATE THE EXISTING RECOORD
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editGroup() {
         url = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxInitEdit.php';
         //getGroupNameEdit(document.editGroup.groupId.value,document.editGroup.optionValue.value);

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	groupId: (document.editGroup.groupId.value),
							degree: (document.editGroup.degree.value),
							//batch: (document.editGroup.batch.value),
							//studyPeriod: (document.editGroup.studyPeriod.value),
							groupName: (document.editGroup.groupName.value),
							groupShort: (document.editGroup.groupShort.value),
							optional:	(document.editGroup.editOptional.checked?1:0),
							optionalSubject : (document.editGroup.optionalSubject.value),
							parentGroup: (document.editGroup.parentGroup.value),
							groupTypeName: (document.editGroup.groupTypeName.value)},
               onCreate: function() {
                  showWaitDialog(true);
               },
               onSuccess: function(transport){
                     hideWaitDialog(true);
                     if (trim(transport.responseText)=="<?php echo CLASS_NOT_EXIST ?>"){
						    alert(trim(transport.responseText));
                            document.editGroup.degree.focus();
							return false;
                      }
					if (trim(transport.responseText)=='This group is used in time table, you cannot edit this group'){
							alert(trim(transport.responseText));
							document.editGroup.parentGroup.focus();
							return false;
					  }
					if (trim(transport.responseText)=='This group is allocated to student, you cannot edit this group'){
							alert(trim(transport.responseText));
							document.editGroup.parentGroup.focus();
							return false;
					  }

					  if (trim(transport.responseText)=='Cant make parent, it is already parent of child'){
							alert(trim(transport.responseText));
							document.editGroup.parentGroup.focus();
							return false;
					  }

					  if (trim(transport.responseText)=='Sub-group should not be parent of main group'){
							alert(trim(transport.responseText));
							document.editGroup.parentGroup.focus();
							return false;
					  }
					  if (trim(transport.responseText)=="<?php echo PARENT_NOT_EXIST ?>"){
							alert(trim(transport.responseText));
							document.editGroup.parentGroup.focus();
							return false;
					  }

                     else if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditGroup');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                        // location.reload();
                     }
					 else {
                        messageBox(trim(transport.responseText));

						if (trim(transport.responseText)=="<?php echo PARENTCLASS_NOT_EXIST ?>"){
                            document.editGroup.degree.focus();
							return false;
                        }
						if (trim(transport.responseText)=="<?php echo GROUP_NAME_EXIST ?>"){
							//document.editGroup.groupName.value='';
							document.editGroup.groupName.focus();
						}
						if (trim(transport.responseText)=="<?php echo OPTIONAL_SUBJECT_NOT_FOUND ?>"){
							//document.editGroup.groupName.value='';
							document.editGroup.optionalSubject.focus();
						}
						else {
							//document.editGroup.groupShort.value='';
							document.editGroup.groupShort.focus();
						}
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO populate the values
 // during editing the record
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id,classId) {
         url = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxGetValues.php';

         new Ajax.Request(url,
           {
             method:'post',
			 asynchronous:false,
             parameters: {groupId: id},

              onCreate: function() {
                  showWaitDialog(true);
              },
               onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditGroup');
                        messageBox("<?php echo GROUP_NOT_EXIST;?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        //return false;
                   }
                   var j = eval('('+trim(transport.responseText)+')');
                   document.editGroup.degree.value = j.classId;
                   document.editGroup.groupName.value = j.groupName;
                   document.editGroup.groupShort.value = j.groupShort;
				   if (j.isOptional == 1) {
					document.editGroup.editOptional.checked = true;
				   }
				   else {
					document.editGroup.editOptional.checked = false;
				   }
                   document.editGroup.groupTypeName.value = j.groupTypeId;
                   document.editGroup.groupId.value = j.groupId;
				   document.editGroup.optionValue.value = j.isOptional;
                   document.editGroup.degree.focus();

				   if(j.optionalSubjectId != '') {
					checkEditOptionalSubject(document.editGroup.degree.value,document.editGroup.optionValue.value,j.optionalSubjectId);
				   }
				   else {
					document.editGroup.optionalSubject.length = null;
					document.editGroup.optionalSubject.disabled = true;
					addOption(document.editGroup.optionalSubject, '', 'Select');
				   }

				   if(j.parentGroupId == 0) {
					document.editGroup.parentGroup.value = '';
					checkEditParentGroup();
				   }
				   else {
					getGroupNameEdit(document.editGroup.groupId.value,document.editGroup.optionValue.value,j.parentGroupId);	
				   }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
   path='<?php echo UI_HTTP_PATH;?>/displayGroupReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
   try{
     var a=window.open(path,"DisplayGroupReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
    }
    //window.open(path,"DisplayGroupReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayGroupCSV.php?'+qstr;
	window.location = path;
}

</script>

</head>
<body>
    <?php
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/Group/listGroupContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</SCRIPT>
</body>
</html>
<?php
// $History: listGroup.php $
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 4/13/10    Time: 4:19p
//Updated in $/LeapCC/Interface
//add field optional subjet for optional group
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 8/26/09    Time: 12:49p
//Updated in $/LeapCC/Interface
//Gurkeerat: fixed issue 1251,1250,1249
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 8/26/09    Time: 12:12p
//Updated in $/LeapCC/Interface
//fixed bug no.0001253
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 8/17/09    Time: 12:25p
//Updated in $/LeapCC/Interface
//show classes in drop down instead of degree, batch & study period
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/08/09    Time: 11:02a
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/04/09    Time: 11:06a
//Updated in $/LeapCC/Interface
//make space between print & export to excel button and sr. no. make left
//align
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/03/09    Time: 2:08p
//Updated in $/LeapCC/Interface
//fixed bug no.0000838
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/28/09    Time: 6:40p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000574, 0000575, 0000576, 0000577, 0000578, 0000579,
//0000580, 0000581
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/11/09    Time: 6:14p
//Updated in $/LeapCC/Interface
//put condition to check group name & code will not same at same classId
//on add & edit
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/11/09    Time: 3:52p
//Updated in $/LeapCC/Interface
//added optional field functionality
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/10/09    Time: 10:57a
//Updated in $/LeapCC/Interface
//put new field isOptional
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
//*****************  Version 24  *****************
//User: Jaineesh     Date: 9/15/08    Time: 6:35p
//Updated in $/Leap/Source/Interface
//checks in group
//
//*****************  Version 23  *****************
//User: Jaineesh     Date: 9/03/08    Time: 6:30p
//Updated in $/Leap/Source/Interface
//in edit, check the existance of parent of group
//
//*****************  Version 22  *****************
//User: Jaineesh     Date: 8/28/08    Time: 1:30p
//Updated in $/Leap/Source/Interface
//correct indentation
//
//*****************  Version 21  *****************
//User: Jaineesh     Date: 8/25/08    Time: 5:55p
//Updated in $/Leap/Source/Interface
//class should not be different from parent group class, modified in add
//or edit group
//
//*****************  Version 20  *****************
//User: Jaineesh     Date: 8/21/08    Time: 3:19p
//Updated in $/Leap/Source/Interface
//modified in messages
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 8/13/08    Time: 1:41p
//Updated in $/Leap/Source/Interface
//modified in alert message
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 8/13/08    Time: 1:17p
//Updated in $/Leap/Source/Interface
//modified to choose parent group name
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 8/11/08    Time: 1:50p
//Updated in $/Leap/Source/Interface
//modified for duplicate records
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 8/08/08    Time: 7:59p
//Updated in $/Leap/Source/Interface
//modified for wait dialog box
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 8/07/08    Time: 3:47p
//Updated in $/Leap/Source/Interface
//modified for edit or delete messages
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 8/01/08    Time: 2:44p
//Updated in $/Leap/Source/Interface
//modified onCreate & onSuccess functionso
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 7/29/08    Time: 6:26p
//Updated in $/Leap/Source/Interface
//modified in parent group name
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 7/29/08    Time: 5:04p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 7/29/08    Time: 11:14a
//Updated in $/Leap/Source/Interface
//modified in code if class ID is 0 for add & edit
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 7/29/08    Time: 11:05a
//Updated in $/Leap/Source/Interface
//modification in code if classId is 0
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 7/19/08    Time: 6:41p
//Updated in $/Leap/Source/Interface
//modification in sorting field of group name
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/18/08    Time: 4:04p
//Updated in $/Leap/Source/Interface
//change alert into messagebox
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/17/08    Time: 8:04p
//Updated in $/Leap/Source/Interface
//modified for add & edit
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/15/08    Time: 6:05p
//Updated in $/Leap/Source/Interface
//modified for parent group id
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/08/08    Time: 12:06p
//Updated in $/Leap/Source/Interface
//remove the alert to check the pop up for add
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/05/08    Time: 11:05a
//Updated in $/Leap/Source/Interface
//modified in group edit function
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/03/08    Time: 7:03p
//Created in $/Leap/Source/Interface
//ajax functions add, edit, delete for group list
?>