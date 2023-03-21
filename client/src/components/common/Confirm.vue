<template>
  <q-dialog v-model="dialog" persistent>
    <q-card>
      <q-card-section class="text-center">
        <div class="text-h5">{{ title }}</div>
        <div class="text-subtitle1 q-mt-md">{{ message }}</div>
      </q-card-section>

      <q-card-actions align="center">
        <q-btn label="Ні" color="negative" @click="dialog = false" />
        <q-btn label="Так" color="positive" @click="confirm" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true
    },
    message: {
      type: String,
      required: true
    },
    onConfirm: {
      type: Function,
      required: true
    },
    value: {
      type: Boolean,
      required: true
    }
  },

  data () {
    return {
    }
  },

  computed: {
    dialog: {
      get () {
        return this.value
      },
      set (val) {
        this.$emit('input', val)
      }
    }
  },

  methods: {
    confirm () {
      this.onConfirm()
      this.dialog = false
    }
  }
}
</script>
