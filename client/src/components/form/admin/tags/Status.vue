<template>
  <q-select
    dence
    outlined
    v-model="model"
    :options="options"
    @input="onChange"
  />
</template>

<script>
import _ from 'lodash'

export default {
  name: 'Status',

  props: {
    tag: {
      type: Object,
      required: true
    }
  },

  data () {
    return {
      model: null,
      options: [
        { label: 'Pending', value: 'pending' },
        { label: 'Approved', value: 'approved' },
        { label: 'Rejected', value: 'rejected' }
      ]
    }
  },

  created () {
    this.model = _.find(this.options, { value: this.tag.status })
  },

  methods: {
    onChange () {
      this.$emit('update', Object.assign(this.tag, { status: this.model.value }))
    }
  }
}
</script>
