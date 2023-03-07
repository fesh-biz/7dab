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

    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5">
    </div>
  </div>
</template>

<script>
import TagField from 'components/form/common/TagField'
import _ from 'lodash'

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
      tagIds: []
    }
  },

  watch: {
    $route () {
      this.tagIds = this.getQueryVars().tids
      this.formModel.keyword = this.getQueryVars().kw
    }
  },

  created () {
    if (this.getQueryVars().tids) {
      this.tagIds = this.getQueryVars().tids
    }

    if (this.getQueryVars().kw) {
      this.formModel.keyword = this.getQueryVars().kw
    }
  },

  methods: {
    changeURI () {
      const getQueryVarsFromFormModel = () => {
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

      this.$router.push({ name: 'search', query: getQueryVarsFromFormModel() })
    },

    getQueryVars () {
      const queryTagIds = () => {
        let res = []

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
