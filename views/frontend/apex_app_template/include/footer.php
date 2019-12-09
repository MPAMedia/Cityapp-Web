

<!-- Start footer -->
<footer id="mu-footer" role="contentinfo">
    <div class="container">
        <div class="mu-footer-area">
            <p class="mu-copy-right">&copy; Copyright <a rel="nofollow" href="http://droideve.com">droideve.com</a>. All right reserved. Template Designed  by <a  target="_blank" rel="nofollow" href="http://markups.io">markups.io</a></p>
        </div>
    </div>

</footer>
<!-- End footer -->



<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- Bootstrap -->
<script src="<?=base_url("views/skin/frontend/".FRONTEND_TEMPLATE_NAME)?>/assets/js/bootstrap.min.js"></script>
<!-- Slick slider -->
<script type="text/javascript" src="<?=base_url("views/skin/frontend/".FRONTEND_TEMPLATE_NAME)?>/assets/js/slick.min.js"></script>
<!-- Ajax contact form  -->
<script type="text/javascript" src="<?=base_url("views/skin/frontend/".FRONTEND_TEMPLATE_NAME)?>/assets/js/app.js"></script>



<!-- Custom js -->
<script type="text/javascript" src="<?=base_url("views/skin/frontend/".FRONTEND_TEMPLATE_NAME)?>/assets/js/custom.js"></script>

<script>

    <?php
            $link = $this->session->userdata("redirect_to");
            if($link!=""){
                echo "redirect('".$link."')";
                $this->session->set_userdata(array(
                   "redirect_to" => ""
                ));
            }

    ?>


    function redirect(url) {
        document.location.href=url;
        //window.open(url, '_target');
        setTimeout(function () {

        },2000);
    }

</script>



</body>
</html>