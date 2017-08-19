<?php
//-------------------------------------------------------
// Purpose: to design the layout for Hostel.
//
// Author : Jaineesh
// Created on : (26.06.2008 )
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
					<td valign="top">Inventory &nbsp;&raquo;&nbsp;Items & Supplier Mapping Master</td>
					<td valign="top" align="right">
					</td>
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
								<td class="contenttab_border" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										<tr>
											<td class="content_title">Mapping Detail : </td>
											<td class="content_title" ></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="contenttab_row" valign="top">
                                <form name="searchForm" id="searchForm" method="post" onsubmit="return false;">
                                <input type="hidden" name="itemId" id="itemId" value="" />
                                  <table border="0" cellpadding="0" cellspacing="0">
                                   <tr>
                                      <td class="contenttab_internal_rows" valign="top" style="padding-top:5px;padding-left:10px;"><nobr><b>Item Code</b></nobr></td>
                                      <td class="padding">:</td>
                                      <td class="padding">
                                       <input name="itemCode" id="itemCode" class="inputbox" value="" />  
                                      </td>
                                      <td style="padding-left:5px">
                                      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onclick="getMappingValue(document.searchForm.itemCode.value);return false;"/>
                                    </td>  
                                   </tr>
                                  </table>
                                </form>
                                <!--Main Result Div-->
                                <div id="results"></div>
                                <!--Main Result Div Ends-->
							 </td>
						</tr>
                        <tr><td height="3px"></td></tr>
                        <tr><td align="center" id="saveTd" style="display:none">
                          <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="doMapping();return false;"/>
                        </td></tr>
					</table>
				  </td>
			   </tr>
		    </table>
		  </td>
	  </tr>
</table>
<?php 
// $History: itemsSupplierMappingContents.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/09/09   Time: 11:11
//Updated in $/Leap/Source/Templates/INVENTORY/ItemsMaster
//Added links for "Items & Supplier Mapping" module and corrected page
//title.
?>