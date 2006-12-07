<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * japa configuration class
 *
 */

class JapaConfig
{
     /**
     * Japa main configuration array
     * @var array $config
     */
    private $config;   
    
     /**
     * Japa override array
     * vars defined can be assigned only once
     * @var array $override
     */
    private $override = array();

    /**
     * constructor
     *
     * @param mixed $data Data passed to the constructor
     */
    public function __construct( & $config )
    {
        $this->config = & $config;
    }

    /**
     * set global config var
     *
     * @param string $name Var name
     * @param mixed $value Var value
     * @param bool $override if false the var can be assigned only once
     * @return null if the var cant be assigned
     */
    public function setVar( $name, $value, $override = true )
    {
        if(isset($this->override[$name]))
        {
            return null;
        }
        else
        {
            if($override == false)
            {
                $this->override[$name] = true;
            }
            $this->config[$name] = $value;
        }   
    }
    
    /**
     * validate the action request
     *
     * @param mixed $data
     */
    public function getVar( $name )
    {
        if(!isset($this->config[$name]))
        {
            return null;
        }
        else
        {
            return $this->config[$name];
        }
    }
    
    /**
     * validate the action request
     *
     * @param mixed $data
     */
    public function deleteVar( $name )
    {
        if(isset($this->override[$name]))
        {
            return null;
        }
        else
        {
            unset($this->config[$name]);
            return true;
        }  
    }
    
    /**
     * set module specific config var
     *
     * @param string $module Module name
     * @param string $name Var name
     * @param mixed $value Var value
     * @param bool $override if false the var can be assigned only once
     * @return null if the var cant be assigned
     */
    public function setModuleVar( $module, $name, $value, $override = true )
    {
        if(isset($this->override[$module][$name]))
        {
            return null;
        }
        else
        {
            if($override == false)
            {
                $this->override[$module][$name] = true;
            }
            $this->config[$module][$name] = $value;
        }
    }
    
    /**
     * set module specific config array
     *
     * @param string $module Module name
     * @param array $value Var value
     * @param bool $override if false the var can be assigned only once
     * @return null if the var cant be assigned
     */
    public function setModuleArray( $module, $value, $override = true )
    {
        if(isset($this->override[$module]))
        {
            return null;
        }
        else
        {
            if($override == false)
            {
                $this->override[$module] = true;
            }
            $this->config[$module] = $value;
        }
    }
    
    /**
     * validate the action request
     *
     * @param mixed $data
     */
    public function deleteModuleVar( $module, $name )
    {
        if(isset($this->override[$module][$name]))
        {
            return null;
        }
        else
        {
            unset($this->config[$module][$name]);
            return true;
        }  
    }
    
    /**
     * get module specific config var
     *
     * @param string $module Module name
     * @param string $name Var name
     * @return mixed NULL if the var dosent exists else the var value
     */
    public function getModuleVar( $module, $name )
    {
        if(!isset($this->config[$module][$name]))
        {
            return null;
        }
        else
        {
            return $this->config[$module][$name];
        }  
    }
    
    /**
     * get module specific config var
     *
     * @param string $module Module name
     * @param string $name Var name
     * @return mixed NULL if the var dosent exists else the var value
     */
    public function getModuleArray( $module )
    {
        if(!isset($this->config[$module]))
        {
            return null;
        }
        else
        {
            return $this->config[$module];
        }  
    }
    
    /**
     * dump config vars
     *
     * @param string $module Module name
     * @return array 
     */
    public function dump( $module = false )
    {
        if( ($module !== false) && isset($this->config[$module]) )
        {
            return var_export($this->config[$module]);
        }
        else
        {
            return var_export($this->config);
        }  
    }
}

?>