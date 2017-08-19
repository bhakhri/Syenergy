<?php
//-------------------------------------------------------
// THIS FILE is used for fine & role mapping
// Author : Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignFinetoRoles');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Assign Role to Fines Mapping Master</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
var topPos = 0;
var leftPos = 0;
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
      new Array('srNo','#','width="2%"','',false), 
      new Array('roleNames','Role','width="15%"','',true) , 
      new Array('associativeEmployee','Role Employee Name<br>(Employee Code - Institute)','width="30%"','',true),
      new Array('fineCategoryAbbrs','Fines to be Taken','width="20%"','',true),
      new Array('instituteId','Institute Valid','width="20%"','',true), 
      new Array('userNames','Approver','width="15%"','',true) , 
      new Array('action','Action','width="2%"','align="center"',false)
     );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxMappedFinesList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddMapping';   
editFormName   = 'EditMappingDiv';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteMapping';
divResultName  = 'results';
page=1; //default page
sortField = 'roleName';
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
    
   
    var fieldsArray = new Array(
        new Array("roleName","<?php echo SELECT_ROLE;;?>"),
        new Array("fineName","<?php echo SELECT_FINE_NAME;?>"),
        new Array("validInstitute","Select Institute"),
        new Array("approver","<?php echo ENTER_APROVER_NAME;?>"), 
        new Array("classId","Select class") 
	
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
    if(act=='Add') {
        addMapping();
        return false;
    }
    else if(act=='Edit') {
        editMapping();
        return false;
    }
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO ADD A NEW CITY
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addMapping() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxAddFineRoleMapping.php';
         var l=document.AddMapping.fineName.length;
         var selFines="";
         for(var i=0 ; i < l ;i++){
             if(document.AddMapping.fineName.options[ i ].selected){
                 if(selFines!=''){
                     selFines +=',';
                 }
                 selFines += document.AddMapping.fineName.options[ i ].value;
             }
         }
         if(trim(selFines)==''){
            messageBox("Select atleast one fine");
            document.AddMapping.fineName.focus();
            return false;
         }
         
         
         var li=document.AddMapping.validInstitute.length;
         var selInstitutes="";
         for(var i=0 ; i < li ;i++){
             if(document.AddMapping.validInstitute.options[ i ].selected){
                 if(selInstitutes!=''){
                     selInstitutes +=',';
                 }
                 selInstitutes += document.AddMapping.validInstitute.options[ i ].value;
             }
         }
         if(trim(selInstitutes)==''){
            messageBox("Select atleast one Institute");
            document.AddMapping.validInstitute.focus();
            return false;
         }
         
         
         var li=document.AddMapping.classId.length;
         var selClassId="";
         for(var i=0 ; i < li ;i++){
             if(document.AddMapping.classId.options[ i ].selected){
                 if(selClassId!=''){
                     selClassId +=',';
                 }
                 selClassId += document.AddMapping.classId.options[ i ].value;
             }
         }
         if(trim(selClassId)==''){
            messageBox("Select atleast one class");
            document.AddMapping.selClassId.focus();
            return false;
         }
         
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                  action: 'add',
                  roleId:  (document.AddMapping.roleName.value), 
                  instituteIds:  (selInstitutes), 
                  fineIds: (selFines), 
                  fineClassId: selClassId,
                  userIds: trim(document.AddMapping.approver.value)
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
                             hiddenFloatingDiv('AddMappingDiv');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.AddMapping.approver.focus();
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteMapping(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxDeleteFineRoleMapping.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 roleFineId: id
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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "addCity" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddMapping.reset();
   document.AddMapping.classId.length = null; 
   document.AddMapping.roleName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editMapping() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxAddFineRoleMapping.php';
         var l=document.EditMapping.fineName.length;
         var selFines="";
         for(var i=0 ; i < l ;i++){
             if(document.EditMapping.fineName.options[ i ].selected){
                 if(selFines!=''){
                     selFines +=',';
                 }
                 selFines += document.EditMapping.fineName.options[ i ].value;
             }
         }
         if(trim(selFines)==''){
            messageBox("Select atleast one fine");
            document.EditMapping.fineName.focus();
            return false;
         }
         
         
         var l=document.EditMapping.validInstitute.length;
         var selInstitutes="";
         for(var i=0 ; i < l ;i++){
             if(document.EditMapping.validInstitute.options[ i ].selected){
                 if(selInstitutes!=''){
                     selInstitutes +=',';
                 }
                 selInstitutes += document.EditMapping.validInstitute.options[ i ].value;
             }
         }
         if(trim(selInstitutes)==''){
            messageBox("Select atleast one institute");
            document.EditMapping.validInstitute.focus();
            return false;
         }
         
         var li=document.EditMapping.classId.length;
         var selClassId="";
         for(var i=0 ; i < li ;i++){
             if(document.EditMapping.classId.options[ i ].selected){
                 if(selClassId!=''){
                     selClassId +=',';
                 }
                 selClassId += document.EditMapping.classId.options[ i ].value;
             }
         }
         if(trim(selClassId)==''){
            messageBox("Select atleast one class");
            document.EditMapping.selClassId.focus();
            return false;
         }
         
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 action: 'edit',
                 roleFineId: (document.EditMapping.roleFineId.value), 
                 roleId:    (document.EditMapping.roleName.value),                
                 fineIds: (selFines), 
                 instituteIds: (selInstitutes),
                 fineClassId: selClassId,
                 userIds: trim(document.EditMapping.approver.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditMappingDiv');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.EditMapping.approver.focus();
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
         document.EditMapping.reset();
         url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxGetMappedFines.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                roleFineId: id
             },
             asynchronous:false, 
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                hideWaitDialog(true);
                if(trim(transport.responseText)==0) {
                   hiddenFloatingDiv('EditMappingDiv');
                   messageBox("<?php echo ROLE_FINE_MAPPING_NOT_EXIST; ?>");
                   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                   return false;
                }
                
                var ret=trim(transport.responseText).split('!!~~!!');
                j = eval('('+ret[0]+')');
                
                fineClassId = j.fineClassId;
                document.EditMapping.roleFineId.value = j.roleFineId;
                document.EditMapping.roleName.value = j.roleId;
                var fineId=j.fineCategoryId.split(',');
                var cnt=fineId.length;
                var l=document.EditMapping.fineName.options.length;
                for(var i=0;i<cnt;i++){
                  for(var k=0;k<l;k++){
                      if(document.EditMapping.fineName.options[k].value==fineId[i]){
                        document.EditMapping.fineName.options[k].selected=true;  
                      }
                  }                            
                }
                
                var instituteId=j.fineInstituteId.split(',');
                var cnt=instituteId.length;
                var l=document.EditMapping.validInstitute.options.length;
                for(var i=0;i<cnt;i++){
                  for(var k=0;k<l;k++){
                      if(document.EditMapping.validInstitute.options[k].value==instituteId[i]){
                        document.EditMapping.validInstitute.options[k].selected=true;  
                      }
                  }                            
                }
                document.EditMapping.approver.value=trim(j.userName);                  
                
                j = eval('('+ret[1]+')');
                len = j.length; 
                document.EditMapping.classId.length = null;
                for(i=0;i<len;i++) {
                  addOption(document.EditMapping.classId, j[i].classId, j[i].instituteClassName);
                }
                
                var classId=fineClassId.split(',');
                var cnt=classId.length;
                var l=document.EditMapping.classId.options.length;
                for(var i=0;i<cnt;i++){
                  for(var k=0;k<l;k++){
                      if(document.EditMapping.classId.options[k].value==classId[i]){
                        document.EditMapping.classId.options[k].selected=true;  
                      }
                  }                            
                }
                
                document.EditMapping.roleName.focus(); 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print fine category report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/assignFineToRoleReportPrint.php?'+qstr;
    window.open(path,"AssignFineToRoleReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='assignFineToRoleReportCSV.php?'+qstr;
}
/*function for help details*/
function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');   
      return false;
    }
    
    if(document.getElementById('helpChk').checked == false) {
      return false;
    }
    //document.getElementById('divHelpInfo').innerHTML=title; 
    document.getElementById('helpInfo').innerHTML= msg;   
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);
    
    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}

function getClass(mod,type) {

    if(mod=='A') {
	  form = document.AddMapping;
    }
    else {
      form = document.EditMapping;   
    }
    form.classId.length = null;  
    
    var l=form.validInstitute.length;
    var selInstitutes="";
    for(var i=0 ; i < l ;i++) {
        if(type=='A') {
            if(selInstitutes!=''){
              selInstitutes +=',';
            }
            selInstitutes += form.validInstitute.options[ i ].value; 
        }
        else {
            if(form.validInstitute.options[ i ].selected){
                if(selInstitutes!=''){
                  selInstitutes +=',';
                }
                selInstitutes += form.validInstitute.options[ i ].value;
            }
        }
    }
    
	var url = '<?php echo HTTP_LIB_PATH;?>/Fine/getClases.php';
    
	new Ajax.Request(url,
	{
		method:'post',
		parameters: {validInstitute: selInstitutes,
                     mode: mod
                    },
		asynchronous:false,  	
		onCreate: function(){
		  showWaitDialog(true);
		},
		 onSuccess: function(transport){ 
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.classId.length = null;
			for(i=0;i<len;i++) {
			  addOption(form.classId, j[i].classId, j[i].instituteClassName);
			}
			resetResult('other');
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

</script>
</head>                                   
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Fine/assignFineToRoleContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>

