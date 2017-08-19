<?php
$rupeeIcon = "<img src='".IMG_HTTP_PATH."/rupee-icon.gif' style='height:10px'>"; 
$dataContent="<table border='0px' cellpadding='0px' cellspacing='0px'> 
               <CopyReceipt>
				<tr>
				  <td>
					<table border='0px' cellpadding='0px' cellspacing='0px' style='padding: 12px' >
					   <tr>
					     <td>
					 	 <tr class='dataFont'>
					        <td class='dataFont' colspan=2 style='padding-top:4px'><b>Date&nbsp;:</b>&nbsp;<date></td>  
					     </tr>
					     <tr class='dataFont'>
					        <td class='dataFont' colspan=2 style='padding-top:4px'>
					            <b>Bank Name&nbsp;:&nbsp;</b><bankName>
					            <span style='float:right'><b>A/C No.:</b>&nbsp;<acNo></span>
					        </td> 
					     </tr>
					     <tr class='dataFont'>
					        <td align='left' colspan='2' style='padding-top:10px'>
					            <table border='0px' cellpadding='0px' cellspacing='0px' >
					                <tr>
					                    <td align='center'>
					                        <img src='".IMG_HTTP_PATH."/logo.gif' width='200' height='60' border=0>       
					                    </td> 
					                </tr>  
					            </table>
							</td>
					     </tr> 
					     <tr class='dataFont'>
					         <td colspan='2' align='center' style='padding-top:10px'><b>FINE RECEIPT</b></td>
					     </tr>
					     <tr>
					        <td class='dataFont' style='padding-top:4px;width:35%' valign='top' nowrap> 
					           <b>Student Name</b>
					        </td>					
					        <td class='dataFont' style='padding-top:4px;width:65%' nowrap valign='top'><b>:</b>&nbsp;<studentName></td>
					     </tr>
					     <tr>
					        <td class='dataFont' style='padding-top:4px' valign='top'> 
					            <b>Father's Name</b>
					        </td>
					        <td class='dataFont' style='padding-top:4px' valign='top'><b>:&nbsp;</b><fatherName></td>
					     </tr>
					     <tr>
					        <td class='dataFont' align='left' style='padding-top:4px' valign='top'>
					            <b>Class Name</b></td>
					         <td class='dataFont' nowrap style='padding-top:4px' valign='top'><b>:</b><studentClass></td>
					     </tr>
					     <tr>
					        <td class='dataFont' style='padding-top:4px' nowrap align='left'>
					          <b>Reg No.</b>
					        </td>
					        <td class='dataFont' style='padding-top:4px' nowrap><b>:</b>&nbsp;<regNo></td>
					     </tr>
					     <tr>
					        <td class='dataFont' style='padding-top:4px' nowrap align='left'>
					          <b>Roll No.</b>
					        </td>
					        <td class='dataFont' style='padding-top:4px' nowrap><b>:</b>&nbsp;<rollNo></td>
					     </tr>
					     <tr>
					        <td colspan='2' style='padding-top:8px'>   
					          <table width='100%' border='1px' cellpadding='1px' cellspacing='0px'> 
					           <tr>
					               <td class='dataFont' align='center' width='5%'><strong>#</strong></td>
					               <td class='dataFont'  width='60%'><strong>Particulars</strong></td>
					               <td class='dataFont' align='right' width='35%'><strong>Amount&nbsp;($rupeeIcon)</strong></td>
					           </tr> 
					           <FineDetail>
							   <tr>
							    <td class='dataFont' colspan = 2  style='padding:4px 0px 0px 4px' ><strong>Total Amount&nbsp;</strong></td> 
								<td class='dataFont' align='right' style='padding-top:4px' nowrap>&nbsp;<totalAmount></td> 
							   </tr> 
					          </table>
					         </td>
					     </tr>
					     <tr>
					        <td class='dataFont' colspan='2' height='6px'></td>
					     </tr>
					     <tr>
					        <td class='dataFont' colspan='2' align='left'><b><u></u></b></td>
					     </tr>
					     <tr>
					        <td class='dataFont' colspan='2' height='6px'></td>
					     </tr>   
					     <tr>
					        <td class='dataFont' colspan='3'><b>Cash&nbsp;/&nbsp;DD No.&nbsp;</b>............................................................</td>
					     </tr>
					     <tr>
					        <td class='dataFont' colspan='3' style='padding-top:5px'>..................................................<b>&nbsp;Dated&nbsp;</b>.......................</td>
					     </tr>
					     <tr>
					        <td class='dataFont' colspan='3' style='padding-top:5px'><b>Bank Name&nbsp;</b>................................................................</td>
					     </tr>
					     <tr>
					        <td class='dataFont' colspan='3' style='padding-top:5px'>......................................................................................</td>
					     </tr>     
					     <tr>
					       <td  valign='bottom' class='dataFont' colspan=3 style='padding-top:40px'><b>Depositor's Singnature</b> <span  style='float:right'>  <b>Authorised Signatory</b></span></td> 
					     </tr>                       
					     <tr>
					        <td  valign='bottom' class='dataFont' colspan=3 style='padding-top:10px;font-weight: normal; font-size: 9px; FONT-FAMILY: Arial, Helvetica, sans-serif; '>
					         <b><i>Computerized Generate Slip so please pay on same date</i></b>
					        </td>
					     </tr>                  
					     </td>                    
					   </tr>      
					</table>
				</td>
				<CutImage>
			</tr>
		</table>";
?>

