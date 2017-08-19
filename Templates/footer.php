    </td>
</tr>
<tr>
    <td height="10px" colspan="2"></td>
</tr>
 <tr>
     <td colspan="2" class="text_menu" valign="middle" width="100%">
	 <table cellspacing="0" cellspacing="0" width="100%" border="0">
	 <tr>
		<td align="right" class="text_menu" valign="middle" width="730">For reporting feedback / issues write to <?php echo ACCOUNT_MANAGER_NAME;?> at <?php echo ACCOUNT_MANAGER_EMAIL;?> | &laquo; <a href='http://www.syenergy.in' target="_blank" style='color:#FFFFFF'>powered by syenergy Technologies Pvt. Ltd.</a> &raquo;</td>
		 <td align="right" class="text_menu" valign="middle"></td>
	</tr>
	 </table>
	 </td>
</tr>

</table>
<script type="text/javascript">

   //determine browser area
    winWDP = (isMozilla)? window.innerWidth-20 : document.documentElement.clientWidth-20;
    winHDP = (isMozilla)? window.innerHeight : document.documentElement.clientHeight;

</script>
<?php floatingDiv_Start('suggestionBox','Suggestion '); ?>
<form name="SuggestionForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr>
		<td height="5px"></td></tr>
	<tr>
	<tr>
		<td width="11%" align="left"><nobr><b>Subject</b></nobr><?php echo REQUIRED_FIELD;?></td>
		<td width="1%" align="right"><nobr><b>:&nbsp;</b></nobr></td>
		<td width="88%">
        <?php
              require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->getSuggestion();
        ?>
        <!--<select size="1" class="inputbox1" name="suggestionSubject" id="suggestionSubject"><option value="">Select</option>
		</select>-->
        </td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
	<tr>
		<td width="7%" valign="top"><strong>Message</strong><?php echo REQUIRED_FIELD;?></td>
		<td class="contenttab_internal_rows" valign="top"><nobr><strong>:</strong></nobr></td>
		<td width="94%" valign="top">
			&nbsp;<textarea id="suggestionText" name="suggestionText"  style="width:360px" class="inputbox" rows="10"/></textarea>
		</td>
	</tr> 
	<tr>
		<td height="5px"></td>
	</tr>
	<tr>
		<td colspan="3" align="center">
		<table border="0" cellspacing="0" cellpadding="0"  width="100%" align="center">
		<tr>
			<td align="center" style="padding-right:10px" colspan="3" width="100%">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/send.gif" onClick="return validateSuggestionForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('suggestionBox');return false;" /></td>
		</tr>
		</table>
		</td>
     </tr>
	<tr>
		<td height="5px"></td>
	</tr>
</table>
</form>
<?php floatingDiv_End(); ?>


<!--Start Subjects Div-->
<?php floatingDiv_Start('subjectBox','Brief Description',99,''); ?>
<form name="subjectSearchFrm" action="" method="post" onsubmit="return false">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
     <tr><td height="5px" colspan="2"></td></tr>
     <?php
        $id = $sessionHandler->getSessionVariable('RoleId');
        if($id!=3 && id!=4) {
     ?>
             <tr id="classResultRow" >
                <td colspan="2" align="left" valign="top">
                   <table width="65%" border="0" align="left" cellspacing="2px" cellpadding="2px" >
                     <tr>
                        <td class="contenttab_internal_rows" width="2%"><nobr><strong>Time Table&nbsp;<?php echo REQUIRED_FIELD ?></strong></nobr></td>
                        <td class="contenttab_internal_rows" width="2%"><nobr><strong>:&nbsp;</strong></nobr></td>
                        <td class="contenttab_internal_rows" width="2%"><nobr><strong>
                          <nobr><select size="1" class="inputbox1" name="classTimeId" id="classTimeId" style="width:200px" onchange="getActiveTimeTableLabelClass(); return false;">
                            <option value="">Select</option>
                            <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getTimeTableLabelData();?>
                          </select>
                          </nobr>
                        </td>
                        <td class="contenttab_internal_rows" width="2%" style="padding-left:10px;"><nobr><strong>Class&nbsp;</strong></nobr></td>
                        <td class="contenttab_internal_rows" width="2%"><nobr><strong>:&nbsp;</strong></nobr></td>
                        <td class="contenttab_internal_rows" width="2%"><nobr><strong>
                          <nobr>
                                <select size="1" class="inputbox1" name="classSubjectId" id="classSubjectId" style="width:270px">
                                    <option value="">Select</option>
                                </select>
                          </nobr>
                        </td>
                        <td class="contenttab_internal_rows" width="88%" style="padding-left:10px;">
                           <nobr>
                             <input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/show_list.gif" onClick="validationSubjectInfoValues();" title="Print" />
                           </nobr>
                        </td>
                     </tr>
                   </table>
                </td>
             </tr>
             <tr><td height="5px" colspan="2"></td></tr>
     <?php
        }
     ?>
     <tr>
        <td valign='top' colspan="2">
          <div id="scroll22" style="overflow:auto; width:920px; height:400px; vertical-align:top;">
            <div id="subjectInfoDiv" style="width:98%; vertical-align:top;"></div>
          </div>
        </td>
      </tr>
     <tr><td height="5px" colspan="2"></td></tr>
    </table>
</form>
<?php floatingDiv_End(); ?>
<!--End Subjects Div-->

<?php //floatingDiv_Start('helpVideoDiv','Help'); ?>
<?php /**CODE SNIPPET FOR VIDEO HELP***/ ?>
<!--
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>
        <td>
         <div id="helpVideoContainerDiv" style="width:640px;height:320px;"></div>
        </td>
     </tr>
    <tr>
        <td height="5px"></td>
    </tr>
</table>
</form>
-->
<?php //floatingDiv_End(); ?>

<?php  floatingDiv_Start('helpVideoDiv','Help','122','','','1'); ?>
<div id="helpInfoDiv" style="max-width:650px; vertical-align:top;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>
        <tr>
            <td width="89%">
                <div id="helpVideoContainerDiv"  style="width:640px;height:320px;overflow:auto" ></div>
            </td>
        </tr>
		<tr>
        <td height="5px"></td>
    </tr>
    </table>
</div>
<?php floatingDiv_End(); ?>

<script language="javascript">
function openVideoHelpDiv(){

   var url = themeFilePathURL+'/Populate/getHelpVideoInfo.php';
   new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 moduleName: "<?php echo MODULE; ?>"
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                  hideWaitDialog(true);
                  var ret=trim(transport.responseText);
                  document.getElementById('helpVideoContainerDiv').style.textAlign='';
                  displayWindow('helpVideoDiv',650,330);
                  if(ret!=-1){
                    var so1 = new SWFObject("<?php echo JS_PATH;?>/YTPlayer.swf", "YTPlayer", "640", "320", "8",  null, true);
                    so1.addParam("allowFullScreen", "false");
                    so1.addParam("allowSciptAccess", "always");
                    so1.addVariable("movieName", ret);
                    so1.addVariable("autoStart", "false");
                    so1.addVariable("logoPath", ""); // 60*60 dimension
                    so1.addVariable("logoPosition", "top_left"); // accepted values are top_left, top_right, bottom_left and bottom_right
                    so1.addVariable("logoClickURL", "");
                    so1.write("helpVideoContainerDiv");
                  }
                  else{
                      //messageBox("No video help is available");
                      document.getElementById('helpVideoContainerDiv').style.textAlign='center';
                      document.getElementById('helpVideoContainerDiv').innerHTML='<h3>Help video is coming soon !!!</h3>';
                  }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

   function populateSubjectInfoValues(recordsPerPage,linksPerPage) {

       document.getElementById('subjectInfoDiv').innerHTML='';
       var url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxGetClassSubjectList.php';

       //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array );

       id = "<?php echo $sessionHandler->getSessionVariable('RoleId'); ?>";
       if(id!=3 && id!=4) {
          classId=document.getElementById('classSubjectId').value;
          timeTableLabelId = document.getElementById('classTimeId').value;
          refereshClassIdData(classId);
          if(classId=='all') {
            sortField11="className";
            sortOrder11="ASC";
          }
          else {
            sortField11="subjectName";
            sortOrder11="ASC";
          }
          listObjSubject = new initPage(url,recordsPerPage,linksPerPage,1,'',sortField11,'ASC','subjectInfoDiv','','',true,'listObjSubject',tableHeadArray,'','','&classId='+classId+'&timeTableLabelId='+timeTableLabelId);
       }
       else {
          classId="<?php echo $sessionHandler->getSessionVariable('ClassId'); ?>";
          refereshClassIdData(classId);
          if(classId=='all') {
            sortField11="className";
            sortOrder11="ASC";
          }
          else {
            sortField11="subjectName";
            sortOrder11="ASC";
          }
          listObjSubject = new initPage(url,recordsPerPage,linksPerPage,'1','',sortField11,'ASC','subjectInfoDiv','','',true,'listObjSubject',tableHeadArray,'','','&classId='+classId);
       }
       sendRequest(url, listObjSubject,'sortOrderBy='+sortOrder11+'&sortField='+sortField11,true);
    }

    function refereshClassIdData(classId) {
         if(classId=='all') {
            tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                                  new Array('className','Class','width="20%" align="left"',true),
                                  new Array('subjectName','Subject Name','width="8%" align="left"',true),
                                  new Array('subjectAbbreviation','Subject Abbr.','width="8%" align="left"',true),
                                  new Array('subjectCode','Subject Code','width="8%" align="left"',true),
                                  new Array('subjectTypeName','Subject Type','width="8%" align="left"',true));
         }
         else {
              tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                                  new Array('subjectName','Subject Name','width="8%" align="left"',true),
                                  new Array('subjectAbbreviation','Subject Abbr.','width="8%" align="left"',true),
                                  new Array('subjectCode','Subject Code','width="8%" align="left"',true),
                                  new Array('subjectTypeName','Subject Type','width="8%" align="left"',true));
         }
    }

    function validationSubjectInfoValues() {

       if(document.getElementById('classTimeId').value=='') {
          messageBox("<?php echo SELECT_TIME_TABLE; ?>");
          document.getElementById('classTimeId').focus();
          return false;
       }
       recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
       linksPerPage = <?php echo LINKS_PER_PAGE;?>;
       populateSubjectInfoValues(recordsPerPage,linksPerPage);
    }


    function getActiveTimeTableLabelClass() {

        var url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxGetTimeTableClass.php';
        pars="timeTableLabelId="+document.getElementById('classTimeId').value;

        new Ajax.Request(url,
        {
           method:'post',
           parameters: pars,
           asynchronous:false,
           onCreate: function(){
             showWaitDialog(true);
           },
           onSuccess: function(transport){
               hideWaitDialog(true);
                var j = eval('(' + transport.responseText + ')');
                len = j.length;
                document.getElementById('classSubjectId').length = null;
                if(len>0) {
                  addOption(document.getElementById('classSubjectId'), 'all', 'All');
                  for(i=0;i<len;i++) {
                    addOption(document.getElementById('classSubjectId'), j[i].classId, j[i].className);
                  }
                }
                else {
                  addOption(document.getElementById('classSubjectId'), '', 'Select');
                }
                // now select the value
                //document.attendanceForm.degree.value = j[0].classId;
            },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
        });
    }

	function validateSuggestionForm(frm, act) {

		var fieldsArray = new Array(
			//new Array("suggestionSubject","<?php echo SELECT_SUGGESTION_SUBJECT;?>"),
			new Array("suggestionText","<?php echo SELECT_SUGGESTION_TEXT; ;?>")
		);

		var len = fieldsArray.length;
		for(i=0;i<len;i++) {
				
			if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
				alert(fieldsArray[i][1]);
				eval("frm."+(fieldsArray[i][0])+".focus();");
				return false;
				break;
			} 
		} 

		addSuggestion();
		return false;
	}
//This function adds form through ajax

	function addSuggestion() {

		var url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxSuggestionAdd.php';
        var subjectValue=0;
        if(document.SuggestionForm.suggestionSubject[0].checked==true){
           subjectValue=document.SuggestionForm.suggestionSubject[0].value;
        }
        else if(document.SuggestionForm.suggestionSubject[1].checked==true){
           subjectValue=document.SuggestionForm.suggestionSubject[1].value;
        }
        else{
          subjectValue=document.SuggestionForm.suggestionSubject[2].value;
        }
		new Ajax.Request(url,
		{
			 method:'post',
			 parameters: {
                 //suggestionSubject: (document.SuggestionForm.suggestionSubject.value),
                 suggestionSubject: subjectValue,
                 suggestionText: (document.SuggestionForm.suggestionText.value)
             },
			 onCreate: function() {
				showWaitDialog();
			 },
			 onSuccess: function(transport){

				 hideWaitDialog();
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {

					 flag = true;
					 if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
					
						 blankValues1();
					 }
					 else {

						 hiddenFloatingDiv('suggestionBox');
						 return false;
					 }
				 }
				 else {

					 messageBox(trim(transport.responseText));
				 }
			 },
			  onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}
function blankValues1() {

   //document.SuggestionForm.suggestionSubject.value = '';
   document.SuggestionForm.reset();
   document.SuggestionForm.suggestionText.value = '';
}


function makeSuperUser(){
    var url = '<?php echo HTTP_LIB_PATH;?>/SuperLogin/makeSuperUser.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 1:1
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     var ret=trim(transport.responseText);
                     if(ret=="<?php echo FAILURE; ?>"){
                         messageBox("Could not go back to your login");
                         return false;
                     }
                     window.location= "<?php echo UI_HTTP_PATH; ?>/superLoginList.php?"+ret;

             },
             onFailure: function(){ hideWaitDialog(true); messageBox("<?php echo TECHNICAL_PROBLEM; ?>"); }
           });
}

</script>
<?php
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");

// check for who will see the footer. 1 for admin, 2 for teacher, 4 for student

	if(($sessionHandler->getSessionVariable('RoleId')==1)||($sessionHandler->getSessionVariable('RoleId')==2)){
		require_once(TEMPLATES_PATH . '/dynamicFooter.php');
	}
?>
<script type="text/javascript">
//initial text for new multiple dropdowns
var initialTextForMultiDropDowns="Click to select multiple items";
var selectTextForMultiDropDowns="item";;

changeColor(currentThemeId);
//for toggling help facility ON/OFF
toggleHelpFacility("<?php echo $helpPermission; ?>");

//***********************for dragging/moving "Help" Divs************************
var draggers=document.getElementsByTagName('div');
for (var i_tem = 0; i_tem < draggers.length; i_tem++){
 if ( draggers[i_tem].id=='divHelpInfo'){
	Drag.init(draggers[i_tem]);
   }
}
</script>

<?php
	  require_once(MODEL_PATH . "/LoginManager.inc.php");
	  $loginManager = LoginManager::getInstance();
	  $loginManager->saveCurrentAccessedDetails();


//$History: footer.php $
//
//*****************  Version 19  *****************
//User: Parveen      Date: 4/16/10    Time: 10:16a
//Updated in $/LeapCC/Templates
//populateSubjectInfoValues function updated
//
//*****************  Version 18  *****************
//User: Parveen      Date: 4/15/10    Time: 5:23p
//Updated in $/LeapCC/Templates
//subjectInformation new functionality added
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 30/03/10   Time: 17:41
//Updated in $/LeapCC/Templates
//Modified UI of "Suggest A Feature" pop-up div
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 7/01/10    Time: 10:43
//Updated in $/LeapCC/Templates
//Added code for new multiple selected dropdowns in common
//student/parent/employee search filters
//
//*****************  Version 15  *****************
//User: Rahul.nagpal Date: 11/14/09   Time: 1:01p
//Updated in $/LeapCC/Templates
//sugguest a feature link is removed and now on dynamic footer
//
//*****************  Version 14  *****************
//User: Rahul.nagpal Date: 11/11/09   Time: 11:40a
//Updated in $/LeapCC/Templates
//footer text improvements.
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 6/11/09    Time: 15:18
//Updated in $/LeapCC/Templates
//Corrected "Help Related" problems
//
//*****************  Version 12  *****************
//User: Rahul.nagpal Date: 11/06/09   Time: 11:46a
//Updated in $/LeapCC/Templates
//
//*****************  Version 11  *****************
//User: Rahul.nagpal Date: 11/03/09   Time: 3:56p
//Updated in $/LeapCC/Templates
//Added still footer for teacher login
//
?>
<script>
  try {
      //For adjusting menu lookup
      if(navigator.appName=="Microsoft Internet Explorer")
      {
        document.getElementById("menuLookupContainer").style.right=(((document.documentElement.clientWidth/2)-498)) + "px";   
        document.getElementById("menuLookupContainer").style.width="204px";   
        document.getElementById("menuLookupContainer").style.marginTop="20px";   
      }
      else
      {
        document.getElementById("menuLookupContainer").style.right=(((document.documentElement.clientWidth/2)-499)) + "px";
        document.getElementById("menuLookupContainer").style.width="202px"
        document.getElementById("menuLookupContainer").style.marginTop="-1px"
      }
      
      Event.observe(document, 'click', function(event) {
       document.getElementById("menuLookupContainer").style.display='none';    
      })
      //$$('[id!="menuLookupContainer"]');
  } catch (e) {}
  
  
   function getTimeTablePeriodSlotPopulate() {
       url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';
       document.getElementById('periodRowDiv').innerHTML = ''; 
       if(document.getElementById('periodSlotId').value=='') {
         return false; 
       }
       
       var type='periodSlot';
       var val= document.getElementById('periodSlotId').value;
       
       new Ajax.Request(url,
       {
         method:'post',
         asynchronous:false,
         parameters: {type: type,
                      id: val},
         onCreate: function() {
             showWaitDialog(true); 
        },
        onSuccess: function(transport){
           hideWaitDialog(true);
           j = eval('('+transport.responseText+')'); 
           document.getElementById('periodRowDiv').innerHTML = j;  
        },
        onFailure: function(){ alert('Something went wrong...') }
       }); 
   }
</script>
<?php
  global $FE; 
  require_once(BL_PATH . "/Index/eventWishes.php");
?>  