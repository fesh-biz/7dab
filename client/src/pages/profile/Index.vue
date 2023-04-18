<template>
  <div class="q-mt-lg">
    <!-- Profile Stats -->
    <stats :user-id="me.id" @fetched="fetchContentStats"/>

    <q-linear-progress v-if="isFetchingStats && statsFetched" indeterminate style="position: relative; top: 4px"/>

    <!-- Tabs, Tab Panels -->
    <div class="profile" v-if="!isFetchingStats">
      <!-- Tabs -->
      <q-tabs
        v-model="tab"
        inline-label
        outside-arrows
        mobile-arrows
        no-caps
        class="q-mt-xl text-grey-8"
      >
        <q-tab :disable="!contentStats.posts.total" name="posts" icon="mms" :label="$t('posts')">
          <q-badge color="green" class="q-ml-sm">
            {{ contentStats.posts.total }}
          </q-badge>
        </q-tab>
        <q-tab :disable="!contentStats.total_comments" name="comments" icon="mode_comment" :label="$t('comments')">
          <q-badge color="green" class="q-ml-sm">
            {{ contentStats.total_comments }}
          </q-badge>
        </q-tab>
        <q-tab :disable="!contentStats.total_answers" name="answers" icon="forum" :label="$t('answers')">
          <q-badge color="green" class="q-ml-sm">
            {{ contentStats.total_answers }}
          </q-badge>
        </q-tab>
      </q-tabs>

      <!-- Tab Panels -->
      <q-card flat bordered class="q-mb-lg">
        <q-card-section>
          <q-tab-panels v-model="tab" animated>
            <!-- Posts -->
            <q-tab-panel name="posts">
              <posts-tabs :total-posts="contentStats.posts" />
            </q-tab-panel>

            <!-- Comments -->
            <q-tab-panel name="comments">
              <data-table
                url="profile/comments"
                :columns="commentTableColumns"
                row-key="comment"
                use-body
              >
                <template #table="props">
                  <q-tr>
                    <!-- Actions -->
                    <q-td>
                      <!-- Preview -->
                      <q-item
                        :to="{name: 'postPage', params: {id: props.props.row.id}, query: {c: 1}}"
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
                  </q-tr>
                </template>
              </data-table>
            </q-tab-panel>

            <q-tab-panel name="answers">
              <div class="text-h6">Answers</div>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
            </q-tab-panel>
          </q-tab-panels>
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>

<script>
import Stats from 'components/user/Stats'
import PostsTabs from 'components/profile/PostsTabs'
import Me from 'src/models/user/me'
import ProfileApi from 'src/plugins/api/profile'
import DataTable from 'components/common/DataTable'
import IconWithTooltip from 'components/common/IconWithTooltip'

export default {
  name: 'Account',

  components: { Stats, PostsTabs, DataTable, IconWithTooltip },

  data () {
    return {
      me: Me.query().first(),
      tab: 'comments',
      isFetchingStats: true,
      api: new ProfileApi(),
      contentStats: null,
      statsFetched: false,
      commentTableColumns: [
        {
          required: true,
          label: this.$t('actions'),
          align: 'left',
          sortable: false
        },
        {
          required: true,
          label: this.$t('content'),
          align: 'left',
          sortable: false
        }
      ]
    }
  },

  methods: {
    async fetchContentStats () {
      this.statsFetched = true
      const res = await this.api.fetchContentStats()
      this.isFetchingStats = false
      this.contentStats = res.data
    }
  }
}
</script>

<style lang="sass">
.profile
  .q-item
    padding: 0
    min-height: unset
    float: left
  .q-tab-panel
    padding: 0
</style>
