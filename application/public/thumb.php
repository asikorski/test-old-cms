<?php
/**
 * @author: Arnold Sikorski
 *
 * Wyświetlanie obrazków
 * Wpis do httpacces:
 * RewriteRule ^gfx/(.*) thumb.php?query=$1 [L]
 */
if (!empty($_GET['query'])) {

    date_default_timezone_set('Europe/Warsaw');
    $cacheFolder = '../../_cache/gfx';
    $file = $cacheFolder . '/' . $_GET['query'];
    if (!is_dir($cacheFolder)) {
        echo("Can't find files dir</br>");
        echo("Create...</br>");
        if (!mkdir($cacheDir, 0777)) {
            echo("Can't create cache dir</br>");
        }
    } else {
        if (file_exists($file)) {
            /* Generowanie pliku */
            $lastModified = filemtime($file);
            $eTag = md5_file($file);
            header("Content-Length: " . filesize($file));
            header('Content-Type: ' . getMimeType($file));
            header("Cache-Control: private");
            header("Pragma: ");
            header("Expires: ");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModified) . " GMT");
            header("ETag: $eTag");


            readfile($file);
            /* Otwieramy plik */
        } else {
            header('HTTP/1.1 404 Not Found');
        }
    }
} else {
    header('HTTP/1.1 404 Not Found');
}

function getMimeType($file_path) {
    $mtype = '';
    if (function_exists('mime_content_type')) {
        $mtype = mime_content_type($file_path);
    } else if (function_exists('finfo_file')) {
        $finfo = finfo_open(FILEINFO_MIME);
        $mtype = finfo_file($finfo, $file_path);
        finfo_close($finfo);
    }
    $info = getimagesize($file_path);
    $mtype = $info['mime'];

    if ($mtype == '') {
        $mtype = "application/force-download";
    }
    return $mtype;
}

?>