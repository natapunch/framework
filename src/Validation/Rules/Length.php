<?php
namespace Punchenko\Framework\Validation\Rules;
use Punchenko\Framework\Validation\InterfaceRule;

class Length implements InterfaceRule
{
    /**
     * @param string $field_name
     * @param $field_value
     * @param array $params
     * @return bool
     */
    public function check(string $field_name, $field_value, array $params): bool
    {
        $string_length = strlen($field_value);
        return $params[0] <= $string_length && $string_length <= $params[1];
    }

    /**
     * @param string $field_name
     * @param $field_value
     * @param array $params
     * @return string
     */
    public function getError(string $field_name, $field_value, array $params): string
    {
        return "Field length $field_name must be between " . $params[0] . "and " . $params[1];
    }
}