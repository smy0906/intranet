<?php
namespace Intra\Core;

class TwigResponse
{
    private $response_array;

    public function __construct()
    {
        $this->response_array = [];
    }

    public function add(array $array)
    {
        $this->response_array = array_merge($this->response_array, $array);
    }

    public function get()
    {
        return $this->response_array;
    }
}
