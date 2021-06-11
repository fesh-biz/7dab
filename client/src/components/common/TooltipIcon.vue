<template>
  <div>
    <q-tooltip
      anchor="top middle"
      self="bottom middle"
      :offset="[10, 10]"
      transition-show="scale"
      transition-hide="scale"
    >
      {{ tooltip }}
    </q-tooltip>
    <q-icon @click="$emit('click')" :size="iconSize" :name="iconName" class="q-mr-xs" />
  </div>
</template>

<script>
export default {
  name: 'TooltipIcon',

  props: {
    tooltip: {
      type: String,
      required: true
    },
    iconName: {
      type: String,
      required: true
    },
    iconSize: {
      type: String,
      default: 'sm'
    }
  },

  data () {
    return {}
  }
}
</script>
