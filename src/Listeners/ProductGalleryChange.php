<?php

namespace PortedCheese\ProductVariation\Listeners;

use App\Product;
use PortedCheese\BaseSettings\Events\ImageUpdate;

class ProductGalleryChange
{
    public function handle(ImageUpdate $event)
    {
        $morph = $event->morph;
        if ($event->action === "deleting"){
            if (! empty($morph) && get_class($morph) == Product::class) {
                foreach ($morph->variations()->get() as $variation ){
                    if ($variation->product_image_id === $event->image->id){
                        $variation->clearImage();
//                        $variation->product_image_id = null;
//                        $variation->save();
                    }
                }
            }
        }
    }
}