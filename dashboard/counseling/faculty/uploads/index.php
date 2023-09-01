<?php
$requested_file = $_SERVER['REQUEST_URI'];
$redirected_file = str_replace('/admin/', '/uploads/', $requested_file);
if (file_exists($_SERVER['DOCUMENT_ROOT'] . $redirected_file)) {
    header("Location: $redirected_file");
    exit();
} else {
    header("HTTP/1.0 404 Not Found");
    exit();
}
?>
