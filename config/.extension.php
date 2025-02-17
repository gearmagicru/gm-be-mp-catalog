<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации расширения.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'translator' => [
        'locale'   => 'auto',
        'patterns' => [
            'text' => [
                'basePath' => __DIR__ . '/../lang',
                'pattern'   => 'text-%s.php'
            ]
        ],
        'autoload' => ['text'],
        'external' => [BACKEND, 'api']
    ],

    'accessRules' => [
        // для авторизованных пользователей панели управления
        [ // разрешение "Полный доступ" (any: view, read, install, download)
            'allow',
            'permission'  => 'any',
            'controllers' => [
                'Components' => ['data', 'view'],
                'Component'  => ['view'],
                'Download'   => ['view', 'run', 'status'],
                'Selection'  => ['view']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Просмотр" (view)
            'allow',
            'permission'  => 'view',
            'controllers' => [
                'Components' => ['data', 'view'],
                'Component'  => ['view'],
                'Selection'  => ['view']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Чтение" (read)
            'allow',
            'permission'  => 'read',
            'controllers' => [
                'Components' => ['data']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Установка" (install)
            'allow',
            'permission'  => 'install',
            'controllers' => [
                'Components' => ['data']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Загрузка" (download)
            'allow',
            'permission'  => 'download',
            'controllers' => [
                'Components' => ['data']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Информация о расширении" (info)
            'allow',
            'permission'  => 'info',
            'controllers' => ['Info'],
            'users'       => ['@backend']
        ],
        [ // для всех остальных, доступа нет
            'deny'
        ]
    ],

    'viewManager' => [
        'id'          => 'g-marketplace-catalog-{name}',
        'useTheme'    => true,
        'useLocalize' => true,
        'viewMap'     => [
            // информация о расширении
            'info' => [
                'viewFile'      => '//backend/extension-info.phtml', 
                'forceLocalize' => true
            ],
        ]
    ]
];
