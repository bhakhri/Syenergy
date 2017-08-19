<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','StudentInfoDetail');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();

require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
$studentInformationManager = StudentInformationManager::getInstance();
$studentId=$sessionHandler->getSessionVariable('StudentId');
/*************GET STUDENT's DOCUMENTS INFORMATIONS************/
 $studentDocumentsArray=$studentInformationManager->getStudentAttachedDocuments($studentId);
 $docArray=array();
 if(is_array($studentDocumentsArray) and count($studentDocumentsArray)>0){
     foreach($studentDocumentsArray as $key=>$value){
         $docId=$studentDocumentsArray[$key]['documentId'];
         $docFile=$studentDocumentsArray[$key]['documentFileName'];
         if($docFile!=''){
             if(file_exists(STORAGE_PATH.'/Images/Student/Documents/'.$docFile)){
                 $docArray[$docId]=$docFile;
             }
         }
     }
 }
/*************GET STUDENT's DOCUMENTS INFORMATIONS************/
?>
    <table width="100%" border="0" cellspacing="1" cellpadding="0"> 
    <tr class="rowheading">
      <td class="searchhead_text" style="padding-left:3px;">#</td>
      <td class="searchhead_text" style="padding-left:3px;">Document</td>
      <td class="searchhead_text" style="padding-left:3px;">Upload File</td>
      <td class="searchhead_text" style="padding-left:3px;" width="5%">Download</td>
      <td class="searchhead_text" style="padding-left:3px;" align="center" width="5%">Delete</td>
    </tr>
    <?php
     if(is_array($globalStudentDocumentsArray) and count($globalStudentDocumentsArray)>0){
       $x=1;
       foreach($globalStudentDocumentsArray as $key=>$value){
          $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
          $fileExistsFlag=0;
          if(array_key_exists($key,$docArray)){
              $fileExistsFlag=1; 
          }
          
          echo '<tr '.$bg.'>';
           echo '<td class="padding_top" style="padding-left:3px;">'.$x.'</td>';
           echo '<td class="padding_top" style="padding-left:3px;">'.$value.'</td>';
           echo '<td class="padding_top" style="padding-left:3px;"><input type="file" name="uploadDocs_'.$key.'" class="inputbox" size="20" /></td>';
           if($fileExistsFlag==1){
             $downloadString= '<a href="#"><img src="'.IMG_HTTP_PATH.'/download.gif" onclick="documentDownload('.$key.','.$studentId.');" title="Download File"></a>';
             $deleteString= '<a href="#"><img src="'.IMG_HTTP_PATH.'/delete.gif" onclick="deleteAttachedDocumentFile('.$key.','.$studentId.');" title="Delete uploaded file"></a>';
             echo '<td class="padding_top" style="padding-left:3px;" align="center">'.$downloadString.'</td>';
             echo '<td class="padding_top" style="padding-left:3px;" align="center">'.$deleteString.'</td>';
           }
           else{
             echo '<td class="padding_top" style="padding-left:3px;" align="center">'.NOT_APPLICABLE_STRING.'</td>';
             echo '<td class="padding_top" style="padding-left:3px;" align="center">'.NOT_APPLICABLE_STRING.'</td>';  
           }
           
          echo '<tr>'; 
          $x++;
       }
       ?>
       <tr><td colspan="5" height="5px"></td></tr>
       <tr>
         <td  align="center" style="padding-right:5px" valign="bottom" colspan="5">       
            <input type="image"  src="<?php echo IMG_HTTP_PATH;?>/upload.gif" onClick="return uploadAttachments();" >
          </td>
       </tr>
      <?php
     }
    else{
        echo NO_DATA_FOUND;
    } 
    ?>
  </table>