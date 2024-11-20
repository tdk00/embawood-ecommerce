<?php
namespace ResponseHandler;

use Illuminate\Routing\ResponseFactory;
use CoreDispatcher\CoreDispatcher;

class ArtResponseFactory extends ResponseFactory
{
    public function json($data = [], $status = 200, array $headers = [], $options = 0, $authOptions = [])
    {
        CoreDispatcher::process();

        return parent::json($data, $status, $headers, $options);
    }
}
