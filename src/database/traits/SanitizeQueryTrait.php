<?php
namespace Database\Traits;

trait SanitizeQueryTrait
{
    public function entityEncode($input)
    {
        foreach ($input as &$value) {
            $value = htmlentities($value);
        }
        return $input;
    }

    public function entityDecode($input)
    {
        foreach ($input as &$value) {
            $value = html_entity_decode($value);
        }
        return $input;
    }
}