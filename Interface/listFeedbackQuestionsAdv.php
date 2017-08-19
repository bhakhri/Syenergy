<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Gurkeerat Sidhu
// Created on : (13.01.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_Questions');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/FeedBack/initFeedBackQuestionsList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Questions Master(Advanced) </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                new Array('srNo','#','width="2%"','',false), 
                                new Array('feedbackQuestionSetName','Question Set','width="20%"','',true) , 
                                new Array('feedbackQuestion','Question','width="55%"','',true) ,
                                new Array('answerSetName','Answer Set','width="15%"','',true),
                                new Array('action','Action','width="3%"','align="center"',false)
                              );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitFeedBackQuestionsList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFeedBackQuestions';   
editFormName   = 'EditFeedBackQuestions';
winLayerWidth  = 600; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteFeedBackQuestions';
divResultName  = 'results';
page=1; //default page
sortField = 'feedbackQuestionSetName';
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
//Author : Gurkeerat Sidhu
// Created on : (13.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    /*var spt=id.split('~');
    if(spt[1]!=-1){ //if it is used previously
      messageBox("<?php echo NO_EDIT;?>");   
      return false;
    }*/
  
    populateValues(id,dv,w,h);   
    
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Gurkeerat Sidhu
// Created on : (13.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
                                new Array("questionSet","<?php echo ADV_SELECT_QUESTION_SET;?>")
                                //,
                                //new Array("answerSet","<?php echo ADV_SELECT_ANSWER_SET;?>"),
                                //new Array("questionTxt","<?php echo ADV_ENTER_FEEDBACK_QUESTION;?>")
                                
                               );

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
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<5 && fieldsArray[i][0]=='questionTxt' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ADV_FEEDBACK_QUESTIONS_NAME_LENGTH;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
        }
     
    }
    if(act=='Add') {
        addFeedBackQuestions();
        return false;
    }
    else if(act=='Edit') {
        editFeedBackQuestions();
        return false;
    }
}

//for deleting a row from the table 
    function deleteRow(value){
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody');
      
      var tr=document.getElementById('row'+rval[0]);
      tbody1.removeChild(tr);
      reCalculate();
      
      if(isMozilla){
          if((tbody1.childNodes.length-2)==0){
              resourceAddCnt=0;
          }
      }
      else{
          if((tbody1.childNodes.length-1)==0){
              resourceAddCnt=0;
          }
      }
    } 
    
var resourceAddCnt=0;
    // check browser
     var isMozilla = (document.all) ? 0 : 1;
//to add one row at the end of the list
    function addOneRow(cnt) {
        //set value true to check that the records were retrieved but not posted bcos user marked them deleted
        document.getElementById('deleteFlag').value=true;
              
        if(cnt=='')
       cnt=1;  
         if(isMozilla){
             if(document.getElementById('anyidBody').childNodes.length <= 3){
                resourceAddCnt=0; 
             }       
        }
        else{
             if(document.getElementById('anyidBody').childNodes.length <= 1){
               resourceAddCnt=0;  
             }       
        } 
        resourceAddCnt++; 
        
        createRows(resourceAddCnt,cnt);
    }

function setAnswerSetValue(){
     
     var ele=document.getElementById('tableDiv').getElementsByTagName("SELECT");
     var len=ele.length;
     //alert(len);
     for(var i=0;i<len;i++){
       ele[i].value = document.AddFeedBackQuestions.answerSetHidden.value;  
     }
}    
    
var bgclass='';
    
//function createRows(start,rowCnt,optionData,sectionData,roomData){
function createRows(start,rowCnt){
       // alert(start+'  '+rowCnt);
     var tbl=document.getElementById('anyid');
     var tbody = document.getElementById('anyidBody');
     
                         
     for(var i=0;i<rowCnt;i++){
      var tr=document.createElement('tr');
      tr.setAttribute('id','row'+parseInt(start+i,10));
      
      var cell1=document.createElement('td');
      var cell2=document.createElement('td'); 
      var cell3=document.createElement('td');
      var cell4=document.createElement('td');
      
      cell1.setAttribute('align','left');
      cell1.name='srNo';
      cell2.setAttribute('align','left'); 
      cell3.setAttribute('align','left');
      cell4.setAttribute('align','center');
      
      
      if(start==0){
        var txt0=document.createTextNode(start+i+1);
      }
      else{
        var txt0=document.createTextNode(start+i);
      }
      var txt1=document.createElement('textarea');
      var txt2=document.createElement('select');
      var txt3=document.createElement('a');

      
      
      txt1.setAttribute('id','questionTxt'+parseInt(start+i,10));
      txt1.setAttribute('name','questionTxt[]'); 
      //txt1.className='htmlElement';  
      txt1.setAttribute('cols','40');
      txt1.setAttribute('rows','2');
      txt1.setAttribute('maxlenth','5000');
      
      txt2.setAttribute('id','answerSet'+parseInt(start+i,10));
      txt2.setAttribute('name','answerSet[]');
      txt2.className='inputbox';
      txt2.setAttribute('style','width:190px;');
   
      txt3.setAttribute('id','rd');
      txt3.className='htmlElement';  
      txt3.setAttribute('title','Delete');       
      txt3.innerHTML='X';
      txt3.style.cursor='pointer';
      txt3.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff
      
      
      cell1.appendChild(txt0);
      cell2.appendChild(txt1);
      cell3.appendChild(txt2);
      cell4.appendChild(txt3);
      
             
      tr.appendChild(cell1);
      tr.appendChild(cell2);
      tr.appendChild(cell3);
      tr.appendChild(cell4);
      
      
      bgclass=(bgclass=='row0'? 'row1' : 'row0');
      tr.className=bgclass;
      
      tbody.appendChild(tr); 
      var len= document.AddFeedBackQuestions.answerSetHidden.options.length;
      var t=document.AddFeedBackQuestions.answerSetHidden;
      if(len>0) {
        var tt='answerSet'+parseInt(start+i,10) ;
        eval('document.AddFeedBackQuestions.'+tt+'.length = null');
        //alert(eval("document.AddFeedBackQuestions.tt.length"));
        for(k=0;k<len;k++) { 
            addOption(eval('document.AddFeedBackQuestions.'+tt), t.options[k].value,  t.options[k].text);
        }
        var ele=document.getElementById('tableDiv').getElementsByTagName("SELECT");
        var len2=ele.length;
        if(len2>0){
           var temp="";
                for(var i=0;i<len2;i++){
                    if(ele[i].id!=tt){ 
                        temp = ele[i].value;
                    }
                    else{
                        break;
                    }
               }
           var tt3 = eval('document.AddFeedBackQuestions.'+tt);
           tt3.value = eval(temp);   
       }
     }
  } 
  tbl.appendChild(tbody); 
  reCalculate();  
}  

//to clean up table rows
    function cleanUpTable(){
       var tbody = document.getElementById('anyidBody');
       for(var k=0;k<=resourceAddCnt;k++){
             try{
              tbody.removeChild(document.getElementById('row'+k));
             }
             catch(e){
                 //alert(k);  // to take care of deletion problem
             }
          }  
    }
    
    
//to recalculate Serial no.
function reCalculate(){
  var a =document.getElementById('tableDiv').getElementsByTagName("td");
  var l=a.length;
  var j=1;
  for(var i=0;i<l;i++){     
    if(a[i].name=='srNo'){
    bgclass=(bgclass=='row0'? 'row1' : 'row0');
    a[i].parentNode.className=bgclass;
      a[i].innerHTML=j;
      j++;
    }
  }
  //resourceAddCnt=j-1;
}      

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW FeedBackQuestions
//
//Author : Gurkeerat Sidhu
// Created on : (13.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addFeedBackQuestions() {
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitFeedBackQuestionsAdd.php';
         
         var answerSetString='';
         var questionString='';
         var ele=document.getElementById('tableDiv').getElementsByTagName("TEXTAREA");
         var len=ele.length;
         for(var i=0;i<len;i++){
             if(ele[i].type.toUpperCase()=='TEXTAREA' && ele[i].name=='questionTxt[]'){
                 if(trim(ele[i].value)==''){
                     alert('Enter Question');
                     ele[i].focus();
                     return false;
                 }
                 if(questionString!=''){
                     questionString +='!@~!~!@~!@~!';
                 }
                 questionString += ' '+trim(ele[i].value); 
             }
         }
         
         var ele=document.getElementById('tableDiv').getElementsByTagName("SELECT");
         var len=ele.length;
         if(len == 0)
         {
         messageBox("AnswerSet or Questions can not be empty, Please click on 'Add More' to enter required data");
         return false;     
         }
         for(var i=0;i<len;i++){
             if(ele[i].type.toUpperCase()=='SELECT-ONE'){
                 if(ele[i].value==''){
                     alert('Select Answer Set');
                     ele[i].focus();
                     return false;
                 }
                 if(answerSetString!=''){
                     answerSetString +=',';
                 }
                 answerSetString +=ele[i].value; 
             }
         }
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 questionSet:    (document.AddFeedBackQuestions.questionSet.value), 
                 answerSets:     (answerSetString), 
                 questionTxts:    questionString
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
                             hiddenFloatingDiv('AddFeedBackQuestions');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else if("<?php echo ADV_FEEDBACK_QUESTIONS_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ADV_FEEDBACK_QUESTIONS_ALREADY_EXIST ;?>"); 
                         document.AddFeedBackQuestions.questionTxt.focus();
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A FeedBackQuestions
//  id=FeedBackQuestionsId
//Author : Gurkeerat Sidhu
// Created on : (13.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteFeedBackQuestions(id) {
        /* var spt=id.split('~');
         if(spt[1]!=-1){ //if it used previously
          messageBox("<?php echo NO_DELETE;?>");   
          return false;
         }*/
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitFeedBackQuestionsDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feedbackQuestionId: id},
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

//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "addFeedBackQuestions" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (13.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------
function blankValues() {
   document.AddFeedBackQuestions.questionSet.value = '';
   document.AddFeedBackQuestions.answerSetHidden.value = '';
   //document.AddFeedBackQuestions.questionTxt.value = '';
   cleanUpTable();
   addOneRow(1);
   document.getElementById('tableDiv').style.overflow='hidden';
   document.getElementById('tableDiv').style.overflow='auto';
   document.AddFeedBackQuestions.questionSet.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A FeedBackQuestions
//
//Author : Gurkeerat Sidhu
// Created on : (13.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editFeedBackQuestions() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitFeedBackQuestionsEdit.php';
         
         //var spt=document.EditFeedBackQuestions.feedbackQuestionId.value.split('~'); //extracting questionId
    
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 feedbackQuestionId: (document.EditFeedBackQuestions.feedbackQuestionId.value), 
                 questionSet:        (document.EditFeedBackQuestions.questionSet.value), 
                 answerSet:      (document.EditFeedBackQuestions.answerSet.value), 
                 questionTxt:   trim(document.EditFeedBackQuestions.questionTxt.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditFeedBackQuestions');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo ADV_FEEDBACK_QUESTIONS_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo ADV_FEEDBACK_QUESTIONS_ALREADY_EXIST ;?>"); 
                       document.EditFeedBackQuestions.questionTxt.focus();
                    }
                    else if("<?php echo ADV_SELECT_ANSWER_SET;?>" == trim(transport.responseText)){
                       messageBox("<?php echo ADV_SELECT_ANSWER_SET ;?>"); 
                       document.EditFeedBackQuestions.answerSet.focus();
                    }
                    else if("<?php echo ADV_ENTER_FEEDBACK_QUESTION;?>" == trim(transport.responseText)){
                       messageBox("<?php echo ADV_ENTER_FEEDBACK_QUESTION ;?>"); 
                       document.EditFeedBackQuestions.questionTxt.focus();
                    }
                     else {
                        messageBox(trim(transport.responseText));                         
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editFeedBackQuestions" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (13.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id,dv,w,h) {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetFeedBackQuestionsValues.php';
         new Ajax.Request(url,
           {
             method:'post', 
                 asynchronous:false,
             parameters: {feedbackQuestionId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        //hiddenFloatingDiv('EditFeedBackQuestions');
                        messageBox("<?php echo FEEDBACK_QUESTIONS_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }

                  else if("<?php echo 'Data could not be edited due to records existing in linked tables';?>" == trim(transport.responseText)) {
                        //hiddenFloatingDiv('EditFeedBackQuestions');
                        messageBox(trim(transport.responseText));
                        return false;
                   }
                   else {
                   displayWindow(dv,w,h);
                   j = eval('('+transport.responseText+')');
                   document.EditFeedBackQuestions.questionSet.value        = j.feedbackQuestionSetId;
                   document.EditFeedBackQuestions.answerSet.value          = j.answerSetId;
                   document.EditFeedBackQuestions.questionTxt.value        = j.feedbackQuestion;
                   document.EditFeedBackQuestions.feedbackQuestionId.value = j.feedbackQuestionId;
                   document.EditFeedBackQuestions.questionSet.focus();
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


/* function to print FeedbackAdvanced Questions report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/feedBackAdvQuestionsReportPrint.php?'+qstr;
    window.open(path,"FeedBackAdvQuestionsReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='feedBackAdvQuestionsReportCSV.php?'+qstr;
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FeedbackAdvanced/listFeedBackAdvQuestionsContents.php");
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
// $History: listFeedbackQuestionsAdv.php $ 
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 20/02/10   Time: 12:25
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//0002923,0002322,0002921,0002920,0002919,
//0002918,0002917,0002916,0002915,0002914,
//0002912,0002911,0002913
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/02/10    Time: 5:31p
//Updated in $/LeapCC/Interface
//Updated code to add multiple questions at the same time
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/21/10    Time: 5:37p
//Created in $/LeapCC/Interface
//Created file under question master in feedback module
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/21/10    Time: 11:32a
//Created in $/LeapCC/Interface
//

?>