<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF FeedBack Labels ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Gurkeerat Sidhu
// Created on : (08.01.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_Labels');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/FeedBack/initFeedBackLabelList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Label Master(Advanced) </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
new Array('srNo','#','width="1%"','',false), 
new Array('feedbackSurveyLabel','Name','width="20%"','',true) ,  
new Array('visibleFrom','From','width="15%"','align="center"',true) , 
new Array('visibleTo','To','width="15%"','align="center"',true) , 
new Array('noOfAttempts','No. of Attempts','width="13%"','align=right',true) , 
new Array('isActive','Active','width="12%"','align=center',true), 
new Array('action','Action','width="3%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitFeedBackLabelList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFeedBackLabel';   
editFormName   = 'EditFeedBackLabel';
winLayerWidth  = 320; //  add/edit form width
winLayerHeight = 350; // add/edit form height
deleteFunction = 'return deleteFeedBackLabel';
divResultName  = 'results';
page=1; //default page
sortField = 'feedbackSurveyLabel';
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
//Author : Gurkeerat Sidhu
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id) {
    displayWindow('EditFeedBackLabel',320,215);
   // document.EditFeedBackLabel.noOfAttempts.disabled=false;
  /*  document.getElementById('activeClass2').style.display='';
    makeDDHide('activeClass2','d222','d333');
 document.getElementById('d111').style.zIndex=parseInt(document.getElementById('EditFeedBackLabel').style.zIndex,10)+20;
 document.getElementById('d222').style.zIndex=parseInt(document.getElementById('EditFeedBackLabel').style.zIndex,10)+10;
 document.getElementById('d333').style.zIndex=parseInt(document.getElementById('EditFeedBackLabel').style.zIndex,10)+10;
 document.getElementById('d111').style.height='200px';    */
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Gurkeerat Sidhu
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
                                new Array("feedbackSurveyLabel","<?php echo ENTER_LABEL_NAME; ?>"),
                                new Array("timeTableLabelId","<?php echo SELECT_TIME_TABLE; ?>"),
                                new Array ("noOfAttempts","<?php echo ADV_ENTER_ATTEMPTS; ?>")
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
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length <1 && fieldsArray[i][0]=='feedbackSurveyLabel' ) {
                messageBox("<?php echo ADV_LABEL_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
           if(fieldsArray[i][0]=="noOfAttempts"){
            if (!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))){ 
                messageBox("<?php echo ENTER_NUMERIC_VALUE; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if (eval("frm."+(fieldsArray[i][0])+".value") > 30000){ 
                messageBox("No. of attempts cannot be greater than 30000");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
         } 
        }
    }

    if (act=='Add') {
        if (document.AddFeedBackLabel.startDate.value=='') {
            messageBox("<?php echo ENTER_VISIBLE_FROM; ?>");        
            document.AddFeedBackLabel.startDate.focus();
            return false;
        }
    }

    if (act=='Add') {
        if (document.AddFeedBackLabel.toDate.value=='') {
            messageBox("<?php echo ENTER_VISIBLE_TO; ?>");        
            document.AddFeedBackLabel.toDate.focus();
            return false;
        }
    }
   

    if (act=='Add') {
        if (document.AddFeedBackLabel.noOfAttempts.value<0) {
            messageBox("Number of attempts cannot be less than zero");
            document.AddFeedBackLabel.noOfAttempts.focus();
            return false;
        }
    }
   

    if (act=='Edit') {
        if (document.EditFeedBackLabel.startDate1.value=='') {
            messageBox("<?php echo ENTER_VISIBLE_FROM; ?>");        
            document.EditFeedBackLabel.startDate1.focus();
            return false;
        }
    }

    if (act=='Edit') {
        if (document.EditFeedBackLabel.toDate1.value=='') {
            messageBox("<?php echo ENTER_VISIBLE_TO; ?>");        
            document.EditFeedBackLabel.toDate1.focus();
            return false;
        }
    }

    if (act=='Edit') {
        if (document.EditFeedBackLabel.noOfAttempts.value<0) {
            messageBox("Number of attempts cannot be less than zero");
            document.EditFeedBackLabel.noOfAttempts.focus();
            return false;
        }
    }

   if(act=='Add'){
    if(!dateDifference(document.getElementById('startDate').value,document.getElementById('toDate').value,"-")){
       messageBox("<?php echo ADV_LABEL_DATE_VALIDATION; ?>");   
       document.getElementById('startDate').focus();  
       return false;
     }
     if(!dateDifference(document.getElementById('toDate').value,document.getElementById('extendDate').value,"-")){
       messageBox("<?php echo ADV_LABEL_EXTEND_DATE_VALIDATION; ?>");   
       document.getElementById('extendDate').focus();  
       return false;
     }
   }
   
   if(act=='Edit'){
    if(!dateDifference(document.getElementById('startDate1').value,document.getElementById('toDate1').value,"-")){
       messageBox("<?php echo ADV_LABEL_DATE_VALIDATION; ?>");   
       document.getElementById('startDate1').focus();  
       return false;
     }
     if(!dateDifference(document.getElementById('toDate1').value,document.getElementById('extendDate1').value,"-")){
       messageBox("<?php echo ADV_LABEL_EXTEND_DATE_VALIDATION; ?>");   
       document.getElementById('extendDate1').focus();  
       return false;
     }
   }

    if(act=='Add') {
        addFeedBackLabel();
        return false;
    }
    else if(act=='Edit') {
        editFeedBackLabel();
        return false;
    }
}

function disableAttempts(mode) {

    if (mode==1) {
    
    if (document.AddFeedBackLabel.timeTableLabelId.value==2) {
        document.AddFeedBackLabel.noOfAttempts.value=1;
        document.AddFeedBackLabel.noOfAttempts.disabled=true;
    }
    else {
        document.AddFeedBackLabel.noOfAttempts.disabled=false;
    }
    }
    else if (mode==2) {
    if (document.EditFeedBackLabel.timeTableLabelId.value==2) {
        document.EditFeedBackLabel.noOfAttempts.value='';
        document.EditFeedBackLabel.noOfAttempts.disabled=true;
    }
    else {
        document.EditFeedBackLabel.noOfAttempts.disabled=false;
    }
    }
} 





//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW FeedBack Label
//
//Author : Gurkeerat Sidhu
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addFeedBackLabel() {
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitFeedBackLabelAdd.php';
         var roleId=-1;
         if(document.AddFeedBackLabel.roleId[0].checked){
             roleId=1;
         }
         else if(document.AddFeedBackLabel.roleId[1].checked){
             roleId=2;
         }
         else{
             roleId=3;
         }
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 feedbackSurveyLabel : trim(document.AddFeedBackLabel.feedbackSurveyLabel.value),
                 timeTableLabelId    : document.AddFeedBackLabel.timeTableLabelId.value,
                 startDate           : document.AddFeedBackLabel.startDate.value,
                 toDate              : document.AddFeedBackLabel.toDate.value,
                 extendDate          : document.AddFeedBackLabel.extendDate.value,
                 noOfAttempts        : trim(document.AddFeedBackLabel.noOfAttempts.value),
                 isActive            : (document.AddFeedBackLabel.isActive[0].checked ? 1 : 0 ),
                 roleId              : roleId
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
                             hiddenFloatingDiv('AddFeedBackLabel');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     }
                     else if("<?php echo ADV_LABEL_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo ADV_LABEL_ALREADY_EXIST ;?>"); 
                        document.AddFeedBackLabel.feedbackSurveyLabel.focus();
                      }      
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A AddFeedBack Label
//  id=busRouteId
//Author : Gurkeerat Sidhu
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteFeedBackLabel(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitFeedBackLabelDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 labelId: id
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

//-----------------------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "AddFeedBackLabel" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------
function blankValues() {
   document.AddFeedBackLabel.feedbackSurveyLabel.value = '';
   document.AddFeedBackLabel.isActive[0].checked =true;
   document.AddFeedBackLabel.roleId[0].checked =true;
   document.AddFeedBackLabel.timeTableLabelId.selectedIndex=0;
   //document.AddFeedBackLabel.startDate.value='';
   //document.AddFeedBackLabel.toDate.value='';
   document.AddFeedBackLabel.noOfAttempts.value='';
   document.AddFeedBackLabel.noOfAttempts.disabled=false;
   document.AddFeedBackLabel.feedbackSurveyLabel.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A BUSROUTE
//
//Author : Gurkeerat Sidhu
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editFeedBackLabel() {
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitFeedBackLabelEdit.php';
         var roleId=-1;
         if(document.EditFeedBackLabel.roleId[0].checked){
             roleId=1;
         }
         else if(document.EditFeedBackLabel.roleId[1].checked){
             roleId=2;
         }
         else{
             roleId=3;
         }
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
              labelId             : (document.EditFeedBackLabel.labelId.value),
              feedbackSurveyLabel : (trim(document.EditFeedBackLabel.feedbackSurveyLabel.value)),
              timeTableLabelId    : document.EditFeedBackLabel.timeTableLabelId.value,
              startDate           : document.EditFeedBackLabel.startDate1.value,
              toDate              : document.EditFeedBackLabel.toDate1.value,
              extendDate          : document.EditFeedBackLabel.extendDate1.value,
              noOfAttempts        : document.EditFeedBackLabel.noOfAttempts.value,
              isActive            : (document.EditFeedBackLabel.isActive[0].checked ? 1 : 0 ),
              roleId              : roleId
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditFeedBackLabel');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo ADV_LABEL_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo ADV_LABEL_ALREADY_EXIST ; ?>"); 
                        document.EditFeedBackLabel.feedbackSurveyLabel.focus();
                    }
                    else {
                        messageBox(trim(transport.responseText));                         
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditFeedBackLabel" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetFeedBackLabelValues.php';
         document.EditFeedBackLabel.reset();
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {labelId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditFeedBackLabel');
                        messageBox("<?php echo ADV_LABEL_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        //return false;
                   }
                   
                   var j = eval('('+trim(transport.responseText)+')');
                   document.EditFeedBackLabel.labelId.value =j.feedbackSurveyId; 
                   document.EditFeedBackLabel.feedbackSurveyLabel.value = j.feedbackSurveyLabel;
                   document.EditFeedBackLabel.isActive[0].checked = (j.isActive=="1" ? true : false) ;
                   document.EditFeedBackLabel.isActive[1].checked = (j.isActive=="1" ? false : true) ;
                   document.EditFeedBackLabel.timeTableLabelId.value = j.timeTableLabelId;
                   document.EditFeedBackLabel.startDate1.value = j.visibleFrom;
                   document.EditFeedBackLabel.toDate1.value =j.visibleTo;
                   document.EditFeedBackLabel.extendDate1.value =j.extendTo;
                   
                   if(j.roleId==2){
                     document.EditFeedBackLabel.roleId[0].checked=true;  
                   }
                   else if(j.roleId==3){
                     document.EditFeedBackLabel.roleId[1].checked=true;  
                   }
                   else if(j.roleId==4){
                     document.EditFeedBackLabel.roleId[2].checked=true;  
                   }
                   else{
                     document.EditFeedBackLabel.roleId[0].checked=true;  
                   }
                   document.EditFeedBackLabel.noOfAttempts.value =j.noOfAttempts;
                   document.EditFeedBackLabel.feedbackSurveyLabel.focus();
                   
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

/* function to print FeedBack Label report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/feedBackAdvLabelReportPrint.php?'+qstr;
    window.open(path,"FeedBackLabelReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='feedBackAdvLabelReportCSV.php?'+qstr;
}
//var initialTextForMultiDropDowns='Click to select multiple items';
//var selectTextForMultiDropDowns='items';

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FeedbackAdvanced/listFeedbackLabelContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
    <SCRIPT LANGUAGE="JavaScript">
    <!--
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
    //-->
    </SCRIPT>
</body>
</html>

