<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content" id="#messages-module">
        <div class="row">
            <!-- Message Error -->
            <div class="col-sm-12">
                <?php $this->load->view("backend/include/messages");?>
            </div>

        </div>


        <div class="row">
            <div class="col-md-7">

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b><?=Translate::sprint("Inbox")?></b></h3>

                        <div class="pull-right">
                            <a href="#" id="reload-inbox"><i class="mdi mdi-refresh"></i> </a>
                        </div>
                    </div>

                    <div class="box-body discussions-box">

                        <table id="list-discussions" class="table table-bordered table-hover">
                            <tbody id="discussion-list">

                            </tbody>
                        </table>


                    </div>

                    <div id="pagination" class="box-footer clearfix">

                    </div>

                    <div class="overlay inbox-loading">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>


            <?php

            if(Text::checkUsernameValidate($this->input->get("username"))){
                $this->load->view("backend/html/messenger");
            }else{
                $this->load->view("backend/html/empty_messeneger");
            }


            ?>
        </div>

    </section>



</div>


<script>


    var currentPage  = <?=intval($this->input->get("page"))?>;
    loadDiscussion(<?=intval($this->input->get("page"))?>,true);

    function loadDiscussion(page,refreshing) {

        currentPage = page;

        var dataSet = {
            "page" : page
            <?php
                if(Text::checkUsernameValidate($this->input->get("username"))){
                    echo ',"username":"'.urlencode($this->input->get("username")).'"';
                }
            ?>
        };
        $.ajax({
            url:"<?=  site_url("ajax/messenger/loadInbox")?>",
            data:dataSet,
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {

                if(refreshing==true)
                    $(".inbox-loading").removeClass("hidden");

            },error: function (request, status, error) {

                console.log(request);

                $(".inbox-loading").addClass("hidden");

            },
            success: function (data, textStatus, jqXHR) {

                console.log(data);

                $(".inbox-loading").addClass("hidden");

                if(data.success===1){
                    if(page==1 || page==0)
                        $("#discussion-list").html("");

                    $("#discussion-list").append(data.result.discussions_view);
                    $("#pagination").html(data.result.pagination_view);

                    doPagin();
                }

            }
        });


    }

    setInterval(function () {

    },1500);

    $("#reload-inbox").on('click',function () {
        loadDiscussion(1,true);
        return false;
    });

    function doPagin() {
        $("#messages-module #pagination a[href]").on('click',function () {
            return false;
        });
    }

</script>

