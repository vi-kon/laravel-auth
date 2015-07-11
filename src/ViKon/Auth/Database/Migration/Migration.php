<?php

namespace ViKon\Auth\Database\Migration;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Schema\Builder;

/**
 * Class Migration
 *
 * @package ViKon\Auth\Database\Migration
 *
 * @author  Kovács Vince<vincekovacs@hotmail.com>
 */
class Migration extends \Illuminate\Database\Migrations\Migration
{
    /** @var \Illuminate\Contracts\Config\Repository */
    protected static $config;

    /** @type \Illuminate\Database\Schema\Builder */
    protected static $schema;

    /**
     * @param \Illuminate\Contracts\Config\Repository $config
     */
    public static function setConfig(Repository $config)
    {
        static::$config = $config;
    }

    /**
     * @param \Illuminate\Database\Schema\Builder $schema
     */
    public static function setSchema(Builder $schema)
    {
        self::$schema = $schema;
    }
}