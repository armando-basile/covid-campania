<?php

include_once ROOTPATH . "/core/CsvData.php";
include_once ROOTPATH . "/core/PDCExtractor.php";


class Main
{

    private $template = null;
    private $home_content = null;
    
    /**
     * Class constructor
     */
    function __construct()
    {
        $GLOBALS["htmltitle"] = "Campania - Nuovi Positivi";
        $this->template = file_get_contents(ROOTPATH . "/layouts/main_page.html");
        $this->home_content = file_get_contents(ROOTPATH . "/layouts/home_content.html");
    }

    /**
     * Get html code
     * 
     * @return string 
     */
    public function GetResponseCode()
    {
        global $local_regioni;
                
        $html = $this->template;
        $content = $this->home_content;
        $lableValue = "Campania";
        
        $pdcExtractor = new PDCExtractor();        
        $pdcData = $pdcExtractor->GetData($local_regioni, false, $lableValue);
        
        // update html code with dynamic data
        $content = str_replace("{LABELS}", implode(", ", $pdcData->labels), $content);
        $content = str_replace("{DATA}", implode(", ", $pdcData->nuovi_positivi), $content);
        $content = str_replace("{LABEL-VALUE}", "Nuovi positivi", $content);
        $content = str_replace("{TOP-LABEL}", "Campania: Nuovi Positivi", $content);
                
        $html = str_replace("{PAGE-CONTENT}", $content, $html);
        
        return $html;
    }



}
