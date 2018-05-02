<?php

class BaseModel extends \Phalcon\Mvc\Model
{
    public $createdAt;
    public $updatedAt;

    public function beforeValidationOnCreate()
    {
        $this->createdAt = date('Y-m-d H:i:s');
    }
    public function beforeUpdate()
    {
        $this->updatedAt = date('Y-m-d H:i:s');
    }
}
