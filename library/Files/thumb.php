<?php

if (strpos($_GET['img'], '..') ||
    strpos($_GET['img'], '&#2e;&#2e;') ||
    strpos($_GET['img'], '%2e%2e')) exit;

 date_default_timezone_set('Europe/Warsaw');
 header("Cache-Control: cache");
 header("Pragma: cache");
 
 list($img_path, $img_name) = explode('/', $_GET['img']);


$image_x = $_GET['w'];
$image_y = $_GET['h'];

$cache_dir = '../../_cache/gfx/'. $img_path.'/'.$image_x.'x'.$image_y;
 if(isset($_GET['grey']))
     $cache_dir = '../../_cache/gfx/'. $img_path.'/'.$image_x.'x'.$image_y.'grey';
$upload_dir = '../../_userfiles/'. $img_path;
//var_dump($upload_dir); var_dump($_GET); die;

if($img_path=='gfx-products-frames-thumb') {
    $img_path = 'products-frames';
    $upload_dir = '../../_video_upload/jpg/';
    $cache_dir = '../../_cache/gfx/'.$img_path.'/'.$image_x.'x'.$image_y;
}

$cache_file = $cache_dir . '/' . $img_name;

if (!($image_x==0 && $image_y==0)) {
    if (!file_exists($cache_file) || APPLICATION_ENV=='development') {
    	require_once '../../libs-site/Site/Upload/class.upload.php';
    	$upload = new Upload($upload_dir.'/'.$img_name);
        $upload->image_x = $image_x;
        $upload->image_y = $image_y;
        
    	$upload->file_safe_name=false;
        $upload->image_resize          = true;
        $upload->image_ratio_crop      = isset($_GET['crop']);
        if($upload->image_x==0) {
            $upload->image_ratio_x = true;
        }
        if($upload->image_y==0) {
            $upload->image_ratio_y = true;
        }

        $upload->file_overwrite = true;
    	$upload->file_auto_rename = false;
    	$upload->auto_create_dir = true;
    	$upload->dir_auto_chmod = 0777;
        
    	//$upload->image_ratio_no_zoom_in = true;
    	$upload->jpeg_quality = 100;
    	
        $upload->sharpen = false;
        $upload->sharpen_amount = 80;
        $upload->sharpen_radius = 0.5;
        $upload->sharpen_threshold = 3;
    	
        if(isset($_GET['watermark56x52'])) {
            $upload->mage_watermark_position = 'center';
            $upload->image_watermark = 'watermark56x52.png';
        } else if(isset($_GET['watermark236x59'])) {
            $upload->mage_watermark_position = 'center';
            $upload->image_watermark = 'watermark236x59.png';
        }

        if(isset($_GET['grey']))
            $upload->image_greyscale = true;
        
        $upload->process($cache_dir);
		//exit($upload->log);
    }
} else {
    $cache_dir = $upload_dir;
    $cache_file = $cache_dir . '/' . $img_name;
}

$lastModified = filemtime($cache_file);
$eTag = md5_file($cache_file);
header("Content-Length: ".filesize($cache_file));
header('Content-Type: '.getMimeType($cache_file));
header("Cache-Control: private");
header("Pragma: ");
header("Expires: ");
header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModified)." GMT");
header("ETag: $eTag"); 

if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModified || 
	trim(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH']) == $eTag) {
    header("HTTP/1.1 304 Not Modified");
    exit;
}

readfile($cache_file);

function getMimeType($file_path)
{
	$mtype = '';
	if (function_exists('mime_content_type')){
    	     $mtype = mime_content_type($file_path);
  	}
	else if (function_exists('finfo_file')){
    	     $finfo = finfo_open(FILEINFO_MIME);
    	     $mtype = finfo_file($finfo, $file_path);
    	     finfo_close($finfo);  
  	}
  	$info = getimagesize($file_path);
    $mtype = $info['mime'];
    
	if ($mtype == ''){
    	     $mtype = "application/force-download";
  	}
	return $mtype;
}
 

?>
