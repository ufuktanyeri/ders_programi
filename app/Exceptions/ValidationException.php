<?php

namespace App\Exceptions;

/**
 * Validation Exception
 * 
 * Form ve veri validasyon hataları için
 */
class ValidationException extends AppException
{
    protected $statusCode = 422;
    protected $errorCode = 'VALIDATION_ERROR';
    protected $errors = [];
    
    /**
     * Constructor
     * 
     * @param string $message
     * @param array $errors
     */
    public function __construct($message = "Validation failed", array $errors = [])
    {
        $this->errors = $errors;
        parent::__construct($message);
    }
    
    /**
     * Get validation errors
     * 
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * Convert to array
     * 
     * @return array
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'errors' => $this->errors,
        ]);
    }
}
