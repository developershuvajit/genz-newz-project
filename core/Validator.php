<?php
class Validator {
    private $errors = [];
    private $data = [];
    
    public function validate($data, $rules) {
        $this->data = $data;
        $this->errors = [];
        
        foreach ($rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            foreach ($rules as $rule) {
                $this->applyRule($field, $rule);
            }
        }
        
        return empty($this->errors);
    }
    
    private function applyRule($field, $rule) {
        $value = $this->data[$field] ?? null;
        
        if ($rule === 'required' && empty($value)) {
            $this->errors[$field][] = "The $field field is required.";
        }
        
        if ($rule === 'email' && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "The $field must be a valid email address.";
        }
        
        if (strpos($rule, 'min:') === 0) {
            $min = substr($rule, 4);
            if (!empty($value) && strlen($value) < $min) {
                $this->errors[$field][] = "The $field must be at least $min characters.";
            }
        }
        
        if (strpos($rule, 'max:') === 0) {
            $max = substr($rule, 4);
            if (!empty($value) && strlen($value) > $max) {
                $this->errors[$field][] = "The $field must not exceed $max characters.";
            }
        }
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function hasErrors() {
        return !empty($this->errors);
    }
}
?>