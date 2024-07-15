<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XMLSerializer extends Model
{
    use HasFactory;

     // functions adopted from http://www.sean-barton.co.uk/2009/03/turning-an-array-or-object-into-xml-using-php/

     public function generateValidXmlFromObj(RequestData $obj, $node_block = 'nodes', $node_name = 'node') {
        $arr = get_object_vars($obj);
        return $this->generateValidXmlFromArray($arr, $node_block, $node_name);
    }

    public function generateValidXmlFromArray($array, $node_block = 'nodes', $node_name = 'node') {
        $xml = '';
        $xml .= '<' . $node_block . '>';
        $xml .= $this->generateXmlFromArray($array, $node_name);
        $xml .= '</' . $node_block . '>';

        return $xml;
    }

    private function generateXmlFromArray($array, $node_name) {
        $xml = '';

        if (is_array($array) || is_object($array)) {
            foreach ($array as $key => $value) {
                if (is_numeric($key)) {
                    $key = $node_name;
                }

                $xml .= '<' . $key . '>' . self::generateXmlFromArray($value, $node_name) . '</' . $key . '>';
            }
        } else {
            $xml = htmlspecialchars($array, ENT_QUOTES);
        }

        return $xml;
    }
}
