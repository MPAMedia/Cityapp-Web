<?php

$timezones =  DateTimeZone::listIdentifiers(DateTimeZone::ALL);
$languages =  Translate::getLangsCodes();

$url_parts = parse_url(current_url());
$host = str_replace('www.', '', $url_parts['host']);

$pstore = site_url("store");
$pstore = str_replace('www.', '', $pstore);
$pstore = str_replace('http://', '', $pstore);
$pstore = str_replace('https://', '', $pstore);
$pstore = str_replace($host, '', $pstore);


$poffer = site_url("offer");
$poffer = str_replace('www.', '', $poffer);
$poffer = str_replace('http://', '', $poffer);
$poffer = str_replace('https://', '', $poffer);
$poffer = str_replace($host, '', $poffer);


$pevent = site_url("event");
$pevent = str_replace('www.', '', $pevent);
$pevent = str_replace('http://', '', $pevent);
$pevent = str_replace('https://', '', $pevent);
$pevent = str_replace($host, '', $pevent);



?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content">

        <div class="row">
            <!-- Message Error -->
            <div class="col-sm-12">
                <?php $this->load->view("backend/include/messages");?>
            </div>

        </div>

        <div class="row">

            <div class="col-sm-6">
                <div class="callout callout-info">
                    <h4><i class="mdi mdi-link" aria-hidden="true"></i>&nbsp;&nbsp;
                        <?=Translate::sprint("What is the deep linking?","")?>!</h4>
                    <p>
                        <?=Translate::sprint("DeepLinkingIntro")?>
                    </p>
                </div>
            </div>

            <div class="col-sm-6">

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b> <?php echo Translate::sprint("Deep Linking Configuration"); ?> </b></h3>
                    </div>


                    <div class="box-body">

                            <form id="form" role="form">

                                <div class="form-group">
                                    <label><?=Translate::sprint("Host Name")?></label>
                                    <input type="text" class="form-control" placeholder="value" disabled value="<?=$host?>">
                                </div>

                                <div class="form-group">
                                    <label><?=Translate::sprint("Path Prefix for store")?></label>
                                    <input type="text" class="form-control" placeholder="value" disabled value="<?=$pstore?>">
                                </div>

                                <div class="form-group">
                                    <label><?=Translate::sprint("Path Prefix for offer")?></label>
                                    <input type="text" class="form-control" placeholder="value" disabled value="<?=$poffer?>">
                                </div>


                                <div class="form-group">
                                    <label><?=Translate::sprint("Path Prefix for event")?></label>
                                    <input type="text" class="form-control" placeholder="value" disabled value="<?=$pevent?>">
                                </div>


                            </form>

                    </div>

                </div>



            </div>



    </section>



</div>




