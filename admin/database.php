<?php
if (!headers_sent()) {
    foreach (headers_list() as $header)
        header_remove($header);
}
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($db_file));
readfile($db_file);
exit;