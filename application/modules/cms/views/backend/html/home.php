
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- Message Error -->
            <div class="col-sm-12">
                <?php $this->load->view("backend/include/messages"); ?>
            </div>

        </div>


        <?php

        try {
            CMS_Display::render('overview_counter');
            CMS_Display::render('overview_chart_months');
        } catch (Exception $e) {
            die($e->getTraceAsString());
        }


        ?>





            <?php

            try {
                CMS_Display::render('home_v1');
            } catch (Exception $e) {
                die($e->getTraceAsString());
            }


            ?>





    </section><!-- /.content -->
</div><!-- /.content-wrapper -->