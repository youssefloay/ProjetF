<?php

namespace App\Routing;

use Psr\Container\ContainerInterface;
use ReflectionMethod;
use Twig\Environment;

class Router
{
  private $routes = [];
  private ContainerInterface $container;

  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }

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
   * Get a route. Returns null if not found
   *
   * @param string $uri
   * @param string $httpMethod
   * @return array|null
   */
  public function getRoute(string $uri, string $httpMethod): ?array
  {
    foreach ($this->routes as $route) {
      if ($route['url'] === $uri && $route['http_method'] === $httpMethod) {
        return $route;
      }
    }

    return null;
  }

  /**
   * Executes a route based on provided URI and HTTP method.
   *
   * @param string $uri
   * @param string $httpMethod
   * @return void
   * @throws RouteNotFoundException
   */
  public function execute(string $uri, string $httpMethod)
  {
    $route = $this->getRoute($uri, $httpMethod);

    if ($route === null) {
      throw new RouteNotFoundException();
    }

    $controllerName = $route['controller'];
    $controller = new $controllerName($this->container->get(Environment::class));
    $method = $route['method'];

    $methodInfos = new ReflectionMethod($controllerName . '::' . $method);
    $methodParameters = $methodInfos->getParameters();

    $params = [];

    foreach ($methodParameters as $param) {
      $paramName = $param->getName(); // em
      $paramType = $param->getType()->getName(); // Doctrine\ORM\EntityManager

      if ($this->container->has($paramType)) { // Doctrine\ORM\EntityManager ?
        $params[$paramName] = $this->container->get($paramType);
      }
    }

    call_user_func_array(
      [$controller, $method],
      $params
    );
  }
}
