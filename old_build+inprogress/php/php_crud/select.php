<?php
$dir    = 'files';
$files  = scandir($dir);
// $files2 = scandir($dir, 1);

// print_r($files1);
// print_r($files2);

$selectContent = "<option></option>";
for ($i=2; $i < count($files); $i++) {
    $selectContent .= "<option>" . $files[$i] . "</option>";
}

echo $selectContent;

// echo count($files);
// echo $files[11];
?>
