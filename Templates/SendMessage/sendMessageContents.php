<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Global Masters&nbsp;&raquo;&nbsp;City Master</td>
                <td valign="top" align="right">
                 <!-- 
                <form action="" method="" name="searchForm">
               
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                  <input type="submit" value="Search" name="submit"  class="button" style="margin-bottom: 3px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>&nbsp;
                  </form>
                   --> 
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
                        <td class="content_title">Send Message : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td valign="top" class="contenttab_row">
              <table cellpadding="0" cellspacing="0" border="0" width="100%" >
               <tr>
                <td valign="top" width="50%" rowspan="3">
                 <textarea id="elm1" name="elm1" rows="10" cols="60" style="width: 100%">         
                 
                 </textarea>
                </td>
              </tr>
              <tr>  
                <td valign="top" width="50%" style="padding-left:5px;padding-top:5px;">
                 <span class="content_title">Message Medium :</span> <input type="checkbox" id="smsCheck" name="smsCheck" value="1">SMS &nbsp;
                                  <input type="checkbox" id="emailCheck" name="emailCheck" value="1">E-Mail &nbsp;
                                  <input type="checkbox" id="dashBoardCheck" name="dashBoardCheck" value="1">DashBoard &nbsp;
                </td> 
               </tr>
               <tr> 
               <?php 
                $thisDate=date('Y')."-".date('m')."-".date('d');
               ?> 
                <td valign="top" width="50%" style="padding-left:5px;padding-top:5px;">
                 <span class="content_title">Visible From :</span>
                 <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->datePicker('startDate',$thisDate);
                  ?>
                <br /> 
                <br /> 
                <span class="content_title">Visible To &nbsp;&nbsp;&nbsp;&nbsp;:</span> 
                <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->datePicker('endDate',$thisDate);
                  ?>
                </td> 
               </tr>
              </table>  
             </td>
             </tr>
             <tr>
             <td class="contenttab_border1" >
             <form action="" method="" name="searchForm"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>    
                        <td width="5%" class="contenttab_internal_rows"><nobr><b>Class: </b></nobr></td>
                        <td width="14%" class="padding"><select size="1" class="selectfield" name="class" id="class" onChange="populateGroup();">
                        <option value="">Select Class</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getConcatenateClassData($REQUEST_DATA['class']==''?$REQUEST_DATA['class'] : $REQUEST_DATA['class']);
                        ?>
                      </select></td>
                        <td width="5%" class="contenttab_internal_rows"><nobr><b>Batch: </b></nobr></td>
                        <td width="12%" class="padding"><select size="1" class="selectfield" name="batch" id="batch" onChange="populateGroup();">
                        <option value="">Select Batch</option>
                          <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getBatchData($REQUEST_DATA['batch']==''? $REQUEST_DATA['batch'] : $REQUEST_DATA['batch'] );
                          ?>
                        </select>
                      </td>
                        <td width="11%" class="contenttab_internal_rows"><nobr><b>Study Period: </b></nobr></td>
                        <td width="17%" class="padding"><select size="1" class="selectfield" name="studyPeriod" id="studyPeriod" onChange="populateGroup();">
                        <option value="">Select Study Period</option>
                          <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getStudyPeriodData($REQUEST_DATA['studyPeriod']==''? $REQUEST_DATA['studyPeriod'] : $REQUEST_DATA['studyPeriod'] );
                          ?>
                        </select>
                      </td>
                       <td width="6%" class="contenttab_internal_rows"><nobr><b>Group: </b></nobr></td>
                        <td width="30%" class="padding"><select size="1"  name="group" id="group" class="selectfield2">
                        <option value="">Select Group</option>
                        </select>
                      </td> 
                    </tr>
                     <tr>
                        <td height="10"></td>
                    </tr>
                     <tr>
                        <td  align="right" style="padding-right:5px" colspan="8">
                        <input type="image" name="imageField" onClick="getData();;return false" src="<?php echo IMG_HTTP_PATH;?>/save.gif" /></td>
                    </tr>
                    <tr><td colspan="4" height="5px"></td></td>    
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >&nbsp;
                <div id="showList" style="display:none">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                 <td>
                <form name="listFrm" id="listFrm">
                 <div id="results">
                </div>
                </form>           
                </td>
               </tr>
               <tr><td height="5px"></td></tr>
               <tr> 
                <td align="center">
                  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/send.gif" onClick="return validateForm();return false;" />
                 <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" width="90" height="28" onclick="hide_div('showList',2);return false;" />
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
// $History: sendMessageContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/SendMessage
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 7/15/08    Time: 4:16p
//Updated in $/Leap/Source/Templates/SendMessage
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/12/08    Time: 4:39p
//Updated in $/Leap/Source/Templates/SendMessage
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/11/08    Time: 5:22p
//Updated in $/Leap/Source/Templates/SendMessage
//Modify calender functionility to have common function
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/08/08    Time: 7:29p
//Updated in $/Leap/Source/Templates/SendMessage
//Added comments
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/08/08    Time: 5:49p
//Updated in $/Leap/Source/Templates/SendMessage
//Created sendMessage module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/05/08    Time: 6:17p
//Created in $/Leap/Source/Templates/SendMessage
//Initial Checkin
?>