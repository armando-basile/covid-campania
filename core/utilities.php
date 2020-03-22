<?php

/**
 * Utility functions
 */
class utilities 
{
   /**
    * Get empty or POST var content
    * 
    * @param string $postName
    * @return string
    */
   public static function PostVal($postName)
   {
       $fi = filter_input(INPUT_POST, $postName);
       // check for presence or not
       if (($fi !== null) && !empty($fi))
       {
           // there is a content
           return trim($fi);
       }
       else
       {
           // empty or null
           return "";
       }
   }

   /**
    * Get empty or GET var content
    * 
    * @param string $getName
    * @return string
    */
   public static function GetVal($getName)
   {
       $fi = filter_input(INPUT_GET, $getName);
       // check for presence or not
       if (($fi !== null) && !empty($fi))
       {
           // there is a content
           return trim($fi);
       }
       else
       {
           // empty or null
           return "";
       }
   }

   
   
    
    /**
     * Generate sql code for timestamp update
     * 
     * @param string $sDate date time string formatted using GG/MM/YYYY HH:MM
     * @return string sql text formatted for datetime field
     */
    public static function GetDateFromString($sDate)
    {
        if ($sDate === "")
        {
            return "NULL";
        }
        else
        {
            $sDate = trim($sDate);
            if (strlen($sDate) < 12) { $sDate .= " 00:00"; }

            // DEBUG
            // echo("<!-- data: " . $sDate . " -->\r\n");
            $tmpDate = DateTime::createFromFormat("j/m/Y H:i", $sDate);
            return "'" . $tmpDate->format('Y-m-d H:i:s') . "'";
        }
    }

    
    /**
     * Get string for date time using format GG/MM/YYYY HH:MM
     * 
     * @param DateTime $oDate
     * @param int $full 0 only date, 1 date and time
     * @return string
     */
    public static function GetStringFromDate($oDate, $full = 0)
    {
        if ($oDate === NULL)
        {
            return "";
        }
        else
        {
            $actDate = new DateTime($oDate);
            if ($full === 1)
            {
                return $actDate->format('d/m/Y H:i');
            }
            else
            {
                return $actDate->format('d/m/Y');
            }
        }
    }

    
    /**
     * Detect if string is null or empty value
     * 
     * @param mixed $question object to parse
     * @return bool true if null or empty string
     */
    public static function IsNullOrEmpty($question)
    {
        if (!isset($question)) 
        { 
            return TRUE; 
        }
        
        if (is_null($question)) 
        { 
            return TRUE; 
        }
        
        if (trim($question)=='') 
        { 
            return TRUE; 
        }
        
        return FALSE;
    }
    
    
    /**
     * Remove separator charts at end of string list
     * 
     * @param string $strlist
     * @param string $schar
     * @return string
     */
    public static function RemoveFinalSeparator($strlist, $schar)
    {
        $tmp = $strlist;
        if (!utilities::IsNullOrEmpty($tmp))
        {
            if (substr($tmp, -1) == $schar)
            {
                $tmp = substr($tmp, 0, -1);
            }
        }
        
        return $tmp;
    }
    
    
    /**
     * Return value if not empty, otherwise return def value
     * 
     * @param var $value_to_try value to parse and use if not empty
     * @param var $def_value value to use if $value_to_try is empty
     * @return var Not empty value
     */
    public static function RetNotEmpty($value_to_try, $def_value)
    {
        if (!utilities::IsNullOrEmpty($value_to_try))
        {
            return $value_to_try;
        }
        else
        {
            return $def_value;
        }
    }
    
    public static function GetNotEmpty($get_var, $def_value)
    {
        return self::RetNotEmpty(self::GetVal($get_var) , $def_value);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public static function GetErrorLevel($intval, $separator = ',')
    {
        $errorlevels = array(
            E_ALL => 'E_ALL',
            E_USER_DEPRECATED => 'E_USER_DEPRECATED',
            E_DEPRECATED => 'E_DEPRECATED',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_STRICT => 'E_STRICT',
            E_USER_NOTICE => 'E_USER_NOTICE',
            E_USER_WARNING => 'E_USER_WARNING',
            E_USER_ERROR => 'E_USER_ERROR',
            E_COMPILE_WARNING => 'E_COMPILE_WARNING',
            E_COMPILE_ERROR => 'E_COMPILE_ERROR',
            E_CORE_WARNING => 'E_CORE_WARNING',
            E_CORE_ERROR => 'E_CORE_ERROR',
            E_NOTICE => 'E_NOTICE',
            E_PARSE => 'E_PARSE',
            E_WARNING => 'E_WARNING',
            E_ERROR => 'E_ERROR');
        $result = '';
        foreach($errorlevels as $number => $name)
        {
            if (($intval & $number) == $number) 
            {
                $result .= ($result != '' ? $separator : '').$name; 
            }
        }
        return $result;
    }
    
    
    
    public static function GetNewLocationScript($relativePath)
    {
        return "<!DOCTYPE html>
        <html>
            <head>
                <title>...</title>
            </head>
            <body>
                <script>
                    window.location.href='" . $relativePath . "';
                </script>
            </body>
        </html>
        ";
    }
    
    
    public static function GetWeekDay($dateIn)
    {
        $dayNum = date("N", strtotime($dateIn));
        
        if ($dayNum == 1) {
            return "Lunedì";
        } elseif ($dayNum == 2) {
            return "Martedì";
        } elseif ($dayNum == 3) {
            return "Mercoledì";
        } elseif ($dayNum == 4) {
            return "Giovedì";
        } elseif ($dayNum == 5) {
            return "Venerdì";
        } elseif ($dayNum == 6) {
            return "Sabato";
        } elseif ($dayNum == 7) {
            return "Domenica";
        }
    }
    
    
    public static function IsDayInMonth($day, $month, $year)
    {
        $actDate = new DateTime($year."-".$month."-01");
        $lastDay = $actDate->format("t");
        if ($day > $lastDay)
        {
            return FALSE;
        }
        return TRUE;
        
    }
    
    public static function GetMonthFromNumber($monthNumber)
    {
        if ($monthNumber == 1) {
            return "Gennaio";
        } elseif ($monthNumber == 2) {
            return "Febbraio";
        } elseif ($monthNumber == 3) {
            return "Marzo";
        } elseif ($monthNumber == 4) {
            return "Aprile";
        } elseif ($monthNumber == 5) {
            return "Maggio";
        } elseif ($monthNumber == 6) {
            return "Giugno";
        } elseif ($monthNumber == 7) {
            return "Luglio";
        } elseif ($monthNumber == 8) {
            return "Agosto";
        } elseif ($monthNumber == 9) {
            return "Settembre";
        } elseif ($monthNumber == 10) {
            return "Ottobre";
        } elseif ($monthNumber == 11) {
            return "Novembre";
        } elseif ($monthNumber == 12) {
            return "Dicembre";
        }
    }
    

    
}
