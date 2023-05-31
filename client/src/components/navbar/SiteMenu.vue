<template>
  <q-list dense>
    <!-- Home Page -->
    <q-item tag="a" href="/" native>
      <q-item-section>{{ $t('home_page') }}</q-item-section>
    </q-item>

    <!-- Search Page -->
    <q-item exact clickable :to="{ name: 'search' }">
      <q-item-section>
        {{ $t('search') }}
      </q-item-section>
    </q-item>

    <!-- Add Post -->
    <q-item exact clickable :to="{ name: 'createPost' }">
      <q-item-section>
        {{ $t('add_post') }}
      </q-item-section>
    </q-item>

    <!-- Rules -->
    <q-item exact clickable :to="{ name: 'static.rules' }">
      <q-item-section>
        {{ $t('our_rules') }}
      </q-item-section>
    </q-item>
  </q-list>
</template>

<script>
export default {
  name: 'SiteMenu',

  data () {
    return {}
  }
}
</script>
