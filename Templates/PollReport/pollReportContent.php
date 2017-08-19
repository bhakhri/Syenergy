
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
                    <td valign="top" class="content">
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" style="display:none">
                            <tr>
                                <td valign="top" class="contenttab_row1">
                                    <form name="allDetailsForm" id="allDetailsForm" action="" method="post" onSubmit="return false;">
                                        
                                    </form>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title">Teacher's Poll Details :</td>
                                            <td colspan="2" class="content_title" align="right">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();return false;"/> 
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' >
                                <td valign='top'  colspan='1' class='contenttab_row'>
                                    <div id="scroll2" style="overflow:auto; width:1000px; height:520px; vertical-align:top;">
                                       <div id="resultsDiv" style="width:100%; vertical-align:top;"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr id='pageRow' >    
                                <td valign='top' colspan='1'  class=''>
                                  <table width="98%" valign='top' border="0" class='' cellspacing="0" cellpadding="0" >
                                   <tr>
                                     <td valign='top' colspan='1'  class='' align='left'>    
                                        <span id = 'pagingDiv1' class='contenttab_row1' align='left'></span>
                                     </td>
                                     <td valign='top' colspan='1'  class='' align='right'>   
                                        <span id = 'pagingDiv' align='right'></span> 
                                     </td>
                                   </tr>
                                  </table>      
                                </td>
                            </tr>
                           
                        </table>
                        <!-- form table ends -->
                    </td>
                </tr>
            </table>
            </td>
        </tr>
      </table>

