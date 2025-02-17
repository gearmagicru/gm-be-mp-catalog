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
use Gm\Panel\Data\Provider\Pagination;
use Gm\Exception\InvalidArgumentException;
use Gm\Backend\Marketplace\ApiCommand\ApiCommand;

/**
 * Модель каталога компонентов Маркетплейс.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\Catalog\Model
 * @since 1.0
 */
class Components extends BaseObject
{
    /**
     * Заголовки состояний компонента.
     * 
     * @var array
     */
    public array $stateTitles = [];

    /**
     * Категории компонентов.
     * 
     * @var array
     */
    public array $categories = [];

    /**
     * Разработчики.
     * 
     * @var array
     */
    public array $developers = [];

    /**
     * Виды компонентов.
     * 
     * @var array
     */
    public array $types = [];

    /**
     * Коды видов компонентов.
     * 
     * @var array
     */
    public array $typeCodes = [];

    /**
     * Редакции веб-приложений.
     * 
     * @var array
     */
    public array $editions = [];

    /**
     * Виды лицензий компонентов.
     * 
     * @var array
     */
    public array $licenseTypes = [];

    /**
     * Фильтр компонентов.
     * 
     * @var string|array|null
     */
    public string|array|null $filter = null;

    /**
     * @var ApiCommand
     */
    protected ApiCommand $command;

    /**
     * Пагинация или параметры конфигурации пагинации элементов данных.
     * 
     * Если указаны параметры, то будет создан объект пагинации в {@see BaseProvider::configure()}.
     * Если значение `false`, разбивка элементов на страницы будет не доступна.
     * 
     * @see BaseProvider:setPage()
     *
     * @var Pagination|array|false|null
     */
    public Pagination|array|false|null $pagination = null;

    /**
     * {@inheritdoc}
     */
    public function configure(array $config): void
    {
        parent::configure($config);

        if (is_array($this->pagination)) {
            $this->setPagination($this->pagination);
        }
    }

    /**
     * Устанавливает или создаёт объект пагинации (разбивки элементов на страницы).
     * 
     * @see BaseProvider::$pagination
     * 
     * @param Pagination|array|false $value Объект пагинации или параметры конфигурации для 
     *     создания объекта. Если значение `false`, пагинация будет не доступна.
     * 
     * @return void
     * 
     * @throws InvalidArgumentException Неправильно указано значение пагинации.
     */
    public function setPagination(Pagination|array|false $value): void
    {
        if (is_array($value)) {
            if (isset($value['class']))
                $this->pagination = Gm::createObject($value);
            else
                $this->pagination = new Pagination($value);
        } else
        if ($value instanceof Pagination || $value === false)
            $this->pagination = $value;
        else
            // Для установки нумерация страниц, необходимо чтобы значение имело массив 
            // параметров конфигурации или значение `false`
            throw new InvalidArgumentException(
                'To set pagination, it is necessary that the value has an array of configuration parameters or false.'
            );
    }

    /**
     * Возвращает объект пагинации (разбивки элементов на страницы), используемый 
     * поставщиком данных.
     * 
     * @see BaseProvider::$pagination
     * 
     * @return Pagination|false Возвращает значение `false`, если пагинация не доступна.
     */
    public function getPagination(): Pagination|false
    {
        if ($this->pagination === null) {
            $this->setPagination([]);
        }
        return $this->pagination;
    }

    /**
     * Возвращает управление API для свервера Маркетплейс.
     * 
     * @return ApiCommand
     */
    public function getApiCommand(): ApiCommand
    {
        if (!isset($this->command)) {
            $this->command = Gm::$app->modules->getObject('ApiCommand\ApiCommand', 'gm.be.mp');
        }
        return $this->command;
    }

    /**
     * Возвращает имена редакций веб-приложения.
     * 
     * @param string $ids Идентификаторы редакций.
     * 
     * @return string
     */
    protected function getEditionNames(string $ids) :string
    {
        if (empty($ids)) return '';

        $ids = explode(',', $ids);
        $editions = [];
        foreach ($ids as $id) {
            $editions[] = $this->editions[$id] ?? SYMBOL_NONAME;
        }
        return implode(', ', $editions);
    }

    /**
     * Выполняет подготовку компонентов Маркетплейс перед выводом.
     * 
     * @param mixed $items Компоненты Маркетплейс.
     * 
     * @return void
     */
    protected function prepareItems(array &$items): void
    {
        /** @var \Gm\ModuleManager\ModuleRegistry $mRegistry */
        $mRegistry = Gm::$app->modules->getRegistry();
        /** @var \Gm\ExtensionManager\ExtensionRegistry $eRegistry */
        $eRegistry = Gm::$app->extensions->getRegistry();
        /** @var \Gm\WidgetManager\WidgetRegistry $wRegistry */
        $wRegistry = Gm::$app->widgets->getRegistry();
        foreach ($items as &$item) {
            $item['editions']  = $this->getEditionNames($item['editionId'] ?? '');
            $item['category']  = $this->categories[$item['categoryId']] ?? '';
            $item['developer'] = $this->developers[$item['developerId']] ?? '';
            $item['license']   = $this->licenseTypes[$item['licenseId']] ?? '';

            /**
             * Опредление вида реестра компонента
             */
            // код вида компонента
            $typeCode = $this->typeCodes[$item['typeId']] ?? '';
            $item['typeCls'] = $typeCode ?: 'none';
            $item['type']    = $this->types[$item['typeId']] ?? '';
            switch ($typeCode) {
                // модуль
                case 'module': $registry = $mRegistry; break;
                // расширение
                case 'extension': $registry = $eRegistry; break;
                // виджет
                case 'widget': $registry = $wRegistry; break;
                default:
                    $registry = null;
            }

            /**
             * Опредление состояния компонента (installed, disabled, download, update)
             */
            // доступность компонента
            $enabled = $item['enabled'];
            // состояние компонента
            $state = $enabled ? 'installed' : 'disabled';
            // версия компонента
            $serverVersion = $item['version'];
            // версия установленного компонента
            $clientVersion = false;
            // если известен вид компонента и компонент доступен для установки или обновления
            if ($enabled && $registry) {
                // если компонент уже установлен
                if ($registry->has($item['componentId'])) {
                    $clientVersion = $registry->getVersion($item['componentId']);
                    if ($clientVersion && $serverVersion) {
                        if (version_compare($serverVersion, $clientVersion, '>')) {
                            $state = 'update';
                        }
                    }
                // если компонент не установлен и не скачен
                } else
                    $state = 'download';
            }
            $item['state'] = $state;

            /**
             * Опредление заголовка версии компонента
             */
            if ($enabled) {
                if ($state === 'update')
                    $item['version'] = 'v' . $clientVersion . ' <span>&rarr;</span> v' . $serverVersion;
                else
                    $item['version']  = 'v' . ($clientVersion ? $clientVersion : $serverVersion);
            }

            /**
             * Опредление заголовка состояния компонента
             */
            $stateTitle = $this->stateTitles[$state];
            if ($clientVersion & $state === 'installed') {
                $stateTitle .= ' - <b>' . $clientVersion . '</b>';
            }
            $item['stateTitle'] = $stateTitle;

            /**
             * Локализация названия, описания, назначения
             */
            if ($item['nameLo']) {
                $item['name'] = $item['nameLo'];
            }
            if ($item['descriptionLo']) {
                $item['description'] = $item['descriptionLo'];
            }
            $item['use'] = $this->uses[$item['use']] ?? SYMBOL_NONAME;
        }
    }

    protected function getFilterParams(): string
    {
        if ($this->filter === null) {
            return $this->filter = Gm::$app->request->post('filter', '');
        }

        if (is_array($this->filter))
            return json_encode($this->filter);
        else
        if (is_string($this->filter))
            return $this->filter;
        else
            return '';
    }

    /**
     * Возвращает компоненты Маркетплейс.
     * 
     * @return false|array
     */
    public function getItems(): false|array
    {
        /** @var array Параметры запроса */ 
        $params = [
            'filter' => $this->getFilterParams()
        ];
        /** @var \Gm\Backend\Marketplace\ApiCommand\ApiCommand $command */
        $command = $this->getApiCommand();

        /** @var Pagination $pagination */
        $pagination = $this->getPagination();

        /** @var false|array $result Компоненты каталога Маркетплейс */
        $result = $command->getComponents(array_merge(
            $params,
            $pagination->getQueryParams()
        ));
        // если была ошибка запроса
        if ($result === false) return false;

        // атрибуты компонентов
        $items = $result['items'] ?? [];
        // всего компонентов
        $total = $result['total'] ?? sizeof($items);
        if ($items) {
            $this->prepareItems($items);
        }
        return [
            'items' => $items,
            'total' => $total
        ];
    }
}
