<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * Abstract action class from which all actions extends
 *
 */

abstract class JapaAction
{
    /**
     * Data passed to the Constructor
     * @var mixed $constructorData
     */
    public $constructorData;
    
    /**
     * Japa Model
     * @var object $model
     */
    public $model;    

     /**
     * Japa main configuration array
     * @var array $config
     */
    public $config;   

    /**
     * constructor
     *
     * @param mixed $data Data passed to the constructor
     */
    public function __construct( $data = false )
    {
        $this->constructorData = & $data;
    }

    /**
     * validate the action request
     *
     * @param mixed $data
     */
    abstract protected function validate( $data = false );

    /**
     * perform on the action request
     *
     * @param mixed $data
     */
    abstract protected function perform( $data = false );
}

?>