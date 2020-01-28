<?php
namespace NsCreed\MigrationPath;


use Exception;

class InvalidPathException extends Exception
{
    public function __construct($path)
    {
        parent::__construct(sprintf('The directory "%s" does not exist', $path));
    }
}
