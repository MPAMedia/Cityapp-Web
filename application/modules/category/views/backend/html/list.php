<?php

$users = $data['cats'];

?>
<div class="col-sm-6">
    <div class="box  box-solid">
        <div class="box-header">
            <div class="box-title"><b><?= Translate::sprint("Categories") ?></b></div>
        </div>
        <!-- /.box-header -->
        <div class="box-body  table-bordered ">
            <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th width="10%"><?= Translate::sprint("Image") ?></th>
                        <th><?= Translate::sprint("Category") ?></th>
                        <th><?= Translate::sprint("الفئة") ?></th>
                        <th><?= Translate::sprint("Stores") ?></th>

                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php if (!empty($users)) { ?>

                        <?php foreach ($users AS $user) { ?>
                            <tr>

                                <td align="right">
                                    <?php

                                    if (isset($user["image"])) {
                                        $images = _openDir($user["image"]);
                                        if (isset($images['200_200']['url'])) {
                                            echo '<img style="    height: 45px;width: 45px;    border: 1px solid #eeeeee;
                                                    padding: 2px;" src="' . $images['200_200']['url'] . '"/>';
                                        }

                                    }
                                    ?>
                                </td>
                                <td>
                                    <span
                                        style="font-size: 12px"><?= Translate::sprint(Text::echo_output($user["name"])) ?></span>
                                </td> 
                                   <td>
                                    <span
                                        style="font-size: 12px"><?= Translate::sprint(Text::echo_output($user["name_ar"])) ?></span>
                                </td>
                                <td>
                                    <span style="font-size: 12px"><?= htmlspecialchars($user["nbrStore"]) ?></span>
                                </td>


                                <td align="right">
                                    <a href="<?= site_url("ajax/category/deleteCat?id=" . $user["id_category"]) ?>"
                                       class="linkAccess" onclick="return false;">
                                        <button type="button" title="Delete" class="btn btn-sm"><span
                                                class="glyphicon glyphicon-trash"></span></button>
                                    </a>
                                    <a href="<?= admin_url("category/edit?id=" . $user["id_category"]) ?>">
                                        <button type="button" title="Update" class="btn btn-sm"><span
                                                class="glyphicon glyphicon-edit"></span></button>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4">
                                <div style="text-align: center"> <?= Translate::sprint("No data found", "") ?> !!</div>
                            </td>
                        </tr>

                    <?php } ?>
                    </tbody>
                    <!-- <tfoot>
                     <tr>
                       <th>Rendering engine</th>
                       <th>Browser</th>
                       <th>Platform(s)</th>
                       <th>Engine version</th>
                       <th>CSS grade</th>
                     </tr>
                     </tfoot>-->
                </table>
            </div>

            <div class="row">
                <div class="col-sm-7">
                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">

                    </div>

                </div>

            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->


    <!-- /.box -->
</div>
<!-- /.col -->
<script>

    $('a.linkAccess').on('click',function(){
        var url = ($(this).attr('href'));
        var cat = getURLParameter(url, 'id');
        //calling the ajax function
        pop(cat);
    });

    function getURLParameter(url, name) {
        return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
    }

    function pop(cat) {

        $.ajax({
            type:'post',
            url:"<?=  site_url("ajax/category/delete")?>",
            dataType: 'json',
            data:{'id':cat},
            beforeSend: function (xhr) {
                $(".linkAccess").attr("disabled",true);
            },error: function (request, status, error) {
                alert(request.responseText);
                $(".linkAccess").attr("disabled",false);
                console.log(request);
            },
            success: function (data, textStatus, jqXHR) {

                $(".linkAccess").attr("disabled",false);
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