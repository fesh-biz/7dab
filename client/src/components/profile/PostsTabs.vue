<template>
  <div class="post-tabs">
    <q-tabs
      v-model="tab"
      inline-label
      outside-arrows
      mobile-arrows
      no-caps
      dense
      class="text-grey-8"
    >
      <q-tab name="approved" icon="mms" :label="$t('published')">
        <q-badge color="green" class="q-ml-sm">{{ totalPosts.approved }}</q-badge>
      </q-tab>
      <q-tab name="pending" icon="mark_chat_unread" :label="$t('pending')">
        <q-badge color="green" class="q-ml-sm">{{ totalPosts.pending }}</q-badge>
      </q-tab>
      <q-tab name="draft" icon="chat_bubble_outline" :label="$t('drafts')">
        <q-badge color="green" class="q-ml-sm">{{ totalPosts.draft }}</q-badge>
      </q-tab>
    </q-tabs>

    <q-separator />

    <data-table
      :columns="columns"
      :url="url"
      row-key="post"
      use-body
    >
      <template #table="props">
        <q-tr>
          <!-- Actions -->
          <q-td>
            <!-- Preview -->
            <q-item
              :to="{name: 'postPage', params: {id: props.props.row.id}, query: {p: 1}}"
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

            <!-- Edit -->
            <q-item
              v-if="props.props.row.status === 'draft'"
              :to="{ name: 'editPost', params: { id: props.props.row.id } }"
              target="_blank"
              class="q-ml-sm"
            >
              <q-item-section>
                <icon-with-tooltip
                  :tooltip="$t('to_edit')"
                  color="positive"
                  size="sm"
                  icon="edit_note"
                />
              </q-item-section>
            </q-item>
          </q-td>

          <!-- Title -->
          <q-td>
            {{ getTitle(props.props.row.title) }}
          </q-td>

          <template v-if="tab === 'approved'">
            <!-- Rating -->
            <q-td class="text-right">
              <strong>{{ props.props.row.rating.positive_votes - props.props.row.rating.negative_votes }}</strong>
              (
                {{ props.props.row.rating.positive_votes }} -
                {{ props.props.row.rating.negative_votes }}
              )
            </q-td>

            <!-- Comments -->
            <q-td class="text-right">
              {{ props.props.row.comments }}
            </q-td>
          </template>
        </q-tr>
      </template>
    </data-table>
  </div>
</template>

<script>
import Me from 'src/models/user/me'
import DataTable from 'components/common/DataTable'
import IconWithTooltip from 'components/common/IconWithTooltip'

export default {
  name: 'PostsTabs',

  components: {
    DataTable,
    IconWithTooltip
  },

  props: {
    totalPosts: {
      type: Object,
      required: true
    }
  },

  data () {
    return {
      me: Me.query().first(),
      tab: 'approved',
      title: 'Posts'
    }
  },

  computed: {
    columns () {
      const res = [
        {
          required: true,
          label: this.$t('actions'),
          align: 'left',
          sortable: false
        },
        {
          required: true,
          label: this.$t('title'),
          align: 'left',
          sortable: false
        }
      ]

      if (this.tab === 'approved') {
        res.push({
          required: true,
          label: this.$t('rating'),
          align: 'right',
          sortable: false
        })
        res.push({
          required: true,
          label: this.$t('total_comments'),
          align: 'right',
          sortable: false
        })
      }

      return res
    },

    url () {
      return 'profile/posts?status=' + this.tab
    }
  },

  methods: {
    getTitle (title) {
      let res = title.substring(0, 30)
      if (title.length > 30) {
        res += '...'
      }

      return res
    }
  }
}
</script>

<style lang="sass">
.post-tabs
  .q-item
    padding: 0
    min-height: unset
    float: left
</style>
