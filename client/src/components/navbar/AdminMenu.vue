<template>
  <div>
    <q-separator spaced/>

    <q-list dense>
      <q-item :to="{name: 'admin.posts'}">
        <q-item-section>Адмінка</q-item-section>
      </q-item>
    </q-list>
  </div>
</template>

<script>
export default {
  name: 'AdminMenu',

  data () {
    return {}
  }
}
</script>
