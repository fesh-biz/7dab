<template>
  <div class="admin-posts">
    <!-- Tags Table -->
    <data-table
      :columns="columns"
      url="admin/posts"
      rowKey="id"
      :title="title"
      use-body
      ref="datatable"
    >
      <template #table="props">
        <q-tr>
          <!-- Actions -->
          <q-td>
            <!-- Preview -->
            <q-item
              :to="{name: 'admin.posts.preview', params: {id: props.props.row.id}}"
              target="_blank"
            >
              <q-item-section>
                <icon-with-tooltip
                  :tooltip="$t('preview')"
                  color="positive"
                  size="sm"
                  icon="pageview"
                />
              </q-item-section>
            </q-item>
          </q-td>

          <!-- ID -->
          <q-td>
            {{ props.props.row.id }}
          </q-td>

          <!-- Title -->
          <q-td>
            {{ props.props.row.title }}
          </q-td>

          <!-- Status -->
          <q-td @click="setPrevData(props.props.row)">
            {{ props.props.row.status }}
            <q-popup-edit
              buttons
              v-model="props.props.row.status"
              @save="storeTag(props.props.row, 'status')"
            >
              <status
                :item="props.props.row"
                :statuses="statuses"
                @update="updateTableItem"
              />
            </q-popup-edit>
          </q-td>

          <!-- Views -->
          <q-td>
            {{ props.props.row.views }}
          </q-td>

          <!-- Views -->
          <q-td>
            {{ props.props.row.comments }}
          </q-td>

          <!-- Views -->
          <q-td>
            {{ moment(props.props.row.created_at).format('YYYY-MM-DD') }}
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
import Api from 'src/plugins/api/api'
import IconWithTooltip from 'components/common/IconWithTooltip'
import moment from 'moment'

const columns = [
  {
    name: '',
    required: true,
    label: '',
    align: 'left',
    sortable: true
  },
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
  },
  {
    name: 'views',
    required: true,
    label: 'Views',
    align: 'left',
    sortable: true
  },
  {
    name: 'comments',
    required: true,
    label: 'Comments',
    align: 'left',
    sortable: true
  },
  {
    name: 'created_at',
    required: true,
    label: 'Created at',
    align: 'left',
    sortable: true
  }
]

export default {
  name: 'Index',

  components: {
    IconWithTooltip,
    DataTable,
    Status
  },

  data () {
    return {
      columns,
      api: new Api(),
      prevData: {},
      updateUrl: 'admin/posts',
      title: 'Posts',
      moment: moment,
      statuses: [
        { label: 'Draft', value: 'draft' },
        { label: 'Pending', value: 'pending' },
        { label: 'Reviewing', value: 'reviewing' },
        { label: 'Approved', value: 'approved' },
        { label: 'Declined', value: 'declined' },
        { label: 'Editing', value: 'editing' }
      ]
    }
  },

  methods: {
    setPrevData (item) {
      this.prevData = _.cloneDeep(item)
    },

    updateTableItem (item) {
      const tableData = _.cloneDeep(this.$refs.datatable.data)

      const index = _.findIndex(tableData, { id: item.id })
      tableData.splice(index, 1, item)

      this.$refs.datatable.data = tableData
    },

    storeTag (item, propName) {
      const data = {}
      data[propName] = item[propName]

      const url = `admin/posts/${item.id}`
      this.api.post(url, data, null, null, true)
        .then(() => {
          this.$q.notify({
            message: 'Ok',
            color: 'positive'
          })
        })
        .catch(() => {
          this.updateTableItem(this.prevData)
          this.$q.notify({
            message: 'Fail',
            color: 'negative'
          })
        })
    }
  }
}
</script>

<style lang="sass">
.admin-posts
  .q-item
    padding: 0
    min-height: unset
    float: left
</style>
