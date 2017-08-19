<?php
//-------------------------------------------------------
// Purpose: to design student To Optional Subject Mapping.
//
// Author : Rajeev Aggarwal
// Created on : (05.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
               <td valign="top">Institute Setup&nbsp;&raquo;&nbsp;Assign Optional Subjects To Students</td>
                 
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			  
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Assign Optional Subjects To Students  </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
				<form action="" method="POST" name="optionalSubjectMapping" id="optionalSubjectMapping">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
				 <td valign="top" class="content" >
				 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
				 <tr>
					<td  >
					<table width="100%" border="0" cellspacing="0" cellpadding="0" >
					<tr>
						<td height="10"></td>
					</tr>
					<tr>	
						<td class="contenttab_internal_rows"><nobr><b>Class: </b></nobr></td>
						<td class="padding"><select size="1" class="selectfield1" name="studentClass" id="studentClass" onChange="autoPopulate(this.value,'subjectOptional','Add');">
						<option value="">Select</option>
						<?php
						  require_once(BL_PATH.'/HtmlFunctions.inc.php');
						  echo HtmlFunctions::getInstance()->getClassData($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);
						?>
						</select></td>
						<td class="contenttab_internal_rows"><nobr><b>Subject: </b></nobr></td>
						<td class="padding"><select size="1" class="selectfield" name="subject" id="subject" >
						<option value="">Select</option>
						</select></td>
						<td class="contenttab_internal_rows"><nobr><b>Group: </b></nobr></td>
                        <td class="padding"><select size="1" class="selectfield" name="studentGroup" id="studentGroup" >
                        <option value="">Select</option>
                        </select></td>
                        <td class="contenttab_internal_rows" style="padding-right:5px;padding-top:4px" valign="top"><input type="image" name="imageField1" id="imageField1" onClick="validatetoptionalSubjectMapping();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />          </td>
					</tr>
					<tr>
						<td height="10"></td>
					</tr>
					<tr>
						
						<td height="10" colspan="2"></td>
					</tr>
					<tr><td colspan="4" height="5px"><input type="hidden" name="checkBoxCount" id="checkBoxCount" value="" /></td></tr>
					</table>
				</td>
			</tr>
             <tr>
                <td class="contenttab_row" valign="top" style="border-bottom-width:0px;border-left-width:0px;border-right-width:0px;" >
                <div id="results">  
                
                </div>          
             </td>
          </tr>
          </table>                      
        </td>
		</tr>
          <tr>
          <td align="center">
          <div id="submitButton" style="display:none">
          <input type="image" name="imageField1" id="imageField1" onClick="validateAddForm('optionalSubjectMapping');return false" src="<?php echo IMG_HTTP_PATH;?>/submit.gif" />
          </div>
          </td>
          </tr>
		</table>
		</form>         
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
//$History: optionalSubjectContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/OptionalSubjectMapping
//
//*****************  Version 4  *****************
//User: Arvind       Date: 8/29/08    Time: 3:33p
//Updated in $/Leap/Source/Templates/OptionalSubjectMapping
//
//*****************  Version 1  *****************
//User: Arvind       Date: 8/28/08    Time: 8:08p
//Created in $/Leap/Source/Templates/OptionalSubjectMapping
//added a new file for student to optional subject mapping
?>
 
    


