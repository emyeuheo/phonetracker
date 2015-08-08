<?php
/**
 * Created by PhpStorm.
 * User: macintosh
 * Date: 8/5/15
 * Time: 2:04 PM
 */

namespace App\APIs;


class BaseAPI
{
    protected $data;
    public $isPublic = true;
    public function setData($data) {
        $this->data = $data;
    }
}