<?php

namespace App\Libraries;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Config\Twig;

class TwigLib
{
  private $twig;
  private $loader;

  public function __construct()
  {
    // Buat custom loader
    $this->loader = new class(APPPATH . 'Views') extends FilesystemLoader {
      public function findTemplate(string $name, bool $throw = true): string
      {
        // Coba dengan nama asli dulu
        try {
          return parent::findTemplate($name, true);
        } catch (\Twig\Error\LoaderError $e) {
          // Jika tidak ada extension, tambahkan .html.twig
          if (!preg_match('/\.[a-z]+$/i', $name)) {
            try {
              return parent::findTemplate($name . '.html.twig', true);
            } catch (\Twig\Error\LoaderError $e2) {
              // Jika masih tidak ketemu, coba dengan .twig
              try {
                return parent::findTemplate($name . '.twig', true);
              } catch (\Twig\Error\LoaderError $e3) {
                if ($throw) {
                  throw $e3;
                }
                return '';
              }
            }
          }
          if ($throw) {
            throw $e;
          }
          return '';
        }
      }
    };
    $this->twig = new Environment($this->loader, Twig::getConfig());

    // Register semua common helpers CI4
    $this->registerCIHelpers();
  }

  private function registerCIHelpers()
  {
    // Load common helpers
    helper(['url', 'form', 'html', 'text', 'number', 'array', 'date']);

    // Dapatkan semua fungsi yang tersedia
    $definedFunctions = get_defined_functions();

    // Loop melalui semua fungsi yang ada
    foreach ($definedFunctions['user'] as $function) {
      // Filter hanya fungsi CI
      if (
        strpos($function, 'url_') === 0 ||
        strpos($function, 'form_') === 0 ||
        strpos($function, 'html_') === 0 ||
        strpos($function, 'site_') === 0 ||
        strpos($function, 'base_') === 0 ||
        strpos($function, 'current_') === 0 ||
        in_array($function, [
          'site_url',
          'base_url',
          'current_url',
          'uri_string',
          'index_page',
        ])
      ) {
        $this->twig->addFunction(
          new \Twig\TwigFunction($function, $function)
        );
      }
    }

    // Tambahkan fungsi khusus untuk csrf
    $this->twig->addFunction(
      new \Twig\TwigFunction('csrf_field', function () {
        return csrf_field();
      })
    );
    $this->twig->addFunction(
      new \Twig\TwigFunction('csrf_hash', function () {
        return csrf_hash();
      })
    );
    $this->twig->addFunction(
      new \Twig\TwigFunction('csrf_token', function () {
        return csrf_token();
      })
    );

    $this->twig->addFunction(
      new \Twig\TwigFunction('isset', function ($key) {
        return isset($key);
      })
    );

    $this->twig->addFunction(
      new \Twig\TwigFunction('str_contains', function ($string, $key) {
        return str_contains($string, $key);
      })
    );

    $this->twig->addFunction(
      new \Twig\TwigFunction('old', function ($key) {
        return old($key);
      })
    );

    // Tambahkan fungsi untuk session
    $this->twig->addFunction(
      new \Twig\TwigFunction('session', function ($key) {
        $session = session();
        return $session->get($key);
      })
    );

    // Tambahkan fungsi untuk validation errors
    $this->twig->addFunction(
      new \Twig\TwigFunction('validation_list_errors', function () {
        $validation = \Config\Services::validation();
        return $validation->listErrors();
      })
    );

    $this->twig->addFunction(
      new \Twig\TwigFunction('validation_has_error', function ($field) {
        $validation = \Config\Services::validation();
        return $validation->hasError($field);
      })
    );

    $this->twig->addFunction(
      new \Twig\TwigFunction('flash', function ($type) {
        return session()->getFlashdata($type);
      })
    );

    $this->twig->addFunction(
      new \Twig\TwigFunction('is_array', function ($flash) {
        return is_array($flash);
      })
    );

    // Fungsi untuk cek apakah user sudah login
    $this->twig->addFunction(
      new \Twig\TwigFunction('is_logged_in', function () {
        return auth()->loggedIn() === true;
      })
    );

    // Fungsi untuk mendapatkan data user yang login
    $this->twig->addFunction(
      new \Twig\TwigFunction('username', function () {
        return auth()->user()->username;
      })
    );

    $this->twig->addFunction(
      new \Twig\TwigFunction('validation_show_error', function ($field) {
        $validation = \Config\Services::validation();
        return $validation->showError($field);
      })
    );
  }

  public function render($template, $data = [])
  {
    return $this->twig->render($template, $data);
  }
}
