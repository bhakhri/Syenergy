<?php
//-------------------------------------------------------
// Purpose: to design add student.
//
// Author : Rajeev Aggarwal
// Created on : (05.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Candidate Details : </td>
                        <td class="content_title" align="right"  width="800"><a href="#" onClick="displayWindow('AddStudentEnquiry','850','420');blankValues();return false;"><span class="content_title">Add Enquiry</span></a></td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif"
                         onClick="displayWindow('AddStudentEnquiry','850','420');blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td class="contenttab_row" valign="top" >
              <form name="studentSearchForm" action="" method="post" onSubmit="return false;">
               <!--Add Parent Filter-->
               <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                <tr>
                 <td valign="top"  align="center" style="padding-left:3px" nowrap="nowrap"> <br>
                 <table border='0' width='100%' cellspacing='0' align='center' id="divCity">
                  <tr>
                    <td class="contenttab_internal_rows1" style='text-align:left'><nobr><b>Seeking Admission in</b></nobr>
                    <td class="contenttab_internal_rows1"><b>:</b></td>
                    <td class="padding_top" align="left">
                    <select size="1" class="inputbox1" name="degreeId" id="degreeId" style='width:245px'>
                     <option value="">Select</option>
                        <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getAdmitClassData();
                        ?>
                    </select>
                    </td>
                    <td class="contenttab_internal_rows1" style='text-align:left'><nobr><b>Student Name</b></nobr>
                    <td class="contenttab_internal_rows1"><b>:</b></td>
                    <td class="padding_top" align="left">
                     <input type="text" name="studentNameSearch" id="studentNameSearch" class="inputbox" style='width:200px'>
                    </td>
                    <td class="contenttab_internal_rows1" style='text-align:left'><nobr><b>Father's Name</b></nobr>
                    <td class="contenttab_internal_rows1"><b>:</b></td>
                    <td class="padding_top" align="left">
                     <input type="text" name="fatherNameSearch" id="fatherNameSearch" class="inputbox" style='width:200px'>
                    </td>
                  </tr>
                  <tr><td colspan="9" height="5px"></td></tr>
                   <tr>
                    <td colspan='1' class='contenttab_internal_rows1' style='text-align:left'><b>City</b></td>
                    <td colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='padding_top' align='left' style="padding-left:3px;" >
                    <div id="containerDiv1">
                        <select multiple name='cityId[]' id='cityId1' size='5' class='inputbox' style='width:240px'>
                        <?php
                          echo $htmlFunctions->getCityData();
                        ?>
                        </select>
                        <!--<br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection("cityId[]","All","studentSearchForm");'>All</a> / <a class='allReportLink' href='javascript:makeSelection("cityId[]","None","studentSearchForm");'>None</a>-->
                       </div>
                       <div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="d1"></div>
                       <div style="display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="d2" >
                          <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
                           <tr>
                              <td id="d3" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
                              <td width="5%">
                              <img id="downArrawId" src="<?php echo IMG_HTTP_PATH ?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('cityId1','d1','containerDiv1','d3');" />
                              </td>
                            </tr>
                          </table>
                      </div>
                    </td>
                    <td colspan='1' class='' style='text-align:left'><b>State&nbsp; </b></td>
                    <td colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' align='left' style="padding-left:3px;" >
                    <div id="containerDiv2">
                        <select multiple name='stateId[]' id='stateId' size='5' class='inputbox' style='width:200px'>
                        <?php
                          echo $htmlFunctions->getStatesData();
                        ?>
                        </select>
                     <!--<br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection("stateId[]","All","studentSearchForm");'>All</a> / <a class='allReportLink' href='javascript:makeSelection("stateId[]","None","studentSearchForm");'>None</a>-->
                     </div>
                     <div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="d11"></div>
                     <div style="display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="d22" >
                          <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
                           <tr>
                              <td id="d33" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
                              <td width="5%">
                              <img id="downArrawId" src="<?php echo IMG_HTTP_PATH ?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('stateId','d11','containerDiv2','d33');" />
                              </td>
                            </tr>
                          </table>
                     </div>
                    </td>
                    <td colspan='1' class='' style='text-align:left'><b>Country</b></td>
                    <td colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' align='left' style="padding-left:3px;" >
                    <div id="containerDiv3">
                        <select multiple name='countryId[]' id='countryId' size='5' class='inputbox' style='width:200px' >
                        <?php
                          echo $htmlFunctions->getCountriesData();
                        ?>
                        </select>
                      <!--<br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection("countryId[]","All","studentSearchForm");'>All</a> / <a class='allReportLink' href='javascript:makeSelection("countryId[]","None","studentSearchForm");'>None</a>-->
                      </div>
                     <div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="d111"></div>
                     <div style="display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="d222" >
                      <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
                       <tr>
                          <td id="d333" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
                          <td width="5%">
                          <img id="downArrawId" src="<?php echo IMG_HTTP_PATH ?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('countryId','d111','containerDiv3','d333');" />
                          </td>
                        </tr>
                      </table>
                     </div>
                    </td>
                </tr>
                <tr><td colspan="9" height="5px"></td></tr>
                <tr>
                    <td class="contenttab_internal_rows1" style='text-align:left'><b>Counselor</b>
                    <td class="contenttab_internal_rows1"><b>:</b></td>
                    <td class="padding_top" align="left">
                    <select size="1" class="inputbox1" name="counselorId" id="counselorId" style='width:245px'>
                    <?php
                      if($sessionHandler->getSessionVariable('RoleId')!=1){
                          $conditions=' AND u.userId='.$sessionHandler->getSessionVariable('UserId');
                      }
                      else{
                          $conditions=' AND r.roleType IN (1,11)'; //11 for counseleo
                          ?>
                          <option value="">All</option>
                          <?php
                      }
                    ?>

                        <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getUserData(' ',$conditions,'displayName');
                        ?>
                    </select>
                    </td>
                    <td class="contenttab_internal_rows1" style='text-align:left'><b>Admission Status</b>
                    <td class="contenttab_internal_rows1"><b>:</b></td>
                    <td class="padding_top" align="left">
                    <select size="1" class="inputbox1" name="candidateStatusId" id="candidateStatusId" style='width:200px'>
                        <option value=''>Select</option>
                        <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getEnquiryFormStatus();
                        ?>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td height="20"></td>
                  </tr>
                </tr>
                    <td valign='top' colspan='10' class='' align='center'>
                     <input type="image" name="searchStudentSubmit" value="searchStudentSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return getData();return false;" />
                     </td>
                    </tr>
                   </table>
                  </td>
                 </tr>
                 <tr id='resultRow' style='display:none;'>
                                <td colspan='1' class='contenttab_row'>
                                    <div id = 'results'></div>
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printStudentReport();return false;"/>&nbsp;
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printStudentCSV();return false;"/>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                </table>
               </form>
               </td>
             </tr>
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>

<?php floatingDiv_Start('AddStudentEnquiry','Add Enquiry Details'); ?>
   <form action="" method="POST" name="addForm" id="addForm" onsubmit="return false;">
   <input type="hidden" name="studentId" id="studentId" value="" />
   <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" >
   <tr>
     <td class="contenttab_internal_rows"  colspan="10">
                <table border="0" cellspacing="0" cellpadding="0" >
                <tr>
                  <td class="contenttab_internal_rows" width="80"><nobr><b>Seeking Admission in<?php echo REQUIRED_FIELD?></b></nobr></td>
                    <td class="contenttab_internal_rows"><b>:</b></td>
                    <td class="padding_top" width='252' valign="top">&nbsp;<select size="1" class="inputbox1" name="degree" id="degree" style='width:240px'>
                    <option value="">Select</option>
                    <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getAdmitClassData();
                    ?>
                    </select></td>
                    <td class="contenttab_internal_rows"><b>Counselor<?php echo REQUIRED_FIELD?></b>
                    <td class="contenttab_internal_rows"><b>:</b></td>
                    <td class="padding_top" align="left">
                    <select size="1" class="inputbox1" name="counselor" id="counselor" style="width:150px">
                    <?php
                      if($sessionHandler->getSessionVariable('RoleId')!=1){
                          $conditions=' AND u.userId='.$sessionHandler->getSessionVariable('UserId');
                      }
                      else{
                          $conditions=' AND r.roleType IN (1,11)'; //11 for counseleo
                          ?>
                          <option value="">Select</option>
                          <?php
                      }
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getUserData(' ',$conditions,'displayName');
                     ?>
                        </select>
                    </td>
                </tr>
                <tr>
                  <td class="contenttab_internal_rows" width="80"><nobr><b>Application Form No.</b></nobr></td>
                    <td class="contenttab_internal_rows"><b>:</b></td>
                    <td class="padding" align="left" valign="top"><nobr>
                       <input type="text" id="formNo" name="formNo" class="inputbox" style="width:235px" maxlength="40"/>
                       </nobr>
                    </td>
                    <td class="contenttab_internal_rows" width="80"><nobr><b>Date</b></nobr></td>
                    <td class="contenttab_internal_rows"><b>:</b></td>
                    <td class="padding_top" width='100' valign="top">
                    <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         echo HtmlFunctions::getInstance()->datePicker('enquiryDate',date("Y-m-d"));
                    ?>
                    </td>
                    <td class="contenttab_internal_rows" width="80"><nobr><b>Admission Status<?php echo REQUIRED_FIELD?></b></nobr></td>
                    <td class="contenttab_internal_rows"><b>:</b></td>
                    <td class="contenttab_internal_rows" width='100'>
                      <span id='admissionRow1' style='display:"";'>
                        <select size="1" class="selectfield" name="candidateStatus" id="candidateStatus" style="width:120px;">
                            <option value="">Select</option>
                            <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getEnquiryFormStatus(1,1);
                            ?>
                        </select>
                      </span>
                      <span id='admissionRow2' style='display:"";'>
                         <input type="text" readonly id="candidateStatus1" name="candidateStatus1" class="noBorder" value="" />
                         <input type="hidden" readonly id="candidateStatus2" name="candidateStatus2" class="noBorder" value="" />
                      </span>
                    </td>
                </tr>
           </table>
       </td>
    </tr>
    <tr>
       <td height="10px"><nobr><b></b></nobr></td>
    </tr>
    <tr>
       <td class="contenttab_internal_rows" colspan="10"><nobr><b><u>Student Personal Details</u></b></nobr></td>
    </tr>
    <tr>
            <td class="contenttab_internal_rows" width="105"><nobr><b>First Name<?php echo REQUIRED_FIELD?></b></nobr></td>
            <td class="contenttab_internal_rows"><b>:</b></td>
            <td class="padding_top"><input type="text" id="studentFName" name="studentFName" class="inputbox" maxlength="60"/></td>
            <td class="contenttab_internal_rows" width="80"><nobr><b>&nbsp;&nbsp;Last Name</b></nobr></td>
            <td class="contenttab_internal_rows"><b>:</b></td>
            <td class="padding_top"><input type="text" id="studentLName" name="studentLName" class="inputbox" maxlength="60"/></td>
            <td class="contenttab_internal_rows" width="80"><nobr><b>&nbsp;</b></nobr></td>
            <td class="contenttab_internal_rows"><b>&nbsp;</b></td>
            <td class="padding_top">&nbsp;</td>
    </tr>
    <tr>
            <td class="contenttab_internal_rows"><nobr><b>Date Of Birth</b></nobr></td>
            <td class="contenttab_internal_rows"><b>:</b></td>
            <td class="padding_top">
            <nobr>
            <select size="1" name="studentYear" id="studentYear" class="inputbox1">
              <option value="">Year</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getEmployeeBirthYear('1992');
              ?>
            </select><select size="1" name="studentMonth" id="studentMonth" class="inputbox1">
            <option value="">Month</option>
             <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getBirthMonth('4');
              ?>
            </select><select size="1" name="studentDate" id="studentDate" class="inputbox1">
            <option value="">Date</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getBirthDate('1');
              ?>
            </select></nobr>
            </td>
            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Gender</b></nobr></td>
            <td class="contenttab_internal_rows"><b>:</b></td>
            <td class="padding_top"><input type="radio" id="genderRadio" name="genderRadio" value="M" checked/>Male&nbsp;&nbsp;<input type="radio" id="genderRadio" name="genderRadio" value="F"/>Female                            </td>
            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Email<?php echo REQUIRED_FIELD?></b></nobr></td>
            <td class="contenttab_internal_rows"><b>:</b></td>
            <td class="padding_top"><input type="text" id="studentEmail" name="studentEmail" class="inputbox" maxlength="100"/>                            </td>
    </tr>
    <tr>
            <td class="contenttab_internal_rows"><nobr><b>Contact No.<?php echo REQUIRED_FIELD?></b></nobr></td>
            <td class="contenttab_internal_rows"><b>:</b></td>
            <td class="padding_top"><input type="text" id="studentNo" name="studentNo" class="inputbox"  maxlength="12"/></td>
            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Mobile No.</b></nobr></td>
            <td class="contenttab_internal_rows"><b>:</b></td>
            <td class="padding_top"><input type="text" id="studentMobile" name="studentMobile" class="inputbox"  maxlength="10"/></td>
            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Nationality</b></nobr></td>
            <td class="contenttab_internal_rows"><b>:</b></td>
            <td class="padding_top"><select size="1" class="selectfield" name="studentNationality" id="studentNationality">
            <option value="">Select</option>
            <?php
              global $sessionHandler;
              $defaultNationalityId = $sessionHandler->getSessionVariable('DEFAULT_NATIONALITY');

              require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->getNationalityData($defaultNationalityId);
            ?>
            </select></td>
    </tr>
    <tr>
            <td class="contenttab_internal_rows"><nobr><b>Domicile</b></nobr></td>
            <td class="contenttab_internal_rows"><b>:</b></td>
            <td class="padding_top"><select size="1" class="selectfield" name="studentDomicile" id="studentDomicile">
            <option value="">Select</option>
            <?php
              global $sessionHandler;
              $defaultDomicileId = $sessionHandler->getSessionVariable('DEFAULT_DOMICILE');

              require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->getStatesData($defaultDomicileId);
            ?>
            </select></td>
            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Category</b></nobr></td>
            <td class="contenttab_internal_rows"><b>:</b></td>
            <td class="padding_top"><select size="1" class="selectfield" name="studentCategory" id="studentCategory">
            <option value="">Select</option>
            <?php
              global $sessionHandler;
              $defaultCategoryId = $sessionHandler->getSessionVariable('DEFAULT_CATEGORY');

              require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->getCategoryClassData($defaultCategoryId);
            ?>
            </select></td>
            <td class="contenttab_internal_rows">&nbsp;</td>
            <td class="contenttab_internal_rows">&nbsp;</td>
            <td class="padding_top">&nbsp;</td>
    </tr>
    <tr>
            <td class="contenttab_internal_rows"><nobr><b>Comp. Exam. By</b></nobr></td>
            <td class="contenttab_internal_rows"><b>:</b></td>
            <td class="padding_top"><select size="1" class="selectfield" name="entranceExam" id="entranceExam">
            <option value="">Select</option>
            <?php

              global $sessionHandler;
              //$defaultExamId = $sessionHandler->getSessionVariable('DEFAULT_EXAM');

              require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->getEntranceExamData();
            ?>
            </select></td>
            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Exam. Roll No.</b></nobr></td>
            <td class="contenttab_internal_rows"><b>:</b></td>
            <td class="padding_top">
                <input type="text" id="entranceExamRollNo" name="entranceExamRollNo" class="inputbox"  maxlength="50"/>
            </td>
            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Rank</b></nobr></td>
            <td class="contenttab_internal_rows"><b>:</b></td>
            <td class="padding_top"><input type="text" id="studentRank" name="studentRank" class="inputbox" maxlength="10"/></td>
    </tr>
    <tr>
            <td class="contenttab_internal_rows" width="80"><nobr><b>Father's Name</b></nobr></td>
            <td class="contenttab_internal_rows"><b>:</b></td>
            <td class="padding_top" width="80"><input type="text" id="fatherName" name="fatherName" class="inputbox"  maxlength="100"/></td>
            <td class="contenttab_internal_rows" ><nobr><b>&nbsp;&nbsp;Mother's Name</b></nobr></td>
            <td class="contenttab_internal_rows"><b>:</b></td>
            <td class="padding_top"><input type="text" id="motherName" name="motherName" class="inputbox" size="18"  maxlength="100"/></td>
    </tr>
    <tr>
       <td height="10px"><nobr><b></b></nobr></td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows" colspan="9"><nobr><b><u>Contact Details</u></b></nobr></td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows" width="80"><nobr><b>Address1</b></nobr></td>
        <td class="contenttab_internal_rows"><b>:</b></td>
        <td class="padding_top"><input type="text" id="correspondeceAddress1" name="correspondeceAddress1" class="inputbox" maxlength="255"/></td>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Address2</b></nobr></td>
        <td class="contenttab_internal_rows"><b>:</b></td>
        <td class="padding_top"><input type="text" id="correspondeceAddress2" name="correspondeceAddress2" class="inputbox" maxlength="255"/>                            </td>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Pincode</b></nobr></td>
        <td class="contenttab_internal_rows"><b>:</b></td>
        <td class="padding_top"><input type="text" id="correspondecePincode" name="correspondecePincode" class="inputbox" maxlength="10"/></td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Country<?php echo REQUIRED_FIELD?></b></nobr></td>
        <td class="contenttab_internal_rows"><b>:</b></td>
        <td class="padding_top">
        <select size="1" class="selectfield" name="correspondenceCountry" id="correspondenceCountry" onChange="autoPopulate(this.value,'states','Add','correspondenceStates','correspondenceCity');"><option value="" selected="selected">Select</option>
        <?php
          require_once(BL_PATH.'/HtmlFunctions.inc.php');
          echo HtmlFunctions::getInstance()->getCountriesData($defaultCountryId);
        ?>
        </select></td>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;State<?php echo REQUIRED_FIELD?></b></nobr></td>
        <td class="contenttab_internal_rows"><b>:</b></td>
        <td class="padding_top">
            <select size="1" class="selectfield" name="correspondenceStates" id="correspondenceStates" onChange="autoPopulate(this.value,'city','Add','correspondenceStates','correspondenceCity');">
                <option value="">Select</option>
            </select>
        </td>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;City<?php echo REQUIRED_FIELD?></b></nobr></td>
        <td class="contenttab_internal_rows"><b>:</b></td>
        <td class="padding_top">
            <select size="1" class="selectfield" name="correspondenceCity" id="correspondenceCity"><option value="">Select</option>
             </select>
        </td>
    </tr>
    <tr>
        <td colspan="6"></td>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Other</b>(City)</nobr></td>
        <td class="contenttab_internal_rows"><b>:</b></td>
        <td class="padding_top"><input type="text" id="city" name="city" class="inputbox" maxlength="255"/>                     </td>
    </tr>
    <tr>
       <td height="10px"><nobr><b></b></nobr></td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows" colspan="15"><nobr><b><u>Visitor Details</u></b></nobr></td>
    </tr>
    <tr>
            <td class="contenttab_internal_rows" colspan="3"><nobr><b>Purpose of visit</b></nobr></td>
            <td class="contenttab_internal_rows" colspan="8"><b>:</b>&nbsp;<nobr>
              <input type="text" id="visitPurpose" name="visitPurpose" style="width:450px" class="inputbox" maxlength="100"/>
            </nobr></td>
    </tr>
    <tr>
            <td class="contenttab_internal_rows" colspan="3"><nobr><b>Name of the person whom visitor intends to meet</b></nobr></td>
            <td class="contenttab_internal_rows" colspan="8"><b>:</b>&nbsp;<nobr>
               <input type="text" id="visitorName" style="width:450px" name="visitorName" class="inputbox" maxlength="100" />
             </nobr>
            </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows" width="80">
            <nobr><b>Source from</b></nobr>
        </td>
        <td class="contenttab_internal_rows"><b>:</b>&nbsp;<nobr>
        <td class="contenttab_internal_rows" width="80" colspan="8">
         <table width="65%" border="0" cellspacing="0" cellpadding="0" >
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getVisitorSourceData() ;
            ?>
         </table>
         </nobr>
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows" width="50px"><nobr><b>Paper Name</b></nobr></td>
        <td class="contenttab_internal_rows" width="2px"><b>:</b></td>
        <td class="contenttab_internal_rows" colspan="15"><nobr>&nbsp;
            <input type="text" id="paperName" style="width:250px" name="paperName" class="inputbox" maxlength="100" />
        </nobr></td>
    </tr>
    <tr><td height="10px"></td></tr>
    <tr>
        <td class="contenttab_internal_rows" width="50px"><nobr><b>Remarks</b></nobr></td>
        <td class="contenttab_internal_rows" width="2px"><b>:</b></td>
        <td class="contenttab_internal_rows" colspan="15"><nobr>&nbsp;
            <textarea type="text" id="studentRemarks" name="studentRemarks" class="inputbox1" rows="2" cols="118"/></textarea>
        </nobr></td>
    </tr>
    <tr><td height="10px"></td></tr>
    <tr>
       <td class="padding" align="center" colspan="15">
         <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
         <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddStudentEnquiry');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}  document.getElementById('divCity').style.display=''; return false;  " />
       </td>
    </tr>
    </table>
</form>

<?php floatingDiv_End(); ?>

<?php
// $History: listStudentContents.php $
//
//*****************  Version 22  *****************
//User: Parveen      Date: 4/13/10    Time: 4:36p
//Updated in $/LeapCC/Templates/StudentEnquiry
//query and validation format updated
//
//*****************  Version 21  *****************
//User: Parveen      Date: 3/29/10    Time: 12:05p
//Updated in $/LeapCC/Templates/StudentEnquiry
//look & feel updated
//
//*****************  Version 20  *****************
//User: Parveen      Date: 3/23/10    Time: 6:34p
//Updated in $/LeapCC/Templates/StudentEnquiry
//query & condition format updated
//
//*****************  Version 19  *****************
//User: Parveen      Date: 3/18/10    Time: 12:45p
//Updated in $/LeapCC/Templates/StudentEnquiry
//validation & condition updated
//
//*****************  Version 18  *****************
//User: Parveen      Date: 3/05/10    Time: 4:58p
//Updated in $/LeapCC/Templates/StudentEnquiry
//validation & condition format updated
//
//*****************  Version 17  *****************
//User: Parveen      Date: 3/05/10    Time: 1:08p
//Updated in $/LeapCC/Templates/StudentEnquiry
//comp. exam roll no. validation check added
//
//*****************  Version 16  *****************
//User: Parveen      Date: 3/03/10    Time: 11:36a
//Updated in $/LeapCC/Templates/StudentEnquiry
//visitor details added
//
//*****************  Version 15  *****************
//User: Parveen      Date: 2/20/10    Time: 12:43p
//Updated in $/LeapCC/Templates/StudentEnquiry
//validation and format updated
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 4/01/10    Time: 19:01
//Updated in $/LeapCC/Templates/StudentEnquiry
//Made UI changes
//
//*****************  Version 13  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/StudentEnquiry
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 12  *****************
//User: Administrator Date: 3/06/09    Time: 17:39
//Updated in $/LeapCC/Templates/StudentEnquiry
//corrected lalebls
//
//*****************  Version 11  *****************
//User: Parveen      Date: 6/02/09    Time: 5:30p
//Updated in $/LeapCC/Templates/StudentEnquiry
//validation modify & formatting update
//
//*****************  Version 10  *****************
//User: Parveen      Date: 6/02/09    Time: 3:54p
//Updated in $/LeapCC/Templates/StudentEnquiry
//spellling correct
//
//*****************  Version 9  *****************
//User: Parveen      Date: 6/02/09    Time: 3:41p
//Updated in $/LeapCC/Templates/StudentEnquiry
//format update
//
//*****************  Version 8  *****************
//User: Administrator Date: 1/06/09    Time: 17:18
//Updated in $/LeapCC/Templates/StudentEnquiry
//Updated student enquiry module
//
//*****************  Version 7  *****************
//User: Parveen      Date: 6/01/09    Time: 1:16p
//Updated in $/LeapCC/Templates/StudentEnquiry
//search filter alignment setting (IE)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/01/09    Time: 1:08p
//Updated in $/LeapCC/Templates/StudentEnquiry
//search filter alignment settings
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/01/09    Time: 11:11a
//Updated in $/LeapCC/Templates/StudentEnquiry
//enquiryDate format updated
//
//*****************  Version 4  *****************
//User: Administrator Date: 30/05/09   Time: 17:57
//Updated in $/LeapCC/Templates/StudentEnquiry
//Corrected bugs
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/30/09    Time: 2:40p
//Updated in $/LeapCC/Templates/StudentEnquiry
//enquiryDate added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/30/09    Time: 11:27a
//Updated in $/LeapCC/Templates/StudentEnquiry
//formating & conditions update
//
//*****************  Version 1  *****************
//User: Administrator Date: 29/05/09   Time: 16:51
//Created in $/LeapCC/Templates/StudentEnquiry
//Created "Student Enquiry" module
?>