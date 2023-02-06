<div id="add-category-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">{{__("Add Category")}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="submitCategory" action="{{ route('category.store') }}" method="POST">
                <input type="hidden" id="cat_id" name="cat_id" value="">
                @csrf
                <div class="modal-body px-3 py-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="nameInput">
                                <label for="name" class="control-label">{{__("Name")}}</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="John Doe" required>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="password" class="control-label">Status</label>
                                <select name="status" id="cat_status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>                                        
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light addAmenityForm">{{__("Submit")}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Add Product Modal --}}
<div id="add-product" class="modal fade add_product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __('Add Product') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="save_product_form" method="post" enctype="multipart/form-data" action="{{ route('product.store') }}">
                @csrf 
                <div class="modal-body pb-0">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <div class="form-group" id="product_nameInput">
                                {!! Form::label('title', __('Product Name'), ['class' => 'control-label']) !!}
                                <span class="text-danger">*</span>
                                {!! Form::text('product_name', null, ['class' => 'form-control', 'id' => 'product_name', 'onkeyup' => 'return setSkuFromName(event)', 'placeholder' => __('Product Name'), 'autocomplete' => 'off', 'required' => 'required']) !!}
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group" id="skuInput">
                                {!! Form::label('title', __('SKU'), ['class' => 'control-label']) !!}
                                <span class="text-danger">*</span>
                                {!! Form::text('sku', null, ['class' => 'form-control', 'id' => 'sku', 'onkeyup' => 'return alplaNumeric(event)', 'placeholder' =>  __('SKU'), 'required' => 'required']) !!}
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                                <span class="valid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                                {!! Form::hidden('type_id', 1) !!}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group" id="url_slugInput">
                                {!! Form::label('title', __('URL Slug'), ['class' => 'control-label']) !!}
                                {!! Form::text('url_slug', null, ['class' => 'form-control', 'id' => 'url_slug', 'placeholder' =>  __('URL Slug'), 'onkeypress' => 'return slugify(event)']) !!}
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group" id="categoryInput">
                                {!! Form::label('title', __('Category'),['class' => 'control-label']) !!}
                                <select class="form-control selectizeInput" id="category_list" name="category">
                                    @foreach($product_category as $cat)
                                        <option value="{{$cat->id}}">{{$cat->slug}}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light submitProduct">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- End Product Modal --}}