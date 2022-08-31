<template>
  <q-icon
      class="cursor-pointer"
      :name="icon"
      @click="$emit('click')"
      :color="color"
      :size="size"
  >
    <q-tooltip
        transition-show="scale"
        transition-hide="scale"
        self="bottom middle"
        anchor="top middle"
    >
      {{ tooltip }}
    </q-tooltip>
  </q-icon>
</template>

<script>
export default {
  name: 'IconWithToolTip',

  props: {
    icon: {
      type: String,
      required: true
    },

    tooltip: {
      type: String,
      required: true
    },

    color: {
      type: String,
      default: ''
    },

    size: {
      type: String,
      default: 'md'
    }
  },

  data () {
    return {}
  }
}
</script>
