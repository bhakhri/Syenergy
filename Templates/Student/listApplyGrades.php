<?php 
//--------------------------------------------------------
//This file creates Html Form output for marks not entered report
//
// Author :Ajinder Singh
// Created on : 23-oct-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td valign="top" class="title">
  <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");?>    
  <?php require_once(BL_PATH . "/messages.inc.php");?>    
</td>
</tr>
<tr>
<td valign="top">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
    <tr>
        <td valign="top" class="content">
            <!-- form table starts -->
            <form name="marksNotEnteredForm" action="" method="post" onSubmit="return false;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                    <tr>
                        <td valign="top" class="contenttab_row1">
                                <table align="center" border="0" cellpadding="0">
                                    <tr>
                                        <td valign="top" class="contenttab_internal_rows"><nobr><b>Time Table: </b></nobr></td>
                                        <td valign="top" class="">
                                        <select size="1" class="inputbox1" name="labelId" id="labelId" onBlur="getLabelClass()">
                                        <option value="">Select</option>
                                        <?php
                                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                          echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                                        ?>
                                        </select></td>
                                        <td valign="top" class="contenttab_internal_rows" colspan="2" align="left">
                                            <strong>Class :</strong>
                                        </td>
                                        <td valign="top" class="">
                                            <select class="htmlElement2" name="degreeId" id="degreeId" style="width:200px;" onBlur="getClassSubjects();">
                                            <option value="">Select</option>
                                            </select>
                                        </td>
                                        <td valign="top" class="contenttab_internal_rows" colspan="1" align="right">
                                            <strong>Subject :</strong> &nbsp;
                                        </td>
                                        <td valign="top" align="left" class="">
                                            <select size="1" class="htmlElement" style="width:80px;" name="subjectId" id="subjectId">
                                                <option value="">Select</option>
                                            </select>
                                        </td>
                                        <td valign="top" colspan="1" align="left" class="contenttab_internal_rows">
                                            <strong>Rounding :</strong>
                                        </td>
                                        <td valign="top" class="">
                                            <select name="gradingFormula"  class="htmlElement" id="gradingFormula"  onBlur="hideResults();"  style="width:80px;">
                                                <option value="">Select</option>
                                                <option value="ceil">Round Up</option>
                                                <option value="floor">Round Down</option>
                                                <option value="round">Round Off</option>
                                                <option value="round2">Round 2 Decimals</option> 
                                            </select>
                                        </td>
                                        <td align="center" colspan="4" class="" valign="top">
                                            <span style="padding-right:10px" >
                                            <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
                                        </td>
                                    </tr>
                                </table>
                        </td>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr id='graphRow' style='display:none;'>
                        <td colspan='2' class='contenttab_row'>
                            <div id = 'graphDiv' style='width:980px;overflow:auto;'></div>
                            <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/save_graph.gif" onClick="exportImage()" />
                        </td>
                    </tr>
                    <tr id='nameRow' style='display:none;'>
                        <td colspan="2" class="" height="20" >
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                <tr>
                                    <td colspan="1" class="content_title">Apply Grade :
             &nbsp;&nbsp;<span  style="color:#F10E0E;">Set Grades&nbsp;&nbsp;  Manual : <input type='radio' id='manual' name='choice' value='manual'  onclick='showChoiceDiv(this.id);' />&nbsp;&nbsp; Use Slider : <input type='radio' id='slider' name='choice' value='slider'  checked='checked'  onclick='showChoiceDiv(this.id);' /></span>
                                    </td>
                                    <td colspan="1" class="content_title" align="right"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                   <tr id='sliderRow1' style='display:none;'>
			<td colspan='1' class='contenttab_row'>
                           <div style="margin-top: 5px;" class="redLink"><table rules="all" style="border-collapse: collapse;" width="100%" align="center" bgcolor="#FFFF99" border="1" cellpadding="0" cellspacing="0">
			<tbody><tr>
			<td colspan="1" valign="top">
			<strong>&nbsp;Note:&nbsp;</strong><?php echo NO_INTERNAL_TOTAL_MARKS ;?></td></tr></tbody></table></div></td>			
		    </tr>	
                    <tr id='sliderRow' style='display:none;'>
                        <td colspan='1' class='contenttab_row'>
                            <div id = 'sliderDiv' style='display:none;'><?php echo $divTable;?></div>
                            <div id='manualDiv' style='height:250px;display:none;overflow:auto;'><?php echo $manualDivTable;?></div>
               <div id='divadiv'>
                           
                   <script language="JavaScript"><?php echo $myString;  echo $myString2;?></script>   
               
               </div>

                        </td>
                    </tr>
                    <tr id='resultRow' style='display:none;'>
                        <td colspan='1' class=''></td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>
