<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------
 
 class JapaViewHelper
 {
	/**
	 * Every helper class extends this class and needs this constructor
	 */
  	public function __construct( $args = false, $config = false )
  	{
  		$this->args   = & $args;
  		$this->config = $config;
  	}
  	
    /**
     * dynamic call of public controller objects
     *
     * @param string $helpername helper name
     * @param array $args Arguments passed to the helper class. 
     * @return mixed helper result
     */
    public function __call( $helpername, $args )
    {
    	// get helper instance
      	$view_helper = $this->getHelperInstance( $helpername, $args  );
      	// execute the helper main methode
      	return $view_helper->perform();
    }
    
    /**
     * return view helper instance
     *
     * @param string $class_name helper class name
     * @param mixed $data Data passed to the helper constructor if any
     */ 
    protected function getHelperInstance( & $class_name, & $args )
    {       
        if( !isset($this->viewHelper[$class_name]) )
        {
            // build the whole view helper class name
            $requested_helper = 'ViewHelper' . ucfirst($class_name);
            
            // path to the view helper class
            $class_file = JAPA_APPLICATION_DIR . 'view_helper/' . $requested_helper . '.php';

            if(@file_exists($class_file))
            {
                include_once($class_file);

                // make instance of the view helper class
                $this->viewHelper[$class_name] = new $requested_helper( $args, $this->config );
                
            }
            else
            {
                throw new JapaViewException("View helper dosent exists: ".$class_file);
            }          
        }

        return $this->viewHelper[$class_name]; 
    }
 }
 
?>
