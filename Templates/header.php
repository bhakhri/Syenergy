<?php
// Will display the Registration Form For The Selected Classes On Login Untill Student Submits It
global $sessionHandler;
if($sessionHandler->getSessionVariable('RoleId')==4) {
	
	require_once(MODEL_PATH . "/RegistrationForm/ScStudentRegistration.inc.php");
	$studentRegistration = StudentRegistration::getInstance();
    
    $ttStudentId = $sessionHandler->getSessionVariable('StudentId');
    $ttClassId = $sessionHandler->getSessionVariable('ClassId');
    
	$countClassId= $studentRegistration->countClassId($ttStudentId,$ttClassId);
    $isAllow = $studentRegistration->getMentorAllow($ttStudentId,$ttClassId);
	//check for fee paid to allow registration automatically  
	if($isAllow[0]['isAllowRegistration']=='0'){
	$feeRecordArray = $studentRegistration->getStudentFeeDetails($ttStudentId,$ttClassId);
		   
		if(count($feeRecordArray)>0){
		  $updateIsAllowStatus = $studentRegistration->getUpdateMentorAllow($ttStudentId,$ttClassId);			
		}
	}     
    $enableClasses=$studentRegistration->getEnableClasses();
    $enableClasses=explode(",",$enableClasses[0]['value']);
    
    for($i=0;$i<sizeof($enableClasses);$i++) {
		if ($enableClasses[$i]==$ttClassId) {
			
            if($isAllow[0]['isAllowRegistration']=='1') {
             if($countClassId[0]['count(classId)']=='0') {
                 if(strpos($_SERVER["SCRIPT_FILENAME"],"studentRegistration")>0) {
				 
				 }
				 else {
				  header("Location:".UI_HTTP_PATH."/RegistrationForm/Student/studentRegistration.php");				            
       			   break;
				 }
			   }
               else {
                 if(strpos($_SERVER["SCRIPT_FILENAME"],"studentRegistration")>0) {
                    header("Location: ".UI_HTTP_PATH."/Student/");
                    break;
                 } 
               }
            }
		}
	}
}
 
	//checking if the user is  not logged in, then throw the user out
	$roleId = $sessionHandler->getSessionVariable('RoleId');
	if (empty($roleId) or $roleId == '') {
		redirectBrowser(UI_HTTP_PATH.'/sessionError.php');
	}
	if(!empty($REQUEST_DATA['selectedRoleId']) and $_SERVER['REQUEST_METHOD']=='POST'){

		//echo $REQUEST_DATA['selectedRoleId'];
	  require_once(MODEL_PATH . "/LoginManager.inc.php");
	  $loginManager = LoginManager::getInstance();

      //clearing previous session data
      $accessArray = $loginManager->getAccessArray();
      foreach($accessArray as $accessRecord) {
            $sessionHandler->setSessionVariable($accessRecord['moduleName'],'');
      }

	  $selectedRoleIdArr = explode("~", $REQUEST_DATA['selectedRoleId']);
      $sessionHandler->setSessionVariable('DefaultRoleId',$selectedRoleIdArr[0]);
      $sessionHandler->setSessionVariable('RoleId',$selectedRoleIdArr[0]);
	  $sessionHandler->setSessionVariable('RoleType',$selectedRoleIdArr[2]);
	  $sessionHandler->setSessionVariable('RoleName',$selectedRoleIdArr[1]);

      $accessArray = $loginManager->getAccessArray();
      foreach($accessArray as $accessRecord) {
            $sessionHandler->setSessionVariable($accessRecord['moduleName'],
                Array (
                'view'    =>    $accessRecord['viewPermission'],
                'add'    =>    $accessRecord['addPermission'],
                'edit'    =>    $accessRecord['editPermission'],
                'delete'=>    $accessRecord['deletePermission']
                )
            );
        }

	  if($selectedRoleIdArr[0]==5){

		 $employeeRet = $loginManager->getEmployeeDetail($sessionHandler->getSessionVariable('UserId'));
		 if(is_array($employeeRet) && count($employeeRet) >0) {

			$sessionHandler->setSessionVariable('EmployeeId',$employeeRet[0]['employeeId']);
			$sessionHandler->setSessionVariable('EmployeeName',$employeeRet[0]['employeeName']);
			$sessionHandler->setSessionVariable('EmployeeCode',$employeeRet[0]['employeeCode']);
			$sessionHandler->setSessionVariable('EmployeeEmail',$employeeRet[0]['emailAddress']);
            $sessionHandler->setSessionVariable('EmployeeAbbreviation',$employeeRet[0]['employeeAbbreviation']);                         // update user's login time in user_log table
			$loginManager->updateUserLogTimeIn();

			?>
			<script language='javascript'>
				location.href='<?php echo UI_HTTP_PATH."/Management/index.php"?>';
			</script>
			<?php

		}
	 }
	 else if($selectedRoleIdArr[0]==2){

		$employeeRet = $loginManager->getEmployeeDetail($sessionHandler->getSessionVariable('UserId'));
		if(is_array($employeeRet) && count($employeeRet) >0) {
			$sessionHandler->setSessionVariable('EmployeeId',$employeeRet[0]['employeeId']);
			$sessionHandler->setSessionVariable('EmployeeName',$employeeRet[0]['employeeName']);
			$sessionHandler->setSessionVariable('EmployeeCode',$employeeRet[0]['employeeCode']);
			$sessionHandler->setSessionVariable('EmployeeEmail',$employeeRet[0]['emailAddress']);
			$sessionHandler->setSessionVariable('EmployeeAbbreviation',$employeeRet[0]['employeeAbbreviation']);
			$sessionHandler->setSessionVariable('LoggedName',$studentRet[0]['employeeName']);
            //set session variable related to time table type
            $timeTableRecord = $loginManager->getTimeTableLabelType($sessionHandler->getSessionVariable('EmployeeId'));
            $sessionHandler->setSessionVariable('TeacherTimeTableLabelType',$timeTableRecord[0]['timeTableType']);
			// update user's login time in user_log table
			$loginManager->updateUserLogTimeIn();
			?>
			<script language='javascript'>
				location.href='<?php echo UI_HTTP_PATH."/Teacher/index.php"?>';
			</script>
			<?php



		}

	 }
	 else if(($selectedRoleIdArr[0]==1) || ($selectedRoleIdArr[0]>5)){

		// $sessionHandler->destroySession();
	 	$employeeRet = $loginManager->getEmployeeDetail($sessionHandler->getSessionVariable('UserId'));
        if(is_array($employeeRet) && count($employeeRet) >0) {
			  $sessionHandler->setSessionVariable('EmployeeName',$employeeRet[0]['employeeName']);
		}
		$loginManager->updateUserLogTimeIn();
		$sessionHandler->setSessionVariable('AdminId',$sessionHandler->getSessionVariable('UserId'));
		$moduleNameArray = $loginManager->getModuleList();
		foreach($moduleNameArray as $moduleRecord) {
			$sessionHandler->unsetSessionVariable($moduleRecord['moduleName']);
		}
		$accessArray = $loginManager->getAccessArray();
		foreach($accessArray as $accessRecord) {
			$sessionHandler->setSessionVariable($accessRecord['moduleName'],
				Array (
				'view'	=>	$accessRecord['viewPermission'],
				'add'	=>	$accessRecord['addPermission'],
				'edit'	=>	$accessRecord['editPermission'],
				'delete'=>	$accessRecord['deletePermission']
				)
			);
		}

		// function to fetch dashboard permissions
		$dashboardAccessArray = $loginManager->getDashboardAccessArray();
		if(count($dashboardAccessArray)){

			foreach($dashboardAccessArray as $dashboardAccessRecord) {

				$sessionHandler->setSessionVariable($dashboardAccessRecord['frameName'],$dashboardAccessRecord['frameId'] );
			}
		}
		else{
			$dashboardAccessArray = $loginManager->getDashboardFrame();
			foreach($dashboardAccessArray as $dashboardAccessRecord) {

				$sessionHandler->setSessionVariable($dashboardAccessRecord['frameName'],'');
			}
		}

	?>
	<script language='javascript'>

		location.href='<?php echo UI_HTTP_PATH."/indexHome.php"?>';
	</script>
	<?php
		}
   }
?>
<script>
	function showSuggesstionDetails(dv,w,h) {

		displayFloatingDiv(dv,'', w, h, 200, 180)
	}

	function validateSuggestionForm(frm, act) {

		var fieldsArray = new Array(
			new Array("suggestionSubject","<?php echo SELECT_SUGGESTION_SUBJECT;?>"),
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

    function showSubjectDetails(dv,w,h) {
       displayFloatingDiv(dv,'', w, h, 200, 180)
       recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
       linksPerPage = <?php echo LINKS_PER_PAGE;?>;
       getActiveTimeTableLabelClass();
       populateSubjectInfoValues(recordsPerPage,linksPerPage);
    }


</script><noscript><META http-equiv="refresh" content="0;URL=<?php echo UI_HTTP_PATH;?>/errorNoScript.php"></noscript>
<?php
	define('REQUIRED_FIELD', '<span class="redColorBig">* </span>');
 function floatingDiv_Start($id,$msg,$counter='',$wrapType='nowrap',$helpLink='',$helpFlag=0)
 {
        ?>

<div id="<?php echo $id; ?>" style="border:0px;display:none" class="modalContainer">
        <table border="0" cellpadding="0" cellspacing="0">
     <tr>
        <td class="top_left" width="16px" height="16px"></td>
      <td class="top_mid" height="16px"></td>
      <td class="top_right" width="16px" height="16px"></td>
     </tr>
     <tr>
        <td class="mid_left" width="16px">
        </td>
        <td style="background:#ffffff" <?php echo $wrapType?> valign="top">
     <div class="" style="height:25px">
     <table cellpadding="0px" cellspacing="0px" border="0" width="100%">
     <?php
      if(!$helpFlag){
     ?>
      <tr>
        <td class="popup_left" style="no-repeat; height:25px; width:5px"></td>
        <td class="popup_middle" style="repeat-x; height:25px;">
          <table cellpadding="0"  cellspacing="0" border="0" width="100%" align="center">
          <tr height="25">
           <td class="textLabel" id="divHeaderId<?php echo $counter;?>"><b><?php echo $msg; ?></b></td>
            <td align="right" class="textLabel" valign="middle"><?php echo  $helpLink ?>&nbsp;
            <a onclick="javascript:hiddenFloatingDiv('<?php echo $id; ?>');return false;" style="cursor:pointer; color:#000000"  class="textLabel"><img src="<?php echo IMG_HTTP_PATH?>/close_btn.gif" style="margin-bottom:-3px" title="Close"></a>&nbsp;</td>
           </tr>
         </table>
      </td>
        <td class="popup_right" style="no-repeat; height:25px; width:5px"></td>
       </tr>
       </table>
      <?php
      }
      else{
      ?>
       <tr>
        <td  style="no-repeat; height:10px; width:5px"></td>
        <td  bgcolor="#EAE8E3" style="repeat-x; height:20px;cursor1:move;" onmouseover="over=true;" onmouseout="over=false;" >
          <table cellpadding="0"  cellspacing="0" border="0" width="100%" align="center">
          <tr height="10">
           <td id="divHeaderId<?php echo $counter;?>"><b><?php echo $msg; ?></b></td>
            <td align="right" class="textLabel" valign="middle"><?php echo  $helpLink ?>&nbsp;
            <a onclick="javascript:hiddenFloatingDiv('<?php echo $id; ?>');return false;" style="cursor:pointer; color:#000000"  class="textLabel"><img src="<?php echo IMG_HTTP_PATH?>/close_btn.gif" style="margin-bottom:-3px" title="Close"></a>&nbsp;</td>
           </tr>
         </table>
      </td>
        <td style="no-repeat; height:10px; width:5px"></td>
       </tr>
       </table>
   <HR>
      <?php
      }
      ?>

    </div>
        <?php
 }

function floatingDiv_End()
 {
   ?>
       </td>
        <td class="mid_right" width="16px">
    </td>
    </tr>
    <tr>
        <td class="bottom_left" width="16px" height="16px"></td>
        <td class="bottom_mid" height="16px"></td>
        <td class="bottom_right" width="16px" height="16px"></td>
    </tr>
</table>
    </div>
   <?php
 }

function floatingDiv_Create($id,$msg)
	{
		?>

		<div id="<?php echo $id; ?>"  <?php if($id=='div_Messages'){ echo 'style="height=250px;width:840px;background-color:#FFFFFF;display:block;"'; }else{ echo 'style="height=200px;width:270px;background-color:#FFFFFF;display:block;"';} ?>  >
     <div class="rounded" style="background-color:#DE453D; height:25px; border-color:#DE453D9; border:2px;"  >

	 	 <table cellpadding="0"  cellspacing="0" border="0" width="100%" height="100%" >
	  <tr>
	   <td height="100%" class="textLabel" align="left">&nbsp;<?php echo $msg; ?></td>

	   </tr>
	 </table>
	</div>
		<?php
	}

function floatingDiv_Close()
 {
   ?>
    </div>
   <?php
 }
  
 //compatible code of Sc/Cc for dashboard
  if(CURRENT_PROCESS_FOR=='sc' && $sessionHandler->getSessionVariable('RoleId')==2) {
      $dashboard = UI_HTTP_PATH.'/Teacher/scIndex.php';
  }
  else if(CURRENT_PROCESS_FOR=='sc' && $sessionHandler->getSessionVariable('RoleId')==3) {
      $dashboard = UI_HTTP_PATH.'/Parent/scIndex.php';
  }
  else if(CURRENT_PROCESS_FOR=='sc' && $sessionHandler->getSessionVariable('RoleId')==4) {
      $dashboard = UI_HTTP_PATH.'/Student/scIndex.php';
  }
  else if(CURRENT_PROCESS_FOR=='sc' && $sessionHandler->getSessionVariable('RoleId')==5) {
      $dashboard = UI_HTTP_PATH.'/Management/scIndex.php';
  }
  else {
      $dashboard = UI_HTTP_PATH.'/index.php';
  }

 ?>
 <?php
   //seting new session based upon user selection
   if(!empty($REQUEST_DATA['sessionId']) and $_SERVER['REQUEST_METHOD']=='POST'){
     require_once(MODEL_PATH . "/LoginManager.inc.php");
     $loginManager = LoginManager::getInstance();
	 $sessionHandler->setSessionVariable('SessionId',$REQUEST_DATA['sessionId']);
	 $sessionDetailArray = $loginManager->getSessionDetail($REQUEST_DATA['sessionId']);
	 $sessionYear = $sessionDetailArray[0]['sessionYear'];
	 $sessionHandler->setSessionVariable('SessionYear',$sessionYear);
     if($sessionHandler->getSessionVariable('RoleId')==2 and $sessionHandler->getSessionVariable('EmployeeId')!=''){
       //set session variable related to time table type
       $timeTableRecord = $loginManager->getTimeTableLabelType($sessionHandler->getSessionVariable('EmployeeId'));
       $sessionHandler->setSessionVariable('TeacherTimeTableLabelType',$timeTableRecord[0]['timeTableType']);
     }
	  echo '<script language="javascript" type="text/javascript">window.location=document.referrer;</script>';
   }
   if(!empty($REQUEST_DATA['currentInstituteId']) and $_SERVER['REQUEST_METHOD']=='POST'){

       require_once(MODEL_PATH . "/LoginManager.inc.php");
       $loginManager = LoginManager::getInstance();

       //clearing previous session data
       $accessArray = $loginManager->getAccessArray();
       foreach($accessArray as $accessRecord) {
            $sessionHandler->setSessionVariable($accessRecord['moduleName'],'');
        }

	  $sessionHandler->setSessionVariable('InstituteId',$REQUEST_DATA['currentInstituteId']);

		//FETCH VALUES FROM CONFIG TABLE AND STORE INTO SESSION
		$configArray = $loginManager->getConfigSettings();
		if (is_array($configArray) && count($configArray)) {
			foreach($configArray as $configRecord) {
				$sessionHandler->setSessionVariable($configRecord['param'],$configRecord['value']);
			}
		}
        //********fetches roles corresponding to selected institute*********
        $allRoleArray = $loginManager->getAllUserRole();
        if (is_array($allRoleArray) && count($allRoleArray)) {
          $sessionHandler->setSessionVariable('roleArray',$allRoleArray);
        }

        /*FINDING DEFAULT ROLE OF THE LOGGED IN USER*/
        $defaultRoleArray=$loginManager->getDefaultUserRole($sessionHandler->getSessionVariable('UserId'),$sessionHandler->getSessionVariable('InstituteId'));
        if(is_array($defaultRoleArray) and count($defaultRoleArray)>0){
          if($defaultRoleArray[0]['defaultRoleId']!='' and $defaultRoleArray[0]['defaultRoleId']!=0){
           $sessionHandler->setSessionVariable('DefaultRoleId',$defaultRoleArray[0]['defaultRoleId']);
           $sessionHandler->setSessionVariable('RoleId',$defaultRoleArray[0]['defaultRoleId']);
          }
          else{
            $sessionHandler->setSessionVariable('DefaultRoleId',$sessionHandler->getSessionVariable('RoleId'));
          }
        }
        else{
           $sessionHandler->setSessionVariable('DefaultRoleId',$sessionHandler->getSessionVariable('RoleId'));
        }

        $accessArray = $loginManager->getAccessArray();
        foreach($accessArray as $accessRecord) {
            $sessionHandler->setSessionVariable($accessRecord['moduleName'],
                Array (
                'view'    =>    $accessRecord['viewPermission'],
                'add'    =>    $accessRecord['addPermission'],
                'edit'    =>    $accessRecord['editPermission'],
                'delete'=>    $accessRecord['deletePermission']
                )
            );
        }

        // function to fetch dashboard permissions
        $dashboardAccessArray = $loginManager->getDashboardAccessArray();
        if(count($dashboardAccessArray)){
            foreach($dashboardAccessArray as $dashboardAccessRecord) {
                $sessionHandler->setSessionVariable($dashboardAccessRecord['frameName'],$dashboardAccessRecord['frameId'] );
            }
        }
        else{
            $dashboardAccessArray = $loginManager->getDashboardFrame();
            foreach($dashboardAccessArray as $dashboardAccessRecord) {
                $sessionHandler->setSessionVariable($dashboardAccessRecord['frameName'],'');
            }
        }

      if($sessionHandler->getSessionVariable('RoleId')==2 and $sessionHandler->getSessionVariable('EmployeeId')!=''){
       //set session variable related to time table type
       $timeTableRecord = $loginManager->getTimeTableLabelType($sessionHandler->getSessionVariable('EmployeeId'));
       $sessionHandler->setSessionVariable('TeacherTimeTableLabelType',$timeTableRecord[0]['timeTableType']);
     }

     if($sessionHandler->getSessionVariable('RoleId')==5){
        ?>
         <script language='javascript'>
                location.href='<?php echo UI_HTTP_PATH."/Management/index.php"?>';
         </script>
        <?php
     }
     else if($sessionHandler->getSessionVariable('RoleId')==2){
         ?>
           <script language='javascript'>
                location.href='<?php echo UI_HTTP_PATH."/Teacher/index.php"?>';
           </script>
         <?php
     }
     else if(($sessionHandler->getSessionVariable('RoleId')==1) || ($sessionHandler->getSessionVariable('RoleId')>5)){
         ?>
          <script language='javascript'>
           location.href='<?php echo UI_HTTP_PATH."/indexHome.php"?>';
          </script>
         <?php
     }

   }

 ?>

 <?php
    /******CODE SNIPPET FOR GROUPING*****/
    if($sessionHandler->getSessionVariable('UserExpandCollapseGrouping')==1){
        $checked1='checked';
        $chkImgSrc1='group_icon.gif';
        $chkImgTitle1='Make Grouping Off';
    }
    else{
    $checked1='';
    $chkImgSrc1='group_icon_bw.gif';
    $chkImgTitle1='Make Grouping On';
    }
    if(EXPAND_COLLAPSE_CONFIG_PERMISSION=='1'){ //if expand/collapse permission is set in config file
    ?>
       <input type="checkbox" name="groupingChk" id="groupingChk" onclick="changeGroupingFacility(this);" <?php echo $checked; ?> style="display:none" />&nbsp;
    <?php
   }
   ?>

<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
   <td width="245" valign="top"><?php
		
			require_once(MODEL_PATH . "/InstituteManager.inc.php");
			$instituteManager =  InstituteManager::getInstance();
			global $sessionHandler;
			$logoArray = $instituteManager->checkLogoName($sessionHandler->getSessionVariable('InstituteId'));
			if (isset($logoArray[0]['instituteLogo']) and !empty($logoArray[0]['instituteLogo'])) {
				echo '<a href="'.$dashboard.'" title="'.$logoArray[0]['instituteName'].'"><img name="logo" src="'.IMG_HTTP_PATH.'/Institutes/'.$logoArray[0]['instituteLogo'].'" border="0" width="220" height="67" /></a>';
			}
			else{

				echo '<a href="'.$dashboard.'"><img src="'.IMG_HTTP_PATH.'/logo.gif" border="0" width="220" height="67"></a>';
			}
   ?></td>
    <td  valign="top" align="right">
        <table border="0" cellspacing="0" cellpadding="3" align="right">
        <tr>
            <!-- FOR TEACHERS DAY -->
            <?php 
            $dt = date('m-d');
            if($dt=='09-05' && ($sessionHandler->getSessionVariable('RoleId')!=3 && $sessionHandler->getSessionVariable('RoleId')!=4) )  { ?>        
               <td>
                <div style="width:147px; border:0px solid #000; font-family:arial; font-size:14px; position:relative; top:0px; 
                         text-align: center;background-image: url('<?php echo IMG_HTTP_PATH; ?>/teachers_day_greet.gif'); 
                        background-repeat:no-repeat; height:64px;"></div>
              </td>    
            <?php 
            } 
            ?>
            <!-- FOR TEACHERS DAY Ends -->
            <td class='fontTitleM' align="right" nowrap="nowrap" >
             <?php
			 //*****************code for SUPER USER Re-Login goes here*******************
			   if($sessionHandler->getSessionVariable('RoleId')==3 or $sessionHandler->getSessionVariable('RoleId')==4){
				   if($sessionHandler->getSessionVariable('SuperUserId')!=''){
					   echo '<a class="redLinkSimple" href="Javascript:void(0)" onclick="makeSuperUser();"><b>[ Back to My Login ]</b></a>';
				   }
			   }
			   if($sessionHandler->getSessionVariable('RoleId')==1){
				 echo '<a class="redLinkSimple" href="'.UI_HTTP_PATH.'/superLoginList.php"><img src="'.IMG_HTTP_PATH.'/star.gif" title="Super Login"></a>&nbsp;';
			   }
			 //**************************************************************************
			 ?>
		 </td>
	     <td colspan='1' class='fontTitleM' style='padding-right:5px;' nowrap="nowrap">
			<?php
			if ($sessionHandler->getSessionVariable('RoleId') == 2) {
			?>
            <nobr><a class='redLinkSimple' href='<?php echo HTTP_LIB_PATH ;?>/download.php?t=fm'>Download Manual</a></nobr>&nbsp;|&nbsp;<nobr><a class='redLinkSimple' href='<?php echo UI_HTTP_PATH.'/Teacher' ;?>/listFaq.php'>FAQ</a></nobr>&nbsp;|&nbsp;
			<?php
			 }
			 else {
				 if ($sessionHandler->getSessionVariable('RoleId') != 2 and $sessionHandler->getSessionVariable('RoleId') != 3 and $sessionHandler->getSessionVariable('RoleId') != 4 and $sessionHandler->getSessionVariable('RoleId') != 5) {
			 ?>
             <nobr><a class='redLinkSimple' href='<?php echo UI_HTTP_PATH;?>/listFaq.php'>FAQ</a></nobr>&nbsp;|&nbsp;
             <?php
			 }
			 }
			 ?>
            <nobr><a class='redLinkSimple' href='<?php echo UI_HTTP_PATH ;?>/siteMap.php'>Site Map</a></nobr>&nbsp;|&nbsp;</nobr>
			<nobr>
	<?php 
			if (defined('SHOW_BROADCAST') and SHOW_BROADCAST == true) {

			require_once($FE . "/Library/common.inc.php");
			require_once(MODEL_PATH . "/BroadcastFeatureManager.inc.php");

			$conn = mysqli_connect(BROADCAST_DB_HOST,BROADCAST_DB_USER,BROADCAST_DB_PASS) or die('could not find host');
			mysqli_select_db($conn,BROADCAST_DB_NAME) or die('could not connect to database');
			$recordArray = selectNewBroadcast();
			$cnt=0;
			if($row = mysqli_fetch_Array($recordArray)) {
			  $cnt = $row['cnt'];
			}
			if($cnt == 0) {
			?>
				<a class='redLinkSimple'  href='<?php echo UI_HTTP_PATH;?>/listNewFeature.php'>New Feature</a>
			<?php }
			else {
			?>
				<a class='redLinkSimple' href='<?php echo UI_HTTP_PATH;?>/listNewFeature.php'><b>New Feature</b></a> 
			<?php
			}
			 } ?>
			<!-- <a class='redLinkSimple' target='_blank' href='<?php echo UI_HTTP_PATH;?>/listNewFeature.php'><b>New Feature</b></a>  -->
								
		 </td>

         <!-- Help On -->
            <?php
                if($sessionHandler->getSessionVariable('RoleId')==1 OR $sessionHandler->getSessionVariable('RoleId')==2) {
						if ($sessionHandler->getSessionVariable('HELP_PERMISSION') == '') {
							$helpPermission = 1;
						}
						else {
							$helpPermission = $sessionHandler->getSessionVariable('HELP_PERMISSION');
						}
            ?>
               <td align="right" nowrap="nowrap">
                      <?php

					if($helpPermission==1){
                        $checked='checked';
                        $chkImgSrc='help_on.gif';
                        $chkImgTitle='Help Off';
                     }
                     else{
                         $checked='';
                         $chkImgSrc='help_off.gif';
                         $chkImgTitle='Help On';
                     }
                    ?>
                  <input type="checkbox" name="helpChk" id="helpChk" onclick="changeHelpFacility(this.checked);" <?php echo $checked; ?> style="display:none"  />&nbsp;

               </td>
               <td style="padding-top:3px;" nowrap="nowrap"><input type="image" id="helpChkImg" src="<?php echo IMG_HTTP_PATH; ?>/<?php echo $chkImgSrc; ?>" title="<?php echo $chkImgTitle; ?>" onclick="document.getElementById('helpChk').checked=!document.getElementById('helpChk').checked; changeHelpFacility(document.getElementById('helpChk'));">&nbsp;&nbsp;
               <?php
                if(EXPAND_COLLAPSE_CONFIG_PERMISSION=='1'){ //if expand/collapse permission is set in config file
               ?>
                <img id="groupingChkImg" src="<?php echo IMG_HTTP_PATH; ?>/<?php echo $chkImgSrc1; ?>" title="<?php echo $chkImgTitle1; ?>" onclick="document.getElementById('groupingChk').checked=!document.getElementById('groupingChk').checked;changeGroupingFacility(document.getElementById('groupingChk'));">
              <?php
                }
              ?>
               </td>
            <?php
                }
              else{
            ?>
                  <td align="right" >
                   <?php
                   //for other roles
                   if(EXPAND_COLLAPSE_CONFIG_PERMISSION=='1'){ //if expand/collapse permission is set in config file
                   ?>
                   <img id="groupingChkImg" src="<?php echo IMG_HTTP_PATH; ?>/<?php echo $chkImgSrc1; ?>" title="<?php echo $chkImgTitle1; ?>" onclick="document.getElementById('groupingChk').checked=!document.getElementById('groupingChk').checked;changeGroupingFacility(document.getElementById('groupingChk'));">
                   <?php
                   }
                   ?>
                  </td>
                  <td align="right" >&nbsp;  </td>
             <?php
                }
             ?>
        <!-- Help Off -->

            <!-- <td height="20" class="text_topright_links">&nbsp;<span class="fontTitleM"><?php echo $sessionHandler->getSessionVariable('InstituteName');?> | <?php echo $sessionHandler->getSessionVariable('SessionCode');?></span></td> -->
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
            <td height="20" align="right" class="text_topright_links" nowrap>&nbsp;

             <span class="fontTitleM">
			 <?php
			 if ($sessionHandler->getSessionVariable('RoleId') == 1) {
			 ?>
			 <select name="currentInstituteId" id="currentInstituteId" style="width:70px" class="selectfield" onchange="if(this.value!=''){this.form.submit();}">
                <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getInstituteData($sessionHandler->getSessionVariable('InstituteId'));
                ?>
            </select>
			 <?php
			 }
			 //else if ($sessionHandler->getSessionVariable('RoleId') == 2) {
             //if not student/parent
             else if ($sessionHandler->getSessionVariable('RoleId') != 3 and $sessionHandler->getSessionVariable('RoleId') != 4) {
			 ?>
			 <select name="currentInstituteId" id="currentInstituteId" style="width:70px" class="selectfield" onchange="if(this.value!=''){this.form.submit();}">
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getEmployeeInstitutes();
            ?>
            </select>
			 <?php
			 }
			 else {
				echo $sessionHandler->getSessionVariable('InstituteName');
			 }
			 ?>

             </form>&nbsp; |
            &nbsp;
			<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
             <select name="sessionId" id="sessionId" style="width:90px" class="selectfield" onchange="if(this.value!=''){this.form.submit();}">
                <option value="">select</option>
                <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getSessionData($sessionHandler->getSessionVariable('SessionId'),'sessionName','2');
                ?>
            </select>
            </form>
		   <?php
                $roleSession = $sessionHandler->getSessionVariable('roleArray');
                $roleId2=$sessionHandler->getSessionVariable('RoleId');
				if (is_array($roleSession) && count($roleSession)>0 && $roleId2!=3 and $roleId2!=4) {
					$countRole = count($roleSession);
		   ?>&nbsp; |
            &nbsp;
             <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
             <select name="selectedRoleId" id="selectedRoleId" style="width:100px" class="selectfield" onchange="if(this.value!=''){this.form.submit();}">
                <!--option value="">Select Role</option-->
                <?php
				$cnt=0;
				for($cnt=0;$cnt<$countRole;$cnt++) {
                    $ttStr = $roleSession[$cnt]['roleId'].'~'.$roleSession[$cnt]['roleName'].'~'.$roleSession[$cnt]['roleType'];
                    if($roleSession[$cnt]['roleId']==$sessionHandler->getSessionVariable('DefaultRoleId')){
						echo "<option value='".$ttStr."' SELECTED>".$roleSession[$cnt]['roleName']."</option>";
					}
					else{
						echo "<option value='".$ttStr."'>".$roleSession[$cnt]['roleName']."</option>";
					}
				}
                ?>
            </select>

		   <?php
				}
			?>
			</form>
             </span>
            </td>
			<td nowrap height="20" align='right' class="text_topright_links" style='border:0px solid #000000; text-align:right;'>&nbsp;<a href="<?php echo $dashboard;?>"><img id="homeImage" alt="home" title="home" onMouseOver="changeHomeImage('over');" onMouseOut="changeHomeImage('out');" src="<?php echo IMG_HTTP_PATH;?>/home1.gif"></a> |
			<img src="<?php echo IMG_HTTP_PATH; ?>/blue_button.gif"        alt="Default"     title="Default"       onclick="void(changePrefs(1))" style="height:15px;width:15px" />
			<img src="<?php echo IMG_HTTP_PATH; ?>/brown_button.gif"       alt="Brown"       title="Brown"         onclick="void(changePrefs(2))" style="height:15px;width:15px" />
			<img src="<?php echo IMG_HTTP_PATH; ?>/green_button.gif"       alt="Green"       title="Green"         onclick="void(changePrefs(3))" style="height:15px;width:15px" /> 
			<img src="<?php echo IMG_HTTP_PATH; ?>/round_orange.gif"  alt="Orange"  title="Orange"    onclick="void(changePrefs(4))" style="height:15px;width:15px" /><br>&nbsp;&nbsp;&nbsp;
			<img src="<?php echo IMG_HTTP_PATH; ?>/green_light_button.gif" alt="Light Green" title="Light Green"   onclick="void(changePrefs(5))" style="height:15px;width:15px" />
			<img src="<?php echo IMG_HTTP_PATH; ?>/round_v.gif"         alt="Violet"         title="Violet"           onclick="void(changePrefs(6))" style="height:15px;width:15px" />
			<img src="<?php echo IMG_HTTP_PATH; ?>/blue_light_buton.gif"   alt="Blue Light"  title="Blue Light"    onclick="void(changePrefs(7))" style="height:15px;width:15px" />
			<?php //echo date('F').'&nbsp;'.date('j').',&nbsp;'.date('Y')?>
			</td>
			</tr>
			<tr>

			<td><br /></td>
			</tr>
			<tr>
			<td align="right" colspan="10">
			<?php
				$str = $sessionHandler->getSessionVariable('StudentName')." (".$sessionHandler->getSessionVariable('UserName').") ".($sessionHandler->getSessionVariable('EmployeeName')!='' ? ', '.$sessionHandler->getSessionVariable('EmployeeName') : '');
			?>
			<?php
			 //*******************************************************
			 //Purpose: To Add a notification in 'notifications' table
			 //Author: Kavish Manjkhola
			 //Created On: 04/04/2011
			 //Modified On: 19/04/2011
			 //Copyright 2010-2011: syenergy Technologies Pvt. Ltd.
			 //****************************************************
			 require_once(MODEL_PATH . "/InsuranceVehicleManager.inc.php");
			 $insuranceVehicleManager = InsuranceVehicleManager::getInstance();
			 $dayLimit = $sessionHandler->getSessionVariable('DAY_LIMIT_FOR_NOTIFICATION');	//No of days for which the notice has to be selected;
			 $ctr = 0;			
			 $flag = 0;
			 $check = 1;
			 $getVehicleInsuranceList = $sessionHandler->getSessionVariable('insurancePayRemainingTimeArray');
			 if($getVehicleInsuranceList == '' OR $getVehicleInsuranceList == NULL) {
				$insurancePayRemainingTime = $insuranceVehicleManager->getInsuranceDueDateList($dayLimit);
				$sessionHandler->setSessionVariable('insurancePayRemainingTimeArray',$insurancePayRemainingTime);
				$recordCount = $insurancePayRemainingTime[$ctr]['recordCount'];
				if($recordCount > 0) {
					$getInsuranceMessageSearch = $insuranceVehicleManager->getInsuranceMessageSearch();
					$totalRecords = $getInsuranceMessageSearch[$ctr]['totalRecords'];
					if($totalRecords == 0) {
						$message = $insurancePayRemainingTime[$ctr]['message'];
						$returnStatus = $insuranceVehicleManager->insertNotifications($message);
					}
					else {
						$updateNoticePublishTime = $insuranceVehicleManager->updateNoticePublishTime();
					}
				}
			 }
			 $status = $sessionHandler->getSessionVariable('notificationStatus');
			 if($status == '' OR $status == NULL) {
				 $getNotificationViewStatus	= $insuranceVehicleManager->getNotificationViewStatus();
				 $totalRecords = count($getNotificationViewStatus);
				 for($i=0; $i<$totalRecords; $i++) {
					$viewStatus = $getNotificationViewStatus[$i]['viewStatus'];
					if($viewStatus == 0) {
						$flag = 1;
						$check++;
						break;
					}
				 }
			 }
			 if($check == 1) {
				$sessionHandler->setSessionVariable('notificationStatus',$check);
			 }
             if($flag == 1) {
			 ?>
			<!--	<font color="#666666" size="2"> [</font><a href="<?php echo UI_HTTP_PATH;?>/listNotifications.php" class="redLink"><blink> New Notification(s)</blink></a><font color="#666666" size="2">]</font>&nbsp;&nbsp;&nbsp;&nbsp; -->
			<?php
			}
			?>
			<span class="fontTitleM">Welcome&nbsp;<?php echo $str; ?></span>&nbsp;&nbsp; <font color="#666666" size="2"> [</font><a href="<?php echo UI_HTTP_PATH;?>/logout.php" class="redLink">Logout</a><font color="#666666" size="2">]</font>
			</td>
			 <?php
				 if((file_exists(STORAGE_PATH.'/Images/'.$sessionHandler->getSessionVariable('userPhoto'))) && $sessionHandler->getSessionVariable('userPhoto')&& filesize(STORAGE_PATH.'/Images/'.$sessionHandler->getSessionVariable('userPhoto'))){
                 $imgPhoto = $sessionHandler->getSessionVariable('userPhoto')."?zz=".rand(0,1000);
			?>
            <td height="20" rowspan="4" >&nbsp;<a href='#' class='opacityit'>
            <img id='studentImageId' src='<?php echo STORAGE_HTTP_PATH.'/Images/'.$imgPhoto;?>' width='60' height='60' hspace="1" vspace="1" border="0"></a>&nbsp;</td>
			<?php
			}
			?>
         </tr>
         <!--tr>
			<td colspan="6" align="right" style="padding-right:17px;padding-top:3px">
           <img src="<?php echo IMG_HTTP_PATH; ?>/blue_button.gif"        alt="Default"     title="Default"       onclick="void(changePrefs(1))" style="height:15px;width:15px" />
           <img src="<?php echo IMG_HTTP_PATH; ?>/brown_button.gif"       alt="Brown"       title="Brown"         onclick="void(changePrefs(2))" style="height:15px;width:15px" />
           <img src="<?php echo IMG_HTTP_PATH; ?>/green_button.gif"       alt="Green"       title="Green"         onclick="void(changePrefs(3))" style="height:15px;width:15px" />
           <img src="<?php echo IMG_HTTP_PATH; ?>/round_orange.gif"  alt="Orange"  title="Orange"    onclick="void(changePrefs(4))" style="height:15px;width:15px" />
           <img src="<?php echo IMG_HTTP_PATH; ?>/green_light_button.gif" alt="Light Green" title="Light Green"   onclick="void(changePrefs(5))" style="height:15px;width:15px" />
           <img src="<?php echo IMG_HTTP_PATH; ?>/round_v.gif"         alt="Violet"         title="Violet"           onclick="void(changePrefs(6))" style="height:15px;width:15px" />
           <img src="<?php echo IMG_HTTP_PATH; ?>/blue_light_buton.gif"   alt="Blue Light"  title="Blue Light"    onclick="void(changePrefs(7))" style="height:15px;width:15px" /><br><br><span class="fontTitleM"  align="right"><?php echo date('F').'&nbsp;'.date('j').',&nbsp;'.date('Y')?>


		    <?php
				 if(file_exists(STORAGE_PATH.'/Images/'.$sessionHandler->getSessionVariable('userPhoto'))){
			?>
		   <span class="fontTitleM1"><B><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;". $sessionHandler->getSessionVariable('LoggedName')?></B></span></span>
		   <?php

			}
		   ?>
         </td>
         </tr-->
       <?php if($sessionHandler->getSessionVariable('RoleId') == 3 ) {
             // multiple child select box

             $studentRet = $sessionHandler->getSessionVariable('StudentArray');
             //echo $sessionHandler->getSessionVariable('StudentId');
             if(UtilityManager::notEmpty($REQUEST_DATA['childStudentId'])) {
               $sessionHandler->setSessionVariable('StudentId',$REQUEST_DATA['childStudentId']);
             }
             ?>
        <tr>
             <td height="20" colspan="7" align="right"><form action="" method="post">
             <select class="inputbox" name="childStudentId" id="childStudentId" onChange="this.form.submit();location.href='<?php echo $_SERVER['PHP_SELF'].'?'.md5(date('YmdHis'));?>';"><?php
             $cnt = count($studentRet);
             if($cnt>0 && is_array($studentRet)) {

                 for($i=0;$i<$cnt;$i++) {

                     if($studentRet[$i]['studentId']==$sessionHandler->getSessionVariable('StudentId') ) {
                        $sessionHandler->setSessionVariable('StudentName',$studentRet[$i]['studentName']);
                        $sessionHandler->setSessionVariable('ClassId',$studentRet[$i]['classId']);
                        $selected = 'selected="SELECTED"';
                     }
                     else {
                         $selected = '';
                     }

                     echo '<option value="'.$studentRet[$i]['studentId'].'" '.$selected.'>'.$studentRet[$i]['studentName'].'</option>';
                 }
             }

             ?></select>&nbsp;&nbsp;&nbsp;</form></td>

         </tr>
         <?php }?>
         </table>
     </td>
</tr>
<?php
 //code for showing broadcast message
 require_once(MODEL_PATH . "/BroadcastMessageManager.inc.php");
 $broadcastMsgArray=BroadcastMessageManager::getInstance()->getMessage('WHERE messageDate >= CURRENT_DATE()');
 if(count($broadcastMsgArray)>0 and is_array($broadcastMsgArray)){
     $msgText=trim($broadcastMsgArray[0]['messageText']);
 }
if($msgText!=''){
?>
<tr>
 <td class="contenttab_internal_rows" height="25px;" style="background-color:#FFFF00; border:0px solid #FFC142;padding-left:10px;padding-right:10px;color:#CC0000" colspan="2">
  <marquee SCROLLDELAY="150"><b><?php echo $msgText; ?></b></marquee>
 </td>
</tr>
<?php
}
?>
<tr>
    <td valign="top" colspan="2">
     <?php
       // set global variable for image path;
       echo '<script type="text/javascript">interfacePathURL = "'.UI_HTTP_PATH.'";</script>';
       // set global variable for image path;
       echo '<script type="text/javascript">imagePathURL = "'.IMG_HTTP_PATH.'";</script>';
       // set global variable for css path;
       echo '<script type="text/javascript">cssPathURL = "'.CSS_PATH.'";</script>';
       // set global variable for js path;
       echo '<script type="text/javascript">jsPathURL = "'.JS_PATH.'";</script>';
       // set global variable for change pref file path;
       echo '<script type="text/javascript">themeFilePathURL = "'.HTTP_LIB_PATH.'";</script>';
       // set global variable for session timeout;
       echo '<script type="text/javascript">sessionTimeOut = "'.SESSION_TIMEOUT.'";</script>';
       // set global variable for current themeId;
       echo '<script type="text/javascript">currentThemeId = "'.$sessionHandler->getSessionVariable('UserThemeId').'";</script>';
       // set global variable for grouping facilities ;
       echo '<script type="text/javascript">EXPAND_COLLAPSE_CONFIG_PERMISSION = "'.EXPAND_COLLAPSE_CONFIG_PERMISSION.'";</script>';
       // set global variable for grouping facilities ;
       echo '<script type="text/javascript">EXPAND_COLLAPSE_USER_PERMISSION = "'.$sessionHandler->getSessionVariable('UserExpandCollapseGrouping').'";</script>';

       // set global variable for pagination link position
       echo '<script type="text/javascript">globalPaginationPosition = "'.($sessionHandler->getSessionVariable('PAGINATION_POSITION')!=''?$sessionHandler->getSessionVariable('PAGINATION_POSITION'):3).'";</script>';
	   if(CURRENT_PROCESS_FOR=="cc")
	   {
			require_once(TEMPLATES_PATH. "/menu.php");
			if($sessionHandler->getSessionVariable('RoleId') == 4 ) {
			 if(MODULE!='ADVFB_ProvideFeedBack' and MODULE!='StudentDashboard'){
				 if($sessionHandler->getSessionVariable('UserIdDisabledForInCompleteFeedback')==2){
					  echo '<script language="javascript">messageBox("You have to complete your feedback to get full access of your account");window.location="index.php";</script>';
				 }
           }
			}

		  /*
		   if($sessionHandler->getSessionVariable('RoleId') == 5 ) {
			   require_once(TEMPLATES_PATH. "/managementMenu.php");
		   }
		   elseif($sessionHandler->getSessionVariable('RoleId') == 4 ) {
			   require_once(TEMPLATES_PATH. "/studentMenu.php");
               if(MODULE!='ADVFB_ProvideFeedBack' and MODULE!='StudentDashboard'){
                   if($sessionHandler->getSessionVariable('UserIdDisabledForInCompleteFeedback')==2){
                       echo '<script language="javascript">messageBox("You have to complete your feedback to get full access of your account");window.location="index.php";</script>';
                   }
               }
		   }
		   elseif($sessionHandler->getSessionVariable('RoleId') == 3 ) {
			   require_once(TEMPLATES_PATH. "/parentMenu.php");
		   }
		   elseif($sessionHandler->getSessionVariable('RoleId') == 2 ) {
			   require_once(TEMPLATES_PATH. "/teacherMenu.php");
		   }
		   elseif($sessionHandler->getSessionVariable('RoleId') == 1 ) {
			   require_once(TEMPLATES_PATH. "/menu.php");
		   }
		   else {

			   require_once(TEMPLATES_PATH. "/menu.php");
		   }
			*/
	   }
	   else
	   {
		   if($sessionHandler->getSessionVariable('RoleId') == 5 ) {
			   require_once(TEMPLATES_PATH. "/scManagementMenu.php");
		   }
		   elseif($sessionHandler->getSessionVariable('RoleId') == 4 ) {
			   require_once(TEMPLATES_PATH. "/scStudentMenu.php");
		   }
		   elseif($sessionHandler->getSessionVariable('RoleId') == 3 ) {
			   require_once(TEMPLATES_PATH. "/scParentMenu.php");
		   }
		   elseif($sessionHandler->getSessionVariable('RoleId') == 2 ) {
			   require_once(TEMPLATES_PATH. "/scTeacherMenu.php");
		   }
		   else {
			   require_once(TEMPLATES_PATH. "/scMenu.php");
		   }
	   }
     ?>
    </td>
</tr>
<tr>
<td valign="top" colspan="2">
              
