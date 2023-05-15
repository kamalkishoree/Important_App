<div class="col-6">
    <div class="">
        <div class="driver-rating-quiestions-list">
            <div class="">
                <div class="card-box h-100">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="page-title">{{ ("Driver Review Questions") }}</h4>
                                <button class="btn btn-info waves-effect waves-light text-sm-right add_driver_rating_quiestionbtn" dataid="0">{{ __('Add') }}
                                </button>
                            </div>
                            <p class="sub-header">
                                {{ __("Reviwes Questions") }}
                            </p>
                        </div>
                    </div>
                    <div class="row variant-row">
                        <div class="col-md-12">
                            
                            <div class="table-responsive outer-box">
                                <table class="table table-centered table-nowrap table-striped" id="varient-datatable">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Options') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    
                                        @foreach($driverRatingQuestion as $key => $variant)

                                            @if(!empty($variant->translation_one))
                                                <tr class="variantList" data-row-id="{{$variant->id}}">
                                                    <td>
                                                        <a class="editAttributeBtn" dataid="{{$variant->id}}" href="javascript:void(0);">{{$variant->title}}</a>
                                                    </td>
                                                   
                                                    <td>
                                                        @foreach($variant->option as $key => $value)
                                                        <label style="margin-bottom: 3px;">
                                                            @if(isset($variant) && !empty($variant->type) && $variant->type == 2)
                                                            <span style="padding:8px; float: left; border: 1px dotted #ccc; background:{{$value->hexacode}};"> </span>
                                                            @endif
                                                            &nbsp;&nbsp; {{$value->title}}</label> <br />
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <a class="action-icon editDriverratingQBtn" data-id="{{$variant->id}}" href="javascript:void(0);">
                                                            <i class="mdi mdi-square-edit-outline"></i>
                                                        </a>
                                                        @if( auth()->user()->is_superadmin )
                                                        <a class="action-icon deleteAttributebtn" data-id="{{$variant->id}}" href="javascript:void(0);">
                                                            <i class="mdi mdi-delete"></i>
                                                        </a>
                                                        <form action="{{route('attribute.delete', $variant->id)}}" method="POST" style="display: none;" id="attrDeleteForm{{$variant->id}}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="action-icon btn btn-primary-outline" dataid="{{$variant->id}}" onclick="return confirm('Are you sure? You want to delete the attribute.')"> <i class="mdi mdi-delete"></i></button>
                                                        </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                      @endforeach
                                   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>