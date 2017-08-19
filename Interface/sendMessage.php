<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/City/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Send Message </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeJS("tiny_mce/tiny_mce.js"); 
?> 
<script language="javascript">
tinyMCE.init({
        mode : "textareas",
        theme : "advanced",


       // Theme options
       theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
       theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",

    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true
});



// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('students','<input type=\"checkbox\" id=\"studentList\" name=\"studentList\" onclick=\"selectStudents();\">Students','width="10%"','align=\"left\"',false), 
 new Array('parents','<input type=\"checkbox\" id=\"parentList\" name=\"parentList\" onclick=\"selectParents();\">Parents','width="10%"','align=\"left\"',false), 
 new Array('rollNo','R. No','width="15%"','',true) ,
 new Array('universityRollNo','Univ. R. No.','width="15%"','',true),
 new Array('studentName','Name','width="45%"','',true) );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/SendMessage/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
/*
addFormName    = 'AddCity';   
editFormName   = 'EditCity';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteCity';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button



//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all student checkboxes
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function  selectStudents(){
    
    //state:checked/not checked
    var state=document.getElementById('studentList').checked;
    formx = document.listFrm; 
    
    var l=formx.students.length;
    
    for(var i=0 ;i < l ; i++){
        formx.students[ i ].checked=state;
    }
    
}


//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any student checkboxes selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
function checkStudents(){
    
    formx = document.listFrm; 
    var l=formx.students.length;
    var fl=0;   
    for(var i=0 ;i < l ; i++){
        if(formx.students[ i ].checked==true){
            fl=1;
            break;
        }
    }
    
    return (fl);
    
}

//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all parent checkboxes
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function  selectParents(){
    
    //state:checked/not checked
    var state=document.getElementById('parentList').checked;
    
    formx = document.listFrm; 
    
    var l=formx.parents.length;
    
    for(var i=0 ;i < l ; i++){
        formx.parents[ i ].checked=state;
    }
    
}


//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any parent checkboxes selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
function checkParents(){
    
    formx = document.listFrm; 
    var l=formx.parents.length;
    var fl=0;   
    for(var i=0 ;i < l ; i++){
        if(formx.parents[ i ].checked==true){
            fl=1;
            break;
        }
    }
    
    return (fl);
    
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO get group list
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateGroup(){
   
    //cleans "group" dropdown
    document.getElementById('group').options.length=0;
    var objOption = new Option("Select Group","");
    document.getElementById('group').options.add(objOption); 
    document.getElementById('group').selectedIndex=0; 
    
    if((document.getElementById('class').value != "") && (document.getElementById('batch').value != "") && (document.getElementById('studyPeriod').value != "")){

        var arr= document.getElementById('class').value.split("-");
        
        url = '<?php echo HTTP_LIB_PATH;?>/SendMessage/ajaxGetGroups.php';
       
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {universityId: arr[0],
             degreeId: arr[1],
             branchId: arr[2],
             batchId: document.getElementById('batch').value,
             studyPeriodId: document.getElementById('studyPeriod').value
             },
             onSuccess: function(transport){
               if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                   
                  showWaitDialog(true);
               }
               else {
                    hideWaitDialog(true);
                    j = eval('('+trim(transport.responseText)+')');
                    for(var c=0;c<j.length;c++){
                        var objOption = new Option(j[c].groupName,j[c].groupId+"~"+j[c].classId);
                        document.getElementById('group').options.add(objOption);
                    }

               }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
        
    }
    
}



//-------------------------------------------------------
//THIS FUNCTION IS USED TO hide "showList" div
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function hide_div(id,mode){
    
    if(mode==2){
     document.getElementById(id).style.display='none';
    }
    else{
        document.getElementById(id).style.display='block';
    }
}



//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------

function getData(){
    if((document.getElementById('class').value != "") && (document.getElementById('batch').value != "") && (document.getElementById('studyPeriod').value != "") && (document.getElementById('group').value != "") ){
        hide_div('showList',1);
        sendReq(listURL,divResultName,searchFormName,'');
    }
   else{
       alert("Select Class,Batch,Study Period and Group to get Student List");
       document.getElementById('class').focus();
   } 
    
}




//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateForm() {
    
  if(isEmpty(tinyMCE.get('elm1').getContent()))
    {
        alert("Message Body Cannot be Empty");
        document.getElementById('elm1').focus();
        return false;
    }
  else if(!(document.getElementById('smsCheck').checked) && !(document.getElementById('emailCheck').checked) && !(document.getElementById('dashBoardCheck').checked) ) {

       alert("Please Select a Message Medium");
       document.getElementById('smsCheck').focus(); 
       return false;
    }
  else if(!dateDifference(document.getElementById('startDate').value,document.getElementById('endDate').value,"-")){
      
      alert("'Visible To' Date can not be smaller than 'Visible From' Date");    
      document.getElementById('startDate').focus();
      return false;
  }
  else if(!(checkStudents()) && !(checkParents())){  //checkes whether any student/parent checkboxes selected or not
     alert("Select Student/+Parent to Send Message");
     document.getElementById('studentList').focus();
     return false;
  } 
  else{
     sendMessage(); //sends the message
     return false;
  }  
}

function sendMessage()
{
    alert("OK");
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO send message
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function sendMessage() {
         url = '<?php echo HTTP_LIB_PATH;?>/SendMessage/ajaxSendMessage.php';
         
         
         //determines which student and parents are selected and their studentIds
         formx = document.listFrm; 
         
         var l=formx.parents.length;
         var parent="";  //get studentIds when parent checkboxes are selected
         for(var i=0 ;i < l ; i++){
            if(formx.parents[ i ].checked==true){
                if(parent==""){
                    parent= formx.parents[ i ].value;
                }
               else{
                    parent+="," + formx.parents[ i ].value; 
               } 
            }
         }
         
         var m=formx.students.length;
         var student="";  //get studentIds when student checkboxes are selected
         for(var k=0 ; k < l ; k++){
            if(formx.students[ k ].checked==true){
                if(student==""){
                    student= formx.students[ k ].value;
                }
               else{
                    student+="," + formx.students[ k ].value; 
               } 
            }
         }
         
         //determines message medium
         var msgMedium=((document.getElementById('smsCheck').checked) ? document.getElementById('smsCheck').value: 0)+","+((document.getElementById('emailCheck').checked) ? document.getElementById('emailCheck').value: 0)+","+((document.getElementById('dashBoardCheck').checked) ? document.getElementById('dashBoardCheck').value: 0) ;

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {msgBody: (tinyMCE.get('elm1').getContent()), 
             student: (student),
             parent: (parent), 
             msgMedium: (msgMedium),
             visibleFrom:(document.getElementById('startDate').value),
             visibleTo:(document.getElementById('endDate').value)
             },
             onSuccess: function(transport){
               if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                  showWaitDialog(true);
               }
               else {
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                         messageBox(trim(transport.responseText));
                         /*
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {

                         }
                         else {
                             //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }*/
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
               }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/SendMessage/sendMessageContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
// $History: sendMessage.php $ 
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
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/08/08    Time: 7:29p
//Updated in $/Leap/Source/Interface
//Added comments
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/08/08    Time: 5:48p
//Updated in $/Leap/Source/Interface
//Created sendMessage module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/05/08    Time: 6:15p
//Created in $/Leap/Source/Interface
//Initial Checkin
?>