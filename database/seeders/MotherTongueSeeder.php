<?php

namespace Database\Seeders;

use App\Models\MotherTongue;
use Illuminate\Database\Seeder;

class MotherTongueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $languages_list = array (
            'af' => 'Afrikaans',
            'egy' => 'Ägyptisch',
            'sq' => 'Albanisch',
            'ar' => 'Arabisch',
            'arc' => 'Aramäisch',
            'hy' => 'Armenisch',
            'az' => 'Aserbaidschanisch',
            'eu' => 'Baskisch',
            'bn' => 'Bengalisch',
            'bs' => 'Bosnisch',
            'bg' => 'Bulgarisch',
            'zh' => 'Chinesisch',
            'da' => 'Dänisch',
            'de' => 'Deutsch',
            'en' => 'Englisch',
            'et' => 'Estnisch',
            'fil' => 'Filipino',
            'fi' => 'Finnisch',
            'nl_BE' => 'Flämisch',
            'fr' => 'Französisch',
            'ka' => 'Georgisch',
            'el' => 'Griechisch',
            'ht' => 'Haitianisch',
            'nl_aw' => 'Hawaiisch',
            'he' => 'Hebräisch',
            'hi' => 'Hindi',
            'id' => 'Indonesisch',
            'ga' => 'Irisch',
            'is' => 'Isländisch',
            'it' => 'Italienisch',
            'ja' => 'Japanisch',
            'km' => 'Kambodschanisch',
            'kg' => 'Kongolesisch',
            'ko' => 'Koreanisch',
            'co' => 'Korsisch',
            'hr' => 'Kroatisch',
            'ku' => 'Kurdisch',
            'lo' => 'Laotisch',
            'lv' => 'Lettisch',
            'lij' => 'Ligurisch',
            'lt' => 'Litauisch',
            'lb' => 'Luxemburgisch',
            'ms' => 'Malaiisch',
            'mk' => 'Mazedonisch',
            'mn' => 'Mongolisch',
            'ne' => 'Nepalesisch',
            'no' => 'Norwegisch',
            'ota' => 'Osmanisch',
            'fa' => 'Persisch',
            'pl' => 'Polnisch',
            'pt' => 'Portugiesisch',
            'rm' => 'Rätoromanisch',
            'ro' => 'Rumänisch',
            'ru' => 'Russisch',
            'sco' => 'Schottisch',
            'sv' => 'Schwedisch',
            'sr' => 'Serbisch',
            'sh' => 'Serbo-Kroatisch',
            'sk' => 'Slowakisch',
            'sl' => 'Slowenisch',
            'es' => 'Spanisch',
            'syr' => 'Syrisch',
            'ta' => 'Tamilisch',
            'th' => 'Thailändisch',
            'bo' => 'Tibetisch',
            'cs' => 'Tschechisch',
            'ce' => 'Tschetschenisch',
            'tr' => 'Türkisch',
            'tk' => 'Turkmenisch',
            'uk' => 'Ukrainisch',
            'hu' => 'Ungarisch',
            'uz' => 'Usbekisch',
            'vi' => 'Vietnamesisch',
            'zu' => 'Zulu',
            );

        foreach ($languages_list as $key=>$language) {
            MotherTongue::create(['name' => $language]);
        }
    }
}
