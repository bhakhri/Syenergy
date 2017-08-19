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
define('MODULE','LeaveSetMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/LeaveSet/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Leave Set Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                new Array('srNo',       '#',            'width="4%"','',false), 
                                new Array('leaveSetName',  'Leave Set Name',         'width="70%"','',true) , 
                                new Array('isActive',   'Active',       'width="20%"','align="center"',true), 
                                new Array('action',     'Action',       'width="3%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/LeaveSet/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddLeaveSet';   
editFormName   = 'EditLeaveSet';
winLayerWidth  = 300; //  add/edit form width
winLayerHeight = 300; // add/edit form height
deleteFunction = 'return deleteLeaveSet';
divResultName  = 'results';
page=1; //default page
sortField = 'leaveSetName';
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
        var fieldsArray = new Array( new Array("leaveSetName","<?php echo ENTER_LEAVE_SET_NAME; ?>")
                                     //new Array("fromDate","<?php echo EMPTY_FROM_DATE;?>"),
                                     //new Array("toDate","<?php echo EMPTY_TO_DATE;?>")
                                    );
    }
    else if(act=='Edit') {
        var fieldsArray = new Array( new Array("leaveSetName","<?php echo ENTER_LEAVE_SET_NAME; ?>")
                                     //new Array("fromDate1","<?php echo EMPTY_FROM_DATE;?>"),
                                     //new Array("toDate1","<?php echo EMPTY_TO_DATE;?>")
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
            /*if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length <3 && fieldsArray[i][0]=='leaveSetName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo LEAVE_SET_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
            if(!isAlphaNumericCustom(eval("frm."+(fieldsArray[i][0])+".value"),"-._ ") && fieldsArray[i][0]=='leaveSetName') {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_ALPHABETS_NUMERIC_LABEL; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }*/
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
        /*
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
         } */
    }   
    
    
    if(act=='Add') {
        addLeaveSet();
        return false;
    }
    else if(act=='Edit') {
        editLeaveSet();
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
function addLeaveSet() {
         url = '<?php echo HTTP_LIB_PATH;?>/LeaveSet/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                    leaveSetName: (trim(document.AddLeaveSet.leaveSetName.value)),
                    //fromDate:  (trim(document.AddLeaveSet.fromDate.value)),
                    //toDate:    (trim(document.AddLeaveSet.toDate.value)),
                    isActive: (document.AddLeaveSet.isActive[0].checked ? 1 : 0 )
					//timeTableType: (document.AddLeaveSet.timeTableType[0].checked ? 1 : 2 )
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
						 else{
						     hiddenFloatingDiv('AddLeaveSet');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
						 }
						 
					  }
                      else if("<?php echo LEAVE_SET_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo LEAVE_SET_ALREADY_EXIST ;?>"); 
                        document.AddLeaveSet.leaveSetName.focus();
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
function deleteLeaveSet(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/LeaveSet/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {leaveSetId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "AddLeaveSet" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddLeaveSet.leaveSetName.value = '';
   //document.AddLeaveSet.fromDate.value = '';
   //document.AddLeaveSet.toDate.value = '';
   document.AddLeaveSet.isActive[0].checked =true;
   //document.AddLeaveSet.timeTableType[0].checked =true;
   document.AddLeaveSet.leaveSetName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A BUSROUTE
//
//Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editLeaveSet() {
    
         url = '<?php echo HTTP_LIB_PATH;?>/LeaveSet/ajaxInitEdit.php';
         new Ajax.Request(url,
           {
              method:'post',
              parameters: {leaveSetId: (document.EditLeaveSet.leaveSetId.value),
              leaveSetName: (trim(document.EditLeaveSet.leaveSetName.value)), 
              //fromDate1: (trim(document.EditLeaveSet.fromDate1.value)), 
              //toDate1: (trim(document.EditLeaveSet.toDate1.value)), 
              isActive: (document.EditLeaveSet.isActive[0].checked ? 1 : 0 )
			  //timeTableType: (document.EditLeaveSet.timeTableType[0].checked ? 1 : 2 )
             },
             onCreate: function() {
                 showWaitDialog(true);
                 
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditLeaveSet');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo LEAVE_SET_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo LEAVE_SET_ALREADY_EXIST ; ?>"); 
                        document.EditLeaveSet.leaveSetName.focus();
                    }  
                    else {
                        messageBox(trim(transport.responseText));                         
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditLeaveSet" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/LeaveSet/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {leaveSetId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditLeaveSet');
                        messageBox("<?php echo LABEL_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');
                   document.EditLeaveSet.leaveSetName.value = j.leaveSetName;
                   //document.EditLeaveSet.fromDate1.value = j.startDate;  
                   //document.EditLeaveSet.toDate1.value = j.endDate;  
                   document.EditLeaveSet.isActive[0].checked = (j.isActive=="1" ? true : false) ;
                   document.EditLeaveSet.isActive[1].checked = (j.isActive=="1" ? false : true) ;
				   //document.EditLeaveSet.timeTableType[0].checked = (j.timeTableType=="1" ? true : false) ;
                   //document.EditLeaveSet.timeTableType[1].checked = (j.timeTableType=="1" ? false : true) ;
                   document.EditLeaveSet.leaveSetId.value = j.leaveSetId;
                   document.EditLeaveSet.leaveSetName.focus();
            },
             onFailure:function(){ alert("<?php echo TECHNICAL_PROBLEM;?>") }    
           });
}


/* function to print Time Table Label report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/leaveSetReportPrint.php?'+qstr;
    window.open(path,"LeaveSetReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
  
    window.location='leaveSetReportCSV.php?'+qstr;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/LeaveSet/listLeaveSetContents.php");
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