    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>

<script type="text/javascript">
    var autocomplete = {};
    var autocompletesWraps = ['address'];
    $(document).ready(function(){
        loadMap(autocompletesWraps);
    });
    $('.openModal').click(function(){
        $('#add-order-panel-modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $('.editIconBtn').click(function(){
        $('#add-order-panel-modal').modal({
            backdrop: 'static',
            keyboard: false
        });
     
        $("#name").val($(this).data('name'));
        $("#url").val($(this).data('url'));
        $("#code").val($(this).data('code'));
        $("#key").val($(this).data('key'));
        $('.selected-type option[value='+$(this).data('type')+']').attr('selected','selected');

        $("#order_panel_id").val($(this).data('id'));
    });
    
        <?php if(session('hashKey')) {?>
              $('#hashKeyModal').modal('show');
        <?php }?>
        
   
function copyHashKey() {
    /* Get the text field */
    var hashKeyInput = document.getElementById("hashKeyInput");

    /* Select the text field */
    hashKeyInput.select();
    hashKeyInput.setSelectionRange(0, 99999); /* For mobile devices */

    /* Copy the text inside the text field */
    document.execCommand("copy");

    /* Alert the copied text */
    alert("Hash key copied to clipboard!");
}

$('#route-btn').click(function(){

   var id = $(this).attr('data-id');
      var url = "{{ route('create-product-route', ['id' => ':id']) }}".replace(':id', id);
	 $('#create-route').attr('href',url);
});
</script>

