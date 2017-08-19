<?php 
//  This File calls addFunction used in adding Notice Records
//
// Author :Arvind Singh Rawat
// Created on : 1-October-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);                
UtilityManager::headerNoCache(); 


    //$filename = $_GET['path'];
$filename=IMG_HTTP_PATH.'/Notice/'.$_GET['path'];
//dl_file($filename);
// fix for IE catching or PHP bug issue
header("Pragma: public");
header("Expires: 0"); // set expiration time
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
// browser must download file from server instead of cache

// force download dialog
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");

// use the Content-Disposition header to supply a recommended filename and
// force the browser to display the save dialog.
header("Content-Disposition: attachment; filename=".basename($filename).";");
/*
The Content-transfer-encoding header should be binary, since the file will be read
directly from the disk and the raw bytes passed to the downloading computer.
The Content-length header is useful to set for downloads. The browser will be able to
show a progress meter as a file downloads. The content-lenght can be determines by
filesize function returns the size of a file.
*/
/*header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($filename));

@readfile($filename);
exit(0);   */
/*function dl_file($file){
     echo $file;
    //First, see if the file exists
    if (!is_file($file)) { die("<b>404 File not found!</b>"); }

    //Gather relevent info about file
    $len = filesize($file);
    $filename = basename($file);
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    //This will set the Content-Type to the appropriate setting for the file
    switch( $file_extension ) {
      case "pdf": $ctype="application/pdf"; break;
      case "exe": $ctype="application/octet-stream"; break;
      case "zip": $ctype="application/zip"; break;
      case "doc": $ctype="application/msword"; break;
      case "xls": $ctype="application/vnd.ms-excel"; break;
      case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
      case "gif": $ctype="image/gif"; break;
      case "png": $ctype="image/png"; break;
      case "jpeg":
      case "jpg": $ctype="image/jpg"; break;
      case "mp3": $ctype="audio/mpeg"; break;
      case "wav": $ctype="audio/x-wav"; break;
      case "mpeg":
      case "mpg":
      case "mpe": $ctype="video/mpeg"; break;
      case "mov": $ctype="video/quicktime"; break;
      case "avi": $ctype="video/x-msvideo"; break;

      //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
      case "php":
      case "htm":
      case "html": die("<b>Cannot be used for ". $file_extension ." files!</b>"); break;

      default: $ctype="application/force-download";
    }

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
   
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");

    //Force the download
    $header="Content-Disposition: attachment; filename=".$filename.";";
    header($header );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    @readfile($file);
    exit;         */ 
 ?>