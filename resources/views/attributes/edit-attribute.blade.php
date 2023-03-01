<div class="row">
    <div class="col-md-12">
        <div class="row">
            
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', __('Type'),['class' => 'control-label']) !!}
                    <select class="form-control selectize-select dropDownTypeAttr"  name="type" dataFor="add">
                        @if(isset($for) && ($for ==2)) 
                         <option value="5">{{ __("Checkbox") }}</option>
                        @else
                            <option value="1">{{ __("DropDown") }}</option>
                            <option value="2">{{ __("Color") }}</option>
                            <option value="3">{{ __("Radio") }}</option>
                            <option value="4">{{ __("Textbox") }}</option>
                            <option value="5">{{ __("Checkbox") }}</option>
                            <option value="6">{{ __("Image") }}</option>
                            <option value="7">{{ __("Date") }}</option>
                        @endif
                        
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>
      <input type="hidden" name="attribute_id" value="{{  $attribute->id }}">
        <div class="row rowYK ">
            <div class="col-md-12">
                <h5>{{ __('Attribute') ." Title" }}</h5>
            </div>
            <div class="col-md-12" style="overflow-x: auto;">

                <table class="table table-borderless table-responsive al_table_responsive_data" id="banner-datatable" >
                   
                    <tr>
                                <td >
                                    {!! Form::hidden('language_id[]', 1) !!}
                                    {!! Form::text('title[]', $attribute->title, ['class' => 'form-control']) !!}
                                
                                </td>
                </table>
            </div>
        </div>

        <div class="row rowYK">
            <div class="col-md-12">
                <h5>{{ __('Attribute') ." Options" }}</h5>
            </div>
            <div class="col-md-12" style="overflow-x: auto;">
                <table class="table table-borderless table-responsive al_table_responsive_data optionTableEditAttribute" id="banner-datatable">
                    <tr class="trForClone">
                        <th class="hexacodeClass-add" style="display:none;">{{ __("Color Code") }}</th>
                        
                        <th></th>
                        <th></th>
                    </tr>
                    @foreach ($attribute->option as $key=>$option)
                    <tr>
                        <input type="hidden" name="option_ids[]" value="{{ $option->id }}" >
                        <td style="min-width: 200px; display:none;" class="hexacodeClass-add col-md-6">
                            <input type="text" name="hexacode[]" class="form-control hexa-colorpicker" value="cccccc" id="add-hexa-colorpicker-1">
                        </td>
                      
                        <td>
                           
                            <input type="hidden" name="opt_id[0][]" value="{{ $option->id }}" >
                            <input type="text" name="opt_color[0][]" class="form-control attr-text-box" value="{{ $option->title }}" required>
                               
                          
                        </td>
                       
                        <td class="lasttd">
                            @if($key!=0)
                            <a href="javascript:void(0);" class="action-icon deleteCurRow"> <i class="mdi mdi-delete"></i></a>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                </table>
            </div>
            <div class="col-md-12">
                <button type="button" class="btn btn-info waves-effect waves-light addOptionRow-attribute-edit">{{ __("Add Option") }}</button>
            </div>
        </div>
    </div>
</div>
