<?php
require "lessc.inc.php";
$less = new lessc;
if (!file_exists($paths->stylesOutput)) {
    mkdir(preg_replace(["/[^\/]+$/",], "", $paths->stylesOutput), 0777, true);
    touch($paths->stylesOutput, 0);
}
try {
    $less->checkedCompile(jPath($paths->stylesInput), $paths->stylesOutput);
} catch (\Throwable $th) {
    echo "Error during less > css conversion" . $th;
}
