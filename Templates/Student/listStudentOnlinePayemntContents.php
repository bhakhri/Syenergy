<?php 
//-------------------------------------------------------
//  This File contains html form for all Student Internal Reappear Contents
//
//
// Author :PArveen Sharma
// Created on : 13-Sep-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
   global $sessionHandler; 
    $StudentNameFee = $sessionHandler->getSessionVariable('StudentName');
    $RollNoFee = $sessionHandler->getSessionVariable('RollNo');
    $FatherNameFee = $sessionHandler->getSessionVariable('FatherName');
    $ClassNameFee = $sessionHandler->getSessionVariable('ClassName');
	$ClassIdFee = $sessionHandler->getSessionVariable('ClassId');
?>

<form action="" method="POST" name="allDetailsForm" id="allDetailsForm" onSubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">Online Fee Payment</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
                    <td valign="top" class="content">
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                            <tr>  
                               <td class="contenttab_row" align="center" width="100%">
                                 <nobr><b>Online Fee Payment</b></nobr><br><br> 
                                 <table width="100%" border="0" cellspacing="0px" cellpadding="0px" class="" align="center"> 
                               
                                  <tr>
                                   <td  class="contenttab_internal_rows" width="9%" style="left-padding:20px;" nowrap><nobr><b>Name of Student</b></nobr></td>
                                   <td  class="padding" nowrap width="1%"><nobr><b>:</b></nobr></td>
                                   <td  class="contenttab_internal_rows" width="40%" nowrap><nobr>&nbsp;
                                   <?php echo $StudentNameFee; ?></nobr></td> 
                                   <td  class="contenttab_internal_rows" width="9%" nowrap><nobr><b>Father Name</b></nobr></td>
                                   <td  class="padding" nowrap width="1%"><nobr><b>:</b></nobr></td>
                                   <td  class="contenttab_internal_rows" width="40%" nowrap><nobr>&nbsp;
                                   <?php echo $FatherNameFee; ?></nobr></td> 
                                  </tr>
                                  <tr>
                                  	   <td  class="contenttab_internal_rows" width="9%" style="left-padding:20px;" nowrap><nobr><b>Institute Roll No.</b></nobr></td>
	                                   <td  class="padding" nowrap width="1%"><nobr><b>:</b></nobr></td>
	                                   <td  class="contenttab_internal_rows" width="40%" nowrap><nobr>&nbsp;
	                                   <?php echo $RollNoFee; ?></nobr></td> 
                                       <td  class="contenttab_internal_rows" nowrap><nobr><b>Univ. Roll No.</b></nobr></td>
                                       <td  class="padding" nowrap><nobr><b>:</b></nobr></td>
                                       <td  class="contenttab_internal_rows" nowrap><nobr>&nbsp;
                                       <?php echo $RollNoFee; ?></nobr></td> 
                                       
                                  </tr>
                                  <tr>
                                       <td  class="contenttab_internal_rows" nowrap><nobr><b>Current Class</b></nobr></td>
                                       <td  class="padding" nowrap><nobr><b>:</b></nobr></td>
                                       <td  class="contenttab_internal_rows" nowrap colspan="4"><nobr>&nbsp;
                                       <?php 
                                            echo $ClassNameFee;
                                       ?></nobr>
                                       </td> 
                                  </tr>
                             <tr>   
                                 <!-- <td valign="top" class="contenttab_row1" align="center" colspan="6" width="100%">
                                   <table border="0" cellspacing="0" cellpadding="0px" width="30%">
                                    <tr>    
                                    <td  class="contenttab_internal_rows" nowrap><nobr><b>
                                    Choose Class for Fee Payment<?php echo REQUIRED_FIELD ?></b></nobr></td>
                                    <td  class="padding" nowrap><nobr><b>:</b></nobr></td>           
                                    <td  class="contenttab_internal_rows" nowrap >  
                                    <select size="1" class="selectfield" name="classId" id="classId" onChange="hideResults(); return false;" style="width:250px">
                                      <option value="" selected="selected">Select</option>
                                        <?php
                                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                          
                                           echo HtmlFunctions::getInstance()->getStudentFeeClasses($condition);
                                        ?>
                                    </select>-->
                                    <td class="padding" colspan="6" align="center" nowrap><nobr>  
                                    &nbsp;&nbsp;
                                    <input type="image" name="reSubmit" value="reSubmit" src="<?php echo IMG_HTTP_PATH;?>/show.gif" onClick="return validateAddForm(); return false;" />  
                                    </nobr>
                                   
                               </td>     
                            </tr>
                            <tr id='nameRow' style="display:none;">
                                <td class="" height="20" colspan="6">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="3" class="content_title" align="left">Fee Due Details :</td>
                                            
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style="display:none;"  >
                                <td class='contenttab_row'  colspan="6">
                                	 <div id="scroll2" style="overflow:auto; width:1000px; height:350px; vertical-align:top;">
                                    <div id="divFeeAmountResult"></div>
                                   </div>
                                </td>
                            </tr>
                            <tr id='nameRow2' style="display:none;">
                                <td class="contenttab_row" height="20"  colspan="6">
                                  <table width="100%" border="0"  cellspacing="5px" cellpadding="5px" class="border" id='spanPayment2'>   
							        <tr>                
							           <td class="padding_top" width="100%" nowrap="nowrap" >
							             <nobr><b><font color="red" size="3px;">Terms & Conditions</font></b></nobr>                
							           </td>
							        </tr>
							        <tr>       
							        	<td class="padding_top" width="100%" align="justify">
							              You are being re-directed to a third party site. Please acknowledge the disclaimer before proceeding further. You are about to access a site, the accuracy or completeness of the materials or the reliability of any advice, opinion, statement or other information displayed or distributed through it, is not warranted by the university and shall be solely be construed to be set forth by the third party. 

You will access this site solely for the payment of university fee and other charges and you acknowledge that any reliance on any opinion, advice, statement, memorandum, or information available on the site shall be at your sole risk and consequences. The university expressly disclaims any liability for any deficiency in the services of the service provider whose site you are about to access. 

The University will not be liable to or have any responsibility of any kind for any loss that you incur in the event of any deficiency in the services of the service provider, failure or disruption of the site of the service provider, or resulting from the act or omission of any other party involved in making this site or the data contained therein available to you or from any other cause relating to your access to, inability to access, or use of the site or these materials in accordance thereto the university stand indemnified from all proceedings or matters arising thereto. 

It is the sole responsibility of the parent until the university fee paid by him/her is credited in to the university's Bank Account.                
							           </td>         
							        </tr>   							         
							       
							      </table>
                                </td>
                            </tr>
                        </table>
                        <!-- form table ends -->
                    </td>
                </tr>
            </table>
        </table>
    
</form>        
<script>	

</script>