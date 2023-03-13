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
      outlined
      input-debounce="500"
      new-value-mode="add-unique"
      @keydown.native="onKeyDown"
      ref="tagField"
      @input-value="(val) => inputValue = val"
    />
</template>

<script>
import TagApi from 'src/plugins/api/tag'
import Tag from 'src/models/content/tag'

export default {
  name: 'TagField',

  props: {
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
      options: [],
      api: new TagApi(),
      inputValue: null,
      isFiltering: false,
      commaTimeout: null
    }
  },

  computed: {
    model: {
      get () {
        return this.value
      },
      set (val) {
        this.$emit('input', val)
      }
    }
  },

  methods: {
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

    onKeyDown (event) {
      if (this.$route.name === 'search') return

      if (event.key === ',' || (event.key === ',' && event.shiftKey)) {
        event.preventDefault()

        this.$refs.tagField.add(this.inputValue)
        this.$refs.tagField.updateInputValue('')
      }
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

      this.isFiltering = true
      this.api.search(needle, this.filterLimit)
        .then(res => {
          const tags = res.data.data
          Tag.insert({ data: res.data.data })

          this.isFiltering = false
          update(() => {
            this.options = this.getTagOptions(tags)
          })
        })
    }
  }
}
</script>
