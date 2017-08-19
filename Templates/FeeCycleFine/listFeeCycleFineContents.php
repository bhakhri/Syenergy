<?php 
//
//This file creates Html Form output in "feeCycleFine" Module 
//
// Author :Arvind Singh Rawat
// Created on : 1-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddFeeCycleFine',350,200);blankValues();return false" />&nbsp;</td></tr>
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
	
<?php floatingDiv_Start('AddFeeCycleFine','Add Fee Cycle Fine'); ?>
<form name="addFeeCycleFine" action="" method="post"> 
<table width="100%" border="0" cellspacing="5px" cellpadding="0px" class="border">
	  <tr>
		  <td class="contenttab_internal_rows"><nobr><strong>Fee Cycle<?php echo REQUIRED_FIELD ?></strong></nobr></td>
          <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></td>
		  <td class="padding"><nobr>
            <select size="1" class="selectfield" name="feeCycleId"  style='width:200px' id="feeCycleId">
              <option value="">Select </option>
			  <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getFeeCycleData($REQUEST_DATA['feeCycleId']==''? $stateRecordArray[0]['feeCycleId'] : $REQUEST_DATA['feeCycleId'] );
              ?>
              </select></nobr>
        </td>
     </tr>
	 <!--tr>
		  <td class="contenttab_internal_rows"><nobr><strong>Fee Head<?php echo REQUIRED_FIELD ?></strong></nobr></td>
          <td class="padding">:</td>
		  <td class="padding"><select size="1" class="selectfield" name="feeHeadId" id="feeHeadId">
              <option value="">Select </option>
			  <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  //echo HtmlFunctions::getInstance()->getFeeHeadNameData($REQUEST_DATA['feeHeadId']==''? $stateRecordArray[0]['feeHeadId'] : $REQUEST_DATA['feeHeadId'] );
                  echo HtmlFunctions::getInstance()->getFeeHeadData();
              ?>
              </select>
          </td>
	</tr>-->
    <tr>
		  <td class="contenttab_internal_rows"><nobr><strong>From<?php echo REQUIRED_FIELD ?></strong></nobr></td>
          <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></td>
		  <td class="padding" >
		    <?php
              require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->datePicker('fromDate',date('y')."-".date('m')."-".date('d'));
            ?>	  
		  </td>
    </tr>
	<tr>
		  <td class="contenttab_internal_rows"><nobr><strong>To<?php echo REQUIRED_FIELD ?></strong></nobr></td>
          <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></td>
		  <td class="padding">
		  <?php
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
             echo HtmlFunctions::getInstance()->datePicker('toDate',date('y')."-".date('m')."-".date('d'));
           ?>
		  </td>
	  </tr>
	  <tr>
		  <td class="contenttab_internal_rows"><nobr><strong>Fine Amount<?php echo REQUIRED_FIELD ?></strong></nobr></td>
          <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></td>
		  <td class="padding"><nobr>
            <input type="text" name="fineAmount" id="fineAmount" class="inputbox" maxlength="6"  style='width:196px'/>
            </nobr>
		  </td>
	</tr>
	<tr>
		  <td class="contenttab_internal_rows"><nobr><strong>Fine Type<?php echo REQUIRED_FIELD ?></strong></nobr></td>
          <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></td>
		  <td class="padding"><nobr>
		   <select size="1" class="selectfield" name="fineType"   style='width:200px'  id="fineType">
           <option value=''>Select</option>
		   <?php
 				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				 echo HtmlFunctions::getInstance()->getFineType('1');
			?>
		   </select></nobr>
		  </td>
	</tr>
    <tr>
        <td height="5px"></td>
    </tr>
    <tr>
    <td align="center" style="padding-right:10px" colspan="3">
     <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
     <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddFeeCycleFine');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
     </td>
</tr>
</table> 
</form>

<?php floatingDiv_End(); ?>



<?php floatingDiv_Start('EditFeeCycleFine','Edit Fee Cycle Fine'); ?>
     <form name="editFeeCycleFine" action="" method="post">   
     <input type="hidden" name="feeCycleFineId" id="feeCycleFineId" value="" />     
<table width="100%" border="0" cellspacing="5px" cellpadding="0px" class="border">
      <tr>
          <td class="contenttab_internal_rows"><nobr><strong>Fee Cycle<?php echo REQUIRED_FIELD ?></strong></nobr></td>
          <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></td>
          <td class="padding"><nobr>
            <select size="1" class="selectfield" name="feeCycleId" id="feeCycleId" style='width:200px' >
              <option value="">Select </option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getFeeCycleData($REQUEST_DATA['feeCycleId']==''? $stateRecordArray[0]['feeCycleId'] : $REQUEST_DATA['feeCycleId'] );
              ?>
              </select></nobr>
        </td>
     </tr>
     <!--tr>
          <td class="contenttab_internal_rows"><nobr><strong>Fee Head<?php echo REQUIRED_FIELD ?></strong></nobr></td>
          <td class="padding">:</td>
          <td class="padding"><select size="1" class="selectfield" name="feeHeadId" id="feeHeadId">
              <option value="">Select </option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  //echo HtmlFunctions::getInstance()->getFeeHeadData($REQUEST_DATA['feeHeadId']==''? $stateRecordArray[0]['feeHeadId'] : $REQUEST_DATA['feeHeadId'] );
                  echo HtmlFunctions::getInstance()->getFeeHeadData();
              ?>
              </select>
          </td>
    </tr> -->
    <tr>
          <td class="contenttab_internal_rows"><nobr><strong>From<?php echo REQUIRED_FIELD ?></strong></nobr></td>
          <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></td>
          <td class="padding" >
            <?php
              require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->datePicker('fromDate1',date('y')."-".date('m')."-".date('d'));
            ?>      
          </td>
    </tr>
    <tr>
          <td class="contenttab_internal_rows"><nobr><strong>To<?php echo REQUIRED_FIELD ?></strong></nobr></td>
          <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></td>
          <td class="padding">
          <?php
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
             echo HtmlFunctions::getInstance()->datePicker('toDate1',date('y')."-".date('m')."-".date('d'));
           ?>
          </td>
      </tr>
      <tr>
          <td class="contenttab_internal_rows"><nobr><strong>Fine Amount<?php echo REQUIRED_FIELD ?></strong></nobr></td>
          <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></td>
          <td class="padding"><nobr>
            <input type="text" name="fineAmount" id="fineAmount" class="selectfield" maxlength="6" style='width:196px' />
            </nobr>
          </td>
    </tr>
    <tr>
          <td class="contenttab_internal_rows"><nobr><strong>Fine Type<?php echo REQUIRED_FIELD ?></strong></nobr></td>
          <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></td>
          <td class="padding"><nobr>
           <select size="1" class="selectfield" name="fineType" id="fineType" style='width:200px'>
           <option value=''>Select</option>
           <?php
                 require_once(BL_PATH.'/HtmlFunctions.inc.php');
                 echo HtmlFunctions::getInstance()->getFineType('1');
            ?>
           </select></nobr>
          </td>
    </tr>
    <tr>
        <td height="5px"></td>
    </tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
        <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditFeeCycleFine');return false;" />
    </td>
</tr>
 </table>
</form>
    <?php floatingDiv_End(); ?>
  
<?php 
//$History: listFeeCycleFineContents.php $
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Templates/FeeCycleFine
//updated with all the fees enhancements
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/17/09    Time: 11:17a
//Updated in $/LeapCC/Templates/FeeCycleFine
//search condition & print & CSV report Generate
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/03/09    Time: 1:48p
//Updated in $/LeapCC/Templates/FeeCycleFine
//Gurkeerat: resolved issue 1388
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/10/09    Time: 10:22a
//Updated in $/LeapCC/Templates/FeeCycleFine
//print & excel button alignment update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/08/09    Time: 5:30p
//Updated in $/LeapCC/Templates/FeeCycleFine
//bug fix 505, 504, 503, 968, 961, 960, 959, 958, 957, 956, 955, 954,
//953, 952,
//951, 723, 722, 797, 798, 799, 916, 935, 936, 937, 938, 939, 940, 944
//(alignment, condition & formatting updated)
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/FeeCycleFine
//
//*****************  Version 20  *****************
//User: Arvind       Date: 10/24/08   Time: 2:10p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//added print function
//
//*****************  Version 19  *****************
//User: Arvind       Date: 9/10/08    Time: 4:26p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//added comon date function
//
//*****************  Version 18  *****************
//User: Arvind       Date: 9/05/08    Time: 5:47p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//removed unsortable class
//
//*****************  Version 17  *****************
//User: Arvind       Date: 8/27/08    Time: 12:50p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//html validated
//
//*****************  Version 16  *****************
//User: Arvind       Date: 8/19/08    Time: 2:48p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//replaced search button
//
//*****************  Version 15  *****************
//User: Arvind       Date: 8/14/08    Time: 7:18p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//modified the bread crum
//
//*****************  Version 14  *****************
//User: Arvind       Date: 8/14/08    Time: 7:02p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//modified the bread crum
//
//*****************  Version 13  *****************
//User: Arvind       Date: 8/06/08    Time: 6:43p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//modify the width of fields in table
//
//*****************  Version 12  *****************
//User: Arvind       Date: 8/04/08    Time: 6:14p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//removed unneccesary code at bottom
//
//*****************  Version 11  *****************
//User: Arvind       Date: 8/01/08    Time: 7:34p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//modified max length of fine amount
//
//*****************  Version 10  *****************
//User: Arvind       Date: 8/01/08    Time: 6:21p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//added maxlength in fineamount
//
//*****************  Version 9  *****************
//User: Arvind       Date: 7/29/08    Time: 7:31p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//added condition for fineType
//
//*****************  Version 8  *****************
//User: Arvind       Date: 7/29/08    Time: 6:52p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//modified fine type dropdown
//
//*****************  Version 7  *****************
//User: Arvind       Date: 7/29/08    Time: 4:51p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//modified the sorting parameters
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/29/08    Time: 1:24p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//added the spaces in fields and sorting image
//
//*****************  Version 5  *****************
//User: Arvind       Date: 7/12/08    Time: 11:03a
//Updated in $/Leap/Source/Templates/FeeCycleFine
//added a common function for date
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/05/08    Time: 5:10p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//added column span inrow displaying " row not found "
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/05/08    Time: 3:59p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//added select option in dropdowns
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/03/08    Time: 3:50p
//Updated in $/Leap/Source/Templates/FeeCycleFine
//modification breadcrum
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:38a
//Created in $/Leap/Source/Templates/FeeCycleFine
//Added a new content  file for " FeeCycleFine " Module
//


?>
