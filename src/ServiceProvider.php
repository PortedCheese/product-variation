<?php

namespace PortedCheese\ProductVariation;

use App\Observers\Vendor\ProductVariation\OrderItemObserver;
use App\Observers\Vendor\ProductVariation\OrderObserver;
use App\Observers\Vendor\ProductVariation\OrderStateObserver;
use App\Observers\Vendor\ProductVariation\ProductVariationObserver;
use App\Order;
use App\OrderItem;
use App\OrderState;
use App\Product;
use App\ProductVariation;
use PortedCheese\CategoryProduct\Events\ProductListChange;
use PortedCheese\ProductVariation\Console\Commands\ProductVariationMakeCommand;
use PortedCheese\ProductVariation\Events\CreateNewOrder;
use PortedCheese\ProductVariation\Listeners\SendNewOrderNotify;
use PortedCheese\ProductVariation\Listeners\UpdatePricesList;
use PortedCheese\ProductVariation\Observers\ProductObserver;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        // Публикация конфигурации.
        $this->publishes([
            __DIR__ . "/config/product-variation.php" => config_path("product-variation.php")
        ], "config");

        // Подключение миграций.
        $this->loadMigrationsFrom(__DIR__ . "/database/migrations");

        // Console.
        if ($this->app->runningInConsole()) {
            $this->commands([
                ProductVariationMakeCommand::class,
            ]);
        }

        if (config("product-variation.enableVariations")) {
            // Подключение путей.
            $this->addRoutes();

            // Подключение шаблонов.
            $this->loadViewsFrom(__DIR__ . "/resources/views", "product-variation");
        }

        // Assets.
        $this->publishes([
            __DIR__ . "/resources/js/components" => resource_path("js/components/vendor/product-variation"),
            __DIR__ . "/resources/sass" => resource_path("sass/vendor/product-variation")
        ], "public");

        // Наблюдатели.
        $this->addObservers();

        // События.
        $this->addEvents();

        // Расширить конфиг сайта.
        $this->extendConfigVariables();
    }

    public function register()
    {
        // Стандартная конфигурация.
        $this->mergeConfigFrom(
            __DIR__ . "/config/product-variation.php", "product-variation"
        );

        // Facades.
        $this->initFacades();
    }

    /**
     * Подключение Facade.
     */
    protected function initFacades()
    {
        $this->app->singleton("product-variation-actions", function () {
            $class = config("product-variation.variationFacade");
            return new $class;
        });

        $this->app->singleton("order-actions", function () {
            $class = config("product-variation.orderFacade");
            return new $class;
        });
    }

    protected function extendConfigVariables()
    {
        // SVG
        $svg = app()->config["theme.configSvg"];
        $svg[] = "product-variation::site.includes.svg";
        app()->config["theme.configSvg"] = $svg;

        // Filters.
        if (config("product-variation.enablePriceSort")) {
            $filters = app()->config["category-product.sortOptions"];
            if (empty($filters["price.desc"])) {
                $filters["price.desc"] = (object) [
                    "title" => "Сначала дорогие",
                    "by" => "price",
                    "direction" => "desc",
                    "ico" => "catalog-sort-amount",
                    "arrow" => "down",
                ];
            }
            if (empty($filters["price.asc"])) {
                $filters["price.asc"] = (object) [
                    "title" => "Сначала дешевые",
                    "by" => "price",
                    "direction" => "asc",
                    "ico" => "catalog-sort-amount",
                    "arrow" => "up",
                ];
            }
            app()->config["category-product.sortOptions"] = $filters;
        }
    }

    /**
     * Добавление путей.
     */
    protected function addRoutes()
    {
        $this->addAdminRoutes();
        $this->addSiteRoutes();
    }

    protected function addSiteRoutes()
    {
        // Управление вариациями.
        if (config("product-variation.ordersSiteRoutes")) {
            $this->loadRoutesFrom(__DIR__ . "/routes/site/order.php");
        }
    }

    protected function addAdminRoutes()
    {
        // Управление вариациями.
        if (config("product-variation.productVariationAdminRoutes")) {
            $this->loadRoutesFrom(__DIR__ . "/routes/admin/product-variation.php");
        }
        // Управление Характеристиками вариаций.
        if (config("product-variation.productVariationSpecificationAdminRoutes")) {
            $this->loadRoutesFrom(__DIR__ . "/routes/admin/product-variation-specification.php");
        }
        // Управление статусами заказов.
        if (config("product-variation.orderStatesAdminRoutes")) {
            $this->loadRoutesFrom(__DIR__ . "/routes/admin/order-state.php");
        }
        // Управление заказми.
        if (config("product-variation.orderAdminRoutes")) {
            $this->loadRoutesFrom(__DIR__ . "/routes/admin/order.php");
        }
        // Управление измерениями
        if (config("product-variation.measurementAdminRoutes")) {
            $this->loadRoutesFrom(__DIR__ . "/routes/admin/measurement.php");
        }
    }

    /**
     * Наблюдатели.
     */
    protected function addObservers()
    {
        if (class_exists(ProductVariationObserver::class) && class_exists(ProductVariation::class)) {
            ProductVariation::observe(ProductVariationObserver::class);
        }

        if (class_exists(OrderStateObserver::class) && class_exists(OrderState::class)) {
            OrderState::observe(OrderStateObserver::class);
        }

        if (class_exists(OrderObserver::class) && class_exists(Order::class)) {
            Order::observe(OrderObserver::class);
        }

        if (class_exists(OrderItemObserver::class) && class_exists(OrderItem::class)) {
            OrderItem::observe(OrderItemObserver::class);
        }

        if (class_exists(ProductObserver::class) && class_exists(Product::class)) {
            Product::observe(ProductObserver::class);
        }
    }

    /**
     * Подписки на события.
     */
    protected function addEvents()
    {
        // Создание заказа.
        $this->app["events"]->listen(CreateNewOrder::class, SendNewOrderNotify::class);
        // Изменение списка товаров.
        $this->app["events"]->listen(ProductListChange::class, UpdatePricesList::class);
    }
}
