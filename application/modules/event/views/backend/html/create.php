

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

         <form id="form" role="form">
            <div class="col-md-6">
                <div class="box box-solid">
                    <div class="box-header with-border">
                      <h3 class="box-title"><b><?=Translate::sprint("Create Event","")?></b></h3>
                    </div>
            <!-- /.box-header -->
            <div class="box-body">

                <!-- text input -->
                
                <div class="form-group required">
                    <label for="name"><?=Translate::sprint("Images","")?>:  </label>
                    
                    <label class="msg-error-form image-data"></label>
                    <input type="file" name="addimage" id="fileupload"><br>
                    <div class="clear"></div>
                    <div id="progress" class="hidden">
                        <div class="percent" style="width: 0%"></div>
                    </div>
                    <div class="clear"></div>
                    <div id="image-previews">
                    </div>

                </div>

                    <div class="form-group">
                        <label><?=Translate::sprint("Store","")?></label>
                        <select class="form-control select2 selectStore" style="width: 100%;">
                            <option selected="selected" value="0"><?=Translate::sprint("Select store","")?></option>
                            <?php

                            if(isset($myStores[Tags::RESULT])){
                                foreach ($myStores[Tags::RESULT] as $st){
                                    echo '<option adr="'.$st['address'].'" 
                                    lat="'.$st['latitude'].'" lng="'.$st['longitude'].'" 
                                    value="'.$st['id_store'].'">'.$st['name'].'</option>';
                                }
                            }

                            ?>
                        </select>
                    </div>


                    <div class="form-group">
                  <label><?=Translate::sprint("Event name","")?>  <sup>*</sup> </label>
                  <input type="text" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="name" id="name">
                 
                </div>

                    <div class="form-group">
                  <label><?=Translate::sprint("Event AR name","")?>  <sup>*</sup> </label>
                  <input type="text" class="form-control" placeholder="<?=Translate::sprint("Enter AR Name")?> ..." name="name_ar" id="name_ar">
                 
                </div>




                <!-- textarea -->
                <div class="form-group">
                  <label><?=Translate::sprint("Description","")?> :</label>
                  <textarea id="editable-textarea" class="form-control" style="height: 300px"></textarea>
                </div> 
                 <!-- textarea -->
                <div class="form-group">
                  <label><?=Translate::sprint("Description AR","")?> :</label>
                  <textarea id="editable-textarea_ar" class="form-control" style="height: 300px"></textarea>
                </div>
                
                 <div class="form-group">
                     <div class="row">
                         <div class="col-md-6"><label><?=Translate::sprint("Date Begin")?>  <sup>*</sup> </label>  <input  class="form-control" data-provide="datepicker" value="<?=date("Y-m-d",time())?>" placeholder="YYYY-MM-DD" type="text" name="date_b" id="date_b"/> </div>
                         <div class="col-md-6">  <label><?=Translate::sprint("Date End")?>  <sup>*</sup> </label>  <input class="form-control"   data-provide="datepicker"  type="text" placeholder="YYYY-MM-DD" name="date_e" id="date_e"/></div>
                     </div>
                  </div>
                
                 
                
                 <div class="form-group">
                  <label><?=Translate::sprint("Phone Number","")?>  </label>
                  <input type="text" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="tel" id="tel">
                </div>

                
                 <div class="form-group">
                  <label><?=Translate::sprint("WebSite","")?>   </label>
                  <input type="text" class="form-control" title="format valid : http(s)://www.example.com" placeholder="<?=Translate::sprint("Enter")?> ..." name="web" id="web">
                </div>

            </div>
            <!-- /.box-body -->
          </div>

     
        </div>
            <div class="col-md-6">

              <div class="box box-solid">
                  <div class="box-header with-border">
                      <h3 class="box-title"><b><?=Translate::sprint("Drag the marker to get the exact position","")?>  :</b></h3>
                  </div>

                  <div class="box-body">

                      <div class="form-group">
                          <label> <?=Translate::sprint("Search","")?> :</label>
                          <input type="text" class="form-control" placeholder="<?=Translate::sprint("Search")?> ..." name="places" id="places">
                      </div>
                      <div id="somecomponent" style="width:100%;height:500px;margin-bottom: 15px"></div>
                      <div class="form-group">
                          <label><?=Translate::sprint("Address","")?> :</label>
                          <input  type="text" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." value="" name="address" id="address">
                      </div>

                      <div class="form-group">
                          <div class="row">
                              <div class="col-md-6">  <label><?=Translate::sprint("Lat","")?>  <sup>*</sup></label>  <input  class="form-control" type="text" name="lat" id="lat"/> </div>
                              <div class="col-md-6">  <label><?=Translate::sprint("Lng","")?> <sup>*</sup> </label>  <input class="form-control"  type="text" name="long" id="lng"/></div>
                          </div>
                      </div>
                      <div class="form-group">
                          <button type="button" class="btn  btn-primary" id="btnCreate" > <span class="glyphicon glyphicon-check"></span><?=Translate::sprint("Create","")?>  </button>
                          <button type="button" class="btn btnc  btn-default" > <span class="glyphicon glyphicon-remove"></span><?=Translate::sprint("Clear","")?>  </button>
                      </div>


                  </div>

              </div>

          </div>
         </form>
 </section>
    
</div>
     
     
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="<?=  base_url("views/skin/backend/plugins/uploader/js/jquery.iframe-transport.js")?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/uploader/js/jquery.ui.widget.js")?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/uploader/js/jquery.fileupload.js")?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/datepicker/bootstrap-datepicker.js")?>"></script>


 <script>
      
   
      
      
      $.fn.datepicker.defaults.format = "yyyy-mm-dd";
      $('.datepicker').datepicker({
         startDate: '-3d'
      });
       <?php
    
            $token = $this->mUserBrowser->setToken("SUPIMAGES-4555");
    
        ?>
    
    Uploader(true);


   var fileUploaded = {};
      function Uploader(singleFile){

          $('#fileupload').fileupload({
              url: "<?=site_url("uploader/ajax/uploadImage")?>",
              sequentialUploads: true,
              loadImageFileTypes:/^image\/(gif|jpeg|png|jpg)$/,
              loadImageMaxFileSize: 10000,
              singleFileUploads: singleFile,

              formData     : {
                  'token'     : "<?=$token?>",
                  'ID'        : "<?=sha1($token)?>"
              },
              dataType: 'json',
              done: function (e, data) {


                  var results = data._response.result.results;
                  $("#progress").addClass("hidden");
                  $("#progress .percent").animate({"width":"0%"});
                  $(".image-uploaded").removeClass("hidden");

                  if(singleFile==true){
                      fileUploaded = {};
                      $("#image-previews").html(results.html);
                  }else
                      $("#image-previews").append(results.html);

                  fileUploaded[results.image] = results.image;
                  //$("#image-data").val(results.image_data);

                  $(".image-uploaded #delete").on('click',function(){
                      var nameDir = $(this).attr("data");
                      delete fileUploaded[nameDir];
                      $(".image-uploaded.item_"+nameDir).remove();
                      return false;
                  });

              },
              fail:function (e, data) {

                  $("#progress").addClass("hidden");
                  $("#progress .percent").animate({"width":"0%"});


              },
              progressall: function (e, data) {

                  var progress = parseInt(data.loaded / data.total * 100, 10);

                  $("#progress").removeClass("hidden");
                  $("#progress .percent").animate({"width":progress+"%"},"linear");

              },
              progress: function (e, data) {

                  var progress = parseInt(data.loaded / data.total * 100, 10);

              },
              start: function (e) {

                  $("#fileupload").removeClass("input-error");
                  $(".image-data").text("");

              }
          });



      }


 </script>
  
  
     <script src="<?=  base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js")?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?=  base_url("views/skin/backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js")?>"></script>


    <script>

        var store_id = 0;
        $("#compose-textarea").wysihtml5();

    
   $("#btnCreate").on('click',function(){

                 var name = $("#form #name").val();
                 var name_ar = $("#form #name_ar").val();
                 var address = $("#form #address").val();
                 var desc =  $("#editable-textarea").val();
                 var desc_ar =  $("#editable-textarea_ar").val();
                 var tel = $("#form #tel").val();
                 var website = $("#form #web").val();
                 var lat = $("#form #lat").val();
                 var lng = $("#form #lng").val();

                 var date_b = $("#form #date_b").val();
                 var date_e = $("#form #date_e").val(); 
                    $.ajax({
                        url:"<?=  site_url("ajax/event/create")?>",
                        data:{"store_id":store_id,"name":name,"name_ar":name_ar,"address":address,"desc":desc,"desc_ar":desc_ar,"tel":tel,"website":website,"lat":lat,"lng":lng,"date_b":date_b,"date_e":date_e,
                            "images":JSON.stringify(fileUploaded)},
                        dataType: 'json',
                        type: 'POST',
                        beforeSend: function (xhr) {

                            $("#btnCreate").attr("disabled",true);
                            
                        },error: function (request, status, error) {
                            alert(request.responseText);
                            $("#btnCreate").attr("disabled",false);
                            console.log(request)
                        },
                        success: function (data, textStatus, jqXHR) {

                            console.log(data);

                            $("#btnCreate").attr("disabled",false);


                            if(data.success===1){
                               document.location.href = "<?=admin_url("event/events")?>";
                            }else if(data.success===0){
                                var errorMsg = "";
                                for(var key in data.errors){
                                    errorMsg = errorMsg+data.errors[key]+"\n";
                                }
                                if(errorMsg!==""){
                                    alert(errorMsg);
                                }
                            }
                        }
                    });
                    
                    return false;

});
 
 
   
      
    </script>
  
    <script src="<?=  base_url("views/skin/backend/plugins/locationpicker/locationpicker.jquery.min.js")?>"></script>
<!--    <script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places'></script>
    <script type="text/javascript" src='https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=AIzaSyAlGtU4pi1JQentEWQocVA7ynWtdNLfyd0'></script>-->
    <script type="text/javascript" src='https://maps.googleapis.com/maps/api/js?key=<?=MAPS_API_KEY?>&libraries=places'></script>
<script>



    $('#somecomponent').locationpicker({
        location: {latitude: <?=MAP_DEFAULT_LATITUDE?>, longitude:<?=MAP_DEFAULT_LONGITUDE?>},
        radius: 300,
        inputBinding: {
            latitudeInput: $('#lat'),
            longitudeInput: $('#lng'),
            radiusInput: $('#radius'),
            locationNameInput: $('#places')
        },
        enableAutocomplete: true
    });


$("#btnAdd").on('click',function(){

                 var cat = $("#addCat").val();
                    $.ajax({
                        url:"<?=  site_url("ajax/addGategory")?>",
                        data:{'cat':cat},
                        dataType: 'json',
                        type: 'POST',
                        beforeSend: function (xhr) {
                            $("#btnAdd").attr("disabled",true);
                            
                        },error: function (request, status, error) {
                            alert(request.responseText);
                            $("#btnAdd").attr("disabled",false);
                        },
                        success: function (data, textStatus, jqXHR) {

                            console.log(data);

                            $("#btnAdd").attr("disabled",false);
                            if(data.success===1){
                              alert(data.message);
                               location.reload();
                            }else if(data.success===0){
                                var errorMsg = "";
                                for(var key in data.errors){
                                    errorMsg = errorMsg+data.errors[key]+"\n";
                                }
                                if(errorMsg!==""){
                                    alert(errorMsg);
                                }
                            }
                        }
                    });
                    
                    return false;

});

      //Initialize Select2 Elements

</script>


<!-- Select2 -->

<script src="<?=  base_url("views/skin/backend/plugins/select2/select2.full.min.js")?>"></script>
<script>


    $('.selectStore').select2();
    $('.selectStore').on('select2:select', function (e) {
        // Do something
        var data = e.params.data;
        var id = data.id;
        store_id = id;
        if(id>0){

            var adr = $(".selectStore option[value="+id+"]").attr("adr");
            var lat = $(".selectStore option[value="+id+"]").attr("lat");
            var lng = $(".selectStore option[value="+id+"]").attr("lng");

            $("#address").val(adr);
            $("#lat").val(lat);
            $("#lng").val(lng);

        }

    });
</script>





