<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        Version <?php if (defined("_APP_VERSION")) echo _APP_VERSION; else echo APP_VERSION; ?>
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; <?= date("Y") ?> <a style="text-transform: uppercase"
                                                 href="<?= site_url("") ?>"><?= APP_NAME ?></a>.</strong> <?= Translate::sprint("All rights reserved.") ?>
    <!--          Template Designed by <a target="_blank" href="https://adminlte.io">Almsaeed Studio</a>-->
</footer>


</div><!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.1.4 -->
<script src="<?= base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js") ?>"></script>
<!-- Bootstrap 3.3.5 -->
<script src="<?= base_url("views/skin/backend/bootstrap/js/bootstrap.min.js") ?>"></script>
<!-- AdminLTE App -->
<script src="<?= base_url("views/skin/backend/dist/js/app.min.js") ?>"></script>

<script src="<?= base_url("views/skin/backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") ?>"></script>
<script>
    //Colorpicker
    $("#editable-textarea").wysihtml5({
        "image": false,
        "link": false,
    });
</script>


<?php
echo TemplateManager::loadScripts();
?>

</body>
</html>