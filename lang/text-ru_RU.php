<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Пакет русской локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Каталог Маркетплейс',
    '{description}' => 'Каталог решений Маркетплейс',
    '{permissions}' => [
        'any'      => ['Полный доступ', 'Просмотр и установка из каталога Маркетплейс'],
        'view'     => ['Просмотр', 'Просмотр каталога Маркетплейс'],
        'read'     => ['Чтение', 'Чтение каталога Маркетплейс'],
        'install'  => ['Установка', 'Установка из каталога Маркетплейс'],
        'download' => ['Загрузка', 'Загрузка из каталога Маркетплейс'],
    ],

    // Components: шаблон компонент
    'Developer' => 'Разработчик',
    'Category' => 'Категория',
    'Editions' => 'Редакции',
    'License' => 'Лицензия',
    'Price' => 'Цена',
    'free' => 'бесплатно',
    'for all editions' => 'для всех редакций',
    'backend' => 'для Панели управления',
    'frontend' => 'для Сайта',
    // Components: панель инструментов
    'License' => 'Лицензия',
    'Installing downloaded Marketplace components' => 'Установка загруженных компонентов Маркетплейс',
    'Refresh catalog' => 'Обновление каталога Маркетплейс',
    // Components: панель инструментов / фильтр
    'Apply' => 'Применить',
    'Reset' => 'Сбросить',
    'Type' => 'Вид',
    'Edition' => 'Редакция',
    'License type' => 'Вид лицензии',
    'Use' => 'Назначение',
    'Name' => 'Название',
    'Payment' => 'Стоимость',
    'All' => 'Все',
    'Paid' => 'Платные',
    'Free' => 'Бесплатные',
    'My edition' => 'Моя редакция веб-приложения',
    // Components: cостояние компонент
    'update to the latest current version' => 'обновить до последней актуальной версии',
    'download and install' => 'скачать и установить',
    'latest version installed' => 'установлена последняя версия',
    'install latest version' => 'установить последнюю версию',
    'not available' => 'не доступен',

    // Download: загрузка компонента
    '{download.title}' => 'Загрузка компонента Маркетплейс',
    'after downloading, installation will be performed' => 'после загрузки будет выполнена установка',
    'version' => 'версия',
    'author' => 'автор',
    'loaded' => 'загружено',
    // Download: ошибки
    'Unable to open file {0} for writing Marketplace component' => 'Невозможно открыть файл "{0}" для записи компонента Маркетплейс.',
    'Cannot invoke component installer because component type is not defined' => 'Невозможно вызвать установщик компонента, т. к. не определен тип компонента.',

    // Selection: выбор установки компонента
    '{selection.title}' => 'Установка загруженных компонентов (обновлений) Маркетплейс',
    'Go to the installer component' => 'Перейти к установщику загруженного компонента Маркетплейс',
    '{selection.header}' => 'В Ваше веб-приложение загружено <b>{0}</b> {{0},компонент,компонента,компонентов,компонентов}.',
    'You can install the components now or later using the Marketplace catalog' 
        => 'Вы можете установить компоненты сейчас или сделать это позже с помощью каталога Маркетплейс.',
    'To download components, use the Marketplace catalog' => 'Для загрузки компонентов используйте каталог Маркетплейс.',
    '{selection.footer}' => 'Внимание! Веб-студия "GearMagic" не несёт ответственности за компоненты других разработчиков. По всем вопросам, связанным с работой сторонних компонентов, а также в случаях нарушения работы вашего веб-приложения, вызванного работой сторонних компонентов, обращаться к самим разработчикам компонентов.',
    'Version' => 'Версия',

    // Component: панель кнопок
    'Download and Update' => 'Загрузить и Обновить',
    'Download and Install' => 'Загрузить и Установить',
    'Close' => 'Закрыть',
    // Component: сайдбар
    'Added' => 'Добавлен',
    'Updated' => 'Обнавлён',
    'Current version' => 'Текущая версия',
    'For editions' => 'Для редакций',
    'License' => 'Лицензия',
    'Author' => 'Автор',
    'not specified' => 'не указана',
    'for all' => 'для всех', 
    'Terms of use' => 'Пользовательское соглашение',
    'No terms of use' => 'Пользовательское соглашение <b>отсутствует</b>.',
    // Component: вкладки
    'Details' => 'Подробнее',
    // Component: вкладки / сообщения
    'Error: unable to determine type of installed component' => '<b>Ошибка</b>: невозможно определить вид установленного компонента Маркетплейс.',
    'Error: unable to determine installed component version' => '<b>Ошибка</b>: невозможно определить версию установленного компонента Маркетплейс.',
    'Update the version of your component ({0}) from {1} to {2}' => 'Обновите версию вашего компонента Маркетплейс ({0}) с <b>{1}</b> до более новой <b>{2}</b>.',
    'Install the new version {1} of the {0} Marketplace component' => 'Установите новую версию <b>{0}</b> компонента Маркетплейс ({1}).', 
    'You have the latest version {0} of the Marketplace component ({1})' => 'У вас установлена последняя версия <b>{0}</b> компонента Маркетплейс ({1}).',
    'Description of the Marketplace component' => 'Описание компонента Маркетплейс',
    'Component description missing' => 'Описание компонента <b>отсутствует</b>.',
    'Screenshots' => 'Скриншоты',
    'Screenshots of the Marketplace component' => 'Скриншоты компонента Маркетплейс',
    'Screenshots of component missing' => 'Скриншоты компонента <b>отсутствуют</b>.',
    'Installing' => 'Установка',
    'Marketplace component installation guide' => 'Руководство по установке компонента Маркетплейс',
    'Installation guide missing' => 'Руководство по установке <b>отсутствует</b>.',
    'Reviews' => 'Отзывы',
    'Reviews about the Marketplace component' => 'Отзывы о компоненте Маркетплейс',
    'Reviews about component missing' => 'Отзывы о компоненте <b>отсутствует</b>.',
    'Log in to leave a review or ask a question to the developer' => '<b>Авторизуйтесь</b>, чтобы оставить отзыв или задать вопрос разработчику.',
    'Changelog' => 'Журнал изменений',
    'Contributors, developers, changelog' => 'Участники, разработчики, журнал обновлений',
    'There are no entries in the changelog' => 'В Журнале изменений <b>отсутствую</b> записи.',
    'Tab text is temporarily missing' => 'Текст вкладки временно <b>отсутствует</b>.',
    // Component: информация о компоненте
    '{component.title}' => '{0} <span>{1}</span>',
    // Component: панель управления
    'Install' => 'Установить',
    // Component: всплывающие сообщения / текст
    'Unable to get Marketplace catalog component' => 'Невозможно получить компонент каталога Маркетплейс.',

    // Ошибки API
    'Unable to get Marketplace catalog items' => 'Невозможно получить элементы каталога Маркетплейс.',
    'Unable to get Marketplace catalog items in JSON format' => 'Невозможно получить элементы каталога Маркетплейс в формате JSON.',
    'Unable to get API data in JSON format' => 'Невозможно получить данные из API запроса в формате JSON.',
    'An unknown error occurred while fetching API data' => 'Возникла неизвестная ошибка при получении данных из API запроса.',
    'Unable to execute API request' => 'Невозможно выполнить API запрос.',
    // Статусы ошибок в API
    'CMP_SEARCH_ERROR' => 'Ошибка в поиске компонента Маркетплейс.',
    'CMP_NOT_FOUND' => 'Компонент Маркетплейс не найден.',
    'CMP_REPOSITORY_NOT_FOUND' => 'Репозиторий компонента Маркетплейс не найден.',
    'CMP_DESC_NOT_FOUND' => 'Файл описания компонента Маркетплейс не найден.',
    'CMP_DESC_FILE_BAD' => 'Файл описания компонента Маркетплейс повреждён (не читается).',
    'CMP_DESC_FILE_NOT_READ' => 'Файл описания компонента Маркетплейс не декодируется в нужный формат.',
    'INVALID_ARGUMENT' => 'Неправильно указан или пропущен параметр в API запросе.',
    'CMP_DWN_FILE_NOT_FOUND' => 'Файл загрузки компонента Маркетплейс не найден.',
    'CMP_DWN_FILE_ERROR_READ' => 'Ошибка чтения файла компонента Маркетплейс.',
    'MP_CATALOG_READ_ERROR' => 'Ошибка чтения каталога Маркетплейс.',
    'MP_DATA_READ_ERROR' => 'Ошибка чтения данных Маркетплейс.',
];
