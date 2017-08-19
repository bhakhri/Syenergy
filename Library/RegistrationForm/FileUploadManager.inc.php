<?php
//-------------------------------------------------------
// Purpose: File upload 
//
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

class FileUploadManager{
        private static $instance=NULL;
        public $name;
        public $type;
        public $size;
        public $tmp;
        public $error;
        public $message;
        public $fileExtension;
        public $allowedExtensions;
        /**
         * Function to initialize variables
         *
         * @return bool
         */
         private function __construct($fieldName='image'){
                                   
                global $_FILES,$allowedExtensionsArray;
                $this->error = $_FILES[$fieldName]['error'];
                if($this->error != 0){
                        $this->message = $this->error;
                }
                else{
                        $this->name = $_FILES[$fieldName]['name'];
                        $this->type = $_FILES[$fieldName]['type'];
                        $this->size = $_FILES[$fieldName]['size'];
                        $this->tmp  = $_FILES[$fieldName]['tmp_name'];
                        $extArr = explode(".",$this->name);
                        $this->fileExtension = strtolower($extArr[count($extArr)-1]);
                        $this->allowedExtensions = $allowedExtensionsArray; 
                }
        }
        public static function getInstance($fieldName) {
               $c = __CLASS__;
               self::$instance = new $c($fieldName);
               return self::$instance;
        }
        /**
         * Function to upload image
         * @param $targetPath: target path where to upload image
         * @param $filename : name of the file 
         * @return bool
         */
        public function upload($targetPath, $filename='') {
               
            if($this->size > MAXIMUM_FILE_SIZE) {
               $this->message = 'Maximum upload size is '.ceil(MAXIMUM_FILE_SIZE/1024).' kb'; 
               return false;
            }
            
            if(!in_array($this->fileExtension,$this->allowedExtensions)) {
               $this->message = 'Invalid file extenstion. Try only '.implode(',',$this->allowedExtensions).' extenstions.';  
               return false;
            }
            
            if(trim($filename)=='') {
               $filename = $this->name;
            }               
            if(!is_writable($targetPath) ) {
               @chmod($targetPath,777);
            }
            if(!move_uploaded_file($this->tmp, $targetPath.$filename)) {
                $this->message = 'The file couldn\'t be uploaded.'; 
                return false;                     
            }
            else {
                $this->message = 'The file has been uploaded successfully';
                return true;
            }
        }
}

/*
//test code
include_once("common.inc.php");

if(!empty($_FILES['image']['name'])) {

    $fileObj = FileUploadManager::getInstance('image');
    if($fileObj->upload(IMG_PATH.'/Institutes/')) {
        echo $fileObj->message;                
    }
    else {
        echo $fileObj->message;
    }
}
echo "<form action='' method='post' enctype='multipart/form-data'><input type=\"file\" name=\"image\"><input type=\"submit\" name=\"formSubmit\" value=\"Submit\" /> </form> ";
*/

// $History: FileUploadManager.inc.php $
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 10/01/08   Time: 6:36p
//Updated in $/Leap/Source/Library
//initialized $allowedExtenstions variable from Global array
//$allowedExtensionsArray in common.inc.php 
//
//*****************  Version 3  *****************
//User: Administrator Date: 7/09/08    Time: 11:32a
//Updated in $/Leap/Source/Library
//just added strtolower($this->fileExtenstion) By Pushpender
?>