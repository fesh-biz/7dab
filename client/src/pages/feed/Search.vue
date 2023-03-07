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
import Tag from 'src/models/content/tag'

const formModel = {
  tags: [],
  keyword: null
}

export default {
  name: 'Search',

  data () {
    return {
      api: new SearchApi(),
      page: new Search(),
      formModel: _.cloneDeep(formModel),
      tagOptions: [],
      test: false,
      tagApi: new TagApi(),
      tagsIsFetching: false,
      requestData: null
    }
  },

  computed: {
    tagIdsFromQuery () {
      let res = null

      const tagIds = this.$route.query.tids
      if (tagIds) {
        if (typeof tagIds === 'object') {
          res = tagIds.map(val => parseInt(val))
        } else {
          res = [parseInt(tagIds)]
        }
      }
      return res || null
    },

    keywordFromQuery () {
      return this.$route.query.kw
    },

    query () {
      const query = {}

      if (this.keywordFromQuery) {
        query.kw = this.keywordFromQuery
      }

      if (this.tagIdsFromQuery) {
        query.tids = this.tagIdsFromQuery
      }

      return query
    }
  },

  watch: {
    '$route' (toRoute, fromRoute) {
      this.init()
    }
  },

  created () {
    this.init()
  },

  methods: {
    init () {
      this.formModel = _.cloneDeep(formModel)
      this.fillFormFromQuery()
        .then(() => {
          if (this.tagIdsFromQuery || this.keywordFromQuery) {
            console.log('fetching from init')
            this.fetchPosts()
          }
        })
    },

    fillTags (tags) {
      tags.forEach(tag => {
        this.formModel.tags.push({
          label: tag.title,
          value: tag.id
        })
      })
    },

    fillFormFromQuery () {
      return new Promise((resolve, reject) => {
        if (this.keywordFromQuery) {
          this.formModel.keyword = this.keywordFromQuery
        }

        if (this.tagIdsFromQuery?.length) {
          const tags = Tag.query().whereIdIn(this.tagIdsFromQuery).get()
          if (tags.length === this.tagIdsFromQuery.length) {
            this.fillTags(tags)
            resolve()
            return
          }

          this.tagsIsFetching = true
          this.tagApi.fetchByIds(this.tagIdsFromQuery)
            .then(res => {
              this.tagsIsFetching = false
              const tags = res.data.data

              Tag.insert({ data: tags })

              this.fillTags(tags)
              resolve()
            })
            .catch(() => {
              this.tagsIsFetching = false
              reject()
            })
        } else {
          resolve()
        }
      })
    },

    changeURI () {
      const query = {}
      if (this.formModel.tags) {
        query.tids = this.formModel.tags.map(tag => tag.value)
      }

      if (this.formModel.keyword) {
        query.kw = this.formModel.keyword = this.formModel.keyword.trim().replace(/\s+/g, ' ')
      }

      this.requestData = query
      if (!_.isEqual(this.query, query)) {
        this.$router.push({ name: 'search', query: query })
      }
    },

    fetchPosts () {
      this.changeURI()

      if (!this.requestData.kw && !this.requestData.tids.length) {
        this.$q.notify({
          message: this.$t('empty_form'),
          position: 'center',
          color: 'negative'
        })

        return
      }

      this.api.search(this.requestData)
        .then(res => console.log('posts res', res.data.data))
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
