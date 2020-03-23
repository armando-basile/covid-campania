<?php

include_once ROOTPATH . "/core/utilities.php";

/**
 * Core controller class to manage and address process
 * 
 */
class Base
{



    /**
     * Get html code
     * 
     * @return string 
     */
    public function GetResponseCode()
    {
        // get page request
        $page = utilities::GetVal("p");

        // check for no page specified
        if ($page === "") {
            $page = "main";
        }
        
        $manager = null;
        
        // check for page class to use
        if ($page === "main") {
            include_once ROOTPATH . "/core/Main.php";
            $manager = new Main();
            
        } elseif ($page === "regional_trend") {
            include_once ROOTPATH . "/core/Regional_trend.php";
            $manager = new Regional_trend();  
            
        } elseif ($page === "districts") {
            include_once ROOTPATH . "/core/Districts.php";
            $manager = new Districts();  
        
        } else {
            // if page not found use main
            include_once ROOTPATH . "/core/Main.php";
            $manager = new Main();
        }
        
        return $manager->GetResponseCode();
        
    }



}

