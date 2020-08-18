# Product Variation

## Description

Какое-то описание

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