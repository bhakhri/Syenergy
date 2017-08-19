<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Decepline ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DeceplineMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/Decepline/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Decepline Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
    new Array('srNo','#','width="3%"','',false), 
    new Array('studentName','Name','width="12%"','',true) , 
    new Array('rollNo','Roll.No','width="7%"','',true) , 
    new Array('className','Class','width="20%"','',true), 
    new Array('offenseAbbr','Offence','width="10%"','',true) , 
    new Array('offenseDate','Date','width="8%"','',true) , 
    new Array('remarks','Remarks','width="15%"','',false) , 
    new Array('action','Action','width="10%"','align="right"',false)
  );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Decepline/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddDecepline';   
editFormName   = 'EditDecepline';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteDecepline';
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
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
    if(act=='Add'){
       if(trim(document.AddDecepline.studentName.value)==''){
           messageBox("<?php echo STUENT_NAME_EMPTY;?>"); 
           document.AddDecepline.studentRollNo.focus();
           return false;
       }
    }
    else if(act='Edit'){
        if(trim(document.EditDecepline.studentName.value)==''){
           messageBox("<?php echo STUENT_NAME_EMPTY;?>"); 
           document.AddDecepline.studentRollNo.focus();
           return false;
       }        
    }
    
    var fieldsArray = new Array(
        new Array("offenseId","<?php echo  SELECT_OFFENSE;?>"),
        new Array("remarksTxt","<?php echo ENTER_REMARKS;?>")
    );
    
    var d=new Date();
    var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));

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
       if(!dateDifference(document.AddDecepline.deceplineDate1.value,cdate,"-")){
           messageBox("<?php echo DECEPLINE_DATE_VALIDATION;?>"); 
           document.AddDecepline.deceplineDate1.focus();
           return false;
       }
    }
    else if(act='Edit'){
        if(!dateDifference(document.EditDecepline.deceplineDate2.value,cdate,"-")){
           messageBox("<?php echo DECEPLINE_DATE_VALIDATION;?>"); 
           document.EditDecepline.deceplineDate2.focus(); 
           return false;
       }        
    }
}
    if(act=='Add') {
        addDecepline();
        return false;
    }
    else if(act=='Edit') {
        editDecepline();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A DECEPLINE VIOLATION
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addDecepline() {
         url = '<?php echo HTTP_LIB_PATH;?>/Decepline/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                  studentId:  (document.AddDecepline.studentId.value), 
                  classId:    (document.AddDecepline.classId.value), 
                  offenseId:    (document.AddDecepline.offenseId.value),
                  offenseDate:      (document.AddDecepline.deceplineDate1.value),
                  remarks: trim(document.AddDecepline.remarksTxt.value)
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
                             hiddenFloatingDiv('AddDecepline');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);

                             return false;
                         }
                     } 
                    else if("<?php echo STUDENT_OFFENCE_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo STUDENT_OFFENCE_EXIST ;?>"); 
                         document.AddDecepline.stuentRollNo.focus();
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteDecepline(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         url = '<?php echo HTTP_LIB_PATH;?>/Decepline/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {deceplineId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog(); 
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddDecepline.studentRollNo.value = '';
   document.AddDecepline.classId.value = '';
   document.AddDecepline.studentId.value = '';
   document.AddDecepline.studentName.value = '';
   document.AddDecepline.deceplineDate1.value = "<?php echo date('Y-m-d') ?>";
   document.AddDecepline.offenseId.value = '';
   document.AddDecepline.remarksTxt.value = '';
   document.AddDecepline.studentRollNo.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO decepline
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editDecepline() {
         url = '<?php echo HTTP_LIB_PATH;?>/Decepline/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 deceplineId: (document.EditDecepline.deceplineId.value),
                 studentId: (document.EditDecepline.studentId.value), 
                 classId:   (document.EditDecepline.classId.value), 
                 offenseId: (document.EditDecepline.offenseId.value),
                 offenseDate:       (document.EditDecepline.deceplineDate2.value),
                 remarks: trim(document.EditDecepline.remarksTxt.value)
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditDecepline');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo STUDENT_OFFENCE_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo STUDENT_OFFENCE_EXIST ;?>"); 
                       document.EditDecepline.studentRollNo.focus();
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Decepline/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {deceplineId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditDecepline');
                        messageBox("<?php echo DECEPLINE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');
                   
                   document.EditDecepline.studentName.value = j.studentName;
                   document.EditDecepline.studentRollNo.value = j.rollNo;
                   document.EditDecepline.studentId.value = j.studentId;
                   document.EditDecepline.classId.value = j.classId;
                   document.EditDecepline.deceplineId.value = j.deceplineId;
                   document.EditDecepline.deceplineDate2.value = j.offenseDate;
                   document.EditDecepline.offenseId.value = j.offenseId;
                   document.EditDecepline.remarksTxt.value = j.remarks;
                   
                   document.EditDecepline.studentRollNo.focus();

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO get student details
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getStudent(value,act) {
         url = '<?php echo HTTP_LIB_PATH;?>/Decepline/ajaxGetStudentValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {rollNo: value},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    if(trim(transport.responseText)==0) {
                        messageBox("<?php echo STUDENT_NOT_EXIST; ?>");
                         if(act=='Add'){   
                            document.AddDecepline.studentRollNo.focus();   
                         }
                        else if(act=='Edit') {
                            document.EditDecepline.studentRollNo.focus();
                         }
                        return false;
                    }
                    j = eval('('+transport.responseText+')');

                if(act=='Add'){   
                   document.AddDecepline.studentName.value = j.studentName;
                   document.AddDecepline.classId.value = j.classId;
                   document.AddDecepline.studentId.value = j.studentId;
                }
               else if(act=='Edit') {
                   document.EditDecepline.studentName.value = j.studentName;
                   document.EditDecepline.classId.value = j.classId;
                   document.EditDecepline.studentId.value = j.studentId;
               }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Decepline/listDeceplineContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
// $History: listDecepline.php $ 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/04/09   Time: 13:15
//Created in $/LeapCC/Interface
//Add modified files for bus masters
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 23/12/08   Time: 12:05
//Created in $/LeapCC/Interface
//Created module 'Decepline'
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/12/08   Time: 18:28
//Created in $/Leap/Source/Interface
//Created module 'Decepline'
?>