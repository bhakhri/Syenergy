<?php
//-------------------------------------------------------
// Purpose: To generate feedback functionality 
// Author : Dipanjan Bhattacharjee
// Created on : (19.01.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_ProvideFeedBack');
define('ACCESS','view');

$roleId=$sessionHandler->getSessionVariable('RoleId');

if($roleId==2){//for teacher
  UtilityManager::ifTeacherNotLoggedIn();
}
else if($roleId==3){//for parent
 //not implemented till now
 redirectBrowser(UI_HTTP_PATH.'/Parent/index.php');
}
else if($roleId==4){ //for student
  UtilityManager::ifStudentNotLoggedIn();
}
else{
  redirectBrowser(UI_HTTP_PATH.'/indexHome.php?z=1');
}
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Provide Feedback</title>
<style type="text/css">
.textarea_class
{
    padding: 1px;
    font-family:arial,helvetica,verdana,sans-serif;
    border: 1px solid #CCCCCC; 
    border-width:1px;
    font-size: 12px;
}
</style>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");  
?>
<script language="javascript">
isClick=0;
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

var doubleClicks=0;
function saveFeedbackData(){
    
     if(doubleClicks==1){
         messageBox("Another Request is in progress");
         return false;
     }
     
     if(document.getElementById('labelId').value==''){
         messageBox("<?php echo SELECT_ADV_LABEL_NAME2;?>");
         document.getElementById('labelId').focus();
         return false;
     }
     var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxProvideFeedbackForUsers.php';
     var obj=document.getElementById('dhtmlgoodies_tabView1').getElementsByTagName('INPUT');
     var objLen=obj.length;
     var oString='';
     var qtString='';
     var mString='';
     var catString='';
     var subString1='';
     var subString2='';
     var classString='';
     var groupString='';
     var commentString='';
     var teacherString='';
     var employeeString='';
     var classCommentsIds='';
     var finalIds='';
     
     for(var i=0;i<objLen;i++){
         if (obj[i].type.toUpperCase()=='RADIO' && obj[i].name.indexOf('radio_')>-1 && obj[i].checked==true){
             var ret=obj[i].value.split('_');
             if(oString !=''){
                oString  +=','; 
                qtString +=',';
                mString  +=',';
                subString2 +=',';
                classString +=',';
                teacherString +=',';
                groupString +=',';
             }
             oString  +=ret[2]; 
             qtString +=ret[0];
             mString  +=ret[1];
             subString2 +=ret[4];    
             classString +=ret[3];
             teacherString +=ret[5];
             groupString +=ret[6];
             
             var tSurveyIds = document.getElementById('labelId').value;
             if(ret[2]!=-1) {
                if(finalIds!='') {
                  finalIds +=',';
                }
                 // finalIds will be used to store student complete feedback 
                // SurveyId,Optionid,questionto,mappingto,subId2,classId,teacherId,groupId
	        finalIds += tSurveyIds+'~'+ret[2]+'~'+ret[0]+'~'+ret[1]+'~'+ret[4]+'~'+ret[3]+'~';
		finalIds += ret[5]+'~'+ret[6];
		if(ret[5]=='-1') {
		  finalIds += '~G';
		}
		else {
		  finalIds += '~T'; 
		}
	     }
         }
     }
     
     
     var obj2=document.getElementById('dhtmlgoodies_tabView1').getElementsByTagName('TEXTAREA');
     var objLen2=obj2.length;
     for(var i=0;i<objLen2;i++){
        if (obj2[i].type.toUpperCase()=='TEXTAREA' && obj2[i].name.indexOf('categoryComments')>-1){
            var ret3=obj2[i].id.split('textarea_');
            var ret2=ret3[1].split('_');
            var catId=ret2[0];
            var subId=ret2[1];
            var empId=ret2[2];
            var clsId=ret2[3];
           /* AS COMMENTS ARE NOT MANDATORY
            if(trim(obj2[i].value)==''){
                messageBox("Enter your comments");
                var tLen=tabIdsArray.length;
                for(var x=0;x<tLen;x++){
                   if(tabIdsArray[x]==ret3[1]){
                       break;
                   } 
                }
                try{
                 showTab('dhtmlgoodies_tabView1',x);
                }
                catch(e){}
                obj2[i].focus();
                return false;
            }
           */
           var tLen=tabIdsArray.length;
           for(var x=0;x<tLen;x++){
               if(tabIdsArray[x]==ret3[1]){
                   break;
               } 
           }
           /*
           try{
                 var empFlag=0;
                 var ele=document.getElementById('tabViewdhtmlgoodies_tabView1_'+x).getElementsByTagName('SELECT');
                 var eleLength=ele.length;
                 for(var y=0;y<eleLength;y++){
                    if(ele[y].type.toUpperCase()=='SELECT-ONE' && ele[y].name.indexOf('teacherId')>-1){
                       if(employeeString!=''){
                           employeeString +=',';
                           classCommentsIds +=',';
                       }
                       empFlag=1;
                       employeeString +=ele[y].value;
                       classCommentsIds +=ele[y].options[ele[y].selectedIndex].className.split('_')[1];
                       break;
                    } 
                 }
           }
           catch(e){}
           */
           
           /*if(!empFlag){
              if(employeeString!=''){
                 employeeString +=',';
                 classCommentsIds +=',';
              }
              employeeString +=-1;
              classCommentsIds +=-1;
           }
           */
           if(employeeString!=''){
                 employeeString +=',';
                 classCommentsIds +=',';
           } 
           if(catString!=''){
                catString +=',';
                subString1 +=',';
                commentString +='@!$%*%^~!@';
           }
            catString  += catId;
            subString1 += subId;
            commentString +=trim(obj2[i].value)+' ';
            employeeString +=empId;
            classCommentsIds +=clsId;
         }
       }
       
       /*
       var obj3=document.getElementById('dhtmlgoodies_tabView1').getElementsByTagName('SELECT');
       var objLen3=obj3.length;
       for(var i=0;i<objLen3;i++){
        if (obj3[i].type.toUpperCase()=='SELECT-ONE' && obj3[i].name.indexOf('teacherId')>-1){
            var ret3=obj3[i].id.split('teacherId_');
            var genTabId=ret3[1];
            var ret2=ret3[1].split('_');
            var catsId=ret2[0];
            var subsId=ret2[1];
            var groupId=obj3[i].options[obj3[i].selectedIndex].className.split('_')[0];
            
            if(obj3[i].value==''){
                messageBox("Choose a teacher");
                var tLen=tabIdsArray.length;
                for(var x=0;x<tLen;x++){
                   if(tabIdsArray[x]==genTabId){
                       break;
                   } 
                }
                try{
                 showTab('dhtmlgoodies_tabView1',x);
                }
                catch(e){}
                obj3[i].focus();
                return false;
            }
            if(teacherString!=''){
                teacherString +=',';
            }
            teacherString  += obj3[i].value+'_'+subsId+'_'+groupId;
         }
      }
      */
    /* 
     //as teachers must repeat for subjects 
     if(teacherString!=''){
       var ss=subString2.split(',');
       var cnt=ss.length 
       var teacherArray=new Array();
       for(var k=0;k<cnt;k++){
          teacherArray.push(teacherString); 
       }
       teacherString=teacherArray.join(',');
     }
     */   
     
     if(oString==''){
         messageBox("No options selected");
         return false;
     }
     
     new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 surveyId         : document.getElementById('labelId').value,
                 questionTo       : qtString,
                 mappingTo        : mString,
                 optionTo         : oString,
                 catIds           : catString,
                 catComments      : commentString,
                 subId1           : subString1,
                 subId2           : subString2,
                 classId          : classString,
                 teacherIds       : teacherString,
                 groupIds         : groupString,
                 employeeIds      : employeeString,
                 classCommentsIds : classCommentsIds,
                 finalIds: finalIds
             },
             onCreate: function() {
                 showWaitDialog(true);
                 doubleClicks=1;
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    doubleClicks=0;
                    messageBox(trim(transport.responseText));
                    vanishData();
                    oldLableId='';
                    document.getElementById('labelId').selectedIndex=0;
                    /*
                    var ret=trim(transport.responseText).split('~');
                    if("<?php echo SUCCESS?>"==ret[0]){
                       if(ret.length>1 && ret[1]==0){ 
                        //messageBox("<?php echo ADV_FEEDBACK_DONE;?>.\nBut you have not completed feedback yet");
                        messageBox("Partial Feedback Saved");
                       }
                       else{
                           messageBox("<?php echo ADV_FEEDBACK_DONE;?>");
                       }
                        vanishData();
                        oldLableId='';
                        document.getElementById('labelId').selectedIndex=0;
                    }
                    else{
                        messageBox(ret);
                    }
                    */
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

window.onload=function(){
  
}

var oldLableId=-1;
var catIdArray=new Array();
var subjectIdArray=new Array();
var tabClickedArray=new Array();
var tabIdsArray=new Array();

function fetchMappedCategories(){
         
         var surveyId=document.getElementById('labelId').value;
         
         //to prevent firing of ajax request for same surveyId
         if(oldLableId==surveyId && surveyId!=''){
             return false;
         }
         else{
             oldLableId=surveyId;
         }
         vanishData();
         
         if(surveyId==''){
             messageBox("<?php echo SELECT_ADV_LABEL_NAME2; ?>");
             document.getElementById('labelId').focus();
             return false;
         }
         
         catIdArray=new Array();
         subjectIdArray=new Array();
         tabClickedArray=new Array();
         tabIdsArray=new Array();
         
         if(isClick==1) {
           return false;  
         }
         isClick=1;
         
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetAllocatedCategoriesForUsers.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 surveyId: surveyId
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    isClick=0;
                    if(trim(transport.responseText)==0) {
                        //return false;
                    }
                    if(trim(transport.responseText)=="Feedback Blocked!") {
                        messageBox("<?php echo 'Access Blocked Please Contact Admin.';?>")
                        return false;
                    }

                   var ret=trim(transport.responseText).split('!~!~!~!');
                   
                   if(ret.length>1){
                       document.getElementById('noOfAttemptsTdId').style.display='';
                       document.getElementById('noOfAttemptsTdId').innerHTML='<b>'+ret[1]+'</b>';
                   }
                   
                   var j = eval('('+trim(ret[0])+')');
                   var len=j.length;
                   tabView_maxNumberOfTabs=len; //this variable is defined in js file(for tabs)
                   for(var i=0;i<len;i++){
                           var sName=''; 
                           if(j[i].genSubjectId!=-1){//for mapping with subjects
                               tabGeneratedName=j[i].gensubjectCode;
                           }
                           else{
                               tabGeneratedName=j[i].feedbackCategoryName;
                           }
                           //alert(tabGeneratedName);
                           createNewTab('dhtmlgoodies_tabView1',tabGeneratedName,'','',false);
                           //document.getElementById('tabViewdhtmlgoodies_tabView1_'+i).innerHTML +='Questions For Category :'+j[i].feedbackCategoryName;
                           document.getElementById('tabViewdhtmlgoodies_tabView1_'+i).style.overflow ='auto';
                           tabArray1[i]=tabGeneratedName;
                           tabArray2[i]=false;
                           catIdArray[i]=j[i].feedbackCategoryId;
                           subjectIdArray[i]=j[i].genSubjectId;
                           tabIdsArray[i]=j[i].feedbackCategoryId+'_'+j[i].genSubjectId;
                   }
                  if(len>0){
                   document.getElementById('buttonTrId').style.display='';   
                   for(var i=0;i<len;i++){
                       //fetch questions of each tab
                       fetchMappedQuestions(surveyId,i);
                   }
                   showTab('dhtmlgoodies_tabView1',0);
                   //document.getElementById('dhtmlgoodies_tabView1').style.display='';
                   document.getElementById('dhtmlgoodies_tabView1').style.visibility='visible';
                   
                  }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//this function will fetch questions of different tabs{categories}
function fetchMappedQuestions(surveyId,dynTabId){
   if(surveyId.toString()=='' || dynTabId.toString()==''){
       return false;
   }
   
   //****to prevent repeated ajax calls****
       var len=tabClickedArray.length;
       for(var i=0;i<len;i++){
           if(tabClickedArray[i]==dynTabId){
               return false;
           }
       }
       tabClickedArray[len]=dynTabId;
   //****to prevent repeated ajax calls****
   
   var catId=catIdArray[dynTabId];
   var subjectId=subjectIdArray[dynTabId];

   var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetAllocatedQuestionsForUsers.php';
   new Ajax.Request(url,
   {
     method:'post',
     asynchronous : false,
     parameters: {
         surveyId  : surveyId,
         catId     : catId,
         subjectId : subjectId
     },
     onCreate: function() {
         showWaitDialog(true);
     },
     onSuccess: function(transport){
            hideWaitDialog(true);
            if(trim(transport.responseText)==0) {
                document.getElementById('tabViewdhtmlgoodies_tabView1_'+dynTabId).innerHTML +="<?php echo NO_DATA_FOUND; ?>";
                return false;
            }
            
            document.getElementById('tabViewdhtmlgoodies_tabView1_'+dynTabId).innerHTML +=trim(transport.responseText);
            scanForNotApplicableAnswers(dynTabId)
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
   
}


function scanForNotApplicableAnswers(dynTabId){
    var targetDivId='tabTabdhtmlgoodies_tabView1_'+dynTabId;
    var sourceDivId='tabViewdhtmlgoodies_tabView1_'+dynTabId;
    document.getElementById(targetDivId).style.color='black';
    var ele=document.getElementById(sourceDivId).getElementsByTagName('INPUT');
    var len=ele.length;
    for(var i=0;i<len;i++){
        if(ele[i].type.toUpperCase()=='RADIO' && ele[i].checked==true){
            var val=ele[i].value.split('_');
            if(val[2]==-1){
                document.getElementById(targetDivId).style.color='red';
                try{ 
                 document.getElementById(ele[i].alt).style.color='red';
                }catch(e){}
                //break;
            }
        }
    }
}

function checkIncompleteAnswer(tdId,catId,subId,value){
    var redFlag=0;
    if(value==1){
        document.getElementById(tdId).style.color='black';
    }
    else{
        document.getElementById(tdId).style.color='red';
        redFlag=1;
    }
    var tLen=tabIdsArray.length;
    var genTabId=catId+'_'+subId;
    for(var x=0;x<tLen;x++){
       if(tabIdsArray[x]==genTabId){
           break;
       } 
    }
    try{
       scanForNotApplicableAnswers(x); 
    }
    catch(e){
    }
    
    
}


/****************************************************************/
//Overriding tabClick() function of tab.js
//Dipanjan Bhattacharjee
//Date:14.02.2009
/****************************************************************/
/*
var tabNumber=0;  //Determines the current tab index
function tabClick()
    {
        var idArray = this.id.split('_');
        showTab(this.parentNode.parentNode.id,idArray[idArray.length-1].replace(/[^0-9]/gi,''));
        tabNumber=(idArray[idArray.length-1].replace(/[^0-9]/gi,''));
        alert(tabNumber);
        //fetchMappedQuestions(document.getElementById('labelId').value,tabNumber);
    }
*/

function vanishData(){
   
   //destroy the tabs
   try{ 
     var len=tabArray1.length;
     for(var i=0;i<len;i++){
      deleteTabsFromHere('',i,'dhtmlgoodies_tabView1');
     }
     tabArray2=new Array();
     document.getElementById('dhtmlgoodies_tabView1').style.visibility='hidden';
   }
   catch(e){}
   resetTabIds('dhtmlgoodies_tabView1');
   
  document.getElementById('buttonTrId').style.display='none';
  document.getElementById('noOfAttemptsTdId').style.display='none';
  document.getElementById('noOfAttemptsTdId').innerHTML='';
}

//twiking original delete funtion
function deleteTabsFromHere(tabLabel,tabIndex,parentId){
    if(tabLabel){
        var index = getTabIndexByTitle(tabLabel);
        if(index!=-1){
            deleteTab(false,index[1],index[0]);
        }
        
    }else if(tabIndex>=0){
        if(document.getElementById('tabTab' + parentId + '_' + tabIndex)){
            var obj = document.getElementById('tabTab' + parentId + '_' + tabIndex);
            var id = obj.parentNode.parentNode.id;
            obj.parentNode.removeChild(obj);
            var obj2 = document.getElementById('tabView' + parentId + '_' + tabIndex);
            obj2.parentNode.removeChild(obj2);
            //resetTabIds(parentId);
            activeTabIndex[parentId]=-1;
            showTab(parentId,'0');
        }            
    }
 }


</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FeedbackAdvanced/provideFeedBackContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: provideFeedbackAdv.php $
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 3/05/10    Time: 12:58p
//Updated in $/LeapCC/Interface
//Created "Feedback Comments Report"
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 25/02/10   Time: 17:50
//Updated in $/LeapCC/Interface
//Changed internal logic : Now along with class,subject and employee ,
//group information will also be stored in feedback survey answer table.
//This is needed to place add/edit/delete check in teacher mapping
//module.
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 22/02/10   Time: 12:20
//Updated in $/LeapCC/Interface
//Done modifications :
//1.Showing Yes/No/Partial status for student feedback status in report.
//2.Highlight tabs and questions when NA is selected.
//3.Changed status message when partial feedback is given
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 10/02/10   Time: 12:01
//Updated in $/LeapCC/Interface
//Added Features :
//1.Changed the logic of incomplete feedback algorithm.
//2.Showing category description in top of the tabs instead of bottom.
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 3/02/10    Time: 17:40
//Updated in $/LeapCC/Interface
//Corrected application logic related to survey id check
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 28/01/10   Time: 17:10
//Updated in $/LeapCC/Interface
//Made modifications as instructed by Sachin sir :
//1. Comments are not mandatory
//2. Options should come after questions in a seperate place.
//3. Tab order problem corrected.
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 22/01/10   Time: 17:06
//Updated in $/LeapCC/Interface
//Made UI changes and modified images
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/01/10   Time: 12:16
//Created in $/LeapCC/Interface
//Created "Provide Feedback" Module
?>
