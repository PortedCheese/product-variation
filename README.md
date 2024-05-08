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
    measurementAdminRoutes(true) - Использовать роуты для управления измерениями из пакета
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

    enableVariationSpecifications(false) - Включить характеристики для вариаций
    
    
### Versions
    
    v1.3.0: variation specifications 
        Обновление:
        - новый параметр конфига:  "productVariationSpecificationAdminRoutes" => true,
                                   "enableProductVariationsProductSpecifications" => true
        - php artisan migrate
        - php artisan make:product-variation --controllers  (y - Admin/ProductVariationProductSpecificationController)
        - php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force
        - npm rub
        Проверить переопределение:
        - Admin/ProductVariationController > store, update
        - Resources/admin/product-variations > add specifications; 
            Resourses/admin/orders/show, Resources/site/variations/show;
            Resources/notifications
        - Models/ProductVariation > specifications, specificationsArray; Models/OrderItem > specifications
        - Observers/ProductVariationObserver > deleting
        - Helpers/OrderActionsManager > addItemToOrder
        - Components > ProductVariationList, AddProductVariation, EditProductVariation, ChoodrProductVariation, OrderSingleProduct
        
        
    v1.2.0-v1.2.2: measurement (category-product ^1.4)
        - новый параметр конфига: measurementAdminRoutes(true)
        Проверить переопределение:
        - Resourses: ProductVariation.php
        - Models: ProductVariation, OrderItem 
        - Controllers: Admin/ProductVatiationController > store, update
        - Observers: OrderItemObserver (addMeasurementToItem)
        - Components: addProductVariation, EditProductVariation, ProductVariationList, ChooseProductVAriation, ProductTeaserPrice
        - scss: add rub-format__measurement
        - Blades: admin.orders.show, admin.product-variations.includes.list,notifications.order-client, notifications.order-user
        Обновление:
        - php artisan migrate 
            (create measurements table & add measurement_id [null|int] to product_variations table & add measurement [string] to order_items)
        - php artisan make:product-variation --models --policies --controllers (y - to Measurement)
        - php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force
        - npm run 
    v1.1.6: fix php8.1  deprecation warning
        - проверить переопределение Models/ProductVariation > getHumanSalePriceAttribute()
    v1.1.4-v1.1.5:
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