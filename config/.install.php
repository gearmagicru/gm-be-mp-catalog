<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации установки расширения.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'id'          => 'gm.be.mp.catalog',
    'moduleId'    => 'gm.be.mp',
    'name'        => 'Marketplace Сatalog',
    'description' => 'Solutions catalog Marketplace',
    'namespace'   => 'Gm\Backend\Marketplace\Catalog',
    'path'        => '/gm/gm.be.mp.catalog',
    'route'       => 'catalog',
    'locales'     => ['ru_RU', 'en_GB'],
    'permissions' => ['any', 'view', 'read', 'install', 'download', 'info'],
    'events'      => [],
    'required'    => [
        ['php', 'version' => '8.2'],
        ['app', 'code' => 'GM MS'],
        ['app', 'code' => 'GM CMS'],
        ['app', 'code' => 'GM CRM'],
        ['module', 'id' => 'gm.be.mp']
    ]
];
