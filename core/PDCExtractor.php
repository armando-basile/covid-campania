<?php

include_once ROOTPATH . "/core/CsvData.php";

class PDCExtractor {
    
    private $outData = null;
    
    private $labelPos = null;
    private $fieldPos = null;
    
    /**
     * Constructor
     */
    function __construct() {
        
         $this->outData = new CsvData();
         $this->outData->labels = [];
         $this->outData->data = [];
    }
    
    
    public function GetData($url, $labelName, $lableValue, $fieldName) {
        
        global $dpc_start_date, $dpc_end_date;
        $csvContent = null;
        
        $start_date = $dpc_start_date;
        
        while (strtotime($start_date) <= strtotime($dpc_end_date)) {
            
            // Prepare url to get csv
            $urlDate = str_replace("-", "", $start_date);
            $fullUrl = $url . $urlDate . ".csv";
            
            try {
                // try to get data from source
                $this->GetDataFromCsv($fullUrl, $start_date, $labelName, $lableValue, $fieldName);
            
            } catch (Exception $Ex) {
                // error occurred during get data
                echo "<!-- ERROR: " . $Ex->getMessage() . " -->\n";
            }

            // update new data to scan
            $start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
        }
        
        return $this->outData;
        
    }
    
    
    
    private function GetDataFromCsv($url, $day, $labelName, $lableValue, $fieldName) {
        
        echo "<!-- url: " . $url . " -->\n";
        
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
        
        $posLabel = array_search($labelName, $headers);
        $posField = array_search($fieldName, $headers);
        
        // extract data from csv
        for ($i=1; $i<count($lines); $i++) {
            $line = $lines[$i];
            
            // check for empty lines
            if (trim($line) !== "") {                
                $fields = explode(",", $line);
                
                $lineLabel = $fields[$posLabel];
                $lineFiels = $fields[$posField];
                
                if (trim($lineLabel) === $lableValue) {
                    $dayLabel = (new DateTime($day))->format("d/m");
                    array_push($this->outData->labels, "\"" . $dayLabel . "\"");
                    array_push($this->outData->data, "\"" . $lineFiels . "\"");
                }
            }            
        }
        
    }
    
}
