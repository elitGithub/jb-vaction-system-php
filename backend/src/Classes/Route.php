<?php

declare(strict_types = 1);

namespace Eli\Vacation;


use InvalidArgumentException;

/**
 * Class Route
 *
 * @package DevCoder
 */
class Route
{

    /**
     * @var array<string>
     */
    private array $vars = [];
    private Request $request;

    /**
     * Route constructor.
     *
     * @param  string  $name
     * @param  string  $path
     * @param  array   $parameters
     *    $parameters = [
     *      0 => (string) Controller name : HomeController::class.
     *      1 => (string|null) Method name or null if invoke method
     *    ]
     * @param  array   $methods
     */
    public function __construct (private string $name, private string $path, private array $parameters, private array $methods = ['GET'])
    {
        if (empty($methods)) {
            throw new InvalidArgumentException('HTTP methods argument was empty; must contain at least one method');
        }
        $this->request = new Request();
    }

    public function match (string $path, string $method): bool
    {
        $regex = $this->getPath();
        foreach ($this->getVarsNames() as $variable) {
            $varName = trim($variable, '{\}');
            $regex = str_replace($variable, '(?P<' . $varName . '>[^/]++)', $regex);
        }

        if (in_array($method, $this->getMethods()) && preg_match('#^' . $regex . '$#sD', static::trimPath($path), $matches)) {
            $values = array_filter($matches, static fn($key) => is_string($key), ARRAY_FILTER_USE_KEY);
            if ($this->request->isPost()) {
                $this->getPostVars();
            }
            foreach ($values as $key => $value) {
                $this->vars[$key] = $value;
            }
            return true;
        }
        return false;
    }

    public function getName (): string
    {
        return $this->name;
    }

    public function getPath (): string
    {
        return $this->path;
    }

    public function getParameters (): array
    {
        return $this->parameters;
    }

    public function getMethods (): array
    {
        return $this->methods;
    }

    public function getVarsNames (): array
    {
        preg_match_all('/{[^}]*}/', $this->path, $matches);
        return reset($matches) ?? [];
    }

    public function getPostVars() {
        if (empty($_POST)) {
            // If a form submits content-type of application/json, $_POST and $_REQUEST are not automatically filled.
            $_POST = array_merge($_POST, json_decode(file_get_contents('php://input'), true));
            $_REQUEST = array_merge($_POST, $_REQUEST);
        }

        foreach ($_POST as $key => $value) {
            $this->vars[$key] = filter_var($value,FILTER_SANITIZE_STRING);
        }

    }

    public function hasVars (): bool
    {
        return $this->getVarsNames() !== [];
    }

    public function getVars (): array
    {
        return $this->vars;
    }

    public static function trimPath (string $path): string
    {
        return '/' . rtrim(ltrim(trim($path), '/'), '/');
    }
}