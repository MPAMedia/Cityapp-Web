<?php

$uri_m = $this->uri->segment(2);
$uri_parent = $this->uri->segment(3);
$uri_child = $this->uri->segment(4);


?>

<?php if (GroupAccess::isGranted('user',USER_ADMIN)) : ?>
    <li class="<?php if ($uri_m == "category") echo "active"; ?>">
        <a href="<?= admin_url("category/categories") ?>">
            <i class="mdi mdi-format-list-bulleted"></i> &nbsp;<span>
                            <?= Translate::sprint("Categories") ?></span>
        </a>
    </li>
<?php endif; ?>
