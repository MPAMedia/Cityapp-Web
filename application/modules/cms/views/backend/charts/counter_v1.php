<div class="row">

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>
    <?php foreach ($chart_v1_home as $key => $chart_module): ?>
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-orange-active" style="background-color: <?= (isset($chart_module['color']) ?  $chart_module['color'] : '#ff7701') ?> !important;"><?= (isset($chart_module['icon_tag']) ?  $chart_module['icon_tag']: "<i class='mdi mdi-google-analytics'></i>") ?></span>
                <div class="info-box-content">
                                <span class="info-box-text" data-toggle="tooltip"
                                      title="<?= (isset($chart_module['count_label']) ?  $chart_module['count_label']: Translate::sprint($key)) ?>">
                                    <?= (isset($chart_module['count_label']) ?  Translate::sprint($chart_module['count_label']): Translate::sprint($key)) ?>
                                </span>
                    <span class="info-box-number"><?= $chart_module["count"] ?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
    <?php endforeach; ?>

</div>