<template>
  <div class="container">
    <ul class="list-inline center-block">
      <li>
        <p>{{ $t("auth.select_lang_tx") }} </p>
      </li>
      <li>
        <label class="lang_tx" v-on:click="changeLanguage('ge')">German</label>
      </li>
      <li>
        <label class="lang_tx" v-on:click="changeLanguage('en')">English</label>
      </li>
    </ul>
  </div>
</template>

<script>
    export default {
        name: 'localization-component',
        data () {
            return {
              america: 'img/language/america_flag.png',
            }
        },
        methods: {
          changeLanguage: function(lang){
            //session language
            var self = this
            self.$session.set('v_lang', lang)
            //get current url
            var l_url = String(window.location);
            //check if current url has local ex: /en /ge
            var check_if_has_local = l_url.charAt(l_url.length-3)

            //if path is in root redirect to login/lang(en,ge)
            if(location.pathname == "/"){
              window.location.replace(l_url+"login/"+lang);
            }
            //if url has localize, remove current localize and add new
            else if(check_if_has_local=="/"){
              window.location.replace(l_url.slice(0,-2)+lang);
            }
            //url has no local initialize, add local
            else{
              window.location.replace(l_url+"/"+lang);
            }
          },//end changeLanguage
          resetImage: function (){
              this.america = 'img/language/america_flag.png'
          },
        },//end method
        mounted() {
           this.resetImage();
        },

    }
</script>

<style>
   .lang_tx {
      color:#017cff;
   }
</style>