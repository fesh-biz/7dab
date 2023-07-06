<template>
  <div>
    <data-table
      :columns="columns"
      url="admin/users"
      row-key="id"
      title="Користувачі"
      use-body
      red="datatable"
    >
      <template #table="props">
        <q-tr>
          <!-- Actions -->
          <q-td>
            <!-- Preview -->
            <q-item
              :to="{name: 'user', params: {id: props.props.row.id}}"
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

          <!-- Login -->
          <q-td>
            {{ props.props.row.login }}
          </q-td>

          <!-- Email -->
          <q-td>
            {{ props.props.row.email }}
          </q-td>

          <!-- Verified At -->
          <q-td>
            {{ moment(props.props.row.email_verified_at).format('YYYY MM DD') }}
          </q-td>
        </q-tr>
      </template>
    </data-table>
  </div>
</template>

<script>
import DataTable from 'components/common/DataTable'
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
    name: 'login',
    required: true,
    label: 'Login',
    align: 'left',
    sortable: true
  },
  {
    name: 'email',
    required: true,
    label: 'Email',
    align: 'left',
    sortable: true
  },
  {
    name: 'email_verified_at',
    required: true,
    label: 'Verified At',
    align: 'left',
    sortable: true
  }
]

export default {
  name: 'UsersIndex',
  components: { DataTable, IconWithTooltip },
  data () {
    return {
      columns: columns,
      moment: moment
    }
  }
}
</script>
