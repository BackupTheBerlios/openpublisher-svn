<?php
/**
* @package SPLIB
* @version $Id: RssGenerator.php,v 1.4 2003/08/01 20:08:37 harry Exp $
*/
/**
* RssGenerator Class
* Generates RSS 1.0 documents according to http://purl.org/rss/1.0/
* @access public
* @package SPLIB
*/
class RssGenerator {
    /**
    * Stores the document as a DOM Document instance
    * @access private
    * @var resource
    */
    private $dom;

    /**
    * Stores the root element of the rss feed
    * as a DOM Element object
    * @access private
    * @var resource
    */
    private $rss;

    /**
    * Stores the channel element
    * as a DOM Element object
    * @access private
    * @var resource
    */
    private $channel;

    /**
    * Stores the item sequence for the channel
    * as a DOM Element object
    * @access private
    * @var resource
    */
    private $itemSequence;

    /**
    * Stores the image element
    * as a DOM Element object
    * @access private
    * @var resource
    */
    private $image;

    /**
    * An array of items as DOM Element instances
    * @access private
    * @var resource
    */
    private $items;

    /**
    * Stores the textinput element
    * as a DOM Element object
    * @access private
    * @var resource
    */
    private $textinput;

    /**
    * RssGenerator-Konstruktor
    * @access public
    */
    public function __construct() {
        $this->dom= new DomDocument('1.0');
        // Elemente werden eingerueckt
        $this->dom->formatOutput = true;
        $this->initialize();
    }

    /**
    * Creates the root rdf element
    * Sets up the channel and item sequence
    * @return void
    * @access private
    */
    private function initialize () {
        $rss=$this->dom->createElement('rdf:RDF');

        // Namespace hack
        $rss->setAttribute('xmlns:rdf',
            'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
        $rss->setAttribute('xmlns',
            'http://purl.org/rss/1.0/');
        $this->rss=$this->dom->appendChild($rss);
        $this->channel=$this->dom->createElement('channel');
        $this->itemSequence=$this->dom->createElement('rdf:seq');
    }

    /**
    * Adds the url of the image to the channel element
    * @param string url of image
    * @return void
    * @access private
    */
    private function addChannelImage($url) {
        $image=$this->dom->createElement('image');
        $image->setAttribute('rdf:resource',$url);
        $this->channel->appendChild($image);
    }

    /**
    * Adds a url of an item to the items sequence in the channel element
    * @param string url of an item
    * @return void
    * @access private
    */
    private function addChannelItem ($url) {
        $li=$this->dom->createElement('rdf:li');
        $li->setAttribute('resource',$url);
        $this->itemSequence->appendChild($li);
    }

    /**
    * Adds a url of an item to the items sequence in the channel element
    * @param string url of an item
    * @return void
    * @access private
    */
    private function addChannelTextInput($url) {
        $textInput=$this->dom->createElement('textinput');
        $textInput->setAttribute('rdf:resource',$url);
        $this->channel->appendChild($textInput);
    }

    /**
    * Makes the final appends to the rss document
    * @return void
    * @access private
    */
    private function finalize () {
        $channelItems=$this->dom->createElement('items');
        $channelItems->appendChild($this->itemSequence);
        $this->channel->appendChild($channelItems);
        $this->rss->appendChild($this->channel);

        if ( isset ( $this->image ) )
            $this->rss->appendChild($this->image);

        if ( is_array ( $this->items ) ) {
            foreach ($this->items as $item) {
                $this->rss->appendChild($item);
            }
        }

        if ( isset ( $this->textinput ) )
            $this->rss->appendChild($this->textinput);
    }

    /**
    * Fügt grundsätzliche Kanalinformationen ein
    * @param string Titel des Kanals, z. B. "SitePoint"
    * @param string URL des Kanals
    * e.g. "http://www.sitepoint.com/"
    * @param string Beschreibung des Kanals
    * @param string about URL
    * z. B. "http://www.sitepoint.com/about/"
    * @return void
    * @access public
    */
    public function addChannel ($title,$link,$desc,$aboutUrl) {
        $this->channel->setAttribute('rdf:about',$aboutUrl);
        $titleNode=$this->dom->createElement('title');
        $titleNodeText=$this->dom->createTextNode($title);
        $titleNode->appendChild($titleNodeText);
        $this->channel->appendChild($titleNode);
        $linkNode=$this->dom->createElement('link');
        $linkNodeText=$this->dom->createTextNode($link);
        $linkNode->appendChild($linkNodeText);
        $this->channel->appendChild($linkNode);
        $descNode=$this->dom->createElement('description');
        $descNodeText=$this->dom->createTextNode($desc);
        $descNode->appendChild($descNodeText);
        $this->channel->appendChild($descNode);
    }

    /**
    * Fügt die Beschreibung des Logos in die Datei ein
    * @param string src URL des Bildes
    * @param string alternativer Text für das Bild
    * @param string Link für das Bild, z. B.
    * http://www.sitepoint.com/
    * @return void
    * @access public
    */
    public function addImage ($src,$alt,$link) {
        $this->addChannelImage($src);
        $this->image=$this->dom->createElement('image');
        $this->image->setAttribute('rdf:about',$src);
        $titleNode=$this->dom->createElement('title');
        $titleNodeText=$this->dom->createTextNode($alt);
        $titleNode->appendChild($titleNodeText);
        $this->image->appendChild($titleNode);
        $urlNode=$this->dom->createElement('url');
        $urlNodeText=$this->dom->createTextNode($src);
        $urlNode->appendChild($urlNodeText);
        $this->image->appendChild($urlNode);
        $linkNode=$this->dom->createElement('link');
        $linkNodeText=$this->dom->createTextNode($link);
        $linkNode->appendChild($linkNodeText);
        $this->image->appendChild($linkNode);
    }

    /**
    * Fügt eine Beschreibung der Suchfunktionalität ein
    * @param string Name der Suchseite, z. B. "Search"
    * @param string Beschreibung der Suchseite, z. B.
    * "Search SitePoint..."
    * @param string URL der Suchseite
    * @param string GET-Variable für die Suche, z. B.
    * "q" für "?q="
    * @return void
    * @access public
    */
    public function addSearch ($title,$desc,$url,$var) {
        $this->addChannelTextInput($url);
        $this->textinput=$this->dom->createElement('textinput');
        $this->textinput->setAttribute('rdf:about',$url);
        $titleNode=$this->dom->createElement('title');
        $titleNodeText=$this->dom->createTextNode($title);
        $titleNode->appendChild($titleNodeText);
        $this->textinput->appendChild($titleNode);
        $descNode=$this->dom->createElement('description');
        $descNodeText=$this->dom->createTextNode($desc);
        $descNode->appendChild($descNodeText);
        $this->textinput->appendChild($descNode);
        $nameNode=$this->dom->createElement('name');
        $nameNodeText=$this->dom->createTextNode($var);
        $nameNode->appendChild($nameNodeText);
        $this->textinput->appendChild($nameNode);
        $linkNode=$this->dom->createElement('link');
        $linkNodeText=$this->dom->createTextNode($url);
        $linkNode->appendChild($linkNodeText);
        $this->textinput->appendChild($linkNode);
    }

    /**
    * Fügt einen RSS-Eintrag in das Dokument
    * @param string Titel des Eintrags
    * @param string Link des Eintrags
    * @param string Beschreibung des Eintrags
    * @return void
    * @access public
    */
    public function addItem ($title,$link,$desc) {
        $this->addChannelItem($link);
        $itemNode=$this->dom->createElement('item');
        $itemNode->setAttribute('rdf:about',$link);
        $titleNode=$this->dom->createElement('title');
        $titleNodeText=$this->dom->createTextNode($title);
        $titleNode->appendChild($titleNodeText);
        $itemNode->appendChild($titleNode);
        $linkNode=$this->dom->createElement('link');
        $linkNodeText=$this->dom->createTextNode($link);
        $linkNode->appendChild($linkNodeText);
        $itemNode->appendChild($linkNode);
        $descNode=$this->dom->createElement('description');
        $descNodeText=$this->dom->createTextNode($desc);
        $descNode->appendChild($descNodeText);
        $itemNode->appendChild($descNode);
        $this->items[]=$itemNode;
    }

    /**
    * Gibt das RSS-Dokument als String zurück
    * @return string XML-Dokument
    * @access public
    */
    public function toString () {
        $this->finalize();
        return $this->dom->saveXML();
    }
}
?>