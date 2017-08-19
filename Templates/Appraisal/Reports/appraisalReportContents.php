<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"></td></tr>
              <tr>
                                <td class="contenttab_row" colspan="2" valign="top" >
                                <form name="reportForm" id="reportForm" onsubmit="return false;">
                                <table border="0" cellpadding="0" cellspacing="0">
                                 <tr>
                                  <td class="contenttab_internal_rows"><b>Employee</b></td>
                                  <td class="padding">:</td>
                                  <td class="padding">
                                   <select name="employeeId" id="employeeId" class="inputbox" onchange="vanishData();">
                                    <option value="-1">All</option>
                                    <?php
                                     echo HtmlFunctions::getInstance()->getEmployeeData();
                                    ?>
                                   </select>
                                  </td>
                                  <!--
                                  <td class="contenttab_internal_rows"><b>Sort By</b></td>
                                  <td class="padding">:</td>
                                  <td class="padding">
                                   <select name="sortBy" id="sortBy" class="inputbox" style="width:150px;">
                                   <option value="employeeName">Employee</option>
                                   </select>
                                  </td>
                                  <td class="contenttab_internal_rows"><b>Sort Order</b></td>
                                  <td class="padding">:</td>
                                  <td class="padding">
                                   <select name="sortOrder" id="sortOrder" class="inputbox" style="width:80px;">
                                   <option value="ASC">ASC</option>
                                   <option value="DESC">DESC</option>
                                   </select>
                                  </td>
                                  -->
                                  <td>
                                   <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/showlist.gif" onClick="return showReport();" >&nbsp;
                                  </td>
                                </table>
                                </form>
                                <div id="results"></div>
                                </td>
             </tr>
             <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr id="printTrId" style="display:none">
                            <td class="content_title" valign="middle" align="right" width="20%">
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
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

<?php floatingDiv_Start('AddCity','Add City'); ?>
    <form name="AddCity" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>City Name<?php echo REQUIRED_FIELD; ?> : </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="cityName" name="cityName" class="inputbox" maxlength="50" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>City Code<?php echo REQUIRED_FIELD; ?> : </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="cityCode" name="cityCode" class="inputbox" maxlength="10" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>State Name<?php echo REQUIRED_FIELD; ?> : </b></nobr></td>
        <td width="79%" class="padding"><select size="1" class="selectfield" name="states" id="states">
        <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getStatesData($REQUEST_DATA['states']==''? $cityRecordArray[0]['stateId'] : $REQUEST_DATA['states'] );
              ?>
        </select>
    </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddCity');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditCity','Edit City '); ?>
<form name="EditCity" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="cityId" id="cityId" value="" />  
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>City Name<?php echo REQUIRED_FIELD; ?> : </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="cityName" name="cityName" class="inputbox" maxlength="50" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>City Code<?php echo REQUIRED_FIELD; ?> : </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="cityCode" name="cityCode" class="inputbox" maxlength="10" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>State Name<?php echo REQUIRED_FIELD; ?> : </b></nobr></td>
        <td width="79%" class="padding"><select size="1" class="selectfield" name="states" id="states">
        <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getStatesData($REQUEST_DATA['states']==''? $cityRecordArray[0]['stateId'] : $REQUEST_DATA['states'] );
              ?>
        </select>
    </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditCity');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->


<?php
// $History: listCityContents.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/17/10    Time: 3:37p
//Updated in $/LeapCC/Templates/City
//print & csv format added 
//
//*****************  Version 2  *****************
//User: Administrator Date: 4/06/09    Time: 15:22
//Updated in $/LeapCC/Templates/City
//Corrected bugs
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/City
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/City
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/City
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:47p
//Updated in $/Leap/Source/Templates/City
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:22p
//Updated in $/Leap/Source/Templates/City
//corrected breadcrumb and reset button height and width
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 8/14/08    Time: 5:50p
//Updated in $/Leap/Source/Templates/City
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 6/30/08    Time: 7:42p
//Updated in $/Leap/Source/Templates/City
//Solved TabOrder Problem
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 6/28/08    Time: 2:35p
//Updated in $/Leap/Source/Templates/City
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 6/28/08    Time: 12:57p
//Updated in $/Leap/Source/Templates/City
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 6/28/08    Time: 11:23a
//Updated in $/Leap/Source/Templates/City
//Added AjaxList Functionality
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 6/25/08    Time: 11:37a
//Updated in $/Leap/Source/Templates/City
//Added AjaxEnabled Delete Functionality
//Modified delete button
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/18/08    Time: 1:05p
//Updated in $/Leap/Source/Templates/City
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/18/08    Time: 11:52a
//Updated in $/Leap/Source/Templates/City
//adding constraints done
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/13/08    Time: 11:02a
//Updated in $/Leap/Source/Templates/City
//Modifying Comments Complete
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/12/08    Time: 7:58p
//Updated in $/Leap/Source/Templates/City
//Comment Insertion Complete
?>