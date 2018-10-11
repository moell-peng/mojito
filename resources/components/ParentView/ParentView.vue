<template>
  <keep-alive :include="cacheTags">
    <router-view/>
  </keep-alive>
</template>
<script>
  export default {
    name: 'ParentView',
    computed: {
      cacheTags() {
        return this.$store.getters.cacheTags
      }
    }
  }
</script>