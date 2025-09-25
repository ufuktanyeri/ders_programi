<?php

class ValidationHelper {

    /**
     * Validate email address
     */
    public static function email($email) {
        if (empty($email)) {
            return 'Email adresi gereklidir.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Geçerli bir email adresi giriniz.';
        }

        if (strlen($email) > 255) {
            return 'Email adresi 255 karakterden uzun olamaz.';
        }

        return null; // Valid
    }

    /**
     * Validate name
     */
    public static function name($name, $minLength = 2, $maxLength = 100) {
        if (empty($name)) {
            return 'İsim gereklidir.';
        }

        $name = trim($name);
        $length = mb_strlen($name, 'UTF-8');

        if ($length < $minLength) {
            return "İsim en az {$minLength} karakter olmalıdır.";
        }

        if ($length > $maxLength) {
            return "İsim en fazla {$maxLength} karakter olabilir.";
        }

        // Check for valid characters (letters, spaces, some special chars)
        if (!preg_match('/^[\p{L}\s\-\'\.]+$/u', $name)) {
            return 'İsim sadece harf, boşluk, tire ve apostrof içerebilir.';
        }

        return null; // Valid
    }

    /**
     * Validate password
     */
    public static function password($password, $minLength = 6) {
        if (empty($password)) {
            return 'Şifre gereklidir.';
        }

        if (strlen($password) < $minLength) {
            return "Şifre en az {$minLength} karakter olmalıdır.";
        }

        if (strlen($password) > 255) {
            return 'Şifre 255 karakterden uzun olamaz.';
        }

        return null; // Valid
    }

    /**
     * Validate strong password
     */
    public static function strongPassword($password) {
        $basicValidation = self::password($password, 8);
        if ($basicValidation) {
            return $basicValidation;
        }

        // Check for uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return 'Şifre en az bir büyük harf içermelidir.';
        }

        // Check for lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return 'Şifre en az bir küçük harf içermelidir.';
        }

        // Check for number
        if (!preg_match('/[0-9]/', $password)) {
            return 'Şifre en az bir sayı içermelidir.';
        }

        // Check for special character
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            return 'Şifre en az bir özel karakter içermelidir.';
        }

        return null; // Valid
    }

    /**
     * Validate phone number
     */
    public static function phone($phone) {
        if (empty($phone)) {
            return null; // Phone is optional
        }

        // Remove spaces, dashes, parentheses
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);

        // Turkish phone number format
        if (!preg_match('/^(\+90|0)?[5][0-9]{9}$/', $phone)) {
            return 'Geçerli bir telefon numarası giriniz. (0555 123 45 67)';
        }

        return null; // Valid
    }

    /**
     * Validate URL
     */
    public static function url($url) {
        if (empty($url)) {
            return null; // URL is optional
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return 'Geçerli bir URL giriniz.';
        }

        return null; // Valid
    }

    /**
     * Validate required field
     */
    public static function required($value, $fieldName = 'Bu alan') {
        if (empty($value) && $value !== '0') {
            return "{$fieldName} gereklidir.";
        }

        return null; // Valid
    }

    /**
     * Validate string length
     */
    public static function length($value, $min = null, $max = null, $fieldName = 'Bu alan') {
        if (empty($value)) {
            return null; // Let required() handle empty values
        }

        $length = mb_strlen($value, 'UTF-8');

        if ($min !== null && $length < $min) {
            return "{$fieldName} en az {$min} karakter olmalıdır.";
        }

        if ($max !== null && $length > $max) {
            return "{$fieldName} en fazla {$max} karakter olabilir.";
        }

        return null; // Valid
    }

    /**
     * Validate numeric value
     */
    public static function numeric($value, $min = null, $max = null, $fieldName = 'Bu değer') {
        if (empty($value) && $value !== '0') {
            return null; // Let required() handle empty values
        }

        if (!is_numeric($value)) {
            return "{$fieldName} sayı olmalıdır.";
        }

        $value = floatval($value);

        if ($min !== null && $value < $min) {
            return "{$fieldName} en az {$min} olmalıdır.";
        }

        if ($max !== null && $value > $max) {
            return "{$fieldName} en fazla {$max} olabilir.";
        }

        return null; // Valid
    }

    /**
     * Validate integer
     */
    public static function integer($value, $min = null, $max = null, $fieldName = 'Bu değer') {
        if (empty($value) && $value !== '0') {
            return null; // Let required() handle empty values
        }

        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            return "{$fieldName} tam sayı olmalıdır.";
        }

        $value = intval($value);

        if ($min !== null && $value < $min) {
            return "{$fieldName} en az {$min} olmalıdır.";
        }

        if ($max !== null && $value > $max) {
            return "{$fieldName} en fazla {$max} olabilir.";
        }

        return null; // Valid
    }

    /**
     * Validate date
     */
    public static function date($date, $format = 'Y-m-d', $fieldName = 'Tarih') {
        if (empty($date)) {
            return null; // Let required() handle empty values
        }

        $datetime = DateTime::createFromFormat($format, $date);

        if (!$datetime || $datetime->format($format) !== $date) {
            return "{$fieldName} geçerli bir tarih olmalıdır. ({$format})";
        }

        return null; // Valid
    }

    /**
     * Validate time
     */
    public static function time($time, $format = 'H:i', $fieldName = 'Saat') {
        if (empty($time)) {
            return null; // Let required() handle empty values
        }

        $datetime = DateTime::createFromFormat($format, $time);

        if (!$datetime || $datetime->format($format) !== $time) {
            return "{$fieldName} geçerli bir saat olmalıdır. ({$format})";
        }

        return null; // Valid
    }

    /**
     * Validate enum value
     */
    public static function enum($value, $allowedValues, $fieldName = 'Bu değer') {
        if (empty($value)) {
            return null; // Let required() handle empty values
        }

        if (!in_array($value, $allowedValues)) {
            $allowed = implode(', ', $allowedValues);
            return "{$fieldName} şu değerlerden biri olmalıdır: {$allowed}";
        }

        return null; // Valid
    }

    /**
     * Validate file upload
     */
    public static function file($file, $allowedTypes = [], $maxSize = null, $fieldName = 'Dosya') {
        if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null; // Let required() handle missing files
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return "{$fieldName} yüklenirken bir hata oluştu.";
        }

        // Check file size
        if ($maxSize && $file['size'] > $maxSize) {
            $maxSizeMB = round($maxSize / 1048576, 2);
            return "{$fieldName} boyutu {$maxSizeMB} MB'dan küçük olmalıdır.";
        }

        // Check file type
        if (!empty($allowedTypes)) {
            $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($fileType, $allowedTypes)) {
                $allowed = implode(', ', $allowedTypes);
                return "{$fieldName} şu formatlardan biri olmalıdır: {$allowed}";
            }
        }

        return null; // Valid
    }

    /**
     * Validate Turkish ID number (TC Kimlik No)
     */
    public static function turkishId($id) {
        if (empty($id)) {
            return null; // Optional field
        }

        // Remove spaces
        $id = preg_replace('/\s/', '', $id);

        // Must be 11 digits
        if (!preg_match('/^[0-9]{11}$/', $id)) {
            return 'TC Kimlik numarası 11 haneli olmalıdır.';
        }

        // First digit cannot be 0
        if ($id[0] === '0') {
            return 'TC Kimlik numarası 0 ile başlayamaz.';
        }

        // Algorithm check
        $digits = str_split($id);
        $sum1 = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8];
        $sum2 = $digits[1] + $digits[3] + $digits[5] + $digits[7];

        $check1 = ($sum1 * 7 - $sum2) % 10;
        if ($check1 != $digits[9]) {
            return 'Geçersiz TC Kimlik numarası.';
        }

        $sum3 = $sum1 + $sum2 + $digits[9];
        $check2 = $sum3 % 10;
        if ($check2 != $digits[10]) {
            return 'Geçersiz TC Kimlik numarası.';
        }

        return null; // Valid
    }

    /**
     * Multiple validation
     */
    public static function validate($data, $rules) {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                $error = null;

                if (is_string($rule)) {
                    // Simple rule like 'required', 'email'
                    switch ($rule) {
                        case 'required':
                            $error = self::required($value, $field);
                            break;
                        case 'email':
                            $error = self::email($value);
                            break;
                        case 'name':
                            $error = self::name($value);
                            break;
                        default:
                            break;
                    }
                } elseif (is_array($rule)) {
                    // Rule with parameters like ['length', 2, 100]
                    $ruleName = array_shift($rule);
                    array_unshift($rule, $value); // Add value as first parameter

                    switch ($ruleName) {
                        case 'length':
                            $error = self::length(...$rule);
                            break;
                        case 'numeric':
                            $error = self::numeric(...$rule);
                            break;
                        case 'integer':
                            $error = self::integer(...$rule);
                            break;
                        case 'enum':
                            $error = self::enum(...$rule);
                            break;
                        default:
                            break;
                    }
                }

                if ($error) {
                    if (!isset($errors[$field])) {
                        $errors[$field] = [];
                    }
                    $errors[$field][] = $error;
                }
            }
        }

        return $errors;
    }
}
?>