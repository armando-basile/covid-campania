<?php

include "conf/config.php";
include ROOTPATH . "/core/Base.php";

// set start time 
$time = explode(' ', microtime());
$start_time = $time[1] + $time[0];

// set title empty
$htmltitle = "";

# open session
session_start();
        
// init core controller class and generate response code
$corepage = new Base();
$htmlpage = $corepage->GetResponseCode();

// update generation time
$ftime = explode(' ', microtime());
$finish_time = $ftime[1] + $ftime[0];


// update generation time
$total_time = round(($finish_time - $start_time), 4);

// update page with dynamic data
$htmlpage_final = str_replace("{GENERATION-TIME}", $total_time, $htmlpage);
$htmlpage_final = str_replace("{RELEASE}", APP_REL, $htmlpage_final);
$htmlpage_final = str_replace("{BS-THEME}", $bs_theme, $htmlpage_final);
$htmlpage_final = str_replace("{PAGE-TITLE}", $htmltitle, $htmlpage_final);

// send generated code to web browser
echo $htmlpage_final;