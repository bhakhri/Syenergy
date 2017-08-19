<?php 
//This file creates Html Form output in "Display Marks "  Module 
//
// Author :Arvind Singh Rawat
// Created on : 16-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 $queryString =  $_SERVER['QUERY_STRING'];
?>
<form method="POST" name="addForm" id="addForm"  action="<?php echo HTTP_LIB_PATH;?>/Student/fileUpload.php"      method="post" enctype="multipart/form-data" style="display:inline">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">

            <tr>
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>
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
                        <td class="content_title">Marks Detail :</td>
                         <td width="67%" align='right'>  
                            <span class="content_title">Study Period:</span>
                            <select size="1" class="selectfield" name="studyPeriod" id="studyPeriod" style="width:100px" onchange="refreshStudentData('<?php echo $studentDataArr[0]['studentId']?>',this.value);"> 
                            <option value="0">All</option>
                              <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStudyPeriodName($studentDataArr[0]['studentId'],$studentDataArr[0]['classId']);
                              ?>
                              </select>&nbsp;&nbsp;
                         </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                    <table width="100%" border="0" cellspacing="5" cellpadding="5">
                    <tr>
                        <td valign="top" colspan="5" align="center">
                            <div id="scroll2" style="overflow:auto; height:510px; vertical-align:top;">
                                 <div id="gradeResultDiv" style="width:98%; vertical-align:top;"></div>
                            </div>
                        </td>
                     </tr>
                    <tr>
                        <td colspan='1' align='right'><div id = 'saveDiv3'></div></td>
                    </tr>      
                    <?php
                    /*
                        <tr>
                            <td class="content_title" title="Print" align="right" style="padding-right:10px" valign="top"><img src="<?php echo IMG_HTTP_PATH; ?>/print.gif"    onClick="printReport();" ></img>
                             &nbsp;&nbsp;&nbsp;<input type="image"  name="studentPrintSubmit" id='generateCSV2' onClick="printCSV();return false;" value="studentPrintCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" />
                            </td>
                        </tr>  
                    */    
                    ?>   
                  </table>
                </div>
             </td>
          </tr>
           <tr>
             <td class="content_title" title="Print" align="right" style="padding-right:10px">
              <input type="image"  name="studentPrintSubmit" id='generateCSV2' onClick="printReport(); return false;" value="studentPrintCSV" src="<?php echo IMG_HTTP_PATH;?>/print.gif" />&nbsp;&nbsp;
             <input type="image"  name="studentPrintSubmit" id='generateCSV2' onClick="printCSV();return false;" value="studentPrintCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" />&nbsp;
             </td>
           </tr>
          </table>
        </td>
    </tr>
   </table>

                
<?php 
//$History : $

?>
