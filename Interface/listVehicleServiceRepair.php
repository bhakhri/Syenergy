<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF BUSSTOP ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (10.06.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleServiceRepair');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Fuel/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Service cum Repair </title>
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
    new Array('busNo','Registration No.','width="10%"','',true),
    new Array('serviceDate','Service Date','width="8%"','align="center"',true),
    new Array('kmReading','KM Reading','width="10%"','align="right"',true),
    new Array('billNo','Bill/Ticket No.','width="10%"','align="left"',true),
    new Array('servicedAt','Serviced At','width="7%"','align="left"',true),
    new Array('action','Action','width="2%"','align="center"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/VehicleServiceRepair/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddServiceRepair';   
editFormName   = 'EditServiceRepair';
winLayerWidth  = 360; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteServiceRepair';
divResultName  = 'results';
page=1; //default page
sortField = 'busNo';
sortOrderBy    = 'ASC';
var topPos = 0;
var leftPos = 0;
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
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);
}

//-------------------------------------------------------
//THIS FUNCTIPN IS FOR HELP

//Author : Gagan Gill
//Created on : 15 Nov 2010
//--------------------------------------------------------

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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d'); ?>";

function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
								  new Array("vehicleType","<?php echo SELECT_VEHICLE_TYPE; ?>"),
								  new Array("busNo","<?php echo SELECT_BUS_NAME; ?>"),
								  new Array("busService","<?php echo SELECT_SERVICE_TYPE; ?>"),
								  new Array("readingEntry","<?php echo ENTER_READING_ENTRY; ?>"),
								  new Array("billNo","<?php echo ENTER_BILL_NO; ?>"),
								  new Array("servicedAt","<?php echo ENTER_SERVICED_AT; ?>")
								);

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            if(fieldsArray[i][0]=="readingEntry" && (!isInteger(eval("frm."+(fieldsArray[i][0])+".value")) || eval("frm."+(fieldsArray[i][0])+".value") < 0 ))  {
                messageBox("<?php echo INVALID_READING; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            } 
            
            /*if(fieldsArray[i][0]=="servicedAt" && (!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value")) || eval("frm."+(fieldsArray[i][0])+".value") < 0 ))  {
                messageBox("<?php echo INVALID_SERVICED_AT; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
          }*/
         
        }
    }
    
    if(act=='Add') {
		var d=new Date();
		var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));

		if(!dateDifference(document.addVehicleServiceRepair.serviceDate.value,cdate,"-")) {
		   messageBox("<?php echo SERVICE_DATE_VALIDATION; ?>");
		   document.addVehicleServiceRepair.serviceDate.focus();
		   return false;
		 }


        addVehicleServiceRepair();
        return false;
    }
    else if(act=='Edit') {
		var d=new Date();
		var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));

		if(!dateDifference(document.editVehicleServiceRepair.serviceDate1.value,cdate,"-")) {
		   messageBox("<?php echo SERVICE_DATE_VALIDATION; ?>");
		   document.editVehicleServiceRepair.serviceDate1.focus();
		   return false;
		 }
        editVehicleServiceRepair();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A BUS
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addVehicleServiceRepair() {
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleServiceRepair/ajaxInitAdd.php';
		 var pars = generateQueryString('addVehicleServiceRepair');
         new Ajax.Request(url,
           {
             method:'post',
             parameters: pars,
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
                             hiddenFloatingDiv('AddServiceRepair');
							 location.reload();
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A BUSSTOP
//  id=busStopId
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteServiceRepair(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleServiceRepair/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {serviceRepairId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "addFuel" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
	document.addVehicleServiceRepair.reset();
	document.getElementById('getServiceNo').style.display = 'none';
	cleanUpTable();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A BUSSTOP
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editVehicleServiceRepair() {
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleServiceRepair/ajaxInitEdit.php';
		 var pars = generateQueryString('editVehicleServiceRepair');
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: pars,
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {  
                         hiddenFloatingDiv('EditServiceRepair');
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


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "SERVICE DETAIL" DIV
//
//Author : Jaineesh
// Created on : (10.06.10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getServiceDetailData() {
	//hideResults();
	 url = '<?php echo HTTP_LIB_PATH;?>/VehicleServiceRepair/ajaxGetServiceValues.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 asynchronous:false,
		 parameters: {},
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			hideWaitDialog(true);
			var j = trim(transport.responseText);
			// now select the value
			//document.getElementById('marksDiv').innerHTML = 'block';
			document.getElementById('serviceDetail').innerHTML = trim(transport.responseText);
		},
		  onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "SERVICE DETAIL" DIV
//
//Author : Jaineesh
// Created on : (10.06.10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getEditServiceDetailData() {
	//hideResults();
	 url = '<?php echo HTTP_LIB_PATH;?>/VehicleServiceRepair/ajaxGetServiceValues.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 asynchronous:false,
		 parameters: {},
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			hideWaitDialog(true);
			var j = trim(transport.responseText);
			// now select the value
			//document.getElementById('marksDiv').innerHTML = 'block';
			document.getElementById('serviceDetail1').innerHTML = trim(transport.responseText);
		},
		  onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}



//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "SERVICE DETAIL" DIV
//
//Author : Jaineesh
// Created on : (10.06.10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getServiceDetail(id) {
	//hideResults();
	form = document.addVehicleServiceRepair;
	if (form.busNo.value != '') {
		if (id == 1) {
			document.getElementById('getServiceNo').style.display='';

		//form.getServiceNo.style.display='';
			var url = '<?php echo HTTP_LIB_PATH;?>/VehicleServiceRepair/getVehicleService.php';

			new Ajax.Request(url,
			{
				method:'post',
				parameters: {serviceType:id,
				busId: document.addVehicleServiceRepair.busNo.value},
				onCreate: function(){
				showWaitDialog();
			},
				onSuccess: function(transport){
				hideWaitDialog(true);
				if(trim(transport.responseText)==0){
					messageBox("<?php echo FREE_SERVICE_FINISHED;?>");
					hiddenFloatingDiv('AddServiceRepair'); 
					return false;

				// exit();
				sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
			}
				var j = eval('(' + transport.responseText + ')');
				len = j.length;
				form.serviceNo.length = null;

				if (len == "") {
				addOption(form.serviceNo, '', 'Select');
				return false;
			}

			for(i=0;i<len;i++) {
				addOption(form.serviceNo, j[i].serviceId, j[i].serviceNo);
			}
			// now select the value
			//form.subject.value = j[0].subjectId;
			//form.room.value = hostelRoomId;
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
			});
		}
		else {
			document.getElementById('getServiceNo').style.display='none';
		}
	}
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getVehicleDetails() {
	form = document.addVehicleServiceRepair;
	form.busService.value = "";
	var url = '<?php echo HTTP_LIB_PATH;?>/VehicleTyre/getVehicleNumbers.php';
	var pars = 'vehicleTypeId='+form.vehicleType.value;
	if (form.vehicleType.value=='') {
		form.busNo.length = null;
		addOption(form.busNo, '', 'Select');
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
			
			if(j==0) {
				form.busNo.length = null;
				addOption(form.busNo, '', 'Select');
				return false;
			}
			len = j.length;
			form.busNo.length = null;
			addOption(form.busNo, '', 'Select');
			for(i=0;i<len;i++) {
				addOption(form.busNo, j[i].busId, j[i].busNo);
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

var bgclass='';
var resourceRepairAddCnt=0;
// check browser
var isMozilla = (document.all) ? 0 : 1;

//for deleting a row from the table 
    function deleteRepairRow(value){
		//alert(value);
    var temp=resourceRepairAddCnt;    
	try {
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody1_add');
      if(isMozilla){
          if((tbody1.childNodes.length-3)==0){
              resourceRepairAddCnt=0;
              /*if(!checkExperienceRowExisting()){
                  resourceExpAddCnt=temp;
                  return false;
              }*/
          }
      }
      else{
          if((tbody1.childNodes.length-2)==0){
              resourceRepairAddCnt=0;
             /* if(!checkExperienceRowExisting()){
                  resourceExpAddCnt=temp;
                  return false;
              }*/
          }
      }
	  
      var tr=document.getElementById('row_exp'+rval[0]);
      tbody1.removeChild(tr);
	  /*
	  reCalculate1();
      
	  if(isMozilla){
          if((tbody1.childNodes.length-3)==0){
              resourceExpAddCnt=0;
			  document.getElementById('trExperience').style.display = 'none';
			  //checkExperienceRowExisting();
          }
      }
      else{
		  //alert(tbody1.childNodes.length);
          if((tbody1.childNodes.length-1)==0){
              resourceExpAddCnt=0;
			  document.getElementById('trExperience').style.display = 'none';
			  //checkExperienceRowExisting();
          }
      }*/
	}
	catch (e) {
	}
   }

   function deleteEditRepairRow(value){
    var temp=resourceRepairAddCnt;    
	try {
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody1_edit');
      if(isMozilla){
          if((tbody1.childNodes.length-3)==0){
              resourceRepairAddCnt=0;
              /*if(!checkExperienceRowExisting()){
                  resourceExpAddCnt=temp;
                  return false;
              }*/
          }
      }
      else{
          if((tbody1.childNodes.length-2)==0){
              resourceRepairAddCnt=0;
             /* if(!checkExperienceRowExisting()){
                  resourceExpAddCnt=temp;
                  return false;
              }*/
          }
      }
	  
      var tr=document.getElementById('row_exp'+rval[0]);
      tbody1.removeChild(tr);
	  /*
	  reCalculate1();
      
	  if(isMozilla){
          if((tbody1.childNodes.length-3)==0){
              resourceExpAddCnt=0;
			  document.getElementById('trExperience').style.display = 'none';
			  //checkExperienceRowExisting();
          }
      }
      else{
		  //alert(tbody1.childNodes.length);
          if((tbody1.childNodes.length-1)==0){
              resourceExpAddCnt=0;
			  document.getElementById('trExperience').style.display = 'none';
			  //checkExperienceRowExisting();
          }
      }*/
	}
	catch (e) {
	}
   }

   function cleanUpTable(){
       var tbody = document.getElementById('anyidBody1_add');
       for(var k=0;k<=resourceRepairAddCnt;k++){
             try{
              tbody.removeChild(document.getElementById('row_exp'+k));
             }
             catch(e){
                 //alert(k);  // to take care of deletion problem
             }
          }  
    }

	function cleanUpEditTable(){
       var tbody = document.getElementById('anyidBody1_edit');
	   //alert(tbody);
       for(var k=0;k<=resourceRepairAddCnt;k++){
             try{
              tbody.removeChild(document.getElementById('row_exp'+k));
             }
             catch(e){
				 //alert(e);
                 //alert(k);  // to take care of deletion problem
             }
          }  
    }


	function addVehicleRepairOneRow(cnt,mode) {
	
        if(cnt=='')
        cnt=1;
        if(isMozilla){
             if(document.getElementById('anyidBody1_'+mode).childNodes.length <= 3){
                resourceRepairAddCnt=0; 
             }       
        }
        else{
             if(document.getElementById('anyidBody1_'+mode).childNodes.length <= 1){
               resourceRepairAddCnt=0;  
             }       
        }
		
        resourceRepairAddCnt++; 
        createRepairRows(resourceRepairAddCnt,cnt,mode);
    }


	

	function createRepairRows(start,rowCnt,mode){
           // alert(start+'  '+rowCnt);
		 var serverDate = "<?php echo date('Y-m-d') ?>";
         var tbl=document.getElementById('anyid1_'+mode);
         var tbody = document.getElementById('anyidBody1_'+mode);
         
         for(var i=0;i<rowCnt;i++){
          var tr=document.createElement('tr');
          tr.setAttribute('id','row_exp'+parseInt(start+i,10));
          
          var cell1=document.createElement('td');
		  cell1.setAttribute('align','right');
		  cell1.name='srNo1';
          var cell2=document.createElement('td'); 
          var cell3=document.createElement('td'); 
          var cell4=document.createElement('td');
		  var cell5=document.createElement('td');
          
          cell1.setAttribute('align','left');
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
          var txt1=document.createElement('select');
		  var txt2=document.createElement('input');
		  var txt3=document.createElement('input');
          var txt4=document.createElement('a');
          
          txt1.setAttribute('id','repair'+parseInt(start+i,10));
          txt1.setAttribute('name','repair[]'); 
          txt1.className='inputbox1';
		  /*txt3.setAttribute('size',"20");
		  txt3.setAttribute('maxLength','"40"');
		  txt3.setAttribute('type','text');*/

          txt2.setAttribute('id','items'+parseInt(start+i,10));
          txt2.setAttribute('name','items[]');
          txt2.className='inputbox1';
          txt2.setAttribute('size','"20"');
		  txt2.setAttribute('maxLength',"200");
          txt2.setAttribute('type','text');

		  txt3.setAttribute('id','charges'+parseInt(start+i,10));
          txt3.setAttribute('name','charges[]');
          txt3.className='inputbox1';
          txt3.setAttribute('size','"20"');
		  txt3.setAttribute('maxLength',"6");
          txt3.setAttribute('type','text');

		  //hiddenIds.innerHTML=optionData;         
          txt4.setAttribute('id','rd');
          txt4.className='htmlElement';  
          txt4.setAttribute('title','Delete');       
          
          txt4.innerHTML='X';
          txt4.style.cursor='pointer';
          
		  if(mode == 'add') {
			//txt7.setAttribute('onclick','javascript:deleteExpRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff
            txt4.onclick = new Function("deleteRepairRow('" + parseInt(start+i,10)+'~0' + "')");
		  }
		  else if (mode == 'edit') {
			txt4.onclick = new Function("deleteEditRepairRow('" + parseInt(start+i,10)+'~0' + "')");  //for ie and ff
		  }
          
          cell1.appendChild(txt0);
          //cell1.appendChild(hiddenId);
          cell2.appendChild(txt1);
		  cell3.appendChild(txt2);
		  cell4.appendChild(txt3);
		  cell5.appendChild(txt4);
                 
          tr.appendChild(cell1);
          tr.appendChild(cell2);
          tr.appendChild(cell3);
          tr.appendChild(cell4);
		  tr.appendChild(cell5);
          
          bgclass=(bgclass=='row0'? 'row1' : 'row0');
          tr.className=bgclass;
          
          tbody.appendChild(tr); 
      
          // add option Teacher   
		 
		  if(mode == 'add') {
			  var len= document.getElementById('repairType').options.length;
			  var t=document.getElementById('repairType');
			  // add option Select initially
			  if(len>0) {
				var tt='repair'+parseInt(start+i,10) ; 
				//alert(eval("document.getElementById(tt).length"));
				for(k=0;k<len;k++) { 
				  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
				 }
			  }
		  }

		  if(mode == 'edit') {
			  var len= document.getElementById('repairType1').options.length;
			  var t=document.getElementById('repairType1');
			  // add option Select initially
			  if(len>0) {
				var tt='repair'+parseInt(start+i,10) ; 
				//alert(eval("document.getElementById(tt).length"));
				for(k=0;k<len;k++) { 
				  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
				 }
			  }
		  }
      }
      tbl.appendChild(tbody);   
   }


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditFuel" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)                                              
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
		cleanUpEditTable();
		
		url = '<?php echo HTTP_LIB_PATH;?>/VehicleServiceRepair/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {serviceRepairId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        //hiddenFloatingDiv('EditFuel');
                        messageBox("<?php echo FUEL_RECORD_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        return false;
                    }
                   else{
                                            
                       j = eval('('+transport.responseText+')');
					   //alert(transport.responseText);
                       document.editVehicleServiceRepair.vehicleType.value = j['vehicleServiceDetails'][0]['vehicleTypeId'];
					   document.editVehicleServiceRepair.busNo.value = j['vehicleServiceDetails'][0]['busId'];
                       document.editVehicleServiceRepair.busService.value = j['vehicleServiceDetails'][0]['serviceType'];
					   if(j['vehicleServiceDetails'][0]['serviceType'] == 1) {
							document.getElementById('getEditServiceNo').style.display = '';
							document.getElementById('divServiceNo').innerHTML = '';
							document.getElementById('divServiceNo').innerHTML = j['vehicleServiceDetails'][0]['serviceNo'];	
					   }
					   else {
							document.getElementById('getEditServiceNo').style.display = 'none';
					   }
                       document.editVehicleServiceRepair.serviceDate1.value = j['vehicleServiceDetails'][0]['serviceDate'];
                       document.editVehicleServiceRepair.readingEntry.value = j['vehicleServiceDetails'][0]['kmReading'];
                       document.editVehicleServiceRepair.billNo.value = j['vehicleServiceDetails'][0]['billNo'];
                       document.editVehicleServiceRepair.servicedAt.value = j['vehicleServiceDetails'][0]['servicedAt'];
                       document.editVehicleServiceRepair.serviceRepairId.value =j['vehicleServiceDetails'][0]['serviceRepairId'];
					   document.getElementById('serviceDetail1').innerHTML = j['repairServiceDiv'];
					   var len = j['vehicleServiceRepairDetail'].length;
						if(len > 0 ) {
							addVehicleRepairOneRow(len,'edit');
							resourceRepairAddCnt = len;
							for(i=0;i<len;i++) {
								varFirst = i+1;
								repair = 'repair'+varFirst;
								items = 'items'+varFirst;
								charges = 'charges'+varFirst;

								if (j['vehicleServiceRepairDetail'][i]['amount'].length > 6 ) {
									j['vehicleServiceRepairDetail'][i]['amount'] = j['vehicleServiceRepairDetail'][i]['amount'].split('.00')[0];
							    }

								document.getElementById(repair).value = j['vehicleServiceRepairDetail'][i]['type'];
								document.getElementById(items).value = j['vehicleServiceRepairDetail'][i]['item'];
								document.getElementById(charges).value = j['vehicleServiceRepairDetail'][i]['amount'];
							}
						}

                       document.editVehicleServiceRepair.busNo.focus();
                   }
             },
              onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
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
/* function to print fuel report report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/fuelReportPrint.php?'+qstr;
    window.open(path,"FuelReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='fuelReportCSV.php?'+qstr;
}


function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){    
  var form = document.AddFuel;
 }
 else{
     var form = document.EditFuel;
 }
  eval('form.'+eleName+'.focus()');
  return false;
 }
}

window.onload=function(){
 //document.getElementById('calImg1').onblur=getFuel1
 //document.getElementById('calImg2').onblur=getFuel2
}

function getFuel1(){
  getLastMilege(document.AddFuel.busId.value,1);
}

function getFuel2(){
  getLastMilege(document.EditFuel.busId.value,2);
}

function getAmountCalculation() {
	document.AddFuel.amount.value = document.AddFuel.litres.value * document.AddFuel.rate.value;
}

function getEditAmountCalculation() {
	document.EditFuel.amount.value = document.EditFuel.litres.value * document.EditFuel.rate.value;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/VehicleServiceRepair/listVehicleServiceRepairContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
    //This function will populate list
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>

<?php 
// $History: $ 
//
?>