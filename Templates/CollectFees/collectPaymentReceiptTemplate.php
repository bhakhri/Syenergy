<?php 
//This file is used as printing version for payment receipt template.
//
// Author :Parveen Sharma
// Created on : 29-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

$paddingLeft = "style='padding-left:170px'";

$showPaymentModeDetail1 = "<tr>
                            <td class='dataFont' colspan='2'>
                               <table width='100%' border='0px' cellpadding='1px' cellspacing='0px'>   
                                 <tr>
                                    <td><b>Payment Mode:</b>&nbsp;</td>
                                 </tr>   
                                 <tr><td class='dataFont' colspan='2' height='4px'></td></tr>   
                                 <tr>
                                    <td class='dataFont' colspan='2'><b>By Cash:&nbsp;</b><CashAmount></td>
                                 </tr>   
                                 <PaymentModeDetail>
                               </table>  
                            </td>
                         </tr>";
                         
 $paymentModeDetail1 = "<tr>
                          <td colspan='2'>   
                            <table width='100%' border='1px' cellpadding='1px' cellspacing='0px'> 
                               <tr>
                                 <td class='dataFont' align='left'   width='20%'><b>Instrument</b></td>
                                 <td class='dataFont' align='left'   width='20%'><b>Number</b></td>
                                 <td class='dataFont' align='right'  width='20%'><b>Amount</b></td> 
                                 <td class='dataFont' align='left'   width='20%'><b>Bank</b></td>
                                 <td class='dataFont' align='center' width='20%'><b>Date</b></td>
                               </tr> 
                               <InstrumentDetail>
                            </table>
                          </td>   
                         </tr>";              
                         
                         
 $receiptData="<table width='100%' border='0px' cellpadding='0px' cellspacing='0px' >
                <tr class='dataFont'>
                     <td align='left' colspan='2'>
                        <table width='100%' border='0px' cellpadding='0px' cellspacing='0px' >
                          <tr>
                             <td align='left' width='25%' valign='top'>
                               <InstituteLogo>       
                             </td> 
                             <td align='center' width='75%' valign='top'>
                                <b><InstituteName><InstituteAddress></b>
                             </td>   
                          </tr>  
                        </table>
                     </td>
                 </tr> 
                 <tr class='dataFont'>   
                      <td align='left' colspan='2'>
                        <table width='100%' border='0px' cellpadding='0px' cellspacing='0px' >
                          <tr>
                             <td align='left' width='25%'>&nbsp;</td> 
                             <td align='center' width='75%'>
                                <b>RECEIPT FOR FEE & CHARGES</b>
                             </td>   
                          </tr>  
                        </table>
                     </td>                                      
                 </tr> 
                 <tr><td class='dataFont' colspan='2' height='4px'></td></tr> 
                 <tr class='dataFont'>
                     <td width='50%' align='left'><b>Receipt No.:&nbsp;</b><ReceiptNo></td>   
                     <td align='right'><b>Date:&nbsp;</b><ReceiptDate>&nbsp;</td>   
                 </tr> 
                 <tr><td class='dataFont' colspan='2' height='4px'></td></tr> 
                 <tr>
                    <td class='dataFont' colspan='2'> 
                        Received from <By1>&nbsp;<b><StudentName></b>&nbsp;<By2>&nbsp;<b><FatherName></b>&nbsp;
                    </td>
                 </tr>
                 <tr><td class='dataFont' colspan='2' height='4px'></td></tr> 
                 <tr>
                    <td class='dataFont' align='left' colspan='2'>
                        Semester:&nbsp;<b><Semester></b><span style='padding-left:15px'>Branch:&nbsp;<b><Branch></b></span>
                    </td>
                 </tr>
                 <tr><td class='dataFont' colspan='2' height='4px'></td></tr> 
                 <tr>
                    <td class='dataFont' colspan='2'> 
                        Roll No.(Reg No.)&nbsp;<b><u><RollNo>(<RegNo>)</u></b> a sum of Rs <b><u><Amount>/-</u></b> <b>(<WordsAmount>)</b>&nbsp;
                        <Following>      
                    </td>
                 </tr> 
                 <tr><td class='dataFont' colspan='2' height='10px'></td></tr> 
                 <tr>
                     <td colspan='2'>   
                        <table width='100%' border='1px' cellpadding='1px' cellspacing='0px'> 
                           <FeeHeadsDetail>
                        </table>
                     </td>
                 </tr> 
                 <tr><td class='dataFont' colspan='2' height='6px'></td></tr>
                 <tr>
                    <td class='dataFont' colspan='2'>
                       <table width='100%' border='0px' cellpadding='1px' cellspacing='0px'>   
                          <tr>
                              <td class='dataFont' width='60%'>&nbsp;</td>
                              <td class='dataFont' align='left' width='25%' nowrap><b>Total Due Amount</b></td>       
                              <td class='dataFont' width='2%'><b>&nbsp;:&nbsp;</b></td>
                              <td class='dataFont' width='8%' align='right' nowrap><NetPaidAmount></td>
                          </tr>  
                          <PrevFineAmount> 
                          <tr>
                            <td class='dataFont'>&nbsp;</td>
                            <td class='dataFont' align='left'><b>Installment</b></td>
                            <td class='dataFont'><b>&nbsp;:&nbsp;</b></td>
                            <td class='dataFont' align='right' ><Installment></td>
                          </tr>
                          <PrevPaymentDetail> 
                        </table>
                    </td>
                 </tr>
                 <tr><td class='dataFont' colspan='2' height='6px'></td></tr>
                 <tr><td class='dataFont' colspan='2' align='left'><b><u><WordsAmount></u></b></td></tr>
                 <tr><td class='dataFont' colspan='2' height='6px'></td></tr>   
                 <ShowPaymentDetail>   
                 <PrintRemarks>
                 <tr>
                   <td height='40px' valign='bottom' class='dataFont' align='left'><b>".strtoupper($sessionHandler->getSessionVariable('UserName'))."</b></td> 
                   <td height='40px' valign='bottom' class='dataFont' align='right'><b>Authorised Signatory</b></td> 
                 </tr>                       
              </table>";

  
              
  $paymentReceiptPrint = "<table width='98%' border='0px' cellpadding='0px' cellspacing='0px' align='center'>
               <tr>
                 <td width='47%'>".$receiptData."</td>
                 <td width='6%'>&nbsp;</td>
                 <td width='47%'>".$receiptData."</td>
               </tr>";
 