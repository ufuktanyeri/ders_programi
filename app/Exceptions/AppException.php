<?php

namespace App\Exceptions;

use Exception;

/**
 * Base Application Exception
 * 
 * Tüm uygulama özel exception'larının base sınıfı
 */
class AppException extends Exception
{
    protected $statusCode = 500;
    protected $errorCode = 'APP_ERROR';
    
    /**
     * Constructor
     * 
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        
        // Log exception
        $this->logException();
    }
    
    /**
     * Get HTTP status code
     * 
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
    
    /**
     * Get error code
     * 
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
    
    /**
     * Log exception
     * 
     * @return void
     */
    protected function logException()
    {
        if (function_exists('logger')) {
            logger(
                sprintf(
                    '%s: %s in %s:%d',
                    get_class($this),
                    $this->getMessage(),
                    $this->getFile(),
                    $this->getLine()
                ),
                'error'
            );
        }
    }
    
    /**
     * Convert to array
     * 
     * @return array
     */
    public function toArray()
    {
        return [
            'error' => true,
            'code' => $this->errorCode,
            'message' => $this->getMessage(),
            'status_code' => $this->statusCode,
        ];
    }
    
    /**
     * Convert to JSON
     * 
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
