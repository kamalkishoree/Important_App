<div id="pay-receive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pay/Receive Money</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="row">
                <div class="login-form">
                    <ul class="list-inline">
                      <li class="d-inline-block mr-2">
                          <input type="radio" id="teacher" name="status"  value="unassigned">
                          <label for="teacher">Pending<span class="showspan">Pay</span></label>
                        </li>
                      <li class="d-inline-block mr-2">
                        <input type="radio" id="student"  name="status" value="assigned">
                        <label for="student">Active<span class="showspan">Receive</span></label>
                      </li>
                      
                    </ul>
                  </div>
            </div>
            
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="field-1" class="control-label">Select Agent</label>
                            <select name="" id="" class="form-control"></select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="field-2" class="control-label">Amount</label>
                            <input type="text" class="form-control" id="field-2"
                                placeholder="3000">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-blue waves-effect waves-light">Add</button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->