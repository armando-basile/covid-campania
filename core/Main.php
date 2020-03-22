<?php




class Main
{

    private $template = null;
    
    /**
     * Class constructor
     */
    function __construct()
    {
        $GLOBALS["htmltitle"] = "Home Page";
        $this->template = file_get_contents(ROOTPATH . "/layouts/main_page.html");
    }

    /**
     * Get html code
     * 
     * @return string 
     */
    public function GetResponseCode()
    {
        
        return $this->template;
    }



}
