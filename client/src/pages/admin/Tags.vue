<template>
  <div>
    <!-- Tags Table -->
    <data-table
      :columns="columns"
      url="admin/tags"
      rowKey="id"
      title="Tags"
      use-body
      ref="datatable"
    >
      <template #table="props">
        <q-tr>
          <!-- ID -->
          <q-td>
            {{ props.props.row.id }}
          </q-td>

          <!-- Title -->
          <q-td>
            {{ props.props.row.title }}
            <q-popup-edit
              buttons
              v-model="props.props.row.title"
              @save="storeTag(props.props.row, 'title')"
            >
              <q-input @focus="setTagPrevData(props.props.row)" @input="updateTag(props.props.row)" v-model="props.props.row.title" />
            </q-popup-edit>
          </q-td>

          <!-- Status -->
          <q-td>
            {{ props.props.row.status }}
            <q-popup-edit
              buttons
              v-model="props.props.row.status"
              @save="storeTag(props.props.row, 'status')"
            >
              <status
                :item="props.props.row"
                @update="updateTag"
              />
            </q-popup-edit>
          </q-td>
        </q-tr>
      </template>
    </data-table>
  </div>
</template>

<script>
import DataTable from 'components/common/DataTable'
import Status from 'components/form/admin/tags/Status'
import _ from 'lodash'
import Tag from 'src/plugins/api/tag'

const columns = [
  {
    name: 'id',
    required: true,
    label: '#',
    align: 'left',
    sortable: true
  },
  {
    name: 'title',
    required: true,
    label: 'Title',
    align: 'left',
    sortable: true
  },
  {
    name: 'status',
    required: true,
    label: 'Status',
    align: 'left',
    sortable: true
  }
]

export default {
  name: 'Tags',

  components: {
    DataTable,
    Status
  },

  data () {
    return {
      columns,
      tagApi: new Tag(),
      tagPrevData: {}
    }
  },

  methods: {
    setTagPrevData (tag) {
      this.tagPrevData = _.cloneDeep(tag)
    },

    updateTag (tag) {
      const tableData = _.cloneDeep(this.$refs.datatable.data)

      const index = _.findIndex(tableData, { id: tag.id })
      tableData.splice(index, 1, tag)

      this.$refs.datatable.data = tableData
    },

    storeTag (tag, propName) {
      const data = {}
      data[propName] = tag[propName]

      this.tagApi.update(tag.id, data)
        .then(() => {
          this.$q.notify({
            message: 'Ok',
            color: 'positive'
          })
        })
        .catch(() => {
          this.updateTag(this.tagPrevData)
          this.$q.notify({
            message: 'Fail',
            color: 'negative'
          })
        })
    }
  }
}
</script>
