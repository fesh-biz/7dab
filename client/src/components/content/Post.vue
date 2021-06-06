<template>
  <q-card :flat="$q.platform.is.mobile" class="q-my-md">
    <!-- Title, Author -->
    <q-card-section class="flex justify-between">
      <!-- Title -->
      <div>
        {{ item.title }}
      </div>

      <!-- Author -->
      <div>
        {{ item.user.name }}
      </div>
    </q-card-section>

    <!-- Body -->
    <q-card-section>
      {{ item.body }}
    </q-card-section>

    <!-- Info -->
    <q-card-section>
      <post-info
        :tags="item.tags"
        :rating="item.rating"
        :total-comments="item.total_comments"
        :total-views="item.total_views"
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
