<div id="add-order-panel-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                
                @if (Route::currentRouteName() == 'inventory-panel-db')
                <h4 class="modal-title">{{__("Add Inventory Panel DB")}}</h4>
                @else
                <h4 class="modal-title">{{__("Add Order Panel DB")}}</h4>
                @endif

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="add-order-panel-db" action="{{ route('order-panel-db.store') }}" method="POST">
                @csrf
                <input type="hidden" name="order_panel_id" value="" id="order_panel_id">
                <div class="modal-body px-3 py-0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="nameInput">
                                <label for="name" class="control-label">{{__("Name")}}</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="John Doe" required>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="urlInput">
                                <label for="url" class="control-label">{{__("Url")}}</label>
                                <input type="url" class="form-control" name="url" id="url" placeholder="Enter Url" required>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="codeInput">
                                <label for="code" class="control-label">{{__("Code")}}</label>
                                <input type="text" class="form-control" name="code" id="code" placeholder="Enter Code" required>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="keyInput">
                                <label for="key" class="control-label">{{__("Key")}}</label>
                                <input type="text" class="form-control" name="key" id="key" placeholder="Enter Key" required>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="control-label">Status</label>
                                <select name="status" id="statusss" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>                                        
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group selected-type">
                                <label for="panel_type" class="control-label">Panel Type</label>
                                <select name="type" id="panel_type" class="form-control" >
                                    <option value="0" >Order Panel</option>
                                    <option value="1">Inventory Panel</option>
                                </select>                                        
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light addorderPanelForm">{{__("Submit")}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

