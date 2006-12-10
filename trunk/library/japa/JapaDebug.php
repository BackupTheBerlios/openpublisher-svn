<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * debugging class
 *
 */

class JapaDebug
{
     /**
     * debug values
     * @var array $debugValue
     */ 
    private $debugValue = array();
     /**
     * start of time mesuring
     * @var int $timeStart
     */ 
    public $timeStart   = 0;
     /**
     * array of debug points
     * @var array $debugPoint
     */ 
    public $debugPoint   = array();
     
     /**
     * constructor
     */ 
    public function __construct()
    {
        // start time mesuring
        $this->timeStart = $this->microtime_float();
    }

    /**
     * handle debugging message array
     *
     * @param string $methode method that handle debugging message array
     */
    public function japaVarDump( $methode )
    {
        $this->buildFinalDebugArray();
        if(method_exists($this, $methode))
        {
            $this->$methode();
        }
    }

    /**
     * get time
     *
     * @return float
     */
    public function microtime_float() 
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
    /**
     * set debug point
     *
     * @param string $pointName 
     * @param array $location 
     * @param mixed $data
     */ 
    public function setDebugPoint( $pointName, $location, $data = null )
    {
        if(!isset($this->debugValue['debugPoint'][$pointName]))
        {
            $this->debugValue['debugPoint'][$pointName]['location'] = $location;
            
            $this->debugValue['debugPoint'][$pointName]['time'] = round($this->microtime_float()-$this->timeStart,6);
            if(function_exists('memory_get_usage'))
            {
                $this->debugValue['debugPoint'][$pointName]['memory'] = memory_get_usage ();
            }
            
            if(isset($this->model->dba))
            {
                if($this->config->getVar('debugGetNumQueries') == true)
                {
                    $this->debugValue['debugPoint'][$pointName]['numQueries'] = count($this->model->dba->stat['query']);
                }
            }
        
            if($data != null)
            {
                $this->debugValue['debugPoint'][$pointName]['data'] = $data;
            }
        
            return true;
        }    
        return false;
    }
    /**
     * build final debug array
     *
     */    
    private function buildFinalDebugArray()
    {
        if(($this->config->getVar('debugGetQueries') == true) && 
            isset($this->model->dba))
        {
            $this->debugValue['debugPoint']['queries'] = & $this->model->dba->stat['query'];
        }
    }
    
    /**
     * log debugging message array
     *
     */
    private function log()
    {
        $message  = "---------------------------------------\n";
        $message .= "--- DEBUG DATE: ".date("Y-m-d H:i:s", time())." ---\n";
        $message .= "---------------------------------------\n";
        error_log($message.print_r($this->debugValue, true)."\n\n", 3, $this->config->getVar('logs_path') . 'japa_debug.log');
    }
    /**
     * append debugging message array
     *
     */
    private function append()
    {     
        echo '<pre id="debug">';
        print_r($this->debugValue);
        echo '</pre>';
    }
    
    /**
     * open new debugging window
     *
     */
    private function newWindow()
    { 
        echo "<SCRIPT language=javascript>\n";
	    echo "japa_debug = window.open('','debug','width=680,height=600,resizable,scrollbars=yes');\n";
	    echo "japa_debug.document.write('<HTML><HEAD><TITLE>Japa Debug Console_'+self.name+'</TITLE></HEAD><BODY bgcolor=#ffffff>');\n";
	    echo "japa_debug.document.write('<h3 id=\"debug\">_debug window ".date("Y-m-d H:i:s", time())."</h3>');\n";
	    echo "japa_debug.document.write('<pre id=\"debug\">');\n";
	    echo "japa_debug.document.write('".$this->buildJavascriptOutput()."');\n";
	    echo "japa_debug.document.write('</pre>');\n";
        echo "japa_debug.document.write('</BODY></HTML>');\n";
        echo "</SCRIPT>\n";
    }
    
    private function buildJavascriptOutput()
    {
        return strtr(htmlentities(var_export($this->debugValue, true)), array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));
    }
}

?>