<template>
  <div>
    <q-editor
      :class="{'border-red': !!error_message}"
      v-model="model"
      @input="input"
      :toolbar="toolbar"
      max-height="200px"
    />
    <div v-if="!!error_message" style="color: red; font-size: 11px">
      {{ error_message }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'Editor',

  props: {
    error_message: {
      type: String,
      default: null
    },
    value: {
      type: String,
      required: true
    }
  },

  data () {
    return {
      model: '',
      toolbar: [
        [
          {
            label: this.$q.lang.editor.align,
            icon: this.$q.iconSet.editor.align,
            fixedLabel: true,
            list: 'only-icons',
            options: ['left', 'center', 'right', 'justify']
          }
        ],
        ['bold', 'italic', 'strike', 'underline', 'subscript', 'superscript'],
        ['token', 'hr', 'link', 'custom_btn'],
        ['quote', 'unordered', 'ordered', 'outdent', 'indent'],

        ['undo', 'redo']
      ]
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
