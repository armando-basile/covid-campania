<?php

include_once ROOTPATH . "/core/CsvData.php";
include_once ROOTPATH . "/core/DistrictsData.php";

class PDCExtractor {
    
    private $outData = null;    
    private $outDistricts = null;   
    private $firstDay = true;
    private $totalTamponi = 0;
    
    private $lblRegione        = "denominazione_regione";
    private $lblProvincia      = "denominazione_provincia";
    private $lblTotPositivi    = "totale_attualmente_positivi";
    private $lblTotCasi        = "totale_casi";
    private $lblTamponi        = "tamponi";
    private $lblNuoviPositivi  = "nuovi_attualmente_positivi";
    private $lblDeceduti       = "deceduti";
    private $lblDimessi        = "dimessi_guariti";
    
    
    
    
    /**
     * Constructor
     */
    function __construct() {
        
         $this->outData = new CsvData();
         $this->outDistricts = [];
    }
    
    
    public function GetData($url, $isDistrict, $lableValue) {
        
        global $dpc_start_date, $dpc_end_date;
        $csvContent = null;
        
        $start_date = $dpc_start_date;
        $this->firstDay = true;
        
        while (strtotime($start_date) <= strtotime($dpc_end_date)) {
            
            // Prepare url to get csv
            $urlDate = str_replace("-", "", $start_date);
            $fullUrl = $url . $urlDate . ".csv";
            
            try {
                // try to get data from source
                $this->GetDataFromCsv($fullUrl, $start_date, $isDistrict, $lableValue);
            
            } catch (Exception $Ex) {
                // error occurred during get data
                echo "<!-- ERROR: " . $Ex->getMessage() . " -->\n";
            }

            // update new data to scan
            $start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
        }
        
        return $this->outData;
    }
    
    
    
    public function GetDistrictsData($url) {
        
        global $dpc_start_date, $dpc_end_date;
        $csvContent = null;
        
        $start_date = $dpc_start_date;
        
        while (strtotime($start_date) <= strtotime($dpc_end_date)) {
            
            // Prepare url to get csv
            $urlDate = str_replace("-", "", $start_date);
            $fullUrl = $url . $urlDate . ".csv";
            
            if (file_exists($fullUrl)) {            
                try {
                    // try to get data from source
                    $this->GetDistrictsDataFromCsv($fullUrl, $start_date);

                } catch (Exception $Ex) {
                    // error occurred during get data
                    echo "<!-- ERROR: " . $Ex->getMessage() . " -->\n";
                }
            }

            // update new data to scan
            $start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
        }
        
        return $this->outDistricts;
    }
    
    
    
    
    
    private function GetDataFromCsv($url, $day, $isDistrict, $lableValue) {
        
        //echo "<!-- url: " . $url . " -->\n";
        
        $posLabel = 0;
        $posTotali = 0;
        $posTamponi = 0;
        $posNuoviPositivi = 0;
        $posDeceduti = 0;
        $posDimessi = 0;
                
        if (!file_exists($url)) {           
            return;
        }
        
        try {
            // set start time 
            //$tm = explode(' ', microtime());
            //$st = $tm[1] + $tm[0];
            
            // try to get data from source
            $csvContent = file_get_contents($url);
            
            // update generation time
            //$ftm = explode(' ', microtime());
            //$ft = $ftm[1] + $ftm[0];

            // read time
            //$total_tm = round(($ft - $st), 4);
            //echo "<!-- file_get_contents($url) time: " . $total_tm . " -->\n";

        } catch (Exception $Ex) {
            // error detected
            echo "<!-- Unable to get day " . $day . ": " . $Ex->getMessage() . " -->\n";
            return;
        }

        
        // extract position for labels and data from csv header
        $lines = explode("\n", $csvContent);        
        $headers = explode(",", $lines[0]);
        
        // extract data fields position
        if ($isDistrict) {
            // Provincia
            $posLabel = array_search($this->lblProvincia, $headers);
            $posTotali = array_search($this->lblTotCasi, $headers);
            
        } else {
            // Regione
            $posLabel = array_search($this->lblRegione, $headers);
            $posTotali = array_search($this->lblTotPositivi, $headers);
            $posNuoviPositivi = array_search($this->lblNuoviPositivi, $headers);
            $posDeceduti = array_search($this->lblDeceduti, $headers);
            $posDimessi = array_search($this->lblDimessi, $headers);
            $posTamponi = array_search($this->lblTamponi, $headers);
        }
        
        // set day as label
        $dayLabel = (new DateTime($day))->format("d/m");
        
        //echo "<!-- $posLabel; $posTotali; $posNuoviPositivi; $posDeceduti; $posDimessi -->\n";
        
        // extract data from csv
        for ($i=1; $i<count($lines); $i++) {
            $line = $lines[$i];
            
            // check for empty lines
            if (trim($line) !== "") {                
                $fields = explode(",", $line);
                
                // check for specific row found
                if (trim($fields[$posLabel]) === $lableValue) {
                    // update data
                    if ($this->firstDay === false) {
                        array_push($this->outData->labels, "\"" . $dayLabel . "\"");
                        array_push($this->outData->totale_positivi, "\"" . $fields[$posTotali] . "\"");

                        // check for regional data
                        if (!$isDistrict) {
                            // Regione
                            $tamponiForDay = intval($fields[$posTamponi]) - $this->totalTamponi;
                            $this->totalTamponi = intval($fields[$posTamponi]);
                            
                            array_push($this->outData->nuovi_positivi, "\"" . $fields[$posNuoviPositivi] . "\"");
                            array_push($this->outData->dimessi, "\"" . $fields[$posDimessi] . "\"");
                            array_push($this->outData->deceduti, "\"" . $fields[$posDeceduti] . "\"");
                            array_push($this->outData->tamponi, "\"" . $tamponiForDay . "\"");                            
                        }
                        
                    } else {
                        // get only tamponi
                        $this->firstDay = false;
                        $this->totalTamponi = intval($fields[$posTamponi]);
                    }  
                }                
            }            
        }
    }
    
    
    
    private function GetDistrictsDataFromCsv($url, $day) {
        
        global $districtName;
        
        $posLabel = 0;
        $posTotali = 0;

        if (!file_exists($url)) {           
            return;
        }
        
        try {
            // try to get data from source
            $csvContent = file_get_contents($url);
        } catch (Exception $Ex) {
            // error detected
            echo "<!-- Unable to get day " . $day . ": " . $Ex->getMessage() . " -->\n";
            return;
        }
        
        // extract position for labels and data from csv header
        $lines = explode("\n", $csvContent);        
        $headers = explode(",", $lines[0]);
        
        // Provincia
        $posLabel = array_search($this->lblProvincia, $headers);
        $posTotali = array_search($this->lblTotCasi, $headers);

        // set day as label
        $dayLabel = (new DateTime($day))->format("d/m");
        
        $dayData = new DistrictsData();
        $dayData->label = "\"" . $dayLabel . "\"";
        
        // extract data from csv
        for ($i=1; $i<count($lines); $i++) {
            $line = $lines[$i];
            
            // check for empty lines
            if (trim($line) !== "") {                
                $fields = explode(",", $line);
                
                // check for some district
                if (in_array(trim($fields[$posLabel]), $districtName)) {
                    
                    if ($fields[$posLabel] === "Napoli") {
                        $dayData->NA = $fields[$posTotali];
                    } elseif ($fields[$posLabel] === "Caserta") {
                        $dayData->CE = $fields[$posTotali];
                    } elseif ($fields[$posLabel] === "Salerno") {
                        $dayData->SA = $fields[$posTotali];
                    } elseif ($fields[$posLabel] === "Avellino") {
                        $dayData->AV = $fields[$posTotali];
                    } else {
                        $dayData->BN = $fields[$posTotali];
                    }
                }                
            }            
        }
        
        array_push($this->outDistricts, $dayData);
    }
    
}
