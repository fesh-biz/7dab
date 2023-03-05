<template>
  <div style="border: 1px solid gray; margin-bottom: 20px">
    <p>Sync Testing variable: {{ model }}</p>
    <q-input
      dense
      outlined
      v-model="model"
      @input="(val) => {$emit('update:variable', val)}"
    />
  </div>
</template>

<script>
export default {
  name: 'SyncTesting',

  props: {
    variable: {
      type: String,
      required: true
    }
  },

  data () {
    return {
      model: this.variable
    }
  }
}
</script>
