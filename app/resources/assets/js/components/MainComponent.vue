<template>
  <div>
    <h3 class="block-header">{{ title }}</h3>
    <h1>{{ $t("auth.failed") }}</h1>
    <button v-on:click="changeLang('en')">Add 1</button>
    <button class="btn btn-primary waves-effect" data-type="success">CLICK ME</button>
    <transition name="fade" mode="out-in">
        <router-view v-on:changeTitle="updateTitle($event)"></router-view>
    </transition>
  </div>
</template>

<script>
    export default {
        name:'main-app',
         data () {
            return {
              title: 'Defeult title',
              lang: 'en'
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            updateTitle: function(updatedTitle){
                this.title = updatedTitle;
            },
            changeLang: function(){
                this.$session.destroy()
            }
        }
    }
</script>
