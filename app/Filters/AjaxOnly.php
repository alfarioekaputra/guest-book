<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AjaxOnly implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Dapatkan segment terakhir URL untuk memeriksa operasi (new/edit)
        $uri = $request->getUri();
        $lastSegment = $uri->getSegment($uri->getTotalSegments());

        // Hanya terapkan filter untuk operasi 'new' dan 'edit'
        if (in_array($lastSegment, ['new', 'edit'])) {
            // Periksa header X-Requested-With secara manual
            $isAjax = $request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';

            if (!$isAjax) {
                return service('response')
                    ->setStatusCode(403)
                    ->setContentType('application/json')
                    ->setBody(json_encode([
                        'status' => 'error',
                        'message' => 'Operasi ini hanya bisa diakses melalui AJAX request'
                    ]));
            }
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
