<?php

namespace App\Traits;

trait SlugTrait
{
  protected function generateSlug($title, $field = 'slug')
  {
    $slug = url_title($title, '-', true);
    $originalSlug = $slug;
    $count = 1;

    while (true) {
      $exists = $this->where($field, $slug)
        ->where('deleted_at', null)
        ->first();

      if (!$exists) {
        break;
      }

      $slug = $originalSlug . '-' . $count;
      $count++;
    }

    return $slug;
  }
}
