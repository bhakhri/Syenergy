<?php
//This file creates Html Form output in Book Module
//
// Author :Nancy Puri
// Created on : 04-Oct-2010
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
                                    <?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
                                </td>
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddBook',300,200);blankValues();return false;" />&nbsp;</td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
                            </tr>
                            <tr>
                                <td align="right" colspan="2">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                                        <tr>
                                            <td class="content_title" valign="middle" align="right" width="20%">
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
                                            </td>
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

<?php floatingDiv_Start('AddBook','Add Book'); ?>
<form name="addBook" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">

<tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Book Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="bookName" name="bookName" class="inputbox" maxlength="50"/></td>
</tr>
<tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Book Author<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="bookAuthor" name="bookAuthor" class="inputbox" maxlength="50"/></td>
</tr>
<tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Unique Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="uniqueCode" name="uniqueCode" class="inputbox" maxlength="20"/></td>
</tr>
<tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Institue Book Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="instituteBookCode" name="instituteBookCode" class="inputbox" maxlength="20"/></td>
</tr>
<tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>ISBN Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="isbnCode" name="isbnCode" class="inputbox" maxlength="20"/></td>
</tr>
<tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>No. of books<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="noOfBooks" name="noOfBooks" class="inputbox" maxlength="15"/></td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
                    <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddBook');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td>
</tr>


</table>
</form>

<?php floatingDiv_End(); ?>



<?php floatingDiv_Start('EditBook','Edit Book'); ?>
<form name="editBook" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">


   <input type="hidden" name="bookId" id="bookId" value="" />  
    <tr>
        <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Book Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" id="bookName" name="bookName" class="inputbox"  maxlength="50"/></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Book Author<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" id="bookAuthor" name="bookAuthor" class="inputbox" maxlength="50"/></td>
    </tr>
    <tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Unique Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="uniqueCode" name="uniqueCode" class="inputbox" maxlength="20"/></td>
</tr>
<tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Insitute Book Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="instituteBookCode" name="instituteBookCode" class="inputbox" maxlength="20"/></td>
</tr>
<tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>ISBN Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="isbnCode" name="isbnCode" class="inputbox" maxlength="20"/></td>
</tr>
<tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>No. of books<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="noOfBooks" name="noOfBooks" class="inputbox" maxlength="15"/></td>
</tr>
    <tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
                    <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"
                    onclick="javascript:hiddenFloatingDiv('EditBook');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>

</table>
</form>

    <?php floatingDiv_End(); ?>
