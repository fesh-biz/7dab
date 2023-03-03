<template>
  <div class="row justify-center q-pt-lg">
    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5">
      <q-card>
        <q-card-section>
          <div class="text-h6">{{ $t('search') }}</div>
        </q-card-section>

        <!-- Search Form -->
        <q-card-section>
          <!-- Text -->
          <q-input
            class="q-mb-lg"
            outlined
            v-model="formModel.keyword"
            :label="$t('word_or_phrase')"
          />

          <!-- Tags -->
          <q-linear-progress
            class="q-mb-xl"
            dusk="main-new-posts-loading"
            v-if="tagsIsBusy"
            indeterminate
          />

          <tag-field
            :query-tag="getTagFromQuery()"
            :is-busy.sync="tagsIsBusy"
          />
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>

<script>
import SearchApi from 'src/plugins/api/search'
import Search from 'src/plugins/pages/feed/search'
import _ from 'lodash'
import TagField from 'components/form/post/TagField'

const formModel = {
  tags: [],
  keyword: null
}

export default {
  name: 'Search',
  components: { TagField },
  data () {
    return {
      api: new SearchApi(),
      page: new Search(formModel),
      formModel: _.cloneDeep(formModel),
      test: false,
      tagsIsBusy: false
    }
  },

  created () {
    window.document.title = this.$t('search') + ` - ${this.$t('terevenky')}`
    this.api.search({ t: this.$route.query.t })
  },

  methods: {
    getTagFromQuery () {
      const tag = this.$route.query.t

      return tag || null
    }
  }
}
</script>
