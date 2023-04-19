<template>
  <div :class="{'no-top': noTop}">
    <!-- Stock Items -->
    <q-table
      flat
      :title="title"
      :data="data"
      :columns="columns"
      :loading="loading"
      :rows-per-page-label="rowsPerPage"
      :pagination.sync="pagination"
      :row-key="rowKey"
      :filter="filter"
      @request="onRequest"
    >
      <template v-slot:top-right>
        <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
          <template v-slot:append>
            <q-icon name="search"/>
          </template>
        </q-input>
      </template>

      <template v-if="useBody" v-slot:body="props">
        <slot name="table" :props="props"></slot>
      </template>
    </q-table>
  </div>
</template>

<script>
import Api from 'src/plugins/api/api'

export default {
  name: 'Datable',

  props: {
    noTop: {
      type: Boolean,
      default: false
    },

    columns: {
      type: Array,
      required: true
    },
    url: {
      type: String,
      required: true
    },
    rowKey: {
      type: String,
      required: true
    },
    title: {
      type: String,
      default: ''
    },
    useBody: {
      type: Boolean,
      default: false
    }
  },

  data () {
    return {
      api: new Api(),
      filter: '',
      data: [],
      rowsPerPage: '0',
      loading: false,
      pagination: {
        sortBy: '',
        descending: false,
        page: 1,
        rowsPerPage: 0,
        rowsNumber: 0
      }
    }
  },

  watch: {
    url (val) {
      this.fetchItems()
    }
  },

  created () {
    this.fetchItems()
  },

  methods: {
    onRequest (props) {
      this.fetchItems(Object.assign(props.pagination, { keyword: this.filter }))
    },

    fetchItems (params) {
      this.loading = true
      this.data = []

      this.api.get(this.url, params, null, true)
        .then(res => {
          this.loading = false
          this.rowsPerPage = '' + res.data.per_page
          this.data = res.data.data

          this.pagination = {
            sortBy: res?.config?.params?.sortBy,
            descending: res?.config?.params?.descending,
            page: res.data.current_page,
            rowsPerPage: res.data.per_page,
            rowsNumber: res.data.total
          }
        })
        .catch(() => {
          this.loading = false
        })
    }
  }
}
</script>

<style lang="sass">
.no-top
  .q-table__top
    display: none
</style>
