<?php
//used for showing class wise grades
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GraceMarks');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Grace Marks</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<script language="javascript"> 
//to stop special formatting
specialFormatting=0;
recordsPerPage = 1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGraceMarksList.php';
searchFormName = 'testWiseMarksReportForm'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy = 'ASC';
var valShow=0;
var resourceAddCnt1=0;
var resourceAddCnt2=0;
var dtArray=new Array();  
var bgclass1='';
var bgclass2='';

var tableHeadArray = new Array(new Array('srNo','S. No.','width="3%"','',false) ,
                               new Array('studentName','Name','width="12%"','align="left"',true) ,
                               new Array('rollNo','Roll No.','width="7%"','align="left" style="padding-left:5px;"',true),
                               new Array('universityRollNo','Univ. Roll No.','width="7%"','align="left" style="padding-left:5px;"',true),
                               new Array('marksScored','Marks Scored','width="7%"','align="right"',true),
                               new Array('graceMarks','Grace Marks','width="7%"','align="right"',false),
                               new Array('newMarks', 'Marks with Grace','width="9%"','align="right"',false),
                               new Array('maxMarks','Max. Marks','width="7%"','align="right"',false));

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function getData(){
  
   document.getElementById('buttonRow').style.display='none';
   document.getElementById('headingDivId').style.display='none';
   
   if(document.getElementById('class1').value == ""){
     messageBox("<?php echo SELECT_CLASS; ?>");
     document.getElementById('class1').focus();
     return false;
   }
   if(document.getElementById('subject').value == ""){
     messageBox("<?php echo SELECT_SUBJECT; ?>");
     document.getElementById('subject').focus();
     return false;
   }
   if(document.getElementById('group').value == ""){
     messageBox("<?php echo SELECT_GROUP; ?>");
     document.getElementById('group').focus();
     return false;
   }

   var url='<?php echo HTTP_LIB_PATH;?>/GraceMarks/ajaxSubjectMaxMarks.php';    
   
   var intMks = 0;
   var extMks = 0;
   var totMks = 0;
   new Ajax.Request(url,
   {
     method:'post',
     parameters: { class1: document.getElementById('class1').value,
                   subject: document.getElementById('subject').value , 
                   group: document.getElementById('group').value 
                 },
     asynchronous:false,
     onCreate: function(transport){
       showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);                       
        var j = eval('('+trim(transport.responseText)+')');   
        len=j.length;
        if(len>0) {
            for(i=0;i<len;i++) {    
              if(j[i]['mksType']=='1') {  
                intMks = j[i]['maxMarks'];
              }
              if(j[i]['mksType']=='2') {  
                extMks = j[i]['maxMarks'];
              }
             }
        }
        totMks = parseFloat(intMks,2)+parseFloat(extMks,2);
          
        url = '<?php echo HTTP_LIB_PATH;?>/GraceMarks/ajaxGraceMarksList.php';  
   
        var txt1 = "Internal Marks<br>"+intMks;
        var txt2 = "External Marks<br>"+extMks;   
        var txt3 = "Total Marks<br>"+totMks;   
          
        var aa="";
        var bb="";
          
        if(document.testWiseMarksReportForm.graceMarksFor[0].checked==true) {  
          newtxt = "<span style='color:red;'>Grace For<br>Internal</span>";
          aa = "<span style='color:blue;'>External Grace Marks</span>";   
          bb = "<span style='color:blue;'>Total Grace Marks</span>"; 
        }
        else if(document.testWiseMarksReportForm.graceMarksFor[1].checked==true) {  
            newtxt = "<span style='color:red;'>Grace For<br>External</span>";
            aa = "<span style='color:blue;'>Internal Grace Marks</span>";
            bb = "<span style='color:blue;'>Total Grace Marks</span>"; 
        }
        else { 
            newtxt = "<span style='color:red;'>Grace For<br>Total</span>";
            aa = "<span style='color:blue;'>Internal Grace Marks</span>";
            bb = "<span style='color:blue;'>External Grace Marks</span>";   
        }
           
        tableColumns = new Array(new Array('srNo','#', 'width="2%"  align="left"',false), 
                                 new Array('studentName','Name','width="15%" align="left"',true), 
                                 new Array('rollNo','Roll No.','width="10%" align="left"',true),
                                 new Array('universityRollNo','Univ. RNo.','width="12%" align="left"',true),
                                 new Array('intMarks',txt1,'width="12%" align="center"',false),
                                 new Array('extMarks',txt2,'width="12%" align="center"',false),
                                 new Array('marksScored',txt3,'width="12%" align="center"',false),
                                 new Array('graceMarksEnter1',aa,'width="12%" align="center"',false),
                                 new Array('graceMarksEnter2',bb,'width="12%" align="center"',false),
                                 new Array('ttGraceMarks',newtxt,'width="12%" align="center"',false),
                                 new Array('newMarks','Marks with Grace','width="15%" align="center"',false));
             
        document.getElementById('buttonRow').style.display='';
        document.getElementById('headingDivId').style.display='';
        document.getElementById('graceMarksAll').value='';
           
        //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
        listObj4 = new initPage(url,recordsPerPage,linksPerPage,1,'testWiseMarksReportForm','studentName','ASC','results','','',true,'listObj4',tableColumns,'','');
        sendRequest(url, listObj4, '',true)
        return false;  
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
   
}


function getClasses() {
	form = document.testWiseMarksReportForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGraceMarksClasses.php';
	var pars = 'labelId='+form.labelId.value;
	if (form.labelId.value=='') {
		form.class1.length = null;
		addOption(form.class1, '', 'Select');
		return false;
	}

	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.class1.length = null;
			addOption(form.class1, '', 'Select');
			for(i=0;i<len;i++) {
				addOption(form.class1, j[i].classId, j[i].className);
			}
			// now select the value
			//form.class1.value = j[0].classId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function calculateClassAverage() {   
        var eles=document.getElementsByTagName("INPUT");
        var len=eles.length;
        var marksScored = 0;
        var graceMarks = 0;
        var maxMarksScored = 0;
        var hiddenElements = 0;
    
        for(var i=0;i<len;i++){
           if (eles[i].type.toUpperCase()=='HIDDEN' || (eles[i].type.toUpperCase()=='TEXT' && eles[i].name != 'graceMarksAll')){
             if (eles[i].name.indexOf('marksScored') != -1) {
                if (eles[i].value != '') {
                    marksScored += parseInt(eles[i].value);
                }
             }
             else if (eles[i].name.indexOf('graceMarks') != -1) {
                if (eles[i].value != '') {
                    graceMarks  += parseInt(eles[i].value);
                }
             }
             else if (eles[i].name.indexOf('maxMarks') != -1) {
                if (eles[i].value != '') {
                    maxMarksScored  += parseInt(eles[i].value);
                }
             }
             }
             }
            var classAverageWithGrace = ((marksScored + graceMarks)*100) / maxMarksScored;
            classAverageWithGrace = Math.round(classAverageWithGrace*100)/100;
            document.getElementById("classAverageSpan").innerHTML = classAverageWithGrace;

            var classAverageWithoutGrace = ((marksScored)*100) / maxMarksScored;
            classAverageWithoutGrace = Math.round(classAverageWithoutGrace * 100)/100;
            document.getElementById("classAverageSpan3").innerHTML = classAverageWithoutGrace;
}

//-----------------------------------------------------------------------------------------------------------
//used to clear data
function clearData(){
    document.getElementById('results').innerHTML='';
    document.getElementById('buttonRow').style.display='none';
    document.getElementById('headingDivId').style.display='none';
    document.getElementById('graceMarksAll').value='';
    /*
    if(document.testWiseMarksReportForm.graceMarksFor[2].checked==true) {
      document.getElementById('showRange').style.display=''; 
    }
    else {
      document.getElementById('showRange').style.display='none';   
    }
    */
}


//-----------------------------------------------------------------------------------------------------------
//used to populate grace marks textboxes
function setData(value){
    try {
          // var gracetype=document.testWiseMarksReportForm.searchOrder[0].checked==true?1:2;
          s = value.toString();
          var fl=0;
          for(var i = 0; i < s.length; i++){
            var c = s.charAt(i); 
            if(!isDecimal(c))  {
              document.getElementById('graceMarksAll').value=document.getElementById('graceMarksAll').value.replace(c,"");  
              fl=1;
            } 
          }
          if(fl==1){
             document.getElementById('graceMarksAll').focus();
             return false;
          } 
          
          var lc=document.testWiseMarksReportForm.graceMarks.length;
          if(lc >1){
            for(var i=0; i < lc; i++){
            document.testWiseMarksReportForm.graceMarks[ i ].value=value;
            alertData(i); 
            }  
          }
          else if(lc==1){
             document.testWiseMarksReportForm.graceMarks.value= value;
             alertData(0); 
          } 
      return true;
    } catch(e){ }
}

//-----------------------------------------------------------------------------------------------------------
//used to alert students
function alertData(e,srNoUp,srNoDown,i){
	
	var ev = e||window.event;
	
	
   thisKeyCode = ev.keyCode;	
   if (thisKeyCode == '40') {
   	 txt = "graceMarks"+srNoDown;
   	 if(eval("document.getElementById('"+txt+"').value")=='0'){
   	 	eval("document.getElementById('"+txt+"').value=''");
   	 }
   	 eval("document.getElementById('"+txt+"').focus()");
   	 return false;
   }
   else if (thisKeyCode == '38') {
   	 txt = "graceMarks"+srNoUp;
   	 if(eval("document.getElementById('"+txt+"').value")=='0'){
   	 	eval("document.getElementById('"+txt+"').value=''");
   	 }
   	 eval("document.getElementById('"+txt+"').focus()");
   	 return false;
   }
  
    document.getElementById('lblShowRangeList').style.display='';
    val1="graceMarks"+i;      //grace marks
    val2="markScored"+i;      //marks scored                             
    val3="maxMarkScored"+i;   //max marks
    val4="newMarks"+i;   //max marks
    
    val5="finalInternal"+i;   //max marks
    val6="finalExternal"+i;   //max marks
    val7="finalTotal"+i;   //max marks
    val8="previousGraceMarks"+i;   //max marks
    
    
    chkMks='0';
    if(document.testWiseMarksReportForm.graceMarksFor[0].checked==true) {   
       chkMks = parseFloat(trim(eval("document.getElementById('"+val5+"').value")));    
    }
    else if(document.testWiseMarksReportForm.graceMarksFor[1].checked==true) {   
       chkMks = parseFloat(trim(eval("document.getElementById('"+val6+"').value")));  
    }
    else if(document.testWiseMarksReportForm.graceMarksFor[2].checked==true) {   
       chkMks = parseFloat(trim(eval("document.getElementById('"+val7+"').value"))); 
    }
    
    var previousGraceMarks = parseFloat(trim(eval("document.getElementById('"+val8+"').value"))); 
   
    if(chkMks=='') {
      chkMks=0;  
    }
    
    if(chkMks=='<?php echo NOT_APPLICABLE_STRING; ?>') {
      chkMks=0;  
    }
    
    if(previousGraceMarks=='') {
      previousGraceMarks=0;  
    }
    
    
    //GIVING INSTANT ALERT IF SOMETHING WRONG IS INPUTTED
    /*
      marks scored+grace marks <= max marks
    */       
    
    
    
    (parseFloat(trim(eval("document.getElementById('"+val1+"').value"))) + parseFloat(chkMks) > parseFloat(trim(eval("document.getElementById('"+val3+"').value")),10)) ? (eval("document.getElementById('"+val1+"').className='inputboxRed'")) : eval("document.getElementById('"+val1+"').className='inputbox'");

    //check for numeric value
    s = document.getElementById(val1).value.toString();
    var fl=0;
    
    for (var i = 0; i < s.length; i++){
     var c = s.charAt(i); 
     if(!isDecimal(c)){
      document.getElementById(val1).value=document.getElementById(val1).value.replace(c,""); 
      fl=1; 
    }
  }
  if(fl==1){
      document.getElementById(val1).focus(); 
      return false;
  }
  //to show updated "new marks"
  if(trim(document.getElementById(val1).value)!='' && trim(document.getElementById(val1).value)!=''){
    document.getElementById(val4).innerHTML= parseFloat(previousGraceMarks,10) + parseFloat(trim(document.getElementById(val1).value),10)+parseFloat(trim(document.getElementById(val2).value),10)
  }
  else{
    document.getElementById(val4).innerHTML=parseFloat(trim(document.getElementById(val2).value),10) +  parseFloat(previousGraceMarks,10) ;
  }
  calculateClassAverage(); //function added to calculate class average.
  return true; 
}

//-----------------------------------------------------------------------------------------------------------
//used to save data
function saveData(){
  
   if(document.getElementById('class1').value == ""){
        messageBox("<?php echo SELECT_CLASS; ?>");
        document.getElementById('class1').focus();
        return false;
    }
   if(document.getElementById('subject').value == ""){
        messageBox("<?php echo SELECT_SUBJECT; ?>");
        document.getElementById('subject').focus();
        return false;
    }
   if(document.getElementById('group').value == ""){
        messageBox("<?php echo SELECT_GROUP; ?>");
        document.getElementById('group').focus();
        return false;
   }
   
   var lc=document.testWiseMarksReportForm.graceMarks.length;
   if(lc >1){
     for(var i=0; i < lc; i++){
        if(trim(document.testWiseMarksReportForm.graceMarks[ i ].value)!='') {
            str1='';
            str ='';
            chkMks='0';
            if(document.testWiseMarksReportForm.graceMarksFor[0].checked==true) {   
               chkMks = parseFloat(trim(document.testWiseMarksReportForm.finalInternal[i].value));  
               str ='Internal';  
               str1='(Total and Internal) Grace Marks';
            }
            else if(document.testWiseMarksReportForm.graceMarksFor[1].checked==true) {   
               chkMks = parseFloat(trim(document.testWiseMarksReportForm.finalExternal[i].value));    
               str ='External';  
               str1='(Total and External) Grace Marks'; 
            }
            else if(document.testWiseMarksReportForm.graceMarksFor[2].checked==true) {   
               chkMks = parseFloat(trim(document.testWiseMarksReportForm.finalTotal[i].value));    
               str ='Total';  
               str1='(Internal and External) Grace Marks';     
            }
            
            if(chkMks=='') {
              chkMks=0;  
            }
            
            if(chkMks=='<?php echo NOT_APPLICABLE_STRING; ?>') {
              chkMks=0;  
              
            }
            
            if(parseFloat(document.testWiseMarksReportForm.graceMarks[i].value,10)+parseFloat(chkMks,10)>parseFloat(document.testWiseMarksReportForm.maxMarkScored[i].value,10)){
               alertData(0);
               messageBox("Sum of "+str1+" and marks scored can not be greater than maximum "+str+" marks");
               document.testWiseMarksReportForm.graceMarks[i].focus();
               return false;
            }
        }
     }  
   }
   if(typeof lc === "undefined") {  
       if(trim(document.testWiseMarksReportForm.graceMarks.value)!=''){
           chkMks='0';
           str='';
           str1='';
           if(document.testWiseMarksReportForm.graceMarksFor[0].checked==true) {   
             chkMks = parseFloat(trim(document.testWiseMarksReportForm.finalInternal.value));    
             str ='Internal';   
             str1='(Total and Internal) Grace Marks';
           }
           else if(document.testWiseMarksReportForm.graceMarksFor[1].checked==true) {   
             chkMks = parseFloat(trim(document.testWiseMarksReportForm.finalExternal.value));    
             str ='External';   
             str1='(Total and External) Grace Marks';
           }
           else if(document.testWiseMarksReportForm.graceMarksFor[2].checked==true) {   
             chkMks = parseFloat(trim(document.testWiseMarksReportForm.finalTotal.value));    
              str ='Total';   
              str1='(Internal and External) Grace Marks';  
           }
            
            if(chkMks=='') {
              chkMks=0;  
            }
            
            if(chkMks=='<?php echo NOT_APPLICABLE_STRING; ?>') {
              chkMks=0;  
            }
          
          if(parseFloat(document.testWiseMarksReportForm.graceMarks.value,10)+parseFloat(chkMks,10) >parseFloat(document.testWiseMarksReportForm.maxMarkScored.value,10)){
            alertData(0);
            messageBox("Sum of "+str1+" and marks scored can not be greater than maximum "+str+" marks");
            document.testWiseMarksReportForm.graceMarks.focus();
            return false;
         }
       }
   }
  
  
  giveGraceMarks();
  return false;     
}

//--------------------------------------------------------------------------------------
//used to save grace marks data
/*  
    var doubleClickFl=0; 
    function giveGraceMarks(){
       
        if(doubleClickFl==1){
          messageBox("Another Request is in progress.");
          return false;
        }
       
        var studentIds='';
        var graceMarks='';
        
        var lc=document.testWiseMarksReportForm.graceMarks.length;      
        if(lc >1){
            for(var i=0; i < lc; i++) {
                if(trim(document.testWiseMarksReportForm.graceMarks[i].value)!='') {
                    if(studentIds!=''){
                       studentIds +=',';   
                       graceMarks +=','; 
                    }
                    studentIds += document.testWiseMarksReportForm.students[i].value;
                    graceMarks += document.testWiseMarksReportForm.graceMarks[i].value !='' ? parseInt(document.testWiseMarksReportForm.graceMarks[i].value,10) : 0;
                }
             }  
       }
       else if(lc==1){
         studentIds =0;  
         graceMarks =0;
         if(trim(document.testWiseMarksReportForm.graceMarks.value)!='') {
           studentIds =document.testWiseMarksReportForm.students.value;
           graceMarks = document.testWiseMarksReportForm.graceMarks.value !='' ? parseInt(document.testWiseMarksReportForm.graceMarks.value,10) : 0;
         }
       }
       
       alert(studentIds);
       return false;
       
       var url = '<?php echo HTTP_LIB_PATH;?>/GraceMarks/giveGraceMarks.php';
       new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 studentIds : studentIds,
                 graceMarks : graceMarks,
                 classId    : document.getElementById('class1').value,
                 subjectId  : document.getElementById('subject').value,
                 group      : document.getElementById('group').value,
                 rollNo     : trim(document.getElementById('studentRollNo').value) ,
                 graceMarksFormat : newtxt  
             },
             asynchronous:false,
             onCreate: function(transport){
                  showWaitDialog(true);
                  doubleClickFl=1;
             },
             onSuccess: function(transport){
                hideWaitDialog(true);
                doubleClickFl=0;
                if(trim(transport.responseText)=="<?php echo SUCCESS;?>"){
                   messageBox("<?php echo GRACE_MARKS_GIVEN; ?>");
                }
                else{
                  messageBox(trim(transport.responseText));  
                }
                clearData();
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
        }); 
    }
*/

var doubleClickFl=0; 
function giveGraceMarks(){
    
        if(doubleClickFl==1){
          messageBox("Another Request is in progress.");
          return false;
        }
       
        var studentIds='';
        var graceMarks='';
        
        var lc=document.testWiseMarksReportForm.graceMarks.length;      
        
        if(lc >1){
            for(var i=0; i < lc; i++){
                if(studentIds!=''){
                   studentIds +=',';   
                   graceMarks +=','; 
                }
                studentIds += document.testWiseMarksReportForm.students[i].value;
                graceMarks += document.testWiseMarksReportForm.graceMarks[i].value !='' ? parseInt(document.testWiseMarksReportForm.graceMarks[i].value,10) : 0;
            }  
       }
       if(typeof lc === "undefined") {
         studentIds =document.testWiseMarksReportForm.students.value;
         graceMarks = document.testWiseMarksReportForm.graceMarks.value !='' ? parseInt(document.testWiseMarksReportForm.graceMarks.value,10) : 0;
       }
       
   
    if(document.testWiseMarksReportForm.graceMarksFor[0].checked==true) {  
        newtxt = 1;
    }
    else if(document.testWiseMarksReportForm.graceMarksFor[1].checked==true) {  
        newtxt = 2;
    }
    else { 
        newtxt = 3;
    }
   
   var url = '<?php echo HTTP_LIB_PATH;?>/GraceMarks/giveGraceMarks.php';
   new Ajax.Request(url,
   {
     method:'post',
     parameters: {
        studentIds : studentIds,
        graceMarks : graceMarks,
        classId    : document.getElementById('class1').value,
        subjectId  : document.getElementById('subject').value,
        group      : document.getElementById('group').value,
        rollNo     : trim(document.getElementById('studentRollNo').value) ,
        graceMarksFormat : newtxt  
     },
     onCreate: function(transport){
        showWaitDialog(true);
        doubleClickFl=1;
     },
     onSuccess: function(transport){
        hideWaitDialog(true);
        doubleClickFl=0;
        if(trim(transport.responseText)=="<?php echo SUCCESS;?>"){
            messageBox("<?php echo GRACE_MARKS_GIVEN; ?>");
        }
        else{
          messageBox(trim(transport.responseText));  
        }
        clearData();
     },
     onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
    }); 
}

//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO clear rollno when clas,subject or group is changed
//
//Author : Dipanjan Bhattacharjee
// Created on : (07.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function deleteRollNo(){
  // document.frmGraceMarks.studentRollNo.value=""; 
}

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh 
// Created on : (12.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function groupPopulate(value) {
   url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxAllGroupPopulate.php';
   
   clearData();
   
   document.testWiseMarksReportForm.group.options.length=0;
   var objOption = new Option("Select","");
   document.testWiseMarksReportForm.group.options.add(objOption); 
   
   if(document.getElementById('subject').value==""){
       return false;
   }

 new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 subjectId: document.getElementById('subject').value,
                 classId: document.getElementById('class1').value
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
				    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 

					 var r=1;
                     var tname='';
                     document.testWiseMarksReportForm.group.options.length=0; 
                     if(j.length>0) {
                       var objOption = new Option("All","-1");
                       document.testWiseMarksReportForm.group.options.add(objOption);   
                     }
                     else {
                       var objOption = new Option("Select Group","");
                       document.testWiseMarksReportForm.group.options.add(objOption);   
                     }
                     for(var c=0;c<j.length;c++){
						 var objOption = new Option(j[c].groupName,j[c].groupId);
                         document.testWiseMarksReportForm.group.options.add(objOption);
					 }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh 
// Created on : (12.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function subjectPopulate(value) {
   url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxTransferredSubjectPopulate.php';
   
   clearData();
   
   
   document.testWiseMarksReportForm.subject.options.length=0;
   var objOption = new Option("Select Subject","");
   document.testWiseMarksReportForm.subject.options.add(objOption);
   
   document.testWiseMarksReportForm.group.options.length=0;
   var objOption = new Option("Select Group","");
   document.testWiseMarksReportForm.group.options.add(objOption); 
   
   if(document.getElementById('class1').value==""){
       return false;
   }

 new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 classId: document.getElementById('class1').value
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 

                     var r=1;
                     var tname='';

                     for(var c=0;c<j.length;c++){
                         
                         var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                         document.testWiseMarksReportForm.subject.options.add(objOption);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}
// used to cleanup page after refresh
window.onload=function(){
    // Clear List
    // document.testWiseMarksReportForm.reset();  
    // document.getElementById('class1').focus();
    getClasses();
    getShowDetail();
    var roll = document.getElementById("studentRollNo");
    // autoSuggest(roll);
}

function getShowDetail() {
   if(valShow==1) {
     document.getElementById("showRange").style.display='';
     document.getElementById("lblMsg").innerHTML="Hide Range Detail";
     document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"
     valShow=0;
     getRange();
   }
   else {
     document.getElementById("showRange").style.display='none';
     document.getElementById("lblMsg").innerHTML="Show Range Detail"; 
     document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif"
     valShow=1;  
   }
}


function checkDuplicateRanke(value) {
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

function getRange() {
   
 try {
    
    showWaitDialog(true); 
    document.getElementById('lblShowRangeList').style.display='none'; 
    document.getElementById('hideRangeData').style.display='';       
    document.getElementById('showRangeData').style.display='none';
    
    dtArray=new Array();   
    dtArray.splice(0,dtArray.length); //empty the array  
   
    bgclass1='';
    bgclass2='';
 
    cleanUpTable(1);
    cleanUpTable(2);
    
    resourceAddCnt1=0;
    resourceAddCnt2=0;
    
    // First Range Check
    var testRange=trim(document.getElementById('firstRange').value);
    var tR=testRange.split(',');
    var len1=tR.length;
    for(var i=0;i<len1;i++){
        var tRange=tR[i].split('-');
        var len2=tRange.length;
        if(len2!=2){
            cleanUpTable(1);
            cleanUpTable(2);
            hideWaitDialog(true);  
            messageBox("Define First Range incorrrect");
            document.getElementById('firstRange').focus();
            return false;
        }
        for(var k=0;k<len2;k++){
          if(!isDecimal(trim(tRange[k]))){
            cleanUpTable(1);
            cleanUpTable(2);
            hideWaitDialog(true);  
            messageBox("Enter numeric value for Define First Range");
            document.getElementById('firstRange').focus();
            return false;
          }
          tRange[k] = trim(tRange[k]);
        }
        
        if(parseFloat(tRange[0],10) > parseFloat(tRange[1],10)) {
           cleanUpTable(1);
           cleanUpTable(2);
           strRange = tRange[0]+'-'+tRange[1];  
           hideWaitDialog(true);  
           messageBox("Define First Range should not be accepted ("+strRange+")");
           document.getElementById('firstRange').focus();
           return false;  
        }
        
        if(checkDuplicateRanke('f'+tRange[0]+'-'+tRange[1])==0){
           cleanUpTable(1);
           cleanUpTable(2);
           strRange = tRange[0]+'-'+tRange[1];  
           hideWaitDialog(true);  
           messageBox("Duplicate range should not be accepted ("+strRange+")");
           document.getElementById('firstRange').focus();
           return false;
        }
       
        cntNoOfStudent = 0;
        per=0; 
        if(typeof (document.testWiseMarksReportForm.graceMarks) === "undefined") {  
        }
        else {
            var lc=document.testWiseMarksReportForm.graceMarks.length;
            for(var j=0; j < lc; j++) {
               rangeVal = eval("document.getElementById('newMarks"+j+"').innerHTML");  
               if(parseFloat(rangeVal,10) >= parseFloat(tRange[0],10) &&  parseFloat(rangeVal,10) <= parseFloat(tRange[1],10)) {
                 cntNoOfStudent++;                
               }
            }  
            if(cntNoOfStudent>0) {
              per= parseFloat(parseInt(cntNoOfStudent)*100/parseInt(document.testWiseMarksReportForm.graceMarks.length),2).toFixed(2);  
            }
        }
        strRange = tRange[0]+' - '+tRange[1];
        addOneRow(1,1,strRange,cntNoOfStudent,per);
    }
    
    // Second Range Check
    var testRange=trim(document.getElementById('secondRange').value);
    var tR=testRange.split(',');
    var len1=tR.length;
    for(var i=0;i<len1;i++){
        var tRange=tR[i].split('-');
        var len2=tRange.length;
        if(len2!=2){
           cleanUpTable(1);
           cleanUpTable(2);
           hideWaitDialog(true);  
           messageBox("Define Second Range incorrrect");
           document.getElementById('secondRange').focus();
           return false;
        }
        for(var k=0;k<len2;k++){
          if(!isDecimal(trim(tRange[k]))){
            cleanUpTable(1);
            cleanUpTable(2);
            hideWaitDialog(true);  
            messageBox("Enter numeric value for Define Second Range");
            document.getElementById('secondRange').focus();
            return false;
          }
          tRange[k] = trim(tRange[k]);
        }
        
        if(parseFloat(tRange[0],10) > parseFloat(tRange[1],10)) {
           cleanUpTable(1);
           cleanUpTable(2);
           strRange = tRange[0]+'-'+tRange[1];  
           hideWaitDialog(true);  
           messageBox("Define Second Range should not be accepted ("+strRange+")");
           document.getElementById('secondRange').focus();
           return false;  
        }
        
        if(checkDuplicateRanke('s'+tRange[0]+'-'+tRange[1])==0) {
           cleanUpTable(1);
           cleanUpTable(2);
           strRange = tRange[0]+'-'+tRange[1];   
           hideWaitDialog(true);  
           messageBox("Duplicate range should not be accepted ("+strRange+")");
           document.getElementById('secondRange').focus();
           return false;
        }
        
        cntNoOfStudent = 0;
        per=0; 
        if(typeof (document.testWiseMarksReportForm.graceMarks) === "undefined") {  
          
        }
        else {
            var lc=document.testWiseMarksReportForm.graceMarks.length;
            for(var j=0; j < lc; j++) {
               rangeVal = eval("document.getElementById('newMarks"+j+"').innerHTML");  
               if(parseFloat(rangeVal,10) >= parseFloat(tRange[0],10) &&  parseFloat(rangeVal,10) <= parseFloat(tRange[1],10)) { 
                 cntNoOfStudent++;                
               }
            }   
            if(cntNoOfStudent>0) {
              per= parseFloat(parseInt(cntNoOfStudent)*100/parseInt(document.testWiseMarksReportForm.graceMarks.length),2).toFixed(2);  
            }
        }
        strRange = tRange[0]+' - '+tRange[1];
        addOneRow(1,2,strRange,cntNoOfStudent,per);
    }
    document.getElementById('hideRangeData').style.display='none';       
    document.getElementById('showRangeData').style.display='';
    document.getElementById('showRangeData').style.display='';
    document.getElementById('lblTotalStudent').innerHTML= parseInt(document.testWiseMarksReportForm.graceMarks.length);
    
    hideWaitDialog(true);  
  } 
  catch(e) {
    document.getElementById('hideRangeData').style.display='none';       
    document.getElementById('showRangeData').style.display='';
    document.getElementById('lblTotalStudent').innerHTML="<?php echo NOT_APPLICABLE_STRING ?>";  
    cleanUpTable(1);
    cleanUpTable(2);
    hideWaitDialog(true);  
  }    
}

//to add one row at the end of the list
function addOneRow(cnt,drawMode,strRange,cntNoOfStudent,per) {
    
     if(drawMode==1) {
         if(cnt=='')
           cnt=1;  
         if(isMozilla){
           if(document.getElementById('anyidBody').childNodes.length <= 3){
             resourceAddCnt1=0; 
           }       
         }  
         else{
             if(document.getElementById('anyidBody').childNodes.length <= 1){
               resourceAddCnt1=0;  
             }       
         }
         resourceAddCnt1++; 
         resourceAddCnt = resourceAddCnt1;
     }
     else {
        if(cnt=='')
           cnt=1;  
         if(isMozilla){
           if(document.getElementById('anyidBody1').childNodes.length <= 3){
             resourceAddCnt2=0; 
           }       
         }  
         else{
             if(document.getElementById('anyidBody1').childNodes.length <= 1){
               resourceAddCnt2=0;  
             }       
         }
         resourceAddCnt2++; 
         resourceAddCnt = resourceAddCnt2;
     }
     createRows(resourceAddCnt,cnt,drawMode,strRange,cntNoOfStudent,per); 

}



//create dynamic rows 
function createRows(start,rowCnt,drawMode,strRange,cntNoOfStudent,per){

     if(cntNoOfStudent=='') {
       cntNoOfStudent=0;   
     }
     if(drawMode=='1') {  
       var tbl=document.getElementById('anyid');
       var tbody = document.getElementById('anyidBody');
       bgclass1=(bgclass1=='row0'? 'row1' : 'row0');  
       bgclass = bgclass1;
       strMode='f';
     }
     else {
       var tbl=document.getElementById('anyid1');
       var tbody = document.getElementById('anyidBody1');  
       bgclass2=(bgclass2=='row0'? 'row1' : 'row0');  
       bgclass = bgclass2;
       strMode='s';
     }
                         
     for(var i=0;i<rowCnt;i++) {
          var tr=document.createElement('tr');
          tr.setAttribute('id','row'+strMode+parseInt(start+i,10));
          
          var cell1=document.createElement('td');  
          var cell2=document.createElement('td');
          var cell3=document.createElement('td'); 
           
          cell1.setAttribute('align','left');  
          cell2.setAttribute('align','right');  
          cell3.setAttribute('align','right');     

          var txt1=document.createElement('label');   
          txt1.className='dataFont'; 
          txt1.innerHTML=strRange;    
          
          var txt2=document.createElement('label');   
          txt2.className='dataFont'; 
          txt2.setAttribute('style','padding-right:5px'); 
          if(parseInt(cntNoOfStudent) > 0 ) {
            txt2.innerHTML="<b>"+cntNoOfStudent+"</b>";  
          }  
          else {
            txt2.innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>";    
          }
          
          var txt3=document.createElement('label');   
          txt3.className='dataFont'; 
          txt3.setAttribute('style','padding-right:5px');
          if(parseInt(cntNoOfStudent) > 0 ) {
            txt3.innerHTML="<b>"+per+"%</b>";  
          }  
          else {
            txt3.innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>";    
          }
          
          cell1.appendChild(txt1);      
          cell2.appendChild(txt2);
          cell3.appendChild(txt3);
          
          tr.appendChild(cell1);                
          tr.appendChild(cell2);
          tr.appendChild(cell3);
        
          tr.className=bgclass;

          tbody.appendChild(tr); 
     } 
     tbl.appendChild(tbody);   
}


//to clean up table rows
function cleanUpTable(drawMode) {
   
   if(drawMode=='1') { 
     var tbody = document.getElementById('anyidBody');
     resourceAddCnt = resourceAddCnt1;
     strMode='f';
   }
   else {
     var tbody = document.getElementById('anyidBody1');  
     resourceAddCnt = resourceAddCnt2;
     strMode='s';
   }
   
   for(var k=0;k<=resourceAddCnt;k++){
       try{
         tbody.removeChild(document.getElementById('row'+strMode+k));
       }
       catch(e){
         //alert(k);  // to take care of deletion problem
      }
   }  
}

</script>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/GraceMarks/listGraceMarksContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>