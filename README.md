# Product Variation

## Description

Добавление вариаций к товарам и заказов

## Install
    php artisan migrate
    
    php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force
    
    php artisan make:product-variation
                            {--all : Run all}
                            {--models : Export models}
                            {--observers : Export observers}
                            {--controllers : Export controllers}
                            {--policies : Export and create rules}
                            {--only-default : Create default rules}
                            {--scss : Export scss}
                            {--vue : Export vue}
                            
## Config
    
    NEW_ORDER_NOTIFY_EMAIL - Адрес куда уходят уведомления о новом заказе для клиента

Выгрузить конфигурацию:

    php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=config
    
Переменные конфигурации:

    enableVariations(true) - Включить вариации
    productVariationAdminRoutes(true) - Использовать роуты для управления вариациями из пакета
    ordersSiteRoutes(true) - Использовать роуты для сайта из пакета
    orderStatesAdminRoutes(true) - Использовать роуты для управления сатусами заказа из пакета
    orderAdminRoutes(true) - Использовать роуты для управления заказами из пакета
    
    variationFacade - Класс фасада для действий с вариациями
    orderFacade - Класс фасада для действий с заказами
    
    productVariationResource - Класс для фасада вариаций
    
    orderNumberHasLetter - Номер заказа с буквой в начале
    orderDigitsLength - длина цифровой части номера заказа
    
    clientNotifyEmail(NEW_ORDER_NOTIFY_EMAIL, false) - Адрес куда уходят уведомления о новом заказе для клиента
    enableClientNotification(true) - Включить уведомления о заказе для клиента
    enableUserNotification(true) - Включить уведомления о заказе для пользователя
    
    enablePriceFilter(true) - Включить фильтрацию по ценам
    priceFilterKey(product_price) - Ключ для фильтра по цене
    enablePriceSort(true) - Включить сортировку по цене
    priceSortReplaceNull(1000000000) - Значение цены когда цены нет, для помещения пустых товаров в конец списка
    
    
### Versions
    v1.1.4:
        - изменен компонет выбора вариации ChooseProductVariationComponent
        (если нет ни одной опубликованной вариации, выводить сообщение "нет в наличии")
        Обновление:
        - php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force
        - npm run prod
    v1.1.3:
       - изменен компонет выбора вариации ChooseProductVariationComponent 
        (не показывать неопубликованную вариацию в компоненте, если вариация одна или если все вариации не опубликованы)
       Обновление:
       - php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force
       - npm run prod
    v1.1.2: 
        - изменен компонет выбора вариации ChooseProductVariationComponent (вывод неопубликованной вариации без radio)
        Обновление:
        - php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force
        - npm run prod
    v1.1.1: vendorName
    v1.0.4:
        - добавлен класс для фасада вариаций productVariatonResource
    v1.0.2:
        - Попарвлен вывод скидки при выборе вариации
    Обновление:
        - php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force
        - npm run prod