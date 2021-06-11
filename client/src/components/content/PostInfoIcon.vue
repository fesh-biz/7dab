<template>
  <div class="flex">
    <tooltip-icon icon-size="xs" :icon-name="icon" :tooltip="tooltipLabel"/> {{ amount }}
  </div>
</template>

<script>
import TooltipIcon from 'components/common/TooltipIcon'

export default {
  name: 'PostInfoIcon',

  components: {
    TooltipIcon
  },

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
