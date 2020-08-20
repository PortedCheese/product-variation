<?php

namespace PortedCheese\ProductVariation;

use App\Observers\Vendor\ProductVariation\OrderStateObserver;
use App\Observers\Vendor\ProductVariation\ProductVariationObserver;
use App\OrderState;
use App\Product;
use App\ProductVariation;
use Illuminate\Support\Facades\Blade;
use PortedCheese\ProductVariation\Console\Commands\ProductVariationMakeCommand;

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

        // Подключение путей.
        $this->addRoutes();

        // Подключение шаблонов.
        $this->loadViewsFrom(__DIR__ . "/resources/views", "product-variation");

        // Assets.
        $this->publishes([
            __DIR__ . "/resources/js/components" => resource_path("js/components/vendor/product-variation")
        ], "public");

        // Наблюдатели.
        $this->addObservers();
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
        // Управление статусами заказов.
        if (config("product-variation.orderStatesAdminRoutes")) {
            $this->loadRoutesFrom(__DIR__ . "/routes/admin/order-state.php");
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
    }
}
