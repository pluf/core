<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

/**
 * Localization class.
 *
 * The localization of the code is performed using the __() function call.
 * This function is directly available in the Pluf.php file.
 *
 * The Pluf_L10n class is used to load the localization strings in memory
 * in the $GLOBALS['_PX_locale'] array. All the strings are stored in utf-8
 * as all the applications created with the Plume Framework must use the utf-8
 * encoding.
 * The Pluf locale files are in the Pluf/locale/ folder.
 *
 * The locale files can be optimized and an optimized version of the files
 * stored in the Pluf temp folder. The temp folder is defined in the global
 * configuration as 'tmp_folder'. 
 *
 * 2 letter ISO codes from http://www.oasis-open.org/cover/iso639a.html
 */
class Pluf_L10n
{
    /**
     * Folder in which the locale file are available.
     */
    public $locale_folder = '';

    /** 
     * Constructor.
     *
     * See loadDomain(). If no folder is provided, the default Pluf/locale
     * folder is used to load the locales from.
     *
     * @param string Locale folder without trailing slash ('')
     * @param string Language ('en')
     * @param string Domain ('pluf')
     */
    function __construct($folder='', $lang='en', $domain='pluf')
    {
        if ('' == $folder) {
            $this->locale_folder = dirname(__FILE__).'/locale';
        }
        $this->loadDomain($lang, $domain);
    }
    
    /**
     * Load a domain file.
     * A domain file is a .lang file in the main locale folder of plume.
     *
     * @param string Language ('en')
     * @param string Domain, without the .lang ('pluf')
     * @return bool Success
     */
    function loadDomain($lang='en', $domain='pluf')
    {
        if ('en' == $lang) {
            return true;
        }
        return $this->loadFile($this->locale_folder.$lang.'/'.$domain.'.lang');
    }

    /**
     * Load a locale file
     *
     * @param string Complete path to the locale file
     * @return bool Success
     */
    function loadFile($file)
    {
        if (!empty($GLOBALS['_PX_locale_files'][$file])) {
            return true;
        }
        if (!file_exists($file)) {
            return false;
        }
        if (!isset($GLOBALS['_PX_locale'])) {
            $GLOBALS['_PX_locale'] = array();
        }
        // Load optimized file if available
        if ('' != Pluf::f('tmp_folder')) {
            $phpfile = Pluf::f('tmp_folder').'/Pluf_L10n-'
                .str_replace(DIRECTORY_SEPARATOR, '_', substr($file, 0, -5))
                .'.php';
            if (file_exists($phpfile) 
                && (@filemtime($file) < @filemtime($phpfile))) {
                $l = include $phpfile;
                $GLOBALS['_PX_locale'] = array_merge($GLOBALS['_PX_locale'], $l);
                $GLOBALS['_PX_locale_files'][$file] = 'optimized';
                return true;
            }
        }
        $lines = file($file);
        $count = count($lines);
        for ($i=1; $i<$count; $i++) {
            $tmp = (!empty($lines[$i+1])) ? trim($lines[$i+1]) : '';
            if (!empty($tmp) && ';' == substr($lines[$i],0,1)) {
                $GLOBALS['_PX_locale'][trim(substr($lines[$i],1))] = $tmp;
                $i++;
            }
        }
        $GLOBALS['_PX_locale_files'][$file] = true;
        return true;
    }
    
    /**
     * Optimize a locale. Convert the .lang in a .php file 
     * ready to be included. The optimized file is encoded 
     * with the current encoding.
     *
     * @param string Locale file to optimize
     * @return bool Success
     */
    function optimizeLocale($file)
    {
        if (!file_exists($file)) {
            return false;
        }
        $phpfile = Pluf::f('tmp_folder').'/Pluf_L10n-'
            .str_replace(DIRECTORY_SEPARATOR, '_', substr($file, 0, -5))
            .'.php';
        $lines = file($file);
        $out = '<?php '."\n".'/* automatically generated file from: '
            .$file.'  */'."\n\n";
        $out .= '$l = array();'."\n";
        $count = count($lines);
        for ($i=1; $i<$count; $i++) {
            $tmp = (!empty($lines[$i+1])) ? trim($lines[$i+1]) : '';
            if (!empty($tmp) && ';' == substr($lines[$i],0,1)) {
                $string = '$l[\''
                    .str_replace("'", "\\'", trim(substr($lines[$i],1)))
                    .'\'] = \''.str_replace("'", "\\'", $tmp).'\';'."\n";
                $out .= $string;
                $i++;
            }
        }
        $out .= 'return $l;'."\n\n".'?>';
        file_put_contents($phpfile, $out, LOCK_EX);
        @chmod($phpfile, 0777);
        return true;
    }

    /**
     * Get the available locales for a domain.
     *
     * @param string Domain ('')
     * @return array List of 2 letter iso codes
     */
    function getAvailableLocales($domain='')
    {
        $rootdir = $this->locale_folder.'/';
        $locales = array();
        $locales[] = 'en'; //English is always available
        $current_dir = opendir($rootdir);
        if (!empty($domain)) {
            $domain .= '.lang';
        }
        while($entryname = readdir($current_dir)) {
            if (is_dir($rootdir.$entryname.'/') 
                and ($entryname != '.' and $entryname!='..') 
                and (2 == strlen($entryname))
                ) {
                $entryname = strtolower($entryname);
                if (empty($domain)) {
                    $locales[] = $entryname;
                } elseif (is_file($rootdir.$entryname.'/'.$domain)) {
                    $locales[] = $entryname;
                }
            }
        }
        closedir($current_dir);
        sort($locales);
        reset($locales);
        return $locales;
    }

    /**
     * Return the "best" accepted language from the list of available 
     * languages.
     *
     * Use $_SERVER['HTTP_ACCEPT_LANGUAGE'] if the accepted language is empty
     *
     * @param array Available languages in the system
     * @param string String of comma separated accepted languages ('')
     * @return string Language 2 letter iso code, default is 'en'
     */
    function getAcceptedLanguage($available, $accepted ='')
    {
        if (empty($accepted)) {
            if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                $accepted = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            } else {
                return 'en';
            }
        }
        $acceptedlist = explode(',', $accepted);
        foreach ($acceptedlist as $lang) {
            //for the fr-FR en-US cases
            $lang = strtolower(substr($lang, 0, 2)); 
            if (in_array($lang, $available)) {
                return $lang;
            }
        }
        //no match found, English
        return 'en';
    }

    /**
     * Returns iso codes.
     *
     * @param bool Is the language the key in the array (false)
     * @return array The key is either the language or the iso code
     */
    function getIsoCodes($lang=false)
    {
        $res = array('aa' => 'Afar',
                     'ab' => 'Abkhazian',
                     'af' => 'Afrikaans',
                     'am' => 'Amharic',
                     'ar' => 'Arabic',
                     'as' => 'Assamese',
                     'ay' => 'Aymara',
                     'az' => 'Azerbaijani',
                     'ba' => 'Bashkir',
                     'be' => 'Byelorussian',
                     'bg' => 'Bulgarian',
                     'bh' => 'Bihari',
                     'bi' => 'Bislama',
                     'bn' => 'Bengali',
                     'bo' => 'Tibetan',
                     'br' => 'Breton',
                     'ca' => 'Catalan',
                     'co' => 'Corsican',
                     'cs' => 'Czech',
                     'cy' => 'Welsh',
                     'da' => 'Danish',
                     'de' => 'German',
                     'dz' => 'Bhutani',
                     'el' => 'Greek',
                     'en' => 'English',
                     'eo' => 'Esperanto',
                     'es' => 'Spanish',
                     'et' => 'Estonian',
                     'eu' => 'Basque',
                     'fa' => 'Persian',
                     'fi' => 'Finnish',
                     'fj' => 'Fiji',
                     'fo' => 'Faroese',
                     'fr' => 'French',
                     'fy' => 'Frisian',
                     'ga' => 'Irish',
                     'gd' => 'Scots gaelic',
                     'gl' => 'Galician',
                     'gn' => 'Guarani',
                     'gu' => 'Gujarati',
                     'ha' => 'Hausa',
                     'he' => 'Hebrew',
                     'hi' => 'Hindi',
                     'hr' => 'Croatian',
                     'hu' => 'Hungarian',
                     'hy' => 'Armenian',
                     'ia' => 'Interlingua',
                     'ie' => 'Interlingue',
                     'ik' => 'Inupiak',
                     'id' => 'Indonesian',
                     'is' => 'Icelandic',
                     'it' => 'Italian',
                     'iu' => 'Inuktitut',
                     'ja' => 'Japanese',
                     'jv' => 'Javanese',
                     'ka' => 'Georgian',
                     'kk' => 'Kazakh',
                     'kl' => 'Greenlandic',
                     'km' => 'Cambodian',
                     'kn' => 'Kannada',
                     'ko' => 'Korean',
                     'ks' => 'Kashmiri',
                     'ku' => 'Kurdish',
                     'ky' => 'Kirghiz',
                     'la' => 'Latin',
                     'ln' => 'Lingala',
                     'lo' => 'Laothian',
                     'lt' => 'Lithuanian',
                     'lv' => 'Latvian;lettish',
                     'mg' => 'Malagasy',
                     'mi' => 'Maori',
                     'mk' => 'Macedonian',
                     'ml' => 'Malayalam',
                     'mn' => 'Mongolian',
                     'mo' => 'Moldavian',
                     'mr' => 'Marathi',
                     'ms' => 'Malay',
                     'mt' => 'Maltese',
                     'my' => 'Burmese',
                     'na' => 'Nauru',
                     'ne' => 'Nepali',
                     'nl' => 'Dutch',
                     'no' => 'Norwegian',
                     'oc' => 'Occitan',
                     'om' => 'Afan (oromo)',
                     'or' => 'Oriya',
                     'pa' => 'Punjabi',
                     'pl' => 'Polish',
                     'ps' => 'Pashto;pushto',
                     'pt' => 'Portuguese',
                     'qu' => 'Quechua',
                     'rm' => 'Rhaeto-romance',
                     'rn' => 'Kurundi',
                     'ro' => 'Romanian',
                     'ru' => 'Russian',
                     'rw' => 'Kinyarwanda',
                     'sa' => 'Sanskrit',
                     'sd' => 'Sindhi',
                     'sg' => 'Sangho',
                     'sh' => 'Serbo-croatian',
                     'si' => 'Singhalese',
                     'sk' => 'Slovak',
                     'sl' => 'Slovenian',
                     'sm' => 'Samoan',
                     'sn' => 'Shona',
                     'so' => 'Somali',
                     'sq' => 'Albanian',
                     'sr' => 'Serbian',
                     'ss' => 'Siswati',
                     'st' => 'Sesotho',
                     'su' => 'Sundanese',
                     'sv' => 'Swedish',
                     'sw' => 'Swahili',
                     'ta' => 'Tamil',
                     'te' => 'Telugu',
                     'tg' => 'Tajik',
                     'th' => 'Thai',
                     'ti' => 'Tigrinya',
                     'tk' => 'Turkmen',
                     'tl' => 'Tagalog',
                     'tn' => 'Setswana',
                     'to' => 'Tonga',
                     'tr' => 'Turkish',
                     'ts' => 'Tsonga',
                     'tt' => 'Tatar',
                     'tw' => 'Twi',
                     'ug' => 'Uigur',
                     'uk' => 'Ukrainian',
                     'ur' => 'Urdu',
                     'uz' => 'Uzbek',
                     'vi' => 'Vietnamese',
                     'vo' => 'Volapuk',
                     'wo' => 'Wolof',
                     'xh' => 'Xhosa',
                     'yi' => 'Yiddish',
                     'yo' => 'Yoruba',
                     'za' => 'Zhuang',
                     'zh' => 'Chinese',
                     'zu' => 'Zulu');
        if ($lang) {
            $res = array_flip($res);
            ksort($res); //order by lang
        }
        return $res;
    }

    /**
     * Get the country codes.
     *
     * @param Indexed by code (false)
     * @return array English name indexed country code or reverse
     */
    public static function getCountryCodes($idx_by_code=false)
    {
        $ctr = array(
                     'Andorra' => 'AD',
                     'United Arab Emirates' => 'AE',
                     'Afghanistan' => 'AF',
                     'Antigua & Barbuda' => 'AG',
                     'Anguilla' => 'AI',
                     'Albania' => 'AL',
                     'Armenia' => 'AM',
                     'Netherlands Antilles' => 'AN',
                     'Angola' => 'AO',
                     'Antarctica' => 'AQ',
                     'Argentina' => 'AR',
                     'American Samoa' => 'AS',
                     'Austria' => 'AT',
                     'Australia' => 'AU',
                     'Aruba' => 'AW',
                     'Azerbaijan' => 'AZ',
                     'Bosnia and Herzegovina' => 'BA',
                     'Barbados' => 'BB',
                     'Bangladesh' => 'BD',
                     'Belgium' => 'BE',
                     'Burkina Faso' => 'BF',
                     'Bulgaria' => 'BG',
                     'Bahrain' => 'BH',
                     'Burundi' => 'BI',
                     'Benin' => 'BJ',
                     'Bermuda' => 'BM',
                     'Brunei Darussalam' => 'BN',
                     'Bolivia' => 'BO',
                     'Brazil' => 'BR',
                     'Bahama' => 'BS',
                     'Bhutan' => 'BT',
                     'Bouvet Island' => 'BV',
                     'Botswana' => 'BW',
                     'Belarus' => 'BY',
                     'Belize' => 'BZ',
                     'Canada' => 'CA',
                     'Cocos (Keeling) Islands' => 'CC',
                     'Central African Republic' => 'CF',
                     'Congo' => 'CG',
                     'Switzerland' => 'CH',
                     'Côte D\'ivoire (Ivory Coast)' => 'CI',
                     'Cook Iislands' => 'CK',
                     'Chile' => 'CL',
                     'Cameroon' => 'CM',
                     'China' => 'CN',
                     'Colombia' => 'CO',
                     'Costa Rica' => 'CR',
                     'Cuba' => 'CU',
                     'Cape Verde' => 'CV',
                     'Christmas Island' => 'CX',
                     'Cyprus' => 'CY',
                     'Czech Republic' => 'CZ',
                     'Germany' => 'DE',
                     'Djibouti' => 'DJ',
                     'Denmark' => 'DK',
                     'Dominica' => 'DM',
                     'Dominican Republic' => 'DO',
                     'Algeria' => 'DZ',
                     'Ecuador' => 'EC',
                     'Estonia' => 'EE',
                     'Egypt' => 'EG',
                     'Western Sahara' => 'EH',
                     'Eritrea' => 'ER',
                     'Spain' => 'ES',
                     'Ethiopia' => 'ET',
                     'Finland' => 'FI',
                     'Fiji' => 'FJ',
                     'Falkland Islands (Malvinas)' => 'FK',
                     'Micronesia' => 'FM',
                     'Faroe Islands' => 'FO',
                     'France' => 'FR',
                     'France, Metropolitan' => 'FX',
                     'Gabon' => 'GA',
                     'United Kingdom (Great Britain)' => 'GB',
                     'Grenada' => 'GD',
                     'Georgia' => 'GE',
                     'French Guiana' => 'GF',
                     'Ghana' => 'GH',
                     'Gibraltar' => 'GI',
                     'Greenland' => 'GL',
                     'Gambia' => 'GM',
                     'Guinea' => 'GN',
                     'Guadeloupe' => 'GP',
                     'Equatorial Guinea' => 'GQ',
                     'Greece' => 'GR',
                     'South Georgia and the South Sandwich Islands' => 'GS',
                     'Guatemala' => 'GT',
                     'Guam' => 'GU',
                     'Guinea-Bissau' => 'GW',
                     'Guyana' => 'GY',
                     'Hong Kong' => 'HK',
                     'Heard & McDonald Islands' => 'HM',
                     'Honduras' => 'HN',
                     'Croatia' => 'HR',
                     'Haiti' => 'HT',
                     'Hungary' => 'HU',
                     'Indonesia' => 'ID',
                     'Ireland' => 'IE',
                     'Israel' => 'IL',
                     'India' => 'IN',
                     'British Indian Ocean Territory' => 'IO',
                     'Iraq' => 'IQ',
                     'Iran, Islamic Republic of' => 'IR',
                     'Iceland' => 'IS',
                     'Italy' => 'IT',
                     'Jamaica' => 'JM',
                     'Jordan' => 'JO',
                     'Japan' => 'JP',
                     'Kenya' => 'KE',
                     'Kyrgyzstan' => 'KG',
                     'Cambodia' => 'KH',
                     'Kiribati' => 'KI',
                     'Comoros' => 'KM',
                     'St. Kitts and Nevis' => 'KN',
                     'Korea, Democratic People\'s Republic of' => 'KP',
                     'Korea, Republic of' => 'KR',
                     'Kuwait' => 'KW',
                     'Cayman Islands' => 'KY',
                     'Kazakhstan' => 'KZ',
                     'Lao People\'s Democratic Republic' => 'LA',
                     'Lebanon' => 'LB',
                     'Saint Lucia' => 'LC',
                     'Liechtenstein' => 'LI',
                     'Sri Lanka' => 'LK',
                     'Liberia' => 'LR',
                     'Lesotho' => 'LS',
                     'Lithuania' => 'LT',
                     'Luxembourg' => 'LU',
                     'Latvia' => 'LV',
                     'Libyan Arab Jamahiriya' => 'LY',
                     'Morocco' => 'MA',
                     'Monaco' => 'MC',
                     'Moldova, Republic of' => 'MD',
                     'Montenegro' => 'ME',
                     'Madagascar' => 'MG',
                     'Marshall Islands' => 'MH',
                     'Macedonia, Republic of' => 'MK',
                     'Mali' => 'ML',
                     'Mongolia' => 'MN',
                     'Myanmar' => 'MM',
                     'Macau' => 'MO',
                     'Northern Mariana Islands' => 'MP',
                     'Martinique' => 'MQ',
                     'Mauritania' => 'MR',
                     'Monserrat' => 'MS',
                     'Malta' => 'MT',
                     'Mauritius' => 'MU',
                     'Maldives' => 'MV',
                     'Malawi' => 'MW',
                     'Mexico' => 'MX',
                     'Malaysia' => 'MY',
                     'Mozambique' => 'MZ',
                     'Namibia' => 'NA',
                     'New Caledonia' => 'NC',
                     'Niger' => 'NE',
                     'Norfolk Island' => 'NF',
                     'Nigeria' => 'NG',
                     'Nicaragua' => 'NI',
                     'Netherlands' => 'NL',
                     'Norway' => 'NO',
                     'Nepal' => 'NP',
                     'Nauru' => 'NR',
                     'Niue' => 'NU',
                     'New Zealand' => 'NZ',
                     'Oman' => 'OM',
                     'Panama' => 'PA',
                     'Peru' => 'PE',
                     'French Polynesia' => 'PF',
                     'Papua New Guinea' => 'PG',
                     'Philippines' => 'PH',
                     'Pakistan' => 'PK',
                     'Poland' => 'PL',
                     'St. Pierre & Miquelon' => 'PM',
                     'Pitcairn' => 'PN',
                     'Puerto Rico' => 'PR',
                     'Portugal' => 'PT',
                     'Palau' => 'PW',
                     'Paraguay' => 'PY',
                     'Qatar' => 'QA',
                     'Réunion' => 'RE',
                     'Romania' => 'RO',
                     'Serbia' => 'RS',
                     'Russian Federation' => 'RU',
                     'Rwanda' => 'RW',
                     'Saudi Arabia' => 'SA',
                     'Solomon Islands' => 'SB',
                     'Seychelles' => 'SC',
                     'Sudan' => 'SD',
                     'Sweden' => 'SE',
                     'Singapore' => 'SG',
                     'St. Helena' => 'SH',
                     'Slovenia' => 'SI',
                     'Svalbard & Jan Mayen Islands' => 'SJ',
                     'Slovakia' => 'SK',
                     'Sierra Leone' => 'SL',
                     'San Marino' => 'SM',
                     'Senegal' => 'SN',
                     'Somalia' => 'SO',
                     'Suriname' => 'SR',
                     'Sao Tome & Principe' => 'ST',
                     'El Salvador' => 'SV',
                     'Syrian Arab Republic' => 'SY',
                     'Swaziland' => 'SZ',
                     'Turks & Caicos Islands' => 'TC',
                     'Chad' => 'TD',
                     'French Southern Territories' => 'TF',
                     'Togo' => 'TG',
                     'Thailand' => 'TH',
                     'Tajikistan' => 'TJ',
                     'Tokelau' => 'TK',
                     'Turkmenistan' => 'TM',
                     'Tunisia' => 'TN',
                     'Tonga' => 'TO',
                     'East Timor' => 'TP',
                     'Turkey' => 'TR',
                     'Trinidad & Tobago' => 'TT',
                     'Tuvalu' => 'TV',
                     'Taiwan' => 'TW',
                     'Tanzania, United Republic of' => 'TZ',
                     'Ukraine' => 'UA',
                     'Uganda' => 'UG',
                     'United States Minor Outlying Islands' => 'UM',
                     'United States of America' => 'US',
                     'Uruguay' => 'UY',
                     'Uzbekistan' => 'UZ',
                     'Vatican City State (Holy See)' => 'VA',
                     'St. Vincent & the Grenadines' => 'VC',
                     'Venezuela' => 'VE',
                     'British Virgin Islands' => 'VG',
                     'United States Virgin Islands' => 'VI',
                     'Viet Nam' => 'VN',
                     'Vanuatu' => 'VU',
                     'Wallis & Futuna Islands' => 'WF',
                     'Samoa' => 'WS',
                     'Yemen' => 'YE',
                     'Mayotte' => 'YT',
                     'South Africa' => 'ZA',
                     'Zambia' => 'ZM',
                     'Zaire' => 'ZR',
                     'Zimbabwe' => 'ZW',
                     'Unknown or unspecified country' => 'ZZ'
                     );
        if ($idx_by_code) {
            $ctr = array_flip($ctr);
        }
        ksort($ctr);
        return $ctr;
    }

    /**
     * Returns iso codes.
     *
     * @param bool Is the language the key in the array (false)
     * @return array The key is either the language or the iso code
     */
    public static function getNativeLanguages($lang=false)
    {
        $res = array(
                     'ab' => 'Аҧсуа',
                     'aa' => 'Afaraf',
                     'af' => 'Afrikaans',
                     'ak' => 'Akan',
                     'am' => 'አማርኛ',
                     'ar' => 'العربية',
                     'an' => 'Aragonés',
                     'as' => 'অসমীয়া',
                     'av' => 'авар мацӀ',
                     'ae' => 'avesta',
                     'ay' => 'aymar aru',
                     'az' => 'Azərbaycanca',
                     'bm' => 'bamanankan',
                     'ba' => 'башҡорт теле',
                     'be' => 'Беларуская мова',
                     'bn' => 'বাংলা',
                     'bh' => 'Bihari',
                     'bi' => 'Bislama',
                     'bs' => 'bosanski jezik',
                     'br' => 'brezhoneg',
                     'bg' => 'български език',
                     'ca' => 'català',
                     'ch' => 'Chamoru',
                     'ce' => 'нохчийн мотт',
                     'ny' => 'chiCheŵa',
                     'cu' => 'чӑваш чӗлхи',
                     'kw' => 'Kernewek',
                     'co' => 'corsu',
                     'cr' => 'ᓀᐦᐃᔭᐍᐏᐣ',
                     'hr' => 'hrvatski jezik',
                     'cs' => 'čeština',
                     'da' => 'dansk',
                     'dv' => 'ދިވެހިބަސ',
                     'dz' => 'རྫོང་ཁ',
                     'en' => 'English',
                     'eo' => 'Esperanto',
                     'et' => 'eesti keel',
                     'ee' => 'Ɛʋɛgbɛ',
                     'fo' => 'føroyskt',
                     'fj' => 'vosa Vakaviti',
                     'fi' => 'suomi',
                     'fr' => 'français',
                     'fy' => 'frysk',
                     'ff' => 'Fulfulde, Pulaar, Pular',
                     'gl' => 'Galego',
                     'lg' => 'Luganda',
                     'ka' => 'ქართული ენა',
                     'de' => 'Deutsch',
                     'el' => 'Ελληνικά',
                     'kl' => 'kalaallisut',
                     'gn' => 'Avañe\'ẽ',
                     'gu' => 'ગુજરાતી',
                     'ht' => 'Kreyòl ayisyen',
                     'ha' => 'Hausancī',
                     'he' => 'עִבְרִית',
                     'hz' => 'Otjiherero',
                     'hi' => 'हिन्दी',
                     'ho' => 'Hiri Motu',
                     'hu' => 'magyar',
                     'is' => 'íslenska',
                     'io' => 'Ido',
                     'ig' => 'Igbo',
                     'id' => 'Bahasa Indonesia',
                     'ia' => 'interlingua',
                     'ie' => 'Interlingue',
                     'iu' => 'ᐃᓄᒃᑎᑐᑦ',
                     'ik' => 'Iñupiaq',
                     'ga' => 'Gaeilge',
                     'it' => 'italiano',
                     'ja' => '日本語 (にほんご)',
                     'jv' => 'basa Jawa',
                     'kn' => 'ಕನ್ನಡ',
                     'kr' => 'कॉशुर',
                     'kk' => 'Қазақ тілі',
                     'km' => 'Central Khmer',
                     'ki' => 'Gĩkũyũ',
                     'rw' => 'kinyaRwanda',
                     'ky' => 'кыргыз тили',
                     'kv' => 'коми кыв',
                     'kg' => 'Kikongo',
                     'ko' => '한국어 (韓國語)',
                     'kj' => 'Kurdish',
                     'lo' => 'ພາສາລາວ',
                     'la' => 'latine',
                     'lv' => 'latviešu valoda',
                     'li' => 'Limburgs',
                     'ln' => 'lingala',
                     'lt' => 'lietuvių kalba',
                     'lu' => 'Luxembourgish',
                     'mg' => 'Malagasy fiteny',
                     'ms' => 'bahasa Melayu',
                     'ml' => 'മലയാളം',
                     'mt' => 'Malti',
                     'gv' => 'Gaelg',
                     'mi' => 'te reo Māori',
                     'mr' => 'मराठी',
                     'mh' => 'Kajin M̧ajeļ',
                     'mn' => 'монгол хэл',
                     'na' => 'Ekakairũ Naoero',
                     'nv' => 'Diné bizaad',
                     'nd' => 'isiNdebele',
                     'nr' => 'isiNdebele',
                     'ng' => 'Owambo',
                     'ne' => 'नेपाली',
                     'se' => 'sámi',
                     'no' => 'Norsk',
                     'nb' => 'Norsk bokmål',
                     'nn' => 'Norsk nynorsk',
                     'oc' => 'Occitan',
                     'oj' => 'ᐊᓂᔑᓇᐯᒧᐏᐣ (Anishinaabemowin)',
                     'or' => 'ଓଡ଼ିଆ',
                     'om' => 'Afaan Oromoo',
                     'os' => 'ирон ӕвзаг',
                     'pi' => 'पालि',
                     'fa' => 'فارسی',
                     'pl' => 'polski',
                     'pt' => 'português',
                     'pa' => 'ਪੰਜਾਬੀ',
                     'ps' => 'پښتو',
                     'qu' => 'Runa Simi',
                     'rm' => 'rumantsch grischun',
                     'rn' => 'kiRundi',
                     'ru' => 'русский язык',
                     'sm' => 'gagana fa\'a Samoa',
                     'sg' => 'yângâ tî sängö',
                     'sa' => 'संस्कृतम्',
                     'sc' => 'sardu',
                     'gd' => 'Gàidhlig',
                     'sr' => 'српски језик',
                     'sn' => 'chiShona',
                     'ii' => 'ꆇꉙ',
                     'sd' => 'سنڌي، سندھی',
                     'si' => 'සිංහල',
                     'sk' => 'slovenčina',
                     'sl' => 'slovenščina',
                     'so' => 'Soomaaliga',
                     'st' => 'Sesotho',
                     'es' => 'español',
                     'su' => 'basa Sunda',
                     'sw' => 'kiswahili',
                     'ss' => 'siSwati',
                     'sv' => 'Svenska',
                     'tl' => 'Tagalog',
                     'ty' => 'te reo Tahiti',
                     'tg' => 'тоҷикӣ',
                     'ta' => 'தமிழ்',
                     'tt' => 'татарча',
                     'te' => 'తెలుగు',
                     'th' => 'ภาษาไทย',
                     'bo' => 'བོད་ཡིག',
                     'ti' => 'ትግርኛ',
                     'to' => 'faka-Tonga',
                     'ts' => 'Xitsonga',
                     'tn' => 'Setswana',
                     'tr' => 'Türkçe',
                     'tk' => 'Түркмен',
                     'tw' => 'Twi',
                     'ug' => 'Uyƣurqə',
                     'uk' => 'українська мова',
                     'ur' => 'اردو',
                     'uz' => 'O\'zbek',
                     've' => 'Tshivenḓa',
                     'vi' => 'Tiếng Việt',
                     'vo' => 'Volapük',
                     'wa' => 'walon',
                     'cy' => 'Cymraeg',
                     'wo' => 'Wolof',
                     'xh' => 'isiXhosa',
                     'yi' => 'ייִדיש',
                     'yo' => 'Yorùbá',
                     'za' => 'Saɯ cueŋƅ',
                     'zu' => 'isiZulu',
                     );
        if ($lang) {
            $res = array_flip($res);
            ksort($res); //order by lang
        }
        return $res;
    }

    public static function getInstalledLanguages()
    {
        $l = array();
        $nl = self::getNativeLanguages();
        foreach (Pluf::f('languages', array('en')) as $lang) {
            $lang2 = substr($lang, 0, 2);
            $l[mb_convert_case($nl[$lang2], MB_CASE_TITLE, 'UTF-8')] = $lang;
        }
        return $l;
    }
}
