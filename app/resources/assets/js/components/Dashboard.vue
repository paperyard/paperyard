<template>
  <div>
    <div class="row">
             <div class="col-md-6 text-center col-md-offset-3">
                <input type="text" class="form-control" id="usr" style="text-align:center">
            </div>
    </div>
    <hr>
    <div class="row">

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="card dashboard_card">
                <div class="body ">
                    <ul class="list-group" >
                        <li class="list-group-item" style="padding-top:20px;"><strong>zu bearbeiten</strong><span class="badge bg-light-blue">8</span></li>
                        <li class="list-group-item" style="padding-top:20px;"><strong>diese Woche gesammt</strong><span class="badge bg-light-blue">19</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="card dashboard_card">
                <div class="body">

                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="card dashboard_card hidden-xs hidde-sm">
              <div class="container-fluid" style="padding-top:10px">
                  <div class="pull-left" style="padding-left:0px">
                    <input type="text" class="knob" data-linecap="round" value="60" data-width="130" data-height="130" data-thickness="0.25" data-angleoffset="-180"
                           data-fgColor="#017cff" data-bgColor="#b1d5ff" >
                  </div>
                   <!-- <div class="pull-right" style="border:1px solid red">
                        <div class="row">
                             <label class="pull-right">zuletzt geöffnet</label>
                        </div>
                        <div class="row">
                             <label class="pull-right">zuletzt geöffnet</label>
                        </div>
                   </div> -->
              </div>
            </div>
        </div>
       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="card dashboard_card">
               <div style="padding:17px">
                <div class="col-xs-5 col-sm-3 " style="padding-left:0px;">
                    <div style="width:200px height:100%; border-right:3px solid #b1d5ff;">
                      <img src="img/docs/doc1.png" class="img-responsive" style="height:120px; border:1px solid #b1d5ff; ">
                    </div>
                </div>
                <div class="col-xs-7 col-sm-9 hidden-xs hidden-sm hidden-md">
                       <div class="row" >
                         <label class="pull-right">zuletzt geöffnet</label>
                        </div>
                       <div class="row pull-right" style="margin-top:4px">
                          <div class="col-xs-2 col-sm-1" style="border: 1px solid #b1d5ff; padding:0px; width:60px; margin:3px">
                             <img src="img/docs/doc1.png" class="img-responsive" style="height:85px;">
                          </div>
                           <div class="col-xs-2 col-sm-1" style="border: 1px solid #b1d5ff; padding:0px; width:60px; margin:3px">
                             <img src="img/docs/doc2.png" class="img-responsive" style="height:85px;">
                          </div>
                           <div class="col-xs-2 col-sm-1" style="border: 1px solid #b1d5ff; padding:0px; width:60px; margin:3px">
                             <img src="img/docs/doc2.png" class="img-responsive" style="height:85px; ">
                          </div>
                           <div class="col-xs-2 col-sm-1" style="border: 1px solid #b1d5ff; padding:0px; width:60px; margin:3px">
                             <img src="img/docs/doc4.png" class="img-responsive" style="height:85px; ">
                          </div>
                           <div class="col-xs-2 col-sm-1" style="border: 1px solid #b1d5ff; padding:0px; width:60px; margin:3px">
                             <img src="img/docs/doc5.png" class="img-responsive" style="height:85px; ">
                          </div>
                           <div class="col-xs-2 col-sm-1" style="border: 1px solid #b1d5ff; padding:0px; width:60px; margin:3px">
                             <img src="img/docs/doc1.png" class="img-responsive" style="height:85px; ">
                          </div>
                       </div>
                </div>
                <!-- mobile  -->
                <div class="col-xs-7 col-sm-9 visible-xs visible-sm">
                       <div class="row" >
                         <label class="pull-right">zuletzt geöffnet</label>
                        </div>
                       <div class="row pull-right" style="margin-top:24px">

                          <div class="col-xs-2 col-sm-1" style="border: 1px solid #b1d5ff; padding:0px; width:40px; margin:3px">
                             <img src="img/docs/doc1.png" class="img-responsive" style="height:60px;">
                          </div>
                           <div class="col-xs-2 col-sm-1" style="border: 1px solid #b1d5ff; padding:0px; width:40px; margin:3px">
                             <img src="img/docs/doc2.png" class="img-responsive" style="height:60px;">
                          </div>
                           <div class="col-xs-2 col-sm-1" style="border: 1px solid #b1d5ff; padding:0px; width:40px; margin:3px">
                             <img src="img/docs/doc2.png" class="img-responsive" style="height:60px; ">
                          </div>
                       </div>
                </div>
               </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="card dashboard_card">
                <div class="body">

                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="card dashboard_card">
                <div class="body">

                </div>
            </div>
        </div>

    </div>
  </div>
</template>

<script>
    export default {
        name: 'dashboard',
        data () {
            return {
            }
        },
        methods: {
          changeTitle: function(){
            this.$emit('changeTitle', 'Dashboard');
          }
        },
        mounted() {
            this.changeTitle();
        },
    }
</script>

<style>
    .dashboard_card {
       height:150px
    }
</style>