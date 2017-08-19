<?php
//This file creates Html Form output in Subjects Module 
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>

    <tr>
        <td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr height="30">
                                <td class="contenttab_border" height="20" style="border-right:0px;">
				  <table>
				     <tr>
					<td class="contenttab_internal_rows">&nbsp;<nobr><b><h2>Photo Gallery</h2></nobr></td>
				    </tr>	
				  </table>	                                  
                                </td>
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;">
                                </td>
                            </tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" >
                                   <div id="scroll2" style="overflow:auto; width:1000px; height:510px; vertical-align:top;">
                                       <div id="resultsDiv" style="width:98%; vertical-align:top;"></div>
                                   </div>
                                </td>
                            </tr>
             <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                       <td class="content_title" valign="middle" align="right" width="20%"></td>
                    </tr>
                    </table>
                </td>
             </tr>
                        </table>
         </td>
    </tr>
    </table>
   </td>
    </tr>
    </table>   


<!--Start Notice  Div-->
<?php floatingDiv_Start('divMessage','Photo Gallery','',''); ?>
<form name="MessageForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">	
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>
        <td width="89%" style="padding-left:5px">
            <div id="scroll12" style="overflow:auto; width:700px; height:500px; vertical-align:top;">
                <div id="photoResultsDiv" style="width:98%; vertical-align:top;"></div>

            </div>
        </td>
    </tr>
</table>
</form>
<?php floatingDiv_End(); ?>



