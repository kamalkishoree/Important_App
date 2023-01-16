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
        $("#order_panel_id").val($(this).data('id'));
    });
</script>