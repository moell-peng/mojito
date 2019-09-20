<template>
  <div>
    <h1>Hello! {{config.admin.appName.fullName}}</h1>

  </div>
</template>

<script>
import config from '@/config'
  export default {
    name: 'adminDashboard',
    data() {
      return {
        config
      }
    }
  }
</script>