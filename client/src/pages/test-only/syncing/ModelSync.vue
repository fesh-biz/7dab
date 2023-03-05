<template>
  <div style="border: 1px solid gray">
    <p>Model Sync: {{ model }}</p>
    <q-input
      dense
      outlined
      v-model="model"
    />
  </div>
</template>

<script>
export default {
  name: 'SyncTesting',

  props: {
    value: {
      type: String,
      required: true
    }
  },

  data () {
    return {
      model: this.value
    }
  },

  watch: {
    model (newValue) {
      this.$emit('input', newValue)
    }
  }
}
</script>
