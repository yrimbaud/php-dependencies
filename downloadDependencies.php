<?php
$packageName = "bitwasp/bitcoin"; // Define the package name to install

// Execute Composer to download the package and its dependencies
exec("composer require $packageName");

// Execute Composer to get the package information
$output = shell_exec("composer show $packageName --all --format=json");

// Replace forbidden characters with hyphens
$zipFileName = str_replace("/", "-", $packageName) . ".zip";

// Initialize the ZIP archive
$zip = new ZipArchive();

if ($zip->open($zipFileName, ZipArchive::CREATE) !== TRUE) {
    exit("Cannot open <$zipFileName>\n");
}

// Add the vendor directory to the ZIP
$folderToZip = "vendor";
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderToZip), RecursiveIteratorIterator::LEAVES_ONLY);

foreach ($files as $name => $file) {
    if (!$file->isDir()) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen(__DIR__) + 1);
        $zip->addFile($filePath, $relativePath);
    }
}

// Close the ZIP archive
$zip->close();

echo "The package $packageName has been downloaded and a ZIP file has been created.\n";

// Delete the vendor directory
exec("rm -rf vendor");
?>
