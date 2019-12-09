<?php if (GroupAccess::isGranted('store', DELETE_STORE)): ?>
    <script>

        $('a.linkAccess').on('click', function () {
            var url = ($(this).attr('href'));
            var cat = getURLParameter(url, 'store');


            //calling the ajax function
            pop(cat);
        });


        function getURLParameter(url, name) {
            return (RegExp(name + '=' + '(.+?)(&|$)').exec(url) || [, null])[1];
        }

        function pop(cat) {

            $.ajax({
                type: 'post',
                url: "<?=  site_url("ajax/store/status")?>",
                dataType: 'json',
                data: {'id': cat, 'type': 'store'},
                beforeSend: function (xhr) {
                    $(".linkAccess").attr("disabled", true);
                }, error: function (request, status, error) {
                    alert(request.responseText);
                    $(".linkAccess").attr("disabled", false);
                    console.log(request);
                },
                success: function (data, textStatus, jqXHR) {

                    $(".linkAccess").attr("disabled", false);
                    if (data.success === 1) {
                        document.location.reload();
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


        $("div #_delete").on('click', function () {

            var selector = $(this);

            var id = $(this).attr("data");

            $.ajax({
                url: "<?=  site_url("ajax/store/delete")?>",
                data: {"id": id, "type": "store"},
                dataType: 'json',
                type: 'POST',
                beforeSend: function (xhr) {
                    selector.attr("disabled", true);
                }, error: function (request, status, error) {
                    alert(request.responseText);
                    selector.attr("disabled", false);

                    console.log(request);
                },
                success: function (data, textStatus, jqXHR) {

                    selector.attr("disabled", false);
                    if (data.success === 1) {
                        document.location.reload();
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
        });


    </script>
<?php endif; ?>