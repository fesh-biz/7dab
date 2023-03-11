<template>
  <div>
    <!-- Search Form, Status, Messages -->
    <div class="row justify-center q-pt-lg">
      <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5">
        <!-- Search -->
        <q-card flat bordered>
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

          <q-linear-progress
            v-if="isFetchingSearchResult"
            indeterminate
          />
          <!-- Fill Form Message -->
          <q-card-section v-if="!hasFormModelVars">
            {{ $t('fill_form') }}
          </q-card-section>

          <!-- No Result -->
          <q-card-section v-if="hasFormModelVars && !isFetchingSearchResult && !posts.length">
            {{ $t('no_posts_for_request') }}
          </q-card-section>
        </q-card>
      </div>
    </div>

    <!-- Search Result -->
    <div class="row justify-center q-pt-lg">
      <div v-if="posts.length" class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5">
        <post
          v-for="(post, index) in posts"
          :key="'post' + index"
          :post="post"
        />
      </div>
    </div>
  </div>
</template>

<script>
import TagField from 'components/form/common/TagField'
import _ from 'lodash'
import Tag from 'src/models/content/tag'
import TagApi from 'src/plugins/api/tag'
import Api from 'src/plugins/api/search'
import Post from 'src/models/content/post'
import PostComponent from 'src/components/content/Post'
import Cache from 'src/plugins/cache/cache'
import CachePost from 'src/models/cache/cache-post'

const formModel = {
  tags: [],
  keyword: ''
}

export default {
  name: 'Search',

  components: {
    TagField,
    post: PostComponent
  },

  data () {
    return {
      formModel: _.cloneDeep(formModel),
      tagIds: [],
      tagApi: new TagApi(),
      isFetchingTags: false,
      isFetchingSearchResult: false,
      api: new Api(),
      cache: new Cache(),
      posts: []
    }
  },

  computed: {
    // postIds () {
    //   return [] // this.cache.getPageIds('posts')
    // },

    // posts () {
    //   return Post.query()
    //     .withAll()
    //     .whereIdIn(this.postIds)
    //     .orderBy('id', 'desc')
    //     .get()
    // }
  },

  watch: {
    $route () {
      this.fillForm()
        .then(() => {
          this.search()
        })

      this.$nextTick(() => {
        window.scrollTo(0, 0)
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
    hasFormModelVars () {
      return !!this.formModel.keyword || !!this.formModel.tags.length
    },

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
    },

    search () {
      if (!this.hasFormModelVars()) return

      if (this.cache.hasCacheForCurrentPage()) {
        this.setPosts()
      }

      this.isFetchingSearchResult = true
      this.api.search(this.queryVarsFromFormModel(), 'posts')
        .then((res) => {
          const posts = res.data.data
          Post.insert({ data: posts })
          this.setPosts()

          this.isFetchingSearchResult = false
        })
    },

    setPosts () {
      const pageId = this.cache.getPage().id
      let postIds = CachePost.query().where('page_id', pageId).get()
      postIds = postIds.map(p => p.id)

      this.posts = Post.query()
        .withAll()
        .whereIdIn(postIds)
        .orderBy('id', 'desc')
        .get()
    },

    fillForm () {
      return new Promise(resolve => {
        const getQueryVars = () => {
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
        const queryVars = getQueryVars()

        this.formModel.keyword = queryVars.kw

        const fillModelTags = (tags) => {
          this.formModel.tags = []

          if (tags) {
            tags.forEach(tag => {
              if (tag.status !== 'rejected') {
                this.formModel.tags.push({
                  label: tag.title,
                  value: tag.id
                })
              }
            })
          }
        }

        const ids = queryVars.tids || []

        if (!ids.length) {
          fillModelTags()
          resolve()
          return
        }

        const tags = Tag.query().whereIdIn(ids).get()
        if (tags.length === ids.length) {
          fillModelTags(tags)
          resolve()
          return
        }

        this.isFetchingTags = true
        this.tagApi.fetchByIds(ids)
          .then(res => {
            const tags = res.data.data
            Tag.insert({ data: tags })

            fillModelTags(tags)
            this.isFetchingTags = false
            resolve()
          })
      })
    },

    changeURI () {
      if (!this.hasFormModelVars) {
        this.$q.notify({
          message: this.$t('empty_form'),
          position: 'center',
          color: 'negative'
        })

        return
      }

      if (!this.page.isNextRequestSameAsCurrent(this.queryVarsFromFormModel)) {
        this.$router.push({ name: 'search', query: this.queryVarsFromFormModel })
      }

      this.page.setCurrentRequest(this.queryVarsFromFormModel)
    }
  }
}
</script>
