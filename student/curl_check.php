<?php
echo "PHP Version: " . PHP_VERSION . "<br>";

if (extension_loaded('curl')) {
    echo "cURL Status: ENABLED";
} else {
    echo "cURL Status: DISABLED";
}
?>