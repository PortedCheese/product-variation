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

Если надо выгрузить конфиг:

    php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=config
    
Переменные конфига:

    productVariationAdminRoutes - Использовать роуты для управления вариациями из пакета
    orderStatesAdminRoutes - Использовать роуты для управления сатусами заказа из пакета
    orderAdminRoutes - Использовать роуты для управления заказами из пакета
    
    ordersSiteRoutes - Использовать роуты для заказа из пакета
    
    variationFacade - Класс фасада для действий с вариациями
    orderFacade - Класс фасада для действий с заказами
    
    orderNumberHasLetter - Номер заказа с буквой в начале
    orderDigitsLength - длина цифровой части номера заказа
    
    clientNotifyEmail - Адрес куда уходят уведомления о новом заказе для клиента
    enableClientNotification - Включить уведомления о заказе для клиента
    enableUserNotification - Включить уведомления о заказе для пользователя