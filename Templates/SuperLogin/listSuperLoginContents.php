<?php
//-------------------------------------------------------
// Purpose: to design the student detail for subject centric.
//
// Author : Rajeev Aggarwal
// Created on : (05.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");   
?>

    <tr>
        <td valign="top" colspan=2>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
		<tr>
		 <td valign="top" class="content">
		 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
		 <tr>
			<td valign="top" class="contenttab_row1" align="center">
			<form name="allDetailsForm" action="" method="post" onSubmit="return false;">
                <select name="hiddenClassId" id="hiddenClassId" style="display:none" >
                    <option value="">All</option>
                     <?php
                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                       echo HtmlFunctions::getInstance()->getLoginClass();
                    ?>
                 </select>
            
				<table width='10%'  border="0" cellspacing="0" cellpadding="0" align="left">
                    <tr><td height="8"></td></tr>
                    <tr>
                       <td class="contenttab_internal_rows"  valgin="top" width="2%" ><nobr><b>Roll No./Reg No.</b></nobr></td>
                       <td class="contenttab_internal_rows" valgin="top"  width="2%" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                       <td  class="contenttab_internal_rows"  valgin="top" width="2%"> 
                         <input name="rollNo" id="rollNo" class="inputbox" style="width:247px" type="text">  
                       </td>
                       <td class="contenttab_internal_rows" valgin="top" style="padding-left:10px;"  width="2%" ><nobr><b>Student Name</b></nobr></td>
                       <td class="contenttab_internal_rows" valgin="top" width="2%" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                       <td  class="contenttab_internal_rows"  valgin="top"  width="2%"> 
                         <input name="studentName" id="studentName" class="inputbox" style="width:200px" type="text">  
                       </td>
                       <td class="contenttab_internal_rows" valgin="top" style="padding-left:10px;"  width="2%" ><nobr><b>Father's Name</b></nobr></td>
                       <td class="contenttab_internal_rows" valgin="top" width="2%" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                       <td  class="contenttab_internal_rows"  valgin="top" colspan='4'  width="2%"> 
                         <input name="fatherName" id="fatherName" class="inputbox" style="width:200px" type="text">  
                       </td>
                    </tr>
                    <tr><td height="8"></td></tr>  
					<tr>
                       <td class="contenttab_internal_rows" align="left" width='9%'><nobr><b>Degree</b></nobr></td>
                       <td class="contenttab_internal_rows" width="1%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                       <td class="contenttab_internal_rows" >  
                            <select name="degreeId" id="degreeId" style="width:250px" class="selectfield" onchange="getSearchValue('Branch'); return false;" >
                                <option value="">All</option>
                                 <?php
                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                   echo HtmlFunctions::getInstance()->getAllDegree();
                                ?>
                            </select>
                       </td> 
                       <td  class="contenttab_internal_rows" style="padding-left:10px;"><nobr><b>Branch</b></nobr></td>
                       <td  class="contenttab_internal_rows" width="1%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                          <td  class="contenttab_internal_rows" width="2%">  
                            <select name="branchId" id="branchId"  class="selectfield" style="width:205px"  onchange="getSearchValue('Batch'); return false;">
                                <option value="">All</option>
                            </select>
                        </td>
                        <td  class="contenttab_internal_rows" style="padding-left:10px;" ><nobr><b>Batch</b></nobr></td>
                        <td  class="contenttab_internal_rows" width="1%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                          <td  class="contenttab_internal_rows" width="2%">  
                            <select name="batchId" id="batchId"  class="selectfield" style="width:205px"  onchange="getSearchValue('Class'); return false;">
                                <option value="">All</option>
                            </select>
                        </td>
                     </tr>
                     <tr>   
                        <td class="contenttab_internal_rows" valgin="top" width="2%" ><nobr><b>Class</b></nobr></td>
                        <td  class="contenttab_internal_rows"  width="1%" style="padding-top:7px;"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                        <td  class="contenttab_internal_rows" colspan="3" width="2%" style="padding-top:7px;">  
                          <select name="classId" id="classId" class="selectfield" style="width:350px"  >
                            <option value="">All</option>
                          </select>
                         </td>
						 <td valign='top' class="contenttab_internal_rows" colspan="4" style="padding-top:8px;padding-left:48px;">
 				           <input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateForm();return false;" />
                         </td>       
					 </tr>
                      
				</table>
				</form> 
			</td>
			</tr>
			<tr id='nameRow' style='display:<?php echo $showData?>'>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Student / Parent  Detail: </td>
						<td align="right" valign="middle">
                         <!--<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport()"/>&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printStudentCSV()"/>-->
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr id='resultRow' style='display:<?php echo $showTitle?>'>
                <td class="contenttab_row1" valign="top" ><div id="results">  
                 </div>          
             </td>
          </tr>
		 
          </table>
        </td>
    </tr>
	 <tr id='nameRow2' style='display:<?php echo $showPrint?>'><td colspan='1' align='right' height="35" valign="bottom">
      <!--<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport()"/>&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printStudentCSV()"/>-->
      </td>
      </tr>
    </table>
    </td>
    </tr>
    </table>
