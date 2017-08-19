<?php
//used for entering student's interl marks
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleFeeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Bus Fee Master </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');?>

<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

//to stop special formatting
specialFormatting=0;
var previousMarks =0;

var tableHeadArray = new Array(new Array('srNo','#','width="1%"','',false),
	new Array('routeName ','Route Name','width="25%"','',true) ,
	new Array('cityName','Stop City Name','width="15%"','',true) ,
	new Array('busFeeTextBox','Bus Fee','width="15%" align="right"','',true)
 );
recordsPerPage =1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'searchForm'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'roomName';
sortOrderBy = 'ASC';


	function fillTextBox(busFee){
		if(!isNumeric(busFee)){
			alert("Bus Fee Should Be Numeric.");
			 document.getElementById('fillBusFee').focus();
			return false;
		}
		 $$('input[id*="busFee"]').each(function(a){ 
		 	if(document.getElementById('notOverideValues').checked){
		 		if(isEmpty(a.value)){
					a.value= busFee;
			 	}
		 	}
		 	else{
		 		a.value= busFee;
		 	}
		});
	}
	
	
	function checkValue(busFee,id){
		if(!isNumeric(busFee)){
			alert("Bus Fee Should Be Numeric.");
			document.getElementById(id).focus();
			return false;
		}
	}
	
	function validateData(){
		form = document.searchForm; 
		var url = '<?php echo HTTP_LIB_PATH;?>/Fee/BusFee/ajaxGetBusFeeList.php';
		
		if(trim(document.getElementById('routeId').value) == ""){
			messageBox("Select Route Name");
			document.getElementById('routeId').focus();
			return false;
		}
	 	else if(trim(document.getElementById('busStopHeadId').value)==""){
			messageBox("<?php echo SELECT_BUS_STOP_CITY; ?>");
			document.getElementById('busStopHeadId').focus();
			return false;
		}
		else if(trim(document.getElementById('degreeId').value)==""){
			messageBox("<?php echo SELECT_DEGREE; ?>");
			document.getElementById('degreeId').focus();
			return false;
		}
		else if(trim(document.getElementById('branchId').value)==""){
			messageBox("<?php echo SELECT_BRANCH; ?>");
			document.getElementById('branchId').focus();
			return false;
		}
		else if(trim(document.getElementById('batchId').value)==""){
			messageBox("<?php echo SELECT_BATCH; ?>");
			document.getElementById('batchId').focus();
			return false;
		}
		else if(trim(document.getElementById('classId').value)==""){
			messageBox("<?php echo SELECT_STUDY_PERIOD; ?>");
			document.getElementById('studyPeriodId').focus();
			return false;
		}
		
		totalrouteId= form.elements['routeId[]'].length;
		selectedRouteId='';
		countRoteName=0;
		for(i=0;i<totalrouteId;i++){
			if (form.elements['routeId[]'][i].selected == true) {
				if (selectedRouteId != '') {
					selectedRouteId += ',';
				}
				countRoteName++;
				selectedRouteId += form.elements['routeId[]'][i].value;
			}
		}
		if(selectedRouteId == ''){
			messageBox("<?php echo SELECT_ROUTE_NAME; ?>");
			document.getElementById('routeId').focus();
			return false;
		}
		document.getElementById('showList').style.display ='';
		document.getElementById('feeTextBoxDiv').style.display ='';
	        //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
	        listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','roomName','ASC','results','','',true,'listObj',tableHeadArray,'','','&selectedRouteId='+selectedRouteId+'&busStopHeadId='+document.getElementById('busStopHeadId').value+'&batchId='+document.getElementById('batchId').value+'&classId='+document.getElementById('classId').value);
 sendRequest(url, listObj, ' ',false);
	}
	
	function validateForm(){
		
		var url = '<?php echo HTTP_LIB_PATH;?>/Fee/BusFee/ajaxBusFeeAdd.php';
		
		if(trim(document.getElementById('routeId').value) == ""){
			messageBox("Select Route Name");
			document.getElementById('routeId').focus();
			return false;
		}
	 	else if(trim(document.getElementById('busStopHeadId').value)==""){
			messageBox("<?php echo SELECT_BUS_STOP_CITY; ?>");
			document.getElementById('busStopHeadId').focus();
			return false;
		}
		else if(trim(document.getElementById('degreeId').value)==""){
			messageBox("<?php echo SELECT_DEGREE; ?>");
			document.getElementById('degreeId').focus();
			return false;
		}
		else if(trim(document.getElementById('branchId').value)==""){
			messageBox("<?php echo SELECT_BRANCH; ?>");
			document.getElementById('branchId').focus();
			return false;
		}
		else if(trim(document.getElementById('batchId').value)==""){
			messageBox("<?php echo SELECT_BATCH; ?>");
			document.getElementById('batchId').focus();
			return false;
		}
		else if(trim(document.getElementById('classId').value)==""){
			messageBox("<?php echo SELECT_STUDY_PERIOD; ?>");
			document.getElementById('studyPeriodId').focus();
			return false;
		}
		feeAmountData = '';
		//detecting Fee list
		busElement= $$('input[id*="busFee"]');
		busFeelength = busElement.length;
		for(i=0; i < busFeelength ; i++){ 
			if(feeAmountData!=""){
				feeAmountData +=',';	
			}
			if(isEmpty(document.getElementById('busFee'+i).value)){
				messageBox("Please Enter Bus Fee at row "+( i +1));
				return false;
			}
			feeAmountData += document.getElementById('busFee'+i).value+'-'+document.getElementById('data'+i).value;
		}
        
        classIds='';
        if(document.getElementById('classId').value=='all') {
            cnt = document.getElementById('classId');
            for(i=1;i<cnt.length;i++) {
              if(classIds!='') {
                classIds = classIds +",";
              }
              classIds = classIds + trim(cnt[i].value);
            }
        }
        else if(document.getElementById('classId').value!='') {
          classIds =  document.getElementById('classId').value; 
        }
        
        if(classIds=='') {
          classIds='0';  
        }
        
		new Ajax.Request(url,
		   {
		     method:'post',
		     asynchronous:false,
		     parameters: {
			     	classId: classIds,
			     	feeAmountData :feeAmountData	
		     },
		    onCreate: function() {
		         showWaitDialog(true);
		     },
		     onSuccess: function(transport){
		     	hideWaitDialog(true);
		     	if(trim(transport.responseText) == 'Please Enter Bus Fee'){
		     		messageBox(transport.responseText);
		     		return false;
		     	}
		     	messageBox(transport.responseText);
		        resetForm('all');
			getAllDegree('all');
		     },
		     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		   });
		
	}
	
	//-------------------------------------------------------
	//THIS FUNCTION IS USED TO GET BUS STOP
	//
	//Author : Nishu Bindal
	// Created on : (6.Feb.2012)
	// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------

	function getBusStopCity() { 
		form = document.searchForm;
		
		totalrouteId= form.elements['routeId[]'].length;
		selectedRouteId='';
		countRoteName=0;
		for(i=0;i<totalrouteId;i++){
			if (form.elements['routeId[]'][i].selected == true) {
				if (selectedRouteId != '') {
					selectedRouteId += ',';
				}
				countRoteName++;
				selectedRouteId += form.elements['routeId[]'][i].value;
			}
		}
		if(selectedRouteId == ''){
			messageBox("<?php echo SELECT_ROUTE_NAME; ?>");
			document.getElementById('routeId').focus();
			return false;
		}
		
		var url = '<?php echo HTTP_LIB_PATH;?>/Fee/BusFee/getBusStopCity.php';
		new Ajax.Request(url,
		{
			method:'post',
			parameters: {selectedRouteId : selectedRouteId	
				},
			 onCreate: function(){
				 showWaitDialog(true);
			 },
			onSuccess: function(transport){ 
				hideWaitDialog(true);
				var j = eval('(' + transport.responseText + ')');
				len = j.length;
				form.busStopCity.length = null;
				if(j==0 || len == 0) {
					addOption(form.busStopCity, '', 'Select');
					return false;
				}
				else{ addOption(form.busStopCity, 'all', 'All');
					for(i=0;i<len;i++) {
						addOption(form.busStopCity, j[i].busStopCityId, j[i].cityName);
					}
				}
			
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
	
	//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET BRANCHES 
//
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getBranches() {
	form = document.searchForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/BusFee/getBranches.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: {	degreeId: document.getElementById('degreeId').value	
			},
			
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.branchId.length = null; 
			if(j== 0 || len == 0) {
				addOption(form.branchId,'', 'Select');
			}
			else{	
				addOption(form.branchId,'', 'Select');
				for(i=0;i<len;i++) {
					addOption(form.branchId, j[i].branchId, j[i].branchCode);
				}
			}
			resetForm('batch');
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET BRANCHES 
//
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getBatch() { 
	form = document.searchForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/BusFee/getBatches.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: {	branchId: document.getElementById('branchId').value,
				degreeId: document.getElementById('degreeId').value
			},
			
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.batchId.length = null;
			if(j==0 || len == 0) {
				addOption(form.batchId, '', 'Select');
			}
			else{
				addOption(form.batchId, '', 'Select');
				for(i=0;i<len;i++) {
					addOption(form.batchId, j[i].batchId, j[i].batchName);
				}
			}
			resetForm('class');
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET Classes
//
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getClass() { 

	form = document.searchForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/BusFee/getClases.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: {	branchId: document.getElementById('branchId').value,
				degreeId: document.getElementById('degreeId').value,
				batchId: document.getElementById('batchId').value
			},
			
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.classId.length = null;
			if(j==0 || len == 0) {
				addOption(form.classId, '', 'Select');
				return false;
			}
			else{
				for(i=0;i<len;i++) {
					addOption(form.classId, j[i].classId, j[i].className);
				}
			}
			resetForm();
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

	
	function resetForm(mode){
		form = document.searchForm;
		document.getElementById('showList').style.display ='none';
		document.getElementById('fillBusFee').value ='';
		document.getElementById('feeTextBoxDiv').style.display ='none';
		document.getElementById('notOverideValues').checked=true;
		if(mode == 'all'){
			form.busStopCity.length = null;
			addOption(form.busStopCity, '', 'Select');
			document.getElementById('routeId').selectedIndex = -1;
			document.getElementById('degreeId').selectedIndex = 0;
			form.branchId.length = null;
			addOption(form.branchId, '', 'Select');
			form.batchId.length = null;
			addOption(form.batchId, '', 'Select');
			form.classId.length = null;
			addOption(form.classId, '', 'Select');
		}
		else if(mode == 'restAll'){
			form.busStopCity.length = null;
			addOption(form.busStopCity, '', 'Select');
		}
		else if(mode == 'branch'){
			form.branchId.length = null;
			addOption(form.branchId, '', 'Select');
			form.batchId.length = null;
			addOption(form.batchId, '', 'Select');
			form.classId.length = null;
			addOption(form.classId, '', 'Select');
		}
		else if(mode == 'batch'){
			form.batchId.length = null;
			addOption(form.batchId, '', 'Select');
			form.classId.length = null;
			addOption(form.classId, '', 'Select');
		}
		else if(mode=='class'){
			form.classId.length = null;
			addOption(form.classId, '', 'Select');
		}
		
		
	}
    
function getAllDegree(str) {
    
    form = document.searchForm;    
    
    if(str=='Degree') {
      form.branchId.length = null; 
      form.batchId.length = null; 
      form.classId.length = null; 
      addOption(form.branchId, '', 'Select'); 
      addOption(form.batchId, '', 'Select'); 
      addOption(form.classId, '', 'Select'); 
    }
    else if(str=='Branch') {
      form.batchId.length = null; 
      form.classId.length = null; 
      addOption(form.batchId, '', 'Select'); 
      addOption(form.classId, '', 'Select'); 
    }
    else if(str=='Batch') {
      form.classId.length = null; 
      addOption(form.classId, '', 'Select'); 
    }
    else {
      form.degreeId.length = null; 
      form.branchId.length = null; 
      form.batchId.length = null; 
      form.classId.length = null; 
      addOption(form.degreeId, '', 'Select'); 
      addOption(form.branchId, '', 'Select'); 
      addOption(form.batchId, '', 'Select'); 
      addOption(form.classId, '', 'Select'); 
    }
    
    
    classStatus=1;
    if(form.classStatus[1].checked==true) { 
      classStatus =2;
    }
    
    degreeId = form.degreeId.value;
    branchId = form.branchId.value;
    batchId = form.batchId.value;
    
    
    param = "classStatus="+classStatus+"&searchMode="+str; 
    param = param + "&degreeId="+degreeId+"&branchId="+branchId+"&batchId="+batchId;
    
    
    var url = '<?php echo HTTP_LIB_PATH;?>/Fee/BusFee/ajaxSearchValues.php';
    
    new Ajax.Request(url,
    {
        method:'post',
        parameters: param, 
        asynchronous:false,
        onCreate: function(){
             showWaitDialog(true);
        },
        onSuccess: function(transport){ 
            hideWaitDialog(true);
            
            var ret=trim(transport.responseText).split('!~!!~!');
            
            if(str=='all') {  
              form.degreeId.length = null;       
              var j0 = eval(ret[0]);
              if(j0.length>0) {
                addOption(form.degreeId,'all', 'All');      
              }
              else {
                addOption(form.degreeId, '', 'Select');        
              }
              for(i=0;i<j0.length;i++) { 
                addOption(form.degreeId, j0[i].degreeId1, j0[i].degreeCode1);
              }
              str='all';
            }
            
            if(str=='Degree' || str == 'all') {
              form.branchId.length = null;       
              var j1 = eval(ret[1]);
              if(j1.length>0) {
                addOption(form.branchId,'all', 'All');      
              }
              else {
                addOption(form.branchId, '', 'Select');        
              }
              for(i=0;i<j1.length;i++) { 
                addOption(form.branchId, j1[i].branchId1, j1[i].branchCode1);
              } 
              str='all';  
            }  
            
            if(str=='Branch' || str == 'all') {
              form.batchId.length = null;         
              var j2= eval(ret[2]);
              if(j2.length>0) {
                addOption(form.batchId,'all', 'All');      
              }
              else {
                addOption(form.batchId, '', 'Select');        
              }
              for(i=0;i<j2.length;i++) { 
                addOption(form.batchId, j2[i].batchId1, j2[i].batchName);
              } 
              str='all';
            }
            
            if(str=='Batch' || str == 'all') {
              form.classId.length = null;       
              var j3 = eval(ret[3]);
              if(j3.length>0) {
                addOption(form.classId,'all', 'All');      
              }
              else {
                addOption(form.classId, '', 'Select');        
              }
              for(i=0;i<j3.length;i++) { 
                addOption(form.classId, j3[i].classId, j3[i].className);
              } 
            }
            
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    }); 
}    
    
window.onload=function(){
   getAllDegree('all');
}
	
</script>
</head>
<body>
<?php
	require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Fee/BusFee/listBusFeeContents.php");
	require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
