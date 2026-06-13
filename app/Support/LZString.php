<?php
namespace App\Support;

class LZString
{
    private static string $keyStrUriSafe = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+-$';

    public static function decompressFromEncodedURIComponent(?string $input): ?string
    {
        if ($input === null || $input === '') return $input;
        $input = str_replace(' ', '+', $input);
        return self::decompress(strlen($input), 32, function($index) use ($input) {
            return strpos(self::$keyStrUriSafe, $input[$index]);
        });
    }

    private static function decompress(int $length, int $resetValue, callable $getNextValue): ?string
    {
        $dictionary = [0, 1, 2];
        $enlargeIn = 4; $dictSize = 4; $numBits = 3; $entry = '';
        $result = [];
        $data = ['val' => $getNextValue(0), 'position' => $resetValue, 'index' => 1];
        $bits = 0; $maxpower = 2; $power = 1;
        while ($power != $maxpower) { $resb = $data['val'] & $data['position']; $data['position'] >>= 1; if ($data['position'] == 0) { $data['position'] = $resetValue; $data['val'] = $getNextValue($data['index']++); } $bits |= ($resb > 0 ? 1 : 0) * $power; $power <<= 1; }
        switch ($bits) {
            case 0:
                $bits = 0; $maxpower = 256; $power = 1;
                while ($power != $maxpower) { $resb = $data['val'] & $data['position']; $data['position'] >>= 1; if ($data['position'] == 0) { $data['position'] = $resetValue; $data['val'] = $getNextValue($data['index']++); } $bits |= ($resb > 0 ? 1 : 0) * $power; $power <<= 1; }
                $c = chr($bits); break;
            case 1:
                $bits = 0; $maxpower = 65536; $power = 1;
                while ($power != $maxpower) { $resb = $data['val'] & $data['position']; $data['position'] >>= 1; if ($data['position'] == 0) { $data['position'] = $resetValue; $data['val'] = $getNextValue($data['index']++); } $bits |= ($resb > 0 ? 1 : 0) * $power; $power <<= 1; }
                $c = self::unichr($bits); break;
            case 2: return '';
            default: $c = '';
        }
        $dictionary[3] = $c; $w = $c; $result[] = $c;
        while (true) {
            if ($data['index'] > $length) return '';
            $bits = 0; $maxpower = 1 << $numBits; $power = 1;
            while ($power != $maxpower) { $resb = $data['val'] & $data['position']; $data['position'] >>= 1; if ($data['position'] == 0) { $data['position'] = $resetValue; $data['val'] = $getNextValue($data['index']++); } $bits |= ($resb > 0 ? 1 : 0) * $power; $power <<= 1; }
            $c = $bits;
            switch ($c) {
                case 0:
                    $bits = 0; $maxpower = 256; $power = 1;
                    while ($power != $maxpower) { $resb = $data['val'] & $data['position']; $data['position'] >>= 1; if ($data['position'] == 0) { $data['position'] = $resetValue; $data['val'] = $getNextValue($data['index']++); } $bits |= ($resb > 0 ? 1 : 0) * $power; $power <<= 1; }
                    $dictionary[$dictSize++] = chr($bits); $c = $dictSize - 1; $enlargeIn--; break;
                case 1:
                    $bits = 0; $maxpower = 65536; $power = 1;
                    while ($power != $maxpower) { $resb = $data['val'] & $data['position']; $data['position'] >>= 1; if ($data['position'] == 0) { $data['position'] = $resetValue; $data['val'] = $getNextValue($data['index']++); } $bits |= ($resb > 0 ? 1 : 0) * $power; $power <<= 1; }
                    $dictionary[$dictSize++] = self::unichr($bits); $c = $dictSize - 1; $enlargeIn--; break;
                case 2: return implode('', $result);
            }
            if ($enlargeIn == 0) { $enlargeIn = 1 << $numBits; $numBits++; }
            if (isset($dictionary[$c])) $entry = $dictionary[$c];
            elseif ($c == $dictSize) $entry = $w . mb_substr($w,0,1,'UTF-8');
            else return null;
            $result[] = $entry;
            $dictionary[$dictSize++] = $w . mb_substr($entry,0,1,'UTF-8');
            $enlargeIn--; $w = $entry;
            if ($enlargeIn == 0) { $enlargeIn = 1 << $numBits; $numBits++; }
        }
    }

    private static function unichr(int $u): string
    {
        return mb_convert_encoding('&#'.$u.';', 'UTF-8', 'HTML-ENTITIES');
    }
}
