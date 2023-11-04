<?php

use App\Models\StoreVisits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;



/**
 * Get Next Auto-increment id
 *
 * @param string $table
 * @return string
 */
function nextIncrementId(string $table): string
{
    // $statement = DB::select("show table status like '{$table}'");
    // return $statement[0]->Auto_increment;

    //Temporary solutions
    return DB::table($table)->max('id') + 1;
}

function nextIncrementIdByCompany(string $table): string
{
    $company_id = Auth::user()->company_id;
    return DB::table($table)->where('company_id', $company_id)->max('id_by_company') + 1;
}





/**
 * Get Next Auto-increment id with prefix
 *
 * @param string $table
 * @param string $prefix
 * @return string
 */
function nextId(string $table, string $prefix = ''): string
{
    return $prefix . sprintf("%05d", nextIncrementId($table));
}

function nextIdByCompany(string $table, string $prefix = ''): string
{
    return $prefix . sprintf("%05d", nextIncrementIdByCompany($table));
}



/**
 * Get Country Code by country abbreviation
 *
 * @param String $abbribiation
 * @return void
 */
function getCountryCodeByAbbreviation($abbribiation)
{
    $countries =  Cache::rememberForever('countries', function () {
        $countries = file_get_contents(resource_path('js/countries.json'));
        return json_decode($countries, true);
    });

    $dialCode = null;

    foreach ($countries as $country) {
        if (isset($country['dialCode']) && (strtolower($abbribiation) === strtolower($country['iso']))) {
            $dialCode = $country['dialCode'];
            break;
        }
    }
    return $dialCode;
}

/**
 * @param String $column_prfix
 */

function getColumnByUserLanguage($resource, $column_prefix, $language_name = 'english')
{
    $language_name = strtolower($language_name);
    $language_convertions = [
        'english' => 'english',
        'french' => 'francis',
        'german' => 'deutsch'
    ];
    $language_name = $language_convertions[$language_name] ?? $language_name;
    $field_name = $column_prefix . '_' . $language_name;

    return isset($resource->$field_name) ? $resource->$field_name : (isset($resource[$field_name]) ? $resource[$field_name] : null);
}


function getPriceRanges($min=0, $max=100, $step=5)
{
    $start = 0;
    $priceRange = [];
    while($min < $max && $min != $max){
        $start= (($start+$step) > $max) ? $max : $start+$step;
        array_push($priceRange, ["name" => "$min-$start", "value" => "$min-$start"]);
        $min = $start;
    }
    array_push($priceRange, ["name" => "Other preis", "value" => "other_preis"]);
    return $priceRange;
}

function file_get_contents_curl( $url ) {

    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_AUTOREFERER, TRUE );
    curl_setopt( $ch, CURLOPT_HEADER, 0 );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, TRUE );

    $data = curl_exec( $ch );
    curl_close( $ch );

    return $data;

}


function getCountryNameFromCode($code)
{

 $countries = [
         "AF" => "Afghanistan",
         "EG" => "Ägypten",
         "AX" => "Åland",
         "AL" => "Albanien",
         "DZ" => "Algerien",
         "AS" => "Amerikanisch-Samoa",
         "VI" => "Amerikanische Jungferninseln",
         "AD" => "Andorra",
         "AO" => "Angola",
         "AI" => "Anguilla",
         "AQ" => "Antarktis",
         "AG" => "Antigua und Barbuda",
         "GQ" => "Äquatorialguinea",
         "AR" => "Argentinien",
         "AM" => "Armenien",
         "AW" => "Aruba",
         "AZ" => "Aserbaidschan",
         "ET" => "Äthiopien",
         "AU" => "Australien",
         "BS" => "Bahamas",
         "BH" => "Bahrain",
         "BD" => "Bangladesch",
         "BB" => "Barbados",
         "BY" => "Belarus",
         "BE" => "Belgien",
         "BZ" => "Belize",
         "BJ" => "Benin",
         "BM" => "Bermuda",
         "BT" => "Bhutan",
         "BO" => "Bolivien",
         "BQ" => "Bonaire",
         "BA" => "Bosnien und Herzegowina",
         "BW" => "Botswana",
         "BV" => "Bouvetinsel",
         "BR" => "Brasilien",
         "VG" => "Britische Jungferninseln",
         "IO" => "Britisches Territorium im Indischen Ozean",
         "BN" => "Brunei Darussalam",
         "BG" => "Bulgarien",
         "BF" => "Burkina Faso",
         "BI" => "Burundi",
         "CL" => "Chile",
         "CN" => "China",
         "CK" => "Cookinseln",
         "CR" => "Costa Rica",
         "CI" => "Elfenbeinküste",
         "CW" => "Curaçao",
         "DK" => "Dänemark",
         "DE" => "Deutschland",
         "DM" => "Dominica",
         "DO" => "Dominikanische Republik",
         "DJ" => "Dschibuti",
         "EC" => "Ecuador",
         "SV" => "El Salvador",
         "ER" => "Eritrea",
         "EE" => "Estland",
         "FK" => "Falklandinseln",
         "FO" => "Färöer",
         "FJ" => "Fidschi",
         "FI" => "Finnland",
         "FR" => "Frankreich",
         "GF" => "Französisch-Guayana",
         "PF" => "Französisch-Polynesien",
         "TF" => "Französische Süd- und Antarktisgebiete",
         "GA" => "Gabun",
         "GM" => "Gambia",
         "GE" => "Georgien",
         "GH" => "Ghana",
         "GI" => "Gibraltar",
         "GD" => "Grenada",
         "GR" => "Griechenland",
         "GL" => "Grönland",
         "GP" => "Guadeloupe",
         "GU" => "Guam",
         "GT" => "Guatemala",
         "GG" => "Guernsey",
         "GN" => "Guinea",
         "GW" => "Guinea-Bissau",
         "GY" => "Guyana",
         "HT" => "Haiti",
         "HM" => "Heard und McDonaldinseln",
         "HN" => "Honduras",
         "HK" => "Hongkong",
         "IN" => "Indien",
         "ID" => "Indonesien",
         "IM" => "Insel Man",
         "IQ" => "Irak",
         "IR" => "Iran",
         "IE" => "Irland",
         "IS" => "Island",
         "IL" => "Israel",
         "IT" => "Italien",
         "JM" => "Jamaika",
         "JP" => "Japan",
         "YE" => "Jemen",
         "JE" => "Jersey",
         "JO" => "Jordanien",
         "KY" => "Kaimaninseln",
         "KH" => "Kambodscha",
         "CM" => "Kamerun",
         "CA" => "Kanada",
         "CV" => "Kap Verde",
         "KZ" => "Kasachstan",
         "QA" => "Katar",
         "KE" => "Kenia",
         "KG" => "Kirgisistan",
         "KI" => "Kiribati",
         "CC" => "Kokosinseln",
         "CO" => "Kolumbien",
         "KM" => "Komoren",
         "CD" => "Kongo",
         "KP" => "Nordkorea",
         "KR" => "Südkorea",
         "HR" => "Kroatien",
         "CU" => "Kuba",
         "KW" => "Kuwait",
         "LA" => "Laos",
         "LS" => "Lesotho",
         "LV" => "Lettland",
         "LB" => "Libanon",
         "LR" => "Liberia",
         "LY" => "Libyen",
         "LI" => "Liechtenstein",
         "LT" => "Litauen",
         "LU" => "Luxemburg",
         "MO" => "Macao",
         "MG" => "Madagaskar",
         "MW" => "Malawi",
         "MY" => "Malaysia",
         "MV" => "Malediven",
         "ML" => "Mali",
         "MT" => "Malta",
         "MA" => "Marokko",
         "MH" => "Marshallinseln",
         "MQ" => "Martinique",
         "MR" => "Mauretanien",
         "MU" => "Mauritius",
         "YT" => "Mayotte",
         "MX" => "Mexiko",
         "FM" => "Mikronesien",
         "MD" => "Moldawien",
         "MC" => "Monaco",
         "MN" => "Mongolei",
         "ME" => "Montenegro",
         "MS" => "Montserrat",
         "MZ" => "Mosambik",
         "MM" => "Myanmar",
         "NA" => "Namibia",
         "NR" => "Nauru",
         "NP" => "Nepal",
         "NC" => "Neukaledonien",
         "NZ" => "Neuseeland",
         "NI" => "Nicaragua",
         "NL" => "Niederlande",
         "NE" => "Niger",
         "NG" => "Nigeria",
         "NU" => "Niue",
         "MK" => "Nordmazedonien",
         "MP" => "Nördliche Marianen",
         "NF" => "Norfolkinsel",
         "NO" => "Norwegen",
         "OM" => "Oman",
         "AT" => "Österreich",
         "TL" => "Osttimor",
         "PK" => "Pakistan",
         "PS" => "Staat Palästina",
         "PW" => "Palau",
         "PA" => "Panama",
         "PG" => "Papua-Neuguinea",
         "PY" => "Paraguay",
         "PE" => "Peru",
         "PH" => "Philippinen",
         "PN" => "Pitcairninseln",
         "PL" => "Polen",
         "PT" => "Portugal",
         "PR" => "Puerto Rico",
         "TW" => "Taiwan",
         "CG" => "Republik Kongo",
         "RE" => "Réunion",
         "RW" => "Ruanda",
         "RO" => "Rumänien",
         "RU" => "Russische Föderation",
         "BL" => "Saint-Barthélemy",
         "MF" => "Saint-Martin",
         "SB" => "Salomonen",
         "ZM" => "Sambia",
         "WS" => "Samoa",
         "SM" => "San Marino",
         "ST" => "São Tomé und Príncipe",
         "SA" => "Saudi-Arabien",
         "SE" => "Schweden",
         "CH" => "Schweiz",
         "SN" => "Senegal",
         "RS" => "Serbien",
         "SC" => "Seychellen",
         "SL" => "Sierra Leone",
         "ZW" => "Simbabwe",
         "SG" => "Singapur",
         "SX" => "Sint Maarten",
         "SK" => "Slowakei",
         "SI" => "Slowenien",
         "SO" => "Somalia",
         "ES" => "Spanien",
         "LK" => "Sri Lanka",
         "SH" => "St. Helena",
         "KN" => "St. Kitts und Nevis",
         "LC" => "St. Lucia",
         "PM" => "Saint-Pierre und Miquelon",
         "VC" => "St. Vincent und die Grenadinen",
         "ZA" => "Südafrika",
         "SD" => "Sudan",
         "GS" => "Südgeorgien und die Südlichen Sandwichinseln",
         "SS" => "Südsudan",
         "SR" => "Suriname",
         "SJ" => "Svalbard und Jan Mayen",
         "SZ" => "Eswatini",
         "SY" => "Syrien, Arabische Republik",
         "TJ" => "Tadschikistan",
         "TZ" => "Tansania, Vereinigte Republik",
         "TH" => "Thailand",
         "TG" => "Togo",
         "TK" => "Tokelau",
         "TO" => "Tonga",
         "TT" => "Trinidad und Tobago",
         "TD" => "Tschad",
         "CZ" => "Tschechische Republik",
         "TN" => "Tunesien",
         "TR" => "Türkei",
         "TM" => "Turkmenistan",
         "TC" => "Turks- und Caicosinseln",
         "TV" => "Tuvalu",
         "UG" => "Uganda",
         "UA" => "Ukraine",
         "HU" => "Ungarn",
         "UM" => "United States Minor Outlying Islands",
         "UY" => "Uruguay",
         "UZ" => "Usbekistan",
         "VU" => "Vanuatu",
         "VA" => "Vatikanstadt",
         "VE" => "Venezuela",
         "AE" => "Vereinigte Arabische Emirate",
         "US" => "Vereinigte Staaten von Amerika",
         "GB" => "Vereinigtes Königreich",
         "VN" => "Vietnam",
         "WF" => "Wallis und Futuna",
         "CX" => "Weihnachtsinsel",
         "EH" => "Westsahara",
         "CF" => "Zentralafrikanische Republik",
         "CY" => "Zypern",
         "XK" => "Kosovo"
    ];

    $countryCode = strtoupper($code);

    if(isset($countries[$countryCode])){
        return $countries[$countryCode];
    }

    return null;
}

