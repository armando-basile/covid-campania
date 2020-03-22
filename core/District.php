<?php

include_once ROOTPATH . "/core/CsvData.php";
include_once ROOTPATH . "/core/PDCExtractor.php";


class District
{

    private $template = null;
    private $home_content = null;
    
    
    /**
     * Class constructor
     */
    function __construct()
    {
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
        global $dpc_provincie, $htmltitle, $districtName;
        
        // extract district to scan
        $c = utilities::GetVal("c");
        $lableValue = $districtName[$c];
        
        $htmltitle = $lableValue . " - totale casi";
        
        $html = $this->template;
        $content = $this->home_content;
        
        
        $pdcExtractor = new PDCExtractor();        
        $pdcData = new CsvData();
        $pdcData = $pdcExtractor->GetData($dpc_provincie, true, $lableValue);
        
        // update html code with dynamic data
        $content = str_replace("{LABELS}", implode(", ", $pdcData->labels), $content);
        $content = str_replace("{DATA}", implode(", ", $pdcData->totale_positivi), $content);
        $content = str_replace("{LABEL-VALUE}", "Totale casi", $content);
        $content = str_replace("{TOP-LABEL}", $lableValue . ": Totale casi", $content);
        
        $html = str_replace("{PAGE-CONTENT}", $content, $html);
        
        return $html;
    }



}

