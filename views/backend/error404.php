<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-yellow"> 404</h2>

            <div class="error-content">
                <h3><i class="fa fa-warning text-yellow"></i><?=Translate::sprint("Oops! Page not found","")?> .</h3>
                <p>
                    <?=Translate::sprint("We could not find the page you were looking for","")?> .
                    <?=Translate::sprint("Meanwhile, you may","")?>  <a href="<?=admin_url()?>">
                    <?=Translate::sprint("return to the home page","")?>  </a>
                    <?=Translate::sprint("or try using the search form","")?> .
                </p>
            </div>

            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->
    </section>
    <!-- /.content -->
</div>