<?php

/**
 * View Template Engine
 * 
 * A simple but powerful template engine for rendering views
 * with support for layouts, components, and template inheritance
 */
class View
{
  /**
   * Views directory
   * @var string
   */
  protected $viewsPath;

  /**
   * Layouts directory
   * @var string
   */
  protected $layoutsPath;

  /**
   * Components directory
   * @var string
   */
  protected $componentsPath;

  /**
   * Current layout
   * @var string|null
   */
  protected $layout = null;

  /**
   * Content sections
   * @var array
   */
  protected $sections = [];

  /**
   * Current section
   * @var string|null
   */
  protected $currentSection = null;

  /**
   * Shared data across all views
   * @var array
   */
  protected static $sharedData = [];

  /**
   * Constructor
   * 
   * @param string $viewsPath Path to views directory
   */
  public function __construct(string $viewsPath = null)
  {
    $this->viewsPath = $viewsPath ?? __DIR__ . '/../app/Views';
    $this->layoutsPath = $this->viewsPath . '/layouts';
    $this->componentsPath = $this->viewsPath . '/components';
  }

  /**
   * Render a view
   * 
   * @param string $view View name (e.g., 'home/index')
   * @param array $data Data to pass to view
   * @return string Rendered content
   */
  public function render(string $view, array $data = []): string
  {
    // Merge shared data with view data
    $data = array_merge(static::$sharedData, $data);

    // Extract data to variables
    extract($data);

    // Start output buffering
    ob_start();

    // Include the view file
    $viewPath = $this->getViewPath($view);

    if (!file_exists($viewPath)) {
      throw new Exception("View not found: {$view} (Path: {$viewPath})");
    }

    include $viewPath;

    // Get the content
    $content = ob_get_clean();

    // If layout is set, render with layout
    if ($this->layout) {
      $layoutPath = $this->layoutsPath . '/' . $this->layout . '.php';

      if (!file_exists($layoutPath)) {
        throw new Exception("Layout not found: {$this->layout}");
      }

      // Store content in a section
      $this->sections['content'] = $content;

      // Render layout
      ob_start();
      include $layoutPath;
      $content = ob_get_clean();

      // Reset layout for next render
      $this->layout = null;
      $this->sections = [];
    }

    return $content;
  }

  /**
   * Output a rendered view
   * 
   * @param string $view View name
   * @param array $data Data to pass to view
   * @return void
   */
  public function make(string $view, array $data = []): void
  {
    echo $this->render($view, $data);
  }

  /**
   * Set the layout
   * 
   * @param string $layout Layout name
   * @return void
   */
  public function extends(string $layout): void
  {
    $this->layout = $layout;
  }

  /**
   * Start a section
   * 
   * @param string $name Section name
   * @return void
   */
  public function section(string $name): void
  {
    $this->currentSection = $name;
    ob_start();
  }

  /**
   * End a section
   * 
   * @return void
   */
  public function endSection(): void
  {
    if ($this->currentSection) {
      $this->sections[$this->currentSection] = ob_get_clean();
      $this->currentSection = null;
    }
  }

  /**
   * Yield a section
   * 
   * @param string $name Section name
   * @param string $default Default content if section doesn't exist
   * @return void
   */
  public function yield(string $name, string $default = ''): void
  {
    echo $this->sections[$name] ?? $default;
  }

  /**
   * Include a component
   * 
   * @param string $component Component name
   * @param array $data Data to pass to component
   * @return void
   */
  public function component(string $component, array $data = []): void
  {
    $componentPath = $this->componentsPath . '/' . $component . '.php';

    if (!file_exists($componentPath)) {
      throw new Exception("Component not found: {$component}");
    }

    extract($data);
    include $componentPath;
  }

  /**
   * Include a partial view
   * 
   * @param string $partial Partial view name
   * @param array $data Data to pass to partial
   * @return void
   */
  public function partial(string $partial, array $data = []): void
  {
    $partialPath = $this->getViewPath($partial);

    if (!file_exists($partialPath)) {
      throw new Exception("Partial not found: {$partial}");
    }

    extract($data);
    include $partialPath;
  }

  /**
   * Escape HTML entities
   * 
   * @param mixed $value Value to escape
   * @return string
   */
  public function e($value): string
  {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
  }

  /**
   * Share data across all views
   * 
   * @param string|array $key Key or array of key-value pairs
   * @param mixed $value Value (if key is string)
   * @return void
   */
  public static function share($key, $value = null): void
  {
    if (is_array($key)) {
      static::$sharedData = array_merge(static::$sharedData, $key);
    } else {
      static::$sharedData[$key] = $value;
    }
  }

  /**
   * Check if view exists
   * 
   * @param string $view View name
   * @return bool
   */
  public function exists(string $view): bool
  {
    return file_exists($this->getViewPath($view));
  }

  /**
   * Get full path to view file
   * 
   * @param string $view View name
   * @return string
   */
  protected function getViewPath(string $view): string
  {
    // Convert dot notation to directory separator
    $view = str_replace('.', DIRECTORY_SEPARATOR, $view);

    return $this->viewsPath . '/' . $view . '.php';
  }

  /**
   * Asset helper - get asset URL
   * 
   * @param string $path Asset path
   * @return string
   */
  public function asset(string $path): string
  {
    $baseUrl = $this->getBaseUrl();
    return $baseUrl . '/public/assets/' . ltrim($path, '/');
  }

  /**
   * URL helper - generate URL
   * 
   * @param string $path URL path
   * @return string
   */
  public function url(string $path): string
  {
    $baseUrl = $this->getBaseUrl();
    return $baseUrl . '/' . ltrim($path, '/');
  }

  /**
   * Get base URL
   * 
   * @return string
   */
  protected function getBaseUrl(): string
  {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

    return $protocol . '://' . $host . $basePath;
  }

  /**
   * CSRF token input field
   * 
   * @return string
   */
  public function csrf(): string
  {
    $token = $_SESSION['csrf_token'] ?? '';
    return '<input type="hidden" name="csrf_token" value="' . $this->e($token) . '">';
  }

  /**
   * Old input value (for form repopulation)
   * 
   * @param string $key Input name
   * @param mixed $default Default value
   * @return mixed
   */
  public function old(string $key, $default = '')
  {
    return $_SESSION['old'][$key] ?? $default;
  }

  /**
   * Flash message
   * 
   * @param string $key Message key
   * @return string|null
   */
  public function flash(string $key): ?string
  {
    $message = $_SESSION['flash'][$key] ?? null;

    if ($message !== null) {
      unset($_SESSION['flash'][$key]);
    }

    return $message;
  }
}
