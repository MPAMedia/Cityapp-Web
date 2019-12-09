<!-- page script -->
<script>

    $("#btnEdit").on('click',function(){

        var cat = $("#addCat").val();

        $.ajax({
            url:"<?=site_url("ajax/category/editCategory")?>",
            data:{
                'cat':cat,
                'id':"<?=$category['id_category']?>",
                "image":<?=$uploader_variable?>
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                $("#btnEdit").attr("disabled",true);

            },error: function (request, status, error) {
                alert(request.responseText);
                $("#btnEdit").attr("disabled",false);
            },
            success: function (data, textStatus, jqXHR) {

                $("#btnEdit").attr("disabled",false);
                if(data.success===1){
                    /*alert(data.message);*/
                    document.location.href="<?=admin_url("category/categories")?>";
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