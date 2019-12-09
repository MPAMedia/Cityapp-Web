<script src="<?= base_url("views/skin/backend/plugins/select2/select2.full.min.js") ?>"></script>
<!-- page script -->
<script>

    $('a.linkAccess').on('click', function () {

        //$('#myModal').modal('show');
        //('#myModal').modal('hide');

        var url = ($(this).attr('href'));
        $('#modal-default').modal('show');

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
            }, error: function (request, status, error) {
                alert(request.responseText);
                $(".linkAccess").attr("disabled", false);
                $('#switcher').modal('hide');
                $('#modal-default').modal('hide');
            },
            success: function (data, textStatus, jqXHR) {

                $('#switcher').modal('hide');
                $('#modal-default').modal('hide');

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
<script>


    $('#select_owner').select2({

        ajax: {
            url: "<?=site_url("ajax/user/getOwners")?>",
            dataType: "json",
            data: function (params) {

                var query = {
                    q: params.term,
                };

                // Query parameters will be ?search=[term]&type=public
                return query;
            },
            processResults: function (data) {
                // Tranforms the top-level key of the response object from 'items' to 'results'
                console.log(data);
                return {
                    results: data
                };
            },
            results: function (data, page) {
                console.log(data);

                return {results: data};
            }
        }
    });


    $("div .deleteUser").on('click', function () {

        var user_id = parseInt($(this).attr("data"));

        $('#switcher').modal('show');

        $("#apply").on('click', function () {

            var switch_to = parseInt($("#select_owner").val());

            applyDelete(user_id, switch_to);

            return false;
        });

        return false;
    });


    function applyDelete(user_id, switch_to) {

        $.ajax({
            type: 'post',
            url: "<?=site_url("ajax/user/delete")?>",
            data: {
                "id": user_id,
                "switch_to": switch_to,
            },
            type: "POST",
            dataType: 'json',
            beforeSend: function (xhr) {
                $("#apply").attr("disabled", true);
            }, error: function (request, status, error) {

                $("#apply").attr("disabled", false);
                $('#switcher').modal('hide');
                $('#modal-default').modal('hide');

                console.log(request);
            },
            success: function (data, textStatus, jqXHR) {

                console.log(data);

                $('#switcher').modal('hide');
                $('#modal-default').modal('hide');

                $("#apply").attr("disabled", false);

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

    }


</script>