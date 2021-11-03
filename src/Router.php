<?php

namespace App;

class Router
{
  private $routes = [];

  /**
   * Add a route into the router internal array
   *
   * @param string $name
   * @param string $url
   * @param string $httpMethod
   * @param string $controller Controller class
   * @param string $method
   * @return self
   */
  public function addRoute(
    string $name,
    string $url,
    string $httpMethod,
    string $controller,
    string $method
  ): self {
    $this->routes[] = [
      'name' => $name,
      'url' => $url,
      'http_method' => $httpMethod,
      'controller' => $controller,
      'method' => $method
    ];

    return $this;
  }

  /**
   * Checks if a route exists
   *
   * @param string $uri
   * @param string $httpMethod
   * @return boolean
   */
  public function hasRoute(string $uri, string $httpMethod): bool
  {
    foreach ($this->routes as $route) {
      if ($route['url'] === $uri && $route['http_method'] === $httpMethod) {
        return true;
      }
    }

    return false;
  }

  public function execute(string $uri, string $httpMethod)
  {
    if (!$this->hasRoute($uri, $httpMethod)) {
      // return 404;
    }

    // $controller = new $route['controller'];
    // $method = $route['method'];
    // $controller->$method();
  }
}
