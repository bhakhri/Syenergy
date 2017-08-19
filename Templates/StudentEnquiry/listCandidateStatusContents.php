<?php
//-------------------------------------------------------
// This file is a Template file for listing of Candidate Status
//
//
// Author :Vimal Sharma                          
// Created on : (11.02.2009)
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
                <td valign="top">Admission&nbsp;&raquo;&nbsp;Candidate Status</td>

            </tr>
            </table>
        </td>
    </tr>
            <tr>
                <td height="10"></td>
            </tr>   
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405"> 
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
             <tr>
                <td height="10"></td>
            </tr>   
               <tr>
                    <td valign="top" align="right">
                            <form action="" method="" name="searchForm">
                                <table border="0" cellspacing="0" cellpadding="0" width="100%" align=center>
                                <tr>
                                    <td class="padding_top"><b> &nbsp;&nbsp;Select Program : </b><select size="1" class="selectfield" name="allPrograms" id="allPrograms" style="width:120px;" onchange="blankDivs(); return false;">
                                    <option value="0" SELECTED="SELECTED">All</option>
                                    <?php
                                        require_once(MODEL_PATH . "/Admission/CandidateManager.inc.php");     
                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                        echo HtmlFunctions::getInstance()->getAllProgramList();
                                        ?>
                                    </select>
                            </td>
                                <td class="padding_top"><b> &nbsp;&nbsp;Select Status : </b><select size="1" class="selectfield" name="statusType" id="statusType" style="width:120px;" onchange="blankDivs(); return false;">
                                        <option value="AL" selected="selected">ALL</option> 
                                        <option value="SL">Admission & Offered</option>
                                        <option value="A">Admission</option>
                                        <option value="O">Offered</option>
                                        <option value="S">Waiting</option>
                                        <option value="R">Rejected</option>
                                </select></td>
                            <td><b> &nbsp;&nbsp;Search : </b>
                            <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                              &nbsp;
                              <input type="image"  name="submit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" title="Show List"   style="margin-bottom: -5px;" onClick="sendReq(listURL,divResultName,searchFormName,'');showDivs();return false;"/>&nbsp;
                             </tr>
                            </table>
                            </form>
                        </td>
                </tr> 
                <tr>
                    <td height="10"></td>
                </tr>                                     
             </table>             
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr id='nameRow' style='display:'>
                        <td class="content_title">Candidate Status Detail : </td>
                        <td>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr> 
                                <td class="content_title" title="Export to Excel" align="right"> <a id='generateCandidateCSV' href='javascript:void(0);' onClick='javascript:exportCandidateCSV();'><img src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0"></a></td>
                                <td class="content_title" title="Re Generate" width="20"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                                    align="right" onClick="regenerate();return false;" />&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                        </tr>
                        
                    </table> 
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <div id="results">  
                 <table width="100%" border="0" cellspacing="0" cellpadding="0"  id="anyid">
                 </table></div>           
             </td>
          </tr>
          
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>

<!--Start Edit Div-->
<?php floatingDiv_Start('editForm','Edit Candidate '); ?>
 <form action="" method="POST" name="editForm" id="editForm">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align=center> 
    <tr>
        <td height="10" colspan=2></td>
    </tr>  
    <tr>
        <td valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
            <tr>
                <td height="5" colspan=2></td>
            </tr>   
            <tr>
                <td valign="top"> 
                <fieldset class="fieldset">
                <legend>Candidate Details</legend>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td height="5"></td>
                    </tr>
                    <tr>
                        <td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Form No</b></nobr></td>
                        <td class="contenttab_internal_rows"><b>:</b></td>
                        <td class="padding_top"><input type="text" id="formNo" name="formNo" class="inputbox" maxlength="20" disabled/></td>
                     </tr>
                    <tr>    
                        <td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Candidate Name</b></nobr></td>
                        <td class="contenttab_internal_rows"><b>:</b></td>
                        <td class="padding_top"><input type="text" id="candidateName" name="candidateName" class="inputbox" maxlength="60" disabled/></td>
                    </tr>

                           <tr>  
                        <td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Gender</b></nobr></td>
                        <td class="contenttab_internal_rows"><b>:</b></td>
                        <td class="padding_top"><input type="radio" id="genderRadio" name="genderRadio" value="M" checked disabled/>Male&nbsp;&nbsp;<input type="radio" id="genderRadio" name="genderRadio" value="F" disabled/>Female</td>
                    </tr>
                    <tr>
                        <td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Date Of Birth</b></nobr></td>
                        <td class="contenttab_internal_rows"><b>:</b></td>
                        <td class="padding_top"> <nobr><input type="text" id="dob" name="dob" class="inputbox" maxlength="11" disabled/></nobr>
                        
                        </td>
                    </tr>
                    <tr>
                        <td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>AIEEE Roll No</b></nobr></td>
                        <td class="contenttab_internal_rows"><b>:</b></td>
                        <td class="padding_top"><input type="text" id="AIEEERollNo" name="AIEEERollNo" class="inputbox" maxlength="10" disabled/></td>
                    </tr>
                    <tr>          
                        <td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Rank</b></nobr></td>
                        <td class="contenttab_internal_rows"><b>:</b></td>
                        <td class="padding_top"><input type="text" id="AIEEERank" name="AIEEERank" class="inputbox" maxlength="10" disabled/></td>
                    </tr>
                    <tr>
                        <td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Mobile No </b></nobr></td>
                        <td class="contenttab_internal_rows"><b>:</b></td>
                        <td class="padding_top"><input type="text" id="candidateMobile" name="candidateMobile" class="inputbox"  maxlength="15" disabled/></td>
                    </tr>
                     <tr>
                        <td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Hostel Accomodation Required</b></nobr></td>
                        <td class="contenttab_internal_rows"><b>:</b></td>
                        <td class="padding_top"><input type="radio" id="hostelFacility" name="hostelFacility" value="1" disabled/>Yes&nbsp;&nbsp;<input type="radio" id="hostelFacility" name="hostelFacility" value="0" disabled/>No
                    </tr>
                    <tr>
                        <td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Email</b></nobr></td>
                        <td class="contenttab_internal_rows"><b>:</b></td>
                        <td class="padding_top"><input type="text" id="candidateEmail" name="candidateEmail" class="inputbox" maxlength="100" disabled/>
                        </td>
                    </tr>
                    <tr>
                        <td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Category</b></nobr></td>
                        <td class="contenttab_internal_rows"><b>:</b></td>
                        <td class="padding_top"><select size="1" class="selectfield" name="candidateCategory" id="candidateCategory"  disabled>
                        <option value="">Select</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getCategoryClassData($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);
                        ?>
                        </select></td>
                    </tr>
                </table>
                </fieldset>
                </td>
            </tr> 
            <tr>
                <td height="10"></td>
            </tr>    
            <tr>  
                <td valign="top"> 
                <fieldset class="fieldset">
                <legend>Father / Guardian Details</legend>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td height="5"></td>
                    </tr>
                    <tr>    
                        <td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Father/Mother/Guardian Name</b></nobr></td>
                        <td class="contenttab_internal_rows"><b>:</b></td>
                        <td class="padding_top"><input type="text" id="fatherGuardianName" name="fatherGuardianName" class="inputbox" maxlength="60" disabled/></td>
                    </tr>
                    <tr>  
                        <td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Relation with Candidate</b></nobr></td>
                        <td class="contenttab_internal_rows"><b>:</b></td>
                        <td class="padding_top"><select size="1" class="selectfield" name="relationWithCandidate" id="relationWithCandidate" disabled>
                            <option value="F" selected="selected">Father</option> 
                            <option value="M">Mother</option>
                            <option value="G">Guardian</option> 
                        </select></td>
                    </tr>  
                    <tr>          
                        <td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><b>Mobile No </b></nobr></td> 
                        <td class="contenttab_internal_rows"><b>:</b></td>
                        <td class="padding_top"><input type="text" id="fatherGuardianMobile" name="fatherGuardianMobile" class="inputbox"  maxlength="15" disabled/></td>
                    </tr>                       
                </table>
                </fieldset>
            </td>
        </tr>
        </table>                   
        </td>
        <td valign="top">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td height="5"></td>
                </tr>    
                <tr>  
                    <td valign="top"> 
                    <fieldset class="fieldset">
                    <legend>Allot Program Status</legend>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td height="5" colspan="3"></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Program Status</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="candidateStatusType" id="candidateStatusType" style="width:120px;">
                                    <option value="S" SELECTED>Waiting</option> 
                                    <option value="A">Admission</option>
                                    <option value="O">Offered</option>
                                    <option value="R">Rejected</option>
                            </select></td>   
                        </tr>   
                        <tr>
                        <td class="contenttab_internal_rows"><nobr><b>Program Alloted</b></nobr></td>
                        <td class="contenttab_internal_rows"><b>:</b></td>
                        <td class="padding_top"><select size="1" class="selectfield" name="programAlloted" id="programAlloted">
                            <?php echo HtmlFunctions::getInstance()->getAllProgramList(); ?>
                            </select></td>
                        </tr>                        
                    </table>
                    </fieldset>
                </tr>               
                <tr>
                    <td height="5" colspan=2></td>
                </tr>
            </table>
        </td>
    </tr> 
    <tr>
        <td height="5px"></td></tr>
    <tr>       
    <tr>
        <td align="center" style="padding-right:10px" colspan="2">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this,'Edit');">
            <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('editForm');return false;">
        
        </td>
    </tr>
    <tr>
        <td height="5px"></td></tr>
    <tr>                                                             
</table>

</form>



<?php floatingDiv_End(); ?>
<!--End: Div To Edit The Table-->