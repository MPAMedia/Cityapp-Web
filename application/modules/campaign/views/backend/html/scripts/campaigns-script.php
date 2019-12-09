
<script>

    $('a.linkAccess').on('click', function () {

        $('#modal-default').modal('show');
        //$('#myModal').modal('show');
        //('#myModal').modal('hide');

        var url = ($(this).attr('href'));
        $("#_apply").on('click', function () {
            //calling the ajax function
            pop(url);
            return true;
        });
    });


    function getURLParameter(url, name) {
        return (RegExp(name + '=' + '(.+?)(&|$)').exec(url) || [, null])[1];
    }

    function pop(url) {

        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            beforeSend: function (xhr) {
                $(".linkAccess").attr("disabled", true);
                $("#_apply").attr("disabled", true);
            }, error: function (request, status, error) {
                alert(request.responseText);
                $(".linkAccess").attr("disabled", false);
                $("#_apply").attr("disabled", false);
            },
            success: function (data, textStatus, jqXHR) {

                $("#_apply").attr("disabled", false);

                $(".linkAccess").attr("disabled", false);
                if (data.success === 1) {
                    document.location.reload()
                } else if (data.success === 0) {
                    var errorMsg = "";
                    for (var key in data.errors) {
                        errorMsg = errorMsg + data.errors[key] + "\n";
                    }
                    if (errorMsg !== "") {
                        alert(errorMsg);
                    }
                }
            }

        });

        return false;
    }

</script>