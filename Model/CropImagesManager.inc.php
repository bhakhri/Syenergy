<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Crop Image" Module

// Author :Ajinder Singh
// Created on : 05-Sep-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class CropImagesManager {
    
    private static $instance = null;
    
    public static $image;
    public static $image_type;
    
    
    //--------------------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ApproveStudentManager" CLASS
    //
    // Author :Dipanjan Bhattacharjee 
    // Created on : (12.06.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------      
        private function __construct() {
        }

    //-------------------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ApproveStudentManager" CLASS
    //
    // Author :Dipanjan Bhattacharjee 
    // Created on : (12.06.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------       
      public static function getInstance() {
            if (self::$instance === null) {
                $class = __CLASS__;
                return self::$instance = new $class;
            }
            return self::$instance;
      }
 
       public function load($filename) {
     
          $image_info = getimagesize($filename);
          $this->image_type = $image_info[2];
          if( $this->image_type == IMAGETYPE_JPEG ) {
     
             $this->image = imagecreatefromjpeg($filename);
          } elseif( $this->image_type == IMAGETYPE_GIF ) {
     
             $this->image = imagecreatefromgif($filename);
          } elseif( $this->image_type == IMAGETYPE_PNG ) {
     
             $this->image = imagecreatefrompng($filename);
          }
       }
       
       public function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
     
          if( $image_type == IMAGETYPE_JPEG ) {
             imagejpeg($this->image,$filename,$compression);
          } elseif( $image_type == IMAGETYPE_GIF ) {
     
             imagegif($this->image,$filename);
          } elseif( $image_type == IMAGETYPE_PNG ) {
     
             imagepng($this->image,$filename);
          }
          if( $permissions != null) {
     
             chmod($filename,$permissions);
          }return true;
       }
       public function output($image_type=IMAGETYPE_JPEG) {
     
          if( $image_type == IMAGETYPE_JPEG ) {
             imagejpeg($this->image);
          } elseif( $image_type == IMAGETYPE_GIF ) {
     
             imagegif($this->image);
          } elseif( $image_type == IMAGETYPE_PNG ) {
     
             imagepng($this->image);
          }
       }
       
       public function getWidth() {
     
          return imagesx($this->image);
       }
       
       public function getHeight() {
     
          return imagesy($this->image);
       }
       
       public function resizeToHeight($height) {
     
          $ratio = $height / $this->getHeight();
          $width = $this->getWidth() * $ratio;
          $this->resize($width,$height);
       }
     
       public function resizeToWidth($width) {
          $ratio = $width / $this->getWidth();
          $height = $this->getheight() * $ratio;
          $this->resize($width,$height);
       }
     
       public function scale($scale) {
          $width = $this->getWidth() * $scale/100;
          $height = $this->getheight() * $scale/100;
          $this->resize($width,$height);
       }
     
       public function resize($width,$height) {
          $new_image = imagecreatetruecolor($width, $height);
          imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
          $this->image = $new_image;
       }      
}
?>
