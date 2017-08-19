<?php 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ClassFineSetUp');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Class Fine Setup </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">



var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                               new Array('className','Class','width="25%"','',true),
                               new Array('fromDate','From Date','width="12%" align="center"','align="center"',true),
                               new Array('toDate','To Date','width="12%" align="center"','align="center"',true), 
                               new Array('feeFineTypeId','Fine Type','width="12%" align="left"','align="left"',true),  
                               new Array('chargesFormat','Charges Format','width="12%" align="center"','align="center"',true),
                               new Array('charges','Amount','width="12%" align="right"','align="right"',true), 
                               new Array('action1','Action','width="10%"','align="center"',false)
				               );



recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Fee/FineSetUp/ajaxGetFineDetail.php';
searchFormName = 'allDetailsForm';  // name of the form which will be used for search
addFormName    = 'AddFeeHead';   
editFormName   = 'EditFeeHead';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 200; // add/edit form height
deleteFunction = 'return deleteFineDetail';
divResultName  = 'results';
page=1; //default page
sortField = 'fromDate';
sortOrderBy    = 'ASC';
valShow = '1';
// ajax search results ---end ///
 
 var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button       
//This function Displays Div Window

function editWindow(id,dv,w,h) { 
    displayWindow(dv,w,h);
    populateValues(id);   
}

function deleteFineDetail(id) {
	
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Fee/FineSetUp/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeFineId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
              
                     hideWaitDialog(true);
                   //  messageBox(trim(transport.responseText));
                     if("Data deleted successfully"==trim(transport.responseText)) {
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                       return false;
                     }
                     else {
                       messageBox(trim(transport.responseText));
                     } 
             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         } 
}


var dtArray=new Array();  
    var dtInstitute='';

        function getSearchValue(str) {

          if(document.allDetailsForm.activeRadio[0].checked==true) {  
             isActive=1; 
          }
          else if(document.allDetailsForm.activeRadio[1].checked==true) {  
             isActive=2;
          }
          else {
             isActive=3;
          }
            
          dtArray.splice(0,dtArray.length); //empty the array  
          
          if(dtInstitute=='') { 
            document.allDetailsForm.instituteId.length = null; 
            addOption(document.getElementById('instituteId'), '', 'All');
          }
          
          ttStr = str;  
          if(ttStr=='' || ttStr =='Degree') {
            document.allDetailsForm.degreeId.length = null; 
            addOption(document.getElementById('degreeId'), '', 'All');
            ttStr='';         
          }
          
          if(ttStr=='' || ttStr =='Branch') {
             document.allDetailsForm.branchId.length = null;  
             addOption(document.getElementById('branchId'), '', 'All');
             ttStr='';         
          }
          
          if(ttStr=='' || ttStr =='Batch') {
            document.allDetailsForm.batchId.length = null;  
            addOption(document.getElementById('batchId'), '', 'All');
            ttStr='';         
          }
          
          if(ttStr=='' || ttStr =='Class') {
           document.getElementById('fineClassId').length = null; 
           //addOption(document.getElementById('fineClassId'), '', 'Select');
           ttStr='';         
          }
               
          var len= document.getElementById('hiddenClassId').options.length;
          var t=document.getElementById('hiddenClassId');
          if(len>0) {
              for(k=1;k<len;k++) { 
                 if(t.options[k].value != '' ) { 
                     retId = (t.options[k].value).split('!!~!!~!!');
                     retName = (t.options[k].text).split('!!~!!~!!');
                     // instituteCode, className, degreeCode, batchName, branchCode, isActive
                     ttStr = str;    
                     
                     if(retId[5]==isActive) {
                         // Fetch the Institute
                         if(dtInstitute=='') { 
                            if(checkDuplicate(retName[0]+"institute")!=0) {
                              addOption(document.getElementById('instituteId'), retId[0],  retName[0]);
                            }
                         }
                         
                         // Fetch the degree
                         if(ttStr=='' || ttStr =='Degree') {
                             temp1='';
                             temp2='';
                             if(document.getElementById('instituteId').value!='') {
                               temp1 = document.getElementById('instituteId').value+"~";
                               temp2 = retId[0]+"~";  
                             }
                             
                             ttName = retName[2]+' ('+retName[0]+')';  
                             ttId = retId[2]+'~'+retId[0]+'degree';   
                             if(temp1=='') {
                               if(checkDuplicate(ttId)!=0) {
                                 addOption(document.getElementById('degreeId'), retId[2],  ttName);
                               }
                             }
                             else {
                               if(temp1==temp2) {
                                 if(checkDuplicate(ttId)!=0) { 
                                   addOption(document.getElementById('degreeId'), retId[2],  ttName);
                                 }  
                               }
                             }
                             ttStr = '';
                         }       
                         
                         // Fetch the Branch
                         if(ttStr=='' || ttStr =='Branch') {
                             temp1='';
                             temp2='';
                             if(document.getElementById('instituteId').value!='') {
                               temp1 += document.getElementById('instituteId').value+"~";
                               temp2 += retId[0]+"~";  
                             }
                             
                             if(document.getElementById('degreeId').value!='') {
                               temp1 += document.getElementById('degreeId').value+"~";
                               temp2 += retId[2]+"~";  
                             }
                             
                             ttName = retName[4]+' ('+retName[0]+')';  
                             ttId = retId[4]+'~'+retId[0]+'branch';   
                             if(temp1=='') {
                               if(checkDuplicate(ttId)!=0) {
                                 addOption(document.getElementById('branchId'), retId[4],  ttName);
                               }
                             }
                             else {
                               if(temp1==temp2) {
                                 if(checkDuplicate(ttId)!=0) { 
                                    addOption(document.getElementById('branchId'), retId[4],  ttName);
                                 }  
                               }
                             }
                             ttStr = '';
                         }
                         
                          // Fetch the Batch
                         if(ttStr=='' || ttStr =='Batch') {
                            temp1='';
                            temp2='';

                            if(document.getElementById('instituteId').value!='') {
                              temp1 += document.getElementById('instituteId').value+"~";
                              temp2 += retId[0]+"~";  
                            }
                            
                            if(document.getElementById('degreeId').value!='') {
                              temp1 += document.getElementById('degreeId').value+"~";
                              temp2 += retId[2]+"~";  
                            }
                            if(document.getElementById('branchId').value!='') {
                              temp1 += document.getElementById('branchId').value+"~";
                              temp2 += retId[4]+"~";
                            }

                            ttName = retName[3]+' ('+retName[0]+')';  
                            ttId = retId[3]+'~'+retId[0]+'batch';   
                            if(temp1=='') {
                               if(checkDuplicate(ttId)!=0) { 
                                 addOption(document.getElementById('batchId'), retId[3],  ttName);
                               }
                            }
                            else {
                               if(temp1==temp2) {
                                  if(checkDuplicate(ttId)!=0) {   
                                    addOption(document.getElementById('batchId'), retId[3],  ttName);
                                  }
                               }  
                            }
                            ttStr = '';
                         }
                         
                         // Fetch the Class
                         if(ttStr=='' || ttStr =='Class') { 
                              temp1='';
                              temp2='';
                              if(document.getElementById('instituteId').value!='') {
                                temp1 += document.getElementById('instituteId').value+"~";
                                temp2 += retId[0]+"~";  
                              }

                              if(document.getElementById('degreeId').value!='') {
                                temp1 += document.getElementById('degreeId').value+"~";
                                temp2 += retId[2]+"~";  
                              }
                              if(document.getElementById('branchId').value!='') {
                                temp1 += document.getElementById('branchId').value+"~";
                                temp2 += retId[4]+"~";
                              }
                              if(document.getElementById('batchId').value!='') {
                                 temp1 += document.getElementById('batchId').value+"~";
                                 temp2 += retId[3]+"~";
                              }
                              
                              ttName = retName[1]+' ('+retName[0]+') '+retName[5];  
                              ttId = retId[1]+'~'+retId[0]+'class';   
                              if(temp1=='') {
                                 if(checkDuplicate(ttId)!=0) { 
                                   addOption(document.getElementById('fineClassId'), retId[1],  ttName);
                                 }
                              }
                              else {
                                 if(temp1==temp2) {
                                   if(checkDuplicate(ttId)!=0) {  
                                     addOption(document.getElementById('fineClassId'), retId[1],  ttName);
                                   }
                                 }  
                              }  
                              ttStr = '';
                         }
                     }  // Is Active
                  } // end If
               } // end for loop
           } // end if condition
          
           dtInstitute='1';
    }


window.onload = function () {
    getSearchValue('');
  
}




function addForm(frm) { 

    if(document.getElementById('fineTypeId').value =='') {
      messageBox("Select Fine Type");
      document.getElementById('fineTypeId').focus();
      return false;
    }
	if(document.getElementById('fineClassId').value =='') {
      messageBox("Select Class");
      document.getElementById('fineClassId').focus();
      return false;
    }

	
    var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FineSetUp/ajaxFineSetUpAdd.php';

            toDateCheck = '';
            
            formx = document.allDetailsForm;
            var obj=formx.getElementsByTagName('INPUT');
            var total=obj.length;
            for(var i=0;i<total;i++) {
                if(obj[i].type.toUpperCase()=='HIDDEN' && obj[i].name.indexOf('idNos[]')>-1) {
                   // blank value check 
                   id =obj[i].value;

                   if(eval("document.getElementById('fromDate"+id+"').value")=='') {
                     messageBox ("Select From Date");  
                     eval("document.getElementById('fromDate"+id+"').focus()");
                     return false;             
                   }
                   
                   if(eval("document.getElementById('toDate"+id+"').value")=='' ) {
                     messageBox ("Select To Date");  
                     eval("document.getElementById('toDate"+id+"').focus()");
                     return false;             
                   }

                   if(!dateDifference(eval("document.getElementById('fromDate"+id+"').value"),eval("document.getElementById('toDate"+id+"').value"),'-') ) {
                      messageBox ("<?php echo DATE_VALIDATION;?>");  
                      eval("document.getElementById('fromDate"+id+"').focus()");
                      return false;             
                   }
                   
                   if(toDateCheck=='') {
                      toDateCheck = eval("document.getElementById('toDate"+id+"').value"); 
                   }
                   else {
                       if(!dateDifference(toDateCheck,eval("document.getElementById('fromDate"+id+"').value"),'-') ) {
                          messageBox ("From Date should not be less than previous to date");  
                          eval("document.getElementById('fromDate"+id+"').focus()");
                          return false;             
                       }   
                   }
                   
	               if(eval("document.getElementById('chargesFormat"+id+"').value")=='' ) {
                     messageBox ("Select Charges Format");  
                     eval("document.getElementById('chargesFormat"+id+"').focus()");
                     return false;             
                   }
	               if(trim(eval("document.getElementById('charges"+id+"').value"))=='' ) {
                     messageBox ("Enter Amount");  
                     eval("document.getElementById('charges"+id+"').focus()");
                     return false;             
                   }

		           if(!isDecimal(trim(eval("document.getElementById('charges"+id+"').value")))) {                          
                     messageBox ("Enter numeric value for amount");
                     eval("document.getElementById('charges"+id+"').focus()");  
                     return false;
                   }
 	              }
              }

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
                    if(trim(transport.responseText)!='') {
                        messageBox(trim(transport.responseText));  
                        cleanUpTable();
                        resetValues();
		           sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);    
                        return false;
                    }
                 },
                 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
               });
}

function showPreviousFineDetail(){
  
      
   if(document.getElementById('fineClassId').value==""){
      messageBox("<?php echo SELECT_CLASS; ?>");
      document.getElementById('fineClassId').focus();
      return false;
   } 

    if(document.getElementById('fineTypeId').value =='') {
       messageBox("Select Fine Type");
        document.getElementById('fineTypeId').focus();
     return false;
    }

	 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 

}

function getShowDetail() {
	
   document.getElementById("addFine").style.display='';
   document.getElementById("saveFineDetail").style.display='';			
   document.getElementById("showFine").style.display='none';
   document.getElementById("lblMsg").innerHTML="Please Click to Show Advance Search";
   if(valShow==0) {
	document.getElementById("addFine").style.display='none';
	document.getElementById("saveFineDetail").style.display='none';
	document.getElementById("showFine").style.display='';
	document.getElementById("lblMsg").innerHTML="Please Click to Add Fine";
	valShow=1;
   }
   else {
     valShow=0;  
   }
}


function printReport() {
   params = generateQueryString('allDetailsForm');
   path='<?php echo UI_HTTP_PATH;?>/Fee/listFineDetailPrintReport.php?searchbox='+params+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
   window.open(path,"FineDetailReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    params = generateQueryString('allDetailsForm');
    path='<?php echo UI_HTTP_PATH;?>/Fee/listFineDetailReportCSV.php?searchbox='+params+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.location = path;
}


function resetValues() {
	form = document.allDetailsForm;
	document.getElementById('allDetailsForm').reset();
	//document.getElementById('classId').selectedIndex=0;  
	//document.getElementById('classId').focus();
}


    function checkDuplicate(value) {
        
        var ii= dtArray.length;
        var fl=1;
        for(var kk=0;kk<ii;kk++){
          if(dtArray[kk]==value){
            fl=0;
            break;
          }  
        }
        if(fl==1){
          dtArray.push(value);
        } 
        
        return fl;
    }
    


 var isMozilla = (document.all) ? 0 : 1;

function addDetailRows(value){
	
	 var tbl=document.getElementById('anyid');
	 var tbody = document.getElementById('anyidBody');
	 //var tblB    = document.createElement("tbody");
	 if(!isInteger(value)){
		return false;
	 }
	 
	 if(resourceAddCnt>0){     //if user reenter no of rows
	  //if(confirm('Previous Data Will Be Erased.\n Are You Sure ?')){
		   cleanUpTable();
	  //}
	  //else{
		//  return false;
	  //}
	} 
	resourceAddCnt=parseInt(value); 
	createRows(0,resourceAddCnt,0);
}


    //for deleting a row from the table 
    function deleteRow(value){
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody');
      
      var tr=document.getElementById('row'+rval[0]);
      tbody1.removeChild(tr);
     resourceAddCnt= resourceAddCnt -1 ; 
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
	  //document.feeForm.rowCnt.value=parseInt(document.feeForm.rowCnt.value)-1;
    } 


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

    //to clean up table rows
    function cleanUpTable(){ 
       var tbody = document.getElementById('anyidBody');
       for(var k=0;k<=resourceAddCnt;k++){
             try{
              tbody.removeChild(document.getElementById('row'+k));
             }
             catch(e){
             }
          }
          resourceAddCnt=0;  
    }

    var bgclass='';

    //create dynamic rows 
    
    //function createRows(start,rowCnt,optionData,sectionData,roomData){
var serverDate="<?php echo date('Y-m-d');?>";
function createRows(start,rowCnt){
	   
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
	  var cell6=document.createElement('td');
	  //var cell7=document.createElement('td');
	  
	  cell1.setAttribute('align','left'); 
	  //cell2.setAttribute('align','left'); 
	  //cell2.setAttribute('style','padding: 5px 5px 5px 5px;width:320px');    
	  cell2.setAttribute('align','center'); 
	  cell3.setAttribute('align','center');
      cell4.setAttribute('style','padding-right:20px;');
      cell4.setAttribute('align','left'); 
	  cell5.setAttribute('align','left');
	  cell6.setAttribute('align','center');
	
	  if(start==0){
		var txt0=document.createTextNode(start+i+1);
	  }
	  else{
		var txt0=document.createTextNode(start+i);
	  }
      
      var idStore=document.createElement('input');        
      
 	
	  var txt2=document.createElement('select');
  	  var txt3=document.createElement('select');
	  var txt4=document.createElement('select');
	  var txt5=document.createElement('input');
	  var txt6=document.createElement('a');
      
	 
      // To store table ids 
      idStore.setAttribute('type','hidden'); 
      idStore.setAttribute('name','idNos[]'); 
      idStore.setAttribute('value',parseInt(start+i,10));
    	
	

	  txt4.setAttribute('id','chargesFormat'+parseInt(start+i,10));
	  txt4.setAttribute('name','chargesFormat[]');
      txt4.setAttribute('style','width:120px;');    
	  txt4.className='htmlElement';
	
           
	  txt5.setAttribute('id','charges'+parseInt(start+i,10));
	  txt5.setAttribute('name','charges[]'); 
	  txt5.className='inputbox';
      txt5.setAttribute('style','width:150px');    
	  txt5.value='';

	       
	  txt6.setAttribute('id','rd');
	  txt6.className='htmlElement';  
	  txt6.setAttribute('title','Delete');       
	  
	  txt6.innerHTML='X';
	  txt6.style.cursor='pointer';
	  

	  txt6.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff    
	  
	  cell1.appendChild(txt0);
	  cell1.appendChild(idStore);

	  
	 //cell2.innerHtml ='<select multiple="multiple" name="classId[]" id="classId" size="5" class="inputBox" style="width: 180px;"> </select>';
	    cell2.innerHTML='<input type="text" id="fromDate'+parseInt(start+i,10)+'" name="fromDate[]" class="inputBox" readonly="true" value="'+serverDate+'" size="8" />';
	  cell2.innerHTML +="<input type=\"image\" id=\"fromDate\" name=\"fromDate\" title=\"Select Date\" src=\""+imagePathURL+"/calendar.gif\"  onClick=\"return showCalendar('fromDate"+parseInt(start+i,10)+"','%Y-%m-%d', '24', true);\">";
	   cell3.innerHTML='<input type="text" id="toDate'+parseInt(start+i,10)+'" name="toDate[]" class="inputBox" readonly="true" value="'+serverDate+'" size="8" />';
	  cell3.innerHTML +="<input type=\"image\" id=\"toDate\" name=\"toDate\" title=\"Select Date\" src=\""+imagePathURL+"/calendar.gif\"  onClick=\"return showCalendar('toDate"+parseInt(start+i,10)+"','%Y-%m-%d', '24', true);\">";
	  cell4.appendChild(txt4);
	  cell5.appendChild(txt5); 
	   
	  cell6.appendChild(txt6);
			 
	  tr.appendChild(cell1);
	  tr.appendChild(cell2);
	  tr.appendChild(cell3);
	  tr.appendChild(cell4);
	  tr.appendChild(cell5);
	  tr.appendChild(cell6);
	 //  tr.appendChild(cell7);
	  
	  bgclass=(bgclass=='row0'? 'row1' : 'row0');
	  tr.className=bgclass;
	  
      
	  tbody.appendChild(tr); 
	  var tt='chargesFormat'+parseInt(start+i,10) ;
	  addOption(document.getElementById(tt), "","Select"); 
	  addOption(document.getElementById(tt), "1","Daily"); 
	  addOption(document.getElementById(tt), "2","Fixed");
/*
	  var ttClassName='classId'+parseInt(start+i,10) ;	  	  
      var len= document.getElementById('fineClassId').options.length;
      var t=document.getElementById('fineClassId');
      for(yy=1;yy<len;yy++) {
        addOption(document.getElementById(ttClassName),t.options[yy].value,  t.options[yy].text );
      }*/
  } 
  tbl.appendChild(tbody);
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fee/FineSetUp/listClassFineSetUpContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
<SCRIPT LANGUAGE="JavaScript">
  
       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
   
</SCRIPT>
</html>



