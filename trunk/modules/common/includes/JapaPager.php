<?php
// ---------------------------------------------
// Open Publisher CMS
// Copyright (c) 2006
// by Armand Turpel < cms@open-publisher.net >
// http://www.open-publisher.net/
// ---------------------------------------------
// LICENSE LGPL
// http://www.gnu.org/licenses/lgpl.html
// ---------------------------------------------

/**
 * JapaPager
 *
 * USAGE:
 * new JapaPager( array('result'     => & string, // result string ref. with pager links
                         'numItems'   => int,      // total items
                         'perPage'    => int,      // items per page
                         'numPage'    => int,      // current page
                         'delta'      => int,      // delta range number
                         'url'        => string,   // the url for each link
                         'var_prefix' => string,   // prefix for the pager link var
                         'css_class'  => string    // css class for the links 
                        )); 
 *
 */

class JapaPager
{ 
    /**
     * constructor
     *
     * @param array &$config
     */
    public function __construct( & $config )
    {
        $this->config          = & $config;
        $this->totalPages      = $this->getTotalPages(); 
        $this->totalDeltaPages = $this->getTotalDeltaPages();  
        $this->numDeltaPage    = $this->getNumDeltaPage(); 
        if(!isset($this->config['url_postfix'])) 
        {
        		$this->config['url_postfix'] = '';
        }
        
        if($this->totalPages > 1)
        {
            $this->buildLinks();
        }
    }
    
    /**
     * get total pages
     *
     * @return int
     */
    private function getTotalPages()
    {
        if (($this->config['numItems'] % $this->config['perPage']) > 0) {
            $totalPages = ceil((float)$this->config['numItems'] / (float)$this->config['perPage']);
        } else {
            $totalPages = $this->config['numItems'] / $this->config['perPage'];
        }
        return $totalPages;
    }  
    
    /**
     * get total delta pages
     *
     * @return int
     */
    private function getTotalDeltaPages()
    {
        if (($this->totalPages % $this->config['delta']) > 0) {
            $totalDeltaPages = ceil((float)$this->totalPages / (float)$this->config['delta']);
        } else {
            $totalDeltaPages = $this->totalPages / $this->config['delta'];
        }

        return $totalDeltaPages;
    }     

    /**
     * get current delta page number
     *
     * @return int
     */
    private function getNumDeltaPage()
    {
        if (($this->config['numPage'] % $this->config['delta']) > 0) {
            $numDeltaPage = ceil((float)$this->config['numPage'] / (float)$this->config['delta']);
        } else {
            $numDeltaPage = $this->config['numPage'] / $this->config['delta'];
        }

        return $numDeltaPage;
    }    
    
    /**
     * build link to the previous delta page
     *
     * @param int Number of the current delta page
     * @return string 
     */
    private function addPreDelta( $numDelta )
    {
        if($this->config['numPage'] > $this->config['delta'])
        {
            $preDeltaPage = '<a href="'.$this->config['url'].'/'.$this->config['var_prefix'].'page/'.$numDelta.$this->config['url_postfix'].'" class="'.$this->config['css_class'].'">&lt;&lt;</a> ';
        }
        else
        {
            $preDeltaPage = '';
        } 
        
        return $preDeltaPage;
    }  
    
    /**
     * build link to the next delta page
     *
     * @param int Number of the current delta page
     * @return string 
     */
    private function addNextDelta( $numDelta )
    {
        $this->lastPage = $this->totalDeltaPages * $this->config['delta'] - $this->config['delta'] + 1;

        if($this->lastPage >= $numDelta)
        {
            $nextDeltaPage = '<a href="'.$this->config['url'].'/'.$this->config['var_prefix'].'page/'.$numDelta.$this->config['url_postfix'].'" class="'.$this->config['css_class'].'">&gt;&gt;</a> ';
        }
        else
        {
            $nextDeltaPage = '';
        }        

        return $nextDeltaPage;
    }    
    
    /**
     * build link to the first page
     *
     * @return string 
     */
    private function firstPage()
    {
        if($this->numDeltaPage > 1)
        {
            $firstDeltaPage = '<a href="'.$this->config['url'].'/'.$this->config['var_prefix'].'page/1'.$this->config['url_postfix'].'" class="'.$this->config['css_class'].'">1</a> ';
        }
        else
        {
            $firstDeltaPage = '';
        }

        return $firstDeltaPage;
    }     

    /**
     * build link to last delta page
     *
     * @return string
     */
    private function lastDeltaPage()
    {
        if(($this->lastPage > $this->config['delta']) && ($this->config['numPage'] < $this->lastPage))
        {
            $lastDeltaPage = '<a href="'.$this->config['url'].'/'.$this->config['var_prefix'].'page/'.$this->lastPage.$this->config['url_postfix'].'" class="'.$this->config['css_class'].'">'.$this->lastPage.'</a> ';
        }
        else
        {
            $lastDeltaPage = '';
        }

        return $lastDeltaPage;
    }
    
    /**
     * buld pager links
     *
     */
    private function buildLinks()
    {
        // build start and end of the current delta range
        $start = $this->numDeltaPage * $this->config['delta'] - $this->config['delta'] +1;
        $end   = $this->numDeltaPage * $this->config['delta'];

        // add first page link and previous delta range link
        $this->config['result'] = $this->firstPage();
        $this->config['result'] .= $this->addPreDelta($start - $this->config['delta']);
        
        for( $p=$start; $p <= $end; $p++ )
        {
            // check if there are no more items
            if((($p-1) * $this->config['perPage']) >= $this->config['numItems'])
            {
                break;
            }
            // is this the current page?
            if($this->config['numPage'] != $p)
            {
                $this->config['result'] .= '<a href="'.$this->config['url'].'/'.$this->config['var_prefix'].'page/'.$p.$this->config['url_postfix'].'" class="'.$this->config['css_class'].'">'.$p.'</a> ';  
            }
            else
            {
                $this->config['result'] .= '<span class="'.$this->config['css_class'].'">'.$p.'</span> ';              
            }
        }
        // add last page link and next delta range link
        $this->config['result'] .= $this->addNextDelta($end + 1);
        $this->config['result'] .= $this->lastDeltaPage();
    }      
}

?>