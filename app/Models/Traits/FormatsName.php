<?php

namespace App\Models\Traits;

trait FormatsName
{
    protected function formatName($value)
    {
        $value = preg_replace('/\s+/', ' ', trim($value));
        $value = strtolower($value);

        $exceptions = ['de', 'del', 'dela', 'la', 'van', 'von'];

        $words = explode(' ', $value);

        $words = array_map(function ($word) use ($exceptions) {
            return in_array($word, $exceptions)
                ? $word
                : ucfirst($word);
        }, $words);

        return implode(' ', $words);
    }
}