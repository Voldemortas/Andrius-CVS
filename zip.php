<?php

$zip = new ZipArchive();
$filename = "./test112.zip";

if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
    exit("cannot open <$filename>\n");
}
$zip->addFromString("testfilephp.txt" . time(), "#1 This is a test string added as testfilephp.txt.\n");
$zip->close();
?>
