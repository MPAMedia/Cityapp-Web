<div id="welcome" class="step">
    <?php
        if(INIT_PLATFORM=="ns-android"){
            $message = "(Android ".APP_VERSION.")";
        }else if(INIT_PLATFORM=="ns-ios"){
            $message = "(iOS ".APP_VERSION.")";
        }else
            $message = "";

    ?>

    <h1 class="title">NearbyStores <?=$message?> Installation</h1>
    <p>

    </p>

</div>