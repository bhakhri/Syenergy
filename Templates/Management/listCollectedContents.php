<?php 
// This file creates Html Form output in fees collected Module 
//
// Author :Rajeev Aggarwal
// Created on : 12-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Fees Collected Detail</td>
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
                        <td class="content_title">Fees Collected Detail:</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top"><form action="" method="POST" name="listForm" id="listForm"><div id="results">  
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
                 <tr class="rowheading">
					<td width="3%" class="searchhead_text">&nbsp;&nbsp;<b>#</b></td>
                    <td width="12%" class="searchhead_text"><b>Receipt</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                    <td width="8%" class="searchhead_text"><b>Date</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=receiptDate')" /></td>
					<td width="12%" class="searchhead_text"><b>Name</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=fullName')" /></td>
					<td width="7%" class="searchhead_text"><b>Roll No</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=rollNo')" /></td>
                    <td width="9%" class="searchhead_text"><b>Fee Cycle</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=cycleName')" /></td>
                    <td width="10%" class="searchhead_text"><b>Payable(Rs)</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=discountedFeePayable')" /></td>
                    <td width="8%" class="searchhead_text"><b>Paid(Rs)</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=totalAmountPaid')" /></td>
					<td width="13%" class="searchhead_text" nowrap><b>Outstanding(Rs)</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=outstanding')" /></td>
					<td width="8%" class="searchhead_text" nowrap><b>Status</b></td>
                 </tr>
                <?php
                $recordCount = count($studentRecordArray);
                if($recordCount >0 && is_array($studentRecordArray)){ 

                   for($i=0; $i<$recordCount; $i++ ){
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                        $rollno = $studentRecordArray[$i]['rollNo'];
						$cls = $studentRecordArray[$i]['className'];
						$batchName = $studentRecordArray[$i]['batchName'];
						
						if($rollno=="")
							$rollno = "--";

						$uniRollno = $studentRecordArray[$i]['universityRollNo'];
						if($uniRollno=="")
							$uniRollno = "--";

                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($studentRecordArray[$i]['receiptNo']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($studentRecordArray[$i]['receiptDate']).'</td>
						<td class="padding_top" valign="top">'.strip_slashes($studentRecordArray[$i]['fullName']).'</td>
						<td class="padding_top" valign="top">'.strip_slashes($studentRecordArray[$i]['rollNo']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($studentRecordArray[$i]['cycleName']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($studentRecordArray[$i]['discountedFeePayable']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($studentRecordArray[$i]['totalAmountPaid']).'</td>
						<td class="padding_top" valign="top">'.strip_slashes($studentRecordArray[$i]['outstanding']).'</td>
                        <td class="padding_top" valign="top">'.$receiptArr[$studentRecordArray[$i]['receiptStatus']].'</td>
                        </tr>';
                    }
                }
                else {
                    echo '<tr><td colspan="9" align="center">No record found</td></tr>';
                }
                ?>   
                 </table></div></form>    
			</td>
           </tr>
          </table>
        </td>
    </tr>
    </table>
<?php 
//$History: listCollectedContents.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:02p
//Created in $/LeapCC/Templates/Management
//Initial checkin
?>