<?php

namespace PortedCheese\ProductVariation\Console\Commands;

use PortedCheese\BaseSettings\Console\Commands\BaseConfigModelCommand;

class ProductVariationMakeCommand extends BaseConfigModelCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "make:product-variation
                    {--all : Run all}
                    {--models : Export models}
                    {--controllers : Export controllers}
                    {--observers : Export observers}
                    {--policies : Export and create rules}
                    {--only-default : Create only default rules}
                    {--scss : Export scss}
                    {--vue : Export vue}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Settings for product variations";

    /**
     * Имя пакета.
     *
     * @var string
     */
    protected $vendorName = 'PortedCheese';
    protected $packageName = "ProductVariation";

    /**
     * The models that need to be exported.
     * @var array
     */
    protected $models = [
        "ProductVariation", "OrderState", "Order", "OrderItem", "OrderItemSet", "Measurement",
    ];

    /**
     * Создание контроллеров.
     *
     * @var array
     */
    protected $controllers = [
        "Admin" => [
            "ProductVariationController", "OrderStateController", "OrderController", "MeasurementController",
            "ProductVariationProductSpecificationController"
        ],
        "Site" => [
            "OrderController",
        ],
    ];

    /**
     * Создание наблюдателей
     *
     * @var array
     */
    protected $observers = [
        "ProductVariationObserver", "OrderStateObserver",
        "OrderObserver", "OrderItemObserver", "ProductObserver",
    ];

    /**
     * Папка для vue файлов.
     *
     * @var string
     */
    protected $vueFolder = "product-variation";

    /**
     * Список vue файлов.
     *
     * @var array
     */
    protected $vueIncludes = [
        'admin' => [
            "admin-variation-list" => "ProductVariationListComponent",
        ],
        'app' => [
            "order-single" => "OrderSingleProductComponent",
            "order-addon" => "OrderAddonComponent",
            "teaser-price" => "ProductTeaserPriceComponent",
        ],
    ];

    /**
     * Политики.
     *
     * @var array
     */
    protected $ruleRules = [
        [
            "title" => "Вариации",
            "slug" => "product-variations",
            "policy" => "ProductVariationPolicy",
        ],
        [
            "title" => "Статусы заказов",
            "slug" => "order-states",
            "policy" => "OrderStatePolicy",
        ],
        [
            "title" => "Заказы",
            "slug" => "orders",
            "policy" => "OrderPolicy",
        ],
        [
            "title" => "Измерения",
            "slug" => "measurements",
            "policy" => "MeasurementPolicy",
        ],
    ];

    /**
     * Стили.
     * 
     * @var array 
     */
    protected $scssIncludes = [
        "app" => [
            "product-variation/rub-format",
            "product-variation/variation-price",
            "product-variation/choose-variation",
        ],
        "admin" => [],
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $all = $this->option("all");

        if ($this->option("models") || $all) {
            $this->exportModels();
        }

        if ($this->option("controllers") || $all) {
            $this->exportControllers("Admin");
            $this->exportControllers("Site");
        }

        if ($this->option("observers") || $all) {
            $this->exportObservers();
        }

        if ($this->option("vue") || $all) {
            $this->makeVueIncludes("admin");
            $this->makeVueIncludes("app");
        }

        if ($this->option("policies") || $all) {
            $this->makeRules();
        }

        if ($this->option("scss") || $all) {
            $this->makeScssIncludes("app");
//            $this->makeScssIncludes("admin");
        }
    }
}