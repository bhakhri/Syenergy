<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF FeedBackGrades
// Author : Gurkeerat Sidhu
// Created on : (12.01.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_Options');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/FeedBack/initFeedBackGradeList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>:  Answer Set Options </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
    new Array('srNo','#','width="2%"','',false),
    new Array('answerSetName','Answer Set','width="28%"','',true), 
    new Array('optionLabel','Option Text','width="28%"','',true) ,
    new Array('optionPoints','Option Weight','width="28%"','align="right"',true), 
    new Array('printOrder','Print Order','width="28%"','align="right"',true), 
    new Array('action','Action','width="3%"','align="center"',false)
  );
  

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitFeedbackAdvOptionsList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFeedbackOptions';   
editFormName   = 'EditFeedbackOptions';
winLayerWidth  = 355; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteFeedbackOptions';
divResultName  = 'results';
page=1; //default page
sortField = 'answerSetName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

var dtArray=new Array(); 

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Gurkeerat Sidhu
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    //displayWindow(dv,w,h);
    populateValues(id,dv,w,h);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Gurkeerat Sidhu
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
	
   
        var ele=document.getElementById('tableDiv').getElementsByTagName("INPUT");
        var len=ele.length;
		
        if(act=='Add') {   
		  if(document.AddFeedbackOptions.answerSet.value=='') {
		     messageBox("<?php echo SELECT_ANSWERSET; ?>");	
		     document.AddFeedbackOptions.answerSet.focus();
  		     return false;	  
		   }
        }
        else {
          if(document.EditFeedbackOptions.answerSet.value=='') {
             messageBox("<?php echo SELECT_ANSWERSET; ?>");    
             document.EditFeedbackOptions.answerSet.focus();
             return false;      
           }
        }
		
		if(act=='Add') {
			  dtArray.splice(0,dtArray.length); //empty the array
			  for(var i=0;i<len;i++) {
				 if(ele[i].type.toUpperCase()=='TEXT' && ele[i].name=='optionLabel[]'){
					 if(trim(ele[i].value)==''){
						 messageBox("<?php echo ENTER_OPTION_LABEL;?>");
						 ele[i].className='inputboxRed'; 
						 ele[i].focus();
						 return false;
					 }
					 if(checkDuplicate(ele[i].value)==0) {
						messageBox ("You can not enter same option text under one answer set");  
						ele[i].className='inputboxRed'; 
						ele[i].focus(); 
						return false;
					}
			   }
			 }

			 dtArray.splice(0,dtArray.length); //empty the array
			 for(var i=0;i<len;i++){
				 if(ele[i].type.toUpperCase()=='TEXT' && ele[i].name=='optionPoints[]'){
					 if(ele[i].value==''){
						 messageBox("<?php echo ENTER_OPTION_VALUE;?>");
						 ele[i].className='inputboxRed'; 
						 ele[i].focus();
						 return false;
					 }
					 if(!isDecimal(trim(ele[i].value))) {
						messageBox("<?php echo ENTER_OPTION_LABEL_TO_NUM; ?>");
						ele[i].className='inputboxRed'; 
						ele[i].focus();
						return false;
					}
					if(ele[i].value<0 || ele[i].value>1000) {
						messageBox("<?php echo OPTION_VALUE_GREATER_ZERO; ?>");
						ele[i].className='inputboxRed'; 
						ele[i].focus();
						return false;
						break;
				   }
				   if(checkDuplicate(ele[i].value)==0) {
						messageBox ("You can not enter same option weight under one answer set");  
						ele[i].className='inputboxRed'; 
						ele[i].focus(); 
						return false;
				   } 
			   }
			 }
         
			 dtArray.splice(0,dtArray.length); //empty the array
			 for(var i=0;i<len;i++){
				 if(ele[i].type.toUpperCase()=='TEXT' && ele[i].name=='printOrder[]'){
					 if(ele[i].value==''){
						 messageBox("<?php echo ENTER_ADV_PRINT_ORDER;?>");
						 ele[i].className='inputboxRed'; 
						 ele[i].focus();
						 return false;
					 }
					 if(!isNumeric(trim(ele[i].value)) ) {
						messageBox("<?php echo NUMERIC_PRINT_ORDER_VALIDATIONS; ?>");
						ele[i].className='inputboxRed'; 
						ele[i].focus();
						return false;
					 }
			  
					if( ele[i].value<1 || ele[i].value>100) {
						messageBox("<?php echo ANSWER_OPTIONS_PRINT_ORDER_VALIDATIONS; ?>");
						ele[i].className='inputboxRed'; 
						ele[i].focus();
						return false;
					} 
					if(checkDuplicate(ele[i].value)==0) {
						messageBox ("You can not enter same print order for different options");  
						ele[i].className='inputboxRed'; 
						ele[i].focus(); 
						return false;
					} 
				 }
			 }
	}


   
    if(act=='Add') {
        addFeedbackOptions();
        return false;
    }
    else if(act=='Edit') {
        editFeedbackOptions();
        return false;
    }
}


function checkDuplicate(value) {
    var i= dtArray.length;
    var fl=1;
    for(var k=0;k<i;k++){
      if(dtArray[k]==value){
        fl=0;
        break;
      }  
    }
    if(fl==1){
      dtArray.push(value);
    } 
    return fl;
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
      var cell4=document.createElement('td');
      var cell5=document.createElement('td');
      
      cell1.setAttribute('align','left');
      cell1.name='srNo';
      cell2.setAttribute('align','left'); 
      cell3.setAttribute('align','left');
      cell4.setAttribute('align','left');
      cell5.setAttribute('align','center');
      
      
      if(start==0){
        var txt0=document.createTextNode(start+i+1);
      }
      else{
        var txt0=document.createTextNode(start+i);
      }

      var idStore=document.createElement('input');   

      var txt1=document.createElement('input');
      var txt2=document.createElement('input');
      var txt3=document.createElement('input');
      var txt4=document.createElement('a');

      

      idStore.setAttribute('type','hidden'); 
	  idStore.setAttribute('name','idNos[]');
	  idStore.setAttribute('id','idNos'+parseInt(start+i,10));  
	  idStore.setAttribute('value',parseInt(start+i,10));
      
      txt1.setAttribute('id','optionLabel'+parseInt(start+i,10));
      txt1.setAttribute('name','optionLabel[]'); 
      txt1.setAttribute('type','text');
      txt1.className='inputbox'; 
      txt1.setAttribute('style','width:190px;'); 
      //txt1.setAttribute('cols','40');
      //txt1.setAttribute('rows','2');
      txt1.setAttribute('maxlenth','100');
      
      txt2.setAttribute('id','optionPoints'+parseInt(start+i,10));
      txt2.setAttribute('name','optionPoints[]');
      txt2.setAttribute('type','text');
      txt2.className='inputbox';
      txt2.setAttribute('style','width:60px;');
      txt2.setAttribute('maxlenth','5');
      
      txt3.setAttribute('id','printOrder'+parseInt(start+i,10));
      txt3.setAttribute('name','printOrder[]');
      txt3.setAttribute('type','text');
      txt3.className='inputbox';
      txt3.setAttribute('style','width:60px;');
      txt3.setAttribute('maxlenth','5');
   
      txt4.setAttribute('id','rd');
      txt4.className='htmlElement';  
      txt4.setAttribute('title','Delete');       
      txt4.innerHTML='X';
      txt4.style.cursor='pointer';
      txt4.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff
      
      
      cell1.appendChild(txt0);
      cell2.appendChild(txt1);
      cell3.appendChild(txt2);
      cell4.appendChild(txt3);
      cell5.appendChild(txt4);
	  cell5.appendChild(idStore);        

             
      tr.appendChild(cell1);
      tr.appendChild(cell2);
      tr.appendChild(cell3);
      tr.appendChild(cell4);

      tr.appendChild(cell5);
      
      
      bgclass=(bgclass=='row0'? 'row1' : 'row0');
      tr.className=bgclass;
      
      tbody.appendChild(tr); 
      /*var len= document.AddFeedBackQuestions.answerSetHidden.options.length;
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
     } */
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
//THIS FUNCTION IS USED TO ADD A NEW DEGREE
//
//Author : Gurkeerat Sidhu
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addFeedbackOptions() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitFeedbackAdvOptionsAdd.php';
         
         var textString='';
         var weightString='';
         var orderString='';
		 var tIdNos='';
         var ele=document.getElementById('tableDiv').getElementsByTagName("INPUT");
         var len=ele.length;
         if(len == 0)
         {
         messageBox("Option text,option weight and print order can not be empty, Please click on 'Add More' to enter required data");
         return false;     
         }

         for(var i=0;i<len;i++){
             if(ele[i].type.toUpperCase()=='HIDDEN' && ele[i].name=='idNos[]'){
                 if(tIdNos!=''){
                    tIdNos +='!@~!~!@~!@~!';
                 }
                 tIdNos += ' '+trim(ele[i].value); 
             }
         }


         for(var i=0;i<len;i++){
             if(ele[i].type.toUpperCase()=='TEXT' && ele[i].name=='optionLabel[]'){
                 if(trim(ele[i].value)==''){
                     messageBox("<?php echo ENTER_OPTION_LABEL;?>");
                     ele[i].focus();
                     return false;
                 }
                 if(textString!=''){
                     textString +='!@~!~!@~!@~!';
                 }
                 textString += ' '+trim(ele[i].value); 
             }
         }
         
         for(var i=0;i<len;i++){
             if(ele[i].type.toUpperCase()=='TEXT' && ele[i].name=='optionPoints[]'){
                 if(ele[i].value==''){
                     messageBox("<?php echo ENTER_OPTION_VALUE;?>");
                     ele[i].focus();
                     return false;
                 }
                 if(!isDecimal(trim(ele[i].value))) {
                    messageBox("<?php echo ENTER_OPTION_LABEL_TO_NUM; ?>");
                    ele[i].focus();
                    return false;
                }
                if(ele[i].value<0 || ele[i].value>1000) {
                messageBox("<?php echo OPTION_VALUE_GREATER_ZERO; ?>");
                ele[i].focus();
                return false;
                break;
            } 

            if(weightString!=''){
                     weightString +='!@~!~!@~!@~!';
                 }
                 weightString +=ele[i].value; 
             }
         }
         
         for(var i=0;i<len;i++){
             if(ele[i].type.toUpperCase()=='TEXT' && ele[i].name=='printOrder[]'){
                 if(ele[i].value==''){
                     messageBox("<?php echo ENTER_ADV_PRINT_ORDER;?>");
                     ele[i].focus();
                     return false;
                 }
                 if(!isNumeric(trim(ele[i].value)) ) {
                    messageBox("<?php echo NUMERIC_PRINT_ORDER_VALIDATIONS; ?>");
                    ele[i].focus();
                    return false;
                 }
          
                if( ele[i].value<1 || ele[i].value>100) {
                    messageBox("<?php echo ANSWER_OPTIONS_PRINT_ORDER_VALIDATIONS; ?>");
                    ele[i].focus();
                    return false;
                } 
                 if(orderString!=''){
                     orderString +='!@~!~!@~!@~!';
                 }
                 orderString +=ele[i].value; 
             }
         }
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 optionLabel:   textString, 
                 optionPoints:  weightString,
                 printOrder:    orderString,
				 tIdNos: tIdNos,
                 surveyId:      trim(document.AddFeedbackOptions.answerSet.value)
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
                             hiddenFloatingDiv('AddFeedbackOptions');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
		 				 /*  var ret=trim(transport.responseText).split('!~~!');	  
						   var j0 = trim(ret[0]);
						   var j1 = trim(ret[1]);  
						   messageBox(j0);
						   if(j1!='') {
							 id = "optionLabel"+j1;
							 eval("document.getElementById('"+id+"').className='inputboxRed'"); 
							 eval("document.getElementById('"+id+"').focus()");
						   } 
                        messageBox(trim(transport.responseText)); 
						*/
                        if("<?php echo FEEDBACK_OPTION_ALREADY_EXIST; ?>" == trim(transport.responseText)){
							document.AddFeedbackOptions.optionLabel.focus();
                        }
                        else if("<?php echo FEEDBACK_OPTION_VALUE_ALREADY_EXIST; ?>" == trim(transport.responseText)) {
                         document.AddFeedbackOptions.optionPoints.focus();
						  
                        }
						else if("<?php echo FEEDBACK_ORDER_ALREADY_EXIST; ?>" == trim(transport.responseText)) {
                         document.AddFeedbackOptions.printOrder.focus();
	                     }
			    }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") } }
           );

}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A optionLabel
//  id=degreeId
//Author : Gurkeerat Sidhu
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteFeedbackOptions(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitFeedbackAdvOptionsDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {answerSetOptionId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "AddFeedbackOptions" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------
function blankValues() {
   //document.AddFeedbackOptions.optionLabel.value = '';
   //document.AddFeedbackOptions.optionPoints.value = '';
   document.AddFeedbackOptions.answerSet.value = '';
   cleanUpTable();
   addOneRow(1);
   document.AddFeedbackOptions.answerSet.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A grade label
//
//Author : Gurkeerat Sidhu
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editFeedbackOptions() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitFeedbackAdvOptionsEdit.php';
                  
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                 answerSetOptionId  :    (document.EditFeedbackOptions.answerSetOptionId.value),
                 optionLabel        :    trim(document.EditFeedbackOptions.optionLabel.value), 
                 optionPoints       :    trim(document.EditFeedbackOptions.optionPoints.value),
                 printOrder         :    trim(document.EditFeedbackOptions.printOrder.value),
                 surveyId           :    trim(document.EditFeedbackOptions.answerSet.value) 
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditFeedbackOptions');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                   else if("<?php echo FEEDBACK_OPTION_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo FEEDBACK_OPTION_ALREADY_EXIST ;?>"); 
                         document.EditFeedbackOptions.optionLabel.focus();
                    }  
					else if("<?php echo ANSWER_OPTIONS_PRINT_ORDER_VALIDATIONS;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ANSWER_OPTIONS_PRINT_ORDER_VALIDATIONS ;?>"); 
                         document.EditFeedbackOptions.printOrder.focus();
                    }  
                    else if("<?php echo ENTER_OPTION_LABEL_TO_NUM;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ENTER_OPTION_LABEL_TO_NUM ;?>"); 
                         document.EditFeedbackOptions.printOrder.focus();
                    }  
                    else {
                        messageBox(trim(transport.responseText)); 
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditFeedbackOptions" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id,dv,w,h) {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetFeedbackAdvOptionsValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {answerSetOptionId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        //hiddenFloatingDiv('EditFeedbackOptions');
                        messageBox("<?php echo OPTION_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                     }
                     else if(trim(transport.responseText)=="<?php echo OPTION_CAN_NOT_MOD_DEL; ?>"){
                       messageBox("<?php echo OPTION_CAN_NOT_MOD_DEL; ?>");
                       //hiddenFloatingDiv('EditFeedbackOptions');
                     }
                    else{
                         displayWindow(dv,w,h); 
                         j = eval('('+trim(transport.responseText)+')');
                         document.EditFeedbackOptions.optionLabel.value      = j.optionLabel;
                         document.EditFeedbackOptions.optionPoints.value      = j.optionPoints;
                         document.EditFeedbackOptions.answerSet.value      = j.answerSetId;
                         document.EditFeedbackOptions.answerSetOptionId.value = j.answerSetOptionId;
                         document.EditFeedbackOptions.printOrder.value = j.printOrder;
                         document.EditFeedbackOptions.answerSet.focus();
                    }     
                  

             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print FeedBack Grades report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/feedbackAdvOptionsReportPrint.php?'+qstr;
    window.open(path,"FeedbackAdvOptionsReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='feedbackAdvOptionsReportCSV.php?'+qstr;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FeedbackAdvanced/listFeedbackAdvOptionsContents.php");
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
// $History: listFeedbackOptionsAdv.php $ 
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 20/02/10   Time: 12:25
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//0002923,0002322,0002921,0002920,0002919,
//0002918,0002917,0002916,0002915,0002914,
//0002912,0002911,0002913
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 2/06/10    Time: 6:58p
//Updated in $/LeapCC/Interface
//removed document.AddFeedbackOptions.optionPoints.focus();
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 2/06/10    Time: 6:51p
//Updated in $/LeapCC/Interface
//made enhancements under feedback module
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 25/01/10   Time: 15:52
//Updated in $/LeapCC/Interface
//Made UI related changes as instructed by sachin sir
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 1/20/10    Time: 5:05p
//Updated in $/LeapCC/Interface
//Resolved issues:0002615,0002635,0002600,0002601,0002614
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 1/13/10    Time: 11:24a
//Updated in $/LeapCC/Interface
//Fixed issue as list was not populating
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 5:18p
//Created in $/LeapCC/Interface
//Created file under Feedback Advanced Answer Set Options Module
//

?>