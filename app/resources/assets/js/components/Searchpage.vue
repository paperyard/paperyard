<template>
   <div>

    <div class="row">
      <div class="col-md-6" >
        <h3 class="block-header">Suche</h3>
      </div>
      <div class="col-md-6">

      </div>
      <div class="col-md-12">
        <input type="text" class="form-control" id="usr" >
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-12">


        <div class="row">
          <a href="#home_only_icon_title" data-toggle="tab" style="padding-left:0px margin-left:0px; margin:20px">
            <i class="fa  fa-th-large" style="color:#999; font-size:30px; padding-left:0px margin-left:0px"></i>
          </a>

          <a href="#profile_only_icon_title" data-toggle="tab">
            <i class="fa  fa-th" style="color:#999; font-size:30px;"></i>
          </a>

          <a href="#messages_only_icon_title" data-toggle="tab" style="margin-left:20px">
            <i class="fa  fa-th-list" style="color:#999; font-size:30px;"></i>
          </a>

          <a href="#settings_only_icon_title" data-toggle="tab" style="margin-left:20px">
            <i class="fa  fa-align-justify" style="color:#999; font-size:30px;"></i>
          </a>
        </div>
        <br>
        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane fade in active" id="home_only_icon_title">
            <div class="col-md-4" style="padding:0px; margin:0px; max-height:600px">
              <img src="img/docs/s_doc1.png" style="max-width:95%; border: 3px solid #b1d5ff; border-radius: 4px;">
            </div>
            <div class="col-md-4" style="padding:0px; margin:0px; max-height:600px">
              <img src="img/docs/s_doc2.png" style="max-width:95%; border: 3px solid #b1d5ff; border-radius: 4px;">
            </div>
            <div class="col-md-4" style="padding:0px; margin:0px; max-height:600px">
              <img src="img/docs/s_doc3.png" style="max-width:95%; border: 3px solid #b1d5ff; border-radius: 4px;">
            </div>
          </div>
          <div role="tabpanel" class="tab-pane fade" id="profile_only_icon_title">
            <div class="col-md-2" style="padding:0px; margin:0px; max-height:400px">
              <img src="img/docs/s_doc1.png" style="max-width:95%; border: 3px solid #b1d5ff; border-radius: 4px;">
            </div>
            <div class="col-md-2" style="padding:0px; margin:0px; max-height:400px">
              <img src="img/docs/s_doc1.png" style="max-width:95%; border: 3px solid #b1d5ff; border-radius: 4px;">
            </div>
            <div class="col-md-2" style="padding:0px; margin:0px; max-height:400px">
              <img src="img/docs/s_doc1.png" style="max-width:95%; border: 3px solid #b1d5ff; border-radius: 4px;">
            </div>
            <div class="col-md-2" style="padding:0px; margin:0px; max-height:400px">
              <img src="img/docs/s_doc1.png" style="max-width:95%; border: 3px solid #b1d5ff; border-radius: 4px;">
            </div>
            <div class="col-md-2" style="padding:0px; margin:0px; max-height:400px">
              <img src="img/docs/s_doc1.png" style="max-width:95%; border: 3px solid #b1d5ff; border-radius: 4px;">
            </div>
            <div class="col-md-2" style="padding:0px; margin:0px; max-height:400px">
              <img src="img/docs/s_doc1.png" style="max-width:95%; border: 3px solid #b1d5ff; border-radius: 4px;">
            </div>
          </div>
          <div role="tabpanel" class="tab-pane fade" id="messages_only_icon_title">
            <h1>WIP</h1>
          </div>
          <div role="tabpanel" class="tab-pane fade" id="settings_only_icon_title">
            <h1>WIP</h1>
          </div>
        </div>


      </div>
    </div>
  </div>
</template>

<script>
    export default {
        name: 'search',
        methods: {
          changeTitle: function(){
            this.$emit('changeTitle', '');
          }
        },
        mounted() {
            this.changeTitle();
        },
    }
</script>

