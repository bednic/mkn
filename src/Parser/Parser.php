<?php
/**
 * Created by PhpStorm.
 * @author tomas
 * @package mkn
 * Date: 2017-04-20
 * Time: 11:06
 */

namespace MKN\Parser;

use MKN\Provider\Provider;

class Parser
{
    private $xml;
    private $xsd;
    private $provider;

    /**
     * Parser constructor.
     * @param string $MKN_XML_file
     * @param null|string $MKN_XSD_file
     */
    public function __construct($MKN_XML_file, $MKN_XSD_file = null)
    {
        $this->provider = new Provider();
        $this->xml = $MKN_XML_file;
        $this->xsd = $MKN_XSD_file;
    }
    public function parse()
    {
        $xml = realpath($this->xml);
        $xsd = $this->xsd?realpath($this->xsd):null;

        $this->parseXML($xml,$xsd);
    }

    private function parseXML($xmlPath, $xsdPath = null)
    {

        $xml = new \DOMDocument();
        $xml->load($xmlPath);

        /** @var \DOMNode $item */
        foreach ($xml->getElementsByTagName('VETA') as $item){
            $code = null;
            $name = null;

            foreach ($item->attributes as $attribute){
                switch ($attribute->name){
                    case 'kod':
                        $code = $attribute->value;
                        break;
                    case 'naz':
                        $name = $attribute->value;
                        break;
                }
            }
            $this->provider->insert($code,$name);
        }

    }
}