<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF TEST TYPES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TestTypesMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/TestType/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Test Type Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
 new Array('srNo','#','width="3%"','',false), 
 new Array('testTypeName','Name','width="200"','',true) ,
 new Array('testTypeCode','Code','width="130"','',true), 
 new Array('testTypeAbbr','Abbr.','width="80"','',true) ,  
 new Array('weightageAmount','Weightage Amt.','width="150" ','align="right" style="padding-right:15px"',true), 
 //new Array('weightagePercentage','Weightage.Per','width="60" ','align="right" style="padding-right:15px"',false), 
 new Array('evaluationCriteriaName','Eva. Criteria','width="130"','',true), 
 new Array('universityName','University','width="200"','',true), 
 new Array('degreeCode','Degree','width="130"','',true), 
 new Array('action','Action','width="3%"','align="center"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/TestType/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddTestType';   
editFormName   = 'EditTestType';
winLayerWidth  = 690; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteTestType';
divResultName  = 'results';
page=1; //default page
sortField = 'testTypeName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    //displayWindow(dv,w,h);
    //***As the Div is Huge so we have to incorporate this function.
    //Same functionality but can set left and top of the Div also***
    displayFloatingDiv(dv,'', w, h, 200, 150)
    populateValues(id);   
}

 

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {

    var fieldsArray = new Array(new Array("testtypeName","<?php echo ENTER_TESTTYPE_NAME; ?>"),
    new Array("testtypeCode","<?php echo ENTER_TESTTYPE_CODE; ?>"),
    new Array("testtypeAbbr","<?php echo ENTER_TESTTYPE_ABBR; ?>"),
	new Array("testType","<?php echo SELECT_TESTTYPE_CATEGORY; ?>"),
	new Array("university","Select an university"),
	new Array("labelId","<?php echo SELECT_TIMETABLE; ?>"),
	new Array("weightageAmount","<?php echo ENTER_TESTTYPE_WEIGHTAGE; ?>"),
    new Array("evaluationCriteria","<?php echo SELECT_TESTTYPE_EVALUATION; ?>"), 
    new Array("cnt","<?php echo ENTER_TESTTYPE_COUNT; ?>"), 
    new Array("sortOrder","<?php echo ENTER_TESTTYPE_SORT_ORDER; ?>"),
    new Array("subjectType","<?php echo SELECT_SUBJECT_TYPE; ?>"),
    new Array("conductingAuthority","<?php echo SELECT_CONDUCTING_AUTHORITY; ?>")
    );
    
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
          if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }

		else if(fieldsArray[i][0]=="testType" && eval("frm."+(fieldsArray[i][0])+".value")=='testType')  {
            //winmessageBox(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        } 

		else if(fieldsArray[i][0]=="labelId" && eval("frm."+(fieldsArray[i][0])+".value")=='labelId')  {
            //winmessageBox(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        } 

		else if(fieldsArray[i][0]=="weightageAmount" && !isDecimal(eval("frm."+(fieldsArray[i][0])+".value")))  {
            //winmessageBox(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox("<?php echo ENTER_TESTTYPE_WEIGHTAGE_NUM; ?>");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else if(fieldsArray[i][0]=="weightageAmount" && trim(eval("frm."+(fieldsArray[i][0])+".value"))>100)  {
            //winmessageBox(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox("<?php echo WEIGHTAGE_AMOUNT_RESTRICTION; ?>");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        } 
        
        else if(fieldsArray[i][0]=="weightagePercentage" && !isDecimal(eval("frm."+(fieldsArray[i][0])+".value")))  {
            //winmessageBox(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox("<?php echo ENTER_TESTTYPE_WEIGHTAGE_PERCENTAGE_NUM; ?>");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        } 
        else if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='testtypeName' ) {
                //winmessageBox("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo TESTTYPE_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            } 
       else if(fieldsArray[i][0]=="conductingAuthority" && eval("frm."+(fieldsArray[i][0])+".value")=="")  {
            //winmessageBox(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        } 
      else if(fieldsArray[i][0]=="evaluationCriteria" && eval("frm."+(fieldsArray[i][0])+".value")=="")  {
            //winmessageBox(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }                  
      else if(fieldsArray[i][0]=="subjectType" && eval("frm."+(fieldsArray[i][0])+".value")=="")  {
            //winmessageBox(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }                                      
      else if(fieldsArray[i][0]=="cnt" && !fieldsArray[i][0].disabled){
            if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))) //if not in integer format
              {
                 messageBox("<?php echo ENTER_TESTTYPE_COUNT_NUM; ?>");
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }
             if(trim(eval("frm."+(fieldsArray[i][0])+".value"))>100){
                 messageBox("<?php echo CNT_AMOUNT_RESTRICTION; ?>");
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              } 
        }
        
        else if(fieldsArray[i][0]=="sortOrder"){
            if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))){ //if not in integer format
                 messageBox("<?php echo ENTER_TESTTYPE_SORT_ORDER_NUM; ?>");
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }
            if(trim(eval("frm."+(fieldsArray[i][0])+".value"))>100){
                 messageBox("<?php echo SORT_ORDER_AMOUNT_RESTRICTION; ?>");
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }  
        }
        else  {
                if (!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]!="cnt" && fieldsArray[i][0]!="weightageAmount" && fieldsArray[i][0]!="weightagePercentage" && fieldsArray[i][0]!="sortOrder") {
                messageBox("<?php echo ENTER_ALPHABETS_NUMERIC; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
                }
            }
            //else {
                //unsetmessageBoxStyle(fieldsArray[i][0]);
            //}
        }
       
    if(act=='Add') {
        addTestType();
        return false;
    }
    else if(act=='Edit') {
        editTestType();
        return false;
    }
 }

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW TESTTYPE  
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addTestType() {
         url = '<?php echo HTTP_LIB_PATH;?>/TestType/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {testtypeName: (document.AddTestType.testtypeName.value), 
             testtypeCode: (document.AddTestType.testtypeCode.value),  
             testtypeAbbr: (document.AddTestType.testtypeAbbr.value), 
             universityId: (document.AddTestType.university.value),
             degreeId: (document.AddTestType.degree.value), 
             branchId: (document.AddTestType.branch.value), 
             weightageAmount: (document.AddTestType.weightageAmount.value), 
             //weightagePercentage: (document.AddTestType.weightagePercentage.value),
             subjectId: (document.AddTestType.subject.value), 
             studyPeriodId: (document.AddTestType.studyPeriod.value),  
             evaluationCriteriaId: (document.AddTestType.evaluationCriteria.value), 
             cnt: (document.AddTestType.cnt.value),
             sortOrder: (document.AddTestType.sortOrder.value),
             subjectTypeId: (document.AddTestType.subjectType.value),
             conductingAuthority: (document.AddTestType.conductingAuthority.value),
			 testType: (document.AddTestType.testType.value),
			 labelId: (document.AddTestType.labelId.value)
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
                        else if("<?php echo TESTTYPE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                          messageBox("<?php echo TESTTYPE_ALREADY_EXIST ;?>"); 
                          document.AddTestType.testtypeCode.focus();
                        }  
                         else {
                             hiddenFloatingDiv('AddTestType');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        //messageBox(trim(transport.responseText)); 
                        messageBox("This TestType Code already exists.Please enter a new code");
                        document.AddTestType.testtypeCode.focus();
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


//THIS FUNCTION IS USED TO DELETE A TESTTYPE
//  id=testtypeId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteTestType(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/TestType/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {testTypeId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "AddTestType" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {

   document.AddTestType.testtypeName.value='';
   document.AddTestType.testtypeCode.value='';
   document.AddTestType.testtypeAbbr.value='';
   document.AddTestType.university.selectedIndex=0;
   document.AddTestType.degree.selectedIndex=0;
   document.AddTestType.branch.selectedIndex=0;
   document.AddTestType.weightageAmount.value = '';
   document.AddTestType.testType.value='';
   document.AddTestType.labelId.value='';
   //document.AddTestType.weightagePercentage.value='';
   document.AddTestType.subject.selectedIndex=0;
   document.AddTestType.studyPeriod.selectedIndex=0;
   document.AddTestType.evaluationCriteria.selectedIndex=0;
   document.AddTestType.cnt.disabled=false;
   document.AddTestType.cnt.value='';
   document.AddTestType.sortOrder.value='';
   document.AddTestType.subjectType.value='';
   document.AddTestType.conductingAuthority.value='';
   document.AddTestType.testtypeName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A TESTTYPE
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editTestType() {
         url = '<?php echo HTTP_LIB_PATH;?>/TestType/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {testtypeId: (document.EditTestType.testtypeId.value), 
             testtypeName: (document.EditTestType.testtypeName.value), 
             testtypeCode: (document.EditTestType.testtypeCode.value),  
             testtypeAbbr: (document.EditTestType.testtypeAbbr.value), 
             universityId: (document.EditTestType.university.value),
             degreeId: (document.EditTestType.degree.value), 
             branchId: (document.EditTestType.branch.value), 
             weightageAmount: (document.EditTestType.weightageAmount.value), 
             //weightagePercentage: (document.EditTestType.weightagePercentage.value),
             subjectId: (document.EditTestType.subject.value), 
             studyPeriodId: (document.EditTestType.studyPeriod.value),  
             evaluationCriteriaId: (document.EditTestType.evaluationCriteria.value), 
             cnt: (document.EditTestType.cnt.value),
             sortOrder: (document.EditTestType.sortOrder.value),
             subjectTypeId: (document.EditTestType.subjectType.value),
             conductingAuthority: (document.EditTestType.conductingAuthority.value),
			 testType: (document.EditTestType.testType.value),
			 labelId: (document.EditTestType.labelId.value)
            },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);

                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditTestType');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                   else if("<?php echo TESTTYPE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                          messageBox("<?php echo TESTTYPE_ALREADY_EXIST ;?>"); 
                          document.EditTestType.testtypeCode.focus();
                        }  
                  else {
                       // messageBox(trim(transport.responseText));                         
                       messageBox("This TestType Code already exists.Please enter a new code");
                       document.EditTestType.testtypeCode.focus();
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditTestType" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/TestType/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {testtypeId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditTestType');
                        messageBox("<?php echo TESTTYPE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+trim(transport.responseText)+')');

                   document.EditTestType.testtypeName.value=j.testTypeName;
                   document.EditTestType.testtypeCode.value=j.testTypeCode;
                   document.EditTestType.testtypeAbbr.value=j.testTypeAbbr;
                   document.EditTestType.university.value=(j.universityId==null ? 'NULL' : j.universityId);
                   document.EditTestType.degree.value=(j.degreeId==null ? 'NULL' : j.degreeId);
                   document.EditTestType.branch.value=(j.branchId==null ? 'NULL' : j.branchId);
                   document.EditTestType.weightageAmount.value=j.weightageAmount;
                   //document.EditTestType.weightagePercentage.value=j.weightagePercentage;
                   //document.EditTestType.subject.value=(j.subjectId==NULL ? 'NULL' : j.subjectId);
                   document.EditTestType.studyPeriod.value=(j.studyPeriodId==null ?  'NULL' : j.studyPeriodId);
                   document.EditTestType.evaluationCriteria.value=(j.evaluationCriteriaId ==null ? 'NULL' : j.evaluationCriteriaId);
                   document.EditTestType.cnt.value=j.cnt;
                   var etxt=document.EditTestType.evaluationCriteria.options[document.EditTestType.evaluationCriteria.selectedIndex].text;
                   if(trim(etxt)=="Percentage" || trim(etxt)=="Average of Best"  || trim(etxt)=="Sum of Best"){
                       document.EditTestType.cnt.disabled=false;
                   }
                   else{
                      document.EditTestType.cnt.disabled=true; 
                   } 
                   document.EditTestType.sortOrder.value=j.sortOrder;
                   document.EditTestType.subjectType.value=j.subjectTypeId;
                   document.EditTestType.conductingAuthority.value=j.conductingAuthority;
				   document.EditTestType.testType.value=j.testTypeCategoryId;
				   document.EditTestType.labelId.value=j.timeTableLabelId;
                                  
                   document.EditTestType.testtypeName.focus();
                   document.EditTestType.testtypeId.value=j.testTypeId;

				   getTimeTableEditSubject(j.subjectId);

             },
             onFailure: function(){messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO give messageBoxs and make conunt active/inactive
// to user when he/shw
//selects Percentage or Slabs
//
//Author : Dipanjan Bhattacharjee
// Created on : (07.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
function evaluationCriteriaAction(value,frm){
   //messageBox(value); 
   if(trim(value)=="Percentage" || trim(value)=="Slabs"){
       messageBox("<?php echo ENTER_TESTTYPE_CONDITION; ?>");
   }
   //make count txtbox active/inactive based on user selection
   if(trim(value)=="Percentage" || trim(value)=="Average of Best" || trim(value)=="Sum of Best"){
      if(frm=="Add"){
        document.AddTestType.cnt.disabled=false; 
        document.AddTestType.cnt.value=""; 
        document.AddTestType.cnt.focus();
      }
     else{
         document.EditTestType.cnt.disabled=false;
         //document.EditTestType.cnt.value="";
         document.EditTestType.cnt.focus(); 
     }  
   }
  else{
    if(frm=="Add"){
        document.AddTestType.cnt.disabled=true; 
         document.AddTestType.cnt.value=0;
      }
     else{
         document.EditTestType.cnt.disabled=true;
         document.EditTestType.cnt.value=0;
     }  
  }  
}

function getTimeTableSubject() {
	//hideResults();
	form = document.AddTestType;
	var url = '<?php echo HTTP_LIB_PATH;?>/TestType/getTimeTableSubject.php';
	var pars = 'labelId='+form.labelId.value;

	if (form.labelId.value=='') {
		form.subject.length = null;
		addOption(form.subject, '', 'Select');
		return false;
	}
	
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog();
		 },
		onSuccess: function(transport){
			hideWaitDialog();
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.subject.length = null;
			addOption(form.subject, 'NULL', 'All');
			/*
			if (len > 0) {
				addOption(form.sectionId, 'all', 'All');
			}
			*/
			for(i=0;i<len;i++) {
				addOption(form.subject, j[i].subjectId, j[i].subjectCode);
			}
			// now select the value
			form.subject.value = j[0].subjectId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function getTimeTableEditSubject(mySubjectId) {
	//hideResults();
	form = document.EditTestType;
	var url = '<?php echo HTTP_LIB_PATH;?>/TestType/getTimeTableSubject.php';
	var pars = 'labelId='+form.labelId.value;

	if (form.labelId.value=='') {
		form.subject.length = null;
		addOption(form.subject, '', 'Select');
		return false;
	}
	
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog();
		 },
		onSuccess: function(transport){
			hideWaitDialog();
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.subject.length = null;
			addOption(form.subject, 'NULL', 'All');
			/*
			if (len > 0) {
				addOption(form.sectionId, 'all', 'All');
			}
			*/
			for(i=0;i<len;i++) {
				addOption(form.subject, j[i].subjectId, j[i].subjectCode);
			}
			// now select the value
			//form.subject.value = j[0].subjectId;
			document.EditTestType.subject.value=(mySubjectId==null ? 'NULL' : mySubjectId);
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

/* function to print TestType report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/testTypeReportPrint.php?'+qstr;
    window.open(path,"TestTypeReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
  
    window.location='testTypeReportCSV.php?'+qstr;
}



</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/TestType/listTestTypeContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listTestType.php $ 
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 8/10/09    Time: 14:19
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//00001621,00001644,00001645,00001646,
//00001647,00001711
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 29/07/09   Time: 11:35
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug id---0000749
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 25/07/09   Time: 13:12
//Updated in $/LeapCC/Interface
//Done Bug Fixing.
//Bug ids---0000680 to 0000688,0000690 to 0000696
//
//*****************  Version 12  *****************
//User: Administrator Date: 12/06/09   Time: 11:08
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids----0000032,0000036,0000043
//
//*****************  Version 11  *****************
//User: Administrator Date: 11/06/09   Time: 12:13
//Updated in $/LeapCC/Interface
//Corrected spelling mistakes
//
//*****************  Version 10  *****************
//User: Administrator Date: 2/06/09    Time: 11:34
//Updated in $/LeapCC/Interface
//Done bug fixing.
//BugIds : 1167 to 1176,1185
//
//*****************  Version 9  *****************
//User: Administrator Date: 1/06/09    Time: 13:09
//Updated in $/LeapCC/Interface
//Corrected bugs------bug2_30-05-09.doc
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 28/04/09   Time: 18:15
//Updated in $/LeapCC/Interface
//Modified "cnt" field's display logic
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 3/25/09    Time: 6:34p
//Updated in $/LeapCC/Interface
//modified to show test type category 
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 3/23/09    Time: 2:46p
//Updated in $/LeapCC/Interface
//modified in showing test type select
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 3/16/09    Time: 6:24p
//Updated in $/LeapCC/Interface
//modified for test type & put test type category
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/01/09    Time: 10:52
//Updated in $/LeapCC/Interface
//Make university selection compulsory
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:01
//Updated in $/LeapCC/Interface
//Showing "weightage amount,weightage percentage and evaluation criteria"
//in list
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 23  *****************
//User: Dipanjan     Date: 11/06/08   Time: 11:10a
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 22  *****************
//User: Dipanjan     Date: 10/24/08   Time: 2:11p
//Updated in $/Leap/Source/Interface
//Added functionality for TestType report print and export to csv
//
//*****************  Version 21  *****************
//User: Arvind       Date: 10/16/08   Time: 3:32p
//Updated in $/Leap/Source/Interface
//added sum of best in condition for evaluation criteria
//
//*****************  Version 20  *****************
//User: Arvind       Date: 10/16/08   Time: 3:29p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 18  *****************
//User: Dipanjan     Date: 8/27/08    Time: 12:29p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 8/20/08    Time: 1:59p
//Updated in $/Leap/Source/Interface
//Added standard messages
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 8/12/08    Time: 1:21p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 8/09/08    Time: 3:42p
//Updated in $/Leap/Source/Interface
//Modified so that weightage percentage and weightage ammount
//accepts decimal values
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 8/08/08    Time: 3:06p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 8/07/08    Time: 11:22a
//Updated in $/Leap/Source/Interface
//Modified count box selection based upon evaluation criteria selection
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 8/02/08    Time: 11:32a
//Updated in $/Leap/Source/Interface
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/01/08    Time: 10:26a
//Updated in $/Leap/Source/Interface
//Modified javascript messageBoxs
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:03p
//Updated in $/Leap/Source/Interface
//Added onCreate() function in ajax code
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 7/29/08    Time: 7:35p
//Updated in $/Leap/Source/Interface
//Corrected JavaScript Code
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 7/24/08    Time: 4:59p
//Updated in $/Leap/Source/Interface
//Modified so that
//university,degree,branch,subject,study period and evaluation criteria
//becomes optional
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 7/09/08    Time: 7:18p
//Updated in $/Leap/Source/Interface
//Add `Select` as default selected value in dropdowns of University,
//Degree, Branch, Study Period, Evaluation Criteria, subject and subject
//type.
//and made modifications so that data is  being populated in study period
//dropdown
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 7/01/08    Time: 1:04p
//Updated in $/Leap/Source/Interface
//Modified DataBase Column names
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/30/08    Time: 11:30a
//Updated in $/Leap/Source/Interface
//Added AjaxList & AjaxSearch Functionality
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/25/08    Time: 7:10p
//Updated in $/Leap/Source/Interface
//Added AjaxEnabled Delete functionality
//Added deleteTestType function
//Added Input Data validation using Javascript
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/19/08    Time: 2:59p
//Updated in $/Leap/Source/Interface
//Adding extra fields done
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/16/08    Time: 10:20a
//Updated in $/Leap/Source/Interface
//Modification Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:40p
//Created in $/Leap/Source/Interface
//Initial Checkin
?>