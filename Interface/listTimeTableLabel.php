<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF TimeTable Labels ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CreateTimeTableLabels');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/TimeTableLabel/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Time Table Label Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                new Array('srNo',       '#',            'width="3%"','',false), 
                                new Array('labelName',  'Name',         'width="25%"','',true) , 
                                new Array('startDate',  'From Date',   'width="20%"','align="center"',true) , 
                                new Array('endDate',    'To Date',     'width="20%"','align="center"',true) , 
                                new Array('isActive',   'Active',       'width="12%"','',true), 
								new Array('typeOf',		'Type',       'width="12%"','',true), 
                                new Array('action',     'Action',       'width="1%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/TimeTableLabel/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddTimeTableLabel';   
editFormName   = 'EditTimeTableLabel';
winLayerWidth  = 300; //  add/edit form width
winLayerHeight = 300; // add/edit form height
deleteFunction = 'return deleteTimeTableLabel';
divResultName  = 'results';
page=1; //default page
sortField = 'labelName';
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
//Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008)
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
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
               
    if(act=='Add') {
        var fieldsArray = new Array( new Array("labelName","<?php echo ENTER_LABEL_NAME; ?>"),
                                     new Array("fromDate","<?php echo EMPTY_FROM_DATE;?>"),
                                     new Array("toDate","<?php echo EMPTY_TO_DATE;?>")
                                    );
    }
    else if(act=='Edit') {
        var fieldsArray = new Array( new Array("labelName","<?php echo ENTER_LABEL_NAME; ?>"),
                                     new Array("fromDate1","<?php echo EMPTY_FROM_DATE;?>"),
                                     new Array("toDate1","<?php echo EMPTY_TO_DATE;?>")
                                    );
    }
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
            if(!isAlphaNumericCustom(eval("frm."+(fieldsArray[i][0])+".value"),"-._ ") && fieldsArray[i][0]=='labelName') {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_ALPHABETS_NUMERIC_LABEL; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
        
        if(act=='Add' && eval("frm.fromDate.value")=="0000-00-00") {
            messageBox ("<?php echo EMPTY_FROM_DATE;?>");
            eval("frm.fromDate.focus();");
            return false;
            break; 
        }
        
        if(act=='Add' && eval("frm.toDate.value")=="0000-00-00") {
            messageBox ("<?php echo EMPTY_TO_DATE;?>");
            eval("frm.toDate.focus();");
            return false;
            break; 
        }
        
        if(act=='Edit' && eval("frm.fromDate1.value")=="0000-00-00") {
            messageBox ("<?php echo EMPTY_FROM_DATE;?>");
            eval("frm.fromDate1.focus();");
            return false;
            break; 
        }
        
        if(act=='Edit' && eval("frm.toDate1.value")=="0000-00-00") {
            messageBox ("<?php echo EMPTY_TO_DATE;?>");
            eval("frm.toDate1.focus();");
            return false;
            break; 
        }
        
        if(act=='Add' && !dateDifference(eval("frm.fromDate.value"),eval("frm.toDate.value"),'-')) {
                messageBox ("<?php echo DATE_VALIDATION;?>");
                eval("frm.fromDate.focus();");
                return false;
                break;
         } 
         else if(act=='Edit' && !dateDifference(eval("frm.fromDate1.value"),eval("frm.toDate1.value"),'-')) {
                messageBox ("<?php echo DATE_VALIDATION;?>");
                eval("frm.fromDate1.focus();");
                return false;
                break;
         } 
    }   
    
    
    if(act=='Add') {
        addTimeTableLabel();
        return false;
    }
    else if(act=='Edit') {
        editTimeTableLabel();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW TimeTable Label
//
//Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addTimeTableLabel() {
         url = '<?php echo HTTP_LIB_PATH;?>/TimeTableLabel/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                    labelName: (trim(document.AddTimeTableLabel.labelName.value)),
                    fromDate:  (trim(document.AddTimeTableLabel.fromDate.value)),
                    toDate:    (trim(document.AddTimeTableLabel.toDate.value)),
                    isActive: (document.AddTimeTableLabel.isActive[0].checked ? 1 : 0 ),
					timeTableType: (document.AddTimeTableLabel.timeTableType[0].checked ? 1 : 2 )
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
                        document.AddTimeTableLabel.lebelName.focus();
                      }  
                      else if("<?php echo DATE_VALIDATION;?>" == trim(transport.responseText)){
                        messageBox("<?php echo DATE_VALIDATION ; ?>"); 
                        document.AddTimeTableLabel.fromDate.focus();
                       }  
                       else if("<?php echo EMPTY_FROM_DATE;?>" == trim(transport.responseText)){
                        messageBox("<?php echo EMPTY_FROM_DATE ; ?>"); 
                        document.AddTimeTableLabel.fromDate.focus();
                       } 
                       else if("<?php echo EMPTY_TO_DATE;?>" == trim(transport.responseText)){
                        messageBox("<?php echo EMPTY_TO_DATE ; ?>"); 
                        document.AddTimeTableLabel.toDate.focus();
                       } 
                        else if("<?php echo FROM_TO_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo FROM_TO_ALREADY_EXIST ; ?>"); 
                        document.AddTimeTableLabel.fromDate.focus();
                        }
                         else {
                             hiddenFloatingDiv('AddTimeTableLabel');
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
//THIS FUNCTION IS USED TO DELETE A AddTimeTable Label
//  id=busRouteId
//Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteTimeTableLabel(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/TimeTableLabel/ajaxInitDelete.php';
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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "AddTimeTableLabel" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddTimeTableLabel.labelName.value = '';
   document.AddTimeTableLabel.fromDate.value = '';
   document.AddTimeTableLabel.toDate.value = '';
   document.AddTimeTableLabel.isActive[0].checked =true;
   document.AddTimeTableLabel.timeTableType[0].checked =true;
   document.AddTimeTableLabel.labelName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A BUSROUTE
//
//Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editTimeTableLabel() {
    
         url = '<?php echo HTTP_LIB_PATH;?>/TimeTableLabel/ajaxInitEdit.php';
         new Ajax.Request(url,
           {
              method:'post',
              parameters: {labelId: (document.EditTimeTableLabel.labelId.value),
              labelName: (trim(document.EditTimeTableLabel.labelName.value)), 
              fromDate1: (trim(document.EditTimeTableLabel.fromDate1.value)), 
              toDate1: (trim(document.EditTimeTableLabel.toDate1.value)), 
              isActive: (document.EditTimeTableLabel.isActive[0].checked ? 1 : 0 ),
			  timeTableType: (document.EditTimeTableLabel.timeTableType[0].checked ? 1 : 2 )
             },
             onCreate: function() {
                 showWaitDialog(true);
                 
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditTimeTableLabel');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo LABEL_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo LABEL_ALREADY_EXIST ; ?>"); 
                        document.EditTimeTableLabel.labelName.focus();
                    }  
                    else if("<?php echo EMPTY_FROM_DATE;?>" == trim(transport.responseText)){
                        messageBox("<?php echo EMPTY_FROM_DATE ; ?>"); 
                        document.EditTimeTableLabel.fromDate1.focus();
                       } 
                       else if("<?php echo EMPTY_TO_DATE;?>" == trim(transport.responseText)){
                        messageBox("<?php echo EMPTY_TO_DATE ; ?>"); 
                        document.EditTimeTableLabel.toDate1.focus();
                       } 
                    else if("<?php echo DATE_VALIDATION;?>" == trim(transport.responseText)){
                        messageBox("<?php echo DATE_VALIDATION ; ?>"); 
                        document.EditTimeTableLabel.fromDate1.focus();
                    }  
                     else if("<?php echo FROM_TO_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo FROM_TO_ALREADY_EXIST ; ?>"); 
                        document.EditTimeTableLabel.fromDate1.focus();
                    }  
                    else {
                        messageBox(trim(transport.responseText));                         
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditTimeTableLabel" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/TimeTableLabel/ajaxGetValues.php';
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
                        hiddenFloatingDiv('EditTimeTableLabel');
                        messageBox("<?php echo LABEL_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');
                   document.EditTimeTableLabel.labelName.value = j.labelName;
                   document.EditTimeTableLabel.fromDate1.value = j.startDate;  
                   document.EditTimeTableLabel.toDate1.value = j.endDate;  
                   document.EditTimeTableLabel.isActive[0].checked = (j.isActive=="1" ? true : false) ;
                   document.EditTimeTableLabel.isActive[1].checked = (j.isActive=="1" ? false : true) ;
				   document.EditTimeTableLabel.timeTableType[0].checked = (j.timeTableType=="1" ? true : false) ;
                   document.EditTimeTableLabel.timeTableType[1].checked = (j.timeTableType=="1" ? false : true) ;
                   document.EditTimeTableLabel.labelId.value = j.timeTableLabelId;
                   document.EditTimeTableLabel.labelName.focus();
            },
             onFailure:function(){ alert("<?php echo TECHNICAL_PROBLEM;?>") }    
           });
}


/* function to print Time Table Label report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/timeTableLabelReportPrint.php?'+qstr;
    window.open(path,"TimeTableLabelReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
  
    window.location='timeTableLabelReportCSV.php?'+qstr;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TimeTableLabel/listTimeTableLabelContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>    
</body>
</html>
<?php 
// $History: listTimeTableLabel.php $ 
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 4/21/10    Time: 4:34p
//Updated in $/LeapCC/Interface
//done changes as per FCNS No. 1625
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 24/07/09   Time: 14:58
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids----0000648,0000650,0000667,0000651,0000676,0000649,0000652
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 21/07/09   Time: 12:08
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids ----0000627,0000632,0000633,0000640
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 20/07/09   Time: 19:08
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids ---0000629 to 0000631
//
//*****************  Version 5  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Interface
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 4  *****************
//User: Parveen      Date: 3/12/09    Time: 12:15p
//Updated in $/LeapCC/Interface
//condition update
//
//*****************  Version 3  *****************
//User: Parveen      Date: 3/10/09    Time: 2:35p
//Updated in $/LeapCC/Interface
//start and end date for fields added
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
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/08/08   Time: 3:42p
//Updated in $/Leap/Source/Interface
//applied role level access
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/30/08    Time: 3:33p
//Created in $/Leap/Source/Interface
//Created TimeTable Labels
?>