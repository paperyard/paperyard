<template>
  <div>
    <center>
      <div style="margin-top:50px">
        <div><i class="fa fa-folder-open" style="color:#b1d5ff; font-size:100px"></i></div><br>
        <div><p style="color:#999; font-size:22px">
          Es sind keine Ordner vorhanden.<br>
Du kannst aber gerne welche erstellen.

        </p></div><br>
        <div>
          <button class="btn-flat btn_color main_color waves-effect lg-btn_x2" type="submit"><span class="lg-btn-tx">Benachrichtigung anlegen</span></button>
        </div>
      </div>
    </center>
  </div>
</template>

<script>
    export default {
        name: 'notifications',
        data () {
            return {
            }
        },
        methods: {
          changeTitle: function(){
            this.$emit('changeTitle', 'Ordner');
          }
        },
        mounted() {
            this.changeTitle();
        },
    }
</script>


<style>
    .lg-btn-tx {
       font-size:18px;
       color:#017cff;
       font-weight:bold
    }
    .lg-btn_x2 {
      width:270px;
      height:35px;
      border:none;
      border-radius:5px
    }
    .btn_color{
       background-color:#b1d5ff
    }
</style>