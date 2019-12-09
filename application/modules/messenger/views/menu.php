<?php

$uri_m = $this->uri->segment(2);
$uri_parent = $this->uri->segment(3);
$uri_child = $this->uri->segment(4);


?>

<?php


$newMessageCount = Modules::run("messenger/ajax/countMessagesNoSeen");
if(isset($newMessageCount[Tags::COUNT]))
    $newMessageCount = $newMessageCount[Tags::COUNT];
else
    $newMessageCount = 0;


?>

<?php if(GroupAccess::isGranted('messenger',USE_MESSENGER)): ?>
    <li class=" <?php if ($uri_m == "messenger") echo "active"; ?>">
        <a href="<?= admin_url("messenger/messages") ?>"><i class="mdi mdi-forum"></i> &nbsp;
            <span> <?= Translate::sprint("Messages") ?></span>
            <?php if ($newMessageCount > 0): ?>
                <span class="pull-right-container">
                            <small class="badge pull-right bg-yellow"><?= $newMessageCount ?></small>
                        </span>
            <?php endif; ?>
        </a>

    </li>
<?php endif; ?>


