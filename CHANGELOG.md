## Versions

###v3.0.2 bootstrap 5 fixes
Проверить переопределение:
- компонент OrderSingleProduct (btn-close, addons alerts)
  Обновление:

  php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force 
###v3.0.0 base 5 / bootstrap 5
Проверить переопределение:
- компоненты *
- scss: choose-variation, variation-price
- admin views: menu, orders,index, orders.sjow, orders.includes.user-info-modal

Обновление:

    php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force

###v2.0.0 Дополнения /addons
- Сеты в заказе: товар + дополнения. 
- Фильтрация доступных вариация и характеристик дополнений по выбранной вариации товара
- Обновлены уведомления - добавлены дополлнения

Добавлено:
- Миграции и Модель OrderItemSet и связка OrderItemSetId to OrderItems
- Models/Order > addons
- Models/OrderItem > orderItemSet, orderItermSets
- Models/ProductVariation > sets, addons
- Компонент OrderAddonComponent, ChooseAddonVariationComponent
- views: site.variations.addon-price 
- Observers/OrderItemObserver > deleting
- sass: variation_price__small

Обновление:
        
        php artisan migrate
        php artisan make:product-variation --models 
        php artisan make:product-variation --vue 
        php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force
        npm run
        php artisan cache:clear
        php artisan queue:restart

Проверить переопределение:

- Helpers/ProductVariationActionsManager > clearProductVariationsCache, getVariationsByAddon, getVariationsSpecificationsByAddon, getVariationsSpecificationsByProduct
- Helpers/OrderActionsManager >  addItemToOrder,  addVariationsToOrder, addAddonVariationSetsToOrder,  makeOrderFromCart
- Controllers/Admin/OrderController > show
- Controllers/Site/OrderController > newSingle, newSingleValidator
- Notifications/NewOrderClient , NewOrderUser
- Компоненты: ChooseProductVariation, ChooseSpecification, OrderSingle
- admin views: orders.show
- notification views: order-client, order-user


###v1.3.0-v1.3.1: variation specifications (category-product ^1.6)

Обновление:
        
- новый параметр конфига:  "productVariationSpecificationAdminRoutes" => true,
                           "enableProductVariationsProductSpecifications" => true
- выполнить


    php artisan migrate
    php artisan make:product-variation --controllers  (y - Admin/ProductVariationProductSpecificationController)
    php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force
    npm run
    php artisan cache:clear
    php artisan queue:restart

Проверить переопределение:

- Http/Resources/ProductVariation > specifications, product_image_url
- Admin/ProductVariationController > store, update
- Resources/admin/product-variations > add specifications,  
            Resources/admin/product-variations/includes/list > images-url, 
            Resourses/admin/orders/show, Resources/site/variations/show;
            Resources/notifications
- Models/ProductVariation > specifications, specificationsArray, image, clearImage, getSpecificationsArrayAttribute
- Models/OrderItem > specifications, variation
- Observers/ProductVariationObserver > deleting
- Helpers/OrderActionsManager > addItemToOrder
- Helpers/ProductVariationActionsManager > clearProductVariationsCache, getVariationSpecificationsArray,clearVariationSpecificationsArrayCache
- Components > ProductVariationList, AddProductVariation, EditProductVariation, ChooseProductVariation, OrderSingleProduct,ProductTeaserPrice
        
        
###v1.2.0-v1.2.2: measurement (category-product ^1.4)
Конфиг:
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


         php artisan migrate 
            (create measurements table & add measurement_id [null|int] to product_variations table & add measurement [string] to order_items)
         php artisan make:product-variation --models --policies --controllers (y - to Measurement)
         php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force
         npm run 

###v1.1.6: fix php8.1  deprecation warning
Проверить переопределение Models/ProductVariation > getHumanSalePriceAttribute()

###v1.1.4-v1.1.5: Изменен компонет выбора вариации ChooseProductVariationComponent
Если нет ни одной опубликованной вариации, выводить сообщение "нет в наличии")
       
Обновление:

         php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force
         npm run prod

###v1.1.3: изменен компонет выбора вариации ChooseProductVariationComponent 
не показывать неопубликованную вариацию в компоненте, если вариация одна или если все вариации не опубликованы

Обновление:

        php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force
        npm run prod

###v1.1.2: изменен компонет выбора вариации ChooseProductVariationComponent 
вывод неопубликованной вариации без radio

Обновление:

         php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force
         npm run prod

###v1.1.1: vendorName
###v1.0.4: добавлен класс для фасада вариаций productVariatonResource
###v1.0.2: Попарвлен вывод скидки при выборе вариации
Обновление:

         php artisan vendor:publish --provider="PortedCheese\ProductVariation\ServiceProvider" --tag=public --force
         npm run prod