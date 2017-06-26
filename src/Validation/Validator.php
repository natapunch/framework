<?php
	namespace Punchenko\Framework\Validation;

	use Punchenko\Framework\Validation\Exception\RuleNotFoundException;

	class Validator
	{

		const DIR_RULE = 'Punchenko\Framework\Validation\Rules\\';
		protected static $known_rules = [
			'numeric' => self::DIR_RULE . 'NumericValidate',
			'length' => self::DIR_RULE . 'Length'
		];
		public $request;
		public $errors = [];
		private $rules = [];

		/**
		 * Validator constructor.
		 * @param $request
		 * @param array $rules
		 */
		public function __construct($request, array $rules)
		{
			$this->request = $request;
			$this->rules = $rules;
		}

		/**
		 * Adds new validation rules
		 * @param string $key
		 * @param string $class_namespace
		 * @return bool
		 */
		public static function addValidationRule(string $key, string $class_namespace): bool
		{
			if (class_exists($class_namespace)) {
				self::$known_rules[$key] = $class_namespace;
				return true;
			}
			return false;
		}

		/**
		 * Validates specified object by rules
		 * @return bool
		 * @throws RuleNotFoundException
		 */
		public function validate(): bool
		{
			$result = true;
			foreach ($this->rules as $field_name => $field_rules) {
				foreach ($field_rules as $field_rule) {
					$exploded_rule = explode(":", $field_rule);
					$rule_key = $exploded_rule[0];
					if (!array_key_exists($rule_key, self::$known_rules)) {
						throw new RuleNotFoundException ("Rule \"$rule_key\" not found");
						continue;
					}
					$rule_params = [];
					if (count($exploded_rule) > 1) {
						$rule_params = explode(",", $exploded_rule[1]);
					}
					/** @var InterfaceRule $validation_class */
					$validation_class = new self::$known_rules[$rule_key];
					$field_value = isset($this->request->$field_name) ? $this->request->$field_name : null;

					if (!$validation_class->check($field_name, $field_value, $rule_params)) {
						$result = false;
						$this->errors[$field_name][] = $validation_class->getError(
							$field_name, $field_value, $rule_params
						);
					}
				}
			}
			return $result;
		}

		/**
		 * Returns validation errors
		 *
		 * @return array
		 */
		public function getErrors(): array
		{
			return $this->errors;
		}
	}