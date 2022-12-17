<template>
  <q-select
      :label="$t('tags')"
      v-model="model"
      :options="options"
      use-input
      use-chips
      multiple
      @filter="filterFn"
      hide-dropdown-icon
      outlined
      input-debounce="300"
      new-value-mode="add-unique"
      style="width: 250px"
  />
</template>

<script>
import TagApi from 'src/plugins/api/tag'

export default {
  name: 'TagField',

  data () {
    return {
      model: [],
      options: [],
      tagApi: new TagApi(),
      fetching: false
    }
  },

  methods: {
    filterFn (val, update) {
      if (val === '') {
        update(() => {})
        return
      }

      update(() => {
        const needle = val.toLowerCase()

        this.tagApi.search(needle)
          .then(res => {
            console.log('res', res)
          })

        this.options = this.options.filter(v => {
          return v.label.toLowerCase().indexOf(needle) > -1
        })
      })
    }
  }
}
</script>
