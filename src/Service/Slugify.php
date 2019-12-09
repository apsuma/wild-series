<?php


namespace App\Service;


class Slugify
{
    public function generate(string $input) : string
    {
        $input =  mb_strtolower(trim($input));
        $input = str_replace(' ', '-', $input);
        $input = preg_replace('/[^a-z0-9-]/', '', $input);
        return $input;
    }
}