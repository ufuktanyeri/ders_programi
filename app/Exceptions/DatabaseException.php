<?php

namespace App\Exceptions;

/**
 * Database Exception
 * 
 * Veritabanı işlem hataları için
 */
class DatabaseException extends AppException
{
    protected $statusCode = 500;
    protected $errorCode = 'DATABASE_ERROR';
    
    /**
     * Create connection failed exception
     * 
     * @return static
     */
    public static function connectionFailed()
    {
        return new static('Veritabanı bağlantısı kurulamadı.');
    }
    
    /**
     * Create query failed exception
     * 
     * @param string $query
     * @return static
     */
    public static function queryFailed($query = '')
    {
        $message = 'Veritabanı sorgusu başarısız oldu.';
        if (config('app.debug') && $query) {
            $message .= ' Query: ' . $query;
        }
        return new static($message);
    }
    
    /**
     * Create record not found exception
     * 
     * @param string $model
     * @param mixed $id
     * @return static
     */
    public static function recordNotFound($model = '', $id = null)
    {
        $message = 'Kayıt bulunamadı.';
        if ($model) {
            $message = $model . ' bulunamadı.';
            if ($id !== null) {
                $message .= ' (ID: ' . $id . ')';
            }
        }
        $exception = new static($message);
        $exception->statusCode = 404;
        $exception->errorCode = 'RECORD_NOT_FOUND';
        return $exception;
    }
    
    /**
     * Create duplicate entry exception
     * 
     * @param string $field
     * @return static
     */
    public static function duplicateEntry($field = '')
    {
        $message = 'Bu kayıt zaten mevcut.';
        if ($field) {
            $message = $field . ' zaten kullanılıyor.';
        }
        $exception = new static($message);
        $exception->statusCode = 409;
        $exception->errorCode = 'DUPLICATE_ENTRY';
        return $exception;
    }
    
    /**
     * Create constraint violation exception
     * 
     * @param string $constraint
     * @return static
     */
    public static function constraintViolation($constraint = '')
    {
        $message = 'Veritabanı kısıtı ihlal edildi.';
        if ($constraint) {
            $message .= ' (' . $constraint . ')';
        }
        $exception = new static($message);
        $exception->errorCode = 'CONSTRAINT_VIOLATION';
        return $exception;
    }
}
