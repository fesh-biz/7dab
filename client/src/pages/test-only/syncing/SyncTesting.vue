<template>
  <div style="border: 1px solid gray; margin-bottom: 20px">
    <p>Sync Testing variable: {{ model }}</p>
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
    variable: {
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
        return this.variable
      },
      set (val) {
        this.$emit('update:variable', val)
      }
    }
  }
}
</script>
