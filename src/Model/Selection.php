<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Marketplace\Catalog\Model;

use Gm;
use Gm\Stdlib\BaseObject;

/**
 * Модель выбора установки загруженного компонента Маркетплейс.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\Catalog\Model
 * @since 1.0
 */
class Selection extends BaseObject
{
    /**
     * Возвращает все загруженные компоненты Маркетплейс, которые требуют установки.
     * 
     * @return array
     */
    public function getItems(): array
    {
        $result = [];
        /** @var  array $searchInclude Возвращаемая информация о компоненте */
        $searchInclude = ['icon' => true, 'version' => true, 'config' => true];
        /** @var \Gm\ModuleManager\ModuleManager $modules Менеджер модулей */
        $modules = Gm::$app->modules;
        /** @var \Gm\ModuleManager\ModuleRepository $repository Репозиторий модулей */
        $repository = $modules->getRepository();
        /** @var array $paths Пути всех найденых компонентов */
        $paths = $repository->findPaths();
        $rows = $repository->find(
            $repository::MODULE, 'nonInstalled', $searchInclude, '.install.php', $paths[$repository::MODULE]
        );
        foreach ($rows as $index => $row) {
            // локальный путь к модулю
            $path = $row['path'] ?? '';
            // пространство имён модуля
            $namespace = $row['namespace'] ?? '';
            $row = $this->fetchRow($row);
            $row['type'] = 'module';
            $row['installId'] = $modules->encryptInstallId($path, $namespace);
            $result[] = $row;
        }

        /** @var Gm\ExtensionManager\ExtensionManager $extensions Менеджер расширений модулей */
        $extensions = Gm::$app->extensions;
        /** @var \Gm\ExtensionManager\ExtensionRepository $repository Репозиторий расширений модулей */
        $repository = $extensions->getRepository();
        $rows = $repository->find(
            $repository::EXTENSION, 'nonInstalled', $searchInclude, '.install.php', $paths[$repository::EXTENSION]
        );
        foreach ($rows as $index => $row) {
            // локальный путь к расширению
            $path = $row['path'] ?? '';
            // пространство имён расширения
            $namespace = $row['namespace'] ?? '';
            $row = $this->fetchRow($row);
            $row['type'] = 'extension';
            $row['installId'] = $extensions->encryptInstallId($path, $namespace);
            $result[] = $row;
        }

        /** @var Gm\WidgetManager\WidgetManager $widgets Менеджер расширений модулей */
        $widgets = Gm::$app->widgets;
        /** @var \Gm\WidgetManager\WidgetRepository $repository Репозиторий расширений модулей */
        $repository = $widgets->getRepository();
        $rows = $repository->find(
            $repository::WIDGET, 'nonInstalled', $searchInclude, '.install.php', $paths[$repository::WIDGET]
        );
        foreach ($rows as $index => $row) {
            // локальный путь к виджету
            $path = $row['path'] ?? '';
            // пространство имён виджета
            $namespace = $row['namespace'] ?? '';
            $row = $this->fetchRow($row);
            $row['type'] = 'widget';
            $row['installId'] = $widgets->encryptInstallId($path, $namespace);
            $result[] = $row;
        }

        /** @var Gm\PluginManager\PluginManager $plugins Менеджер расширений модулей */
        $plugins = Gm::$app->plugins;
        /** @var \Gm\WidgetManager\WidgetRepository $repository Репозиторий расширений модулей */
        $repository = $plugins->getRepository();
        $rows = $repository->find(
            $repository::PLUGIN, 'nonInstalled', $searchInclude, '.install.php', $paths[$repository::PLUGIN]
        );
        foreach ($rows as $index => $row) {
            // локальный путь к виджету
            $path = $row['path'] ?? '';
            // пространство имён виджета
            $namespace = $row['namespace'] ?? '';
            $row = $this->fetchRow($row);
            $row['type'] = 'plugin';
            $row['installId'] = $plugins->encryptInstallId($path, $namespace);
            $result[] = $row;
        }
        return $result;
    }

    /**
     * Возвращает атрибуты для вывода информации о компонентах Маркетплейс.
     * 
     * @param array $row Атрибуты компонента полученные менеджером.
     * 
     * @return array
     */
    public function fetchRow(array $row): array
    {
        // версия модуля
        $version = $row['version'];
        $details = '';
        if ($version['version']) {
            $details = $version['version'];
            if ($version['versionDate']) {
                $details = $details . ' / ' . Gm::$app->formatter->toDate($version['versionDate']);
            }
        } else {
            if ($version['versionDate'])
                $details = 'from' . ' ' . Gm::$app->formatter->toDate($version['versionDate']);
            else
                $details = 'unknow';
        }
        return [
            'icon'           => $row['icon'], // значок
            'name'           => $row['name'], // название
            'description'    => $row['description'], // описание
            'version'        => $version['version'], // номер версии
            'versionAuthor'  => $version['author'], // автор версии
            'versionDate'    => $version['versionDate'], // дата версии
            'details'        => $details, // название и описание
        ];
    }
}
