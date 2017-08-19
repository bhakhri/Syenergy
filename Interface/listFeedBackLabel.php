<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF FeedBack Labels ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (13.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CreateFeedBackLabels');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/FeedBack/initFeedBackLabelList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Label Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
new Array('srNo','#','width="3%"','',false), 
new Array('feedbackSurveyLabel','Name','width="20%"','',true) , 
new Array('surveyType','Survey Type','width="15%"','',true) , 
new Array('visibleFrom','From','width="15%"','align="center"',true) , 
new Array('visibleTo','To','width="15%"','align="center"',true) , 
new Array('noAttempts','No. of Attempts','width="13%"','align=right',true) , 
new Array('isActive','Active','width="12%"','align=center',true), 
new Array('action','Action','width="3%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxInitFeedBackLabelList.php';
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
//Author : Dipanjan Bhattacharjee
// Created on : (13.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    document.EditFeedBackLabel.noAttempts.disabled=false;
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (13.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
    new Array("labelName","<?php echo ENTER_LABEL_NAME; ?>"),new Array ("noAttempts","<?php echo ENTER_ATTEMPTS; ?>"));

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
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length <3 && fieldsArray[i][0]=='labelName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo LABEL_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
            if(!isAlphaNumericCustom(eval("frm."+(fieldsArray[i][0])+".value"),"-._ ")) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_ALPHABETS_NUMERIC_LABEL; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }

            if(fieldsArray[i][0]=="noAttempts"){
                if (!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))){ 
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_LABEL_NUMBER ?>");
                //document.addHostel.roomTotal.value="";
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
         } 
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
            
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
        if (document.AddFeedBackLabel.noAttempts.value<=0) {
            messageBox("<?php echo VALUE_NOT_ZERO; ?>");
            document.AddFeedBackLabel.noAttempts.focus();
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
        if (document.EditFeedBackLabel.noAttempts.value<=0) {
            messageBox("<?php echo VALUE_NOT_ZERO; ?>");
            document.EditFeedBackLabel.noAttempts.focus();
            return false;
        }
    }

    if (act=='Edit') {
        if (document.EditFeedBackLabel.surveyType.value == 2) {
            if (document.EditFeedBackLabel.noAttempts.value > 1 || document.EditFeedBackLabel.noAttempts.value < 1) {
            messageBox("<?php echo VALUE_NOT_LESS_TEACHER; ?>");
            document.EditFeedBackLabel.noAttempts.focus();
            return false;
            }
        }
    }


    if(!dateDifference(document.getElementById('startDate').value,document.getElementById('toDate').value,"-")){
       messageBox("From Date Can Not be Greater Than To Date");   
       document.getElementById('startDate').value="";  
       document.getElementById('startDate').focus();  
       return false;
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
    
    if (document.AddFeedBackLabel.surveyType.value==2) {
        document.AddFeedBackLabel.noAttempts.value=1;
        document.AddFeedBackLabel.noAttempts.disabled=true;
    }
    else {
        document.AddFeedBackLabel.noAttempts.disabled=false;
    }
    }
    else if (mode==2) {
    if (document.EditFeedBackLabel.surveyType.value==2) {
        document.EditFeedBackLabel.noAttempts.value='';
        document.EditFeedBackLabel.noAttempts.disabled=true;
    }
    else {
        document.EditFeedBackLabel.noAttempts.disabled=false;
    }
    }
}





//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW FeedBack Label
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addFeedBackLabel() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxInitFeedBackLabelAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {labelName: (trim(document.AddFeedBackLabel.labelName.value)),
             surveyType: document.AddFeedBackLabel.surveyType.value,
             startDate: document.AddFeedBackLabel.startDate.value,
             toDate: document.AddFeedBackLabel.toDate.value,
             noAttempts: document.AddFeedBackLabel.noAttempts.value,
             isActive: (document.AddFeedBackLabel.isActive[0].checked ? 1 : 0 )
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
                      else if("<?php echo LABEL_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo LABEL_ALREADY_EXIST ;?>"); 
                        document.AddFeedBackLabel.labelName.focus();
                      }  
                         else {
                             hiddenFloatingDiv('AddFeedBackLabel');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A AddFeedBack Label
//  id=busRouteId
//Author : Dipanjan Bhattacharjee
// Created on : (13.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteFeedBackLabel(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxInitFeedBackLabelDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {labelId: id},
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
//Author : Dipanjan Bhattacharjee
// Created on : (13.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------
function blankValues() {
   document.AddFeedBackLabel.labelName.value = '';
   document.AddFeedBackLabel.isActive[0].checked =true;
   document.AddFeedBackLabel.surveyType.value=1;
   document.AddFeedBackLabel.startDate.value='';
   document.AddFeedBackLabel.toDate.value='';
   document.AddFeedBackLabel.noAttempts.value='';
   document.AddFeedBackLabel.noAttempts.disabled=false;
   document.AddFeedBackLabel.labelName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A BUSROUTE
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editFeedBackLabel() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxInitFeedBackLabelEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {labelId: (document.EditFeedBackLabel.labelId.value),
              labelName: (trim(document.EditFeedBackLabel.labelName.value)),
              surveyType: document.EditFeedBackLabel.surveyType.value,
              startDate1: document.EditFeedBackLabel.startDate1.value,
              toDate1: document.EditFeedBackLabel.toDate1.value,
              noAttempts: document.EditFeedBackLabel.noAttempts.value,
              isActive: (document.EditFeedBackLabel.isActive[0].checked ? 1 : 0 )
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
                    else if("<?php echo LABEL_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo LABEL_ALREADY_EXIST ; ?>"); 
                        document.EditFeedBackLabel.labelName.focus();
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
//Author : Dipanjan Bhattacharjee
// Created on : (13.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxGetFeedBackLabelValues.php';
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
                        messageBox("<?php echo LABEL_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                   
                    var j= trim(transport.responseText).evalJSON();
					
                    if (j.checkFeedBack>0 ){
						document.EditFeedBackLabel.labelName.disabled=true;	
                        if (j.feedBackInfo[0].surveyType == 2) {
                            document.EditFeedBackLabel.noAttempts.disabled=true;
                        }
                        document.EditFeedBackLabel.surveyType.disabled = true;
                    }
                    else {
                        document.EditFeedBackLabel.surveyType.disabled = false;
						document.EditFeedBackLabel.labelName.disabled=false;	
                    }
                    //if (j.checkFeedBack[0]
                    //alert(transport.responseText);
                    //alert(j.feedBackInfo[0].feedbackSurveyId);
                   
                   /*document.EditFeedBackLabel.labelName.value = j.feedbackSurveyLabel;
                   document.EditFeedBackLabel.isActive[0].checked = (j.isActive=="1" ? true : false) ;
                   document.EditFeedBackLabel.isActive[1].checked = (j.isActive=="1" ? false : true) ;
                   if (j.surveyType == 2) {
                     document.EditFeedBackLabel.noAttempts.disabled=true;
                   }
                   alert(j.surveyType);
                   document.EditFeedBackLabel.surveyType.value = j.surveyType;
                   document.EditFeedBackLabel.startDate1.value = j.visibleFrom;
                   document.EditFeedBackLabel.toDate1.value = j.visibleTo;
                   
                   document.EditFeedBackLabel.noAttempts.value = j.noAttempts;
                   document.EditFeedBackLabel.labelId.value = j.feedbackSurveyId;
                   document.EditFeedBackLabel.labelName.focus();*/
                   document.EditFeedBackLabel.labelName.value = j.feedBackInfo[0].feedbackSurveyLabel;
                   document.EditFeedBackLabel.isActive[0].checked = (j.feedBackInfo[0].isActive=="1" ? true : false) ;
                   document.EditFeedBackLabel.isActive[1].checked = (j.feedBackInfo[0].isActive=="1" ? false : true) ;
                   
                   document.EditFeedBackLabel.surveyType.value = j.feedBackInfo[0].surveyType;
                   document.EditFeedBackLabel.startDate1.value = j.feedBackInfo[0].visibleFrom;
                   document.EditFeedBackLabel.toDate1.value = j.feedBackInfo[0].visibleTo;
                   
                   document.EditFeedBackLabel.noAttempts.value = j.feedBackInfo[0].noAttempts;
                   document.EditFeedBackLabel.labelId.value = j.feedBackInfo[0].feedbackSurveyId;

            },
             onFailure:function(){ alert("<?php echo TECHNICAL_PROBLEM;?>") }    
           });
}

/* function to print FeedBack Label report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/feedBackLabelReportPrint.php?'+qstr;
    window.open(path,"FeedBackLabelReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='feedBackLabelReportCSV.php?'+qstr;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FeedBack/listFeedBackLabelContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
    <SCRIPT LANGUAGE="JavaScript">
    <!--
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
    //-->
    </SCRIPT>
</body>
</html>

<?php 
// $History: listFeedBackLabel.php $ 
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 25/07/09   Time: 13:12
//Updated in $/LeapCC/Interface
//Done Bug Fixing.
//Bug ids---0000680 to 0000688,0000690 to 0000696
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 6/29/09    Time: 5:13p
//Updated in $/LeapCC/Interface
//resolve problem in ie explorer to disabled label name
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/26/09    Time: 6:33p
//Updated in $/LeapCC/Interface
//fixed bugs nos.0000179,0000178,0000173,0000172,0000174,0000171,
//0000170, 0000169,0000168,0000167,0000140,0000139,0000138,0000137,
//0000135,0000134,0000136,0000133,0000132,0000131,0000130,
//0000129,0000128,0000127,0000126,0000125
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/24/09    Time: 3:03p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000258,0000260,0000265,0000270,0000255
//
//*****************  Version 4  *****************
//User: Administrator Date: 11/06/09   Time: 11:15
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//0000011,0000012,0000016,0000018,0000020,0000006,0000017,0000009,0000019
//
//*****************  Version 3  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Interface
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 4/14/09    Time: 6:16p
//Updated in $/Leap/Source/Interface
//modified in feedback label & role wise graph
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 2/27/09    Time: 10:58a
//Updated in $/Leap/Source/Interface
//made code to stop duplicacy
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 1/16/09    Time: 1:32p
//Updated in $/Leap/Source/Interface
//modified left alignment
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 1/12/09    Time: 2:55p
//Updated in $/Leap/Source/Interface
//make changes for sending sendReq() function
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/10/09    Time: 6:08p
//Updated in $/Leap/Source/Interface
//modified in message
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/09/09    Time: 5:58p
//Updated in $/Leap/Source/Interface
//fixed the bugs
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/06/09    Time: 6:29p
//Updated in $/Leap/Source/Interface
//modified in code for edit no. of attempts
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/06/09    Time: 4:13p
//Updated in $/Leap/Source/Interface
//modified in feedback label
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:40p
//Created in $/Leap/Source/Interface
//Created FeedBack Masters
?>