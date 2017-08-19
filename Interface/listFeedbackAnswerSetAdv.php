<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF FeedBackGrades
// Author : Gurkeerat Sidhu
// Created on : (12.01.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_AnswerSet');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/FeedBack/initFeedBackGradeList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>:  Answer Set Options </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
    new Array('srNo','#','width="3%"','align="left"',false), 
    new Array('answerSetName','Name','width="92%"','align="left"',true),
    new Array('action','Action','width="5%"','align="center"',false)
  );


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitAnswerSetList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AnswerSetActionDiv';   
editFormName   = 'AnswerSetActionDiv';
winLayerWidth  = 355; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteAnswerSet';
divResultName  = 'AnswerSetResultDiv';
page=1; //default page
sortField = 'answerSetName';
sortOrderBy    = 'ASC';


// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

var dtArray=new Array(); 
//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Gurkeerat Sidhu
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id,dv,w,h) {
    document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Answer Set';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues(id);   
}
//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Gurkeerat Sidhu 
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
    
    
    var fieldsArray = new Array(new Array("answerSetName","<?php echo ENTER_ANSWERSET_NAME ?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
     }
  
    if(document.getElementById('answerSetId').value=='') {
        //alert('add slot');
        addAnswerSet();
        return false;
    }
    else{
        //alert('edit slot');
        editAnswerSet();
        return false;
    }
}

function emptySlotId() {
    document.getElementById('offenseId').value='';
}

//-------------------------------------------------------
//THIS FUNCTION addTestTypeCategory() IS USED TO ADD NEW COMPLAINT CATEGORY
//
//Author : Gurkeerat Sidhu
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addAnswerSet() {
        
         url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitAnswerSetAdd.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                answerSetName:   trim(document.AnswerSetDetail.answerSetName.value)
                
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
                             hiddenFloatingDiv('AnswerSetActionDiv');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                     
                     else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo ANSWER_SET_EXIST; ?>'){
                            document.AnswerSetDetail.answerSetName.value="";
                            document.AnswerSetDetail.answerSetName.focus();
                        }
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A PERIOD SLOT
//  id=complaintCatId
//Author : Gurkeerat Sidhu
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteAnswerSet(id) {
    
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitAnswerSetDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {answerSetId: id},
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

//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "ADDPERIODSLOT" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
    document.getElementById('divHeaderId').innerHTML='&nbsp; Add Answer Set';
    document.AnswerSetDetail.answerSetName.value = '';
    document.getElementById('answerSetId').value='';
    document.AnswerSetDetail.answerSetName.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A TEST TYPE CATEGORY
//
//Author : Gurkeerat Sidhu
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editAnswerSet() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitAnswerSetEdit.php';
          
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                    answerSetId: (document.AnswerSetDetail.answerSetId.value),
                    answerSetName: trim(document.AnswerSetDetail.answerSetName.value)
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('AnswerSetActionDiv');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;

                     }
                   else {
                        messageBox(trim(transport.responseText)); 
                            if (trim(transport.responseText)=='<?php echo ANSWER_SET_EXIST; ?>'){
                            document.AnswerSetDetail.answerSetName.value="";
                            document.AnswerSetDetail.answerSetName.focus();
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
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxAnswerSetGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {answerSetId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('AnswerSetActionDiv');
                        messageBox("<?php echo ANSWER_SET_NOT_EXIST; ?>");
                        //getAnswerSetData();           
                   }
                   j = eval('('+trim(transport.responseText)+')');

                  
                   document.AnswerSetDetail.answerSetName.value    = j.answerSetName;
                   document.AnswerSetDetail.answerSetId.value= j.answerSetId;
                   document.AnswerSetDetail.answerSetName.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print FeedBack Label report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/feedBackAdvAnswerSetReportPrint.php?'+qstr;
    window.open(path,"FeedBackCategoryReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='feedBackAdvAnswerSetReportCSV.php?'+qstr;
}

</script>

</head>
<body>
    <?php 
        require_once(TEMPLATES_PATH . "/header.php");
        require_once(TEMPLATES_PATH . "/FeedbackAdvanced/listAnswerSetContents.php");
        require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	<SCRIPT LANGUAGE="JavaScript">
    <!--
       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
    </SCRIPT>
</body>
</html>



