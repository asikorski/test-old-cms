<?php
if (strpos($_GET['file'], '..') ||
    strpos($_GET['file'], '&#2e;&#2e;') ||
    strpos($_GET['file'], '%2e%2e')) exit;

$upload_file = '../../_userfiles/'. $_GET['file'];
header("Content-Length: ".filesize($upload_file));
header('Content-Type: application/force-download');
readfile($upload_file);
