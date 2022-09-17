<template>
  <textarea
      class="text-field"
      v-model="model"
  />
</template>

<script>
import PostEditor from 'src/plugins/editor/post'

export default {
  name: 'PostForm',

  props: {
    error_message: {
      type: String,
      default: null
    },
    value: {
      type: String,
      required: true
    },
    order: {
      type: Number,
      required: true
    }
  },

  data () {
    return {
      model: '',
      postEditor: new PostEditor()
    }
  },

  watch: {
    model (val) {
      this.postEditor.updateSection(this.order, val)
    }
  },

  created () {
    this.model = this.value
  },

  methods: {
    input () {
      this.$emit('input', this.model)
    }
  }
}
</script>

<style lang="sass">
.text-field
  width: 100%
  min-height: 200px
  border-radius: 5px
  border: 1px solid lightblue
  resize: none
  padding: 5px 10px
</style>
