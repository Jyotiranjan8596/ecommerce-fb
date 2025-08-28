<?php
namespace App\Helpers;

class NumberToWordsHelper
{
    /**
     * Convert number to words
     *
     * @param float|int $number
     * @return string
     */
    public static function convert($number)
    {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'Negative ';
        $decimal     = ' point ';
        $dictionary  = [
            0          => 'Zero',
            1          => 'One',
            2          => 'Two',
            3          => 'Three',
            4          => 'Four',
            5          => 'Five',
            6          => 'Six',
            7          => 'Seven',
            8          => 'Eight',
            9          => 'Nine',
            10         => 'Ten',
            11         => 'Eleven',
            12         => 'Twelve',
            13         => 'Thirteen',
            14         => 'Fourteen',
            15         => 'Fifteen',
            16         => 'Sixteen',
            17         => 'Seventeen',
            18         => 'Eighteen',
            19         => 'Nineteen',
            20         => 'Twenty',
            30         => 'Thirty',
            40         => 'Forty',
            50         => 'Fifty',
            60         => 'Sixty',
            70         => 'Seventy',
            80         => 'Eighty',
            90         => 'Ninety',
            100        => 'Hundred',
            1000       => 'Thousand',
            1000000    => 'Million',
            1000000000 => 'Billion',
        ];

        if (! is_numeric($number)) {
            return false;
        }

        if ($number < 0) {
            return $negative . self::convert(abs($number));
        }

        $string = '';

        // Split decimal and integer part
        $number_parts = explode('.', (string) $number);
        $int_part     = (int) $number_parts[0];
        $dec_part     = isset($number_parts[1]) ? (int) $number_parts[1] : null;

        // Convert integer part
        switch (true) {
            case $int_part < 21:
                $string = $dictionary[$int_part];
                break;
            case $int_part < 100:
                $tens   = ((int) ($int_part / 10)) * 10;
                $units  = $int_part % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $int_part < 1000:
                $hundreds  = (int) ($int_part / 100);
                $remainder = $int_part % 100;
                $string    = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . self::convert($remainder);
                }
                break;
            default:
                foreach ([1000000000 => 'Billion', 1000000 => 'Million', 1000 => 'Thousand'] as $value => $word) {
                    if ($int_part >= $value) {
                        $base      = (int) ($int_part / $value);
                        $remainder = $int_part % $value;
                        $string    = self::convert($base) . ' ' . $word;
                        if ($remainder) {
                            $string .= $separator . self::convert($remainder);
                        }
                        break;
                    }
                }
                break;
        }

        // Convert decimal part if exists
        if ($dec_part !== null && $dec_part > 0) {
            $string .= $conjunction;
            $string .= self::convert($dec_part) . ' Paise';
        }

        return ucfirst($string);
    }
}
