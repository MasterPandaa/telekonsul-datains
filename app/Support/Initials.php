<?php

namespace App\Support;

use Illuminate\Support\Str;

class Initials
{
    /**
     * Generate initials from a given name.
     */
    public static function from(?string $name, int $maxLetters = 2): string
    {
        if (blank($name ?? null)) {
            return '?';
        }

        $name = trim(preg_replace('/\s+/', ' ', (string) $name));
        if ($name === '') {
            return '?';
        }

        $words = array_filter(explode(' ', $name));
        if (empty($words)) {
            return '?';
        }

        // If the name consists of a single word return the first character only.
        if (count($words) === 1) {
            return Str::upper(Str::substr($words[0], 0, 1));
        }

        $initials = '';
        foreach ($words as $word) {
            $initials .= Str::upper(Str::substr($word, 0, 1));
            if (Str::length($initials) >= $maxLetters) {
                break;
            }
        }

        return Str::upper(Str::substr($initials, 0, $maxLetters));
    }
}
