<?php

$image = "";

if(isset($user['images'][0]['200_200']['url'])){
    $image = $user['images'][0]['200_200']['url'];
}else{
    $image= base_url("views/skin/backend/images/profile_placeholder.png");
}


?>


<div class="direct-chat-msg">
    <div class="direct-chat-info clearfix">
        <span class="direct-chat-name pull-left"><?=ucfirst($user['name'])?></span>
        <span class="direct-chat-timestamp pull-right">
            <?=MyDateUtils::convert($object['created_at'],"UTC",TIME_ZONE,"Y-m-d H:i")?>
        </span>
    </div>
    <!-- /.direct-chat-info -->

    <div class="image-container-40"  style="background-image: url('<?=$image?>');">
        <img class="direct-chat-img invisible" src="<?=$image?>" alt="Message User Image" >
    </div>

    <span>
        <div class="direct-chat-text">
            <?=Text::echo_output($object['content'])?>
        </div>
    </span>
    <!-- /.direct-chat-text -->
</div>