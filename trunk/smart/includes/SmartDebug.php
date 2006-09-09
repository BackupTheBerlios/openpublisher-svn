<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * debugging class
 *
 */

class SmartDebug
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
    public function smartVarDump( $methode )
    {
        $this->buildDebugArray();

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
                $this->debugValue['debugPoint'][$pointName]['memory'] = $this->getMemoryUsage();
            }
            
            if(($this->config['debugGetNumQueries'] == true) && 
                isset($this->model->dba))
            {
                $this->debugValue['debugPoint'][$pointName]['numQueries'] = count($this->model->dba->stat['query']);
            }
        
            if($data != null)
            {
                $this->debugValue['debugPoint'][$pointName]['data'] = $data;
            }
        
            return true;
        }    
        return false;
    }
    
    private function buildDebugArray()
    {
            if(($this->config['debugGetQueries'] == true) && 
                isset($this->model->dba))
            {
                $this->debugValue['debugPoint'][$pointName]['queries'] = & $this->model->dba->stat['query'];
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
        error_log($message.print_r($this->debugValue, true)."\n\n", 3, $this->config['logs_path'] . 'smart_debug.log');
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
	    echo "smart_debug = window.open('','debug','width=680,height=600,resizable,scrollbars=yes');\n";
	    echo "smart_debug.document.write('<HTML><HEAD><TITLE>Smart3 Debug Console_'+self.name+'</TITLE></HEAD><BODY bgcolor=#ffffff>');\n";
	    echo "smart_debug.document.write('<pre id=\"debug\">');\n";
	    echo "smart_debug.document.write('".$this->buildJavascriptOutput()."');\n";
	    echo "smart_debug.document.write('</pre>');\n";
        echo "smart_debug.document.write('</BODY></HTML>');\n";
        echo "</SCRIPT>\n";
    }
    
    private function buildJavascriptOutput()
    {
        return strtr(htmlentities(var_export($this->debugValue, true)), array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));
    }
}

?>