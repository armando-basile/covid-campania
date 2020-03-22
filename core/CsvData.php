<?php


class CsvData {
    
    public $nuovi_positivi = null;
    public $totale_positivi = null;
    public $deceduti = null;
    public $dimessi = null;
    public $labels = null;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->nuovi_positivi = [];
        $this->totale_positivi = [];
        $this->deceduti = [];
        $this->dimessi = [];
        $this->labels = [];
    }
}
