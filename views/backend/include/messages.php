<?php if($this->mUserBrowser->isShadowing()): ?>
    <div class="callout callout-success">
        <h4><i class="glyphicon glyphicon-eye-open" aria-hidden="true"></i>&nbsp;&nbsp;
            <?=Translate::sprint("You are using shadowing mode","")?>! "Now you are connected as Owner in demo version"</h4>
        <p>
            <a href="<?=admin_url("user/close_shadowing")?>">=><?=Translate::sprint("End shadow session and return to your session.")?></a>
        </p>
    </div>
<?php endif;?>

<?php

    $userIsValid = FALSE;

    if($this->mUserBrowser->getData("confirmed")==1){
        $userIsValid = TRUE;
    }

?>


<?php if(installFolderFound()): ?>
<div class="callout callout-danger">
    <h4><?php echo Translate::sprint("The installation folder represents a danger","The installation folder represents a danger"); ?> </h4>
    <p><?php echo Translate::sprint("Please remove install directory",""); ?> </p>
</div>
    <br>
<?php endif;?>

<?php if(!$userIsValid and EMAIL_VERIFICATION): ?>
<div class="callout callout-warning">
    <h4><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;&nbsp;<?=Translate::sprint("Account without verification","")?>!</h4>
    <p><?=Translate::sprint("We've sent mail verification to your mailbox","")?>. </p>
</div>
<?php endif;?>





<?php if(defined(_APP_VERSION) && _APP_VERSION!=APP_VERSION): ?>
    <div class="callout callout-warning">
        <h4><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Update!</h4>
        <h5>The update for <?=APP_VERSION?> is ready</h5>
        <a href="<?=base_url("update?id=".CRYPTO_KEY)?>">Run the update</a>
    </div>
<?php endif;?>



<?php if(defined("DASHBOARD_UPDATED")): ?>
    <div class="callout callout-success">
            <h4><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Congratulation!</h4>
            <h5>Your dashboard has been updated to <?=APP_VERSION?></h5>
            <a href="<?=admin_url("")?>">Home</a> / <a href="<?=admin_url("application")?>">Dashboard Config</a>
    </div>
<?php endif;?>







