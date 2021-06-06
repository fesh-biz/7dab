<template>
  <q-item :to="'test'" class="t-wrapper">
    <q-tooltip
      anchor="top middle"
      self="bottom middle"
      :offset="[10, 10]"
      transition-show="scale"
      transition-hide="scale"
      max-width="300px"
    >
      <span style="font-size: 1rem">{{ item.body }}</span>
    </q-tooltip>

    {{ item.title }}
  </q-item>
</template>

<script>
export default {
  name: 'Tag',

  props: {
    item: {
      type: Object,
      required: true
    }
  }
}
</script>

<style lang="sass">
.t-wrapper
  background-color: #eee
  padding: 0 10px
  margin: 0 2px 5px 2px
  border-radius: 11px
  min-height: unset
</style>
