<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CityMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/City/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: City Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('cityName','City Name','','',true) , 
                               new Array('cityCode','City Code','width="35%"','',true), 
                               new Array('stateName','State Name','width="25%"','',true) , 
                               new Array('action','Action','width="2%"','align="right"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/City/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddCity';   
editFormName   = 'EditCity';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteCity';
divResultName  = 'results';
page=1; //default page
sortField = 'cityName';
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
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("cityName","<?php echo ENTER_CITY_NAME;?>"),
    new Array("cityCode","<?php echo ENTER_CITY_CODE;?>"),
    new Array("states","<?php echo SELECT_STATE_NAME;?>") );

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
           if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='cityName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo CITY_NAME_LENGTH;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
            if(!isAlphaNumericCustom(trim(eval("frm."+(fieldsArray[i][0])+".value"))," ")) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("Special characters are not allowed");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
     
    }
    if(act=='Add') {
        addCity();
        return false;
    }
    else if(act=='Edit') {
        editCity();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addCity() {
         url = '<?php echo HTTP_LIB_PATH;?>/City/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {cityName: (document.AddCity.cityName.value), cityCode: (document.AddCity.cityCode.value), states: (document.AddCity.states.value)},
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
                             hiddenFloatingDiv('AddCity');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     }
                     else if("<?php echo CITY_CODE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo CITY_CODE_ALREADY_EXIST ;?>"); 
                         document.AddCity.cityCode.focus();
                     }
                     else if("<?php echo CITY_NAME_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo CITY_NAME_ALREADY_EXIST ;?>"); 
                         document.AddCity.cityName.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.AddCity.cityCode.focus(); 
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A NEW CITY
//  id=cityId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteCity(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         url = '<?php echo HTTP_LIB_PATH;?>/City/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {cityId: id},
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
   document.AddCity.reset();
   document.AddCity.cityCode.value = '';
   document.AddCity.cityName.value = '';
   document.AddCity.cityName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editCity() {
         url = '<?php echo HTTP_LIB_PATH;?>/City/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {cityId: (document.EditCity.cityId.value), cityName: (document.EditCity.cityName.value), cityCode: (document.EditCity.cityCode.value), states: (document.EditCity.states.value)},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditCity');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else if("<?php echo CITY_CODE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo CITY_CODE_ALREADY_EXIST ;?>"); 
                         document.EditCity.cityCode.focus();
                     }
                     else if("<?php echo CITY_NAME_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo CITY_NAME_ALREADY_EXIST ;?>"); 
                         document.EditCity.cityName.focus();
                     } 
                     else {
                        messageBox(trim(transport.responseText));
                        document.EditCity.cityCode.focus();                         
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
         document.EditCity.reset();
         url = '<?php echo HTTP_LIB_PATH;?>/City/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {cityId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditCity');
                        messageBox("<?php echo CITY_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');
                   
                   document.EditCity.cityCode.value = j.cityCode;
                   document.EditCity.cityName.value = j.cityName;
                   document.EditCity.states.value = j.stateId;
                   document.EditCity.cityId.value = j.cityId;
                   document.EditCity.cityName.focus();

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/listCityPrint.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"CityReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listCityCSV.php?'+qstr;
    window.location = path;
}

function getFee() {
    
     url = '<?php echo HTTP_LIB_PATH;?>/Fee/CollectFees/tempAjaxStudentFeeValue.php';  
     new Ajax.Request(url,
     {
         method:'post',
         parameters: {id: 1},
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
            hideWaitDialog(true);
            messageBox(trim(transport.responseText));
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       }); 
}

</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/City/listCityContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listCity.php $ 
//
//*****************  Version 7  *****************
//User: Parveen      Date: 2/17/10    Time: 3:38p
//Updated in $/LeapCC/Interface
//print & csv format added 
//
//*****************  Version 6  *****************
//User: Administrator Date: 13/06/09   Time: 16:29
//Updated in $/LeapCC/Interface
//Make city code and city name unique for a state
//
//*****************  Version 5  *****************
//User: Administrator Date: 4/06/09    Time: 15:22
//Updated in $/LeapCC/Interface
//Corrected bugs
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 26/05/09   Time: 15:45
//Updated in $/LeapCC/Interface
//Fixed bugs-----Issues [26-May-09]1
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 13/12/08   Time: 15:56
//Updated in $/LeapCC/Interface
//Corrected Bug corresponding to 25-11-2008
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 11/05/08   Time: 5:34p
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 8/27/08    Time: 7:27p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 8/08/08    Time: 12:49p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:03p
//Updated in $/Leap/Source/Interface
//Added onCreate() function in ajax code
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 6/28/08    Time: 2:35p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 6/28/08    Time: 1:48p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 6/28/08    Time: 12:57p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/28/08    Time: 11:23a
//Updated in $/Leap/Source/Interface
//Added AjaxList Functionality
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/25/08    Time: 2:26p
//Updated in $/Leap/Source/Interface
//*********Solved The Problem********
//Open 2 browsers opening city Masters page. On one page, delete a city.
//On the second page, the deleted city is still visible since editing was
//done on first page. Now, click on the Edit button corresponding to the
//deleted city in the second page which was left untouched. Provide the
//new city Code and click Submit button.A blank popup is displayed. It
//should rather display "The city you are trying to edit no longer
//exists".
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/25/08    Time: 11:37a
//Updated in $/Leap/Source/Interface
//Added AjaxEnabled Delete Functionality
//Added deleteCity() function
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/18/08    Time: 11:52a
//Updated in $/Leap/Source/Interface
//adding constraints done
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/12/08    Time: 6:14p
//Updated in $/Leap/Source/Interface
?>