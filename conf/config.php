<?php

// root folder for web application
define("ROOTPATH", (string)filter_input(INPUT_SERVER, "DOCUMENT_ROOT"));

// application properties
define("APP_REL", "0.1.0");
define("APP_RELDATE", "2020-03-22");
define("BAR_TITLE", "COVID CAMPANIA");

// bootstrap theme to use (https://bootswatch.com/)
$bs_theme = "darkly";

// github source Dipartimento Protezione Civile
$dpc_regioni   = "https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-regioni/dpc-covid19-ita-regioni-";
$dpc_provincie = "https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-province/dpc-covid19-ita-province-";


// set timezone for application
date_default_timezone_set('Europe/Rome');

        
?>
