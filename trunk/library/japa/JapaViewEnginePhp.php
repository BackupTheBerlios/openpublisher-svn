<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * Php view (template) engine
 *
 * Here we use php as view language
 */
include_once( JAPA_LIBRARY_DIR . 'japa/JapaViewHelper.php' );
 
class JapaViewEnginePhp extends JapaViewEngine
{
    /**
     * Tokens found
     */                                    
    private $disallowedItems = array();
    
    protected $disallowedHelper = array('renderView','analyze',
                                        'getHelperInstance');
                                    
    /**
     * render the template
     *
     */
    public function renderView()
    {
        // get reference of the view variables
        $view = & $this->vars;
        // compatibility with smart3 
        $tpl = & $view;
        
        // view helper instance
        $helper = new JapaViewHelper;
        $helper->config = $this->config;

        // build the whole file path to the view file
        $view_file = $this->viewFolder . $this->view . '.php';

        if($this->config->getVar('useCodeAnalyzer') == true)
        {
            $this->disallowedItems = array();
            
            if(false == $this->analyze($view_file))
            {
                throw new JapaViewException("View php constructs not allowed: <pre>".var_export($this->disallowedItems,true)."<pre>");
            }
        }
        
        if ( !@file_exists( $view_file ) )
        {
            throw new JapaViewException("View dosent exists: ".$view_file);
        }

        ob_start();
        include( $view_file );
        $this->viewBufferContent = ob_get_clean();      
    } 
    
    /**
     * analyze allowed php tokens in view 
     *
     */
    private function analyze( & $view )
    {
        $code_is_valide = true;

        include_once(JAPA_LIBRARY_DIR . "japa/phpca/PHPCodeAnalyzer.php");
        $analyzer = new PHPCodeAnalyzer();
        $analyzer->source = file_get_contents( $view );
        $analyzer->analyze();
        $_allowedConstructs = $this->config->getVar('allowedConstructs');
        
        foreach($analyzer->calledConstructs as $key => $val)
        {
            if(!in_array($key, $_allowedConstructs))
            {
                $this->disallowedItems[] = $key;
                $code_is_valide = false;
            }
        }  
        
        $_disallowedVariables = $this->model->config->getVar('disallowedVariables');

        foreach($analyzer->usedVariables as $key => $val)
        {
            if(in_array($key, $_disallowedVariables))
            {
                $this->disallowedItems[] = $key;
                $code_is_valide = false;
            }
        } 

        return $code_is_valide;
    } 
}

?>