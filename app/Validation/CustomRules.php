<?php

namespace App\Validation;

class CustomRules
{
    public function after_or_equal(string $str, string $fields, array $data): bool
    {
        if (empty($str) || empty($data[$fields])) {
            return false;
        }

        return strtotime($str) >= strtotime($data[$fields]);
    }
}