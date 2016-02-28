<?php
namespace App\Http;

use League\Fractal\Serializer\ArraySerializer as FractalArraySerializer;

class ArraySerializer extends FractalArraySerializer{

    public function collection($resourceKey, array $data)
    {
        return $resourceKey ? [$resourceKey => $data] : $data ;
    }
}