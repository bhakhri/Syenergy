<?php
//-------------------------------------------------------
// Purpose: to design the layout for Display student Grades
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form action="" method="POST" name="listForm" id="listForm"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
 <td><?php require_once(TEMPLATES_PATH."/breadCrumb.php");?></td>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
               <td valign="top" colspan="2"> </td>  
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
                        <td class="contenttab_border2" colspan="2" id="enterRollNo" style="display:none;"> 
                            <table width="20" border="0" cellspacing="0" cellpadding="0" align="center">
                              <tr><td height="10"></td></tr>  
                              <tr><a id="lk1"  class="set_default_values">Set Default Values for Report Parameters</a>
                                <td class="contenttab_internal_rows" align="left">
                                    <nobr><strong>Univ. Roll No./Roll No. :</strong> &nbsp;</nobr>
                                </td>
                                <td class="contenttab_internal_rows" align="left">
                                    <input class="inputbox" name="rollNo" id="rollNo" value="" style="width:180px" type="text">
                                   </<nobr> 
                                </td>
                                <td style="padding-left:7px" class="contenttab_internal_rows" align="left" nowrap>
                                    <nobr><strong>Include Print Header :</strong> &nbsp;</nobr>
                                </td>
                                <td class="contenttab_internal_rows" align="left" nowrap>   
                                   <input name="printHeader" id="printHeader1" checked="checked" type="radio">No&nbsp;
                                   <input name="printHeader" id="printHeader2" type="radio">Yes
                                </td>
                                <td style="padding-left:7px" class="contenttab_internal_rows" align="left" nowrap>
                                    <nobr><strong>Total Study Period to Print :</strong> &nbsp;</nobr>
                                </td>
                                <td class="contenttab_internal_rows" align="left" nowrap>   
                                   <input style="width:80px" class="inputbox" name="txtTotal" id="txtTotal" type="text" value="2">
                                </td>
                                <td  align="left" style="padding-left:20px"><nobr>
                                  <input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="validateAddForm(); return false;"/></td>
                                </<nobr>
                            </tr>
                            <tr><td height="10"></td></tr>  
                            </table>
                        </td>
                    </tr>
                    <tr style="display:none" id="showTitle">
                        <td class="contenttab_border" height="20" colspan="2">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                            <tr>
                                <td class="content_title">Display Student Grade Detail(s) : </td>
                                <td colspan='1' align='right' valign="middle"   id = 'saveDiv' style='display:none;'>
                                  <!--  
                                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
                                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();return false;"/>
                                  -->  
                                </td>
                            </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="display:none" id="showFName">
                        <td class="contenttab_row" valign="top" align="left" colspan="2"><nobr><br>
                            <b>Student Name&nbsp;:&nbsp;</b><span id="StudentName"></span><span style="padding-left:15px">
                            <b>Father's Name&nbsp;:&nbsp;</b><span id="FatherName"></span></span><br>
                            <input type="hidden" name="studentId" id="studentId" readonly="readonly"></nobr>
                        </td>
                    </tr>
                    <tr style="display:none" id="showData">
                        <td class="contenttab_row" valign="top" colspan="2">
                            <div id="scroll" style="OVERFLOW: auto; HEIGHT:254px; WIDTH:1000px; TEXT-ALIGN: justify;padding-right:10px" class="scroll">
                                <div id="results">  
                            </div> 
                            </div>
                        </td>
                     </tr>
                     <tr style="display:none" id="showCGPA">
                        <td class="contenttab_internal_rows" valign="top" style="padding-right:10px;text-align:right;" colspan="2">
                            <b>Current CGPA&nbsp;:&nbsp;</b><span id="CurrentCGPA"></span>
                        </td>
                     </tr>
                     <tr>
                        <td height="10" colspan="2"></td>
                     </tr>
                     <tr  id = 'saveDiv1' style='display:none;'>
                       <td colspan='1' align='right' valign="middle">
                         <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printTranscriptReport();return false;"/>&nbsp;
                         <!--
                         <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
                         <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();return false;"/>
                         -->
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
    
