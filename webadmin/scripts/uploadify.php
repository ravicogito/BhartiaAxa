<?php
session_start();
/*
Uploadify v2.1.0
Release Date: August 24, 2009

Copyright (c) 2009 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
require("../ngg_Thumbnail.class.php");
if (!empty($_FILES)) {

	$tempFile = $_FILES['Filedata']['tmp_name'];
	//$targetPath = '../uploads/';
	$targetPath = '../../product_images/';
	$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];
	$filename = $_FILES['Filedata']['name'];
	
		move_uploaded_file($tempFile,$targetFile);
		 $thumb = new ngg_Thumbnail($targetFile);
		/*resige*/
		 $cWidth = $thumb->getCurrentWidth();
                $cHeight = $thumb->getCurrentHeight();
                
                $ratio = 1;
                $ratio = $cWidth / 200;//1.33
				$thumb->resizeFix(200, ceil($cHeight/$ratio));
                
               
                $thumbs_file = $targetPath.'midthumb_'.$filename;
                $thumb->save($thumbs_file);
				$width = $thumb->getCurrentWidth();
                $height = $thumb->getCurrentHeight();
				
				//$midFile = 'uploads/'.$filename;
				$midFile = '../product_images/'.$filename;
		list($width,$height) = getimagesize($targetFile);
		
		$fileName = $_FILES['Filedata']['name'];
		$filearray = array('w'=>$width,'h'=>$height,'path'=>$fileName,'baseSize'=>'50','id'=>rand(5,999),'tpath'=>$targetPath.'thumb_'.$fileName, 'path'=>$midFile,	'mtpath'=>$targetPath.$fileName,'spath'=>'thumb_'.$fileName,'toppath'=>$targetPath.'thumb2_'.$fileName,'spath1'=>'thumb2_'.$fileName,'toppath1'=>$targetPath.'thumb3_'.$fileName,'spath12'=>'thumb3_'.$fileName,);
		
		switch ($_FILES['Filedata']['error'])
{     
     case 0:
             $msg = ""; // comment this out if you don't want a message to appear on success.
			 /*do the db send and resizing*/
             break;
     case 1:
              $msg = "The file is bigger than this PHP installation allows";
              break;
      case 2:
              $msg = "The file is bigger than this form allows";
              break;
       case 3:
              $msg = "Only part of the file was uploaded";
              break;
       case 4:
             $msg = "No file was uploaded";
              break;
       case 6:
             $msg = "Missing a temporary folder";
              break;
       case 7:
             $msg = "Failed to write file to disk";
             break;
       case 8:
             $msg = "File upload stopped by extension";
             break;
       default:
            $msg = "unknown error ".$_FILES['Filedata']['error'];
            break;
}

if ($msg)
    $stringData = "Error: ".$_FILES['Filedata']['error']." Error Info: ".$msg;
else
   $stringData = "1"; // This is required for onComplete to fire on Mac OSX
   echo json_encode($filearray);

		//echo "1";
	// } else {
	// 	echo 'Invalid file type.';
	// }
}
?>