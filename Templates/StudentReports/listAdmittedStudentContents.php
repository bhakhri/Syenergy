<?php
//-------------------------------------------------------
// Purpose: to design the layout for SMS.
//
// Author : Parveen Sharma
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
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                            <tr>
                                <td valign="top" class="contenttab_row1">
                                    <form name="allDetailsForm" id="allDetailsForm" action="" method="post" onSubmit="return false;">
                                        <table align="left" border="0" cellspacing="0px" cellpadding="0" width="70%">  
                                             <tr>
                                               <td  width="5px" style="padding-left:10px" class="contenttab_internal_rows" valign="top"><nobr><b>Class<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                                               <td  width="2px" class="contenttab_internal_rows" valign="top"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                               <td  width="81px" class="" valign="top"><nobr>
                                                  <select name="classId" id="classId" style="width:280px" class="selectfield" >
                                                     <option value="">Select</option>
                                                     <?php
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getAdmitClassData();
                                                     ?>
                                                  </select>
                                                  </nobr>
                                               </td>
                                               <td width="5px" class="contenttab_internal_rows" style="padding-left:10px" valign="top"><nobr><b>Quota</b></nobr></td>
                                               <td width="2px" class="contenttab_internal_rows" valign="top"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                               <td width="5px" class="contenttab_internal_rows"><nobr>
                                                    <select multiple name='quotaId[]' id='quotaId' size='5' class='inputbox1' style='width:350px'>
                                                    <?php
                                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                      echo HtmlFunctions::getInstance()->getCurrentCategories($configsRecordArray[$i]['value'],' WHERE parentQuotaId=0 ',$showParentCat='1');
                                                    ?>
                                                    </select><br>
                                                    <div align="left">
                                                    Select &nbsp;
                                                    <a class="allReportLink" href="javascript:makeSelection('quotaId[]','All','allDetailsForm');">All</a> / 
                                                    <a class="allReportLink" href="javascript:makeSelection('quotaId[]','None','allDetailsForm');">None</a>
                                                    </div></nobr>
                                               </td>  
                                                <td style="padding-left:20px" valign="top">
                                                  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form); return false;"/>
                                                </td>
                                             </tr>
                                        </table>
                                    </form>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title">Admitted Student Details :</td>
                                            <td colspan="2" class="content_title" align="right">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();return false;"/> 
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td colspan='1' class='contenttab_row'>
                                    <div id = 'resultsDiv'></div>
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();return false;"/> 
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
