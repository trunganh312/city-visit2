<?php
// helpers/response.php

function sendResponse($status, $status_message, $data, $meta)
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Content-Type: application/json');
    header("HTTP/1.1 " . $status);
    $response['status'] = $status;
    $response['status_message'] = $status_message;
    $response['data'] = $data;
    $response['meta'] = $meta;
    echo json_encode($response);
}

class Response
{
    private $status;
    private $status_message;
    private $data;
    private Meta $meta;
    public function __construct($status, $status_message, $data = null, Meta $meta = null)
    {
        $this->status = $status;
        $this->status_message = $status_message;
        $this->data = $data;
        if ($meta) {
            $this->meta = $meta;
        } else {
            $this->meta = new Meta(1, 1, 0);
        }
    }
    public function sendResponse()
    {
        sendResponse($this->status, $this->status_message, $this->data, $this->meta);
    }
}

class Meta
{
    public $page;
    public $total_pages;
    public $total_elements;
    public function __construct($page, $total_pages, $total_elements)
    {
        $this->page = $page;
        $this->total_pages = $total_pages;
        $this->total_elements = intval($total_elements);
    }
}
