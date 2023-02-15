<?php
namespace App\Traits;
use GuzzleHttp\Client as GCLIENT;
use App\Model\{ClientPreference,Agent,Order,Category,AgentProductPrices,ProductVariant};
use Log;
use App\Traits\{ApiResponser};

trait CategoryTrait{
    use ApiResponser;

    public function getCategoryWithProductByType($type_id='8',$request = ''){
        try {
            $category = Category::with(['translation','products' => function($q){
                $q->where('is_live',1);
            },
            'products.translation','products.variant']);
        
            if( ($request) && $request->has('agent_id') && ($request->agent_id)){
                $category = $category->with(['products.variant.agentPrice' => function($q) use ($request){
                                        if($request->has('agent_id') && ($request->agent_id)){
                                            $q->where('agent_id',$request->agent_id);
                                        }
                                    }]);
                                    //->whereHas('products.variant.agentPrice', function($q) use ($request){
                                    //     $q->where('agent_id',$request->agent_id);
                                    // })
            }else{
                $category->with(['products.variant.agentPrice']);
            }
            $category =  $category->whereHas('products.variant')->where('type_id',$type_id)->get();
            return $category ;
        }catch (Exception $e) {
            \Log::info('getFreeLincerFromDispatcher error');
            \Log::info($e->getMessage());
            return [];
        }
       
    }

    public function saveProduct($request){
        try {
            if($request->has('product_prices') && !empty($request->product_prices)){
                foreach($request->product_prices as $key => $product){
                  $product  = (object)$product;
                    //pr($product);
                    $checkVariant  =   ProductVariant::where(['product_id'=>$product->product_id,'id'=>$product->variant_id])->first();
                    if(  $checkVariant){
                        $AgentProductPrices = AgentProductPrices::updateOrCreate(
                            ['product_id'=>$product->product_id,'agent_id'=>$request->agent_id,'product_variant_id'=>$product->variant_id],
                            ['product_id'=>$product->product_id,'agent_id'=>$request->agent_id,'product_variant_id'=>$product->variant_id,'price'=>$product->price,'product_variant_sku'=>$checkVariant->sku ],
                        
                        );
                    }
                }
                return 1;
            }
            return 0;
        } catch (\Exception $e) {
            Log::info('CategoryTrait error');
            Log::info($e->getMessage());
            return 0;
        }
    }
  

    
}
