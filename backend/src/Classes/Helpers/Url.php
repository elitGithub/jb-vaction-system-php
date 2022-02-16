<?php

namespace Eli\Vacation\Helpers;

class Url
{
    private array $routeMap = [
        'index' => '/',
        '/'     => '/',
        ''      => '/',
    ];

    /**
     * Removes trailing forward slashes from the right of the route.
     * @param string $route (string)
     */
    private function trimSlashes(string $route): string
    {
        $result = rtrim($route, '/');
        return ltrim($result, '/');
    }

    public function segment(string $url)
    {
        if ($url === '/') {
            return $url;
        }
        $url = $this->trimSlashes($url);

        // Now we have the requested path
        $segments = explode('/', $url);

        $hasQueryParams = $this->readQueryParams($url, $segments);
        if (!$hasQueryParams) {
            $segments = array_values(array_filter($segments));
            $requestedRoute = end($segments);
            if (in_array($requestedRoute, $this->routeMap)) {
                return $this->routeMap[$this->removeFileExtensions($requestedRoute)];
            }
            return $this->removeFileExtensions($requestedRoute);
        }

        return $this->justRoute(join('/', $segments));
    }

    public function justRoute($url): array|string
    {
        // Remove the url of the website, so we're left with just the requested path.
        $http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://";
        return str_replace($http . getenv('APP_ROOT'), '', $url);
    }

    public function requestedUrl(): string
    {
        return strtolower($_SERVER['REQUEST_URI']) ;
    }

    private function removeFileExtensions(string $path): bool|string
    {
        $elements = explode('.', $path);
        if (end($elements)) {
            return reset($elements);
        }
        return $path;
    }

    private function readQueryParams(string $url, array &$segments): bool
    {
        $parsed = parse_url($url);
        if (isset($parsed['query'])) {
            return true;
        }
        if (isset($parsed['path'])) {
            $end = end($segments);
            if (ctype_digit($end)) {
                $_GET['id'] = $end;
                // Routes with IDs should be structured as /controller/id.
                unset($segments[array_key_last($segments)]);
                $segments[array_key_last($segments)] .= '/{id}';
                return true;
            }
        }

        return false;
    }
}