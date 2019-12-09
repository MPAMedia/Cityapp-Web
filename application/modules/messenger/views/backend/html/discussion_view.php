<?php

$lastMessage = Translate::sprint("Me").": ";

$sender = json_decode($data['sender'],JSON_OBJECT_AS_ARRAY);
$sender = $sender[Tags::RESULT];
$sender = $sender[0];

$image = "";

if(isset($sender['images'][0]['200_200']['url'])){
    $image = $sender['images'][0]['200_200']['url'];
}else{
    $image= base_url("views/skin/backend/images/profile_placeholder.png");
}

$user_id = $this->mUserBrowser->getData("id_user");

$messages = json_decode($data['messages'],JSON_OBJECT_AS_ARRAY);
$messages = $messages[Tags::RESULT];

$nbrMessageNotSeen = 0;
foreach ($messages as $value){
    if($value['status']<0 && $value['sender_id']!=$user_id){
        $nbrMessageNotSeen++;
    }

    if($value['sender_id']!=$user_id)
        $lastMessage = "";
    else
        $lastMessage = Translate::sprint("Me").": ";

}





?>
<tr <?php if($nbrMessageNotSeen>0){ echo "class='active'";} ?>>
    <td>

        <div class="image-container-50"  style="background-image: url('<?=$image?>');">
            <img class="direct-chat-img invisible" src="<?=$image?>" alt="Message User Image" >
        </div>

        <div class="discussion-content">
            <strong style="text-transform: uppercase"><?=ucfirst($sender['name'])?></strong>
            <?php

            echo '<span class="pull-right badge bg-blue">'.Translate::sprint($sender['typeAuth']).'</span>';

            ?>
            <div class="discussion-content-message" onclick="redirect('<?=  admin_url("messenger/messages/?username=".$sender['username'])?>')">
                <?php

                if(count($messages)>0){
                    echo "<p style='width: 90%'>".$lastMessage." ".Text::echo_output($messages[0]['content'])."<span class=\"paragraph-end\"></span>
                        </p>";
                }

                ?>

                <?php if($nbrMessageNotSeen>0): ?>
                    <span class="badge bg-red"><?=$nbrMessageNotSeen?></span>
                <?php endif; ?>

            </div>
            <?php if(GroupAccess::isGranted('')): ?>
                <span onclick="removeDiscussion(<?=$data['id_discussion']?>)"  data-toggle="tooltip" title="" data-original-title="<?=Translate::sprint("Delete")?>" style="float: right" href=""><i class="mdi mdi-delete"></i></span>
            <?php endif;?>




        </div>

        <?php if($this->mUserBrowser->getData("typeAuth")=="admin"): ?>
            <div class="modal fade" id="modal-default-<?=$data['id_discussion']?>">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body">

                            <div class="row">

                                <div style="text-align: center">
                                    <h3 class="text-red"><?=Translate::sprint("Are you sure?")?></h3>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" id="_delete_discussion"  class="btn btn-flat btn-primary pull-right"><?=Translate::sprint("Yes")?></button>
                            <button type="button" class="btn btn-flat btn-default pull-right" data-dismiss="modal"><?=Translate::sprint("No")?></button>
                        </div>
                    </div>

                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        <?php endif; ?>
    </td>




    <!--    <td width="30%" align="right">-->
    <!--        <a href="--><?//=  admin_url("messages?username=".$sender['username'])?><!--" ><button type="button" class="btn btn-default btn-sm"><span class="mdi mdi-close-octagon-outline"></span> --><?//=Translate::sprint("Block")?><!--</button></a>-->
    <!--        <a href="--><?//=  admin_url("messages?username=".$sender['username'])?><!--" ><button type="button" class="btn btn-default btn-sm"><span class="mdi mdi-forum"></span></button></a>-->
    <!---->
    <!--    </td>-->
</tr>



<script>

    <?php if($this->mUserBrowser->getData("typeAuth")=="admin"): ?>
    function removeDiscussion(id) {

        $("#modal-default-"+id).modal("show");

        $("#modal-default-"+id+" #_delete_discussion").on('click',function () {

            var selector = $(this);
            $.ajax({
                url:"<?=  site_url("ajax/messenger/delete_discussion")?>",
                data:{
                    id:id
                },
                dataType: 'json',
                type: 'POST',
                beforeSend: function (xhr) {

                    selector.attr("disabled",true);

                },error: function (request, status, error) {
                    console.log(request);
                    $("#modal-default-"+id).modal("hide");

                    selector.attr("disabled",false);
                },
                success: function (data, textStatus, jqXHR) {

                    selector.attr("disabled",false);
                    $("#modal-default-"+id).modal("hide");

                    if(data.success===1){

                        document.location.reload();

                    }
                }
            });


            return false;
        });

    }
    <?php endif; ?>


</script>

<script>
    function redirect(url) {
        document.location.href = url;
    }
</script>