<?php
$this->load->view("backend/header-no-auth");
$this->load->library('user_agent');

?>
<!-- Content Wrapper. Contains page content -->


    <div class="my-custom-container">
        <div class="row payment">
            <div class="col-sm-12">
                <div class="alert alert-success">
                    <h4><i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;&nbsp;<?=Translate::sprint("Activated successfully")?></h4>
                    <p><?=Translate::sprint("Your account has been activated successfully, you can now use your account by taking advantage of all available access.")?></p>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-6">
                        <div class="pay-btn">

                            <?php

                            $platform = $this->agent->platform();
                            $platform = strtolower($platform);

                            ?>

                            <?php if($this->agent->is_mobile() and $platform=="ios"): ?>
                            <a href="<?=custom_protocol_url('user/login','nsapp')?>"  class="btn btn-primary btn-block ">
                                <?=Translate::sprint("Open the app")?>
                            </a>
                            <?php else: ?>
                                <a href="<?=site_url('user/login')?>"  class="btn btn-primary btn-block ">
                                    <?=Translate::sprint("Login")?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                <div class="col-sm-6">
                    <div class="pay-btn">
                        <a href="<?=site_url()?>" class="btn btn-default btn-block">
                            <?=Translate::sprint("Close")?>
                        </a>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>


<script>


</script>