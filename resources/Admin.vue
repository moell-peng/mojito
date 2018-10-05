<template>
  <div id="app">
    <router-view/>
  </div>
</template>

<script>
  import { mapActions } from 'vuex'
  import { routeFormatTag } from './libs/util'
  import { getToken } from './libs/auth'
  import { setHttpToken } from './libs/http'

  export default {
    name: 'App',
    methods: {
      ...mapActions([
        'openTagView'
      ])
    },
    watch: {
      $route(route) {
        this.$store.commit('SET_BREADCRUMB', route.matched)
        if (route.name !== 'adminLogin') {
          this.openTagView(routeFormatTag(route))
        }
      }
    },
    mounted () {
      this.$store.commit('SET_BREADCRUMB', this.$route.matched)
    }
  }
</script>

<style>
  html,body{width:100%;height:100%;}
  body {
    margin:0;
  }
  #app {
    height:100%;
  }
</style>