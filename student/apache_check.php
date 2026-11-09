<?php

echo "PHP Version: " . PHP_VERSION . "<br>";

echo "Loaded php.ini: " . php_ini_loaded_file() . "<br>";

echo "cURL Status: ";

if (extension_loaded('curl')) {
    echo "ENABLED<br>";
    echo "cURL Version: " . curl_version()['version'];
} else {
    echo "DISABLED";
}

?>