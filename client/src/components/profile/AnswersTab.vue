<template>
  <data-table
    no-top
    url="profile/answers"
    :columns="columns"
    row-key="comment"
    use-body
  >
    <template #table="props">
      <q-tr>
        <!-- Actions -->
        <q-td>
          <!-- Preview -->
          <q-item
            :to="{name: 'postPage', params: {id: props.props.row.post_id}, query: {c: props.props.row.id}}"
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

        <!-- Reply On -->
        <q-td>
          <icon-with-tooltip
            size="sm"
            :icon="getReplyOnIcon(props.props.row.commentable_type_name)"
            :tooltip="getReplyOnTooltip(props.props.row.commentable_type_name)"
            color="grey-8"
          />
        </q-td>

        <!-- Content -->
        <q-td>
          {{ getShortString(props.props.row.body, 30) }}
        </q-td>

        <!-- Author -->
        <q-td>
          <q-item>
            <q-item-section>
              {{ props.props.row.user.login }}
            </q-item-section>
          </q-item>
        </q-td>

        <!-- Post Title -->
        <q-td>
          {{ getShortString(props.props.row.post.title, 30) }}
        </q-td>

        <!-- Rating -->
        <q-td>
          {{ getRating(props.props.row.rating) }}
        </q-td>

        <!-- Total Answers -->
        <q-td>
          {{ props.props.row.clean_answers_count }}
        </q-td>
      </q-tr>
    </template>
  </data-table>
</template>

<script>
import DataTable from 'components/common/DataTable'
import IconWithTooltip from 'components/common/IconWithTooltip'

export default {
  name: 'AnswerTab',

  components: { DataTable, IconWithTooltip },

  data () {
    return {
      columns: [
        {
          required: true,
          label: this.$t('actions'),
          align: 'left',
          sortable: false
        },
        {
          required: true,
          label: this.$t('reply_on'),
          align: 'left',
          sortable: false
        },
        {
          required: true,
          label: this.$t('answer'),
          align: 'left',
          sortable: false
        },
        {
          required: true,
          label: this.$t('author'),
          align: 'left',
          sortable: false
        },
        {
          required: true,
          label: this.$t('post'),
          align: 'left',
          sortable: false
        },
        {
          required: true,
          label: this.$t('rating'),
          align: 'left',
          sortable: false
        }
      ]
    }
  },

  methods: {
    getReplyOnIcon (commentableType) {
      if (commentableType === 'posts') {
        return 'mms'
      }

      return 'mode_comment'
    },

    getReplyOnTooltip (commentableType) {
      if (commentableType === 'posts') {
        return this.$t('post')
      }

      return this.$t('comment')
    },

    getShortString (str, chars) {
      let res = str.substring(0, 30)
      if (str.length > chars) {
        res += '...'
      }

      return res
    },

    getRating (rating) {
      const p = rating.positive_votes
      const n = rating.negative_votes
      const t = p - n

      return `${t} (${p} - ${n})`
    }
  }
}
</script>
