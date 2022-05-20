<?php
namespace App\Traits;

trait EnumIterator{

    static function getAll($class) {
        $Class = new \ReflectionClass($class);
        return $Class->getConstants();
    }
}

