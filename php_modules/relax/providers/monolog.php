<?php
/*
 * This file is part of the Relax micro-framwork.
 *
 * (c) Olivier Philippon <https://github.com/DrBenton>
 * with inspiration from Fabien Potencier's Silex framework
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Relax\Exception\RelaxProviderException;

$params = $require('relax/params');


// Params check
if (!isset($params['monolog.enabled']) || !$params['monolog.enabled']) {
    throw new RelaxProviderException('Enable Monolog with the parameter "monolog.enabled" set to "true" before using Monolog Provider !');
}
if (!isset($params['monolog.filePath']) || !$params['monolog.filePath']) {
    throw new RelaxProviderException('Set Monolog target file path templates path with the "monolog.filePath" before using Monolog Provider !');
}


// Options setup
$monologOptions = isset($params['monolog.options']) ? $params['monolog.options'] : array() ;
$monologOptions = array_merge(array(
    'loggerName'            => 'relax',
    'level'                 => Logger::DEBUG,
), $monologOptions);


// Go! Go! Go!
$hasBridge = class_exists('Symfony\Bridge\Monolog\Logger');
$loggerClass = $hasBridge ? 'Symfony\Bridge\Monolog\Logger' : 'Monolog\Logger';

$logger = new $loggerClass($monologOptions['loggerName']);
//TODO: add Handlers customization
$logger->pushHandler(new StreamHandler($params['monolog.filePath'], $monologOptions['level']));


// Module exports
$module['exports'] = $logger;
