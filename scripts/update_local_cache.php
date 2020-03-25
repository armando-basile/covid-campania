<?php

$actualPath = "";

if (isset($argc)) {
	$actualPath = dirname($argv[0]);
}

if ($actualPath === "") {
    return;
}

include $actualPath . "/../conf/config.php";

$end_date = $dpc_end_date;
$start_date = date ("Y-m-d", strtotime("-1 day", strtotime($dpc_end_date)));

$urlR = $dpc_regioni;
$urlP = $dpc_province;

function url_exists($url) {
    $file_headers = @get_headers($url);
    if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
        return false;
    } else {
        return true;
    }
}

//echo "<!-- process from $dpc_start_date to $end_date -->\n";

while (strtotime($end_date) >= strtotime($start_date)) {
            
    // Prepare url to get csv
    $urlDate = str_replace("-", "", $end_date);
    $fullUrlR = $urlR . $urlDate . ".csv";
    $fullUrlP = $urlP . $urlDate . ".csv";
    $fullPathR = $actualPath . "/../dpcdata/regioni/$urlDate.csv";
    $fullPathP = $actualPath . "/../dpcdata/province/$urlDate.csv";
    
    try {
        if (url_exists($fullUrlR)) {
            // try to get data from source
            file_put_contents($fullPathR, fopen($fullUrlR, 'r'));
        }

    } catch (Exception $Ex) {
        // error occurred during get data
        echo "<!-- ERROR ON GET REGIONI $urlDate: " . $Ex->getMessage() . " -->\n";
    }

    if ((file_exists($fullPathR)) && (filesize($fullPathR) < 60)) {
        unlink($fullPathR);
    }
    
    try {
        if (url_exists($fullUrlR)) {
            // try to get data from source
            file_put_contents($fullPathP, fopen($fullUrlP, 'r'));
        }

    } catch (Exception $Ex) {
        // error occurred during get data
        echo "<!-- ERROR ON GET PROVINCE $urlDate: " . $Ex->getMessage() . " -->\n";
    }
    
    if ((file_exists($fullPathP)) && (filesize($fullPathP) < 60)) {
        unlink($fullPathP);
    }
    
    // update new data to scan
    $end_date = date ("Y-m-d", strtotime("-1 day", strtotime($end_date)));
}