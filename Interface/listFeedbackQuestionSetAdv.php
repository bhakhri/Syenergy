<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF FEED BACK Question Sets(ADV)
// Author : Dipanjan Bhattacharjee
// Created on : (12.01.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_QuestionSet');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Question Set Master(Advanced) </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                      new Array('srNo','#','width="1%"','',false),
                      new Array('feedbackQuestionSetName','Question Set','width="90%"','',true),
                      new Array('actionString','Action','width="3%"','align="center"',false)
                     );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxAdvFeedBackQuestionSetList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddQuestionSet';
editFormName   = 'EditQuestionSet';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteQuestionSet';
divResultName  = 'results';
page=1; //default page
sortField = 'feedbackQuestionSetName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button



//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function editWindow(id) {
    displayWindow('AddQuestionSet',315,250);
    document.getElementById('divHeaderId1').innerHTML='&nbsp;Edit Feedback Question Set';
    populateValues(id);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {

    var fieldsArray = new Array(
        new Array("setName","<?php echo ENTER_ADV_QUESTION_SET_NAME;?>")
    );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
      if(document.getElementById('setId').value!=''){
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
           if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<1 && fieldsArray[i][0]=='setName' ) {
                messageBox("<?php echo ADV_QUESTION_SET_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
        }
      }
    }
    if(document.getElementById('setId').value == '') {
        addQuestionSet();
        return false;
    }
    else if(document.getElementById('setId').value != '') {
        editQuestionSet();
        return false;
    }

}


var duplicateArray=new Array();

function checkDuplicateValues(val){
    var cnt=duplicateArray.length;
    for(var i=0;i<cnt;i++){
       if(duplicateArray[i]==val){
           return 1;
       }
    }
    duplicateArray.push(val);
    return 0;
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addQuestionSet() {
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxAdvFeedBackQuestionSetOperations.php';

         var ele=document.getElementById('tableDiv').getElementsByTagName("INPUT");
         var len=ele.length;
         if(len == 0)
         {
         messageBox("Please create atleast one question set");
         return false;
         }
         duplicateArray=new Array();
         for(var i=0;i<len;i++){
             if(ele[i].type.toUpperCase()=='TEXT' && ele[i].name=='setName[]'){
                 if(trim(ele[i].value)==''){
                     messageBox("<?php echo ENTER_ADV_QUESTION_SET_NAME;?>");
                     ele[i].focus();
                     return false;
                 }

                 if(trim(ele[i].value).length<1){
                     messageBox("<?php echo ADV_QUESTION_SET_NAME_LENGTH;?>");
                     ele[i].focus();
                     return false;
                 }
                 //duplicate check
                 if(checkDuplicateValues(trim(ele[i].value))==1){
                     messageBox("Duplicate questions set name");
                     ele[i].focus();
                     return false;
                 }
             }
         }

         var pars=generateQueryString('AddQuestionSet')+'&modeName=1';
         new Ajax.Request(url,
           {
             method:'post',
             /*
             parameters: {
                 modeName    : 1,
                 setName     : trim(document.AddQuestionSet.setName.value)
             },
             */
             parameters:pars,
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
                             hiddenFloatingDiv('AddQuestionSet');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     }
                     else if("<?php echo ADV_QUESTION_SET_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ADV_QUESTION_SET_ALREADY_EXIST ;?>");
                         //document.AddQuestionSet.setName.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        //document.AddQuestionSet.setName.focus();
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A NEW CITY
//id=cityId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteQuestionSet(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {

         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxAdvFeedBackQuestionSetOperations.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 modeName : 3,
                 setId    : id
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
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddQuestionSet.reset();
   document.AddQuestionSet.setId.value='';
   document.getElementById('divHeaderId1').innerHTML='&nbsp;Add Feedback Question Set';
   cleanUpTable();
   addOneRow(1);
   //document.AddQuestionSet.setName.focus();
  try{
   document.getElementById('setName1').focus();
  }
  catch(e){}
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editQuestionSet() {
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxAdvFeedBackQuestionSetOperations.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                  modeName    : 2,
                  setId       : document.AddQuestionSet.setId.value,
                  setName     : trim(document.AddQuestionSet.setName.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);

                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('AddQuestionSet');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else if("<?php echo ADV_QUESTION_SET_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ADV_QUESTION_SET_ALREADY_EXIST ;?>");
                         document.AddQuestionSet.setName.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.AddQuestionSet.setName.focus();
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO POPULATE "editCity" DIV
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function populateValues(id) {
         document.getElementById('multiRow').style.display='none';
         document.getElementById('singleRow').style.display='';
         document.getElementById('addMoreRow').style.display='none';
         document.AddQuestionSet.reset();
         document.AddQuestionSet.setId.value='';
         url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetFeedBackQuestionSetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                  setId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('AddQuestionSet');
                        messageBox("<?php echo ADV_QUESTION_SET_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        //return false;
                   }
                   var j = eval('('+trim(transport.responseText)+')');

                   document.AddQuestionSet.setId.value=j.feedbackQuestionSetId;
                   document.AddQuestionSet.setName.value=j.feedbackQuestionSetName;

                   document.AddQuestionSet.setName.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
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

      cell1.setAttribute('align','left');
      cell1.name='srNo';
      cell2.setAttribute('align','left');
      cell3.setAttribute('align','center');


      if(start==0){
        var txt0=document.createTextNode(start+i+1);
      }
      else{
        var txt0=document.createTextNode(start+i);
      }
      var txt1=document.createElement('input');
      var txt2=document.createElement('a');



      txt1.setAttribute('id','setName'+parseInt(start+i,10));
      txt1.setAttribute('name','setName[]');
      txt1.setAttribute('type','text');
      txt1.className='inputbox';
      txt1.setAttribute('style','width:190px;');
		txt1.maxLength=30;

      txt2.setAttribute('id','rd');
      txt2.className='htmlElement';
      txt2.setAttribute('title','Delete');
      txt2.innerHTML='X';
      txt2.style.cursor='pointer';
      txt2.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff


      cell1.appendChild(txt0);
      cell2.appendChild(txt1);
      cell3.appendChild(txt2);

      tr.appendChild(cell1);
      tr.appendChild(cell2);
      tr.appendChild(cell3);

      bgclass=(bgclass=='row0'? 'row1' : 'row0');
      tr.className=bgclass;

      tbody.appendChild(tr);
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


/* function to print Question Set report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/questionSetReportPrint.php?'+qstr;
    window.open(path,"QuestionSetReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='questionSetReportCSV.php?'+qstr;
}

</script>
</head>
<body>
	<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/FeedbackAdvanced/listFeedBackQuestionSetContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php
// $History: listFeedbackQuestionSetAdv.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 19/02/10   Time: 14:22
//Updated in $/LeapCC/Interface
//Done Bug fixing.
//Bug ids---
//0002910,0002909,0002907,
//0002906,0002904,0002908,
//0002905
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 18/02/10   Time: 18:30
//Updated in $/LeapCC/Interface
//Modified UI design: Now users can add multiple records at a time.
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/06/10    Time: 7:36p
//Updated in $/LeapCC/Interface
//Updated folder name
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 1/21/10    Time: 5:22p
//Updated in $/LeapCC/Interface
//Updated breadcrumbs and titles
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/01/10   Time: 12:30
//Created in $/LeapCC/Interface
//Created  "Question Set Master"  module
?>