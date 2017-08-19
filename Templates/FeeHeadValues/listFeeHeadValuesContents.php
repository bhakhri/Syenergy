<?php 
//This file creates Html Form output in Country Module 
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayFloatingDiv('AddFeeHeadValues','',650,250,screen.width/4,screen.height/5);blankValues();return false;" />&nbsp;</td></tr>
             <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
          </tr>
              <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
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
    
    
<?php floatingDiv_Start('AddFeeHeadValues','Add Fee Head Values'); ?>
<form name="addFeeHeadValues" action="" method="post"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
    <td  class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>Fee Cycle<?php echo REQUIRED_FIELD; ?></strong></nobr></td>
    <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
    <td  class="padding">
        <select size="1" class="selectfield" name="feeCycleId" id="feeCycleId">
            <option value="">Select </option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getFeeCycleData($REQUEST_DATA['feeCycleId']==''? $stateRecordArray[0]['feeCycleId'] : $REQUEST_DATA['feeCycleId'] );
            ?>
        </select>
    </td>
    <td  class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>Fee Head<?php echo REQUIRED_FIELD; ?></strong></nobr></td>
    <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
    <td  class="padding">
        <select size="1" class="selectfield" style='width:260px;' name="feeHeadId" id="feeHeadId">
            <option value="">Select </option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getFeeHeadData($REQUEST_DATA['feeHeadId']==''? $stateRecordArray[0]['feeHeadId'] : $REQUEST_DATA['feeHeadId'] );
            ?>
        </select>
    </td>
</tr>
<tr>
    <td class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>Fee Fund Allocation<?php echo REQUIRED_FIELD; ?></strong></nobr></td>
    <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>   
    <td class="padding">
        <select size="1" class="selectfield" name="feeFundAllocationId" id="feeFundAllocationId">
            <option value="">Select </option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getFeeFundAllocationData($REQUEST_DATA['feeFundAllocationId']==''? $stateRecordArray[0]['feeFundAllocationId'] : $REQUEST_DATA['feeFundAllocationId'] );
            ?>
        </select>
    </td>
    <td class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>Quota </strong></nobr></td>
    <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
    <td class="padding">
        <select size="1" class="selectfield" style='width:260px;' name="quotaId" id="quotaId">
        <option value="null">All</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                //echo HtmlFunctions::getInstance()->getCategoryClassData($REQUEST_DATA['quotaId']==''? $stateRecordArray[0]['quotaId'] : $REQUEST_DATA['quotaId'] );
                echo HtmlFunctions::getInstance()->getCurrentCategories($REQUEST_DATA['quotaId']==''? $stateRecordArray[0]['quotaId'] : $REQUEST_DATA['quotaId'],' WHERE parentQuotaId=0 ',$showParentCat='1');
            ?>
        </select>
    </td>
</tr>
<tr>
    <td class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>University </strong></nobr></td>
    <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>    
    <td class="padding">
      <select class="selectfield" name="universityId" id="universityId">
        <option value="null" selected="selected">All</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getUniversityData($REQUEST_DATA['universityId']==''? $stateRecordArray[0]['universityId'] : $REQUEST_DATA['universityId'] );
            ?>
      </select>
    </td>
    <td class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>Degree </strong></nobr></td>
    <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>    
    <td class="padding">
        <select size="1" class="selectfield" style='width:260px;' name="degreeId" id="degreeId">
            <option value="null">All</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getDegreeData($REQUEST_DATA['degreeId']==''? $stateRecordArray[0]['degreeId'] : $REQUEST_DATA['degreeId'] );
            ?>
        </select>
    </td>
</tr>
<tr>
    <td class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>Branch </strong></nobr></td>
    <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>   
    <td class="padding"><select size="1" class="selectfield" name="branchId" id="branchId">
    <option value="null">All</option>
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->getBranchData($REQUEST_DATA['branchId']==''? $stateRecordArray[0]['branchId'] : $REQUEST_DATA['branchId'] );
        ?>
        </select>
    </td>
    <td class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>Batch </strong></nobr></td>
    <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>      
    <td class="padding"><select size="1" class="selectfield" style='width:260px;' name="batchId" id="batchId">
        <option value="null">All</option>
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->getBatchData($REQUEST_DATA['batchId']==''? $stateRecordArray[0]['batchId'] : $REQUEST_DATA['batchId'] );
        ?>
        </select>
    </td>
</tr>
<tr>
        <td class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>Study Period </strong></nobr></td>
        <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>      
        <td class="padding">
            <select size="1" class="selectfield" name="studyPeriodId" id="studyPeriodId">
                <option value="null">All</option>
                <?php
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    echo HtmlFunctions::getInstance()->getStudyPeriod($REQUEST_DATA['studyPeriodId']==''? $stateRecordArray[0]['studyPeriodId'] : $REQUEST_DATA['studyPeriodId'] );
                ?>
            </select>
        </td>
        <td  class="contenttab_internal_rows" style='padding-left:5px'><nobr><b>Is Leet</b></nobr></td>
        <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td> 
        <td  class="padding">
            <input type="radio" id="isLeet" name="isLeet" value="1"/>Yes &nbsp;<input type="radio" id="isLeet" name="isLeet" value="0" checked/>No&nbsp;
            <input type="radio" id="isLeet" name="isLeet" value="2" checked/>Both
        </td>
</tr>
<tr>
        <td  class="contenttab_internal_rows" style='padding-left:5px'><nobr><b>Amount<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>     
        <td  class="padding"><input type="text" id="feeHeadAmount" name="feeHeadAmount" class="inputbox" maxlength="6"/></td>
</tr>
<tr>
<td height="5px"></td></tr>
<tr>
<td align="center" style="padding-right:10px" colspan="6">
    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
    <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddFeeHeadValues');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
</td>
</tr>
<tr>
<td height="5px"></td></tr>
<tr>
  
</table>
 </form>
<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('EditFeeHeadValues','Edit Fee Head Values'); ?>
   <form name="editFeeHeadValues" action="" method="post">   
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
  
     
    <input type="hidden" name="feeHeadValueId" id="feeHeadValueId" value="" />
        <tr>
    <td  class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>Fee Cycle<?php echo REQUIRED_FIELD; ?></strong></nobr></td>
    <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
    <td  class="padding">
        <select size="1" class="selectfield" name="feeCycleId" id="feeCycleId">
            <option value="">Select </option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getFeeCycleData($REQUEST_DATA['feeCycleId']==''? $stateRecordArray[0]['feeCycleId'] : $REQUEST_DATA['feeCycleId'] );
            ?>
        </select>
    </td>
    <td  class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>Fee Head<?php echo REQUIRED_FIELD; ?></strong></nobr></td>
    <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
    <td  class="padding">
        <select size="1" class="selectfield" style='width:260px;' name="feeHeadId" id="feeHeadId">
            <option value="">Select </option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getFeeHeadData($REQUEST_DATA['feeHeadId']==''? $stateRecordArray[0]['feeHeadId'] : $REQUEST_DATA['feeHeadId'] );
            ?>
        </select>
    </td>
</tr>
<tr>
    <td class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>Fee Fund Allocation<?php echo REQUIRED_FIELD; ?></strong></nobr></td>
    <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>   
    <td class="padding">
        <select size="1" class="selectfield" name="feeFundAllocationId" id="feeFundAllocationId">
            <option value="">Select </option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getFeeFundAllocationData($REQUEST_DATA['feeFundAllocationId']==''? $stateRecordArray[0]['feeFundAllocationId'] : $REQUEST_DATA['feeFundAllocationId'] );
            ?>
        </select>
    </td>
    <td class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>Quota </strong></nobr></td>
    <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
    <td class="padding">
        <select size="1" class="selectfield" style='width:260px;' name="quotaId" id="quotaId">
        <option value="null">All</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                //echo HtmlFunctions::getInstance()->getCategoryClassData($REQUEST_DATA['quotaId']==''? $stateRecordArray[0]['quotaId'] : $REQUEST_DATA['quotaId'] );
                echo HtmlFunctions::getInstance()->getCurrentCategories($REQUEST_DATA['quotaId']==''? $stateRecordArray[0]['quotaId'] : $REQUEST_DATA['quotaId'],' WHERE parentQuotaId=0 ',$showParentCat='1');
            ?>
        </select>
    </td>
</tr>
<tr>
    <td class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>University </strong></nobr></td>
    <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>    
    <td class="padding">
      <select class="selectfield" name="universityId" id="universityId">
        <option value="null" selected="selected">All</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getUniversityData($REQUEST_DATA['universityId']==''? $stateRecordArray[0]['universityId'] : $REQUEST_DATA['universityId'] );
            ?>
      </select>
    </td>
    <td class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>Degree </strong></nobr></td>
    <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>    
    <td class="padding">
        <select size="1" class="selectfield" style='width:260px;' name="degreeId" id="degreeId">
            <option value="null">All</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getDegreeData($REQUEST_DATA['degreeId']==''? $stateRecordArray[0]['degreeId'] : $REQUEST_DATA['degreeId'] );
            ?>
        </select>
    </td>
</tr>
<tr>
    <td class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>Branch </strong></nobr></td>
    <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>   
    <td class="padding"><select size="1" class="selectfield" name="branchId" id="branchId">
    <option value="null">All</option>
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->getBranchData($REQUEST_DATA['branchId']==''? $stateRecordArray[0]['branchId'] : $REQUEST_DATA['branchId'] );
        ?>
        </select>
    </td>
    <td class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>Batch </strong></nobr></td>
    <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>      
    <td class="padding"><select size="1" class="selectfield" style='width:260px;' name="batchId" id="batchId">
        <option value="null">All</option>
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->getBatchData($REQUEST_DATA['batchId']==''? $stateRecordArray[0]['batchId'] : $REQUEST_DATA['batchId'] );
        ?>
        </select>
    </td>
</tr>
<tr>
        <td class="contenttab_internal_rows" style='padding-left:5px'><nobr><strong>Study Period </strong></nobr></td>
        <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>      
        <td class="padding">
            <select size="1" class="selectfield" name="studyPeriodId" id="studyPeriodId">
                <option value="null">All</option>
                <?php
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    echo HtmlFunctions::getInstance()->getStudyPeriod($REQUEST_DATA['studyPeriodId']==''? $stateRecordArray[0]['studyPeriodId'] : $REQUEST_DATA['studyPeriodId'] );
                ?>
            </select>
        </td>
        <td  class="contenttab_internal_rows" style='padding-left:5px'><nobr><b>Is Leet</b></nobr></td>
        <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td> 
        <td  class="padding">
            <input type="radio" id="isLeet" name="isLeet" value="1"/>Yes &nbsp;<input type="radio" id="isLeet" name="isLeet" value="0" checked/>No&nbsp;
            <input type="radio" id="isLeet" name="isLeet" value="2" checked/>Both
        </td>
</tr>
<tr>
        <td  class="contenttab_internal_rows" style='padding-left:5px'><nobr><b>Amount<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td  class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>     
        <td  class="padding"><input type="text" id="feeHeadAmount" name="feeHeadAmount" class="inputbox" maxlength="6"/></td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="6">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
                    <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" 
                    onclick="javascript:hiddenFloatingDiv('EditFeeHeadValues');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>

</form>
<?php floatingDiv_End(); ?>
<?php 
//$History: listFeeHeadValuesContents.php $
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Templates/FeeHeadValues
//updated with all the fees enhancements
//
//*****************  Version 6  *****************
//User: Parveen      Date: 9/17/09    Time: 11:17a
//Updated in $/LeapCC/Templates/FeeHeadValues
//search condition & print & CSV report Generate
//
//*****************  Version 5  *****************
//User: Parveen      Date: 7/10/09    Time: 11:52a
//Updated in $/LeapCC/Templates/FeeHeadValues
//required parameter, search by condition, formatting updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/08/09    Time: 6:12p
//Updated in $/LeapCC/Templates/FeeHeadValues
//formatting & condition updated (issue fix 317, 316, 314, 312)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/01/09    Time: 5:38p
//Updated in $/LeapCC/Templates/FeeHeadValues
//quotawise & all condition update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/22/08   Time: 5:13p
//Updated in $/LeapCC/Templates/FeeHeadValues
//print sorting order set
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/FeeHeadValues
//
//*****************  Version 25  *****************
//User: Rajeev       Date: 11/15/08   Time: 3:08p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//change study period function
//
//*****************  Version 24  *****************
//User: Arvind       Date: 10/15/08   Time: 5:57p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//added print button
//
//*****************  Version 23  *****************
//User: Arvind       Date: 9/20/08    Time: 3:17p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//changed sortfield
//
//*****************  Version 21  *****************
//User: Arvind       Date: 9/05/08    Time: 5:47p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//removed unsortable class
//
//*****************  Version 20  *****************
//User: Arvind       Date: 9/01/08    Time: 7:29p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//added number_format() in feehead amount
//
//*****************  Version 18  *****************
//User: Arvind       Date: 9/01/08    Time: 2:51p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//added new fields in dispaly
//
//*****************  Version 16  *****************
//User: Arvind       Date: 8/27/08    Time: 12:58p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//html validated
//
//*****************  Version 15  *****************
//User: Arvind       Date: 8/26/08    Time: 12:42p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//increased the length of feeheadvalues
//
//*****************  Version 14  *****************
//User: Arvind       Date: 8/19/08    Time: 2:50p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//replaced search button
//
//*****************  Version 13  *****************
//User: Arvind       Date: 8/14/08    Time: 7:18p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//modified the bread crum
//
//*****************  Version 12  *****************
//User: Arvind       Date: 8/09/08    Time: 12:51p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//modified the colspan in paging
//
//*****************  Version 11  *****************
//User: Arvind       Date: 8/06/08    Time: 6:40p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//modify the width of fields in table
//
//*****************  Version 10  *****************
//User: Arvind       Date: 8/06/08    Time: 6:31p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//modify the width of the table
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/05/08    Time: 6:13p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//done align of fields in table
//
//*****************  Version 7  *****************
//User: Arvind       Date: 8/04/08    Time: 6:13p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//removed unneccesary code at bottom
//
//*****************  Version 6  *****************
//User: Arvind       Date: 8/01/08    Time: 7:32p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//addedmaxlength of amount
//
//*****************  Version 5  *****************
//User: Arvind       Date: 8/01/08    Time: 7:29p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//modified max length of amount
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/29/08    Time: 12:58p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//added NULL values as deafult values in DROPDOWN of add and edit div's
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/25/08    Time: 12:18p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//corrected the name of the field quotaId in edit div
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/21/08    Time: 5:15p
//Updated in $/Leap/Source/Templates/FeeHeadValues
//amodified width of add div
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/19/08    Time: 12:45p
//Created in $/Leap/Source/Templates/FeeHeadValues
//initial checkin

?>
