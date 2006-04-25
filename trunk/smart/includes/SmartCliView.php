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
 * Parent class of every view class
 *
 */

class SmartCliView extends SmartObject
{
    /**
     * View variable container
     * @var object $viewVar
     */
    public $viewVar = FALSE;

    /**
     * The model object
     * @var object $model
     */
    public $model;

    /**
     * The session object
     * @var object $session
     */
    public $session;
    
     /**
     * Smart main configuration array
     * @var array $config
     */
    public $config;   

    /**
     * Cli arguments
     * @var object $args
     */
    protected $args;

     /**
     * Default error view
     * @var string $error_view
     */
    public $errorView = 'cliError';

     /**
     * Template folder
     * @var bool $template_folder
     */
    public $templateFolder = FALSE;

     /**
     * Data container passed to the view
     * @var mixed $view_data
     */
    public $viewData = FALSE;

    /**
     * constructor
     *
     */
    public function __construct()
    {
        $console_config = $this->getConfigArray();
        
        $configArray = array();
        
        // cli view argument definition
        $configArray['view'] = array('short' => 'v',
                                     'min'   => 1,
                                     'max'   => 1,
                                     'desc'  => 'Set the view to execute.'
                                      );    
                                      
        $console_config = array_merge($configArray, $console_config );
        
        $this->args = & Console_Getargs::factory( $console_config );

        if (PEAR::isError($this->args)) 
        {
            $mes = '';
            if ($this->args->getCode() === CONSOLE_GETARGS_ERROR_USER) 
            {
                $mes = Console_Getargs::getHelp($console_config, null, $this->args->getMessage())."\n\n";
            } 
            else if ($this->args->getCode() === CONSOLE_GETARGS_HELP)
            {
                $mes = Console_Getargs::getHelp($console_config)."\n\n";
            }
            $this->printError( $mes );
        }    
    }

    /**
     * perform
     *
     */
    public function perform()
    {
    }

    /**
     * authentication
     *
     */
    public function auth()
    {
    }

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
    }
    
    /**
     * print error
     *
     */    
    protected function printError( $message)
    {
        // print error message
        fwrite(STDERR, $message, strlen($message));
        exit;       
    }
}

?>
