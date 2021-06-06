<template>
  <q-card :dusk="'post-' + item.id" :flat="$q.platform.is.mobile" class="q-my-md">
    <!-- Title, Author -->
    <q-card-section class="q-pb-none">
      <!-- Author -->
      <div :dusk="'post-' + item.id + '-author'">
        {{ item.user.name }}
      </div>

      <!-- Title -->
      <q-item dense :to="'test'" :dusk="'post-' + item.id + '-title'" class="font-lobster q-px-none">
        <q-item-section style="font-size: 1.2rem">
          {{ item.title }}
        </q-item-section>
      </q-item>
    </q-card-section>

    <!-- Body -->
    <q-card-section  :dusk="'post-' + item.id + '-body'">
      {{ item.body }}
    </q-card-section>

    <!-- Info -->
    <q-card-section>
      <post-info
        :post="item"
      />
    </q-card-section>

    <q-separator v-if="$q.platform.is.mobile"/>
  </q-card>
</template>

<script>
import PostInfo from 'components/content/PostInfo'
export default {
  name: 'Post',
  components: { PostInfo },
  props: {
    item: {
      type: Object,
      required: true
    }
  }
}
</script>
