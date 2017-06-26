<?php

namespace Punchenko\Framework\Validation\Rules;


use Punchenko\Framework\Validation\InterfaceRule;

class NumericValidate implements InterfaceRule
{

    /**
     * @param string $field_name
     * @param $field_value
     * @param array $params
     * @return bool
     */
    public function check(string $field_name, $field_value, array $params): bool
    {
      return is_numeric($field_value);
    }

    /**
     * @param string $field_name
     * @param $field_value
     * @param array $params
     * @return string
     */
    public function getError(string $field_name, $field_value, array $params): string
    {
       return "Field $field_name should be numeric";
    }
}