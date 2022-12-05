<template>
  <q-icon
      class="cursor-pointer"
      :class="{'iwt-disabled': this.disabled}"
      :name="icon"
      @click="click"
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
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },

  data () {
    return {}
  },

  methods: {
    click () {
      if (this.disabled) return

      this.$emit('click')
    }
  }
}
</script>

<style lang="sass">
.iwt-disabled
  cursor: default !important
  opacity: 0.5
</style>
