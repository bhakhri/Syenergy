<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR UNIVERSITY LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
        <td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr height="30">
                                <td class="contenttab_border" height="20" style="border-right:0px;">
                                    <?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
                                </td>
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayFloatingDiv('AddUniversityDiv','',650,250,200,100);blankValues();return false;" />&nbsp;</td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
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
    </td>
    </tr>
    </table>
    <!--Start Add Div-->

<?php floatingDiv_Start('AddUniversityDiv','Add University'); ?>
<form name="AddUniversity" id="AddUniversity" action="<?php echo HTTP_LIB_PATH;?>/University/fileUpload.php" method="post" enctype="multipart/form-data" style="display:inline">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
 <td valign="top">
 <table width="50%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>University Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding"><input type="text" id="universityName" name="universityName" class="inputbox" maxlength="250" tabindex="1" /></td>
    </tr>
    
    <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr><b>Abbr.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
       <td width="1%" class="padding">:</td>
       <td width="50%" class="padding"><input type="text" id="universityAbbr" name="universityAbbr" class="inputbox" maxlength="10" tabindex="3"/></td>
    </tr>
   
    <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Address1<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding" valign="top">:</td>
        <td width="20%" class="padding">
         <textarea id="universityAddress1" name="universityAddress1" class="inputbox" cols="16" rows="5"  maxlength="255" onkeyup="return ismaxlength(this)" tabindex="5" /></textarea>
        </td>
    </tr>
   
    <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr><b>Website<?php echo REQUIRED_FIELD; ?></b></nobr></td>
       <td width="1%" class="padding">:</td>
       <td width="50%" class="padding"><input type="text" id="universityWebsite" name="universityWebsite" class="inputbox" maxlength="50" tabindex="7" /></td>
    </tr>
    <tr>
    <!--Cannot understand its meaning-->
       <td width="21%" class="contenttab_internal_rows"><nobr><b>Contact Person<?php echo REQUIRED_FIELD; ?></b></nobr></td>
       <td width="1%" class="padding">:</td>
       <td width="50%" class="padding">
       <input type="text" id="contactPerson" name="contactPerson" class="inputbox" maxlength="20" tabindex="8">
       </td>
    </tr>
   <tr>
      <td width="21%" class="contenttab_internal_rows"><nobr><b>Designation<?php echo REQUIRED_FIELD; ?></b></nobr></td>
      <td width="1%" class="padding">:</td>
       <td width="50%" class="padding">
       <select size="1" class="selectfield" name="designation" id="designation"  tabindex="10">
       <option value="" selected="true">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getDesignationData($REQUEST_DATA['designation']==''? $universityRecordArray[0]['designationId'] : $REQUEST_DATA['designation'] );
              ?>
        </select></td>
    </tr>
   <tr>
      <td width="21%" class="contenttab_internal_rows"><nobr><b>State<?php echo REQUIRED_FIELD; ?></b></nobr></td>
      <td width="1%" class="padding">:</td>
       <td width="50%" class="padding">
        <select size="1" class="selectfield" name="states" id="states" onChange="autoPopulate(this.value,'city','Add');" tabindex="12">
         <option value="" selected="selected">SELECT</option>
              <?php
                 // require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  //echo HtmlFunctions::getInstance()->getStatesData($REQUEST_DATA['states']==''? $universityRecordArray[0]['stateId'] : $REQUEST_DATA['states'] );
              ?>
        </select>
       </td>
    </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>PIN<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding" >:</td>
        <td width="20%" class="padding"><input type="text" id="pin" name="pin" class="inputbox" maxlength="10" tabindex="14" /></td>
    </tr>
   </table>
 </td>
 <td valign="top">
  <table width="50%" border="0" cellspacing="0" cellpadding="0">
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>University Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding"><input type="text" id="universityCode" name="universityCode" class="inputbox" maxlength="10" tabindex="2" /></td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Email<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding"><input type="text" id="universityEmail" name="universityEmail" class="inputbox" maxlength="50" tabindex="4" /></td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Address2<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding" valign="top">:</td>
        <td width="20%" class="padding">
         <textarea id="universityAddress2" name="universityAddress2" class="inputbox" cols="16" rows="5" maxlength="255" onkeyup="return ismaxlength(this)" tabindex="6" /></textarea>
        </td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Contact Phone<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding"><input type="text" id="contactNumber" name="contactNumber" class="inputbox" maxlength="20" tabindex="9" /></td>
   </tr>
   
   <tr>            
      <td width="21%" class="contenttab_internal_rows"><nobr><b>Country<?php echo REQUIRED_FIELD; ?></b></nobr></td>
      <td width="1%" class="padding">:</td>
       <td width="50%" class="padding">
       <select size="1" class="selectfield" name="country" id="country" onChange="autoPopulate(this.value,'states','Add');" tabindex="11">
        <option value="" selected="selected">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getCountriesData($REQUEST_DATA['country']==''? $universityRecordArray[0]['countryId'] : $REQUEST_DATA['country'] );
              ?>
        </select>
       </td>
    </tr>
   
   <tr>
      <td width="21%" class="contenttab_internal_rows"><nobr><b>City<?php echo REQUIRED_FIELD; ?></b></nobr></td>
      <td width="1%" class="padding">:</td>
       <td width="50%" class="padding">
       <select size="1" class="selectfield" name="city" id="city" tabindex="13">
       <option value="" selected="selected">SELECT</option>
              <?php
                  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  //echo HtmlFunctions::getInstance()->getCityData($REQUEST_DATA['city']==''? $universityRecordArray[0]['cityId'] : $REQUEST_DATA['city'] );
              ?>
        </select>
       </td>
    </tr>
    <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr><b>Logo</b></nobr></td>
       <td width="1%" class="padding">:</td>
       <td width="50%" class="padding">
        <input type="file" id="universityLogo" name="universityLogo" class="inputbox" tabindex="15">
       </td>
    </tr>
   </table>
</td>
</tr>
    <tr colspan="2">
        <td height="5px"></td></tr>
    <tr>

<tr colspan="2">
    <td align="center" style="padding-right:10px" colspan="2">
      <input type="image" name="imageAdd" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');" tabindex="16"/>
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddUniversityDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" tabindex="17" />
   </td>
</tr>
<tr colspan="2">
    <td height="5px"></td>
</tr>
</table>
<iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
</form>
 <?php floatingDiv_End(); ?>
<!--End Add Div-->
                                                           
<!--Start Edit Div-->
<?php floatingDiv_Start('EditUniversityDiv','Edit University '); ?>
<form name="EditUniversity" id="EditUniversity" action="<?php echo HTTP_LIB_PATH;?>/University/fileUpload.php" method="post" enctype="multipart/form-data" style="display:inline">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<input type="hidden" name="universityId" id="universityId" value="" />
<tr>
 <td valign="top">
 <table width="50%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>University Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding"><input type="text" id="universityName" name="universityName" class="inputbox" maxlength="250" tabindex="1" /></td>
    </tr>
    
    <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr><b>Abbr.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
       <td width="1%" class="padding">:</td>
       <td width="50%" class="padding"><input type="text" id="universityAbbr" name="universityAbbr" class="inputbox" maxlength="10" tabindex="3" /></td>
    </tr>
   
    <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Address1<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding" valign="top">:</td>
        <td width="20%" class="padding">
         <textarea id="universityAddress1" name="universityAddress1" class="inputbox" cols="16" rows="5" maxlength="255" onkeyup="return ismaxlength(this)" tabindex="5" /></textarea>
        </td>
    </tr>
   
    <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr><b>Website<?php echo REQUIRED_FIELD; ?></b></nobr></td>
       <td width="1%" class="padding">:</td>
       <td width="50%" class="padding"><input type="text" id="universityWebsite" name="universityWebsite" class="inputbox" maxlength="50" tabindex="7" /></td>
    </tr>
    <tr>
    <!--Cannot understand its meaning-->
       <td width="21%" class="contenttab_internal_rows"><nobr><b>Contact Person<?php echo REQUIRED_FIELD; ?></b></nobr></td>
       <td width="1%" class="padding">:</td>
       <td width="50%" class="padding"><input type="text" id="contactPerson" name="contactPerson" class="inputbox" maxlength="20" tabindex="8"></td>
    </tr>
   <tr>
      <td width="21%" class="contenttab_internal_rows"><nobr><b>Designation<?php echo REQUIRED_FIELD; ?></b></nobr></td>
      <td width="1%" class="padding">:</td>
       <td width="50%" class="padding"><select size="1" class="selectfield" name="designation" id="designation" tabindex="10"> <option value="" selected="selected">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getDesignationData($REQUEST_DATA['designation']==''? $universityRecordArray[0]['designationId'] : $REQUEST_DATA['designation'] );
              ?>
        </select></td>
    </tr>
   <tr>
      <td width="21%" class="contenttab_internal_rows"><nobr><b>State<?php echo REQUIRED_FIELD; ?></b></nobr></td>
      <td width="1%" class="padding">:</td>
       <td width="50%" class="padding">
       <select size="1" class="selectfield" name="states" id="states" onChange="autoPopulate(this.value,'city','Edit');" tabindex="12">
       <option value="" selected="selected">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getStatesData($REQUEST_DATA['states']==''? $universityRecordArray[0]['stateId'] : $REQUEST_DATA['states'] );
              ?>
        </select></td>
    </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>PIN<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding"><input type="text" id="pin" name="pin" class="inputbox" maxlength="10" tabindex="14" /></td>
    </tr>
   </table>
 </td>
 <td valign="top">
  <table width="50%" border="0" cellspacing="0" cellpadding="0">
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>University Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding"><input type="text" id="universityCode" name="universityCode" class="inputbox" maxlength="10" tabindex="2" /></td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Email<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding"><input type="text" id="universityEmail" name="universityEmail" class="inputbox" maxlength="50" tabindex="4" /></td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Address2<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding" valign="top">:</td>
        <td width="20%" class="padding">
         <textarea id="universityAddress2" name="universityAddress2" class="inputbox" cols="16" rows="5" maxlength="255" onkeyup="return ismaxlength(this)" tabindex="6" /></textarea>
        </td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Contact Phone<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding"><input type="text" id="contactNumber" name="contactNumber" class="inputbox" maxlength="20" tabindex="9" /></td>
   </tr>
   
   <tr>
   <tr>
      <td width="21%" class="contenttab_internal_rows"><nobr><b>Country<?php echo REQUIRED_FIELD; ?></b></nobr></td>
      <td width="1%" class="padding">:</td>
       <td width="50%" class="padding">
       <select size="1" class="selectfield" name="country" id="country" onChange="autoPopulate(this.value,'states','Edit');" tabindex="11">
       <option value="">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getCountriesData($REQUEST_DATA['country']==''? $universityRecordArray[0]['countryId'] : $REQUEST_DATA['country'] );
              ?>
        </select>
        </td>
    </tr>
   
   <tr>
      <td width="21%" class="contenttab_internal_rows"><nobr><b>City<?php echo REQUIRED_FIELD; ?></b></nobr></td>
      <td width="1%" class="padding">:</td>
       <td width="50%" class="padding">
       <select size="1" class="selectfield" name="city" id="city" tabindex="13">
       <option value="">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getCityData($REQUEST_DATA['city']==''? $universityRecordArray[0]['cityId'] : $REQUEST_DATA['city'] );
              ?>
        </select></td>
    </tr>
    <tr>
       <td width="21%" class="contenttab_internal_rows" valign="top" style="padding-top:5px"><nobr><b>Logo</b></nobr></td>
       <td width="1%" class="padding" valign="top" style="padding-top:5px">:</td>
       <td width="50%" class="padding">
        <input type="file" id="universityLogo" name="universityLogo" class="inputbox" tabindex="15" >
        <div id="editLogoPlace" class="cl"></div>
       </td>
    </tr>
   </table>
</td>
</tr>
    <tr colspan="2">
        <td height="5px"></td></tr>
    <tr>

<tr colspan="2">
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageEdit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');" tabindex="16" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onClick="javascript:hiddenFloatingDiv('EditUniversityDiv');return false;" tabindex="17" />
   </td>
</tr>
<tr colspan="2">
    <td height="5px"></td></tr>
<tr>
</table>
<iframe id="uploadTargetEdit" name="uploadTargetEdit" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>
</form>
<?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->


<?php
// $History: listUniversityContents.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 21/10/09   Time: 11:42
//Updated in $/LeapCC/Templates/University
//Done bug fixing.
//bug ids---
//00001796,00001794,00001786,00001630
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 15/10/09   Time: 13:31
//Updated in $/LeapCC/Templates/University
//Done bug fixing.
//Bug ids---
//00001787,00001788,00001789
//
//*****************  Version 3  *****************
//User: Administrator Date: 5/06/09    Time: 12:35
//Updated in $/LeapCC/Templates/University
//Done bug fixing.
//bug ids---1272 to 1281
//
//*****************  Version 2  *****************
//User: Administrator Date: 27/05/09   Time: 16:08
//Updated in $/LeapCC/Templates/University
//Corrected html code
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/University
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/University
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:48p
//Updated in $/Leap/Source/Templates/University
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:22p
//Updated in $/Leap/Source/Templates/University
//corrected breadcrumb and reset button height and width
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 8/14/08    Time: 5:51p
//Updated in $/Leap/Source/Templates/University
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 7/17/08    Time: 10:58a
//Updated in $/Leap/Source/Templates/University
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 7/09/08    Time: 1:52p
//Updated in $/Leap/Source/Templates/University
//
//*****************  Version 15  *****************
//User: Pushpender   Date: 7/08/08    Time: 5:58p
//Updated in $/Leap/Source/Templates/University
//made changes for state, city populate issue, file upload and fixed the
//width of columns
//
//*****************  Version 14  *****************
//User: Pushpender   Date: 7/07/08    Time: 8:18p
//Updated in $/Leap/Source/Templates/University
//changed name of Dialog div
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 6/30/08    Time: 7:42p
//Updated in $/Leap/Source/Templates/University
//Solved TabOrder Problem
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 6/30/08    Time: 5:40p
//Updated in $/Leap/Source/Templates/University
//Added JavaScript code for making selection of Country-->State-->City
//mandatory
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 6/30/08    Time: 4:35p
//Updated in $/Leap/Source/Templates/University
//Added AjaxListing and AjaxSearch Functionality
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 6/26/08    Time: 2:55p
//Updated in $/Leap/Source/Templates/University
//Added AjaxEnabled Delete Functionality
//
//*****************  Version 9  *****************
//User: Pushpender   Date: 6/23/08    Time: 5:25p
//Updated in $/Leap/Source/Templates/University
//added enctype="multipart/form-data" in edit form
//
//*****************  Version 8  *****************
//User: Pushpender   Date: 6/20/08    Time: 7:45p
//Updated in $/Leap/Source/Templates/University
//made changes to upload file
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 6/17/08    Time: 2:50p
//Updated in $/Leap/Source/Templates/University
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/17/08    Time: 10:52a
//Updated in $/Leap/Source/Templates/University
//Modifying html Done
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 6/16/08    Time: 7:23p
//Updated in $/Leap/Source/Templates/University
//added file upload code through iframe
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/16/08    Time: 9:53a
//Updated in $/Leap/Source/Templates/University
//Modifying functions Done
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/14/08    Time: 7:23p
//Updated in $/Leap/Source/Templates/University
//just reviewed
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:20p
//Updated in $/Leap/Source/Templates/University
//Modifying Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 7:31p
//Created in $/Leap/Source/Templates/University
//Initial Checkin
?>