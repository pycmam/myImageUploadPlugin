<?php

/**
 * PluginImageRealtyTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginImageRealtyTable extends ImageTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object PluginImageRealtyTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PluginImageRealty');
    }
}