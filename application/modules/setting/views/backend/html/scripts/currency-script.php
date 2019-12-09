<script src="<?=  base_url("views/skin/backend/plugins/select2/select2.full.min.js")?>"></script>

<script>

    $("#addCurrency").on('click',function(){

        var symbol_currency = $("#form #symbol_currency").val();
        var name_currency = $("#form #name_currency").val();
        var code_currency = $("#form #code_currency").val();
        var format_currency = $("#form #CURRENCY_FORMAT").val();
        var rate_currency = $("#form #rate_currency").val();

        var dataSet = {
            "symbol_currency":symbol_currency,
            "name_currency":name_currency,
            "code_currency":code_currency,
            "format_currency":format_currency,
            "rate_currency" :rate_currency
        };

        $.ajax({
            url:"<?=  site_url("ajax/setting/addNewCurrency")?>",
            data:dataSet,
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                $("#form #addCurrency").attr("disabled",true);

            },error: function (request, status, error) {
                alert(request.responseText);
                $("#form #addCurrency").attr("disabled",false);

                console.log(request.responseText);
            },
            success: function (data, textStatus, jqXHR) {

                $("#form #addCurrency").attr("disabled",false);
                if(data.success===1){
                    document.location.reload();
                }else  if(data.success===0){
                    var errorMsg = "";
                    for(var key in data.errors){
                        errorMsg = errorMsg+data.errors[key]+"\n";
                    }
                    if(errorMsg!==""){
                        alert(errorMsg);
                    }
                }
            }
        });

        return false;

    });


</script>

<script>

    $('.DEFAULT_CURRENCY').select2();
    $('.CURRENCY_FORMAT').select2();


    $(".content #btnSave").on('click',function () {

        var selector = $(this);

        var dataSet = {
            "DEFAULT_CURRENCY":$("#DEFAULT_CURRENCY").val(),
            "token":""
        };

        $.ajax({
            url:"<?=  site_url("ajax/setting/saveAppConfig")?>",
            data:dataSet,
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                selector.attr("disabled",true);
            },error: function (request, status, error) {
                alert(request.responseText);
                selector.attr("disabled",false);
            },
            success: function (data, textStatus, jqXHR) {

                console.log(data);

                selector.attr("disabled",false);
                if(data.success===1){
                    document.location.reload();
                }else if(data.success===0){
                    var errorMsg = "";
                    for(var key in data.errors){
                        errorMsg = errorMsg+data.errors[key]+"\n";
                    }
                    if(errorMsg!==""){
                        alert(errorMsg);
                    }
                }
            }
        });


        return false;
    });


</script>

<script>

    $("table #deleteCurrency").on('click',function () {

        var code = $(this).attr("data");
        $("#modal-confirm").modal("show");

        $("#_ok").on('click',function () {

            pop(code,$(this),$("#modal-confirm"));

            return false;
        });

        return false;
    });



    function pop(id,selector,modal) {

        $.ajax({
            type:'post',
            url:"<?=  site_url("ajax/setting/deleteCurrency")?>",
            dataType: 'json',
            data:{'code':id},
            beforeSend: function (xhr) {
                selector.attr("disabled",false);
            },error: function (request, status, error) {
                alert(request.responseText);
                selector.attr("disabled",false);
                modal.modal("hide");
            },
            success: function (data, textStatus, jqXHR) {

                selector.attr("disabled",false);
                modal.modal("hide");
                if(data.success===1){
                    document.location.reload();
                }else if(data.success===0){
                    var errorMsg = "";
                    for(var key in data.errors){
                        errorMsg = errorMsg+data.errors[key]+"\n";
                    }
                    if(errorMsg!==""){
                        alert(errorMsg);
                    }
                }
            }

        });

        return false;
    }

</script>
<script src="<?=  base_url("views/skin/backend/plugins/datatables/jquery.dataTables.min.js")?>"></script>
<script>
    $('#example2').DataTable({
        "language": {
            "url": "<?=  base_url("views/skin/backend/plugins/datatables/langs/".DEFAULT_LANG.".lang")?>"
        }
    });
</script>
