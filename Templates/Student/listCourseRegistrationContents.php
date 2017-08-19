<?php 
//-------------------------------------------------------
//  This File contains html form for all Student Internal Reappear Contents
//
//
// Author :PArveen Sharma
// Created on : 13-Sep-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<?php
    require_once(BL_PATH.'/HtmlFunctions.inc.php');
    global $sessionHandler;    
    $htmlFunctions = HtmlFunctions::getInstance();      
    $studentName1 = $sessionHandler->getSessionVariable('StudentName'); 
     
    require_once(MODEL_PATH.'/CommonQueryManager.inc.php');
    $commonQueryManager = CommonQueryManager::getInstance(); 
    $tclassId = $sessionHandler->getSessionVariable('ClassId');
    if($tclassId=='') {
      $tclassId=0;  
    }
    $condition = " AND c.classId = ".$tclassId;
    $foundArray = $commonQueryManager->getDegreeName($condition);
    $recordCount = 0; 
    $condition='';
    $condition = " AND c.branchId = '".$foundArray[0]['branchId']."' AND c.batchId = '".$foundArray[0]['batchId']."'";
    $degreeCode = $sessionHandler->getSessionVariable('REGISTRATION_DEGREE'); 
    
    $ssDate = $sessionHandler->getSessionVariable('REGISTRATION_DEGREE_END_DATE');
    if($ssDate!='') {
      $sDate = explode('-',$ssDate); 
      $serverDate = explode('-',date('Y-m-d')); 
      $start_date=gregoriantojd($sDate[1], $sDate[2], $sDate[0]);
      $end_date  =gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);
      $diff=$end_date-$start_date;
    }
    
    if($foundArray[0]['degreeCode']!=$degreeCode) {
      $recordCount = 0;
    }
    else {
      $foundArray = $commonQueryManager->getRegistrationDegreeList($condition);
      $recordCount = count($foundArray);
    }
?>    
<form action="" method="post" name="allDetailsForm" id="allDetailsForm" onSubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">Course Registration Form</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
                    <td valign="top" class="content">
<?php 
    if($recordCount>0) {
        $showId=1;
        if($diff>0) {
          $showId=0;
       }
?>
                        <!-- form table starts -->
                        <input type="hidden" readonly="readonly" value="<?php echo $showId; ?>" id="showId" name="showId">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                            <tr>  
                               <td class="contenttab_row" align="center" width="100%">
                                 <nobr><b>REGISTRATION FORM</b></nobr><br><br> 
                                 <table width="100%" border="0" cellspacing="0px" cellpadding="0px" class="" align="center"> 
                                 <tr>
                                   <td  class="contenttab_internal_rows" width="9%" nowrap><nobr><b>Date of Registration</b></nobr></td>
                                   <td  class="padding" nowrap width="1%"><nobr><b>:</b></nobr></td>
                                   <td  class="contenttab_internal_rows" width="40%" colspan="4" nowrap><nobr>&nbsp;
                                    <label id="regDate1"> <?php $dt = date('Y-m-d');  echo UtilityManager::formatDate($dt); ?> </label>
                                   </nobr></td> 
                
                                  </tr>
                                  <tr>
                                   <td  class="contenttab_internal_rows" width="9%" nowrap><nobr><b>Name of Student</b></nobr></td>
                                   <td  class="padding" nowrap width="1%"><nobr><b>:</b></nobr></td>
                                   <td  class="contenttab_internal_rows" width="40%" nowrap><nobr>&nbsp;
                                   <?php echo $studentName1; ?></nobr></td> 
                                   <td  class="contenttab_internal_rows" width="9%" nowrap><nobr><b>Institute Roll No.</b></nobr></td>
                                   <td  class="padding" nowrap width="1%"><nobr><b>:</b></nobr></td>
                                   <td  class="contenttab_internal_rows" width="40%" nowrap><nobr>&nbsp;
                                   <?php echo parseOutput($studentInformationArray[0]['rollNo']); ?></nobr></td> 
                                  </tr>
                                  <tr>
                                       <td  class="contenttab_internal_rows" nowrap><nobr><b>Univ. Roll No.</b></nobr></td>
                                       <td  class="padding" nowrap><nobr><b>:</b></nobr></td>
                                       <td  class="contenttab_internal_rows" nowrap><nobr>&nbsp;
                                       <?php echo parseOutput($studentInformationArray[0]['universityRollNo']); ?></nobr></td> 
                                       <td  class="contenttab_internal_rows" nowrap><nobr><b>Course / Branch</b></nobr></td>
                                       <td  class="padding" nowrap><nobr><b>:</b></nobr></td>
                                       <td  class="contenttab_internal_rows" nowrap><nobr>&nbsp;
                                       <?php 
                                            if($studentInformationArray[0]['degreeName']!='') {
                                                echo parseOutput(strtoupper($studentInformationArray[0]['degreeName']));
                                             }
                                             
                                             if($studentInformationArray[0]['branchName']!='') {  
                                                echo " / ".strtoupper($studentInformationArray[0]['branchName']);
                                             }
                                       ?></nobr></td> 
                                  </tr>
                                  <tr>
                                       <td  class="contenttab_internal_rows" nowrap><nobr><b>Current Semester / Session</b></nobr></td>
                                       <td  class="padding" nowrap><nobr><b>:</b></nobr></td>
                                       <td  class="contenttab_internal_rows" nowrap colspan="4"><nobr>&nbsp;
                                       <?php 
                                            echo parseOutput($studentInformationArray[0]['periodName']); 
                                            if($studentInformationArray[0]['branchName']!='') {  
                                                echo " / ".$sessionHandler->getSessionVariable('SessionName');
                                            }
                                       ?></nobr>
                                       </td> 
                                  </tr>
                                  </table>  
                               </td>     
                            </tr>
                            <tr>
                                <td class="" height="20" colspan="6">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title" align="left">Course Detials :</td>
                                            <td colspan="1" class="content_title" align="right">
                                               <!-- <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/submit.gif" onClick="addReappearSubjects();" /> -->
                                               <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport()"/>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class='contenttab_row'  colspan="6">
                                    <div id="resultsDiv">
                                        <table width="100%" border="0" cellspacing="0px" cellpadding="1px" class="contenttab_border2"> 
                                           <tr>
                                              <td class="" valign="top" colspan="3">  
                                                <table class="border" width="100%" border="0" cellspacing="0px" cellpadding="0px" >
                                                 <tr>
                                                    <td width="5%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Cumulative Grade Point Average (CGPA)</b></nobr></td>
                                                    <td width="1%" class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                                    <td width="14%" class="contenttab_internal_rows" align="left"><nobr>
                                                      <input type="text" name="cgpa" id="cgpa" class="inputbox" maxlength="4"  style="width:80px"/></nobr>
                                                    </td>
                                                    <td width="5%" class="contenttab_internal_rows"><nobr><b>&nbsp;Major Concentration</b></nobr></td>
                                                    <td width="1%" class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                                    <td width="74%" class="contenttab_internal_rows" align="left"><nobr>
                                                      <input type="text" name="majorConcentration" id="majorConcentration" class="inputbox" maxlength="150"  style="width:350px"/></nobr>
                                                    </td>
                                                 </tr>
                                               </table>  
                                              </td> 
                                           </tr>
                                           <tr>
                                                <td class="contenttab_internal_rows" height="10px"></td>
                                           </tr>
                                                <input type="hidden" readonly name="totalClass" id="totalClass" class="inputbox" value="<?php echo $recordCount; ?>" style="width:350px"/>
                                                <input readonly type="hidden" name="editId" id="editId" value="0">
                                                <input type="hidden" name="confirmId" id="confirmId" value="N">
                                           <?php      
                                                for($i=0;$i<$recordCount;$i++) {
                                                   $classId = $foundArray[$i]['classId'];
                                                   $periodValue = $foundArray[$i]['periodValue'];
                                           ?>       
                                                   <input type="hidden" readonly name="tClassId<?php echo ($i+1); ?>" id="tClassId<?php echo ($i+1); ?>" class="inputbox" value="<?php echo $classId; ?>" style="width:350px"/>
                                                   <input type="hidden" readonly name="periodValue<?php echo ($i+1); ?>" id="periodValue" class="inputbox" value="<?php echo $periodValue; ?>" style="width:350px"/>
                                                   
                                                   <select size="1"  name="careerId" id="careerId<?php echo ($i+1); ?>" style="display:none">
                                                   <option value="">Select</option>
                                                     <?php
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        
                                                        $studyPeriodId=4+$i;
                                                        $condition='';
                                                        //$condition = " AND sp.studyPeriodId=$studyPeriodId AND upper(p.periodicityName) = 'TERM' AND c.classId = $tclassId";  
                                                        echo HtmlFunctions::getInstance()->getCourseList('',$condition); 
                                                     ?>
                                                   </select>
                                                   
                                                   <select size="1"  name="electiveId" id="electiveId<?php echo ($i+1); ?>" style="display:none">
                                                   <option value="">Select</option>
                                                     <?php
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        $studyPeriodId=4+$i;
                                                        //$condition = " AND sp.studyPeriodId=$studyPeriodId AND upper(p.periodicityName) = 'TERM' AND c.classId = $tclassId";  
                                                        echo HtmlFunctions::getInstance()->getCourseList('',$condition);
                                                     ?>
                                                   </select>  
                                                   
                                                   <tr>
                                                      <td class="contenttab_internal_rows" colspan="4">
                                                       <table class="border" width="100%" border="0" cellspacing="0px" cellpadding="0px" >
                                                         <tr>
                                                           <td width="5%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Term-<?php echo UtilityManager::romanNumerals($i+4); ?>&nbsp;</b></nobr></td>
                                                         </tr>    
                                                       </table>
                                                       </td>
                                                   </tr>
                                                   <tr>
                                                     <td class="" valign="top" id="CareerCourses<?php echo ($i+1); ?>" width="49%">
                                                       <div id="scroll2" style="overflow:auto; vertical-align:top;">
                                                           <table class="padding" width="100%" border="0"  id="anyidCareer<?php echo ($i+1); ?>">
                                                              <tbody id="anyidBodyCareer<?php echo ($i+1); ?>">
                                                                <tr class="rowheading">
                                                                    <td class="searchhead_text" width="10%"  align="left"><nobr><b>Sr. No.</b></nobr></td>
                                                                    <td class="searchhead_text" width="60%" align="left"><nobr><b>Career Courses</b></nobr></td>
                                                                    <td class="searchhead_text" width="20%" align="left"><nobr><b>Credits</b></nobr></td>
                                                                    <td class="searchhead_text" width="10%" align="center"><nobr><b>Action</b></nobr></td>
                                                                </tr>
                                                              </tbody>
                                                           </table>               
                                                           <div class="searchhead_text" align="left">&nbsp;&nbsp;Add Rows:&nbsp;&nbsp;
                                                             <a href="javascript:addElectiveOneRow(1,<?php echo ($i+1); ?>,'C');" title="Add One Row"><b>+</b></a></div></nobr>
                                                           </div>
                                                       </div>          
                                                     </td>
                                                     <td class="" valign="top" id="BlankRow<?php echo ($i+1); ?>" width="2%">
                                                     <td class="" valign="top" id="ElectiveCourses<?php echo ($i+1); ?>" width="49%">   
                                                       <div id="scroll2" style="overflow:auto; vertical-align:top;">     
                                                           <table class="padding" width="100%" border="0"  id="anyidElective<?php echo ($i+1); ?>">
                                                             <tbody id="anyidBodyElective<?php echo ($i+1); ?>">
                                                                <tr class="rowheading">
                                                                    <td class="searchhead_text" width="10%"  align="left"><nobr><b>Sr. No.</b></nobr></td>
                                                                    <td class="searchhead_text" width="60%" align="left"><nobr><b>Elective Courses</b></nobr></td>
                                                                    <td class="searchhead_text" width="20%" align="left"><nobr><b>Credits</b></nobr></td>
                                                                    <td class="searchhead_text" width="10%" align="center"><nobr><b>Action</b></nobr></td>
                                                                </tr>
                                                             </tbody>
                                                           </table>               
                                                           <div class="searchhead_text" align="left">&nbsp;&nbsp;Add Rows:&nbsp;&nbsp;
                                                             <a href="javascript:addElectiveOneRow(1,<?php echo ($i+1); ?>,'E');" title="Add One Row"><b>+</b></a></div></nobr>
                                                           </div>
                                                       </div>    
                                                     </td>   
                                                   </tr>
                                                   <tr>
                                                      <td class="contenttab_internal_rows" height="10px"></td>
                                                   </tr>
                                             <?php
                                              }
                                             ?>
                                             <!-- 
                                              <tr>
                                                <td class="contenttab_internal_rows" colspan="4">
                                                  <table class="border" width="100%" border="0" cellspacing="0px" cellpadding="0px" >
                                                    <tr>
                                                      <td width="5%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Total Credits (Carrer/Elective)&nbsp;:&nbsp;</b></nobr></td>
                                                    </tr>    
                                                  </table>
                                                </td>
                                              </tr>
                                             -->  
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr id='nameRowId'>
                                <td class="" height="20"  colspan="6" >
                                    <table width="100%" border="0" cellspacing="0" align="center" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="5" class="padding" align="right">
                                             <?php if($showId==1) { ?>
                                                      <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/submit.gif" onClick="return validateAddForm(this.form,'N');return false;" />
                                             <?php } ?>         
                                            </td>
                                        </tr>
                                        <tr>   
                                            <td width="2%" class="contenttab_internal_rows" valign="top" style="font-size:11px; color:red;">
                                                <nobr><b>Note&nbsp;:&nbsp;</b></nobr> 
                                            </td>
                                            <td height="10px">&nbsp;</td>
                                            <td width="98%" class="contenttab_internal_rows" valign="top" style="font-size:11px; color:red;">
                                              <nobr><b>
                                                   Confirm the details of PGP2 Registration by clicking Confirm you would not be able to add/modify  the same.<br>After confirmation, 
                                                   please submit the printed Registration form to concerned Program Officer.
                                               </b>
                                              </nobr>
                                            </td>
                                             <td class="padding" align="right">
                                                <?php if($showId==1) { ?>  
                                                    <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/confirm.gif" onClick="return validateAddForm(this.form,'Y');return false;" />
                                                 <?php } ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <!-- form table ends -->
<?php
}
else {
?>
            <table width="100%" border="0" cellspacing="2" cellpadding="4">
                <tr>
                    <td colspan="2" style="background-repeat:repeat-x;"></td>
                </tr>
                <tr height="25">
                    <td width="37%" align="left" class="text_loginpanel" ></td>
                    <td width="63%" align="left" class="text_loginpanel">Course Registration Form is not available</a>
                    </td>
                </tr>
            </table>
<?php
}
?>

                    </td>
                </tr>
            </table>
        </table>
        <?php
            $studentId = $sessionHandler->getSessionVariable('StudentId');  
            $studentCurrentClassId = $sessionHandler->getSessionVariable('ClassId'); 
        ?>        
        <input readonly type="hidden" name="studentId" id="studentId" value="<?php echo $studentId; ?>">
        <input readonly type="hidden" name="currentClassId" id="currentClassId" value="<?php echo $studentCurrentClassId; ?>">
</form>        

<?php 
// $History: listStudentInternalReappearContents.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 1/28/10    Time: 5:40p
//Updated in $/LeapCC/Templates/Student
//validation & format update (button & radio button updated)
//
//*****************  Version 4  *****************
//User: Parveen      Date: 1/19/10    Time: 6:27p
//Updated in $/LeapCC/Templates/Student
//function & validation message and format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/12/10    Time: 6:16p
//Updated in $/LeapCC/Templates/Student
//format validation updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/09/10    Time: 5:22p
//Updated in $/LeapCC/Templates/Student
//look & feel updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/09/10    Time: 1:05p
//Created in $/LeapCC/Templates/Student
//initial checkin
//

?>
