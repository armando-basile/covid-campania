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
        $GLOBALS["htmltitle"] = "Home Page";
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
        global $dpc_regioni;
                
        $html = $this->template;
        $content = $this->home_content;
        $labelName = "denominazione_regione";
        $lableValue = "Campania";
        $fieldName = "nuovi_attualmente_positivi";
        
        $pdcExtractor = new PDCExtractor();
        $pdcData = $pdcExtractor->GetData($dpc_regioni, $labelName, $lableValue, $fieldName);
        
        // update html code with dynamic data
        $content = str_replace("{DATA}", implode(", ", $pdcData->data), $content);
        $content = str_replace("{LABELS}", implode(", ", $pdcData->labels), $content);
        
        $html = str_replace("{PAGE-CONTENT}", $content, $html);
        
        return $html;
    }



}
