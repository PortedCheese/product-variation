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

    v1.0.2:
        - Попарвлен вывод скидки при выборе вариации
    Обновление:
        - php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force
        - npm run prod