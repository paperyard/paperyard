<template>
  <div>

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
            this.$emit('changeTitle', 'Einstellungen');
          }
        },
        mounted() {
            this.changeTitle();
        },
    }
</script>

<style>

</style>