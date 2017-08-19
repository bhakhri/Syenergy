<?php
global $FE;
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/Index/dashBoardList.php");
UtilityManager::ifNotLoggedIn();

$classId ='';
$classId1 ='';
if($sessionHandler->getSessionVariable('AverageAttendance')!=''){

	$classId = $activeClassArray[0]['classId'];
}
else
	$classId ='';

if($sessionHandler->getSessionVariable('TestTypeDetail')!=''){

	$classId1 = $activeClassArray[0]['classId'];
}
else
	$classId1 ='';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Home </title>

<?php require_once(TEMPLATES_PATH .'/jsCssHeader.php'); ?>


<script language="javascript">
//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY Notice Div
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var p;
function showNoticeDetails(id,dv,w,h) {
    
	height=screen.height/5;
	width=screen.width/4.5;
	//displayWindow('divNotice',600,600);
	displayFloatingDiv(dv,'', w, h, 200, 180)
	
    	populateNoticeValues(id);  
	
}

var p;
function showNoticeCount(id,dv,w,h) {
    
	height=screen.height/5;
	width=screen.width/4.5;
	//displayWindow('divNotice',600,600);
	displayFloatingDiv(dv,'', w, h, 200, 180)
	
    	populateNoticeCount(id);
	
}


//------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divNotice" DIV

//--------------------------------------------------------
function populateNoticeValues(id) {
	   url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxGetNoticeDetails.php';
       try{
	    hideDropDowns(0);
       }catch(e){}
       new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {noticeId: id},
		 onCreate: function() {

			 showWaitDialog();
		 },
		 onSuccess: function(transport){
			 
			  hideWaitDialog();
			  if(trim(transport.responseText)==0) {
			  
					hiddenFloatingDiv('divNotice');
					messageBox("This Notice Record Does Not Exists");
			  }
			  j = eval('('+trim(transport.responseText)+')');
				 
			  document.getElementById('noticeSubject').innerHTML = trim(j.noticeSubject);
			  document.getElementById('noticeDepartment').innerHTML = trim(j.departmentName+' ('+j.abbr+')');
			  document.getElementById('noticeText').innerHTML = trim(j.noticeText);
			  document.getElementById('visibleToDate').innerHTML=customParseDate(j.visibleToDate,"-");
			  document.getElementById('visibleFromDate').innerHTML=customParseDate(j.visibleFromDate,"-");
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}
//------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "countNotice" Count

//--------------------------------------------------------
function populateNoticeCount(id){
	   url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxGetNoticeCount.php';
       try{
	    hideDropDowns(0);
       }catch(e){}
       new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {noticeId: id},
		 onCreate: function() {

			 showWaitDialog();
		 },
		 onSuccess: function(transport){
			 
			  hideWaitDialog();
			  if(trim(transport.responseText)==0) {
			  
					hiddenFloatingDiv('countNotice');
					messageBox("This Notice Record Does Not Exists");
			  }
			  j = eval('('+trim(transport.responseText)+')');
				 
			  document.getElementById('Sr.no').innerHTML = trim(j.Sr.no);
			  document.getElementById('userName').innerHTML = trim(j.userName);
			  document.getElementById('name').innerHTML = trim(j.name);
			  document.getElementById('role').innerHTML=trim(j.role);
			  document.getElementById('date').innerHTML=customParseDate(j.date);
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY Event Div
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function showEventDetails(id,dv,w,h) {
	 
	displayFloatingDiv(dv,'', w, h, 200, 180)
    //displayWindow('divEvent',300,200);
    populateEventValues(id);   
}
function showGraph(id) {

	if(document.getElementById('classId').value != '0'){
	
		id=document.getElementById('classId').value;
	}
	else {
		//alert("Please Select Class");
		document.getElementById('resultsDiv').innerHTML = '<br><br><br><b><center>Please Select Class<center></b>';
		return false;
	}

	var url = '<?php echo HTTP_LIB_PATH;?>/Index/getHistogram.php';
	var pars = 'classId='+id;
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		asynchronous:false,
		 onCreate: function(){
			 showWaitDialog();
		 },
		onSuccess: function(transport){
		hideWaitDialog();
		if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
			
			showBarChartResults();
		}
		else {
			document.getElementById('resultsDiv').innerHTML = '';
		}
	},
	onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});

}
function showBarChartResults() {
	//document.getElementById("resultRow").style.display='';
	//form = document.marksNotEnteredForm;
	var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amline","290", "167", "8", "#FFFFFF");
	 so.addVariable("path", "./");  
	  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart	
	  x = Math.random() * Math.random();
	  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>140</y><rotate>true</rotate><text>Attendance Threshold ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency><rotate>true</rotate><text_size>10</text_size></category></values><plot_area><margins><bottom>60</bottom></margins></plot_area></settings>");
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/barChartSetting.xml"));
	  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/averageAttencanceActivityBarData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	so.write("resultsDiv");
}

function showTestGraph(id) {

	
	if(document.getElementById('classId1').value){
	
		id=document.getElementById('classId1').value;
	}	
	var url = '<?php echo HTTP_LIB_PATH;?>/Index/getTestGraph.php';
	var pars = 'classId='+id;
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		asynchronous:false,
		 onCreate: function(){
			 showWaitDialog();
		 },
		onSuccess: function(transport){
		hideWaitDialog();
		if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
			
			showTestBarChartResults();
		}
	},
	onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});

}
function showTestBarChartResults() {
	//document.getElementById("resultRow").style.display='';
	//form = document.marksNotEnteredForm;
	  var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amline","295", "360", "8", "#FFFFFF");
	  so.addVariable("path", "./");  
	  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart	
	  x = Math.random() * Math.random();
	  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>240</y><rotate>true</rotate><text_size>8</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency><rotate>true</rotate><text_size>10</text_size></category></values><plot_area><margins><left>20</left><right>20</right><bottom>265</bottom></margins></plot_area><legend><enabled></enabled><x>10</x><y>190</y><width>280</width><max_columns>100</max_columns><color></color><alpha>10</alpha><border_color></border_color><border_alpha></border_alpha><text_color></text_color><text_size>9</text_size><spacing>2</spacing><margins></margins><reverse_order>false</reverse_order><align>center</align><key><size></size><border_color></border_color></key></legend></settings>");
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/stackChartSetting.xml"));
	  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/testTypeStackData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv1");
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divAttendance" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateEventValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxGetEventDetails.php';
		 hideDropDowns(0);

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {eventId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('divEvent');
                        messageBox("This Event Record Doen Not Exists");
                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+trim(transport.responseText)+')');
                   
				   document.getElementById('eventTitle').innerHTML = trim(j.eventTitle);
				   document.getElementById('shortDescription').innerHTML = trim(j.shortDescription);
				   document.getElementById('longDescription').innerHTML = trim(j.longDescription);
				   document.getElementById('startDate').innerHTML = customParseDate(j.startDate,"-");
				   document.getElementById('endDate').innerHTML = customParseDate(j.endDate,"-");
                   
				   
				   /*document.EventForm.eventTitle.value = trim(j.eventTitle);
                   document.EventForm.shortDescription.value = trim(j.shortDescription);
                   document.EventForm.longDescription.value = trim(j.longDescription);
                   document.EventForm.startDate.value = customParseDate(j.startDate,"-");
                   document.EventForm.endDate.value = customParseDate(j.endDate,"-");*/

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
 
 

function showData(id){

	path='<?php echo UI_HTTP_PATH;?>/userWisePrint.php?dateSelected='+id;
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
}
function showDetailData(subId,categoryId,classId){

	//alert("subjectId"+subId);
	//alert("testtypeCategoryId"+categoryId);
	//alert("classId"+classId);

	path='<?php echo UI_HTTP_PATH;?>/testTypeWisePrint.php?subId='+subId+'&categoryId='+categoryId+'&classId='+classId;
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
}

function showStudentAttendanceThresholdData(classId,subjectId){

	path='<?php echo UI_HTTP_PATH;?>/studentAttendanceThresholdPrint.php?classId='+classId+'&subjectId='+subjectId;
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
}

// CHART INITED //////////////////////////////////////////////////////////////////////////    
// amChartInited(chart_id)
// This function is called when the chart is fully loaded and initialized.
function amChartInited(chart_id){
  // get the flash object into "flashMovie" variable   
  flashMovie = document.getElementById(chart_id);
  // tell the field with id "chartfinished" that this chart was initialized
  document.getElementById("chartfinished").value = "chart " + chart_id + " is finished";           
}      
      
// RETURN DATA ///////////////////////////////////////////////////////////////////////////
// amReturnData(chart_id, data)
// This function is called when you request data from a chart 
//  by calling the flashMove.getData() function.
function amReturnData(chart_id, data){
  document.getElementById("data").value = unescape(data);
}

// RETURN PARAM //////////////////////////////////////////////////////////////////////////
// amReturnParam(chart_id, param)
// This function is called when you request a setting from a chart  
// by calling the flashMovie.getParam(param) function.
function amReturnParam(chart_id, param){
  document.getElementById("returnedparam").value = unescape(param);
}

// RETURN SETTINGS ///////////////////////////////////////////////////////////////////////
// amReturnSettings(chart_id, settings)
// This function is called when you request settings from a chart 
// by calling flashMovie.getSettings() function.  
function amReturnSettings(chart_id, settings){
  document.getElementById("settings").value = unescape(settings);
}      

// RETURN IMAGE DATA /////////////////////////////////////////////////////////////////////
// amReturnImageData(chart_id, data)
// This function is called when the export to image process is finished and might be used
// as alternative way to get image data (instead of posting it to some file)
function amReturnImageData(chart_id, data){
  // your own functions here
}

// ERROR /////////////////////////////////////////////////////////////////////////////////
// amError(chart_id, message)
// This function is called when an error occurs, such as no data, or file not found.
function amError(chart_id, message){
  alert(message);
}

// FIND OUT WHICH SLICE WAS CLICKED //////////////////////////////////////////////////////
// amSliceClick(chart_id, index, title, value, percents, color, description)
// This function is called when the viewer clicks on the slice. It returns chart_id, 
// the sequential number of the slice (index), the title, value, percent value, 
// color and description.
function amSliceClick(chart_id, index, title, value, percents, color, description){
  
  roleArr = description.split("~");
  if(roleArr[1]=="city"){
	 
	path='<?php echo UI_HTTP_PATH;?>/cityWiseEnquiryStudentPrint.php?cityId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }
  if(roleArr[1]=="state"){
	 
	path='<?php echo UI_HTTP_PATH;?>/stateWiseEnquiryStudentPrint.php?stateId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }
  if(roleArr[1]=="class"){
	 
	path='<?php echo UI_HTTP_PATH;?>/degreeWiseEnquiryStudentPrint.php?classId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }
  document.getElementById("sliceclick").value = index;
} 

// FIND OUT WHICH SLICE WAS HOVERED //////////////////////////////////////////////////////
// amSliceOver(chart_id, index, title, value, percents, color, description)
// This function is called when the viewer rolls over the slice. It returns chart_id, the 
// sequential number of the slice (index), the title, value, percent value, 
// color and description.
function amSliceOver(chart_id, index, title, value, percents, color, description){
  document.getElementById("sliceover").value = index;
}

// FIND OUT WHEN THE MOUSE WAS MOVED AWAY FROM A SLICE ///////////////////////////////////
// amSliceOut(chart_id)
// This function is called when the viewer rolls away from the slice.
function amSliceOut(chart_id){
  document.getElementById("sliceover").value = "";
} 
  

// EXPORT AS IMAGE ///////////////////////////////////////////////////////////////////////
// flashMovie.exportImage([file_name]) 
// This function will start the process of exporting the chart as an image. The file_name
// is a name of a file to which image data will be posted (files provided in the download 
// package are export.php and export.aspx). The file_name is optional and can be set in 
// the <export_as_image><file> setting.

function exportImage() {
  flashMovie.exportImage('ampie/export.php');  
}

function hideDropDowns(mode){
    //show/hide in search filter
    var frmObj1=document.forms['attendanceForm'].elements;
    var objLength=frmObj1.length;
    for(var i=0;i<objLength;i++){
        if(frmObj1[i].type=='select-multiple' || frmObj1[i].type=='select-one'){
          if(mode==0){ 
            frmObj1[i].style.display='none';
          }
          else{
              frmObj1[i].style.display='';
          }
        }
    }
    
    
    //show/hide in result divs
    var frmObj1=document.forms['testForm'].elements;
    var objLength=frmObj1.length;
    for(var i=0;i<objLength;i++){
        if(frmObj1[i].type=='select-multiple' || frmObj1[i].type=='select-one'){
          if(mode==0){ 
            frmObj1[i].style.display='none';
          }
          else{
              frmObj1[i].style.display='';
          }
        }
    }
}

function hiddenFloatingDiv(divId) 
{
    try{
     hideDropDowns(1);
    }catch(e){}
    //document.getElementById(divId).innerHTML = originalDivHTML;
    document.getElementById(divId).style.visibility='hidden';
    //document.getElementById('dimmer').style.visibility = 'hidden';
    document.getElementById('modalPage').style.display = "none";
    makeMenuDisable('qm0',false);
    
   // DivID = "";
}

//For autosuggest
function changeDefaultTextOnClick()
{
    if(document.getElementById('menuLookup').value=="Menu Lookup..")
    {
        document.getElementById('menuLookup').value="";
        document.getElementById('menuLookup').className="text_class";
    }
}
function changeDefaultTextOnBlur()
{
    if(document.getElementById('menuLookup').value=="")
    {
        document.getElementById('menuLookup').className="fadeMenuText"; 
        document.getElementById('menuLookup').value="Menu Lookup..";
    }
}
//This script throws a ajax request to populate autosuggest menu
function getMenuLookup()
{
    document.getElementById('menuLookupContainer').style.display="none";
    if(document.getElementById('menuLookup').value.length>1)
    {
        url = '<?php echo HTTP_LIB_PATH;?>/menuLookup.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {txt: document.getElementById('menuLookup').value},
             onCreate: function() {
                 
                // showWaitDialog(true);
             },
             onSuccess: function(transport){
                    // hideWaitDialog(true);
                    if((transport.responseText)!="") {
                        var display="<ul style='list-style:none'>";
                        var obj=transport.responseText.evalJSON() 
                          if(obj)
                        {
                            var objSize=10;
                            if(obj.length<10)
                            {
                                objSize=obj.length;
                            }
                            
                            for(var arrayIndex=0;arrayIndex<objSize;arrayIndex++)
                            {
                                display+="<li style='padding:3px'><a href='"+obj[arrayIndex]['link']+"'>"+obj[arrayIndex]['data']+"</a></li>";
                            }       
                        }
                        display+="</ul>";
                        document.getElementById('menuLookupContainer').style.display="";
                        document.getElementById('menuLookupContainer').style.display="block";
                        document.getElementById('menuLookupContainer').innerHTML=display;
                        return false;
                    }
             },
             onFailure: function(){ 
                 //messageBox("<?php echo TECHNICAL_PROBLEM;?>") 
             }
           });  
     }
}
// Autosuggest ends
</script>
</head>
<body>
<?php 
    function trim_output($str,$maxlength,$mode=1,$rep='...'){
       $ret=($mode==2?chunk_split($str,12):$str);

       if(strlen($ret) > $maxlength){
          $ret=substr($ret,0,$maxlength).$rep; 
       }
      return $ret;  
    } 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Index/internalIndexHome.php");
    require_once(TEMPLATES_PATH . "/footer.php"); 
    
?>
<script language="javascript">
	var classId = "<?php echo $classId ?>";
	var classId1 = "<?php echo $classId1 ?>";
	window.onload=function(){
		//setInterval("getData()",10000);
		enableTooltips();
		if(classId){
			showGraph("<?php echo $classId ?>");
		}
		if(classId1){
			showTestGraph("<?php echo $classId1?>");
		}
	 };

//This Function will get the total download counts of the user 
function getData(){ 
   url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxGetNoticeCount.php';   
  
   new Ajax.Request(url,
   {
     method:'post',
     onCreate: function() {
         showWaitDialog();
     },
     onSuccess: function(transport){
        hideWaitDialog();
        if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
           return false;
        }
        else {
          document.getElementById('divNoticeCountList').innerHTML = trim(transport.responseText);
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
   return false;
}
</script>
</body>
</html>
