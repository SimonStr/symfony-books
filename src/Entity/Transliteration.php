<?php

namespace App\Entity;


class Transliteration
{
    private const CYR_TO_LAT = [
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'ґ' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'e',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'y',
        'і' => 'i',
        'ї' => 'i',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'ts',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'sht',
        'ь' => '',
        'ы' => 'y',
        'ъ' => '',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
        'А' => 'a',
        'Б' => 'b',
        'В' => 'v',
        'Г' => 'g',
        'Ґ' => 'g',
        'Д' => 'd',
        'Е' => 'e',
        'Ж' => 'zh',
        'З' => 'z',
        'И' => 'i',
        'Й' => 'y',
        'І' => 'i',
        'Ї' => 'yi',
        'К' => 'k',
        'Л' => 'l',
        'М' => 'm',
        'Н' => 'n',
        'О' => 'o',
        'П' => 'p',
        'Р' => 'r',
        'С' => 's',
        'Т' => 't',
        'У' => 'u',
        'Ф' => 'f',
        'Х' => 'h',
        'Ц' => 'ts',
        'Ч' => 'ch',
        'Ш' => 'sh',
        'Щ' => 'sht',
        'Ь' => '',
        'Ы' => 'y',
        'Ъ' => '',
        'Э' => 'e',
        'Ю' => 'yu',
        'Я' => 'ya',
        "'" => '',
        " " => '-',
    ];

    /**
     * @param string $cyrText
     *
     * @return string
     */
    public static function transliterate(string $cyrText): string
    {
        $latText = '';
        if (\mb_strlen($cyrText) > 0) {
            $latText = \str_replace(
                \array_keys(self::CYR_TO_LAT),
                \array_values(self::CYR_TO_LAT),
                $cyrText
            );
            $latText = preg_replace("/[^ \w]+/", "", $latText);
        }
        return $latText;
    }

}