<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Пакет английской (британской) локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Marketplace Сatalog',
    '{description}' => 'Solutions catalog Marketplace',
    '{permissions}' => [
        'any'      => ['Full access', 'View and install from the Marketplace Catalog'],
        'view'     => ['View', 'Browse Marketplace Catalog'],
        'read'     => ['Reading', 'Reading the Marketplace Catalog'],
        'install'  => ['Install', 'Installation from the Marketplace Сatalog'],
        'download' => ['Donwload', 'Download from Marketplace Сatalog'],
    ],

    // Components: шаблон компонент
    'Developer' => 'Developer',
    'Category' => 'Category',
    'Editions' => 'Editions',
    'License' => 'License',
    'Price' => 'Price',
    'free' => 'free',
    'for all editions' => 'for all editions',
    'backend' => 'for Control Panel',
    'frontend' => 'for Site',
    // Components: панель инструментов
    'License' => 'License',
    'Installing downloaded Marketplace components' => 'Installing downloaded Marketplace components',
    'Refresh catalog' => 'Refresh Marketplace catalog',
    // Components: панель инструментов / фильтр
    'Apply' => 'Apply',
    'Reset' => 'Reset',
    'Type' => 'Type',
    'Edition' => 'Edition',
    'License type' => 'License type',
    'Use' => 'Use',
    'Name' => 'Name',
    'Payment' => 'Payment',
    'All' => 'All',
    'Paid' => 'Paid',
    'Free' => 'Free',
    'My edition' => 'My edition',
    // Components: cостояние компонент
    'update to the latest current version' => 'update to the latest current version',
    'download and install' => 'download and install',
    'latest version installed' => 'latest version installed',
    'install latest version' => 'install latest version',
    'not available' => 'not available',

    // Download: загрузка компонента
    '{download.title}' => 'Downloading the Marketplace component',
    'after downloading, installation will be performed' => 'after downloading, installation will be performed',
    'version' => 'version',
    'author' => 'author',
    'loaded' => 'loaded',
    // Download: ошибки
    'Unable to open file {0} for writing Marketplace component' => 'Unable to open file "{0}" for writing Marketplace component.',
    'Cannot invoke component installer because component type is not defined' => 'Cannot invoke component installer because component type is not defined.',

    // Selection: выбор установки компонента
    '{selection.title}' => 'Installing downloaded components (updates) Marketplace',
    'Go to the installer component' => 'Go to the installer component',
    '{selection.header}' => '<b>{0}</b> {{0},component,component,components,components} loaded into your web application.',
    'You can install the components now or later using the Marketplace catalog' 
        => 'You can install the components now or do it later using the Marketplace catalog.',
    'To download components, use the Marketplace catalog' => 'To download components, use the Marketplace catalog.',
    '{selection.footer}' => 'Attention! Web-studio "GearMagic" is not responsible for the components of other developers. For all questions related to the operation of third-party components, as well as in cases of disruption of your web application caused by the operation of third-party components, contact the component developers themselves.',
    'Version' => 'Version',

    // Component: панель кнопок
    'Download and Update' => 'Download and Update',
    'Download and Install' => 'Download and Install',
    'Close' => 'Close',
    // Component: сайдбар
    'Added' => 'Added',
    'Updated' => 'Updated',
    'Current version' => 'Current version',
    'For editions' => 'For editions',
    'License' => 'License',
    'Author' => 'Author',
    'not specified' => 'not specified',
    'for all' => 'for all', 
    'Terms of use' => 'Terms of use',
    'No terms of use' => 'User agreement <b>none</b>.',
    // Component: вкладки
    'Details' => 'Component',
    // Component: вкладки / сообщения
    'Error: unable to determine type of installed component' => '<b>Ошибка</b>: unable to determine type of installed component.',
    'Error: unable to determine installed component version' => '<b>Ошибка</b>: it is impossible to determine the version of the installed Marketplace component.',
    'Update the version of your component ({0}) from {1} to {2}' => 'Update the version of your component ({0}) from <b>{1}</b> to <b>{2}</n>.',
    'Install the new version {1} of the {0} Marketplace component' => 'Install the new version <b>{1}</b> of the {0} Marketplace component.', 
    'You have the latest version {0} of the Marketplace component ({1})' => 'You have the latest version <b>{0}</b> of the Marketplace component ({1}).',
    'Description of the Marketplace component' => 'Description of the Marketplace component',
    'Component description missing' => 'Component description <b>missing</b>.',
    'Screenshots' => 'Screenshots',
    'Screenshots of the Marketplace component' => 'Screenshots of the Marketplace component',
    'Screenshots of component missing' => 'Screenshots of component <b>missing</b>.',
    'Installing' => 'Installing',
    'Marketplace component installation guide' => 'Marketplace component installation guide',
    'Installation guide missing' => 'Installation guide missing <b>missing</b>.',
    'Reviews' => 'Reviews',
    'Reviews about the Marketplace component' => 'Reviews about the Marketplace component',
    'Reviews about component missing' => 'Reviews about component <b>missing</b>.',
    'Log in to leave a review or ask a question to the developer' => '<b>Log in</b> to leave a review or ask a question to the developer.',
    'Changelog' => 'Changelog',
    'Contributors, developers, changelog' => 'Contributors, developers, changelog',
    'There are no entries in the changelog' => 'There are <b>no entries</b> in the changelog.',
    'Tab text is temporarily missing' => 'Tab text is temporarily <b>missing</b>.',
    // Component: информация о компоненте
    '{component.title}' => '{0} <span>{1}</span>',
    // Component: панель управления
    'Install' => 'Install',
    // Component: всплывающие сообщения / текст
    'Unable to get Marketplace catalog component' => 'Unable to get Marketplace catalog component.',

    // Ошибки API
    'Unable to get Marketplace catalog items' => 'Unable to get Marketplace catalog items.',
    'Unable to get Marketplace catalog items in JSON format' => 'Unable to get Marketplace catalog items in JSON format.',
    'Unable to get API data in JSON format' => 'Unable to get API data in JSON format.',
    'An unknown error occurred while fetching API data' => 'An unknown error occurred while fetching API data.',
    'Unable to execute API request' => 'Unable to execute API request.',
    // Статусы ошибок в API
    'CMP_SEARCH_ERROR' => 'Error in the search for the Marketplace component.',
    'CMP_NOT_FOUND' => 'Marketplace component not found.',
    'CMP_REPOSITORY_NOT_FOUND' => 'Marketplace component repository not found.',
    'CMP_DESC_NOT_FOUND' => 'Marketplace component description file not found.',
    'CMP_DESC_FILE_BAD' => 'The description file of the Marketplace component is corrupted (unreadable).',
    'CMP_DESC_FILE_NOT_READ' => 'The description file of the Marketplace component is not decoded into the required format.',
    'INVALID_ARGUMENT' => 'Invalid or missing parameter in API request.',
    'CMP_DWN_FILE_NOT_FOUND' => 'Marketplace component download file not found.',
    'CMP_DWN_FILE_ERROR_READ' => 'Error reading Marketplace component file.',
    'MP_CATALOG_READ_ERROR' => 'Error reading Marketplace catalog.',
    'MP_DATA_READ_ERROR' => 'Error reading Marketplace data.'
];
