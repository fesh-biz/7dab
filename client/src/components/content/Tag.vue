<template>
  <q-item :to="'test'" class="t-wrapper">
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
  padding: 0 5px
  font-size: 0.7rem
  margin: 0 2px 5px 2px
  border-radius: 11px
  min-height: unset
</style>
