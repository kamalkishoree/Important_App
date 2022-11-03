<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\{Product, ProductCategory, ProductVariant, ProductTranslation};
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rule = array(
            'sku' => 'required|unique:products',
            'url_slug' => 'required',
            'category' => 'required',
            'product_name' => 'required',
        );
        $validation  = Validator::make($request->all(), $rule);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $product = new Product();
        $product->sku = $request->sku;
        $product->url_slug = empty($request->url_slug) ? $request->sku : $request->url_slug;
        $product->title = empty($request->product_name) ? $request->sku : $request->product_name;
        $product->type_id = $request->type_id;
        $product->category_id = $request->category;
        $product->vendor_id = $request->vendor_id;
        $product->save();
        if ($product->id > 0) {
            $datatrans[] = [
                'title' => $request->product_name??null,
                'body_html' => '',
                'meta_title' => '',
                'meta_keyword' => '',
                'meta_description' => '',
                'product_id' => $product->id,
                'language_id' => 1
            ];
            $product_category = new ProductCategory();
            $product_category->product_id = $product->id;
            $product_category->category_id = $request->category;
            $product_category->save();
            $proVariant = new ProductVariant();
            $proVariant->sku = $request->sku;
            $proVariant->product_id = $product->id;
            $proVariant->product_id = $product->id;
            $proVariant->barcode = $this->generateBarcodeNumber();
            $proVariant->save();
            ProductTranslation::insert($datatrans);
            return redirect()->back()->with('success', __('Product added successfully!') );
            // return redirect('client/masterproduct/' . $product->id . '/edit')->with('success', __('Product added successfully!') );
        }
    }

    private function generateBarcodeNumber()
    {
        $random_string = substr(md5(microtime()), 0, 14);
        while (ProductVariant::where('barcode', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 14);
        }
        return $random_string;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
