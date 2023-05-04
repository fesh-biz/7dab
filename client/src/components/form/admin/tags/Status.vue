<template>
  <q-select
    dence
    outlined
    v-model="model"
    :options="statuses"
    @input="onChange"
  />
</template>

<script>
import _ from 'lodash'

const statuses = [
  { label: 'Pending', value: 'pending' },
  { label: 'Approved', value: 'approved' },
  { label: 'Rejected', value: 'rejected' }
]

export default {
  name: 'Status',

  props: {
    item: {
      type: Object,
      required: true
    },
    statuses: {
      type: Array,
      default: () => statuses
    }
  },

  data () {
    return {
      model: null
    }
  },

  created () {
    this.model = _.find(this.statuses, { value: this.item.status })
  },

  methods: {
    onChange () {
      this.$emit('update', Object.assign(this.item, { status: this.model.value }))
    }
  }
}
</script>
