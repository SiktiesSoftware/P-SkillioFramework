<?php
namespace skillio\core\routing\validation;

use DateTime;
use skillio\core\Application;
use skillio\core\internal\exceptions\InternalException;
use skillio\core\Session;
use skillio\core\utils\helpers\ParamCollection;

/**
 * Class Validator
 * Validates data against specified rules.
 *
 * @package skillio\core\routing\validation
 */
class Validator
{   
    /** @var array $errors Holds validation errors */
    private array $errors = [];

    /** @var ParamCollection|array $data Holds the data to be validated */
    private ParamCollection|array $data;

    /** @var array $data Holds the validated data */
    private ParamCollection $validatedData;

    /** @var array $fields Holds the fields to be validated */
    private array $fields;

    /** @var array $patterns Holds custom validation patterns */
    private array $patterns;

    /** @var bool $withErrors Flag to include errors in the validation result */    
    private bool $withErrors;

    /** @var self|null $instance Singleton instance of the Validator class */
    private static ?self $instance = null;

    /** @var bool $save Flag to indicate if the data should be saved in the session */
    private bool $save = false;

    /**
     * Default patterns for validation rules.
     * These can be extended or overridden by application-specific configurations.
     */
    private const DEFAULT_PATTERNS = [
        "email" => "/^[\w\.]+@([\w-]+\.)+[\w-]{2,4}$/",
    ];

    /**
     * Get the validation errors.
     *
     * @return array The validation errors.
     */
    public function errors(): array
    {
        return $this->errors;
    }   

    /**
     * Check if there are any validation errors.
     *
     * @return bool True if there are errors, false otherwise.
     */
    public function anyError(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Save the data and errors in the session to be used later.
     * This method is useful for redirecting after validation.
     */
    public function save(): void
    {       
        $this->save = true;
    }

    /**
     * Check if the data has been saved in the session.
     *
     * @return bool True if the data has been saved, false otherwise.
     */
    public function isSaved(): bool
    {
        return $this->save;
    }

    /**
     * Save the validated data to the session.
     * This method is useful for redirecting after validation.
     */
    public function saveToSession(): void
    {
        // Put the data which are not in validatedData
        $data = $this->validatedData->all(); 
        
        // Merge the original data with validated data
        foreach ($this->data->all() as $key => $value)
            if (!isset($data[$key])) 
                $data[$key] = $value;
            
        // remove the "passed" key from the validated data
        foreach ($data as $key => $value)
            if (isset($value['passed']))
                unset($data[$key]['passed']);

        // Save the data in the session
        Session::set(key: "dataValidation", value: $data);

        // Destroy the instance to allow for a new validation
        self::$instance = null;
    }

    /**
     * Get all validated data.
     *
     * @return ParamCollection The validated data.
     */
    public function validatedData(): ParamCollection
    {
        return $this->validatedData;
    }

    /**
     * Constructor to initialize the validator with data.
     *
     * @param ParamCollection|array $data The data to validate.
     * @param array $fields The fields to validate against.
     */
    private function __construct(ParamCollection|array $data, array $fields, bool $withErrors = false)
    {
        $this->data = $data;
        $this->fields = $fields;
        $this->patterns = Application::getInstance()->getConfigFile(name: "validationFields")['patterns'];
        $this->withErrors = $withErrors;
    }

    /**
     * Get the singleton instance of the Validator class.
     *
     * @param ParamCollection|array $data The data to validate.
     * @param array $fields The fields to validate against.
     * @param bool $withErrors Whether to include errors in the validation result.
     * @return self The instance of the Validator class.
     */
    public static function getInstance(mixed $data = [], ?array $fields = [], ?bool $withErrors = false): self
    {
        // If the instance is not set, create a new one
        if (!isset(self::$instance)) 
            self::$instance = new self(data: $data, fields: $fields, withErrors: $withErrors);
        
        return self::$instance;
    }

    /**
     * Handle the validation process.
     *
     * @return self Returns the instance of the Validator class after validation.
     */
    public function handle(): self
    {
        /** @var array $validationRules Set of validation rules */
        $validationRules = 
        [
            'string' => fn(mixed $value, string $param) => $this->validateType(value: $value, type: $param === 'strict' ? 'string:strict' : 'string'),
            'int' => fn(mixed $value) => $this->validateType(value: $value, type: 'int'),
            'float' => fn(mixed $value) => $this->validateType(value: $value, type: 'float'),
            'bool' => fn(mixed $value) => $this->validateType(value: $value, type: 'bool'),
            'array' => fn(mixed $value) => $this->validateType(value: $value, type: 'array'),
            'min' => fn(mixed $value, int $param) => $this->validateMin(value: $value, min: $param),
            'max' => fn(mixed $value, int $param) => $this->validateMax(value: $value, max: $param),
            'email' => fn(mixed $value) => $this->validateEmail(email: $value),
            'date' => fn(mixed $value) => $this->validateDate(date: $value),
            'after' => fn(mixed $value, string $param) => $this->validateDate(date: $value, type: "after:$param"),
            'before' => fn(mixed $value, string $param) => $this->validateDate(date: $value, type: "before:$param"),
            'today' => fn(mixed $value) => $this->validateDate(date: $value, type: 'today'),
        ];

        // Initialize arrays to hold errors and validated data
        $errors = [];
        $validatedData = [];
        foreach ($this->fields as $field => $rules) 
        {
            if (is_string($rules))
                $rules = str_contains($rules, '|') ? explode('|', $rules) : [$rules];

            // Check if the field is required and remove it from the rules
            $isRequired = in_array('required', $rules);
            $rules = array_filter($rules, fn($rule) => $rule !== 'required');
            
            // If not required and not set, skip validation
            if (!$isRequired && (!isset($this->data[$field]) || empty($this->data[$field]))) 
                $validatedData[$field] = ["passed" => true];
            elseif ($isRequired && (!isset($this->data[$field]) || empty($this->data[$field]))) 
                $errors[$field][] = [
                    "rule" => "required",
                    "error" => "RequiredError"
                ];
            else
                foreach ($rules as $rule) 
                {
                    // Split rule into name and parameter if it contains a colon
                    if(str_contains($rule, ':')) 
                        [$rule, $param] = explode(':', $rule, 2);

                    if (!isset($validationRules[$rule])) 
                        new InternalException(message: "Unknown validation rule: $rule for field: $field");
                    
                    // Call the validation rule function
                    $validationRule = $validationRules[$rule];
                    if (isset($param) && !empty($param))
                        $validation = $validationRule($this->data[$field] ?? null, $param);
                    else
                        $validation = $validationRule($this->data[$field] ?? null);

                    // If validation fails, store the error
                    if (!$validation['passed']) 
                    {
                        $errors[$field][] = [
                            "rule" => $rule,
                            "error" => $validation['error']
                        ];
                    }
                }

            // If there are no errors for this field, mark it as passed
            if (empty($errors[$field])) 
                $validatedData[$field] = ["passed" => true]; // Store valid data
            else 
            {
                $error = $errors[$field][0];
                $validatedData[$field] = ["passed" => false, "step" => $error["rule"]]; // Store errors
                $this->errors[$field] = $error["error"];
            }

            $validatedData[$field]["value"] = $this->data[$field] ?? null; // Store the value
        }
        
        // Merge the errors into the validated data
        if ($this->withErrors && $this->anyError()) 
            foreach ($this->errors as $field => $error) 
                $validatedData[$field]["error"] = $error;

        // Set the validated data
        $this->validatedData = new ParamCollection(params: $validatedData);
        return $this;
    }

    #region Validation Methods
    /**
     * Validate a pattern against a value.
     *
     * @param string[]|string $pattern The pattern to validate against.
     * @param string $value The value to validate.
     * @return array An array indicating whether the validation passed and any error message.
     */
    private function validatePattern(array|string $pattern, string $value): array
    {
        // Check the pattern or patterns against the value
        $passed = false;
        if (is_string($pattern)) 
            $passed = preg_match($pattern, $value);
        else 
            foreach ($pattern as $p) 
                if (preg_match($p, $value)) 
                    $passed = true;

        return $passed ? ["passed" => true] : ["passed" => false, "error" => "PatternError"];
    }

    /**
     * Validate the type of a value.
     *
     * @param mixed $value The value to validate.
     * @param string $type The expected type.
     * @return array An array indicating whether the validation passed and any error message.
     */
    private function validateType(mixed $value, string $type): array
    {
        $isValid = false;
        switch ($type) {
            case 'string:strict':
                $isValid = is_string($value) && preg_match('/^[a-zA-Z]+$/', $value);
                break;
            case 'string':
                $isValid = is_string($value);
                break;
            case 'int':
                $isValid = is_int($value);
                break;
            case 'float':
                $isValid = is_float($value);
                break;
            case 'bool':
                $isValid = is_bool($value);
                break;
            case 'array':
                $isValid = is_array($value);
                break;
            default:
                return ["passed" => false, "error" => "UnknownTypeError"];
        }

        return $isValid ? ["passed" => true] : ["passed" => false, "error" => "TypeError"];
    }

    /**
     * Validate the minimum value or length of a value.
     *
     * @param mixed $value The value to validate.
     * @param int $min The minimum value or length.
     * @return array An array indicating whether the validation passed and any error message.
     */
    private function validateMin(mixed $value, int $min): array
    {
        $validation = ["passed" => true];
        if(is_numeric($value) && $value < $min || is_string($value) && strlen($value) < $min || is_array($value) && count($value) < $min) 
            $validation = ["passed" => false, "error" => "MinError"];

        return $validation;
    }

    /**
     * Validate the maximum value or length of a value.
     *
     * @param mixed $value The value to validate.
     * @param int $max The maximum value or length.
     * @return array An array indicating whether the validation passed and any error message.
     */
    private function validateMax(mixed $value, int $max): array
    {
        $validation = ["passed" => true];
        if(is_numeric($value) && $value > $max || is_string($value) && strlen($value) > $max || is_array($value) && count($value) > $max)
            $validation = ["passed" => false, "error" => "MaxError"];

        return $validation;
    }

    /**
     * Validate an email address.
     *
     * @param mixed $email The email address to validate.
     * @return array An array indicating whether the validation passed and any error message.
     */
    private function validateEmail(mixed $email): array
    {
        return $this->validatePattern(
            pattern: $this->patterns['email'] ?? self::DEFAULT_PATTERNS['email'],
            value: $email
        );
    }

    /**
     * Validate a date string.
     *
     * @param mixed $date The date string to validate.
     * @param string|null $type The type of date validation (optional).
     * @return array An array indicating whether the validation passed and any error message.
     */
    private function validateDate(mixed $date, ?string $type = null): array
    {        
        $getDateType = function(string $type) : ?string
        {
            $date = "";
            switch ($type) 
            {
                case 'tomorrow':
                    $date = new DateTime('tomorrow')->format('Y-m-d');
                case 'today':
                    $date = new DateTime()->format('Y-m-d');
                case 'yesterday':
                    $date = new DateTime('yesterday')->format('Y-m-d');
                default:
                    $date = new DateTime($type)->format('Y-m-d');
            }
            return $date;
        };

        // Format the date to 'Y-m-d' for consistency
        $date = new DateTime($date)->format('Y-m-d');

        // If no type is provided, validate against the default date pattern
        $validation = [];
        if ($type !== null) 
        {
            // Handle specific date validation types
            if (str_starts_with($type, 'after:')) 
            {   
                // Extract the date type after 'after:'
                $dateType = $getDateType(substr($type, 6));

                // Compare the date with the date type
                if($date > $dateType)
                    $validation = ["passed" => true];
                else
                    $validation = ["passed" => false, "error" => "AfterError"];
                    
            } 
            elseif (str_starts_with($type, 'before:')) 
            {
                // Extract the date type after 'before:'
                $dateType = $getDateType(substr($type, 7));

                // Compare the date with the date type
                if($date < $dateType)
                    $validation = ["passed" => true];
                else
                    $validation = ["passed" => false, "error" => "BeforeError"];
            }
            elseif (str_starts_with($type, 'today')) 
            {
                // Compare the date with today's date
                $dateType = $getDateType('today');

                // If the date is today, validation passes
                if ($date === $dateType) 
                    $validation = ["passed" => true];
                else 
                    $validation = ["passed" => false, "error" => "TodayError"];
            }
        }
        else
            $validation = $this->validatePattern(
                pattern: $this->patterns['date'] ?? self::DEFAULT_PATTERNS['date'],
                value: $date
            );

        return $validation;
    }
    #endregion
}
?>