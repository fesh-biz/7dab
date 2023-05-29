<template>
  <q-select
    v-model="model"
    :options="users"
    option-value="id"
    option-label="login"
    label="Select an item"
  ></q-select>
</template>

<script>
import Api from 'src/plugins/api/api'

export default {
  name: 'SelectFakeUser',

  data () {
    return {
      api: new Api(),
      users: [],
      model: null
    }
  },

  watch: {
    model (val) {
      this.$emit('input', val.id)
    }
  },

  async created () {
    const res = await this.api.get('admin/users/fake-users', null, null, true)
    this.users = [{ id: 0, login: 'non' }].concat(res.data)

    const randomIndex = Math.floor(Math.random() * res.data.length)
    this.model = res.data[randomIndex]
  }
}
</script>
