<?php

namespace Factory;

use model\MyPDO;

class ConnectionFactory
{
    public static function createDb(): MyPDO
    {
        return MyPDO::getInstance(
            DB_CONNECTION_STRING,
            DB_LOGIN,
            DB_PWD,
            DB_OPTIONS
        );
    }

}