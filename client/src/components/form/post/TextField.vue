<template>
  <div :class="{error: errorMessage}">
    <q-input
        type="textarea"
        v-model="model"
        ref="textField"
        @input="input"
        autogrow
        outlined
    />

    <span v-if="errorMessage" style="color: red">{{ errorMessage }}</span>
  </div>
</template>

<script>
import PostEditor from 'src/plugins/editor/post'

export default {
  name: 'PostForm',

  props: {
    content: {
      type: String,
      required: ''
    },
    order: {
      type: Number,
      required: true
    },
    errorMessage: {
      type: [String]
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
    this.model = this.replaceBr(this.content)
  },

  methods: {
    input () {
      this.$emit('input', this.model)
    },

    replaceBr (string) {
      return string ? string.replace(/<br>/g, '') : ''
    }
  }
}
</script>
