<?php
/**
 * GenzNewz — Form Validation Class
 */

class Validator {
    private array $errors = [];
    private array $data = [];

    public function __construct(array $data) {
        $this->data = $data;
    }

    public static function make(array $data, array $rules): self {
        $validator = new self($data);
        $validator->validate($rules);
        return $validator;
    }

    public function validate(array $rules): void {
        foreach ($rules as $field => $fieldRules) {
            $ruleList = is_string($fieldRules) ? explode('|', $fieldRules) : $fieldRules;
            $value = $this->data[$field] ?? null;

            foreach ($ruleList as $rule) {
                $params = [];
                if (str_contains($rule, ':')) {
                    [$ruleName, $paramStr] = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                } else {
                    $ruleName = $rule;
                }

                $this->applyRule($field, $ruleName, $value, $params);
            }
        }
    }

    private function applyRule(string $field, string $rule, mixed $value, array $params): void {
        switch ($rule) {
            case 'required':
                if ($value === null || $value === '' || (is_array($value) && empty($value))) {
                    $this->addError($field, "{$field} ক্ষেত্রটি আবশ্যক।");
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "একটি বৈধ ইমেইল ঠিকানা প্রদান করুন।");
                }
                break;

            case 'min':
                $min = (int)($params[0] ?? 0);
                if (!empty($value) && mb_strlen((string)$value, 'UTF-8') < $min) {
                    $this->addError($field, "কমপক্ষে {$min} অক্ষর হতে হবে।");
                }
                break;

            case 'max':
                $max = (int)($params[0] ?? 255);
                if (!empty($value) && mb_strlen((string)$value, 'UTF-8') > $max) {
                    $this->addError($field, "সর্বোচ্চ {$max} অক্ষরের মধ্যে হতে হবে।");
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, "শুধুমাত্র সংখ্যা হতে হবে।");
                }
                break;

            case 'unique':
                $table = $params[0] ?? '';
                $column = $params[1] ?? $field;
                $exceptId = $params[2] ?? null;
                $idColumn = $params[3] ?? 'id';

                if (!empty($value) && !empty($table)) {
                    $db = Database::getConnection();
                    $sql = "SELECT 1 FROM {$table} WHERE {$column} = ?";
                    $binds = [$value];
                    if ($exceptId !== null) {
                        $sql .= " AND {$idColumn} != ?";
                        $binds[] = $exceptId;
                    }
                    $stmt = $db->prepare($sql);
                    $stmt->execute($binds);
                    if ($stmt->fetch()) {
                        $this->addError($field, "এই {$field} ইতিমধ্যে ব্যবহৃত হয়েছে। অন্য একটি প্রদান করুন।");
                    }
                }
                break;
        }
    }

    public function addError(string $field, string $message): void {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }

    public function fails(): bool {
        return !empty($this->errors);
    }

    public function passes(): bool {
        return empty($this->errors);
    }

    public function errors(): array {
        return $this->errors;
    }

    public function firstError(): ?string {
        return reset($this->errors) ?: null;
    }
}
