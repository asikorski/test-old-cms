<?php

/**
 * @author: Arnold Sikorski
 *
 * buforuje plik do pozniejszego zapisu
 *

 *
 * @example
 * htaccess:
 * RewriteRule ^download/(.*) getfile.php?query=$1 [L]
 *
 */
function base64url_decode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

// odczytuje nazwe i sciezke pliku
$query = $_GET['query'];
$filesFolder = '../../_files/';
// odczytuje nazwe pliku
if ($query) {
    $filePath = base64url_decode($query);
} else {
    exit();
}

//$sFileName = preg_match_all("/(.*)(\/)(.*)/", $filePath, $aMatches);


$sFileName = preg_match_all("/(.*)(\/)(.*)/", $filePath, $aMatches);
$sFilePath = $filesFolder.$filePath;
// sprawdzam czy plik istnieje
if (file_exists($sFilePath) && array_key_exists(3, $aMatches)) {
    //echo "otwieram";
    // otwieram plik
    $fHandler = fopen($sFilePath, "r");
    // sprawdzam wielkosc pliku
    $iFileSize = filesize($sFilePath);
    // buforuje plik
    $mBufor = fread($fHandler, $iFileSize);
    // nazwa pliku
    //var_dump($aMatches);
    $sFileName = $aMatches[3][0];
    //echo $sFileName;
    //die;
    // dodaje nagłówki
    header("Cache-control: private");
    header("Content-type: application/octet-stream\n");
    header("Content-disposition: attachment; filename=\"" . $sFileName . "\"\n");
    header("Content-transfer-encoding: binary\n");
    header("Content-length: " . $iFileSize . "\n");

    // plik do zapisu/otwarcia
    print($mBufor);

    // zamykam uchwyt do pliku
    fclose($fHandler);

    exit();
} else {
    exit();
}
?>