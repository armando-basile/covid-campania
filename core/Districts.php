<?php

include_once ROOTPATH . "/core/CsvData.php";
include_once ROOTPATH . "/core/PDCExtractor.php";


class Districts
{

    private $template = null;
    private $home_content = null;
    
    /**
     * Class constructor
     */
    function __construct()
    {
        $GLOBALS["htmltitle"] = "Totale casi per provincia";
        $this->template = file_get_contents(ROOTPATH . "/layouts/main_page.html");
        $this->home_content = file_get_contents(ROOTPATH . "/layouts/districts.html");
    }

    /**
     * Get html code
     * 
     * @return string 
     */
    public function GetResponseCode()
    {
        global $local_province;
                
        $html = $this->template;
        $content = $this->home_content;
        
        $pdcExtractor = new PDCExtractor();        
        $pdcData = $pdcExtractor->GetDistrictsData($local_province);
        
        $labels = [];
        $NA = [];
        $CE = [];
        $SA = [];
        $AV = [];
        $BN = [];
        
        foreach ($pdcData as $item) {
            array_push($labels, $item->label); 
            array_push($NA, $item->NA); 
            array_push($CE, $item->CE); 
            array_push($SA, $item->SA); 
            array_push($AV, $item->AV); 
            array_push($BN, $item->BN); 
        }
        
        // update html code with dynamic data
        $content = str_replace("{LABELS}", implode(", ", $labels), $content);
        $content = str_replace("{DATA1}", implode(", ", $NA), $content);
        $content = str_replace("{DATA2}", implode(", ", $CE), $content);
        $content = str_replace("{DATA3}", implode(", ", $SA), $content);
        $content = str_replace("{DATA4}", implode(", ", $AV), $content);
        $content = str_replace("{DATA5}", implode(", ", $BN), $content);
                
        $html = str_replace("{PAGE-CONTENT}", $content, $html);
        
        return $html;
    }



}

