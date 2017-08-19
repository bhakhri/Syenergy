<?php
//---------------------------------------------------------------------------
// THIS FILE used for sending message(sms/email/dashboard) to students
// Author : Dipanjan Bhattacharjee
// Created on : (25.01.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SendMessageToNumbers');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>Send bulk SMS</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeJS("tiny_mce/tiny_mce.js"); 
echo UtilityManager::includeCSS2();
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?>
<script language="javascript">
var SMSML=<?php echo SMS_MAX_LENGTH; ?>;
tinyMCE.init({
        gecko_spellcheck : true,
        mode : "textareas",
        theme : "advanced",
		cleanup:true,
		editor_selector : "mceEditor",
		editor_deselector : "mceNoEditor",
        plugins : "paste",
        theme_advanced_buttons3_add : "pastetext,pasteword,selectall",
        paste_auto_cleanup_on_paste : true,
        paste_preprocess : function(pl, o) {
            // Content string containing the HTML from the clipboard
            //alert(o.content);
        },
        paste_postprocess : function(pl, o) {
            // Content DOM node containing the DOM structure of the clipboard
            //alert(o.node.innerHTML);
        },  
        setup : function(ed) {
        ed.onKeyUp.add(function(ed, e) {
          smsCalculation("'"+removeHTMLTags(tinyMCE.get('elm1').getContent())+"'",SMSML,'sms_no');
         }
        );
      },

       // Theme options
       theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|formatselect,fontselect,fontsizeselect",
       theme_advanced_buttons2 : "search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",

    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left"
    //theme_advanced_statusbar_location : "bottom",
    //theme_advanced_resizing : false
});


//function for checking duplicate mobile nos
function checkDuplicate(val){
    var len=mArray.length;
    for(var i=0;i<len;i++){
        if(mArray[i]==val){
            return 1;
        }
    }
    return 0;
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO send message
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var mArray=new Array();
function sendMessage() {
    
        if(isEmpty(tinyMCE.get('elm1').getContent())){
            messageBox("<?php echo EMPTY_SMS_MSG_BODY; ?>"); 
            try{
              tinyMCE.execInstanceCommand("elm1", "mceFocus");
            }
            catch(e){}
            return false;
        }
        
        var mobileNos=trim(document.getElementById('mobileNoTxt').value);
        if(mobileNos==''){
            messageBox("<?php echo EMPTY_MOBILE_NOS; ?>");
            document.getElementById('mobileNoTxt').focus();
            return false;
        }
		
        var mNosArray=mobileNos.split(',');
        var mCnt=mNosArray.length;
        mArray=new Array();
        for(var i=0;i<mCnt;i++){
			if(trim(mNosArray[i]).length!= 10)
			{
               messageBox("<?php echo INVALID_MOBILE_DIGIT ?> at position "+(i+1));
			   return false;
			}
            if(trim(mNosArray[i])==0 || trim(mNosArray[i]).length<10 || !isNumeric(trim(mNosArray[i]))){
               messageBox("<?php echo INVALID_MOBILE_NO_FOUND?> at position "+(i+1));
               document.getElementById('mobileNoTxt').focus();
               return false;
            }
           if(checkDuplicate(trim(mNosArray[i]))){
              messageBox("<?php echo DUPLICATE_MOBILE_NO_FOUND?> at position "+(i+1));
              document.getElementById('mobileNoTxt').focus();
              return false; 
           }
           else{
              mArray[i]=trim(mNosArray[i]); 
           } 
        }

        var url = '<?php echo HTTP_LIB_PATH;?>/AdminMessage/ajaxAdminSendMessage.php';
        new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 msgBody   : (trim(tinyMCE.get('elm1').getContent())), 
                 mobileNos : (mobileNos)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var ret=trim(transport.responseText);
                     if("<?php echo SUCCESS;?>" == ret) {                     
                         if(confirm("<?php echo MSG_SENT_OK.'\n'.CONFIRM_FILE_DOWNLOADING_FOR_SENT_MESSAGES?>")==true){
                             window.location ="<?php echo UI_HTTP_PATH ?>/adminSentMessagesDocument.php?mobileNos="+mobileNos+'&msgBody='+trim(tinyMCE.get('elm1').getContent());
                         }
                     } 
                     else {
                        messageBox(ret); 
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


//----------------------------------------------------------------------------------------------------------------
//Pupose:Calculates  sms chars and no of smses
//Author: Dipanjan Bhattacharjee
//Date : 5.08.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function smsCalculation(value,limit,target){

 var temp1=value;
 var nos=1;    //no of sms limit://length of a sms
 if(tinyMCE.get('elm1').getContent()!=""){
  document.getElementById('sms_char').value=(parseInt(temp1.length)+1-3);
 } 
 else{
  document.getElementById('sms_char').value=0;   
 } 
 while(temp1.length > (limit+2)){
     temp1=temp1.substr(limit);
     nos=nos+1;
 }    
document.getElementById(target).value=nos; 
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/AdminMessage/listSendAdminMessageContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>

</body>
</html>

<?php                              
// $History: listSendAdminMessage.php $ 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 25/01/10   Time: 14:14
//Created in $/LeapCC/Interface
//Created "Send SMS" modules for sending SMSs to numbers entered by the
//end user
?>
