<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ViewCliTest class
 *
 * USAGE:
 * 
 * /usr/bin/php cli.php -v cliTest [ -f id_node title ] [ -idn 0 ]
 *
 * -v   = view ( required )
 * -f   = node fields to print out ( optional. default = id_node title )
 * -idn = id_node from which the child nodes were selected ( optional default = 0 )
 *
 */

class ViewCliTest extends SmartCliView
{ 
    /**
     * Allowed node fields in argument list of -f
     *
     */
    private $nodeFields = array('id_node','title','overtitle','subtitle');
    
    /**
     * prepend filter chain
     * here we fetch and validate the command line arguments
     *
     */
    public function prependFilterChain()
    {
        // init error string
        $err = '';
  
        // check if there are some fields defined in -f
        if(!$this->args->isDefined('fields'))
        {
            $this->selectedFields = array('id_node','title');    
        }        
        else
        {
            // get the node fields to fetch ( ex.: -f id_node title ) see: $this->nodeFields
            $this->selectedFields = $this->args->getValue('fields'); 
            
            // check if the defined fields in -f are
            foreach($this->selectedFields as $field)
            {
                if(!in_array($field, $this->nodeFields))
                {
                    $err .= "Node field: '{$field}' dosent exists!\n";
                }
            }
        }
        
        // check if  -idn is defined
        if(!$this->args->isDefined('id_node'))
        {
            $this->selectedIdNode = 0;    
        }    
        else
        {
            // get id_node value ( ex.: -idn 1 )
            $this->selectedIdNode = (int)$this->args->getValue('id_node');
        }
        
        // print message if error
        if( !empty($err) )
        {
            $this->printError( $err );
        }
    }
    
    /**
     * Execute this view
     */
    public function perform()
    {
        // header message
        $this->printHeaderMessage();
        
        // init result array
        $this->tplVar['rootNodes'] = array(); 
        
        // get child nodes of a given id_node
        $this->model->action( 'navigation', 'getChilds', 
                              array('id_node' => (int)$this->selectedIdNode,
                                    'result'  => & $this->tplVar['rootNodes'],
                                    'fields'  => $this->selectedFields)); 

        // if result array is empty print out error message
        if(count($this->tplVar['rootNodes']) == 0)
        {
            $this->printError( "The result is empty. May id_node dosent exists.\n\n" );
        }
        
        // print child node fields
        foreach($this->tplVar['rootNodes'] as $node)
        {
            $message = '';
            foreach($this->selectedFields as $field)
            {
                $message .= "{$field}: {$node[$field]}\n";
            }
            $message .= "\n";
            fwrite(STDOUT, $message, strlen($message));
        }
    }
    /**
     * Print out header message
     */
    private function printHeaderMessage()
    {
        $message  = "\n#####################################################################";
        $message .= "\nThis is the Smart3 CLI view 'cliTest'.";
        $message .= "\nThis view print out all child navigation nodes of a given id_node\n";
        $message .= "#######################################################################\n\n";
        
        fwrite(STDOUT, $message, strlen($message));    
    }
    
    /**
     * Return the config array.
     *
     * The config array is the set of rules for command line
     * arguments. 
     *
     * If an option can be invoked by more than one name, they have to be defined
     * by using | as a separator. For example: name1|name2
     * This works both in long and short names.
     *
     * max/min are the most/least number of arguments an option accepts.
     *
     * The 'defaults' field is optional and is used to specify default
     * arguments to an option. These will be assigned to the option if 
     * it is *not* used in the command line.
     * Default arguments can be:
     * - a single value for options that require a single argument,
     * - an array of values for options with more than one possible arguments.
     * Default argument(s) are mandatory for 'default-if-set' options.
     *
     * If max is 0 (option is just a switch), min is ignored.
     * If max is -1, then the option can have an unlimited number of arguments 
     * greater or equal to min.
     * 
     * If max == min == 1, the option is treated as a single argument option.
     * 
     * If max >= 1 and min == 0, the option is treated as a
     * 'default-if-set' option. This implies that it will get the default argument
     * only if the option is used in the command line without any value.
     * (Note: defaults *must* be specified for 'default-if-set' options) 
     *
     * If the option is not in the command line, the defaults are 
     * *not* applied. If an argument for the option is specified on the command
     * line, then the given argument is assigned to the option.
     * Thus:
     * - a --debug in the command line would cause debug = 'default argument'
     * - a --debug 2 in the command line would result in debug = 2
     *  if not used in the command line, debug will not be defined.
     *
     * @param  none
     * @return &array
     */
    protected function & getConfigArray()
    {
        $configArray = array();
        
        // select field option.
        $configArray['fields'] = array('short' => 'f',
                                       'min'     => 1,
                                       'max'     => count($this->nodeFields),
                                       'desc'    => 'Set node fields to print out.'
                                      );
                                      
        // select id_node option.
        $configArray['id_node'] = array('short' => 'idn',
                                        'min'     => 1,
                                        'max'     => 1,
                                        'desc'    => 'Set id_node from which to print out all child nodes. Int value.'
                                      );                                      

        return $configArray;
    }      
}

?>