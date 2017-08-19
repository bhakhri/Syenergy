<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR entering marks
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<?php
    require_once(BL_PATH.'/helpMessage.inc.php');
?>
<form action="" method="" id="searchForm" name="searchForm" onsubmit="return false;"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">

            <tr>
			<?php require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
</tr>

               <!-- <td valign="top">Marks & Attendance  &nbsp;&raquo;&nbsp;Test Marks</td> -->
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
                        <td class="content_title" width="80%">Enter External Marks : </td>
                        <td style="padding-right:10px" align="right" class="content_title">
                         
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <td class="contenttab_border1" >
             
               <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center" >
                    <tr>
                        <td class="contenttab_internal_rows" width="3%"><nobr><b>Class</b></nobr></td>
                        <td class="padding" width="5%"><nobr>:
                        <!--<select size="1" class="selectfield" name="class" id="class" onchange="blankValues(1);populateSubjects(this.value);groupPopulate(this.form.subject.value);"  >-->
         <select style="width:320px;" size="1" class="selectfield" name="class" id="class" onchange="blankValues(1);populateSubjects(this.value);"  >
                        <option value="" selected="selected">Select Class</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTeacherClassData();
                        ?>
                       </select></nobr>
                      </td>
                        <td class="contenttab_internal_rows"  width="2%" style="padding-left:5px"><nobr><b>Subject</b></nobr></td>
                        <td class="padding" width="4%"><nobr>: <select size="1" class="selectfield" name="subject" id="subject" onchange="blankValues();groupPopulate(this.value);testTypePopulate(this.value);">
                        <option value="" selected="selected">Select Subject</option>
                          <?php
                          // require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           //echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                        ?>
                        </select></nobr>
                      </td>
                        <td class="contenttab_internal_rows"  width="2%" style="padding-left:5px"><nobr><b>Group</b></nobr></td>
                        <td class="padding" width="1%" style="padding-right:0px"><nobr>: <select size="1" class="selectfield" name="group" id="group"  onchange="blankValues(1);" style="width:195px;">
                        <option value="" selected="selected">Select Group</option>
                          <?php
                           //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           //echo HtmlFunctions::getInstance()->getTeacherGroupData();
                        ?>
                        </select></nobr>
                      </td>
                      <td class="padding">
                      <nobr>&nbsp;&nbsp;&nbsp;
                     <!-- <input type="image" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
-->                      </nobr>
                      </td>
                    </tr>
                    <tr>
                     <td class="contenttab_internal_rows" style=''><nobr><b>Test Type</b></nobr></td>
                      <td class="padding" style='' align='bottom'><nobr>:
                       <select size="1" class="selectfield" name="testType" id="testType" onchange="blankValues();" >
                       <option value="" selected="selected">SELECT</option>
					   <?php
							//require_once(BL_PATH.'/HtmlFunctions.inc.php');
							//echo HtmlFunctions::getInstance()->getTestTypeCategory("WHERE examType='PC' AND showCategory=1",'');
					   ?>
                      </select> &nbsp;&nbsp; <input type="image" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" /></nobr>
                    </tr>
                    </table>
                    
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >&nbsp;
                <div id="showList" style="display:none">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                 <td>
                
                <!--Do Not Delete-->
                 <input type="hidden" name="mem">
                 <input type="hidden" name="mem">
                <!--Do Not Delete-->
                <table id="divButton1" border="0" cellpadding="0" cellspacing="0" width="100%" height="30px" style="display:none" >
                   <tr class="contenttab_border">
                     <td class="content_title" align="left">List of Students : </td>
                     <td align="right">
             
                   </td>
                 </tr>
                 <tr>
                    <td width="2px"class="contenttab_internal_rows">
                       <nobr><b>Max Marks&nbsp;:&nbsp;</b><input type="text" id="maxMarks" name="maxMarks" ></nobr>
                    </td>
                 </tr>
                 <tr>
                  <td colspan="2">
                   <div id="scroll2" style="overflow:auto; height:410px; vertical-align:top;">
                       <div id="results" style="width:98%; vertical-align:top;"></div>  
                   </div>
                  </td>
                  </tr>
                 </table>
                </td>
               </tr>
               <tr><td height="5px"></td></tr>
               <tr>
                <td align="right">
                  <input type="image" id="imageField2"  name="imageField2" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateForm();return false;" />
             	 <!-- <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" title = "Print"> -->
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
</form>

<!--Assignments Marks Help  Details  Div-->
<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>
        <tr>
            <td width="89%">
                <div id="helpInfo" style="vertical-align:top;" ></div>
            </td>
        </tr>
    </table>
</div>
<?php floatingDiv_End(); ?>
<!--Assignments Marks Help  Details  End -->



