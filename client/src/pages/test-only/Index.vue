<template>
  <div class="row flex justify-center">
    <div class="col-8">
      <p>Index: {{ model }}</p>
      <sync-testing :variable.sync="model"/>
      <model-sync v-model="model"/>
    </div>
  </div>
</template>

<script>
import ModelSync from 'pages/test-only/syncing/ModelSync'
import SyncTesting from 'pages/test-only/syncing/SyncTesting'

export default {
  name: 'Index',

  components: {
    SyncTesting,
    ModelSync
  },

  data () {
    return {
      model: 'Start val'
    }
  },

  watch: {
    model (val) {
      console.log('model', val)
    }
  }
}
</script>
