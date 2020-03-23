<?php

// root folder for web application
define("ROOTPATH", (string)filter_input(INPUT_SERVER, "DOCUMENT_ROOT"));

// application properties
define("APP_REL", "0.2.0");
define("APP_RELDATE", "2020-03-23");
define("BAR_TITLE", "COVID CAMPANIA");

// bootstrap theme to use (https://bootswatch.com/)
$bs_theme = "darkly";

// github source Dipartimento Protezione Civile
$dpc_regioni   = "https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-regioni/dpc-covid19-ita-regioni-";
$dpc_province  = "https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-province/dpc-covid19-ita-province-";
$dpc_end_date = date("Y-m-d");
$dpc_start_date = date ("Y-m-d", strtotime("-21 day", strtotime($dpc_end_date)));

// local cache
$local_regioni = "dpcdata/regioni/";
$local_province = "dpcdata/province/";

// set timezone for application
date_default_timezone_set('Europe/Rome');

$districtName = [
    "NA" => "Napoli",
    "CE" => "Caserta",
    "SA" => "Salerno",
    "AV" => "Avellino",
    "BN" => "Benevento"
];

?>
