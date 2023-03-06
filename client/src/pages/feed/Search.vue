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
            v-if="tagsIsFetching"
            indeterminate
            style="position: relative; top: 4px"
          />
          <q-select
            :label="$t('tags')"
            v-model="formModel.tags"
            :options="tagOptions"
            use-input
            use-chips
            multiple
            option-disable="inactive"
            @filter="filterTags"
            hide-dropdown-icon
            outlined
            input-debounce="300"
            new-value-mode="add-unique"
          />
        </q-card-section>

        <!-- Controls -->
        <q-card-actions class="q-pa-md">
          <q-btn color="positive" @click="fetchPosts" no-caps :label="$t('search')"/>
        </q-card-actions>
      </q-card>
    </div>
  </div>
</template>

<script>
import SearchApi from 'src/plugins/api/search'
import Search from 'src/plugins/pages/feed/search'
import _ from 'lodash'
import TagApi from 'src/plugins/api/tag'

const formModel = {
  tags: [],
  keyword: null
}

export default {
  name: 'Search',

  data () {
    return {
      api: new SearchApi(),
      page: new Search(formModel),
      formModel: _.cloneDeep(formModel),
      tagOptions: [],
      test: false,
      tagApi: new TagApi(),
      tagsIsFetching: false
    }
  },

  computed: {
    tagIdsFromQuery () {
      return this.$route.query.tids || null
    },

    keywordFromQuery () {
      return this.$route.query.kw
    }
  },

  created () {
    this.maybeSetTagsAndKeywordFromQuery()
  },

  methods: {
    maybeSetTagsAndKeywordFromQuery () {
      if (this.keywordFromQuery) {
        this.formModel.keyword = this.keywordFromQuery
      }

      if (this.tagIdsFromQuery) {
        this.tagsIsFetching = true
        this.tagApi.fetchByIds(this.tagIdsFromQuery)
          .then(res => {
            this.tagsIsFetching = false
            const tags = res.data.data
            tags.forEach(tag => {
              this.formModel.tags.push({
                label: tag.title,
                value: tag.id
              })
            })
          })
          .catch(() => {
            this.tagsIsFetching = false
          })
      }
    },

    changeURI () {
      const query = {}
      if (this.formModel.tags) {
        query.tids = this.formModel.tags.map(tag => tag.value)
      }

      if (this.formModel.keyword) {
        query.kw = this.formModel.keyword = this.formModel.keyword.trim().replace(/\s+/g, ' ')
      }

      this.$router.push({ name: 'search', query: query })
      console.log('keyword', this.$route.query.kw)
    },

    fetchPosts () {
      this.changeURI()
      // const requestData = {}

      // if (this.formModel.tags) {
      //   for (const tag of this.formModel.tags) {
      //     console.log(tag)
      //   }
      // }
      //
      // this.api.search(this.formModel)
      //   .then(res => console.log('res', res))
    },

    filterTags (val, update) {
      if (val === '') {
        update(() => {
        })
        return
      }

      const needle = val.toLowerCase()
      this.tagOptions = []

      this.tagApi.search(needle)
        .then(res => {
          const tags = res.data.data

          const options = []
          tags.forEach(tag => {
            options.push({
              label: tag.title,
              value: tag.id,
              inactive: tag.status === 'rejected'
            })
          })

          update(() => {
            this.tagOptions = options
          })
        })
    }
  }
}
</script>
