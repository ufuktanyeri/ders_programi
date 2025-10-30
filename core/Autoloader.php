<?php

/**
 * PSR-4 Compliant Autoloader
 * 
 * This autoloader follows PSR-4 standard for automatic class loading.
 * It maps namespaces to directory paths.
 */

class Autoloader
{
  /**
   * Registered namespaces
   * @var array
   */
  private static $namespaces = [];

  /**
   * Register the autoloader
   * 
   * @return void
   */
  public static function register()
  {
    spl_autoload_register([self::class, 'load']);
  }

  /**
   * Add a namespace to the autoloader
   * 
   * @param string $namespace The namespace
   * @param string $path The path to the namespace
   * @return void
   */
  public static function addNamespace(string $namespace, string $path)
  {
    $namespace = trim($namespace, '\\') . '\\';
    $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    if (!isset(self::$namespaces[$namespace])) {
      self::$namespaces[$namespace] = [];
    }

    self::$namespaces[$namespace][] = $path;
  }

  /**
   * Load a class
   * 
   * @param string $className The fully qualified class name
   * @return bool True if the class was loaded, false otherwise
   */
  private static function load(string $className): bool
  {
    // Normalize namespace separator
    $className = ltrim($className, '\\');

    // Try to find the namespace
    foreach (self::$namespaces as $namespace => $paths) {
      if (strpos($className, $namespace) === 0) {
        // Remove namespace from class name
        $relativeClass = substr($className, strlen($namespace));

        // Try each registered path for this namespace
        foreach ($paths as $basePath) {
          $file = $basePath . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

          if (self::loadFile($file)) {
            return true;
          }
        }
      }
    }

    // Fallback for core classes without namespace
    if (strpos($className, '\\') === false) {
      $corePath = __DIR__ . DIRECTORY_SEPARATOR . $className . '.php';
      if (self::loadFile($corePath)) {
        return true;
      }
    }

    return false;
  }

  /**
   * Load a file if it exists
   * 
   * @param string $file The file path
   * @return bool True if the file was loaded, false otherwise
   */
  private static function loadFile(string $file): bool
  {
    if (file_exists($file)) {
      require_once $file;
      return true;
    }
    return false;
  }

  /**
   * Get all registered namespaces
   * 
   * @return array
   */
  public static function getNamespaces(): array
  {
    return self::$namespaces;
  }
}
