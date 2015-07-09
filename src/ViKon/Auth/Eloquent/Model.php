<?php

namespace ViKon\Auth\Eloquent;

use Illuminate\Contracts\Config\Repository;

class Model extends \Illuminate\Database\Eloquent\Model
{
    /** @var \Illuminate\Contracts\Config\Repository */
    protected static $config;

    /**
     * @param \Illuminate\Contracts\Config\Repository $config
     */
    public static function setConfig(Repository $config)
    {
        static::$config = $config;
    }

}