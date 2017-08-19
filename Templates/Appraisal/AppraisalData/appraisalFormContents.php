<?php
//-------------------------------------------------------
// Purpose: To design the layout for Employee Information.
// Author : Dipanjan Bhattacharjee
// Created on : (24.06.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<?php
require_once(BL_PATH.'/helpMessage.inc.php');
?>
<form method="POST" name="addForm"  id="addForm" method="post"  style="display:inline" onSubmit="return false;">                      
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td>Employee Appraisal</td>
                <td align="right">
                <?php
                 if($sessionHandler->getSessionVariable('OverrideAppraisalModuleAccess')==1){ 
                 ?>    
                  <INPUT type="image" alt="back" title="back" src="<?php echo IMG_HTTP_PATH ?>/bigback.gif" border="0" onClick='listPage("listEmployeeAppraisal.php?<?php echo $_SERVER['QUERY_STRING']; ?>");return false;'>&nbsp;&nbsp;&nbsp;
                 <?php
                 }
                 ?> 
                </td>
            </tr>                                             
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" height="670">
             <tr>
                <td class="contenttab_border" height="20">
                    <div class="content_title">Employee Appraisal
                    <?php
                     if($sessionHandler->getSessionVariable('OverrideAppraisalModuleAccess')==1){
                         echo " For ".$sessionHandler->getSessionVariable('EmployeeNameToBeAppraised').' ('.$sessionHandler->getSessionVariable('EmployeeCodeToBeAppraised').')';
                     }
                    ?> :
                    </div>                
                </td>
             </tr>
             <tr>
             <td class="contenttab_row" valign="top" >
             <?php
               $prefixedTabs="'Leaves','Reviewer','Compatibility'";
               $prefixedTabs2="false,false,false";
               $newTabs='';
               $newTabs2='';
			   $newTabs3='';
             ?>
             <div id="dhtmlgoodies_tabView1" >
              <?php
              $tabCount=0;
              $hodDisabled='disabled="disabled"';
              $hodEnabled='disabled="disabled"';
              if($sessionHandler->getSessionVariable('OverrideAppraisalModuleAccess')==1){
                  $hodEnabled='';
              }
              else{
                  $hodDisabled='';
              }
              if($appraisalDataCount>0){
                for($i=0;$i<$appraisalTabCount;$i++){
                  $tabId=$appraisalTabArray[$i];
                  $proofText =$proofTextArray[$tabId];
                  echo '<div class="dhtmlgoodies_aTab" style="vertical-align:top;">';
                  //echo $tabId;
                  $titleId=-1;
                  $titleId2=-1;
                  
                  echo '<table border="0" cellpadding="0" cellspacing="1" width="100%">';
                  echo  '<tr class="rowheading">
                            <td width="70%" colspan="2" align="center" class="searchhead_text">Activity</td>
                            <td class="searchhead_text">Proofs</td>
                            <td class="searchhead_text">Weightage</td>
                            <td class="searchhead_text">Self-Evaluation</td>
                            <td class="searchhead_text">Authentication By HOD/Dean</td>
                          </tr>';
                  $srNo=0;
                  $totalSelfPoints=0;
                  $totalHODPoints=0;
                  for($j=0;$j<$appraisalDataCount;$j++){
                    if($tabId==$appraisalDataArray[$j]['appraisalTabId']){
                       $tabName=$appraisalDataArray[$j]['appraisalTabName']; 
                       if($titleId!=$appraisalDataArray[$j]['appraisalTitleId']){
                          echo  '<tr>
                                    <td colspan="2" class="searchhead_text">'.$appraisalDataArray[$j]['appraisalTitle'].'</td>
                                    <td class="searchhead_text">&nbsp;</td>
                                    <td colspan="3" align="left" class="searchhead_text">'.$appraisalTitleMaxArray[$appraisalDataArray[$j]['appraisalTitleId']].' (Max)</td>
                                 </tr>';
                          $titleId=$appraisalDataArray[$j]['appraisalTitleId']; 
                          $srNo=1;
                          $totalSelfPoints=0;
                          $totalHODPoints=0;
                       }
                       
                       $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
                       
                       echo  '<tr '.$bg.'>
                                    <td width="2%" valign="top" class="padding_top">'.$srNo.'</td>
                                    <td valign="top" class="padding_top">'.$appraisalDataArray[$j]['appraisalText'].'</td>';
                       $seleEditable=1;
                       $maxValue=$appraisalDataArray[$j]['appraisalWeightage'];
                       $targetElementId='selfEvaluation_'.$j.'_'.$appraisalDataArray[$j]['appraisalId'].'_'.$maxValue.'_'.$tabCount;
                       
                       if($appraisalDataArray[$j]['appraisalProof']==1){
                          if($appraisalDataArray[$j]['editableBySelf']==1){ //if can be editable by employee
                            echo '<td valign="top" class="padding_top"><a href="javascript:void(0);" onclick=getForm('.$appraisalDataArray[$j]['appraisalProofId'].','.$appraisalDataArray[$j]['appraisalId'].',1,"'.$targetElementId.'"); ><font color="blue"><u>'.$proofText.'</u></font></a>';
                          }
                          else{
                            echo '<td valign="top" class="padding_top"><a href="javascript:void(0);" onclick=getForm('.$appraisalDataArray[$j]['appraisalProofId'].','.$appraisalDataArray[$j]['appraisalId'].',0,"'.$targetElementId.'"); ><font color="blue"><u>View</u></font></a>';  
                            $seleEditable=0;
                          } 
                       }
                       else{
                         echo '<td valign="top" class="padding_top">'.NOT_APPLICABLE_STRING.'</td>';
                       }

                       
                                              
                       echo '<td valign="top" align="right" class="padding_top">'.$maxValue.'</td>';  
                       
                       if($showHODAppraisal==1){
                        if($appraisalDataArray[$j]['hodEvaluation']==''){
                           $appraisalDataArray[$j]['hodEvaluation']=0;
                        }
                       }
                       else{
                           $appraisalDataArray[$j]['hodEvaluation']=''; 
                       }
                       
                       if($appraisalDataArray[$j]['selfEvaluation']==''){
                              $appraisalDataArray[$j]['selfEvaluation']=0;
                       }
                       
                       if($seleEditable==0){
                         echo '<td valign="top" align="right" class="padding_top">
                                 <input type="text" name="selfEvaluation" id="selfEvaluation_'.$j.'_'.$appraisalDataArray[$j]['appraisalId'].'_'.$maxValue.'_'.$tabCount.'" class="inputbox" style="width:50px;" disabled="disabled" value="'.$appraisalDataArray[$j]['selfEvaluation'].'" />
                              </td>';
                       }
                       else{
                          
                          /*
                          echo '<td valign="top" align="right" class="padding_top">
                                 <input type="text" name="selfEvaluation" id="selfEvaluation_'.$j.'_'.$appraisalDataArray[$j]['appraisalId'].'_'.$maxValue.'_'.$tabCount.'" class="inputbox" style="width:50px;" value="'.$appraisalDataArray[$j]['selfEvaluation'].'" />
                              </td>';
                          */
                          //as employees cannot make changes from this tabs
                          echo '<td valign="top" align="right" class="padding_top">
                                 <input type="text" name="selfEvaluation" id="selfEvaluation_'.$j.'_'.$appraisalDataArray[$j]['appraisalId'].'_'.$maxValue.'_'.$tabCount.'" class="inputbox" style="width:50px;" value="'.$appraisalDataArray[$j]['selfEvaluation'].'" disabled="disabled" />
                              </td>';
                       }
                       
                       
                       
                       $totalSelfPoints += $appraisalDataArray[$j]['selfEvaluation'];
                       $totalHODPoints  += $appraisalDataArray[$j]['hodEvaluation'];
                       
                       if($seleEditable==0){
                          $hodEnabled='disabled="disabled"';
                       }
                       
                       echo '<td valign="top" align="right" class="padding_top">
                                 <input type="text" name="hodEvaluation" id="hodEvaluation_'.$j.'_'.$appraisalDataArray[$j]['appraisalId'].'_'.$maxValue.'_'.$tabCount.'" class="inputbox" style="width:50px;" '.$hodEnabled.' value="'.$appraisalDataArray[$j]['hodEvaluation'].'" />
                              </td>';
                       echo  '</tr>';
                       $srNo++;
                       
                       if($sessionHandler->getSessionVariable('OverrideAppraisalModuleAccess')==1){
                          $hodEnabled='';
                       }
                    }    
                  }
                  
                  if($showHODAppraisal!=1){
                    $totalHODPoints ='';  
                  }
                  echo '<tr><td colspan="6" height="5px;"></td></tr>';
                  echo  '<tr class="row0">
                              <td colspan="4" class="padding_top"><b>Total Points Gained</b></td>
                              <td align="right" class="padding_top"><b><span id="totalSelfPoints_'.$tabCount.'">'.$totalSelfPoints.'</span></b></td>
                              <td align="right" class="padding_top"><b><span id="totalHODPoints_'.$tabCount.'">'.$totalHODPoints.'</span></b></td>
                         </tr>';
                  echo '</table>'; 
                  
                  echo '</div>';
                  
                  if($newTabs!=''){
                      $newTabs .=',';
                      $newTabs2 .=',';
                   }
                    $newTabs .="'".$tabName."'";
                    $newTabs2 .="false";
                    $tabCount++;
                 }
                 
                 if($newTabs!=''){ 
                  $prefixedTabs =$newTabs.','.$prefixedTabs;
                  $prefixedTabs2 =$newTabs2.','.$prefixedTabs2;
                 }
               }
               ?>
               
               <!--Leaves Tab-->
                <div class="dhtmlgoodies_aTab">
                  <table border="0" cellpadding="0" cellspacing="1" width="100%">
                   <tr class="rowheading">
                     <td class="searchhead_text" colspan="4">TYPES OF LEAVE ALLOWED</td>
                   </tr>
                   <tr class="row0">
                    <td class="padding_top" width="20%">Casual Leave</td>  
                    <td class="padding_top" width="5%">
                     <input type="text" <?php echo $hodDisabled ;?> name="casualLeave" id="casualLeave_-1_-1_5_<?php echo $tabCount; ?>" value="<?php echo $appraisalLeavaDataArray[0]['casual_leave']; ?>" class="inputbox" style="width:60px;" />
                    </td>
                    <td class="padding_top" width="20%">Earned Leave</td>
                    <td class="padding_top">
                     <input type="text" <?php echo $hodDisabled ;?> name="earnedLeave" id="earnedLeave_-1_-1_5_<?php echo $tabCount; ?>" value="<?php echo $appraisalLeavaDataArray[0]['earned_leave']; ?>" class="inputbox" style="width:60px;" />
                    </td>
                   </tr>
                   <tr class="row1">
                    <td class="padding_top">Maternity/Paternity Leave</td>  
                    <td class="padding_top">
                     <input type="text" <?php echo $hodDisabled ;?> name="maternityLeave" id="maternityLeave_-1_-1_5_<?php echo $tabCount; ?>" value="<?php echo $appraisalLeavaDataArray[0]['mp_leave']; ?>" class="inputbox" style="width:60px;" />
                    </td>
                    <td class="padding_top">Study Leave</td>
                    <td class="padding_top">
                     <input type="text" <?php echo $hodDisabled ;?> name="studyLeave" id="studyLeave_<?php echo $tabCount; ?>" value="<?php echo $appraisalLeavaDataArray[0]['study_leave']; ?>" class="inputbox" style="width:60px;" />
                    </td>
                   </tr>
                   <tr class="row0">
                    <td class="padding_top">Leave Without Pay(LWP)</td>  
                    <td class="padding_top">
                     <input type="text" <?php echo $hodDisabled ;?> name="lwpLeave" id="lwpLeave_<?php echo $tabCount; ?>" value="<?php echo $appraisalLeavaDataArray[0]['without_pay']; ?>" class="inputbox" style="width:60px;" />
                    </td>
                    <td class="padding_top">No. of time LWP</td>
                    <td>
                     <input type="text" <?php echo $hodDisabled ;?> name="lwpLeaveCount" id="lwpLeaveCount_<?php echo $tabCount; ?>" value="<?php echo $appraisalLeavaDataArray[0]['lwp_times']; ?>" class="inputbox" style="width:60px;" />
                    </td>
                   </tr>
                   <tr class="row1">
                     <td colspan="4" class="padding_top">Overall Self-Appraisal</td>
                   </tr>
                   <tr class="row0">
                     <td colspan="4" class="padding_top">
                      <textarea <?php echo $hodDisabled ;?> name="overallAppraisalText" id="overallAppraisalText_<?php echo $tabCount; ?>" cols="105" rows="5" maxlength="250" onkeyup="return ismaxlength(this);"><?php echo trim($appraisalLeavaDataArray[0]['self_appraisal']); ?></textarea>
                     </td>
                   </tr>
                  </table>
                  <?php
                   $tabCount++;
                  ?>
                </div>
               <!--Leaves Tab Ends--> 
               
               <!--Reviewer Tab-->
                <div class="dhtmlgoodies_aTab">
                 <table border="0" cellpadding="0" cellspacing="1" width="100%">
                 <tr class="rowheading">
                  <td colspan="4" class="searchhead_text">(To be filled by the Reporting Officer</td>
                 </tr>
                 <tr class="rowheading">
                    <td colspan="4" class="searchhead_text">Attitude and Interpersonal Skills(Give rating on a five-point scale with '5' being the best and '1' the poorest)</td>
                 </tr>
                <tr class="row0">
                  <td class="padding_top" width="2%">1</td>
                  <td class="padding_top" colspan="2" width="90%">Initiative: a self-starter; able to work without constant supervision.</td>
                  <td class="padding_top">
                   <input type="text" alt="5" class="inputbox" style="width:50px;" name="initiative" id="initiative_-1_-1_5_<?php echo $tabCount; ?>" size="4" value="<?php echo $appraisalReviewerDataArray[0]['initiative']; ?>" onchange="updateGrandTotal();" maxlength="1" <?php echo $hodEnabled;?> /></td>
                </tr>
               <tr class="row1">
                  <td class="padding_top">2</td>
                  <td class="padding_top" colspan="2">Responsibility: understands duties; accepts responsibilities readily.</td>
                  <td class="padding_top" >
                   <input type="text" alt="5" class="inputbox" style="width:50px;" name="responsibility" id="responsibility_-1_-1_5_<?php echo $tabCount; ?>" size="4" value="<?php echo $appraisalReviewerDataArray[0]['responsibility']; ?>" onchange="updateGrandTotal();"  maxlength="1" <?php echo $hodEnabled;?> /></td>
               </tr>
               <tr class="row0">
                  <td class="padding_top">3</td>
                  <td class="padding_top" colspan="2">Punctuality: arrives on time, Generally availaible for students during working hours.</td>
                  <td class="padding_top">
                   <input type="text" alt="5" class="inputbox" style="width:50px;" name="punctuality" id="punctuality_-1_-1_5_<?php echo $tabCount; ?>" size="4" value="<?php echo $appraisalReviewerDataArray[0]['punctuality']; ?>" onchange="updateGrandTotal();"  maxlength="1" <?php echo $hodEnabled;?> /> 
                   </td>
               </tr>
               <tr class="row1">
                  <td class="padding_top">4</td>
                  <td class="padding_top" colspan="2">Commitment: committed to his/her work.</td>
                  <td class="padding_top"><input type="text" alt="5" class="inputbox" style="width:50px;" name="committment" id="committment_-1_-1_5_<?php echo $tabCount; ?>" size="4" value="<?php echo $appraisalReviewerDataArray[0]['committment']; ?>" onchange="updateGrandTotal();"  maxlength="1" <?php echo $hodEnabled;?> /></td>
               </tr>
               <tr class="row0">
                  <td class="padding_top">5</td>
                  <td class="padding_top" colspan="2">Loyality: Supports and follows institute's policies and guidelines.</td>
                  <td class="padding_top"><input type="text" alt="5" class="inputbox" style="width:50px;" name="loyality" id="loyality_-1_-1_5_<?php echo $tabCount; ?>" size="4" value="<?php echo $appraisalReviewerDataArray[0]['loyality']; ?>" onchange="updateGrandTotal();"  maxlength="1" <?php echo $hodEnabled;?> /></td>
               </tr>
               <tr class="row1">
                  <td class="padding_top">6</td>
                  <td class="padding_top" colspan="2">Development: Keep knowledge up to date.</td>
                  <td class="padding_top"><input type="text" alt="5" class="inputbox" style="width:50px;" name="develop" id="develop_-1_-1_5_<?php echo $tabCount; ?>" size="4" value="<?php echo $appraisalReviewerDataArray[0]['develop']; ?>" onchange="updateGrandTotal();"  maxlength="1" <?php echo $hodEnabled;?> /></td>
               </tr>
               <tr class="row0">
                  <td class="padding_top">7</td>
                  <td class="padding_top" colspan="2">Oral Communication: speaks effectively with supervisor, collegues and students.</td>
                  <td class="padding_top"><input type="text" alt="5" class="inputbox" style="width:50px;" name="oral" id="oral_-1_-1_5_<?php echo $tabCount; ?>" size="4" value="<?php echo $appraisalReviewerDataArray[0]['oral']; ?>" onchange="updateGrandTotal();"  maxlength="1" <?php echo $hodEnabled;?> /></td>
               </tr>
               <tr class="row1">
                  <td class="padding_top">8</td>
                  <td class="padding_top" colspan="2">Written Communication.</td>
                  <td class="padding_top"><input type="text" alt="5" class="inputbox" style="width:50px;" name="written_com" id="writtencom_-1_-1_5_<?php echo $tabCount; ?>" size="4" value="<?php echo $appraisalReviewerDataArray[0]['written_com']; ?>" onchange="updateGrandTotal();"  maxlength="1" <?php echo $hodEnabled;?> /></td>
               </tr>
               <tr class="row0">
                  <td class="padding_top">9</td>
                  <td class="padding_top" colspan="2">Teamwork: effective in a team.</td>
                  <td class="padding_top"><input type="text" alt="5" class="inputbox" style="width:50px;" name="teamwork" id="teamwork_-1_-1_5_<?php echo $tabCount; ?>" size="4" value="<?php echo $appraisalReviewerDataArray[0]['teamwork']; ?>" onchange="updateGrandTotal();"  maxlength="1" <?php echo $hodEnabled;?> /></td>
               </tr>
              <tr class="row1">
                  <td class="padding_top">10</td>
                  <td class="padding_top" colspan="2">Leadership: gives clear direction and listens to co-workers.</td>
                  <td class="padding_top"><input type="text" alt="5" class="inputbox" style="width:50px;" name="leadership" id="leadership_-1_-1_5_<?php echo $tabCount; ?>" size="4" value="<?php echo $appraisalReviewerDataArray[0]['leadership']; ?>" onchange="updateGrandTotal();"  maxlength="1" <?php echo $hodEnabled;?> /></td>
              </tr>
              <tr class="row0">
                  <td class="padding_top">11</td>
                  <td class="padding_top" colspan="2">Relationship with fellow faculty and staff.</td>
                  <td class="padding_top"><input type="text" alt="5" class="inputbox" style="width:50px;" name="relation" id="relation_-1_-1_5_<?php echo $tabCount; ?>" size="4" value="<?php echo $appraisalReviewerDataArray[0]['relation']; ?>" onchange="updateGrandTotal();"  maxlength="1" <?php echo $hodEnabled;?> /></td>
              </tr>
              <tr class="row1">
                  <td class="padding_top">12</td>
                  <td class="padding_top" colspan="2">Maturity.</td>
                  <td class="padding_top"><input type="text" alt="5" class="inputbox" style="width:50px;" name="maturity" id="maturity_-1_-1_5_<?php echo $tabCount; ?>" size="4" value="<?php echo $appraisalReviewerDataArray[0]['matuarity']; ?>" onchange="updateGrandTotal();"  maxlength="1" <?php echo $hodEnabled;?> /></td>
             </tr>
             <tr class="row0">
                  <td class="padding_top">13</td>
                  <td class="padding_top" colspan="2">Temperament.</td>
                  <td class="padding_top"><input type="text" alt="5" class="inputbox" style="width:50px;" name="temper" id="temper_-1_-1_5_<?php echo $tabCount; ?>" size="4" value="<?php echo $appraisalReviewerDataArray[0]['temper']; ?>"  onchange="updateGrandTotal();"  maxlength="1" <?php echo $hodEnabled;?> /></td>
             </tr>
             <tr class="row1">
                  <td class="padding_top">14</td>
                  <td class="padding_top" colspan="2">Relationship with students.</td>
                  <td class="padding_top"><input type="text" alt="5" class="inputbox" style="width:50px;" name="rel_stud" id="relstud_-1_-1_5_<?php echo $tabCount; ?>" size="4" value="<?php echo $appraisalReviewerDataArray[0]['rel_stud']; ?>" onchange="updateGrandTotal();"  maxlength="1" <?php echo $hodEnabled;?> /></td>
             </tr>
             <tr class="row0">
                  <td class="padding_top">&nbsp;</td>
                  <td class="padding_top" colspan="2"><b>TOTAL</b></td>
                  <td class="padding_top"><input type="text" alt="5" class="inputbox" style="width:50px;" name="grandsum" id="grandsum" size="4" value="<?php echo $appraisalReviewerDataArray[0]['grandtotal']; ?>" maxlength="5" disabled="disabled" /></td>
             </tr>
            </table>
              </div>
              <!--Reviewer Tab Ends-->
             
 
				<!--Compatibility Tab-->
				<?php
					 global $FE;
					 require_once(MODEL_PATH . "/Appraisal/AppraisalDataManager.inc.php");
					 $appDataManager = AppraisalDataManager::getInstance();
					 
					 global $sessionHandler;
					 $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
					 if($employeeId=='') {
					   $employeeId=0;
					 }
					 $sessionId=$sessionHandler->getSessionVariable('SessionId');
					 $appraisalCompatibilityDataArray =  $appDataManager->getCompatibilityList($employeeId,$sessionId);
				?>
                 <div class="dhtmlgoodies_aTab">
                 <table border="0" cellpadding="0" cellspacing="1" width="100%">
                 <tr class="rowheading">
                  <td colspan="4" class="searchhead_text">( To be filled by the Department of Examination )</td>
                 </tr>
                 <tr class="rowheading">
                    <td colspan="4" class="searchhead_text">Examination Performance (PTU Exam Internal/External Duties). Control Panel To Store Excecution and Implementation of Examination System</td>
                 </tr>
                <tr class="row0">
                  <td class="padding_top" width="2%">1</td>
                  <td class="padding_top" colspan="2" width="90%">No. of Invigilation Duties Internal.</td>
                  <td class="padding_top">
				 
                   <input type="text"  alt="5" class="inputbox" style="width:50px;" name="dutiesinternal" id="dutiesinternal" maxlength="3" value="<?php echo $appraisalCompatibilityDataArray[0]['dutiesinternal']; ?>" <?php echo $hodEnabled;?> /></td>
                </tr>

                <tr class="row1">
                  <td class="padding_top">2</td>
                  <td class="padding_top" colspan="2">No. of Invigilation Duties External.</td>
                  <td class="padding_top" >
                   <input type="text"  alt="5" class="inputbox" style="width:50px;" name="dutiesexternal" id="dutiesexternal" maxlength="3" value="<?php echo $appraisalCompatibilityDataArray[0]['dutiesexternal']; ?>" <?php echo $hodEnabled;?> /></td>
               </tr>
               
               <tr class="row0">
                  <td class="padding_top">3</td>
                  <td class="padding_top" colspan="2">No. of Copies Checked.</td>
                  <td class="padding_top">
				  <input type="text"  alt="5" class="inputbox" style="width:50px;" name="copychecked" id="copychecked" maxlength="3" value="<?php echo $appraisalCompatibilityDataArray[0]['copychecked']; ?>" <?php echo $hodEnabled;?> /></td>
               </tr>
              <tr class="row1">
                  <td class="padding_top">4</td>
                  <td class="padding_top" colspan="2">No. of Times acts as External Supreintendent.</td>
                  <td class="padding_top"><input type="text"  alt="5" class="inputbox" style="width:50px;" name="extsupreintendent" id="extsupreintendent" maxlength="3" value="<?php echo $appraisalCompatibilityDataArray[0]['extsupreintendent']; ?>"  <?php echo $hodEnabled;?> /></td>
              </tr>
              <tr class="row0">
                  <td class="padding_top">5</td>
                  <td class="padding_top" colspan="2">No. of Invigilation Duties Performed on Weekend.</td>
                  <td class="padding_top"><input type="text"  alt="5" class="inputbox" style="width:50px;" name="dutiesweekend" id="dutiesweekend" maxlength="3" value="<?php echo $appraisalCompatibilityDataArray[0]['dutiesweekend']; ?>"  <?php echo $hodEnabled;?> /></td>
              </tr>
              <tr class="row1">
                  <td class="padding_top">6</td>
                  <td class="padding_top" colspan="2">Scores Gained.</td>
                  <td class="padding_top"><input type="text" alt="5" class="inputbox" style="width:50px;" name="scoregained" id="scoregained" maxlength="3" value="<?php echo $appraisalCompatibilityDataArray[0]['scoregained']; ?>" <?php echo $hodEnabled;?>/></td>
             </tr>
             
             </table>
              </div>
               <!--Compatibility Tab Ends--> 


              <div style="text-align:center;padding-top:3px;">
              <?php 
                if($sessionHandler->getSessionVariable('OverrideAppraisalModuleAccess')==1){ //for HOD operations
               ?>     
                <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateForm2();return false;" />
               <?php
                }
               else{
               ?>
                <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateForm();return false;" />
               <?php
               }
               ?>  
              </div>          
          
              </div>
              <script type="text/javascript">
                 initTabs('dhtmlgoodies_tabView1',Array(<?php echo $prefixedTabs; ?>),0,985,576,Array(<?php echo $prefixedTabs2; ?>));
                //initTabs('dhtmlgoodies_tabView1',Array('Personal Info','Lectures Take','Topics Covered','Seminars','Publications','Consulting Proj.','Workshops'),0,985,576,Array(false,false,false,false,false,false,false,false));
              </script>
             </td>
          </tr>
         </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
	
    </table>
</form>

 
<!--Start Proof Form Div-->

<?php floatingDiv_Start('ProofDiv','Proofs'); ?>
    <!--help-->
<form name="proofForm" id="proofForm" action="<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalFormWithFile.php" method="post" enctype="multipart/form-data" onsubmit="return false;">
<input type="hidden" name="proofId" id="proofId" value="" />
<input type="hidden" name="appraisalId" id="appraisalId" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
 <td valign="top">
 <div id="proofContentDiv"></div>
 </td>
</tr>  
</table>
<iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
</form>
<?php floatingDiv_End(); ?>
<!--End: Div To Edit The Table-->

<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:550px;HEIGHT:275px; vertical-align:top;"> 
    <table width="100%" border="0 cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="7px"></td>
        </tr>
        <tr>    
            <td width="89%" >
                <div id="helpInfo" style="vertical-align:top;" ></div> 
            </td>
        </tr>
    </table>
</div>       
<?php floatingDiv_End(); ?> 
<?php 
// $History: listEmployeeInfoContents.php $
?>