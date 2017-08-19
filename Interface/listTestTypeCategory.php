<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Offense ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (22.12.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TestTypeCategoryMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Test Type Category Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var topPos = 0;
var leftPos = 0;

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

sortField = 'testTypeName';
sortOrderBy    = 'ASC';

winLayerWidth  = 390; //  add/edit form width
winLayerHeight = 250; // add/edit form height

// ajax search results ---end ///

///////*function for help details*/////

function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');   
      return false;
    }
    
    if(document.getElementById('helpChk').checked == false) {
      return false;
    }
    //document.getElementById('divHelpInfo').innerHTML=title; 
    document.getElementById('helpInfo').innerHTML= msg;   
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);
    
    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}

///// function for help detils ends here/////

function getTestTypeCategoryData(){
  url = '<?php echo HTTP_LIB_PATH;?>/TestType/ajaxInitTestTypeCategoryList.php';
  //var value = document.getElementById('document.searchForm.searchbox_h').value; 
   var value = document.searchForm.searchbox.value; 
  var tableColumns =    new Array(
                        new Array('srNo','#','width="4%" align="left"',false),
                        new Array('testTypeName','Name','width="20%" align="left"',true),
						new Array('testTypeAbbr','Abbr.','width="5%" align="left"',true),
                        new Array('examType','Exam Type','width="10%" align="left"',true),
						new Array('subjectTypeName','Subject Type','width="15%" align="left"',true),
						new Array('showName','Show Status','width="12%" align="left"',true),
						new Array('isAttendanceCategory','Attendance Category','width="20%" align="left"',true),
						new Array('colorCode','Color','width="10%" align="left"',true),
                        new Array('action','Action','width="10%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','testTypeName','ASC','TestTypeCategoryResultDiv','TestTypeCategoryActionDiv','',true,'listObj',tableColumns,'editWindow','deleteTestTypeCategory','&searchbox='+trim(value));
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id,dv,w,h) {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Test Type Category';
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
    
   
    var fieldsArray = new Array(new Array("testTypeCategoryName","<?php echo ENTER_TESTTYPECATEGORY_NAME ?>"),
								new Array("testTypeCategoryAbbr","<?php echo ENTER_TESTTYPECATEGORY_ABBR ?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
         
       /* else if((eval("frm."+(fieldsArray[i][0])+".value.length"))<2 && fieldsArray[i][0]=='testTypeName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo TESTTYPE_NAME_LENGTH ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }         */ 
            
        /*else  if (!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]=='offenseName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_STRING ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
                }*/
            
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
  
    if(document.getElementById('testTypeCategoryId').value=='') {
        //alert('add slot');
		addTestTypeCategory();
        return false;
    }
    else{
		//alert('edit slot');
        editTestTypeCategory();
        return false;
    }
}

function emptySlotId() {
	document.getElementById('offenseId').value='';
}

//-------------------------------------------------------
//THIS FUNCTION addTestTypeCategory() IS USED TO ADD NEW TEST TYPE CATEGORY
//
//Author : Jaineesh
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addTestTypeCategory() {
		
         url = '<?php echo HTTP_LIB_PATH;?>/TestType/ajaxInitTestTypeCategoryAdd.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                testTypeCategoryName:   trim(document.TestTypeCategoryDetail.testTypeCategoryName.value),
				testTypeCategoryAbbr:   trim(document.TestTypeCategoryDetail.testTypeCategoryAbbr.value),
				examType:				trim(document.TestTypeCategoryDetail.examType.value),
				subjectType:			trim(document.TestTypeCategoryDetail.subjectType.value),
				showName:				trim(document.TestTypeCategoryDetail.showName.value),
				attendanceCategory:		trim(document.TestTypeCategoryDetail.attendanceCategory.value),
				colorCode:				trim(document.TestTypeCategoryDetail.subjectColor.value)

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
                             hiddenFloatingDiv('TestTypeCategoryActionDiv');
                             getTestTypeCategoryData();
                             return false;
                         }
                     }
					 
					 else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo TEST_TYPE_CATEGORY_EXIST; ?>'){
							document.TestTypeCategoryDetail.testTypeCategoryName.focus();
						}
						else if (trim(transport.responseText)=='<?php echo TEST_TYPE_CATEGORY_ABBR_EXIST; ?>'){
							document.TestTypeCategoryDetail.testTypeCategoryAbbr.focus();
						}
						else if (trim(transport.responseText)=='<?php echo EXTERNAL_EXAMTYPE_ALREADY_EXIST; ?>'){
							document.TestTypeCategoryDetail.examType.focus();
						}
						//else if (trim(transport.responseText)=='<?php echo ATTENDANCE_CATEGORY_ALREADY_EXIST; ?>')
						{
							document.TestTypeCategoryDetail.attendanceCategory.focus();
						}
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A PERIOD SLOT
//  id=testTypeCategoryId
//Author : Jaineesh
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteTestTypeCategory(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/TestType/ajaxInitTestTypeCategoryDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {testTypeCategoryId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         getTestTypeCategoryData(); 
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
    document.getElementById('examType').disabled = false; 
    document.getElementById('subjectType').disabled = false;
	document.getElementById('divHeaderId').innerHTML='&nbsp; Add Test Type Category';
    document.TestTypeCategoryDetail.reset();
    document.getElementById('subjectColor1').style.backgroundColor=rgb2hex(255,255,255);
    document.getElementById('test1').style.backgroundColor=rgb2hex(255,255,255);
	document.TestTypeCategoryDetail.testTypeCategoryName.value = '';
	document.TestTypeCategoryDetail.testTypeCategoryAbbr.value = '';
	document.getElementById('testTypeCategoryId').value='';
	document.TestTypeCategoryDetail.examType.value = 'PC';
	document.TestTypeCategoryDetail.showName.value = '1';
	document.TestTypeCategoryDetail.attendanceCategory.value = 0;
	document.TestTypeCategoryDetail.testTypeCategoryName.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A TEST TYPE CATEGORY
//
//Author : Jaineesh
// Created on : (22.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editTestTypeCategory() {
         var url = '<?php echo HTTP_LIB_PATH;?>/TestType/ajaxInitTestTypeCategoryEdit.php';
          
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
					testTypeCategoryId: (document.TestTypeCategoryDetail.testTypeCategoryId.value),
					testTypeCategoryName: trim(document.TestTypeCategoryDetail.testTypeCategoryName.value),
					testTypeCategoryAbbr:   trim(document.TestTypeCategoryDetail.testTypeCategoryAbbr.value),
					examType:				trim(document.TestTypeCategoryDetail.examType.value),
					subjectType:			trim(document.TestTypeCategoryDetail.subjectType.value),
					showName:				(document.TestTypeCategoryDetail.showName.value),
					attendanceCategory:		(document.TestTypeCategoryDetail.attendanceCategory.value),
   					colorCode:				trim(document.TestTypeCategoryDetail.subjectColor.value)

             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('TestTypeCategoryActionDiv');
                         getTestTypeCategoryData();
						 //emptySlotId();
                         return false;

                     }
                   else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo TEST_TYPE_CATEGORY_EXIST; ?>'){
							document.TestTypeCategoryDetail.testTypeCategoryName.focus();
						}
						else if (trim(transport.responseText)=='<?php echo TEST_TYPE_CATEGORY_ABBR_EXIST; ?>'){
							document.TestTypeCategoryDetail.testTypeCategoryAbbr.focus();
						}
						else if (trim(transport.responseText)=='<?php echo EXTERNAL_EXAMTYPE_ALREADY_EXIST; ?>'){
							document.TestTypeCategoryDetail.examType.focus();
						}
						//else if (trim(transport.responseText)=='<?php echo ATTENDANCE_CATEGORY_ALREADY_EXIST; ?>')
							{
							document.TestTypeCategoryDetail.attendanceCategory.focus();
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         var url = '<?php echo HTTP_LIB_PATH;?>/TestType/ajaxTestTypeCategoryGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {testTypeCategoryId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();


		if(trim(transport.responseText)==0) {
                   hiddenFloatingDiv('TestTypeCategoryActionDiv');
                        messageBox("<?php echo TEST_TYPE_NOT_EXIST; ?>");
                        getTestTypeCategoryData();    
			
			 }

		
                   var j = eval('('+trim(transport.responseText)+')');
           
			document.TestTypeCategoryDetail.testTypeCategoryName.value = j.testTypeName;
			document.TestTypeCategoryDetail.testTypeCategoryId.value = j.testTypeCategoryId;
			document.TestTypeCategoryDetail.testTypeCategoryAbbr.value = j.testTypeAbbr;
			document.TestTypeCategoryDetail.examType.value	= j.examType;
			document.TestTypeCategoryDetail.subjectType.value = j.subjectTypeId;
			if(j.find==1){	
			  document.getElementById('testCategoryDiv').innerHTML=j.msg;
			  document.getElementById('examType').disabled = true; 
			  document.getElementById('subjectType').disabled = true;
			}
			document.TestTypeCategoryDetail.showName.value	= j.showCategory;
			document.TestTypeCategoryDetail.attendanceCategory.value = j.isAttendanceCategory;
			document.TestTypeCategoryDetail.subjectColor.value = j.colorCode;
			document.TestTypeCategoryDetail.subjectColor.style.backgroundColor='#'+j.colorCode
			document.getElementById('test1').style.backgroundColor ='#'+j.colorCode
			document.TestTypeCategoryDetail.testTypeCategoryName.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
   sortField = listObj.sortField;
   sortOrderBy = listObj.sortOrderBy;
   path='<?php echo UI_HTTP_PATH;?>/displayTestTypeCategoryReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplayTestTypeCategoryReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
	sortField = listObj.sortField;
	sortOrderBy = listObj.sortOrderBy;
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayTestTypeCategoryCSV.php?'+qstr;
	window.location = path;
}

window.onload=function(){
        //loads the data
        getTestTypeCategoryData();    
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
		require_once(TEMPLATES_PATH . "/TestType/listTestTypeCategoryContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	

<script language="javascript">
//For add div
colorTable('color1','test1','subjectColor1');
</script>
</body>
</html>
<?php 
// $History: listTestTypeCategory.php $
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 1/20/10    Time: 5:08p
//Updated in $/LeapCC/Interface
//done changes to Assign Colour scheme to test type and refect this
//colour in student tab. FCNS No. 1102
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 10/20/09   Time: 1:02p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0001811, 0001800, 0001798, 0001795, 0001793, 0001782,
//0001800, 0001813
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 7/25/09    Time: 3:21p
//Updated in $/LeapCC/Interface
//fixed bug no.0000690 & show print & export to excel button
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 6/12/09    Time: 2:54p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000040,0000051,0000052,0000053
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/03/09    Time: 6:03p
//Updated in $/LeapCC/Interface
//add new filed test type category abbr.
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 5/26/09    Time: 5:47p
//Updated in $/LeapCC/Interface
//fixed bug No.8 of  Issues [26-May-09]1.doc dated 26.05.09
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:30p
//Updated in $/LeapCC/Interface
//modified for test type category
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/10/09    Time: 2:43p
//Updated in $/LeapCC/Interface
//modified to save only selected am, pm
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/26/09    Time: 4:39p
//Updated in $/LeapCC/Interface
//add new fields in test type category
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/17/09    Time: 4:52p
//Created in $/LeapCC/Interface
//add new file test type category
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/09/09    Time: 1:04p
//Updated in $/Leap/Source/Interface
//add test type category master
//modified test type
//Bulk Attendance, 
//Daily Attendance 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/24/09    Time: 11:30a
//Created in $/Leap/Source/Interface
//new file for test type category
//

?>
