<template>
  <q-chip
    :color="bgColor"
    size="1rem"
    style="padding-left: 25px"
  >
    <q-avatar>
      <q-icon
        :color="iconColor"
        :name="icon"
        size="1.5rem"
      >
        <q-tooltip
          v-if="tooltip"
          anchor="top middle"
          self="bottom middle"
          :offset="[10, 10]"
          transition-show="scale"
          transition-hide="scale"
        >
          <span style="font-size: 0.8rem">{{ tooltip }}</span>
        </q-tooltip>
      </q-icon>
    </q-avatar>
    <span style="font-size: 0.9rem">{{ label }}</span>
    <slot></slot>
  </q-chip>
</template>

<script>
export default {
  name: 'ChipInfo',

  props: {
    icon: {
      type: String,
      required: true
    },
    label: {
      type: [String, Number],
      required: true
    },
    tooltip: {
      type: String,
      default: null
    },
    bgColor: {
      type: String,
      default: 'light-blue-1'
    },
    iconColor: {
      type: String,
      default: 'grey-7'
    }
  },

  data () {
    return {
    }
  }
}
</script>
