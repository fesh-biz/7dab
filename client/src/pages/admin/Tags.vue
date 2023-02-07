<template>
  <div>
    <!-- Tags Table -->
    <data-table
      :columns="columns"
      url="admin/tags/paginated-search"
      rowKey="id"
      title="Tags"
      use-body
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
          </q-td>

          <!-- Title -->
          <q-td>
            {{ props.props.row.status }}
          </q-td>
        </q-tr>
      </template>
    </data-table>
  </div>
</template>

<script>
import DataTable from 'components/common/DataTable'

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
    DataTable
  },

  data () {
    return {
      columns
    }
  }
}
</script>
