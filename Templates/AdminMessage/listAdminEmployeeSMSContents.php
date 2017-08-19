<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Institute Notices &nbsp;&raquo;&nbsp;Send SMS to Employee</td>
                <td valign="top" align="right">
                 <!-- 
                <form action="" method="" name="searchForm">
               
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                  <input type="submit" value="Search" name="submit"  class="button" style="margin-bottom: 3px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>&nbsp;
                  </form>
                   --> 
                  </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Send SMS : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td valign="top" class="contenttab_row" style="border-bottom-width:0px">
              <table cellpadding="0" cellspacing="0" border="0" width="100%" >
               <tr>
               <td valign="top" width="40%">
                <table cellpadding="0" cellspacing="0" border="0" width="100%" >
                 <tr><td valign="top">
                  <div id="subjectDiv" >
                    <b>Subject : </b><input type="text" name="msgSubject" id="msgSubject" class="inputbox" style="width:395px" maxlength="100">
                   </div> 
                  </td>
                 </tr>
                 <tr><td height="5px"></td></tr> 
                 <tr>
                    <td valign="top" width="40%" style="padding-left:5px">
                  <textarea id="elm1" name="elm1" style="height:100px;" rows="5" cols="53" onkeyup="smsCalculation(this.value,SMSML,'sms_no')" ></textarea>
                 </td>
               </td>
               </tr>
               <tr>
               <td valign="top">
               <div id="smsDiv" class="field3_heading"  style="width:50%" >
                SMS Length :<input type="text" id="sms_char" name="sms_char" class="small_txt" value="0" disabled="true" />
               &nbsp;&nbsp;&nbsp;SMS(s) :     <input type="text" id="sms_no" name="sms_no" class="small_txt" value="1" disabled="true" />
              </div>
             </td>
            </tr> 
           </table>

<td align="left" valign="top" width="60%" style="padding-left:5px;padding-top:5px" >
<form action="" method="" name="searchForm">
<input type="hidden" name="searchType"    id="searchType" value="" />

<input type="hidden" name="roleId"         id="roleId" value="" />
<input type="hidden" name="isTeaching"     id="isTeaching" value="" />
<input type="hidden" name="designationId"  id="designationId" value="" />
<input type="hidden" name="branchId"       id="branchId" value="" />
<input type="hidden" name="cityId"         id="cityId" value="" />

<input type="hidden" name="married"        id="married" value="" />
<input type="hidden" name="dateOfBirthF"    id="dateOfBirthF" value="" />
<input type="hidden" name="dateOfBirthT"    id="dateOfBirthT" value="" />
<input type="hidden" name="dateOfJoiningF"  id="dateOfJoiningF" value="" />
<input type="hidden" name="dateOfJoiningT"  id="dateOfJoiningT" value="" />       
<!--<input type="hidden" name="dateOfMarriage" id="dateOfMarriage" value="" />-->
<!--<input type="hidden" name="dateOfLeaving"  id="dateOfLeaving" value="" />-->


<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td>
<div id="general" class="wrap">
 <div class="left">
  <div class="box">
  <div class="b2">
   <div class="b3">
    <div class="b4">
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
         <td valign="top" class="contenttab_internal_rows"><b>Role:</b></td>
         <td align="left" valign="top" class="padding2">
          <select id="role" name="role" style="width:175px"  multiple="multiple" size="2"  >
            <!--<option value="">Select</option>-->
           <?php
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
             echo HtmlFunctions::getInstance()->getRoleData(''," WHERE roleId NOT IN(3,4)");
           ?>
          </select>
         </td>
         <td valign="top" class="contenttab_internal_rows"><nobr><b>Teacher:</b></nobr></td>
         <td align="left" valign="top">
          <input type="radio" name="teaching" value="1" />Yes
          <input type="radio" name="teaching" value="0" />No
         </td>
       </tr>
       <tr>
        <td>&nbsp;</td>
        <td align="left"  colspan="3" style="padding:3px">
          Select &nbsp;<a class="allReportLink" href='javascript:makeSelectDeselect("role","All");'>All</a> / &nbsp;<a class="allReportLink" href='javascript:makeSelectDeselect("role","None");'>None</a> 
        </td>
       </tr>   
       <tr>
         <td valign="top" class="contenttab_internal_rows"><b>Designation:</b></td>
         <td align="left" valign="top" class="padding2">
          <select id="designation" name="designation" style="width:175px" multiple size="2">
           <!--<option value="">Select</option>-->
           <?php
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
             echo HtmlFunctions::getInstance()->getDesignationData();
           ?>
          </select>
         </td>
         <td valign="top" class="contenttab_internal_rows"><b>Branch:</td>
         <td align="left" valign="top" class="padding2">
          <select id="branch" name="branch" style="width:180px" multiple size="2">
          <!--<option value="">Select</option>-->
           <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->getBranchData();
           ?>
          </select>
         </td>
       </tr>
       <tr>
        <td>&nbsp;</td>
        <td align="left"  colspan="1" style="padding:3px">
          Select &nbsp;<a class="allReportLink" href='javascript:makeSelectDeselect("designation","All");'>All</a> / &nbsp;<a class="allReportLink" href='javascript:makeSelectDeselect("designation","None");'>None</a> 
        </td>
        <td>&nbsp;</td>
        <td align="left"  colspan="1" style="padding:3px">
          Select &nbsp;<a class="allReportLink" href='javascript:makeSelectDeselect("branch","All");'>All</a> / &nbsp;<a class="allReportLink" href='javascript:makeSelectDeselect("branch","None");'>None</a> 
        </td>
       </tr>   
       <tr>
         <td valign="top" class="contenttab_internal_rows"><b>City:</b></td>
         <td align="left" valign="top" class="padding2">
          <select id="city" name="city" style="width:175px" multiple size="2">
           <!--<option value="">Select</option>-->
           <?php
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
             echo HtmlFunctions::getInstance()->getCityData();
           ?>
          </select>
         </td>  
         <td>&nbsp;</td>
         <td align="left" class="padding2"><input type="image" id="simageField1" name="simageField1" onClick="getData(1);return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" /></td>
       </tr>
       <tr>
        <td>&nbsp;</td>
        <td align="left"  colspan="3" style="padding:3px">
          Select &nbsp;<a class="allReportLink" href='javascript:makeSelectDeselect("city","All");'>All</a> / &nbsp;<a class="allReportLink" href='javascript:makeSelectDeselect("city","None");'>None</a> 
        </td>
       </tr> 
      </table>
     </div>
    </div>
   </div>
  </div>
 </div>
</td></tr>

<tr><td>

<img id="toggleButton" src="<?php echo IMG_HTTP_PATH;?>/plus.gif" onclick="moveListButton(dMode);" title="Expand" />
<div id="specific" class="wrap" style="display:none">
 <div class="left">
  <div class="box">
  <div class="b2">
   <div class="b3">
    <div class="b4">
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
         <td valign="top" class="contenttab_internal_rows"><b>Qualifications:</b></td>
         <td align="left" valign="top" class="padding2">
          <input type="text" name="qualification" id="qualification" class="inputbox" style="width:150px" />
         </td>
         <td valign="top" class="contenttab_internal_rows"><nobr><b>Marital Status:</b></nobr></td>
         <td align="left" valign="top">
          <input type="radio" name="isMarried" value="1" />Married
          <input type="radio" name="isMarried" value="0" />Unmarried
         </td>
       </tr>
       
       <tr><td valign="top" align="left" colspan="4"><b>BirthDate</b></td></tr>
       <tr>
         <td valign="top" class="contenttab_internal_rows"><b>From:</b></td>
         <td align="left" valign="top" class="padding2">
         <nobr>
           <select size="1" name="birthYearF" id="birthYearF">
            <option value="">Sel</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBirthYear();
            ?>
            </select>
            <select size="1" name="birthMonthF" id="birthMonthF" >
            <option value="">Sel</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBirthMonth();
            ?>
            </select>
            <select size="1" name="birthDateF" id="birthDateF" >
            <option value="">Sel</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBirthDate();
            ?>
            </select>
          </nobr>  
         </td>
         <td valign="top" class="contenttab_internal_rows"><b>To:</td>
         <td align="left" valign="top" class="padding2">
          <nobr>
           <select size="1" name="birthYearT" id="birthYearT">
            <option value="">Sel</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBirthYear();
            ?>
            </select>
            <select size="1" name="birthMonthT" id="birthMonthT" >
            <option value="">Sel</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBirthMonth();
            ?>
            </select>
            <select size="1" name="birthDateT" id="birthDateT" >
            <option value="">Sel</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBirthDate();
            ?>
            </select>
          </nobr> 
         </td>
       </tr>
       
      <tr><td valign="top" align="left" colspan="4"><b>JoiningDate</b></td></tr>        
       <tr>
         <td valign="top" class="contenttab_internal_rows"><b>From:</b></td>
         <td align="left" valign="top" class="padding2">
         <nobr>
           <select size="1" name="joiningYearF" id="joiningYearF">
            <option value="">Sel</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBirthYear();
            ?>
            </select>
            <select size="1" name="joiningMonthF" id="joiningMonthF" >
            <option value="">Sel</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBirthMonth();
            ?>
            </select>
            <select size="1" name="joiningDateF" id="joiningDateF" >
            <option value="">Sel</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBirthDate();
            ?>
            </select>
          </nobr>  
         </td>
         <td valign="top" class="contenttab_internal_rows"><b>To:</td>
         <td align="left" valign="top" class="padding2">
          <nobr>
           <select size="1" name="joiningYearT" id="joiningYearT">
            <option value="">Sel</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBirthYear();
            ?>
            </select>
            <select size="1" name="joiningMonthT" id="joiningMonthT" >
            <option value="">Sel</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBirthMonth();
            ?>
            </select>
            <select size="1" name="joiningDateT" id="joiningDateT" >
            <option value="">Sel</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBirthDate();
            ?>
            </select>
          </nobr> 
         </td>
       </tr>
       <tr><td colspan="6" height="10"></td></tr>
       <tr>
         <td valign="top" class="contenttab_internal_rows">&nbsp;</td>
         <td align="left" valign="top" class="padding2">&nbsp;</td>
         <td>&nbsp;</td>
         <td align="left" class="padding2" valign="middle"><input type="image" id="simageField2" name="simageField2" onClick="getData(2);return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" /></td>
       </tr>
      </table>   
     </div>
    </div>
   </div>
  </div>
 </div>
</div>

</td></tr>
</table>
</form>
   </td>
  </tr> 
 </table>    
             </td>
             </tr>
             
             <tr>
                <td class="contenttab_row"  valign="top">&nbsp;
                <div id="showList" style="display:none">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                 <td>
                <form name="listFrm" id="listFrm">
                <!--Do not delete-->
                 <input type="hidden" name="emps" id="emps" />
                 <input type="hidden" name="emps" id="emps" />  
                 <!--Do not delete-->
                 
                 <div id="results">
                </div>
                </form>           
                </td>
               </tr>
               <tr><td height="5px"></td></tr>
               <tr> 
                <td align="center">
                <div id="divButton" style="display:none">
                  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/send.gif" onClick="return validateForm();return false;" />
                 <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="hide_div('showList',2);return false;" />
                </div> 
                 </td>
               </tr>
               <tr><td height="5px"></td></tr>
              </table> 
              </div>
             </td>
          </tr>
          
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>

<?php
// $History: listAdminEmployeeSMSContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/AdminMessage
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 9/17/08    Time: 4:14p
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 9/08/08    Time: 4:47p
//Updated in $/Leap/Source/Templates/AdminMessage
//Modified so that "Student" and "Parent" role does not visible
//to the user.
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 9/08/08    Time: 4:06p
//Updated in $/Leap/Source/Templates/AdminMessage
//Updated according to Kabir Sir's suggestion
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 9/05/08    Time: 12:11p
//Updated in $/Leap/Source/Templates/AdminMessage
//Added employee search filter
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/04/08    Time: 6:03p
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/01/08    Time: 6:42p
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/28/08    Time: 6:08p
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/21/08    Time: 4:09p
//Updated in $/Leap/Source/Templates/AdminMessage
//Changed Image Name of submit button
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/18/08    Time: 11:21a
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:21p
//Updated in $/Leap/Source/Templates/AdminMessage
//corrected breadcrumb and reset button height and width
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/11/08    Time: 4:31p
//Updated in $/Leap/Source/Templates/AdminMessage
?>