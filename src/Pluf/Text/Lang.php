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
 * Detect the language of a text.
 *
 * <code>
 * list($lang, $confid) = Pluf_Text_Lang::detect($string);
 * </code>
 */
class Pluf_Text_Lang
{
    /**
     * Given a string, returns the language.
     *
     * Algorithm by Cavnar et al. 94.
     *
     * @param string
     * @param bool Is the string clean (false)
     * @return array Language, Confidence
     */
    public static function detect($string, $is_clean=false)
    {
        if (!$is_clean) {
            $string = Pluf_Text::cleanString($string);
        }
        
    }

    /**
     * Returns the sorted n-grams of a document.
     *
     * FIXME: We should detect the proportion of thai/chinese/japanese
     * characters and switch to unigram instead of n-grams if the
     * proportion is greater than 50%.
     *
     * @param string The clean document.
     * @param int Maximum size of the n grams (3)
     * @return array N-Grams
     */
    public static function docNgrams($string, $n=3)
    {
        // do not remove the accents 
        $words = Pluf_Text::tokenize($string, false); 
        $ngrams = array();
        for ($i=2;$i<=$n;$i++) {
            foreach ($words as $word=>$occ) {
                foreach (self::makeNgrams($word, $i) as $ngram) {
                    $ngrams[] = array($ngram, $occ);
                }
            }
        }
        $out = array();
        foreach ($ngrams as $ngram) {
            if (!isset($out[$ngram[0]])) {
                $out[$ngram[0]] = $ngram[1];
            } else {
                $out[$ngram[0]] += $ngram[1];
            }
        }
        // split the ngrams by occurence.
        $ngrams = array();
        foreach ($out as $ngram=>$occ) {
            if (isset($ngrams[$occ])) {
                $ngrams[$occ][] = $ngram;
            } else {
                $ngrams[$occ] = array($ngram);
            }
        }
        krsort($ngrams);
        $res = array();
        foreach ($ngrams as $occ=>$list) {
            sort($list);
            foreach ($list as $ngram) {
                $res[] = $ngram;
            }
        }
        return $res;
    }

    /**
     * Returns the n-grams of rank n of the word.
     *
     * @param string Word.
     * @return array N-grams
     */
    public static function makeNgrams($word, $n=3)
    {
        $chars = array('_');
        $chars = $chars + Pluf_Text::stringToChars($word);
        $chars[] = '_';
        $l = count($chars);
        $ngrams = array();
        for ($i=0;$i<$l+1-$n;$i++) {
            $ngrams[$i] = array();
        }
        $n_ngrams = $l+1-$n;
        for ($i=0;$i<$l;$i++) {
            for ($j=0;$j<$n;$j++) {
                if (isset($ngrams[$i-$j])) {
                    $ngrams[$i-$j][] = $chars[$i];
                }
            }
        }
        $out = array();
        foreach ($ngrams as $ngram) {
            $t = implode('', $ngram);
            if ($t != '__') {
                $out[] = $t;
            }
        }
        return $out;
    }

    /**
     * Return the distance between two document ngrams.
     *
     * @param array n-gram
     * @param array n-gram
     * @return integer distance
     */
    public static function ngramDistance($n1, $n2)
    {
        $res = 0;
        $n_n1 = count($n1);
        $n_n2 = count($n2);
        if ($n_n1 > $n_n2) {
            list($n_n1, $n_n2) = array($n_n2, $n_n1);
            list($n1, $n2) = array($n2, $n1);
        }
        for ($i=0;$i<$n_n1;$i++) {
            if (false !== ($index = array_search($n1[$i], $n2))) {
                $offset = abs($index - $i);
                $res += ($offset > 3) ? 3 : $offset;
            } else {
                $res += 3;
            }
        }
        $res += ($n_n2 - $n_n1) * 3;
        return $res;
    }
}