<template>
  <q-select
    :label="$t('tags')"
    v-model="model"
    :options="options"
    use-input
    use-chips
    multiple
    option-disable="inactive"
    @filter="filter"
    hide-dropdown-icon
    @input="(val) => {$emit('input', val)}"
    outlined
    input-debounce="300"
    new-value-mode="add-unique"
  />
</template>

<script>
import TagApi from 'src/plugins/api/tag'
import Tag from 'src/models/content/tag'

export default {
  name: 'TagField',

  props: {
    tagIds: {
      type: [Array, Number],
      required: false
    },
    value: {
      type: Array,
      required: false
    },
    filterLimit: {
      type: Number,
      default: 5
    }
  },

  data () {
    return {
      isFetching: false,
      model: null,
      options: [],
      api: new TagApi()
    }
  },

  created () {
    if (this.value?.length) {
      this.model = this.value
    }

    if (this.tagIds) {
      this.fillModelByTagIds(this.tagIds)
    }
  },

  methods: {
    fillModelByTagIds (ids) {
      this.api.fetchByIds(ids)
        .then(res => {
          const tags = res.data.data
          Tag.insert({ data: tags })

          this.model = []
          tags.forEach(tag => {
            if (tag.status !== 'rejected') {
              this.model.push({
                label: tag.title,
                value: tag.id
              })
            }
          })
        })
    },

    getTagOptions (tags) {
      const options = []
      tags.forEach(tag => {
        options.push({
          label: tag.title,
          value: tag.id,
          inactive: tag.status === 'rejected'
        })
      })

      return options
    },

    filter (val, update) {
      this.options = []

      if (val === '') {
        update(() => {
        })
        return
      }

      const needle = val.toLowerCase()
      const fetchedTags = Tag.query().where(tag => {
        return tag.title.toLowerCase().includes(needle)
      }).get()

      if (fetchedTags.length === this.filterLimit) {
        update(() => {
          this.options = this.getTagOptions(fetchedTags)
        })

        return
      }

      this.api.search(needle, this.filterLimit)
        .then(res => {
          const tags = res.data.data
          Tag.insert({ data: res.data.data })

          update(() => {
            this.options = this.getTagOptions(tags)
          })
        })
    }
  }
}
</script>
