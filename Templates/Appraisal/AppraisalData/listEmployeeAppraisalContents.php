<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
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
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Employee Hierarchy : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <form name="employeeDetailsForm" id="employeeDetailsForm" action="" method="post" style="display:inline" onSubmit="return false;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                 <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                <tr>
                 <td valign="top"  align="center">
                 <?php
                   $htmlFunctions=HtmlFunctions::getInstance();
                 ?>
                  <table border='0' width='100%' cellspacing='0'>
                   <?php echo $htmlFunctions->makeEmployeeDefaultSearch(); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeEmployeeAcademicSearch(false,'emp_','employeeDetailsForm'); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeEmployeeAddressSearch('emp_','employeeDetailsForm'); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeEmployeeMiscSearch('emp_'); ?>
                   <tr>
                    <td valign='top' colspan='8' class='' align='center'>
                     <input type="image" name="employeeListSubmit" value="employeeListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateEmployeeList();return false;" />
                     </td>
                    </tr>
                   </table>
                <!--</form>-->
                <div id="showList" style="display:none">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                 <td>
                <!--<form name="listFrm" id="listFrm">-->
                <!--Do not delete-->
                 <input type="hidden" name="emps" id="emps" />
                 <input type="hidden" name="emps" id="emps" />  
                 <!--Do not delete-->
                 
                 <div id="results">
                </div>
                <!--</form>-->
                </td>
               </tr>
               <tr><td height="5px"></td></tr>
               <tr> 
                <td align="center">
                <div id="divButton" style="display:none">
                </div> 
                 </td>
               </tr>
               <tr><td height="5px"></td></tr>
              </table> 
              </div>
             </td>
          </tr>
          </table>
          </td></tr></table>
          </form>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>

<?php
// $History: listAdminEmployeeMessageContents.php $
?>