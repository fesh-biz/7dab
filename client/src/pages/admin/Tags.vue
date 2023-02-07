<template>
  <div>
    <!-- Tags Table -->
    <data-table
      :columns="columns"
      url="admin/tags"
      rowKey="id"
      title="Tags"
      useBody
    />
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
