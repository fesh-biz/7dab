<template>
  <div>
    <q-tooltip
      anchor="top middle"
      self="bottom middle"
      :offset="[10, 10]"
      transition-show="scale"
      transition-hide="scale"
    >
      {{ tooltipLabel }}
    </q-tooltip>
    <q-icon :name="icon" class="q-mr-xs" />{{ amount }}
  </div>
</template>

<script>
export default {
  name: 'PostInfoIcon',

  props: {
    amount: {
      type: Number,
      required: true
    },
    tooltipLabel: {
      type: String,
      required: true
    },
    icon: {
      type: String,
      required: true
    }
  },

  data () {
    return {}
  }
}
</script>
