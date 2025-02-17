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
class Component extends BaseObject
{
    /**
     * @param string $componentId
     * @param string $type
     * @param string $version
     * 
     * @return string|null 
     */
    public function getState(string $componentId, string $type, string $version): ?string
    {
        // реестры установленных компонентов: модулей, расширений, виджетов
        if ($type === 'module')
            $registry = Gm::$app->modules->getRegistry();
        else
        if ($type === 'extension')
            $registry = Gm::$app->extensions->getRegistry();
        else
            return '';

        if ($registry->has($componentId)) {
            $clientVersion = $registry->getVersion($componentId);
            if ($clientVersion) {
                if (version_compare($version, $clientVersion, '>')) {
                    return 'update';
                }
                return '';
            }
        }
        return 'install';
    }
}
