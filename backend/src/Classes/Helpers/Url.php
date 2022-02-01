<?php

namespace Eli\Vacation\Helpers;

class Url
{
    private array $routeMap = [
        'index' => '/',
        '/'     => '/',
        ''      => '/',
    ];

    public function segment(string $url)
    {
        if ($url === '/') {
            return $url;
        }

        $url = ltrim($url, '/');

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

        return join('/', $segments);
    }

    public function justRoute($url): array|string
    {
        // Remove the url of the website, so we're left with just the requested path.
        $http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://";
        return str_replace($http . getenv('REQUEST_ROOT'), '', $url);
    }

    public function requestedUrl(): string
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
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
                return true;
            }
        }

        return false;
    }
}