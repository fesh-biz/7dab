<template>
  <div :class="{error: errorMessage}">
    <q-select
        :label="$t('tags')"
        v-model="model"
        @input="$emit('input', model)"
        :options="options"
        use-input
        use-chips
        multiple
        option-disable="inactive"
        @filter="filterFn"
        hide-dropdown-icon
        outlined
        input-debounce="300"
        new-value-mode="add-unique"
        @keydown.native="onKeyDown"
        ref="tagField"
        @input-value="(val) => inputValue = val"
    />

    <span v-if="errorMessage" style="color: red">{{ errorMessage }}</span>
  </div>
</template>

<script>
import TagApi from 'src/plugins/api/tag'
import PostEditor from 'src/plugins/editor/post'

export default {
  name: 'TagField',

  props: {
    errorMessage: {
      type: String,
      default: null
    },
    tags: {
      type: Array,
      default: null
    }
  },

  data () {
    return {
      options: [],
      tagApi: new TagApi(),
      postEditor: new PostEditor(),
      fetching: false,
      inputValue: null,
      isFiltering: false,
      commaTimeout: null
    }
  },

  computed: {
    model: {
      get () {
        return this.postEditor.formModel.tags
      },
      set (val) {
        this.postEditor.formModel.tags = val
      }
    }
  },

  methods: {
    onKeyDown (event) {
      if (event.key === ',' || (event.key === ',' && event.shiftKey)) {
        event.preventDefault()

        this.$refs.tagField.add(this.inputValue)
        this.$refs.tagField.updateInputValue('')
      }
    },

    filterFn (val, update) {
      if (val === '') {
        update(() => {
        })
        return
      }

      const needle = val.toLowerCase()
      this.options = []

      this.isFiltering = true
      this.tagApi.search(needle)
        .then(res => {
          const tags = res.data

          const options = []
          tags.forEach(tag => {
            options.push({
              label: tag.title,
              value: tag.id,
              inactive: tag.status === 'rejected'
            })
          })

          this.isFiltering = false
          update(() => {
            this.options = options
          })
        })
    }
  }
}
</script>
