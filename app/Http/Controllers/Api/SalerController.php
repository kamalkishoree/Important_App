<?php

namespace App\Http\Controllers\Api;

use Validator,Log;
use App\User;
use Carbon\Carbon;
use App\Model\{Client,GeneralSlot,AgentProductPrices};
use Config,Validation,DB;
use Illuminate\Http\Request;
use App\Traits\{ApiResponser,CategoryTrait};
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;


class SalerController extends BaseController
{
    use ApiResponser,CategoryTrait;
    
    /**
     * CategoryWithProduct
     *
     * @param  mixed $request
     * @return void
     */
    public function CategoryWithProduct(Request $request)
    {
        try {
            $response = $this->getCategoryWithProductByType('8');
            return $this->success($response, __('Success'), 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
    public function CategoryWithProductWithPrice(Request $request)
    {
        try {
            $agent = Auth::user();
            $request->merge(['agent_id'=> $agent->id]);
            $response = $this->getCategoryWithProductByType('8',$request);
            return $this->success($response, __('Success'), 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
    public function saveProductVariantPrice(Request $request)
    {  
         try {
            if(Auth::check()){
                $request->merge(['agent_id'=>Auth::id()]);
            }
          
      
            $validator = Validator::make($request->all(), [
                'agent_id' => 'required|exists:agents,id',
                'product_prices.*.product_id' => 'required|exists:products,id',
                'product_prices.*.variant_id' => 'required|exists:product_variants,id',
            ]);

            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), 422);
            }
        
            $response = $this->saveProduct($request);
            return $this->success($response, __('Success'), 200);
         } catch (\Exception $e) {
            Log::info('SalerController error line 52');
            Log::info($e->getMessage());
            return $this->error($e->getMessage(), $e->getCode());
         }
    }

    public function getGerenalSlot(Request $request)
    {
         try {
           $GerenalSlot = GeneralSlot::where('status',1)->get();
            return $this->success($GerenalSlot, __('Success'), 200);
         } catch (\Exception $e) {
            Log::info('SalerController error line 52');
            Log::info($e->getMessage());
            return $this->error($e->getMessage(), $e->getCode());
         }
    }

    public function saveSloat(Request $request)
    {
         try {
           $GerenalSlot = GeneralSlot::where('status',1)->get();
            return $this->success($GerenalSlot, __('Success'), 200);
         } catch (\Exception $e) {
            Log::info('SalerController error line 52');
            Log::info($e->getMessage());
            return $this->error($e->getMessage(), $e->getCode());
         }
    }

    public function getProductSkeParticulerDB(Request $request)
    {
        try {
            $agent = Auth::user();
         
          $productSku =   AgentProductPrices::selectRaw("*, REPLACE(product_variant_sku, '".$request->db."', '') AS sku")->where('agent_id',  $agent->id)->where('product_variant_sku', 'LIKE', '%' . $request->db.'%' )->pluck('sku')->toArray();
         // pr(implode(',' ,$productSku ));
          $sku['sku'] = implode(',' ,$productSku );
            return $this->success($sku, __('Success'), 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
