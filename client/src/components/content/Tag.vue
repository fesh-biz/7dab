<template>
  <q-item :to="'test'">
    <q-badge outline color="primary" :label="item.title" />
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
