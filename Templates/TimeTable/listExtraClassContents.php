<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<form method="post" name="searchExtraForm" id="searchExtraForm" onsubmit="return false;">
<input type="hidden" value="" name="hiddenActiveTimeTableId"  id="hiddenActiveTimeTableId">
<?php
 require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>

    <tr>
        <td valign="top" colspan="2">
           <tr><td height="5px"></td></tr>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
                    <td valign="top" class="content">
                        <table width="100%" class="contenttab_border1"  border="0" cellspacing="0" cellpadding="0">
                           <tr><td height="10px"></td></tr>
                           </tr>
                           <tr>
                             <td>
                             
         <table width="20%" border="0" cellspacing="2px" cellpadding="0px" align="left" style="padding-left:10px;">
         <?php
          if($roleId!=2){
          ?>
            <tr>
                <td class="contenttab_internal_rows" align="left"><nobr><b>Time Table </b></nobr></td>
                <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                <td class="contenttab_internal_rows" align="left" colspan="10"><nobr>
                <select style="width:320px" size="1" name="timeTableLabelId" id="timeTableLabelId" class="selectfield" onChange="getSearchValue('E');">
                     <option value="">Select</option>
                     <?php
                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                      echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                     ?>
                    </select></nobr>
                </td>
             </tr>
             <tr>
                <td class="contenttab_internal_rows" align="left"><nobr><b>Substitute For</b></nobr></td>
                <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                <td class="contenttab_internal_rows" align="left" colspan="10"><nobr>
                    <select size="1" name="substituteEmployeeId" style="width:320px" id="substituteEmployeeId" class="selectfield">
                    <option value="">Select</option>
                    </select>
                    </nobr>
              </td>
              <td class="contenttab_internal_rows" align="left"><nobr><b>Employee Name</b></nobr></td>
              <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
              <td class="contenttab_internal_rows" align="left"><nobr>
                    <select size="1" style="width:320px" name="employeeId" id="employeeId" class="selectfield">
                      <option value="">All</option>
                      <?php
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                        echo HtmlFunctions::getInstance()->getEmployeeHighlighted();
                      ?>
                    </select>
                    </nobr>
              </td>
           </tr>        
          <?php
           }
          ?>
          
                  
          
                    <tr style='display:none'>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Class</b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr>
                       <select size="1" style="width:320px"  class="selectfield" name="classId" id="classId" onchange="getSearchValue('S');" >
                        <option value="">Select</option>
                        <?php
                        if($roleId==2){
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTeacherClassData();
                        }
                        ?>
                      </select></nobr></td>
                        <td class="contenttab_internal_rows" style="padding-left:5px" align="left"><nobr><b>Subject</b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr>
                         <select style="width:140px" align="right" size="1" class="selectfield" name="subjectId" id="subjectId" onchange="getSearchValue('G');" >
                        <option value="">Select Subject</option>
                        </select></nobr>
                      </td>
                     <td class="contenttab_internal_rows" align="left" style="padding-left:7px"><nobr><b>Group</b></nobr></td>
                     <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                     <td class="contenttab_internal_rows" align="left"><nobr>
                        <select style="width:135px" size="1" class="selectfield" name="groupId" id="groupId"  onchange="getSearchValue('P');">
                        <option value="">Select</option>
                        </select></nobr>
                      </td>
                      <td style="display:none" class="contenttab_internal_rows" align="left"><nobr><b>Period</b></nobr></td>
                      <td style="display:none"  class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                       <td class="contenttab_internal_rows" align="left" colspan="10"><nobr>
                            <td style="display:none" class="contenttab_internal_rows" align="left" nowrap> 
                       <select style="width:200px"  size="1" class="selectfield" name="periodId" id="periodId" >
                        <option value="">Select</option>
                        </select> 
                      </td>
                    </tr>
                    <tr>  
                      <td class="contenttab_internal_rows" align="left"><nobr><b>Date Check</b></nobr></td>
                      <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                      <td class="contenttab_internal_rows" align="left" colspan="10"><nobr>
                         <table width="10%" border="0" cellspacing="0" cellpadding="0" align="left">
                         <tr>
   <td class="contenttab_internal_rows" align="left" nowrap> 
<select size="1" style="width:150px" class="inputbox1" onchange="getDateClear(this.value);" name="searchDateFilter" id="searchDateFilter">
<option value="">Select</option>
<option value="1">Extra Attedance</option>
</select>
</td>
 <td id="lblDt1"  style="display:none" class="contenttab_internal_rows" align="left"><nobr>&nbsp;&nbsp;From&nbsp;</nobr></td>
 <td id="lblDt2"  style="display:none" class="contenttab_internal_rows" align="left" id='lblDate1'><nobr>
    <?php  
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->datePicker('searchFromDate');  
    ?>
   </nobr>
 </td>
 <td id="lblDt3" style="display:none" class="contenttab_internal_rows" align="left" style="padding-left:10px;"><nobr>To&nbsp;&nbsp;</nobr></td>
 <td id="lblDt4" style="display:none" class="contenttab_internal_rows" align="left" id='lblDate2'><nobr>
     <?php 
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->datePicker('searchToDate');  
     ?>
  </nobr> 
 </td>
</tr>
</table> 
                       </nobr>  
                     </td>
 <td  class="contenttab_internal_rows" align="left" style="padding-left:20px">
<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateShowList();return false;" />
</td>
               
                      
                    </tr>
               </table>
                        </td>
                        </tr>      
                        <tr><td height="5px"></td></tr>
                        <tr>
                        <td class="contenttab_internal_rows" colspan="20">
                         <nobr>
                            <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 10px; color: red;"> 
                                &nbsp;&nbsp;*&nbsp;
Employees who left the job are displayed in red color
                            </span>
                         </nobr>
                        </td>
                        </tr>
                        <tr><td height="8px"></td></tr>
                        </table>
                 

         
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr height="30">
                                <td class="contenttab_border" height="20" style="border-right:0px;">
                                   <table>
                                     <tr>
                                        <td class="content_title" width="80%">Extra Class Details : </td>
                                     </tr>
                                   </table>  
                                </td>
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;">
                                      <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddExtraClass',300,200);blankValues(); return false;"  />&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" >
                                    <div id="results"></div>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" colspan="2">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                                        <tr>
                                            <td class="content_title" valign="middle" align="right" width="20%">
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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

 
<?php floatingDiv_Start('AddExtraClass','Add Extra Class Details'); ?> 
         <form method="post" name="searchForm" id="searchForm" onsubmit="return false;">
         
                 <table width="100%" border="0" cellspacing="2px" cellpadding="2px" >
                 <tr><td height="5px"></td></tr>
                 <?php
                  if($roleId!=2){
                  ?>
                    <tr>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Time Table <?php echo REQUIRED_FIELD; ?></b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr>
                        <select style="width:310px"  size="1" name="timeTableLabelId" id="timeTableLabelId" class="selectfield" onchange="autoPopulateEmployee(this.value,'add');">
                             <option value="">Select</option>
                             <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                             ?>
                            </select></nobr>
                        </td>
                        <td class="contenttab_internal_rows" align="left" colspan="3"><nobr>
                         <table>
                           <tr>
                             <td class="contenttab_internal_rows" align="left" colspan="3"><nobr>
                              <b>Show for Employee Name</b></nobr></td>
                             </td>  
                             <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                             <td class="contenttab_internal_rows" align="left"><nobr>
                               <input name="showEmployeeName" id="showEmployeeName1" value='1' checked="checked" onclick="getEmployeeName();" type="radio">Teaching&nbsp;
                               <input name="showEmployeeName" id="showEmployeeName2" value='2' onclick="getEmployeeName();" type="radio">Non Teaching&nbsp;
                               <input name="showEmployeeName" id="showEmployeeName3" value='3' onclick="getEmployeeName();" type="radio">Both
                             </nobr></td>
                           </tr>
                          </table>
                        </td>     
                     </tr>
                     <tr>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Substitute For<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr>
                        <select size="1" name="substituteEmployeeId" style="width:310px" id="substituteEmployeeId" onChange="getClassData('add');" class="selectfield">
                        <option value="">Select</option>
                        </select>
                        </td>       
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Employee Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr>
                         <select size="1" style="width:330px" name="employeeId" id="employeeId" class="selectfield">
                                <option value="">Select</option>
                                  <?php
                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                    echo HtmlFunctions::getInstance()->getEmployeeHighlighted();
                                  ?>
                                </select>
                         </td>
                      </tr>         
                  <?php
                   }
                  ?>
                    <tr>
                       <td class="contenttab_internal_rows" align="left"><nobr><b>Attendance Date</b></nobr></td>
                       <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                       <td class="contenttab_internal_rows" align="left"><nobr>
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->datePicker('forDate',date('Y-m-d'));
                         ?> &nbsp;
                         <!--
                         <input type="image" name="imageField5" id="imageField5" onClick="getAttendanceOptions('add');return false" src="<?php echo IMG_HTTP_PATH;?>/schedule.gif" style="margin-bottom: -5px;" />&nbsp;
                         -->
                        </td>
                        <td class="contenttab_internal_rows" align="left" ><nobr><b>Class<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr>
                       <select size="1" class="selectfield" style="width:330px"  name="classId" style="overflow:auto" id="classId" onchange="populateSubjects(this.value,'add');" >
                        <option value="">Select</option>
                        <?php
                        if($roleId==2){
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTeacherClassData();
                        }
                        ?>
                      </select></nobr></td>
                    </tr>
                    <tr>
                      <td class="contenttab_internal_rows" align="left"><nobr><b>Subject<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                        <td class="contenttab_internal_rows" align="left" colspan="10"><nobr>   
                         <table border="0" cellpadding="0px" cellspacing="0px">
                            <tr>
                              <td class="contenttab_internal_rows" align="left"><nobr>
                         <select style="width:220px" align="right" size="1" class="selectfield" name="subjectId" id="subjectId" onchange="groupPopulate('add');" >
                        <option value="">Select Subject</option>
                        </select></nobr>
                     </td>
                     <td class="contenttab_internal_rows" style="padding-left:5px" align="left"><nobr><b>Group<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                     <td class="contenttab_internal_rows" align="left"><nobr><b>:&nbsp;</b></nobr></td>
                     <td class="contenttab_internal_rows" align="left"><nobr>
                     <select style="width:205px" size="1" class="selectfield" name="groupId" id="groupId">
                                <option value="">Select</option>
                                </select></nobr>
                              </td>
                              <td class="contenttab_internal_rows" style="padding-left:5px" align="left"><nobr><b>Period<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                              <td class="contenttab_internal_rows" align="left"><nobr><b>:&nbsp;</b></nobr></td>
                              <td class="contenttab_internal_rows" align="left"><nobr>
                               <select size="1" class="selectfield" style="width:210px"  name="periodId" id="periodId" >
                                <option value="">Select</option>
                                <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getAllPeriods();
                                ?>
                                </select>
                                </nobr>
                              </td>
                            </tr>
                        </table></nobr>
                      </td> 
                    </tr>
                    <tr>
                      <td class="contenttab_internal_rows" align="left"><nobr><b>Comments</b></nobr></td>
                      <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                      <td class="contenttab_internal_rows" align="left" colspan="10"><nobr>
                      <nobr>
      <input style="width:745px" class="inputbox" type="text" name="commentTxt" id="commentTxt" maxlength="255" /></nobr>
                      </td>
                    </tr>
                    <tr><td height="10px"></td></tr>
                    <tr>
 <td align="center" colspan="12">
    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
    <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddExtraClass');if(flag==true){refereshData();flag=false;}return false;" />
    </td>
                    </tr>
               </table>     
    </form>     
<?php floatingDiv_End(); ?>
<!--End Add Div-->


<?php floatingDiv_Start('EditExtraClass','Edit Extra Class Details'); ?> 
    <form name="editExtraClass" id="editExtraClass" method="post"> 
        <input type="hidden" name="extraId" id="extraId" value="" /> 
       <table width="100%" border="0" cellspacing="2px" cellpadding="2px" >
                 <tr><td height="5px"></td></tr>
                 <?php
                  if($roleId!=2){
                  ?>
                    <tr>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Time Table <?php echo REQUIRED_FIELD; ?></b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                        <td class="contenttab_internal_rows" align="left" colspan="10"><nobr>
                        <select style="width:310px"  size="1" name="timeTableLabelId" id="timeTableLabelId" class="selectfield" onchange="autoPopulateEmployee(this.value,'edit');">
                             <option value="">Select</option>
                             <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                             ?>
                            </select></nobr>
                        </td>
                     </tr>
                     <tr>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Substitute For<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr>
                        <select size="1" name="substituteEmployeeId" style="width:310px" id="substituteEmployeeId" onChange="getClassData('edit');" class="selectfield">
                        <option value="">Select</option>
                        </select>
                        </td>       
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Employee Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr>
                         <select size="1" style="width:330px" name="employeeId" id="employeeId" class="selectfield">
                                <option value="">Select</option>
                                  <?php
                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                    echo HtmlFunctions::getInstance()->getEmployeeHighlighted();
                                  ?>
                                </select>
                         </td>
                      </tr>         
                  <?php
                   }
                  ?>
                    <tr>
                       <td class="contenttab_internal_rows" align="left"><nobr><b>Attendance Date</b></nobr></td>
                       <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                       <td class="contenttab_internal_rows" align="left"><nobr>
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->datePicker('forDate1',date('Y-m-d'));
                         ?> &nbsp;
                         <!--
                         <input type="image" name="imageField5" id="imageField5" onClick="getAttendanceOptions('add');return false" src="<?php echo IMG_HTTP_PATH;?>/schedule.gif" style="margin-bottom: -5px;" />&nbsp;
                         -->
                        </td>
                        <td class="contenttab_internal_rows" align="left" ><nobr><b>Class<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr>
                       <select size="1" class="selectfield" style="width:330px"  name="classId" style="overflow:auto" id="classId" onchange="populateSubjects(this.value,'edit');" >
                        <option value="">Select</option>
                        <?php
                        if($roleId==2){
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTeacherClassData();
                        }
                        ?>
                      </select></nobr></td>
                    </tr>
                    <tr>
                      <td class="contenttab_internal_rows" align="left"><nobr><b>Subject<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                        <td class="contenttab_internal_rows" align="left" colspan="10"><nobr>   
                         <table border="0" cellpadding="0px" cellspacing="0px">
                            <tr>
                              <td class="contenttab_internal_rows" align="left"><nobr>
                         <select style="width:220px" align="right" size="1" class="selectfield" name="subjectId" id="subjectId" onchange="groupPopulate('edit');" >
                        <option value="">Select Subject</option>
                        </select></nobr>
                     </td>
                     <td class="contenttab_internal_rows" style="padding-left:5px" align="left"><nobr><b>Group<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                     <td class="contenttab_internal_rows" align="left"><nobr><b>:&nbsp;</b></nobr></td>
                     <td class="contenttab_internal_rows" align="left"><nobr>
                     <select style="width:205px" size="1" class="selectfield" name="groupId" id="groupId">
                                <option value="">Select</option>
                                </select></nobr>
                              </td>
                              <td class="contenttab_internal_rows" style="padding-left:5px" align="left"><nobr><b>Period<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                              <td class="contenttab_internal_rows" align="left"><nobr><b>:&nbsp;</b></nobr></td>
                              <td class="contenttab_internal_rows" align="left"><nobr>
                               <select size="1" class="selectfield" style="width:210px"  name="periodId" id="periodId" >
                                <option value="">Select</option>
                                <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getAllPeriods();
                                ?>
                                </select>
                                </nobr>
                              </td>
                            </tr>
                        </table></nobr>
                      </td> 
                    </tr>
                    <tr>
                      <td class="contenttab_internal_rows" align="left"><nobr><b>Comments</b></nobr></td>
                      <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
                      <td class="contenttab_internal_rows" align="left" colspan="10"><nobr>
                      <nobr>
      <input style="width:745px" class="inputbox" type="text" name="commentTxt" id="commentTxt" maxlength="255" /></nobr>
                      </td>
                    </tr>
                    <tr><td height="10px"></td></tr>
                    <tr>
 <td align="center" colspan="12">
    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
    <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditExtraClass');if(flag==true){refereshData();flag=false;}return false;" />
    </td>
                    </tr>
               </table>    
    </form>
<?php floatingDiv_End(); ?>
<!--End Edit Div-->
