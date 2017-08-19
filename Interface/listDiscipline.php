<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Discipline ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisciplineMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Discipline/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Discipline Master </title>
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
    new Array('srNo','#','width="1%"','',false), 
    new Array('studentName','Name','width="12%"','',true) , 
    new Array('rollNo','Roll No.','width="10%"','',true) , 
    new Array('universityRollNo','Univ Roll No.','width="15%"','',true) ,
    new Array('className','Class','width="15%"','',true), 
    new Array('offenseAbbr','Offence','width="5%"','',true) , 
    new Array('offenseDate','Date','width="10%"','align="center"',true) , 
	new Array('reportedBy','Reported By','width="10%"','',true) , 
    new Array('remarks','Remarks','width="15%"','',true) , 
    new Array('action','Action','width="2%"','align="right"',false)
  );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Discipline/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddDiscipline';   
editFormName   = 'EditDiscipline';
winLayerWidth  = 320; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteDiscipline';
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
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
// Created on : (12.6.2008)
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
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var cdate="<?php echo date('Y-m-d'); ?>";
function validateAddForm(frm, act) {
    
    if(act=='Add'){
       if(trim(document.AddDiscipline.studentRollNo.value)==''){
           messageBox("<?php echo STUENT_ROLL_NO_EMPTY;?>"); 
           document.AddDiscipline.studentRollNo.focus();
           return false;
       }
    }
    else if(act='Edit'){
        if(trim(document.EditDiscipline.studentRollNo.value)==''){
           messageBox("<?php echo STUENT_ROLL_NO_EMPTY;?>"); 
           document.EditDiscipline.studentRollNo.focus();
           return false;
       }        
    }
    
    var fieldsArray = new Array(
        new Array("offenseId","<?php echo  SELECT_OFFENSE;?>"),
        new Array("remarksTxt","<?php echo ENTER_REMARKS;?>"),
		new Array("reportedBy","<?php echo ENTER_REPORTED_BY;?>")
    );
    
    //var d=new Date();
    //var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            //unsetAlertStyle(fieldsArray[i][0]);
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<5 && fieldsArray[i][0]=='remarksTxt' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo REMARKS_LENGTH;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
        }
        
    if(act=='Add'){
       if(!dateDifference(document.AddDiscipline.disciplineDate1.value,cdate,"-")){
           messageBox("<?php echo DISCIPLINE_DATE_VALIDATION;?>"); 
           document.AddDiscipline.disciplineDate1.focus();
           return false;
       }
    }
    else if(act='Edit'){
        if(!dateDifference(document.EditDiscipline.disciplineDate2.value,cdate,"-")){
           messageBox("<?php echo DISCIPLINE_DATE_VALIDATION;?>"); 
           document.EditDiscipline.disciplineDate2.focus(); 
           return false;
       }        
    }
}
    if(act=='Add') {
        addDiscipline();
        return false;
    }
    else if(act=='Edit') {
        editDiscipline();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A DISCIPLINE VIOLATION
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addDiscipline() {
         url = '<?php echo HTTP_LIB_PATH;?>/Discipline/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                  studentId:  (document.AddDiscipline.studentId.value), 
                  classId:    (document.AddDiscipline.classId.value), 
                  offenseId:    (document.AddDiscipline.offenseId.value),
                  offenseDate:      (document.AddDiscipline.disciplineDate1.value),
                  remarks: trim(document.AddDiscipline.remarksTxt.value),
				  reportedBy:      (document.AddDiscipline.reportedBy.value)
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
                             hiddenFloatingDiv('AddDiscipline');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);

                             return false;
                         }
                     } 
                    else if("<?php echo STUDENT_OFFENCE_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo STUDENT_OFFENCE_EXIST ;?>"); 
                         document.AddDiscipline.stuentRollNo.focus();
                        }   
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE OFFENCE
//  id=cityId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteDiscipline(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         url = '<?php echo HTTP_LIB_PATH;?>/Discipline/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {disciplineId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "addCity" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddDiscipline.studentRollNo.value = '';
   document.AddDiscipline.classId.value = '';
   document.AddDiscipline.studentId.value = '';
   //document.AddDiscipline.studentName.value = '';
   document.getElementById('studentName1').innerHTML = '';
   document.getElementById('className1').innerHTML = '';
   document.AddDiscipline.disciplineDate1.value = "<?php echo date('Y-m-d') ?>";
   document.AddDiscipline.offenseId.value = '';
   document.AddDiscipline.remarksTxt.value = '';
   document.AddDiscipline.reportedBy.value = '';
   document.AddDiscipline.studentRollNo.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO discipline
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editDiscipline() {
         url = '<?php echo HTTP_LIB_PATH;?>/Discipline/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 disciplineId: (document.EditDiscipline.disciplineId.value),
                 studentId: (document.EditDiscipline.studentId.value), 
                 classId:   (document.EditDiscipline.classId.value), 
                 offenseId: (document.EditDiscipline.offenseId.value),
                 offenseDate:       (document.EditDiscipline.disciplineDate2.value), 
                 reportedBy:      (document.EditDiscipline.reportedBy.value),
                 remarks: trim(document.EditDiscipline.remarksTxt.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditDiscipline');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo STUDENT_OFFENCE_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo STUDENT_OFFENCE_EXIST ;?>"); 
                       document.EditDiscipline.studentRollNo.focus();
                    } 
                     else {
                        messageBox(trim(transport.responseText));                         
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editCity" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         var url = '<?php echo HTTP_LIB_PATH;?>/Discipline/ajaxGetValues.php';
         document.EditDiscipline.reset();
         document.getElementById('studentName2').innerHTML = '';
         document.getElementById('className2').innerHTML = '';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {disciplineId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditDiscipline');
                        messageBox("<?php echo DISCIPLINE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');
                   
                   //document.EditDiscipline.studentName.value = j.studentName;
                   document.getElementById('studentName2').innerHTML = j.studentName;
                   document.getElementById('className2').innerHTML = j.className;
                   document.EditDiscipline.studentRollNo.value = j.rollNo;
                   document.EditDiscipline.studentId.value = j.studentId;
                   document.EditDiscipline.classId.value = j.classId;
                   document.EditDiscipline.disciplineId.value = j.disciplineId;
                   document.EditDiscipline.disciplineDate2.value = j.offenseDate;
				   document.EditDiscipline.reportedBy.value = j.reportedBy;
                   document.EditDiscipline.offenseId.value = j.offenseId;
                   document.EditDiscipline.remarksTxt.value = j.remarks;
                   
                   document.EditDiscipline.offenseId.focus();

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO get student details
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getStudent(value,act) {
         if(trim(value)==''){
             return false;
         }
         url = '<?php echo HTTP_LIB_PATH;?>/Discipline/ajaxGetStudentValues.php';
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
                   document.AddDiscipline.classId.value = j.classId;
                   document.AddDiscipline.studentId.value = j.studentId;
                   
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
function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){    
  var form = document.AddDiscipline;
 }
 else{
     var form = document.EditDiscipline;
 }
  eval('form.'+eleName+'.focus()');
  return false;
 }
}


/* function to print Discipline report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/disciplineReportPrint.php?'+qstr;
    window.open(path,"DisciplineReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='disciplineReportCSV.php?'+qstr;
}

</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Discipline/listDisciplineContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listDiscipline.php $ 
//
//*****************  Version 10  *****************
//User: Gurkeerat    Date: 2/25/10    Time: 3:46p
//Updated in $/LeapCC/Interface
//added university roll no.
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 21/10/09   Time: 11:42
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//00001796,00001794,00001786,00001630
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 9/29/09    Time: 1:41p
//Updated in $/LeapCC/Interface
// solved calender problem
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 25/08/09   Time: 17:29
//Updated in $/LeapCC/Interface
//Corrected msg display in teacher dashboard
//and discipline module
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 25/06/09   Time: 12:01
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//00000287 to 00000293,00000295
//
//*****************  Version 4  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Interface
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 1/05/09    Time: 11:34a
//Updated in $/LeapCC/Interface
//added reported by in student discipline
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 26/12/08   Time: 15:03
//Created in $/LeapCC/Interface
//Created 'Discipline' Module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/12/08   Time: 18:25
//Updated in $/Leap/Source/Interface
//Corrected Speling Mistake
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/12/08   Time: 18:28
//Created in $/Leap/Source/Interface
//Created module 'Discipline'
?>