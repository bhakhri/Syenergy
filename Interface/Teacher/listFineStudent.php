<?php
//----------------------------------------------------------------------------------------------------------
// THIS FILE SHOWS A LIST OF STUDENT FINE IN CATEGORIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Rajeev Aggarwal
// Created on : (03.07.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineStudentMaster');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fine Student Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                         new Array('srNo','#','width="2%"','',false), 
                         new Array('rollNo','Roll No.','"width=12%"','',true) , 
                         new Array('universityRollNo','Univ. Roll No.','"width=15%"','',true) , 
                         new Array('fullName','Student Name','width="15%"','',true) , 
						 new Array('fineCategoryAbbr','Fine Category','width="12%"','',true) , 
						 new Array('amount','Amount','width="8%"','align="right"',true) , 
						 new Array('fineDate','Fine Date','width="10%"','align="center"',true) , 
						 new Array('noDues','No Dues','width="10%"','',true) , 
						 new Array('paid','Is Paid?','width="8%"','',true) , 
						 new Array('status','Status','width="10%"','',true) , 
                         new Array('action1','Action','width="2%"','align="right"',false)
                         );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitStudentFineList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFineStudent';   
editFormName   = 'EditFineStudent';
winLayerWidth  = 360; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteFineCategory';
divResultName  = 'results';
page=1; //default page
sortField = 'fineDate';
sortOrderBy    = 'DESC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Rajeev Aggarwal
// Created on : (13.6.2008)
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
//Author : Rajeev Aggarwal
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var cdate="<?php echo date('Y-m-d'); ?>";
function validateAddForm(frm, act) {
    
    if(act=='Add'){

	   if(trim(document.AddFineStudent.studentId.value)==''){
           messageBox("<?php echo STUDENT_EMPTY;?>"); 
           document.AddFineStudent.studentRollNo.focus();
           return false;
       }	
       if(trim(document.AddFineStudent.studentRollNo.value)==''){
           messageBox("<?php echo STUENT_ROLL_NO_EMPTY;?>"); 
           document.AddFineStudent.studentRollNo.focus();
           return false;
       }
    }
    else if(act='Edit'){
        if(trim(document.EditFineStudent.studentRollNo.value)==''){
           messageBox("<?php echo STUENT_ROLL_NO_EMPTY;?>"); 
           document.EditFineStudent.studentRollNo.focus();
           return false;
       }        
    }

    var fieldsArray = new Array(
        new Array("fineCategoryId","<?php echo ENTER_FINE_CATEGORY; ?>"),
        new Array("fineAmount","<?php echo ENTER_FINE_AMOUNT; ?>"),
		new Array("remarksTxt","<?php echo ENTER_FINE_REASON; ?>")
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
            //unsetAlertStyle(fieldsArray[i][0]);
			 if(document.AddFineStudent.fineAmount.value){
			 
				 reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
				 if (!reg.test(document.AddFineStudent.fineAmount.value)){

					messageBox("<?php echo ENTER_FINE_AMOUNT_TO_NUM; ?>");
					document.AddFineStudent.fineAmount.focus();
					return false;
				 }
				 else if(document.AddFineStudent.fineAmount.value==0){
				 
					messageBox("<?php echo ENTER_FINE_AMOUNT;?>");
					document.AddFineStudent.fineAmount.focus();
					return false;
				 }

				if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<10 && fieldsArray[i][0]=='remarksTxt' ) {
					 
					messageBox("<?php echo FINE_REASON_LENGTH;?>"); 
					eval("frm."+(fieldsArray[i][0])+".focus();");
					return false;
					break;
				} 
			 }
        }
        
		if(act=='Add'){
		   if(!dateDifference(document.AddFineStudent.fineDate1.value,cdate,"-")){

			   messageBox("<?php echo FINE_DATE_VALIDATION;?>"); 
			   document.AddFineStudent.fineDate1.focus();
			   return false;
		   }
		}
		else if(act='Edit'){
			if(!dateDifference(document.EditFineStudent.fineDate2.value,cdate,"-")){

			   messageBox("<?php echo FINE_DATE_VALIDATION;?>"); 
			   document.EditFineStudent.fineDate2.focus(); 
			   return false;
		   }
		   
		    if(document.EditFineStudent.fineAmount.value){
				 
					 reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
					 if (!reg.test(document.EditFineStudent.fineAmount.value)){

						messageBox("<?php echo ENTER_FINE_AMOUNT_TO_NUM; ?>");
						document.EditFineStudent.fineAmount.focus();
						return false;
					 }
					 else if(document.EditFineStudent.fineAmount.value==0){
					 
						messageBox("<?php echo ENTER_FINE_AMOUNT;?>");
						document.EditFineStudent.fineAmount.focus();
						return false;
					 }

					if(trim(document.EditFineStudent.remarksTxt.value).length<10) {
						 
						messageBox("<?php echo FINE_REASON_LENGTH;?>"); 
						document.EditFineStudent.remarksTxt.focus();
						return false;
						 
					} 
				 }
		}
     
    }
    if(act=='Add') {
        addFineStudent();
        return false;
    }
    else if(act=='Edit') {
        editFineStudent();
        return false;
    }
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO ADD A NEW Fine Category
// Author : Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function addFineStudent() {

         var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitStudentFineAdd.php';
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {
                   classId: (trim(document.AddFineStudent.classId.value)), 
                   studentId: (trim(document.AddFineStudent.studentId.value)), 
				   amount: (trim(document.AddFineStudent.fineAmount.value)), 
				   fineCategoryId: (trim(document.AddFineStudent.fineCategoryId.value)), 
				   fineDate1: (trim(document.AddFineStudent.fineDate1.value)), 
				   remarksTxt: (trim(document.AddFineStudent.remarksTxt.value)), 
                   dueStatus: (trim(document.AddFineStudent.dueStatus.value))
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
                             hiddenFloatingDiv('AddFineStudent');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                     else if("<?php echo FINE_ALREADY_EXIST; ?>" == trim(transport.responseText)){
                         messageBox("<?php echo FINE_ALREADY_EXIST ;?>"); 
                         document.AddFineStudent.studentRollNo.focus();
                     } 
                     else {
                        messageBox(trim(transport.responseText));  
                     } 

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO DELETE A FINE CATEGORY
// id=fineCategoryId
// Author : Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function deleteFineCategory(id) {

	 if(false===confirm("Do you want to delete this record?")) {
		 return false;
	 }
	 else {   
	
	var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitStudentFineDelete.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {
			 fineStudentId: id
		 },
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



//--------------------------------------------------------------------
// THIS FUNCTION IS USED TO CLEAN UP THE "AddFineCategory" DIV
// Author : Rajeev Aggarwal
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------
function blankValues() {
   document.AddFineStudent.reset();
   document.getElementById('studentName1').innerHTML = "";
   document.getElementById('className1').innerHTML = "";
   document.AddFineStudent.studentId.value='';
   document.AddFineStudent.classId.value='';
   document.AddFineStudent.studentRollNo.focus();
}


//-------------------------------------------------------
// THIS FUNCTION IS USED TO EDIT A Fine Category
// Author : Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function editFineStudent() {

         var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitStudentFineEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                  classId: (trim(document.EditFineStudent.classId.value)), 
                  fineStudentId: (trim(document.EditFineStudent.fineStudentId.value)), 
				  studentId: (trim(document.EditFineStudent.studentId.value)), 
				  amount: (trim(document.EditFineStudent.fineAmount.value)), 
				  oldDueAmount: (trim(document.EditFineStudent.oldDueAmount.value)), 
				  fineCategoryId: (trim(document.EditFineStudent.fineCategoryId.value)), 
				  fineDate2: (trim(document.EditFineStudent.fineDate2.value)), 
				  remarksTxt: (trim(document.EditFineStudent.remarksTxt.value)), 
				  oldDueStatus: (trim(document.EditFineStudent.oldDueStatus.value)), 
                  dueStatus: (trim(document.EditFineStudent.dueStatus.value))
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditFineStudent');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else if("<?php echo FINE_ALREADY_EXIST; ?>" == trim(transport.responseText)){
                         messageBox("<?php echo FINE_ALREADY_EXIST ;?>"); 
                         document.EditFineStudent.fineCategoryId.focus();
                     } 
                     else {
                        messageBox(trim(transport.responseText));  
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO POPULATE "EditFineCategory" DIV
// Author : Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function populateValues(id) {

         var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetStudentFineValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 fineStudentId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditFineStudent');
                        messageBox("<?php echo FINE_CATEGORY_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                   }
                    
                   var j = eval('('+trim(transport.responseText)+')');
                 //alert(transport.responseText);
				   document.EditFineStudent.studentRollNo.readonly=true;
                   document.EditFineStudent.studentRollNo.value   = j.rollNo;
				   document.getElementById('studentName2').innerHTML = j.fullName;
                   document.getElementById('className2').innerHTML = j.className;

                   document.EditFineStudent.fineCategoryId.value   = j.fineCategoryId;
                   document.EditFineStudent.fineDate2.value = j.fineDate;
				   document.EditFineStudent.fineAmount.value = j.amount;
				   document.EditFineStudent.oldDueAmount.value = j.amount;
				   
				   document.EditFineStudent.remarksTxt.value = j.reason;
				   document.EditFineStudent.dueStatus.value = j.noDues;
				   document.EditFineStudent.oldDueStatus.value = j.noDues;
				   document.EditFineStudent.fineStudentId.value = j.fineStudentId;
				   document.EditFineStudent.studentId.value = j.studentId;
                   document.EditFineStudent.fineCategoryId.focus();
                  

             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print fine category report*/
function printReport() {

    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/Teacher/fineStudentReportPrint.php?'+qstr;
    window.open(path,"DegreeReport","status=1,menubar=1,scrollbars=1, width=700, height=400, top=100,left=50");
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO get student details
//
//Author : Rajeev Aggarwal
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getStudent(value,act) {

         if(trim(value)==''){
             return false;
         }
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetStudentValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {rollNo: value},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        messageBox("<?php echo STUDENT_NOT_EXIST; ?>");
                         if(act=='Add'){   
                            //document.AddDiscipline.studentRollNo.focus();   
                         }
                        else if(act=='Edit') {
                            //document.EditDiscipline.studentRollNo.focus();
                         }
                        return false;
                    }
                    j = eval('('+transport.responseText+')');

                if(act=='Add'){   
                   //document.AddDiscipline.studentName.value = j.studentName;
                   document.getElementById('studentName1').innerHTML = j.studentName;
                   document.getElementById('className1').innerHTML = j.className;
				    
                   document.AddFineStudent.classId.value = j.classId;
                   document.AddFineStudent.studentId.value = j.studentId;
                   
                }
               else if(act=='Edit') {
                   //document.EditDiscipline.studentName.value = j.studentName;
                   document.getElementById('studentName2').innerHTML = j.studentName;
                   document.getElementById('className2').innerHTML = j.className;
                   document.EditDiscipline.classId.value = j.classId;
                   document.EditDiscipline.studentId.value = j.studentId;
               }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
window.onload=function(){
    document.searchForm.reset();
    var roll = document.getElementById("studentRollNo");
    autoSuggest(roll);
}
/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='fineStudentReportCSV.php?'+qstr;
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listFineStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listFineStudent.php $ 
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 28/01/10   Time: 11:31
//Updated in $/LeapCC/Interface/Teacher
//Added "Univ. Roll No." column in student list display
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 09-11-06   Time: 3:54p
//Updated in $/LeapCC/Interface/Teacher
//In this if wrong roll no was entered then validations was not working
//during SAVE done in both admin and teacher login
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface/Teacher
//added code for autosuggest functionality
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/29/09    Time: 4:53p
//Updated in $/LeapCC/Interface/Teacher
//fixed bugs 703,704,705,706,707,708,709,733,742,743,744,745,750,
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/09/09    Time: 10:47a
//Updated in $/LeapCC/Interface/Teacher
//Updated module with dependency constraint
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/08/09    Time: 7:21p
//Created in $/LeapCC/Interface/Teacher
//intial checkin
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/06/09    Time: 6:35p
//Updated in $/LeapCC/Interface
//updated validation messages
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/03/09    Time: 4:29p
//Created in $/LeapCC/Interface
//Intial checkin for fine student
?>