<?php

/**
 * Created by PhpStorm.
 * User: jiaqi
 * Date: 7/27/16
 * Time: 10:52 AM
 */
namespace App\Util;
use App\SolrModel\SolrModel;
use Log;

class SolrUtils
{
    public static $ORG_RELATIONS = ["Investor" => "Portfolio Company", "Parent" => 'Subsidiary', "Same" => "Same"];

    public static function s4($n = 4) {
        $result = "";
        $str = "0123456789abcdefghijklmnopqrstuvwxyz";
        $len = strlen($str);
        for($i=0 ; $i<$n; $i++){
            $result .= $str[rand(0,$len-1)];
        }
        return $result;
    }

    public static function guid() {
        return SolrUtils::s4().SolrUtils::s4().'_'.SolrUtils::s4().'_'.SolrUtils::s4().'_'.
        SolrUtils::s4().'_'.SolrUtils::s4().SolrUtils::s4().SolrUtils::s4();
    }

    private static function purgeUserName($userName) {
        $chars = [' ','+','-', '&', '|', '!', '(', ')', '{', '}', '[', ']', '^', '"', '~', '*', '?', ':', '\\'];
        $userName = str_replace($chars, '', $userName);
        return $userName;
    }
    
    public static function getDocID($indexName, $userName) {
        $userName = SolrUtils::purgeUserName($userName);
        return $indexName.'_'.$userName.'_'.time().'_'.SolrUtils::guid();
    }
    
    public static function getID($indexName, $userName) {
        $userName = SolrUtils::purgeUserName($userName);
        return $indexName.'_'.$userName.'_'.time().'_'.time().'_'.SolrUtils::guid();
    }
    
    public static function currentDate() {
        $date = date(SolrModel::$DATE_FORMAT);
        return explode('+', $date)[0].'Z';
    }

    public static function dateStr_ISO8601($date) {
        $date = date(SolrModel::$DATE_FORMAT, $date);
        return explode('+', $date)[0].'Z';
    }

    // Lucene characters that need escaping with \ are + - && || ! ( ) { } [ ] ^ " ~ * ? : \
    public static function escape($chars){
        $luceneReservedCharacters = preg_quote('+-&|!(){}[]^"~*?:\\');
        $query = preg_replace_callback(
            '/([' . $luceneReservedCharacters . '])/',
            function($matches) {
                return '\\' . $matches[0];
            },
            $chars);
        return $query;
    }

    // set publish-related fields
    public static function publishItem(&$item) {
        if(is_array($item)){
            $item['Active'] = 'true';
            $item['state'] = 'published';
            $item['PublishDate'] = SolrUtils::currentDate();
        }
        else{
            $item->Active = 'true';
            $item->state = 'published';
            $item->PublishDate = SolrUtils::currentDate();
        }
    }
    
    public static function setItemState(&$item, $state){
        if(is_array($item)){
            if ($state == 'working') {
                $item['Active'] = 'false';
            } else {
                $item['Active'] = 'true';
            }
            $item['state'] = $state;
        }
        else{
            if ($state == 'working') {
                $item->Active = 'false';
            } else {
                $item->Active = 'true';
            }
            $item->state = $state;
        }
    }

    public static function deleteItem(&$item) {
        if(is_array($item)){
            $item['state'] = 'deleted';
        }
        else{
            $item->state = 'deleted';
        }
    }

    public static function inactItem(&$item) {
        if(is_array($item)) {
            $item['Active'] = 'false';
        }
        else{
            $item->Active = 'false';
        }
    }

    public static function getOrgRelation($relation) {
        if(array_key_exists($relation, SolrUtils::$ORG_RELATIONS))
            return SolrUtils::$ORG_RELATIONS[$relation];
        else
            return array_flip(SolrUtils::$ORG_RELATIONS)[$relation];
    }

    public static function getSolrSearchConditionStr($str='') {
        $str = str_replace(' ', '?', $str);
        $str = str_replace('/', '??', $str);
        return $str;
    }

    public static function generateArraySearchConditionStr($array, $srckey, $distKey) {
        $arr = [];
        if (is_array($array)) {
            foreach ($array as $item) {
                if(!is_array($item)) {
                    $item = new \ArrayObject($item);
                }

                if (isset($item[$srckey]) && !empty($item[$srckey])) {
                    $arr[] = $item[$srckey];
                }
            }
        }

        if (count($arr) > 0) {
            return '('.implode(' || ', array_map(function ($value) use ($distKey) {
                return $distKey.':'.$value;
            }, $arr)).')';
        } else {
            return '';
        }
    }

    public static function ageComputer($dateOfBirth){
        $dateOfBirth = strtotime($dateOfBirth);
        $now = strtotime("now");
        $age=floor(($now-$dateOfBirth)/86400/365);
        return $age;
    }

    public static function getPropertyOrNull($obj, $pro){
        $value = property_exists($obj, $pro)?$obj->$pro:NULL;
        return is_array($value)?implode(" | ",$value):$value;
    }
    
    public static function slugify($str, $options = array()) {
        // Make sure string is in UTF-8 and strip invalid UTF-8 characters
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
        
        $defaults = array(
            'delimiter' => '-',
            'limit' => null,
            'lowercase' => true,
            'replacements' => array(),
            'transliterate' => false,
        );
        
        // Merge options
        $options = array_merge($defaults, $options);
        
        $char_map = array(
            // Latin
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
            'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 
            'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 
            'ß' => 'ss', 
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
            'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 
            'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 
            'ÿ' => 'y',

            // Latin symbols
            '©' => '(c)',

            // Greek
            'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
            'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
            'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
            'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
            'Ϋ' => 'Y',
            'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
            'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
            'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
            'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
            'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',

            // Turkish
            'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
            'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g', 

            // Russian
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
            'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
            'Я' => 'Ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
            'я' => 'ya',

            // Ukrainian
            'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
            'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',

            // Czech
            'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 
            'Ž' => 'Z', 
            'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
            'ž' => 'z', 

            // Polish
            'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z', 
            'Ż' => 'Z', 
            'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
            'ż' => 'z',

            // Latvian
            'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 
            'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
            'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
            'š' => 's', 'ū' => 'u', 'ž' => 'z'
        );
        
        // Make custom replacements
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
        
        // Transliterate characters to ASCII
        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }
        
        // Replace non-alphanumeric characters with our delimiter
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
        
        // Remove duplicate delimiters
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
        
        // Truncate slug to max. characters
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
        
        // Remove delimiter from ends
        $str = trim($str, $options['delimiter']);
        
        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }
}