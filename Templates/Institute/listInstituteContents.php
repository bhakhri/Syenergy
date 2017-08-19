<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR INSTITUTE LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Administrative Masters&nbsp;&raquo;&nbsp;Institute Master</td>
                <td valign="top" align="right">
                <form action="" method="" name="searchForm">
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                 <input type="image"  name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search"   style="margin-bottom: -5px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>&nbsp;
                  </form>
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
                        <td class="content_title">Institute Detail : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayFloatingDiv('AddInstituteDiv','',650,250,200,100);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <div id="results">
                </div>           
             </td>
          </tr>
          <tr><td height="10px"></td></tr>
          <tr>
           <td align="right">
             <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printReport()">
             &nbsp;<INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="javascript:printCSV();">
          </td></tr>
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
    <!--Start Add Div-->

<?php floatingDiv_Start('AddInstituteDiv','Add Institute'); ?>
<form name="AddInstitute" id="AddInstitute" action="<?php echo HTTP_LIB_PATH;?>/Institute/fileUpload.php" method="post" enctype="multipart/form-data" style="display:inline">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
 <td valign="top">
 <table width="50%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Institute Name<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="instituteName" name="instituteName" class="inputbox" maxlength="250" tabindex="1" /></td>
    </tr>
    
    <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr><b>Abbr.<?php echo REQUIRED_FIELD;?></b></nobr></td>
       <td class="padding">:</td>
        <td class="padding">
       <input type="text" id="instituteAbbr" name="instituteAbbr" class="inputbox" maxlength="10" tabindex="3"/></td>
    </tr>
   
    <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Address1<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding" valign="top">:</td>
        <td class="padding">
         <textarea id="instituteAddress1" name="instituteAddress1" class="inputbox" cols="16" rows="5"  maxlength="255" onkeyup="return ismaxlength(this)" tabindex="5" /></textarea>
        </td>
    </tr>
   
    <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr><b>Website<?php echo REQUIRED_FIELD;?></b></nobr></td>
       <td class="padding">:</td>
        <td class="padding"><input type="text" id="instituteWebsite" name="instituteWebsite" class="inputbox" maxlength="50" tabindex="7" /></td>
    </tr>
    <tr>
    <!--Cannot understand its meaning-->
       <td width="21%" class="contenttab_internal_rows"><nobr><b>Contact Person</b></nobr></td>
       <td class="padding">:</td>
        <td class="padding">
       <select size="1" class="selectfield" name="employee" id="employee" disabled="true" tabindex="8">
       <option value="NULL" selected="true">SELECT</option>
              <?php
                  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  //echo HtmlFunctions::getInstance()->getCitiesData($REQUEST_DATA['city']==''? $cityRecordArray[0]['cityId'] : REQUEST_DATA['city'] );
              ?>
        </select></td>
    </tr>
   <tr>
      <td width="21%" class="contenttab_internal_rows"><nobr><b>Designation<?php echo REQUIRED_FIELD;?></b></nobr></td>
       <td class="padding">:</td>
        <td class="padding">
       <select size="1" class="selectfield" name="designationId" id="designationId" tabindex="8">
       <option value="" selected="true">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getDesignationData();
              ?>
        </select></td>
    </tr>
   <tr>
      <td width="21%" class="contenttab_internal_rows"><nobr><b>State<?php echo REQUIRED_FIELD;?></b></nobr></td>
       <td class="padding">:</td>
        <td class="padding">
        <select size="1" class="selectfield" name="states" id="states" onChange="autoPopulate(this.value,'city','Add');" tabindex="12">
         <option value="">SELECT</option>
              <?php
                 // require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  //echo HtmlFunctions::getInstance()->getStatesData($REQUEST_DATA['states']==''? $instituteRecordArray[0]['stateId'] : $REQUEST_DATA['states'] );
              ?>
        </select>
       </td>
    </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>PIN<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="pin" name="pin" class="inputbox" maxlength="10" tabindex="14" /></td>
    </tr>
   </table>
 </td>
 <td valign="top">
  <table width="50%" border="0" cellspacing="0" cellpadding="0">
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Institute Code<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="instituteCode" name="instituteCode" class="inputbox" maxlength="10" tabindex="2" /></td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Email<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="instituteEmail" name="instituteEmail" class="inputbox" maxlength="50" tabindex="4" /></td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Address2<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding" valign="top">:</td>
        <td class="padding">
         <textarea id="instituteAddress2" name="instituteAddress2" class="inputbox" cols="16" rows="5" maxlength="255" onkeyup="return ismaxlength(this)" tabindex="6" /></textarea>
        </td>
  </tr>

  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Contact Phone<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="employeePhone" name="employeePhone" class="inputbox" maxlength="20" tabindex="9" /></td>
   </tr>
   
   <tr>            
      <td width="21%" class="contenttab_internal_rows"><nobr><b>Country<?php echo REQUIRED_FIELD;?></b></nobr></td>
       <td class="padding">:</td>
        <td class="padding">
       <select size="1" class="selectfield" name="country" id="country" onChange="autoPopulate(this.value,'states','Add');" tabindex="11">
        <option value="" selected="selected">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getCountriesData($REQUEST_DATA['country']==''? $instituteRecordArray[0]['countryId'] : $REQUEST_DATA['country'] );
              ?>
        </select>
       </td>
    </tr>
   
   <tr>
      <td width="21%" class="contenttab_internal_rows"><nobr><b>City<?php echo REQUIRED_FIELD;?></b></nobr></td>
       <td class="padding">:</td>
        <td class="padding">
       <select size="1" class="selectfield" name="city" id="city" tabindex="13">
       <option value="">SELECT</option>
              <?php
                  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  //echo HtmlFunctions::getInstance()->getCityData($REQUEST_DATA['city']==''? $instituteRecordArray[0]['cityId'] : $REQUEST_DATA['city'] );
              ?>
        </select>
       </td>
    </tr>
    <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr><b>Logo</b></nobr></td>
       <td class="padding">:</td>
        <td class="padding">
        <input type="file" id="instituteLogo" name="instituteLogo" class="inputbox" tabindex="15">
       </td>
    </tr>
   </table>
</td>
</tr>
    <tr colspan="2">
        <td height="5px"></td></tr>
    <tr>

<tr colspan="2">
    <td align="center" style="padding-right:10px" colspan="3">
      <input type="image" name="imageAdd" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');" tabindex="16"/>
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddInstituteDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" tabindex="17" />
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
<?php floatingDiv_Start('EditInstituteDiv','Edit Institute '); ?>
<form name="EditInstitute" id="EditInstitute" action="<?php echo HTTP_LIB_PATH;?>/Institute/fileUpload.php" method="post" enctype="multipart/form-data" style="display:inline">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<input type="hidden" name="instituteId" id="instituteId" value="" />
<tr>
 <td valign="top">
 <table width="50%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Institute Name<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="instituteName" name="instituteName" class="inputbox" maxlength="250" tabindex="1" /></td>
    </tr>
    
    <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr><b>Abbr.<?php echo REQUIRED_FIELD;?></b></nobr></td>
       <td class="padding">:</td>
        <td class="padding"><input type="text" id="instituteAbbr" name="instituteAbbr" class="inputbox" maxlength="10" tabindex="3" /></td>
    </tr>
   
    <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Address1<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding" valign="top">:</td>
        <td class="padding">
         <textarea id="instituteAddress1" name="instituteAddress1" class="inputbox" cols="16" rows="5" maxlength="255" onkeyup="return ismaxlength(this)" tabindex="5" /></textarea>
        </td>
    </tr>
   
    <tr>
       <td width="21%" class="contenttab_internal_rows"><nobr><b>Website<?php echo REQUIRED_FIELD;?></b></nobr></td>
       <td class="padding">:</td>
        <td class="padding"><input type="text" id="instituteWebsite" name="instituteWebsite" class="inputbox" maxlength="50" tabindex="7" /></td>
    </tr>
    <tr>
    <!--Cannot understand its meaning-->
       <td width="21%" class="contenttab_internal_rows"><nobr><b>Contact Person<?php echo REQUIRED_FIELD;?></b></nobr></td>
       <td class="padding">:</td>
        <td class="padding"><select size="1" class="selectfield" name="employeeId" id="employeeId" tabindex="8">
        <option value="" selected="selected">SELECT</option>
              <?php
                  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                 // echo HtmlFunctions::getInstance()->getEmployeeData();
              ?>
        </select></td>
    </tr>
   <tr>
      <td width="21%" class="contenttab_internal_rows"><nobr><b>Designation<?php echo REQUIRED_FIELD;?></b></nobr></td>
       <td class="padding">:</td>
        <td class="padding"><select size="1" class="selectfield" name="designationId" id="designationId" tabindex="10">
        <option value="" selected="selected">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getDesignationData($REQUEST_DATA['designationId']==''? $instituteRecordArray[0]['designationId'] : $REQUEST_DATA['designationId'] );
              ?>
        </select></td>
    </tr>
   <tr>
      <td width="21%" class="contenttab_internal_rows"><nobr><b>State<?php echo REQUIRED_FIELD;?></b></nobr></td>
       <td class="padding">:</td>
        <td class="padding">
       <select size="1" class="selectfield" name="states" id="states" onChange="autoPopulate(this.value,'city','Edit');" tabindex="12">
       <option value="" selected="selected">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getStatesData($REQUEST_DATA['states']==''? $instituteRecordArray[0]['stateId'] : $REQUEST_DATA['states'] );
              ?>
        </select></td>
    </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>PIN<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="pin" name="pin" class="inputbox" maxlength="10" tabindex="14" /></td>
    </tr>
   </table>
 </td>
 <td valign="top">
  <table width="50%" border="0" cellspacing="0" cellpadding="0">
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Institute Code<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="instituteCode" name="instituteCode" class="inputbox" maxlength="10" tabindex="2" /></td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Email<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="instituteEmail" name="instituteEmail" class="inputbox" maxlength="50" tabindex="4" /></td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Address2<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding" valign="top">:</td>
        <td class="padding">
         <textarea id="instituteAddress2" name="instituteAddress2" class="inputbox" cols="16" rows="5" maxlength="255" onkeyup="return ismaxlength(this)" tabindex="6" /></textarea>
        </td>
  </tr>
  <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Contact Phone<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="employeePhone" name="employeePhone" class="inputbox" maxlength="20" tabindex="9" /></td>
   </tr>
   
   <tr>
   <tr>
      <td width="21%" class="contenttab_internal_rows"><nobr><b>Country<?php echo REQUIRED_FIELD;?></b></nobr></td>
       <td class="padding">:</td>
        <td class="padding">
       <select size="1" class="selectfield" name="country" id="country" onChange="autoPopulate(this.value,'states','Edit');" tabindex="11">
       <option value="">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getCountriesData($REQUEST_DATA['country']==''? $instituteRecordArray[0]['countryId'] : $REQUEST_DATA['country'] );
              ?>
        </select>
        </td>
    </tr>
   
   <tr>
      <td width="21%" class="contenttab_internal_rows"><nobr><b>City<?php echo REQUIRED_FIELD;?></b></nobr></td>
       <td class="padding">:</td>
        <td class="padding">
       <select size="1" class="selectfield" name="city" id="city" tabindex="13">
       <option value="">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getCityData($REQUEST_DATA['city']==''? $instituteRecordArray[0]['cityId'] : $REQUEST_DATA['city'] );
              ?>
        </select></td>
    </tr>
    <tr>
       <td width="21%" class="contenttab_internal_rows" valign="top" style="padding-top:5px"><nobr><b>Logo</b></nobr></td>
       <td class="padding" valign="top" style="padding-top:5px">:</td>
        <td class="padding">
        <input type="file" id="instituteLogo" name="instituteLogo" class="inputbox" tabindex="15" >
        <div id="editLogoPlace" class="cl" style="padding-top:5px"></div>
       </td>
    </tr>
   </table>
</td>
</tr>
    <tr colspan="2">
        <td height="5px"></td></tr>
    <tr>

<tr colspan="2">
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageEdit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');" tabindex="16" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onClick="javascript:hiddenFloatingDiv('EditInstituteDiv');return false;" tabindex="17" />
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
// $History: listInstituteContents.php $
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 21/10/09   Time: 11:42
//Updated in $/LeapCC/Templates/Institute
//Done bug fixing.
//bug ids---
//00001796,00001794,00001786,00001630
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 1/09/09    Time: 11:21
//Updated in $/LeapCC/Templates/Institute
//Done bug fixing.
//Bug ids---
//00001351,00001353,00001354,00001355,
//00001369,00001370,00001371
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/27/09    Time: 3:42p
//Updated in $/LeapCC/Templates/Institute
//Gurkeerat: resolved issue 1287
//
//*****************  Version 4  *****************
//User: Administrator Date: 8/06/09    Time: 14:13
//Updated in $/LeapCC/Templates/Institute
//Done bug fixing.
//bug ids---> 1318 to 1329 ,Leap bugs4.doc(5 to 10,12,20)
//
//*****************  Version 3  *****************
//User: Administrator Date: 27/05/09   Time: 18:15
//Updated in $/LeapCC/Templates/Institute
//Added a space between Institute Name and Institute Code
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 26/05/09   Time: 15:45
//Updated in $/LeapCC/Templates/Institute
//Fixed bugs-----Issues [26-May-09]1
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Institute
//
//*****************  Version 21  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Institute
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 20  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:48p
//Updated in $/Leap/Source/Templates/Institute
//
//*****************  Version 19  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:22p
//Updated in $/Leap/Source/Templates/Institute
//corrected breadcrumb and reset button height and width
//
//*****************  Version 18  *****************
//User: Dipanjan     Date: 8/14/08    Time: 5:53p
//Updated in $/Leap/Source/Templates/Institute
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 7/19/08    Time: 1:09p
//Updated in $/Leap/Source/Templates/Institute
//Corrected Javscript Error during addition
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 7/16/08    Time: 8:21p
//Updated in $/Leap/Source/Templates/Institute
//
//*****************  Version 15  *****************
//User: Pushpender   Date: 7/08/08    Time: 5:58p
//Updated in $/Leap/Source/Templates/Institute
//made changes for state, city populate issue, file upload and fixed the
//width of columns
//
//*****************  Version 14  *****************
//User: Pushpender   Date: 7/07/08    Time: 8:18p
//Updated in $/Leap/Source/Templates/Institute
//changed name of Dialog div
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 6/30/08    Time: 7:42p
//Updated in $/Leap/Source/Templates/Institute
//Solved TabOrder Problem
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 6/30/08    Time: 5:40p
//Updated in $/Leap/Source/Templates/Institute
//Added JavaScript code for making selection of Country-->State-->City
//mandatory
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 6/30/08    Time: 4:35p
//Updated in $/Leap/Source/Templates/Institute
//Added AjaxListing and AjaxSearch Functionality
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 6/26/08    Time: 2:55p
//Updated in $/Leap/Source/Templates/Institute
//Added AjaxEnabled Delete Functionality
//
//*****************  Version 9  *****************
//User: Pushpender   Date: 6/23/08    Time: 5:25p
//Updated in $/Leap/Source/Templates/Institute
//added enctype="multipart/form-data" in edit form
//
//*****************  Version 8  *****************
//User: Pushpender   Date: 6/20/08    Time: 7:45p
//Updated in $/Leap/Source/Templates/Institute
//made changes to upload file
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 6/17/08    Time: 2:50p
//Updated in $/Leap/Source/Templates/Institute
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/17/08    Time: 10:52a
//Updated in $/Leap/Source/Templates/Institute
//Modifying html Done
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 6/16/08    Time: 7:23p
//Updated in $/Leap/Source/Templates/Institute
//added file upload code through iframe
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/16/08    Time: 9:53a
//Updated in $/Leap/Source/Templates/Institute
//Modifying functions Done
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/14/08    Time: 7:23p
//Updated in $/Leap/Source/Templates/Institute
//just reviewed
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:20p
//Updated in $/Leap/Source/Templates/Institute
//Modifying Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 7:31p
//Created in $/Leap/Source/Templates/Institute
//Initial Checkin
?>