<?php

namespace Core;

use Twig\TwigFunction;

/**
 * View
 */
class View
{

    /**
     * Render a view file
     *
     * @param string $view  The view file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig\Environment($loader);
            $twig->addGlobal('base_url', self::baseUrl());
            $twig->addFunction(new TwigFunction('url', [self::class, 'url']));
            $twig->addFunction(new TwigFunction('asset', [self::class, 'asset']));
        }

        echo $twig->render($template, $args);
    }

    /**
     * Build an application URL that works both in XAMPP subfolders and hosting root.
     */
    public static function url(string $path = ''): string
    {
        return self::joinPaths(self::baseUrl(), $path);
    }

    /**
     * Build a public asset URL from the public/ directory.
     */
    public static function asset(string $path = ''): string
    {
        return self::joinPaths(self::baseUrl(), 'public/' . ltrim($path, '/'));
    }

    private static function baseUrl(): string
    {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $scriptDir = rtrim(dirname($scriptName), '/');

        if ($scriptDir === '' || $scriptDir === '.') {
            return '';
        }

        if (substr($scriptDir, -7) === '/public') {
            $scriptDir = substr($scriptDir, 0, -7);
        }

        return $scriptDir === '/' ? '' : $scriptDir;
    }

    private static function joinPaths(string ...$parts): string
    {
        $clean = [];

        foreach ($parts as $part) {
            $part = trim($part, '/');

            if ($part !== '') {
                $clean[] = $part;
            }
        }

        return '/' . implode('/', $clean);
    }
}
