<?php
//---------------------------------------------------------------------------
//  THIS FILE used for showing list of messages to parents and students
//
// Author : Dipanjan Bhattacharjee
// Created on : (22.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ListTeacherCommentMaster');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Teacher Comment </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false),
 new Array('subject','Subject','width="20%"','',true), 
 new Array('comments','Comment','width="40%"','',true) , 
 new Array('postedOn','Dated','width="10%"','align="center"',true), 
 new Array('details','Action','width="10%"','align="right"',false)
 );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
//recordsPerPage = 100;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxTeacherCommentList.php';
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
sortField = 'comments';
sortOrderBy = 'ASC';

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
    document.getElementById('results2').style.display='none';
    populateValues(id);
    displayFloatingDiv(dv,'', w, h, 150, 100);  
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO hide "showList" div
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Created on : (21.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------

var serverDate="<?php echo date('Y-m-d');?>";
function getData(){
    
    if(!dateDifference(document.getElementById('forDate').value,serverDate,'-')){
       messageBox("Date cannot be greater than current date");
       document.getElementById('forDate').focus();
       return false;
    }
    
    if(trim(document.getElementById('studentRollNo').value)!="")
    {
      resetSendReqParam(1); //params reset to default values        
      searchFormName = 'searchForm2'; 
      sendReq(listURL,divResultName,searchFormName,'',false);  
      hide_div('showList',1);

    }
   else if((document.getElementById('class').value != "")  && (document.getElementById('group').value != "") ){
        resetSendReqParam(1); //params reset to default values        
        searchFormName = 'searchForm2'; 
        sendReq(listURL,divResultName,searchFormName,'');
        hide_div('showList',1);
    }
   else{
       messageBox("<?php echo TEACHER_COMMENT_SELECT_STUDENT_LIST; ?>");
       document.getElementById('class').focus();
   } 
    
}

//----------------------------------------------------------------------------------------------------------------
//Pupose:Delete rollNo from studentRollNo field upon changing class,subject or group
//Author: Dipanjan Bhattacharjee
//Date : 19.07.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function deleteRollNo(){
    document.getElementById('studentRollNo').value="";
}

//----------------------------------------------------------------------------------------------------------------
//Pupose:populate comment div
//Author: Dipanjan Bhattacharjee
//Date : 19.07.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function populateValues(id){
     url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetComments.php';
     document.frmComment.msgDate.value = "";
     document.frmComment.msgSubject.value ="";
     //document.frmComment.msgBody.value ="";
     document.getElementById('msgBody').innerHTML="";
     
     document.getElementById('results2').innerHTML="";
     

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {commentId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('CommentDiv');
                        messageBox("<?php echo COMMENT_NOT_EXISTS; ?>");
                    }
                   var j = eval('('+trim(transport.responseText)+')');
                   document.frmComment.msgDate.value=j.postedOn;
                   
                   /*
                   var dt=j.postedOn.split(' ');
                   if(dt.length >1){
                     document.frmComment.msgDate.value = customParseDate(dt[0],"-")+" "+dt[1];
                   }
                   else{
                     document.frmComment.msgDate.value = customParseDate(dt[0],"-");
                   }
                   */
                   
                   document.frmComment.msgSubject.value = j.subject;
                   //document.frmComment.msgBody.value = j.comments;
                   
                   document.getElementById('msgBody').innerHTML = j.comments;
                   
                   document.frmComment.commentId.value = j.commentId;  
                   
                   resetSendReqParam(2); //it will reset when user clicks on search or submit button
                   document.frmComment.searchbox.value="";
                   sendReq(listURL,divResultName,searchFormName,'',false);
                   document.getElementById('results2').style.display='block';
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
    
}

//----------------------------------------------------------------------------------------------------------------
//Pupose:To change send req funct's parameters dynamically
//Author: Dipanjan Bhattacharjee
//Date : 13.08.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function resetSendReqParam(mode){
    if(mode==1){//default
     tableHeadArray = new Array(new Array('srNo','#','width="5%"','',false),new Array('subject','Subject','width="20%"','',true),new Array('comments','Comment','width="40%"','',true) , new Array('postedOn','Dated','width="10%"','align="center"',true),  new Array('details','Details','width="10%"','align="right"',false) );
     listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxTeacherCommentList.php';
     searchFormName = 'searchForm';
     divResultName  = 'results';
     page=1; //default page
     searchFormName = 'searchForm'; 
     sortField = 'comments';
     sortOrderBy = 'ASC';
     recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
    }
   else{
     tableHeadArray = new Array(new Array('srNo','#','width="1%"','',false),
     new Array('studentName','Name','width="15%"','align="left"',true) , 
     new Array('rollNo','Roll No.','width="10%"','align="left"',true) ,  
     new Array('universityRollNo','Univ. Roll No.','width="14%"','align="left"',true) ,  
     new Array('sms','SMS','width="5%"','',true), 
     new Array('email','E-mail','width="10%"','',true), 
     new Array('dashboard','Dashboard','width="5%"','',true),
     new Array('toStudent','Student','width="5%"','',true),
     new Array('toParent','Parent','width="5%"','',true) 
      );
     listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetStudentCommentList.php';
     searchFormName = 'frmComment'; 
     divResultName  = 'results2';
     page=1; //default page
     sortField = 'studentName';
     sortOrderBy = 'ASC'; 
     recordsPerPage = <?php echo RECORDS_PER_PAGE_TEACHER;?>;
   } 
}

var selectedDate="<?php echo date('Y-m-d')?>";
function refreshDropDowns(){
    if(selectedDate!=document.getElementById('forDate').value){
       selectedDate1=trim(document.getElementById('forDate').value);
       getClassData();
       document.getElementById('group').options.length=1;
       document.getElementById('showList').style.display='none';
    }
}

//this function fetches class data based upon user selected dates
function getClassData(){
  var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAdjustedClass.php';
  var classEle=document.getElementById('class');
  classEle.options.length=1;

 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 startDate : document.getElementById('forDate').value,
                 endDate   : document.getElementById('forDate').value
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    var j = eval('('+transport.responseText+')'); 
                    for(var c=0;c<j.length;c++){
                       var objOption = new Option(j[c].className,j[c].classId);
                       classEle.options.add(objOption);
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh 
// Created on : (12.03.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function groupPopulate() {
   //var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGroupPopulate.php';
   var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAdjustedGroupForComments.php';
   document.searchForm2.group.options.length=1;
   /*
   var objOption = new Option("Select Group","");
   document.searchForm2.group.options.add(objOption); 
   */
   
   
   if(document.getElementById('class').value==""){
       return false;
   }
   

 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 classId  : document.getElementById('class').value,
                 startDate : document.getElementById('forDate').value,
                 endDate   : document.getElementById('forDate').value
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    var j = eval('('+transport.responseText+')'); 

                     var r=1;
                     var tname='';

                     for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].groupName,j[c].groupId);
                         document.searchForm2.group.options.add(objOption);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}
 

//overriding default function
function hiddenFloatingDiv(divId){
    resetSendReqParam(1);
    searchFormName = 'searchForm2';
    document.getElementById(divId).style.visibility='hidden';
    document.getElementById('modalPage').style.display = "none";
    makeMenuDisable('qm0',false);
    DivID = "";
}
window.onload=function(){
    document.getElementById('class').focus();
    document.getElementById('calImg').onblur=refreshDropDowns
    var roll = document.getElementById("studentRollNo");
    autoSuggest(roll);
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listTeacherCommentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
// $History: listTeacherComment.php $ 
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 17/02/10   Time: 12:20
//Updated in $/LeapCC/Interface/Teacher
//Added the feature :
//Display Teacher Comments:By Default it should show the list of message
//sent by respective employee. after that search filter can be applied
//which are currently mandatory
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 28/01/10   Time: 11:31
//Updated in $/LeapCC/Interface/Teacher
//Added "Univ. Roll No." column in student list display
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface/Teacher
//added code for autosuggest functionality
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 20/10/09   Time: 18:09
//Updated in $/LeapCC/Interface/Teacher
//Added code for "Time table adjustment"
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Interface/Teacher
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Teacher
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 9/25/08    Time: 6:40p
//Updated in $/Leap/Source/Interface/Teacher
//Corrected date format problem
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 9/09/08    Time: 1:58p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 9/01/08    Time: 5:36p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Interface/Teacher
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/29/08    Time: 6:02p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/22/08    Time: 3:27p
//Updated in $/Leap/Source/Interface/Teacher
//Added Standard Messages
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/13/08    Time: 2:38p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/12/08    Time: 12:20p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/09/08    Time: 2:42p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/25/08    Time: 11:52a
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/24/08    Time: 7:57p
//Updated in $/Leap/Source/Interface/Teacher
//Changed header.php and footer.php paths to the original paths
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/22/08    Time: 6:56p
//Created in $/Leap/Source/Interface/Teacher
//Initial Checkin
?>