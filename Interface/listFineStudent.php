<?php
//----------------------------------------------------------------------------------------------------------
// THIS FILE SHOWS A LIST OF STUDENT FINE IN CATEGORIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Rajeev Aggarwal
// Created on : (03.07.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineStudentMaster');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1); 
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn();
}
else{
  UtilityManager::ifNotLoggedIn();
}
UtilityManager::headerNoCache();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Fine Master </title>
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

var tableHeadArray = new Array(new Array('srNo',                '#',            'width="2%"','',false),
                               new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%"','align=\"left\"',false),  
                               new Array('rollNo',              'Roll No.',     'width="10%"','',true), 
                               new Array('studentName',         'Name',         'width="12%"','',true), 
                               new Array('className',           'Class',        'width="20%"','',true), 
                               new Array('fineCategoryAbbr',    'Fine',         'width="15%"','',true), 
                               new Array('fineDate',            'Date',         'width="8%" align="center"','align="center"',true), 
                               new Array('amount',              'Amount',       'width="10%" align="right"','align="right"',true), 
                               new Array('issueEmployee',       'Assigned By',  'width="15%"','',true), 
                               new Array('status',              'Status',       'width="12%"','',true), 
                               new Array('action1',             'Action',       'width="5%"','align="center"',false));
/*
var tableHeadArray = new Array(
			                     new Array('srNo','#','width="2%"','',false),
			                     new Array('rollNo','Roll No.','"width=10%"','',true) ,			                     
			                 	 new Array('studentName','Name','width="10%"','',true) ,
                                 new Array('className','Class','width="15%"','',true) ,
                                 new Array('fineCategoryAbbr','Fine','width="10%"','',true) ,
                                 new Array('fineDate','Date','width="10%"','align="center"',true) ,                                
                                 new Array('amount','Amount','width="8%"','align="right"',true) ,
								 //new Array('paid','Paid','width="8%"','',true) ,
							     new Array('issueEmployee','Assigned By','width="10%"','',true),
								 new Array('status','Status','width="8%"','',true) ,
								 //new Array('approvedBy','Approved By','width="10%"','',true) ,	
			                     new Array('action1','Action','width="2%"','align="center"',false)
			                  );
*/
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxInitStudentFineList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
addFormName    = 'AddFineStudent';
editFormName   = 'EditFineStudent';
winLayerWidth  = 360; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteFineCategory';
divResultName  = 'results';
page=1; //default page
sortField = 'fineDate';
sortOrderBy    = 'DESC';
ttFineCategoryId ='';
// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
valShow = '0';
var queryString ='';

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Rajeev Aggarwal
// Created on : (13.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var cdate="<?php echo date('Y-m-d'); ?>";
function validateAddForm(frm, act) {

    if(act=='Add'){
       if(trim(document.AddFineStudent.studentId.value)==''){                       
           messageBox("<?php echo STUENT_ROLL_NO_EMPTY;?>");
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
    
    if(act=='Add'){ 
      form = document.AddFineStudent;
    }
    else {
      form  = document.EditFineStudent; 
    }
   
   var findClass=0; 
   var len= form.fineAllowClassId.options.length;
   var t=form.fineAllowClassId;
   if(len>0) {
      for(k=1;k<len;k++) { 
         if(t.options[k].value == form.classId.value) {       
           findClass=1;  
           break;
         }
      } 
   }
   
   if(findClass==0) {
      messageBox("Fine cannot be assigned for this class");
      form.studentRollNo.focus();
      return false;   
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function addFineStudent() {

         var url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxInitStudentFineAdd.php';
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {
                   studentId: (trim(document.AddFineStudent.studentId.value)),
				   amount: (trim(document.AddFineStudent.fineAmount.value)),
				   fineCategoryId: (trim(document.AddFineStudent.fineCategoryId.value)),

				   fineDate1: (trim(document.AddFineStudent.fineDate1.value)),
				   remarksTxt: (trim(document.AddFineStudent.remarksTxt.value)),
                   //dueStatus: (trim(document.AddFineStudent.dueStatus.value)),
                   classId: (trim(document.AddFineStudent.classId.value))
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
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

function deleteAllFine() {
   /*
   if(false===confirm("Do you want to delete all record?")) {
     return false;
   } 
   */
   
   formx = document.listForm;
   fineIds='';
   for(var i=1;i<formx.length;i++){
     if(formx.elements[i].type=="checkbox" && (formx.elements[i].name=="chb[]")){
        if(formx.elements[i].checked){
           if(fineIds=='') {
             fineIds=formx.elements[i].value; 
           }
           else {
             fineIds = fineIds + ',' +formx.elements[i].value; 
           }
        }
     }
   }
  
   if(fineIds=='') {
     messageBox("Please select record");     
     return false;  
   }  
   deleteFineCategory(fineIds); 
   return false;
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO DELETE A FINE CATEGORY
// id=fineCategoryId
// Author : Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function deleteFineCategory(id) {

	 if(false===confirm("Do you want to delete fine record(s)?")) {
		 return false;
	 }
	 else {

	var url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxInitStudentFineDelete.php';
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------
function blankValues(){
   document.AddFineStudent.reset();
   document.getElementById('studentName1').innerHTML = "";
   document.getElementById('className1').innerHTML = "";

   document.AddFineStudent.studentId.value='';
   document.AddFineStudent.classId.value='';   
   document.AddFineStudent.fineCategoryId.length = null;
   addOption(document.AddFineStudent.fineCategoryId, '', 'Select');
   document.AddFineStudent.studentRollNo.focus();
}


//-------------------------------------------------------
// THIS FUNCTION IS USED TO EDIT A Fine Category
// Author : Rajeev Aggarwal
// Created on : (02.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function editFineStudent() {

         var url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxInitStudentFineEdit.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                  fineStudentId: (trim(document.EditFineStudent.fineStudentId.value)),
				  studentId: (trim(document.EditFineStudent.studentId.value)),
				  amount: (trim(document.EditFineStudent.fineAmount.value)),
				  oldDueAmount: (trim(document.EditFineStudent.oldDueAmount.value)),
				  fineCategoryId: (trim(document.EditFineStudent.fineCategoryId.value)),
				  fineDate2: (trim(document.EditFineStudent.fineDate2.value)),
				  remarksTxt: (trim(document.EditFineStudent.remarksTxt.value)),
				  oldDueStatus: (trim(document.EditFineStudent.oldDueStatus.value)),
                  //dueStatus: (trim(document.EditFineStudent.dueStatus.value)),
                  classId: (trim(document.EditFineStudent.classId.value))
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
					 else if("<?php echo FINE_ALREADY_PAID; ?>" == trim(transport.responseText)){
                         messageBox("<?php echo FINE_ALREADY_PAID ;?>");
                         document.EditFineStudent.fineCategoryId.focus();
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO POPULATE "EditFineCategory" DIV
// Author : Saurabh Thukral
// Created on : (27.07.2012)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function populateValues(id) {

         ttFineCategoryId ='';
         var url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxGetStudentFineValues.php';
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
                     return false;
                   }
                   var j = eval('('+trim(transport.responseText)+')');
				   document.EditFineStudent.studentRollNo.readonly=true;
                   document.EditFineStudent.studentRollNo.value   = j[0][0].rollNo;
				   document.getElementById('studentName2').innerHTML = j[0][0].fullName;
                   document.getElementById('className2').innerHTML = j[0][0].className;
                   document.EditFineStudent.fineDate2.value = j[0][0].fineDate;
				   document.EditFineStudent.fineAmount.value = j[0][0].amount;
				   document.EditFineStudent.oldDueAmount.value = j[0][0].amount;
				   document.EditFineStudent.remarksTxt.value = j[0][0].reason;
				   document.EditFineStudent.fineStudentId.value = j[0][0].fineStudentId;
				   document.EditFineStudent.studentId.value = j[0][0].studentId;
                   document.EditFineStudent.classId.value = j[0][0].classId;
                   ttFineCategoryId = j[0][0].fineCategoryId;
                   var value = j[0][0].rollNo;
                   var instituteId = j[0][0].instituteId;
                   getStudent(value,'Edit');
                   return false;         
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO get student details
//
// Author : Saurabh Thukral
// Created on : (27.07.2012)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getStudent(value,act) {

     if(act=='Add') { 
       document.getElementById('studentName1').innerHTML = '';
       document.getElementById('className1').innerHTML = '';
       document.AddFineStudent.classId.value = '';
       document.AddFineStudent.studentId.value = '';
       document.AddFineStudent.fineCategoryId.length = null;  
       document.AddFineStudent.fineAllowClassId.length = null;  
       addOption(document.AddFineStudent.fineCategoryId, '', 'Select');  
       addOption(document.AddFineStudent.fineAllowClassId, '', 'Select');  
     }
     else {
       document.getElementById('studentName2').innerHTML = '';
       document.getElementById('className2').innerHTML = '';
       document.EditFineStudent.classId.value = '';
       document.EditFineStudent.studentId.value = '';
       document.EditFineStudent.fineCategoryId.length = null;  
       document.EditFineStudent.fineAllowClassId.length = null;  
       addOption(document.EditFineStudent.fineCategoryId, '', 'Select');  
       addOption(document.EditFineStudent.fineAllowClassId, '', 'Select');  
     }
     if(trim(value)==''){
        return false;
     }
     url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxGetStudentValues.php';
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
              return false;
            }
            var ret=trim(transport.responseText).split('!!~~!!');
            var j = eval('('+ret[0]+')');
            if(act=='Add'){
               document.getElementById('studentName1').innerHTML = j[0].studentName;
               document.getElementById('className1').innerHTML = j[0].className;
		       var instituteId=j[0].instituteId;			  
               document.AddFineStudent.classId.value = j[0].classId;
               document.AddFineStudent.studentId.value = j[0].studentId;
               frm = document.AddFineStudent;
            }
            else if(act=='Edit') {
               document.getElementById('studentName2').innerHTML = j[0].studentName;
               document.getElementById('className2').innerHTML = j[0].className;
               document.EditFineStudent.classId.value = j[0].classId;
               document.EditFineStudent.studentId.value = j[0].studentId;
               frm = document.EditFineStudent;
           }
           var j = eval('('+ret[1]+')');
           frm.fineCategoryId.length = null; 
           addOption(frm.fineCategoryId, '', 'Select');        
           for(i=0;i<j.length;i++) { 
             addOption(frm.fineCategoryId, j[i].fineCategoryId, j[i].fineCategoryAbbrType);
           }
           
               if(act=="Add") {
                 if(j.length>0) {
                     
                     frm.fineCategoryId.options[1].selected=true; 
                   }
                 }
           var j = eval('('+ret[2]+')');
           frm.fineAllowClassId.length = null; 
           addOption(frm.fineAllowClassId, '', 'Select');        
           for(i=0;i<j.length;i++) { 
             addOption(frm.fineAllowClassId, j[i].classId, j[i].classId);
           }
           if(act=='Edit') { 
             document.EditFineStudent.fineCategoryId.value = ttFineCategoryId;  
             ttFineCategoryId='';
           }
           
       
           
       },             
       onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}




function getShowDetail() {
   document.getElementById("showhideSeats").style.display='';
   document.getElementById("lblMsg").innerHTML="Please Click to Hide Advance Search";
   document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif"
   if(valShow==0) {
     document.getElementById("showhideSeats").style.display='none';
     document.getElementById("lblMsg").innerHTML="Please Click to Show Advance Search"; 
     document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"
     valShow=1;
   }
   else {
     valShow=0;  
   }
}


window.onload=function(){
   valShow=1;    
   //document.listForm.reset();
   //var roll = document.getElementById("studentRollNo");
   //autoSuggest(roll);
}


function getFineStudent(){
	queryString = '';  
	if(isEmpty(document.getElementById('status').value)){
       messageBox("<?php echo ENTER_SUBJECT_TO_CLASS?>");
	   //document.getElementById('saveDiv').style.display='none';
	   document.getElementById('saveDiv1').style.display='none';
	   document.getElementById('showTitle').style.display='none';
	   document.getElementById('showData').style.display='none';
	   document.getElementById('legend').style.display='none';
       document.getElementById('legend12').style.display='none';
	   document.getElementById('approve').style.display='none';
       //document.getElementById('approve12').style.display='none';
	   document.getElementById('results').innerHTML=" ";
	   document.listForm.classId.focus();
	   return false;
   }
   else{
	   //document.getElementById('saveDiv').style.display='';
	   //document.getElementById('saveDiv1').style.display='';
	   //document.getElementById('showTitle').style.display='';
	   //document.getElementById('showData').style.display='';
	   //document.getElementById('legend').style.display='';
	   //document.getElementById('legend12').style.display='';
	   //document.getElementById('approve').style.display='';
	  // document.getElementById('approve12').style.display='';
	   sendReq(listURL,divResultName,'listForm',''); 
	   queryString = generateQueryString('listForm');
   }      
		 
}


function getAllDegree(str) {
    //alert(str);
    form = document.listForm;    
    
        
    searchClassStatus=-1;
    
    if(form.searchClassStatus[0].checked==true) {
      searchClassStatus =1;
    }
    if(form.searchClassStatus[1].checked==true) {
      searchClassStatus =3;
    }
    if(form.searchClassStatus[2].checked==true) {	
      searchClassStatus =4;
    }
    
      
    param = "searchClassStatus="+searchClassStatus+"&searchMode="+str; 
     
    
    var url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxInitStudentFineList.php';
    
    new Ajax.Request(url,
    {
        method:'post',
        parameters: param, 
        asynchronous:false,
        onCreate: function(){
             showWaitDialog(true);
        },
        onSuccess: function(transport){ 
            hideWaitDialog(true);                        
            
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    }); 
}


/* function to print fine category report*/
function printReport() {

    var param = generateQueryString('listForm')
    var qstr=param+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/fineStudentReportPrint.php?'+qstr;
    window.open(path,"DegreeReport","status=1,menubar=1,scrollbars=1, width=700, height=400, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    
    var param = generateQueryString('listForm')
    var qstr=param+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='fineStudentReportCSV.php?'+qstr;
}

function getDateCheck() {
   document.getElementById("startDate").value="";    
   document.getElementById("toDate").value="";
}

function doAll(){

    formx = document.listForm;
    if(formx.checkbox2.checked){
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
                formx.elements[i].checked=true;
            }
        }
    }
    else{
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
                formx.elements[i].checked=false;
            }
        }
    }
}
</script>
</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fine/listFineStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>