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
    }
  },

  computed: {
    model: {
      get () {
        return this.value
      },
      set (val) {
        this.$emit('input', val)
      }
    }
  }
}
</script>
