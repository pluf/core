<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Utility class to clean/manipulate strings. 
 */

class Pluf_Text
{
    /**
     * Wrap a string containing HTML code.
     *
     * The HTML is not broken, words are broken only if very long. 
     *
     * Improved from a version available on php.net
     *
     * @see http://www.php.net/manual/en/function.wordwrap.php#89782
     *
     * @param string The string to wrap
     * @param int The maximal length of a string (45)
     * @param string Wrap string ("\n")
     * @return string Wrapped string
     */
    public static function wrapHtml($string, $length=45, $wrapString="\n")
    {
        $wrapped = '';
        $word = '';
        $html = false;
        $line_len = 0;
        $n = mb_strlen($string);
        for ($i=0; $i<$n; $i++) {
            $char = mb_substr($string, $i, 1);
            /** HTML Begins */
            if ($char === '<') {
                if (!empty($word)) {
                    $line_len += mb_strlen($word);
                    $wrapped .= $word;
                    $word = '';
                }
                $html = true;
                $wrapped .= $char;
                continue;
            } 
            if ($char === '>') {
                /** HTML ends */
                $html = false;
                $wrapped .= $char;
                continue;
            } 
            if ($html) {
                /** If this is inside HTML -> append to the wrapped string */
                $wrapped .= $char;
                continue;
            } 
            if ($char === $wrapString) {
                /** Whitespace characted / new line */
                $wrapped .= $word.$char;
                $word = '';
                $line_len = 0;
                continue;
            } 
            if (in_array($char, array(' ', "\t"))) {
                // Word delimiter, check if split before it needed
                $word .= $char;
                if (mb_strlen($word) + $line_len <= $length) {
                    $line_len += mb_strlen($word);
                    $wrapped .= $word;
                    $word = '';
                } else {
                    // If we add the word, it will be above the limit
                    $line_len = mb_strlen($word);
                    $wrapped .= $wrapString.$word;
                    $word = '';
                }
                continue;
            }
            /** Check chars */
            
            $word .= $char;
            if (mb_strlen($word) + $line_len > $length) {
                $wrapped .= $wrapString;
                $line_len = 0;
                continue;
            } 
            if (mb_strlen($word) >= $length) {
                $wrapped .= $word.$wrapString;
                $word = '';
                $line_len = 0;
                continue;
            } 
        }
        if ($word !== '') {
            $wrapped .= $word;
        }
        return $wrapped;
    }

    /**
     * Given a string, cleaned from the not interesting characters,
     * returns an array with the words as index and the number of
     * times it was in the text as the value.
     *
     * @credits Tokenizer of DokuWiki to handle Thai and CJK words.
     *          http://www.splitbrain.org/projects/dokuwiki
     *
     * @param string Cleaned, lowercased and utf-8 encoded string.
     * @param bool Remove the accents (True)
     * @return array Word and number of occurences.
     */
    public static function tokenize($string, $remove_accents=True)
    {
        if ($remove_accents) {
            $string = self::removeAccents($string);
        }
        $asian1 = '[\x{0E00}-\x{0E7F}]'; // Thai
        $asian2 = '['.
                   '\x{2E80}-\x{3040}'.  // CJK -> Hangul
                   '\x{309D}-\x{30A0}'.
                   '\x{30FD}-\x{31EF}\x{3200}-\x{D7AF}'.
                   '\x{F900}-\x{FAFF}'.  // CJK Compatibility Ideographs
                   '\x{FE30}-\x{FE4F}'.  // CJK Compatibility Forms
                   ']';
        $asian3 = '['. // Hiragana/Katakana (can be two characters)
                   '\x{3042}\x{3044}\x{3046}\x{3048}'.
                   '\x{304A}-\x{3062}\x{3064}-\x{3082}'.
                   '\x{3084}\x{3086}\x{3088}-\x{308D}'.
                   '\x{308F}-\x{3094}'.
                   '\x{30A2}\x{30A4}\x{30A6}\x{30A8}'.
                   '\x{30AA}-\x{30C2}\x{30C4}-\x{30E2}'.
                   '\x{30E4}\x{30E6}\x{30E8}-\x{30ED}'.
                   '\x{30EF}-\x{30F4}\x{30F7}-\x{30FA}'.
                   ']['.
                   '\x{3041}\x{3043}\x{3045}\x{3047}\x{3049}'.
                   '\x{3063}\x{3083}\x{3085}\x{3087}\x{308E}\x{3095}-\x{309C}'.
                   '\x{30A1}\x{30A3}\x{30A5}\x{30A7}\x{30A9}'.
                   '\x{30C3}\x{30E3}\x{30E5}\x{30E7}\x{30EE}\x{30F5}\x{30F6}\x{30FB}\x{30FC}'.
                   '\x{31F0}-\x{31FF}'.
                   ']?';
        $asian = '(?:'.$asian1.'|'.$asian2.'|'.$asian3.')';
        $words = array();
        // handle asian chars as single words.
        $asia = @preg_replace('/('.$asian.')/u',' \1 ',$string);
        if (!is_null($asia)) {
            //will not be called if regexp failure
            $string = $asia;
        }
        $arr = preg_split('/\s+/', $string, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($arr as $w) {
            $w = trim($w);
            if (isset($words[$w])) {
                $words[$w]++;
            } else {
                $words[$w] = 1;
            }
        }
        return $words;
    }

    /**
     * Clean a string from the HTML and the unnecessary
     * punctuation. Convert the string to lowercase.
     *
     * @info Require mbstring extension.
     *
     * @param string String.
     * @return string Cleaned lowercase string.
     */
    public static function cleanString($string)
    {
        $string = html_entity_decode($string, ENT_QUOTES, 'utf-8');
        $string = str_replace('<?php', '', $string);
        $string = strip_tags($string);
        $string = strtr($string, "\r\n\t", '   ');
        $string = strtr($string, 
                        '.<>,;:(){}[]\\|*@!?^_=/\'~`%$#',
                        '                            ');
        return mb_strtolower($string, 'UTF-8');
    }

    /**
     * Remove the accentuated characters.
     *
     * Requires a string in lowercase, the removal is not perfect but
     * is better than nothing.
     *
     * @param string Lowercased string in utf-8.
     * @return string String with some of the accents removed.
     */
    public static function removeAccents($string)
    {
        $map = array(
                     'à'=>'a', 'ô'=>'o', 'ď'=>'d', 'ḟ'=>'f', 'ë'=>'e',
                     'š'=>'s', 'ơ'=>'o', 'ß'=>'ss', 'ă'=>'a', 'ř'=>'r', 
                     'ț'=>'t', 'ň'=>'n', 'ā'=>'a', 'ķ'=>'k', 'ŝ'=>'s', 
                     'ỳ'=>'y', 'ņ'=>'n', 'ĺ'=>'l', 'ħ'=>'h', 'ṗ'=>'p', 
                     'ó'=>'o', 'ú'=>'u', 'ě'=>'e', 'é'=>'e', 'ç'=>'c',
                     'ẁ'=>'w', 'ċ'=>'c', 'õ'=>'o', 'ṡ'=>'s', 'ø'=>'o', 
                     'ģ'=>'g', 'ŧ'=>'t', 'ș'=>'s', 'ė'=>'e', 'ĉ'=>'c',
                     'ś'=>'s', 'î'=>'i', 'ű'=>'u', 'ć'=>'c', 'ę'=>'e', 
                     'ŵ'=>'w', 'ṫ'=>'t', 'ū'=>'u', 'č'=>'c', 'ö'=>'oe', 
                     'è'=>'e', 'ŷ'=>'y', 'ą'=>'a', 'ł'=>'l', 'ų'=>'u', 
                     'ů'=>'u', 'ş'=>'s', 'ğ'=>'g', 'ļ'=>'l', 'ƒ'=>'f', 
                     'ž'=>'z', 'ẃ'=>'w', 'ḃ'=>'b', 'å'=>'a', 'ì'=>'i', 
                     'ï'=>'i', 'ḋ'=>'d', 'ť'=>'t', 'ŗ'=>'r', 'ä'=>'ae', 
                     'í'=>'i', 'ŕ'=>'r', 'ê'=>'e', 'ü'=>'ue', 'ò'=>'o',
                     'ē'=>'e', 'ñ'=>'n', 'ń'=>'n', 'ĥ'=>'h', 'ĝ'=>'g', 
                     'đ'=>'d', 'ĵ'=>'j', 'ÿ'=>'y', 'ũ'=>'u', 'ŭ'=>'u', 
                     'ư'=>'u', 'ţ'=>'t', 'ý'=>'y', 'ő'=>'o', 'â'=>'a', 
                     'ľ'=>'l', 'ẅ'=>'w', 'ż'=>'z', 'ī'=>'i', 'ã'=>'a', 
                     'ġ'=>'g', 'ṁ'=>'m', 'ō'=>'o', 'ĩ'=>'i', 'ù'=>'u', 
                     'į'=>'i', 'ź'=>'z', 'á'=>'a', 'û'=>'u', 'þ'=>'th', 
                     'ð'=>'dh', 'æ'=>'ae', 'µ'=>'u', 'ĕ'=>'e',
                     );
        return strtr($string, $map);
    }

    /**
     * Convert a string to a list of characters.
     *
     * @param string utf-8 encoded string.
     * @return array Characters.
     */
    public static function stringToChars($string)
    {
        $chars = array();
        $strlen = mb_strlen($string, 'UTF-8');
        for ($i=0;$i<$strlen;$i++) {
            $chars[] = mb_substr($string,$i, 1, 'UTF-8');
        }
        return $chars;
    }

    /**
     * Prevent a string to be all uppercase. 
     *
     * If more than 50% of the words in the string are uppercases and
     * if the string contains more than one word, the string is
     * converted using the mb_convert_case.
     *
     * @see http://www.php.net/mb_convert_case
     *
     * @param string String to test.
     * @param int Mode to convert the string (MB_CASE_TITLE)
     * @return string Cleaned string.
     */
    public static function preventUpperCase($string, $mode=MB_CASE_TITLE)
    {
        $elts = mb_split(' ', $string);
        $n_elts = count($elts);
        if ($n_elts > 1) {
            $tot = 0;
            foreach ($elts as $elt) {
                if ($elt == '') {
                    $n_elts--;
                    continue;
                }
                if ($elt == mb_strtoupper($elt, 'UTF-8')) {
                    $tot++;
                }
            }
            if ( (float) $tot / (float) $n_elts >= 0.5) {
                return mb_convert_case(mb_strtolower($string, 'UTF-8'), 
                                       $mode, 'UTF-8');
            }
        }
        return $string;
    }

    /**
     * Simple uppercase prevention.
     *
     * Contrary to self::preventUpperCase, this method will also
     * prevent a single word to be uppercase.
     *
     * @param string String possibly in uppercase.
     * @param int Mode to convert the string (MB_CASE_TITLE)
     * @return string Mode cased if all uppercase in input.
     */
    public static function simplePreventUpperCase($string, $mode=MB_CASE_TITLE)
    {
        if ($string == mb_strtoupper($string)) {
            return mb_convert_case(mb_strtolower($string), $mode, 'UTF-8');
        }
        return $string;
    }
}
