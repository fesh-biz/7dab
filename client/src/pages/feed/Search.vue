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
            v-if="isFetchingTags"
            indeterminate
            style="position: relative; top: 4px"
          />
          <tag-field
            v-model="formModel.tags"
            :tag-ids="tagIds"
          />
        </q-card-section>

        <!-- Controls -->
        <q-card-actions class="q-pa-md">
          <q-btn color="positive" @click="changeURI" no-caps :label="$t('search')"/>
        </q-card-actions>
      </q-card>
    </div>
  </div>
</template>

<script>
import TagField from 'components/form/common/TagField'
import Search from 'src/plugins/pages/feed/search'
import _ from 'lodash'
import Tag from 'src/models/content/tag'
import TagApi from 'src/plugins/api/tag'
import Api from 'src/plugins/api/search'

const formModel = {
  tags: [],
  keyword: ''
}

export default {
  name: 'Search',

  components: {
    TagField
  },

  data () {
    return {
      formModel: _.cloneDeep(formModel),
      tagIds: [],
      page: new Search(),
      tagApi: new TagApi(),
      isFetchingTags: false,
      isFetchingSearchResult: false,
      api: new Api()
    }
  },

  computed: {
    queryVarsFromFormModel () {
      const vars = {}
      if (this.formModel.keyword) vars.kw = this.formModel.keyword

      const tags = this.formModel.tags
      if (tags.length) {
        vars.tids = []
        tags.forEach(tag => {
          vars.tids.push(tag.value)
        })
      }

      return vars
    }
  },

  watch: {
    $route () {
      this.fillForm()
        .then(() => {
          this.search()
        })
    }
  },

  async created () {
    await this.fillForm()

    if (this.hasFormModelVars()) {
      this.search()
    }
  },

  methods: {
    search () {
      this.page.addRequest(this.queryVarsFromFormModel)
      this.isFetchingSearchResult = true
      this.api.search(this.queryVarsFromFormModel)
        .then(() => {
          this.isFetchingSearchResult = false
        })
    },

    fillForm () {
      return new Promise(resolve => {
        this.formModel.keyword = this.getQueryVars().kw

        const fillModel = (tags) => {
          this.formModel.tags = []
          tags.forEach(tag => {
            if (tag.status !== 'rejected') {
              this.formModel.tags.push({
                label: tag.title,
                value: tag.id
              })
            }
          })
        }

        const ids = this.getQueryVars().tids || []

        if (!ids.length) {
          resolve()
          return
        }

        const tags = Tag.query().whereIdIn(ids).get()
        if (tags.length === ids.length) {
          fillModel(tags)
          resolve()
          return
        }

        this.isFetchingTags = true
        this.tagApi.fetchByIds(ids)
          .then(res => {
            const tags = res.data.data
            Tag.insert({ data: tags })

            fillModel(tags)
            this.isFetchingTags = false
            resolve()
          })
      })
    },

    hasFormModelVars () {
      return !!this.formModel.keyword || !!this.formModel.tags.length
    },

    changeURI () {
      this.$router.push({ name: 'search', query: this.queryVarsFromFormModel })
    },

    getQueryVars () {
      const queryTagIds = () => {
        let res = null

        const tagIds = this.$route.query.tids
        if (tagIds) {
          if (typeof tagIds === 'object') {
            res = tagIds.map(val => parseInt(val))
          } else {
            res = [parseInt(tagIds)]
          }
        }

        return res
      }

      const queryKeyword = () => {
        return this.$route.query.kw || null
      }

      return {
        kw: queryKeyword(),
        tids: queryTagIds()
      }
    }
  }
}
</script>
