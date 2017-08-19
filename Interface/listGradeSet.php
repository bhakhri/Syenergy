<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Grade Form
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GradeSetMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Grade Set Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('gradeSetName','Grade Set Name','width="80%"','',true), 
                               new Array('isActive','Active','width="10%"','align="center"',true), 
                               new Array('action','Action','width="8%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/GradeSet/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddGrade';   
editFormName   = 'EditGrade';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteGrade';
divResultName  = 'results';
page=1; //default page
sortField = 'gradeSetName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      

//This function Displays Div Window
function editWindow(id,dv,w,h) {
   
	displayWindow(dv,w,h);
	populateValues(id);   
}

function validateAddForm(frm, act) {

    if(act=='Add') {
	  form  = document.addGrade;
    }
    else if(act=='Edit') {
      form  = document.editGrade;
    }    
    
	if(trim(form.gradeSetName.value) == '') {
	  messageBox ("<?php echo ENTER_GRADESET_NAME;?>");
	  form.gradeSetName.focus();
	  return false;
	}

  	if(act=='Add') {
	  addGrade();
	  return false;
	}
	else if(act=='Edit') {
	  editGrade();  
	  return false;
	}
    
    return false;
}
function addGrade() {
         url = '<?php echo HTTP_LIB_PATH;?>/GradeSet/ajaxInitAdd.php';
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {gradeSetName: trim(document.addGrade.gradeSetName.value),
                          isActive: (document.addGrade.isActive1[0].checked ? 1 : 0 )  
                         },
			 onCreate: function(){
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
						 hiddenFloatingDiv('AddGrade');
						 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
						 //location.reload();
						 return false;
					 }
				 } 
				 else {
					messageBox(trim(transport.responseText)); 
				 }
				 },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function blankValues() {
   document.addGrade.gradeSetName.value = '';
   document.addGrade.isActive1[0].checked =true;
   document.addGrade.gradeSetName.focus();
}

function editGrade() {
         url = '<?php echo HTTP_LIB_PATH;?>/GradeSet/ajaxInitEdit.php';
       
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {gradeSetName: trim(document.editGrade.gradeSetName.value),
                          isActive: (document.editGrade.isActive1[0].checked ? 1 : 0 ), 
                          gradeSetId:(document.editGrade.gradeSetId.value)},
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
				 hideWaitDialog(true);
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					 hiddenFloatingDiv('EditGrade');
					 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
					 return false;
					 //location.reload();
				 }
				 else {
					 messageBox(trim(transport.responseText));
				 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function deleteGrade(id) {  
	 if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
		 return false;
	 }
	 else {
	 url = '<?php echo HTTP_LIB_PATH;?>/GradeSet/ajaxInitDelete.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {gradeSetId: id},
		 onCreate: function(){
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

function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/GradeSet/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {gradeSetId: id},
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    j = eval('('+trim(transport.responseText)+')');
                   document.editGrade.gradeSetId.value = id;
                   document.editGrade.gradeSetName.value = j.gradeSetName;
                   document.editGrade.isActive1[0].checked = (j.isActive=="1" ? true : false) ;
                   document.editGrade.isActive1[1].checked = (j.isActive=="1" ? false : true) ;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function printReport() {
	
	//sortField = listObj.sortField;
	//sortOrderBy = listObj.sortOrderBy;

	var path='<?php echo UI_HTTP_PATH;?>/displayGradeSetReport.php?searchbox='+trim(document.searchForm.searchbox_h.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
	 var a=window.open(path,"GradeSet","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
   
}



/* function to output data to a CSV*/

function printReportCSV() {
//	sortField = listObj.sortField;
//	sortOrderBy = listObj.sortOrderBy;

    var qstr="searchbox="+trim(document.searchForm.searchbox_h.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayGradeSetCSV.php?'+qstr;
	window.location = path;
}

///////////////////
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/GradeSet/listGradeSetContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
    <!--
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
</SCRIPT>    
</body>
</html>
