<?php

include_once ROOTPATH . "/core/CsvData.php";
include_once ROOTPATH . "/core/PDCExtractor.php";


class Regional_trend
{

    private $template = null;
    private $home_content = null;
    
    /**
     * Class constructor
     */
    function __construct()
    {
        $GLOBALS["htmltitle"] = "Andamento regionale";
        $this->template = file_get_contents(ROOTPATH . "/layouts/main_page.html");
        $this->home_content = file_get_contents(ROOTPATH . "/layouts/regional_trend.html");
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
        $pdcData = new CsvData();
        $pdcData = $pdcExtractor->GetData($local_regioni, false, $lableValue);
        
        // update html code with dynamic data
        $content = str_replace("{LABELS}", implode(", ", $pdcData->labels), $content);
        $content = str_replace("{DATA1}", implode(", ", $pdcData->totale_positivi), $content);
        $content = str_replace("{DATA2}", implode(", ", $pdcData->dimessi), $content);
        $content = str_replace("{DATA3}", implode(", ", $pdcData->deceduti), $content);
                
        $html = str_replace("{PAGE-CONTENT}", $content, $html);
        
        return $html;
    }



}

