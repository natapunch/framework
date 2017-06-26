<?php


namespace Punchenko\Framework\Validation;


interface InterfaceRule
{
    /**
     * @param string $field_name
     * @param $field_value
     * @param array $params
     * @return bool
     */
    public function check(string $field_name, $field_value, array $params): bool;


    /**
     * @param string $field_name
     * @param $field_value
     * @param array $params
     * @return string
     */
    public function getError(string $field_name, $field_value, array $params):string;

}