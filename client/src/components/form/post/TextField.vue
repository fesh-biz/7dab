<template>
  <div :class="{error: errorMessage}" :style="{padding: errorMessage ? '5px' : '' }">
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
    value: {
      type: String,
      required: true
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
    this.model = this.value
  },

  methods: {
    input () {
      this.$emit('input', this.model)
    }
  }
}
</script>
