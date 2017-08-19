<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in User Login Report
//
//
// Author :Gurkeerat Sidhu
// Created on : 29.12.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UserLoginReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: User Login Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
   


 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/UserLogin/initUserLoginReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'UserLoginForm'; // name of the form which will be used for search
//addFormName    = 'AddState';   
//editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'roleUserName';
sortOrderBy    = 'ASC';

function validateAddForm(frm) {
     var fieldsArray = new Array(new Array("startDate","<?php echo SELECT_DATE;?>"),
								new Array("toDate","<?php echo SELECT_TODATE;?>")
								);

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
	 fromDateArray = document.UserLoginForm.startDate.value.split("-");
	 toDateArray = document.UserLoginForm.toDate.value.split("-");
	 fromDateYear = fromDateArray[0];
	 fromDateMonth = fromDateArray[1];
	 fromDateDay = fromDateArray[2];

	 toDateYear = toDateArray[0];
	 toDateMonth = toDateArray[1];
	 toDateDay = toDateArray[2];

	 if (toDateYear < fromDateYear) {
		 messageBox("To date can not be less than From date");
		 return false;
	 }
	 else if (toDateYear == fromDateYear && toDateMonth < fromDateMonth) {
			 messageBox("To date can not be less than From date");
			 return false;
	 }
	 else if (toDateYear == fromDateYear && toDateMonth == fromDateMonth && toDateDay < fromDateDay) {
			 messageBox("To date can not be less than From date");
			 return false;
	 }

	//openStudentLists(frm.name,'rollNo','Asc');    
		document.getElementById("nameRow").style.display='';
		document.getElementById("nameRow2").style.display='';
		document.getElementById("resultRow").style.display='';
        sortField='roleUserName';
        
        if (document.UserLoginForm.notLoggedIn.checked == true){
            document.UserLoginForm.listView.value = 1; 
            
          tableHeadArray = new Array(    
                                new Array('srNo','#','width="5%"','',false),
                                new Array('roleUserName','Name','width="25%"','align=left',true),
                                new Array('rollNo','Roll No.','width="20%"','align=left',true),
                                new Array('className','Class','width="25%"','align=left',true)
                            );  
        }
        else{
            document.UserLoginForm.listView.value = 0;   
            if (document.UserLoginForm.loginDetail[0].checked == true) {
            tableHeadArray = new Array(    
                                new Array('srNo','#','width="5%"','',false),
                                new Array('roleUserName','Name','width="25%"','align=left',true),
                                new Array('userName','UserName','width="15%"','align=left',true),
                                new Array('roleName','Role','width="15%"','align=left',true),
                                new Array('dateTimeIn','Date&Time','width="30%"','align=center',false),
                                new Array('userCount','Count','width="10%"','align=right',true)
                            );
        }
      else {
            tableHeadArray = new Array(    
                                new Array('srNo','#','width="5%"','',false),
                                new Array('roleUserName','Name','width="15%"','align=left',true),
                                new Array('userName','UserName','width="10%"','align=left',true),
                                new Array('roleName','Role','width="10%"','align=left',true),
                                new Array('loggedInTime','Date','width="10%"','align=center',true),
                                new Array('timeIn','Time','width="40%"','align=left',false),
                                new Array('userCount','Count','width="10%"','align=right',true)
                            );
       }
     }    
		sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}
function vanishData(){
   document.getElementById('resultsDiv').innerHTML='';
   document.getElementById('nameRow').style.display='none';
   document.getElementById('nameRow2').style.display='none';  
}
function vanishData1(){ 
if(document.UserLoginForm.notLoggedIn.checked == true){
   document.UserLoginForm.loginDetail[0].disabled = true; 
   document.UserLoginForm.loginDetail[1].disabled = true;
   }
   else{
    document.UserLoginForm.loginDetail[0].disabled = false; 
    document.UserLoginForm.loginDetail[1].disabled = false;   
   }
}
/*function printReport(fineCategoryId,startDate,toDate) {
	form = document.FineCollectionForm;
	path='<?php echo UI_HTTP_PATH;?>/fineCollectionReportPrint.php?fineCategoryId='+fineCategoryId+'&startDate='+form.startDate.value+'&toDate='+form.toDate.value;
	a = window.open(path,"FineCollectionReport","status=1,menubar=1,scrollbars=1, width=900");
}  */
/* function to print FeedBack Grades report*/
function printReport() {
    var reportFormat=document.UserLoginForm.loginDetail[0].checked==true?1:0;
    var listView=document.UserLoginForm.listView.value;
    //var qstr="searchbox="+trim(document.UserLoginForm.searchbox.value);
    var qstr='startDate='+document.getElementById('startDate').value+'&toDate='+document.getElementById('toDate').value+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField+'&reportFormat='+reportFormat;
    var path='<?php echo UI_HTTP_PATH;?>/userLoginReportPrint.php?'+qstr+'&listView='+listView;
    window.open(path,"userLoginReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var reportFormat=document.UserLoginForm.loginDetail[0].checked==true?1:0;
    var listView=document.UserLoginForm.listView.value;
    //var qstr="searchbox="+trim(document.UserLoginForm.searchbox.value);
    var qstr='startDate='+document.getElementById('startDate').value+'&toDate='+document.getElementById('toDate').value+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField+'&reportFormat='+reportFormat;
    window.location='userLoginReportCSV.php?'+qstr+'&listView='+listView;
}
function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}


</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/UserLogin/listUserLoginContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

