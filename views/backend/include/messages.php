<?php if($this->mUserBrowser->isShadowing()): ?>
    <div class="callout callout-success">
        <h4><i class="glyphicon glyphicon-eye-open" aria-hidden="true"></i>&nbsp;&nbsp;
            <?=Translate::sprint("You are using shadowing mode","")?>! "Now you are connected as <?=$this->mUserBrowser->getData('username')?>"</h4>
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


<?php

if(ModulesChecker::isEnabled("pack")){

    $this->load->model("pack/pack_model");
    $pack_id = $this->mUserBrowser->getData('pack_id');
    $typeAuth = $this->mUserBrowser->getData('typeAuth');

    if($pack_id>0 and $typeAuth!="admin") {

        $pack = $this->pack_model->getPack($pack_id);
        $expired_date = $this->mUserBrowser->getData('will_expired');
        $days = MyDateUtils::getDays($expired_date);

        if ($days <= 0 and $pack!=NULL) {

            ?>

            <div class="callout callout-warning">
                <h4><?= Translate::sprint("Your pack \"" . $pack->name . "\" has been expired!") ?>!</h4>
                <p>
                    <?php if ($pack->price > 0): ?>
                        <a href="<?= admin_url("pack/renew") ?>">=><?= Translate::sprint("Renew your account") ?></a>
                        <br>
                    <?php endif; ?>
                    <?php if ($this->mPack->canUpgrade()): ?>
                        <a href="<?= site_url("pack/pickpack?req=upgrade") ?>">=><?= Translate::sprint("Upgrade it") ?></a>
                    <?php endif; ?>
                </p>
            </div>

            <?php

        }


    }
}


?>


<?php if(_APP_VERSION!=APP_VERSION): ?>
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







