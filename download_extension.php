<?php
// download_extension.php
// Zips TN_EXTENSION_FOLDER on the fly and forces a download.
// Requires the PHP ZipArchive extension (enabled by default in XAMPP —
// if not, uncomment extension=zip in php.ini and restart Apache).

define('TN_EXTENSION_FOLDER', __DIR__ . '/extensions');
define('TN_ZIP_NAME', 'bharat-trustnet-extension.zip');

function tn_find_manifest_dir($base) {
    if (!is_dir($base)) {
        return null;
    }
    if (file_exists($base . '/manifest.json')) {
        return $base;
    }
    foreach (glob($base . '/*', GLOB_ONLYDIR) as $sub) {
        if (file_exists($sub . '/manifest.json')) {
            return $sub;
        }
    }
    return null;
}

$tn_manifest_dir = tn_find_manifest_dir(TN_EXTENSION_FOLDER);

if ($tn_manifest_dir === null) {
    http_response_code(404);
    die('Extension folder (with manifest.json) not found inside: ' . TN_EXTENSION_FOLDER);
}

if (!class_exists('ZipArchive')) {
    http_response_code(500);
    die('PHP ZipArchive extension is not enabled. In XAMPP, open php.ini, uncomment ";extension=zip" to "extension=zip", then restart Apache.');
}

$zipPath = sys_get_temp_dir() . '/' . TN_ZIP_NAME;

$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    http_response_code(500);
    die('Could not create zip file.');
}

$sourceFolder = realpath($tn_manifest_dir);
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($sourceFolder, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $file) {
    if ($file->isDir()) {
        continue;
    }
    $filePath = $file->getRealPath();
    $relativePath = basename($sourceFolder) . '/' . substr($filePath, strlen($sourceFolder) + 1);
    $zip->addFile($filePath, $relativePath);
}

$zip->close();

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . TN_ZIP_NAME . '"');
header('Content-Length: ' . filesize($zipPath));
header('Cache-Control: no-cache, must-revalidate');
readfile($zipPath);

unlink($zipPath);
exit;