<template>
  <div class="q-pa-md">
    <q-layout view="hHh Lpr lff">
      <q-header elevated class="bg-cyan">
        <q-toolbar>
          <q-btn flat @click="drawer = !drawer" round dense icon="menu" />
        </q-toolbar>
      </q-header>

      <q-drawer
        v-model="drawer"
        :width="200"
        :breakpoint="500"
        bordered
        overlay
        content-class="bg-grey-3"
      >
        <q-scroll-area class="fit">
          <q-list dense>

            <template v-for="(menuItem, index) in menuList">
              <q-item
                :key="index"
                clickable
                :to="{name: menuItem.routeName}"
                exact
                v-ripple
                @click="drawer = false"
              >
                <q-item-section avatar>
                  <q-icon :name="menuItem.icon" />
                </q-item-section>
                <q-item-section>
                  {{ $t(menuItem.label) }}
                </q-item-section>
              </q-item>
              <q-separator :key="'sep' + index"  v-if="menuItem.separator" />
            </template>

          </q-list>
        </q-scroll-area>
      </q-drawer>

      <q-page-container>
        <q-page padding>
          <router-view/>
        </q-page>
      </q-page-container>
    </q-layout>
  </div>
</template>

<script>
const menuList = [
  {
    icon: 'home',
    label: 'main',
    routeName: 'admin.home',
    separator: false
  },
  {
    icon: 'inventory',
    label: 'tags',
    routeName: 'admin.tags',
    separator: false
  }
]

export default {
  name: 'MainLayout',

  data () {
    return {
      drawer: false,
      menuList
    }
  }
}
</script>
