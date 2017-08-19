<?php 
//This file creates Html Form output "ListEmployeeReports " Module 
//
// Author :Parveen Sharma
// Created on : 8-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
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
			 
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Employee List Reports : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >&nbsp;
                 <form name="allDetailsForm" id="allDetailsForm" action="" method="post" onSubmit="return false;">    
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="" >
                <tr><a id="lk1"  class="set_default_values">Set Default Values for Report Parameters</a>
                 <td valign="top"  align="center">
                 <?php
                   $htmlFunctions=HtmlFunctions::getInstance();
                 ?>
                  <table border='0' width='100%' cellspacing='0'>
                   <?php echo $htmlFunctions->makeEmployeeDefaultSearch(); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeEmployeeAcademicSearch(false,'emp_','allDetailsForm'); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeEmployeeAddressSearch('emp_','allDetailsForm'); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeEmployeeMiscSearch('emp_'); ?>
                   </table>
                </form>   
                </td>
               </tr>
               <tr> 
                <td align="center">
                     <form name="employeeListReport" action="" method="post">
<table width="100%" align="center" border="0" > 
    <tr>
    <td colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
  </tr>
  <tr class="rowheading">
    <th scope="col" align="left" class="searchhead_text" colspan="6">Include in Report
    
        &nbsp;&nbsp;     <input type="checkbox" id="incAllInsitute" name="incAllInsitute" value="1" >&nbsp;<b>Search for all institutes</b>  
   
    </tr>
 
   <tr >
   
   <td colspan="6" class="row0" align="left">
 <table align="center" width="100%" >
 <tr class="row1">
     <td ><input type="checkbox" name="check[]" id="empCode" value="1">
      Employee Code </td>
    <td><input type="checkbox"  name="check[]" id="Name" value="1">
      Name </td>
    <td ><input type="checkbox" name="check[]" id="chkDesignation" value="1">
      Designation</td>
    <td ><input type="checkbox" name="check[]" id="chkDepartment" value="1">
      Department</td>
    </tr>
   <tr class="row1">
     <td><input type="checkbox" name="check[]" id="TeachingEmployee" value="1">
      Teaching Employee</td>     
    <td><input type="checkbox" name="check[]" id="employeeRole" value="1">
      Role</td>
    <td><input type="checkbox" name= "check[]" id="Qualification" value="1">
      Qualification </td>
    <td><input type="checkbox" name= "check[]" id="dateOfBirth" value="1">
      Date of Birth</td>
    </tr>
    <tr class="row1">
      <td><input type="checkbox" name= "check[]" id="Married" value="1">
      Married</td>
      <td><input type="checkbox" name= "check[]" id="Gender" value="1"> 
      Gender </td>
      <td><input type="checkbox" name= "check[]" id="fatherName" value="1"> 
      Father's Name </td>
    <td><input type="checkbox" name= "check[]" id="motherName" value="1">
      Mother's Name </td>  
    </tr>
	<tr class="row1">
	  <td><input type="checkbox" name= "check[]" id="DOJ" value="1">
      Date of Joining</td>
      <td><input type="checkbox" name= "check[]" id="DOL" value="1"> 
      Date of Leaving</td>
	  <td><input type="checkbox" name= "check[]" id="employeeEmail" value="1"> 
      Email </td>
	  <td><input type="checkbox" name= "check[]" id="instituteName" value="1"> 
      Institue Name </td>
	</tr>
    <tr class="row1">
      <td><input type="checkbox" name= "check[]" id="userName" value="1">
      User Name</td>
     <td></td>
     <td></td>
     <td></td>
    </tr>
    
 <tr class="row1" >
    <td colspan="2" class="searchhead_text">Phone No's </td>
    <td colspan="2" class="searchhead_text">Address</td>
 </tr>
 <tr class="row1">
    <td><input type="checkbox" name= "check[]" id="MobileNo" value="1"> 
      Mobile Number </td>
    <td><input type="checkbox" name= "check[]" id="LandlineNumber" value="1">
      Landline Number</td>
    <td colspan="2"><input type="checkbox" name= "check[]" id="Address" value="1">
      Address</td>
    </tr>
<tr class="row0">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>

 </table>
 </td>
 </tr>
 
  <tr >
    <td align="center" colspan="6" ><span style="padding-right:10px" >
      <input type="image" name="employeeListSubmit" value="employeeListSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="return validateAddForm(this.value);" />
      <a id='generateCSV2' href='javascript:void(0);'><input type="image"  name="employeePrintSubmit" onClick="return validateAddForm(this.value);" value="employeePrintCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></a>
      </td>
    </tr>
</table>           
 </form>           
  
                </td>
               </tr>
               <tr><td height="5px"></td></tr>
              </table> 
              </div>
             </td>
          </tr>
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>

  
<?php 
// $History: listEmployeeListsContents.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 1/04/10    Time: 12:05p
//Updated in $/LeapCC/Templates/EmployeeReports
//roles, department column added
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/EmployeeReports
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 6  *****************
//User: Parveen      Date: 9/30/09    Time: 5:17p
//Updated in $/LeapCC/Templates/EmployeeReports
//showlist button remove
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/11/09    Time: 3:55p
//Updated in $/LeapCC/Templates/EmployeeReports
//issue fix 1519, 1518, 1517, 1473, 1442, 1451 
//validiations & formatting updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 9/10/09    Time: 2:50p
//Updated in $/LeapCC/Templates/EmployeeReports
//template table formatting & print date formating updated 
//

?>
