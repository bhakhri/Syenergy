<?php
//-------------------------------------------------------
// Purpose: To generate Quota Seat Intake functionality
// Author : Parveen Sharma
// Created on : 27-01-09
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignFinalGrade');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Assign Final Grade</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 

<script type="text/javascript" language="javascript">

var resourceAddCnt=0;
var showDelete='';
// check browser
var isMozilla = (document.all) ? 0 : 1;

var dtArray=new Array();  

function reCalculate(){
      var a=document.getElementsByTagName('td');
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

function addDetailRows(value){
     var tbl=document.getElementById('anyid');
     var tbody = document.getElementById('anyidBody');
     //var tblB    = document.createElement("tbody");
     if(!isInteger(value)){
        return false;
     }
     
     if(resourceAddCnt>0){     //if user reenter no of rows
      if(confirm('Previous Data Will Be Erased.\n Are You Sure ?')){
         cleanUpTable();
      }
      else{
          return false;
      }
    } 
    resourceAddCnt=parseInt(value); 
    createRows(0,resourceAddCnt);
}


//for deleting a row from the table 
function deleteRow(value){
   
    try {   
        var temp=resourceAddCnt;  
        //resourceAddCnt--;
        
        var rval=value.split('~');
        var tbody1 = document.getElementById('anyidBody');
          
        var tr=document.getElementById('row'+rval[0]);
        tbody1.removeChild(tr);
         
        if(isMozilla){
          if((tbody1.childNodes.length-2)==0){
            resourceAddCnt=temp;
          }
        }
        else{
          if((tbody1.childNodes.length-1)==0){
            resourceAddCnt=temp;
          }
        }
    } catch(e){ }
    
    reCalculate();
} 



//to add one row at the end of the list
function addOneRow(cnt)
{
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
     if (resourceAddCnt == 0) {
         document.getElementById('trAttendance').style.display='none';
     }
     else {
         document.getElementById('trAttendance').style.display='';
     }
     //createRows(resourceAddCnt,cnt);
     createRows(resourceAddCnt,cnt);
}

//to clean up table rows
function cleanUpTable() {
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


var bgclass='';

//create dynamic rows 
function createRows(start,rowCnt){

       // alert(start+'  '+rowCnt);
     var tbl=document.getElementById('anyid');
     var tbody = document.getElementById('anyidBody');
                         
     for(var i=0;i<rowCnt;i++) {
          var tr=document.createElement('tr');
          tr.setAttribute('id','row'+parseInt(start+i,10));
          var cell1=document.createElement('td');  
          var cell2=document.createElement('td');
          var cell3=document.createElement('td'); 
          var cell4=document.createElement('td'); 
          var cell5=document.createElement('td');
          var cell6=document.createElement('td'); 
          
          cell1.name='srNo'; 
          cell1.setAttribute('class','searchhead_text');  
          cell1.setAttribute('align','left');  
          cell2.setAttribute('align','left');     
          cell3.setAttribute('align','left'); 
          cell4.setAttribute('align','left'); 
          cell5.setAttribute('align','left'); 
          cell6.setAttribute('align','center'); 
           
          if(start==0){
            var txt0=document.createTextNode(start+i+1);
          }
          else{
            var txt0=document.createTextNode(start+i);
          }
         // var txt0=document.createTextNode(i+1);
          
          var idStore=document.createElement('input');   
          
          var txt1=document.createElement('input');
          var txt2=document.createElement('input');
          var txt3=document.createElement('input'); 
          var txt4=document.createElement('input'); 
          var txt5=document.createElement('a');  
          
          // To store table ids 
          idStore.setAttribute('type','hidden'); 
          idStore.setAttribute('name','idNos[]');
          idStore.setAttribute('id','idNos'+parseInt(start+i,10));  
          idStore.setAttribute('value',parseInt(start+i,10));
                    
          txt1.setAttribute('id','marksFrom'+parseInt(start+i,10));
          txt1.setAttribute('name','marksFrom[]'); 
          txt1.setAttribute('style','width:100px');    
          txt1.setAttribute('maxlength','5');      
          txt1.className='htmlElement';
          
          txt2.setAttribute('type','text');
          txt2.setAttribute('id','marksTo'+parseInt(start+i,10));
          txt2.setAttribute('name','marksTo[]');
          txt2.setAttribute('style','width:100px');  
          txt2.setAttribute('maxlength','5');      
          txt2.className='inputbox1';
          
          txt3.setAttribute('type','text');
          txt3.setAttribute('id','grade'+parseInt(start+i,10));
          txt3.setAttribute('name','grade[]');
          txt3.setAttribute('style','width:100px');  
          txt3.setAttribute('maxlength','3');      
          txt3.className='inputbox1';
          
          txt4.setAttribute('type','text');
          txt4.setAttribute('id','points'+parseInt(start+i,10));
          txt4.setAttribute('name','points[]');
          txt4.setAttribute('style','width:100px');  
          txt4.setAttribute('maxlength','6');      
          txt4.className='inputbox1';
            
          
          imgSrc = '<?php echo IMG_HTTP_PATH; ?>'+'/deactive.gif';
          
          txt5.setAttribute('id','rd'+parseInt(start+i,10));
          txt5.setAttribute('name','rd[]'); 
          txt5.className='inputbox1';   
          if(showDelete=='1') {
            txt5.setAttribute('title','Deactive');  
            txt5.innerHTML='<img src='+imgSrc+' border="0" alt="Deactive" title="Deactive" width="10" height="10">'; 
            txt5.style.cursor='pointer';   
            txt5.setAttribute('href','javascript:deactiveRecord()');  //for ie and ff  
          }
          else if(showDelete=='') {
            txt5.setAttribute('title','Delete');
            txt5.innerHTML='X';  
            txt5.style.cursor='pointer';
            txt5.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff      
          }
          
          cell1.appendChild(txt0);      
          cell2.appendChild(txt1);
          cell3.appendChild(txt2);
          cell3.appendChild(idStore);  
          cell4.appendChild(txt3);
          cell5.appendChild(txt4);   
          cell6.appendChild(txt5);   
          
                  
          tr.appendChild(cell1);                
          tr.appendChild(cell2);
          tr.appendChild(cell3);
          tr.appendChild(cell4);
          tr.appendChild(cell5); 
          tr.appendChild(cell6); 
     
          bgclass=(bgclass=='row0'? 'row1' : 'row0');
          tr.className=bgclass;

          tbody.appendChild(tr); 
     } 
     tbl.appendChild(tbody);  
     reCalculate();     
     showDelete = '';  
}

var percentFromArray=new Array();
var percentToArray=new Array();
var posArray=new Array();


function deactiveRecord() {
   messageBox ("<?php echo DEPENDENCY_CONSTRAINT; ?>");   
}

function validateAddForm(frm) {

    var percentFromArray=new Array();
    var percentToArray=new Array();
    var posArray=new Array();
    var tname ='';
    
    dtArray.splice(0,dtArray.length); //empty the array      
    
    //alert(resourceAddCnt);
    if(resourceAddCnt==0) {
        //msg = confirm('All values for this subject type will be deleted. Are you sure?')
        msg = confirm('All values for this assign final grade set value will be deleted. Are you sure?')
        if(msg == false) {
          return false;
        }
    }
    else {
            formx = document.allDetailsForm;
            var obj=formx.getElementsByTagName('INPUT');
            var total=obj.length;
            var x=0;
            var y=0;
            for(var i=0;i<total;i++) {
                if(obj[i].type.toUpperCase()=='TEXT' && obj[i].name.indexOf('percentFrom[]')>-1) {
                   // blank value check 
                   tname = obj[i].id; 
                   if(trim(obj[i].value) == "") {
                     messageBox ("Grade Point From field cannot be left blank");
                     eval("document.getElementById('"+tname+"').className='inputboxRed'"); 
                     eval("document.getElementById('"+tname+"').focus()");    
                     return false;             
                   }
                   
                   // Integer Value Checks updated
                   if(!isDecimal(trim(obj[i].value),10)) {
                     messageBox ("Enter numeric value for Grade Point From (0 to 100)");
                     eval("document.getElementById('"+tname+"').className='inputboxRed'"); 
                     eval("document.getElementById('"+tname+"').focus()");    
                     return false;
                   }
                   
                   // Ranges Checks 
                   if(isDecimal(obj[i].value,10) < 0 || isDecimal(obj[i].value,10) >100 ) {
                     messageBox ("Enter numeric value for Grade Point From (0 to 100)");
                     eval("document.getElementById('"+tname+"').className='inputboxRed'"); 
                     eval("document.getElementById('"+tname+"').focus()");    
                     return false;
                   }
                   percentFromArray[x]=parseInt(obj[i].value,10);
                   posArray[x]=i;
                   x++;
                }
                
                if(obj[i].type.toUpperCase()=='TEXT' && obj[i].name.indexOf('percentTo[]')>-1) {
                   // blank value check 
                   tname = obj[i].id; 
                   if(trim(obj[i].value) == "") {
                     messageBox ("Grade Point To field cannot be left blank");
                     eval("document.getElementById('"+tname+"').className='inputboxRed'"); 
                     eval("document.getElementById('"+tname+"').focus()");    
                     return false;             
                   }
                   
                   // Integer Value Checks updated
                   if(!isDecimal(trim(obj[i].value))) {
                     messageBox ("Enter numeric value for Grade Point To (0 to 100)");
                     eval("document.getElementById('"+tname+"').className='inputboxRed'"); 
                     eval("document.getElementById('"+tname+"').focus()");    
                     return false;
                   }
                   
                   // Ranges Checks 
                   if(isDecimal(obj[i].value,10) < 0 || isDecimal(obj[i].value,10) >100 ) {
                     messageBox ("Enter numeric value for Grade Point To (0 to 100)");
                     eval("document.getElementById('"+tname+"').className='inputboxRed'"); 
                     eval("document.getElementById('"+tname+"').focus()");    
                     return false;
                   }
                   percentToArray[y]=parseInt(obj[i].value,10);
                   y++;
                }
                
                if(obj[i].type.toUpperCase()=='TEXT' && obj[i].name.indexOf('grade[]')>-1) {     
                    tname = obj[i].id; 
                    if(obj[i].type.toUpperCase()=='TEXT' && obj[i].value=='') { 
                       messageBox ("Grade field cannot be left blank");  
                       eval("document.getElementById('"+tname+"').className='inputboxRed'"); 
                       eval("document.getElementById('"+tname+"').focus()");    
                       return false;   
                    }
                    if(checkDuplicateGrade(obj[i].value)==0) {
                       messageBox ("Grade already define");  
                       eval("document.getElementById('"+tname+"').className='inputboxRed'"); 
                       eval("document.getElementById('"+tname+"').focus()");    
                       return false;
                    }
                }
                
                if(obj[i].type.toUpperCase()=='TEXT' && obj[i].name.indexOf('points[]')>-1) {     
                    tname = obj[i].id; 
                    if(obj[i].type.toUpperCase()=='TEXT' && obj[i].value=='') {
                       // blank value check 
                       if(trim(obj[i].value) == "") {
                         messageBox ("Grade Points field cannot be left blank");  
                         eval("document.getElementById('"+tname+"').className='inputboxRed'"); 
                         eval("document.getElementById('"+tname+"').focus()");    
                         return false;             
                       }
                       
                       // Decimal Value Checks updated
                       if(!isDecimal(trim(obj[i].value))) {
                         messageBox ("Enter decimal value for Grade Points (0 to 100)"); 
                         eval("document.getElementById('"+tname+"').className='inputboxRed'"); 
                         eval("document.getElementById('"+tname+"').focus()");    
                         return false;
                       }
                    }
                }
            }
            
            // Wrong Data Validation Checks
            for(var i=0;i<x;i++) { 
              ifrom = percentFromArray[i];
              ito   = percentToArray[i];
              if(ifrom > ito) {
                 messageBox("Grade Point From cannot be greater than Grade Point To");   
                 for(var z=0;z<total;z++) {
                    if(obj[z].type.toUpperCase()=='TEXT' && obj[z].name.indexOf('percentFrom[]')>-1) {
                        if(z==posArray[i]) {
                          tname = obj[z].id;
                          eval("document.getElementById('"+tname+"').className='inputboxRed'"); 
                          eval("document.getElementById('"+tname+"').focus()");    
                          return false;
                        }
                    }
                 }
                 return false;
              }
              for(var k= i+1; k <x; k++) {  
                jfrom = percentFromArray[k];
                jto   = percentToArray[k];
                for(var j=ifrom; j<=ito; j++) {  
                   if(j == jfrom || j == jto) {
                      messageBox("Wrong data input");   
                      for(var z=0;z<total;z++) {
                        if(obj[z].type.toUpperCase()=='TEXT' && obj[z].name.indexOf('percentFrom[]')>-1) {
                            if(z==posArray[k]) {
                              tname = obj[z].id;
                              eval("document.getElementById('"+tname+"').className='inputboxRed'"); 
                              eval("document.getElementById('"+tname+"').focus()");    
                              return false;
                            }
                        }
                      }
                      return false;
                   }
                } 
              }
            }
    }
    addFinalGrade();
    return false;
}


function checkDuplicateGrade(value) {
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

function resetValues() {
    document.getElementById('allDetailsForm').reset();
}

function hideValue() {
    document.getElementById("totSeats").innerHTML = "<?php echo NOT_APPLICABLE_STRING; ?>"; 
    document.getElementById('trAttendance').style.display='none';
    document.getElementById('results').style.display='none';
    document.getElementById('results11').style.display='none';
    cleanUpTable();   
}

function showFinalGrade() {
   
   var  url = '<?php echo HTTP_LIB_PATH;?>/AssignFinalGrade/ajaxAssignFinalGradeGetValues.php';
   var showDelete = '';  
   document.getElementById('trAttendance').style.display='';    
   cleanUpTable();   
   document.getElementById('finalGradeId').value = '';    
   
   new Ajax.Request(url,
   {
     method:'post',
     parameters: {id: 0 },
     onCreate: function () {
       showWaitDialog(true);
     },
     onSuccess: function(transport){
           hideWaitDialog(true);
            var j = eval('('+trim(transport.responseText)+')');   
            var len=j.finalGradeArr.length;    
            
            if(len>0) {
              document.getElementById('trAttendance').style.display='';       
              for(var i=0;i<len;i++) {
                addOneRow(1);
                document.getElementById('finalGradeId').value = '1'; 
                var varFirst = i+1;
                id = "marksFrom"+varFirst;
                eval("document.getElementById(id).value = j['finalGradeArr'][i]['minval']");    
                id = "marksTo"+varFirst;
                eval("document.getElementById(id).value = j['finalGradeArr'][i]['maxval']");
                id = "grade"+varFirst;
                eval("document.getElementById(id).value = j['finalGradeArr'][i]['grade']");
                id = "points"+varFirst;
                eval("document.getElementById(id).value = j['finalGradeArr'][i]['point']");
              }
           } 
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function addFinalGrade() {
  
   var url = '<?php echo HTTP_LIB_PATH;?>/AssignFinalGrade/ajaxAssignFinalGradeAdd.php';
   
   params = generateQueryString('allDetailsForm');
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     onCreate: function () {
        showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText) || trim("<?php echo FINAL_GRADE_DELETE_SUCCESSFULLY;?>") == trim(transport.responseText) || trim("<?php echo FINAL_GRADE_UPDATE_SUCCESSFULLY;?>") == trim(transport.responseText)) {
            messageBox(trim(transport.responseText));  
            cleanUpTable();
            resetValues();
            document.getElementById('trAttendance').style.display='';
            showFinalGrade();
            return false;
        }
        else {
           var ret=trim(transport.responseText).split('!~~!');
           var j0 = trim(ret[0]);
           var j1 = trim(ret[1]);  
           messageBox(j0);
           if(j1!='') {
             id = "totalSeat"+j1;
             eval("document.getElementById('"+id+"').className='inputboxRed'"); 
             eval("document.getElementById('"+id+"').focus()");
           } 
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

/* function to print block report*/
function printReport() {
  
    path='<?php echo UI_HTTP_PATH;?>/listAssignFinalGradePrint.php';
    window.open(path,"AssignFinalGrade","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
   
    window.location='listAssignFinalGradeCSV.php';
}


window.onload=function() {
   document.getElementById('finalGradeId').value = '';    
   showFinalGrade();
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/AssignFinalGrade/listAssignFinalGradeContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>