<?php

$uri_m = $this->uri->segment(2);
$uri_parent = $this->uri->segment(3);
$uri_child = $this->uri->segment(4);


?>
<li class="<?php if ($uri_parent == "application") echo "active"; ?>">
    <a href="<?= admin_url("setting/application") ?>"><i class="mdi  mdi-settings"></i>
        &nbsp;<span> <?= Translate::sprint("config_sidebar", "Config") ?></span></a>
</li>


<li class="<?php if ($uri_parent == "currencies") echo "active"; ?>">
    <a href="<?= admin_url("setting/currencies") ?>"><i class="mdi mdi-currency-eur"></i>
        &nbsp;<span> <?= Translate::sprint("Currencies") ?></span></a>
</li>
<li class="<?php if ($uri_parent == "deeplinking") echo "active"; ?>">
    <a href="<?= admin_url("setting/deeplinking") ?>"><i class="mdi mdi-link"></i>
        &nbsp;<span> <?= Translate::sprint("Deep Linking") ?></span></a>
</li>