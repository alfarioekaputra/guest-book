<?php

namespace Config;

class Twig
{
  public static function getConfig()
  {
    return [
      'cache' => WRITEPATH . 'cache/twig',
      'debug' => ENVIRONMENT !== 'production',
      'auto_reload' => true,
    ];
  }
}
