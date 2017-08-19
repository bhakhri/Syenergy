<?php 
//This file creates Html Form output for create parent Login
//
// Author :Parveen Sharma
// Created on : 20-oct-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<form name="allDetailsForm" action="" method="post" onSubmit="return false;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?>  
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
                                   <table width="100%" align="center" border="0" >
                                     <tr> 
                                       <td valign="top">
                                         <table border='0' width='100%' cellspacing='0'>
                                            <?php echo $htmlFunctions->makeStudentDefaultSearch(); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentAcademicSearch(); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentAddressSearch(); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentMiscSearch(); ?>
                                          </table>  
                                        </td> 
                                     </tr>
                                     <tr>
                                        <td>
                                            <input type="hidden" name="degs" value="">
                                            <input type="hidden" name="degsText" value="">
                                            <input type="hidden" name="brans" value="">
                                            <input type="hidden" name="branText" value="">
                                            
                                            <input type="hidden" name="subjectId" value="">
                                            <input type="hidden" name="periods" value="">
                                            <input type="hidden" name="periodsText" value="">

                                            <input type="hidden" name="course" value="">
                                            <input type="hidden" name="courseText" value="">

                                            <input type="hidden" name="grps" value="">
                                            <input type="hidden" name="grpsText" value="">
                                            
                                            <input type="hidden" name="univs" value="">
                                            <input type="hidden" name="univsText" value="">

                                            <input type="hidden" name="citys" value="">
                                            <input type="hidden" name="citysText" value="">

                                            <input type="hidden" name="states" value="">
                                            <input type="hidden" name="statesText" value="">
                                            
                                            <input type="hidden" name="cnts" value="">
                                            <input type="hidden" name="cntsText" value="">

                                            <input type="hidden" name="hostels" value="">
                                            <input type="hidden" name="hostelsText" value="">

                                            <input type="hidden" name="buss" value="">
                                            <input type="hidden" name="bussText" value="">

                                            <input type="hidden" name="routs" value="">
                                            <input type="hidden" name="routsText" value="">
                                            <input type="hidden" name="quotaText" value="">
                                            <input type="hidden" name="bloodGroupText" value="">
                                            
                                             <input type="hidden" name="studentCheckIds" id="studentCheckIds" value="">
                                            <input type="hidden" name="onePassword" id="onePassword" value="">
                                           
                                            <input type="hidden" name="studentNotIds" id="studentNotIds" value=""> 
                                            
                                            <input type="hidden" name="fIds" id="fIds" value="">
                                            <input type="hidden" name="mIds" id="mIds" value="">
                                            <input type="hidden" name="gIds" id="gIds" value="">
                                            
                                            <input type="hidden" name="checkValue" id="checkValue" value="">
                                            <input type="hidden" name="check1" id="check1" value="">
                                            
                                            <input type="hidden" name="sortOrderBy1" id="sortOrderBy1" value="">
                                            <input type="hidden" name="sortField1" id="sortField1" value="">
                                        </td>
                                      </tr>  
                                     <tr>
                                        <td valign="top" align="center" height="35px">
                                          <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="validateAddForm(); return false;" />
                                        </td>  
                                     </tr>
                                     <tr>
                                        <td align="center">
                                           <table border="0" width="90%" cellspacing="0">  
                                             <tr>
                                                <td class="contenttab_internal_rows"><b>Note:</b></td>
                                             </tr>
                                             <tr>
                                                <td class="contenttab_internal_rows">&nbsp;a.&nbsp;  To create new parent logins for parents. The username would be a prefix f&lt;RollNumber&gt; for father, prefix m&lt;RollNumber&gt; for mother and g&lt;RollNumber&gt; for guardian.</td>
                                             </tr>
                                             <tr>
                                                <td class="contenttab_internal_rows"  valign="bottom" height="25px"><b>Password:</b></td>
                                             </tr>
                                             <tr>
                                                <td class="contenttab_internal_rows" valign="middle">
                                                  <input type="checkbox" name="overwrite" id="overwrite" value="1" checked="checked">&nbsp;Do not change the password for existing users.  
                                                </td>
                                             </tr>
                                             <tr>
                                                <td class="contenttab_internal_rows">
                                                  <input onClick="document.getElementById('newPassword').value=''; document.getElementById('newPassword').disabled=true;" type="radio" name="password" id="firstNamePassword" checked="checked">&nbsp;The passwords would be first name of parent followed by the year(yy) of birth of student. (i.e. ram90, seema87)  
                                                </td>
                                             </tr>
                                             <tr>
                                                <td class="contenttab_internal_rows">
                                                  <input onClick="document.getElementById('newPassword').value=''; document.getElementById('newPassword').disabled=false;" type="radio" name="password" id="commonPassword">&nbsp;Enter common password:&nbsp;
                                                   <input type="password" name="newPassword" id="newPassword" class="inputbox" disabled="disabled" maxlength="20">
                                                </td>
                                             </tr>
                                             <tr>
                                                <td class="contenttab_internal_rows" valign="bottom"><br><b>Authorized Person:</b></td>
                                             </tr>
                                             <tr>
                                                <td class="contenttab_internal_rows">
                                                  Name:&nbsp;&nbsp;<input type="text" name="authorizedName" id="authorizedName" class="inputbox" maxlength="50">
                                                  &nbsp;&nbsp;&nbsp;&nbsp;
                                                  Designation:&nbsp;&nbsp;<input type="text" name="designation" id="designation" class="inputbox" maxlength="50">
                                                </td>
                                             </tr>
                                           </table>
                                        </td>
                                     </tr>
                                   </table>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20" colspan="2" width="100%">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title"  valign="middle">Parent(s) List :</td>
                                            <td colspan="1" class="content_title" align="right" valign="middle">
                                               <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/generate_logins_print.gif" onClick="passwordReport()" />&nbsp;
                                               <input type="image" id="generateCSV" id="generateCSV" src="<?php echo IMG_HTTP_PATH;?>/generate_logins_export.gif" onClick="passwordReportCSV()" />&nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td class='contenttab_row'>
                                  <div id="ResultDiv" style="overflow:auto; HEIGHT:520px; vertical-align:top;">    
                                    <div id='results'>                               
                                    </div>
                                  </div>  
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right" valign="middle">
                                               <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/generate_logins_print.gif" onClick="passwordReport()" />&nbsp;
                                               <input type="image" id="generateCSV" id="generateCSV" src="<?php echo IMG_HTTP_PATH;?>/generate_logins_export.gif" onClick="passwordReportCSV()" />&nbsp;
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

<!--Start Topic  Div-->
<?php floatingDiv_Start('divInformation','Error(s)/Warning(s)','',''); ?>
<form name="divForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>
      <td valign="top" align="center">
        <div class='report'>For following list(s) login not created.</div> 
      </td>
    </tr>
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>    
        <td width="95%" align="center" valign="top">                          
          <div  style="overflow:auto; width:550px; height:350px; vertical-align:top;">
            <div id="userNotGenerateInfo" style="width:530px; height:350px; vertical-align:top;"></div>
          </div>  
        </td>
    </tr>
</table>
</form> 
<?php floatingDiv_End(); ?>
<?php 
//$History: createParentLoginContents.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 9/21/09    Time: 1:15p
//Updated in $/LeapCC/Templates/CreateParentLogin
//Resolved the sorting, conditions, alignment issues updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 9/18/09    Time: 10:53a
//Updated in $/LeapCC/Templates/CreateParentLogin
//sorting & validations updated & CSV file created
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/28/09    Time: 4:55p
//Updated in $/LeapCC/Templates/CreateParentLogin
//internet browser base div format updated(login not created div
//updated).
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/28/09    Time: 4:11p
//Created in $/LeapCC/Templates/CreateParentLogin
//initial checkin
//
?>
