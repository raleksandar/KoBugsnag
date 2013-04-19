<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Kohana wrapper for Bugsnag Notifier class.
 *
 * @package    KoBugsnag
 * @category   KoBugsnag/Notifier
 * @author     Aleksandar Ružičić <aleksandar@ruzicic.info>
 */
abstract class KoBugsnag_KoBugsnag extends Bugsnag
{
    /**
     * This is called by module's init.php
     */
    public static function init()
    {
        $config = Kohana::$config->load('bugsnag');

        if (isset($config['context']))
        {
            static::setContext($config['context']);
        }

        if (isset($config['release_stage']))
        {
            static::setReleaseStage($config['release_stage']);
        }

        if (isset($config['release_stages_notify']))
        {
            static::setNotifyReleaseStages($config['release_stages_notify']);
        }

        if (isset($config['filters']))
        {
            static::setFilters($config['filters']);
        }

        if (isset($config['use_ssl']))
        {
            static::setUseSSL($config['use_ssl']);
        }

        if (isset($config['error_reporting_level']))
        {
            static::setErrorReportingLevel($config['error_reporting_level']);
        }

        if (isset($config['project_root']))
        {
            static::setProjectRoot($config['project_root']);
        }

        static::register($config['api_key']);

        if ( ! isset($config['enabled']) or $config['enabled'] )
        {
            $static = get_called_class();

            set_error_handler("$static::errorHandler");
            set_exception_handler("$static::exceptionHandler");
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function exceptionHandler($exception)
    {
        parent::exceptionHandler($exception);

        if (Kohana::$errors === true)
        {
            Kohana_Exception::handler($exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function errorHandler($errno, $errstr, $errfile='', $errline=0, $errcontext=array())
    {
        parent::errorHandler($errno, $errstr, $errfile, $errline, $errcontext);

        if (Kohana::$errors === true)
        {
            Kohana::error_handler($errno, $errstr, $errfile, $errline);
        }
    }
}