<template>
  <q-card class="q-my-md">
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
    <q-card-section class="row">
      <!-- Views, Comments, Rating-->
      <div class="flex col-md-4 q-my-md">
        <!-- Rating -->
        <div>{{ $t('rating') }}:{{ item.rating }}</div>
        <!-- Views -->
        <div>{{ $t('views') }}:{{ item.total_views }}</div>
        <!-- Comments -->
        <div>{{ $t('comments') }}:{{ item.total_comments }}</div>
      </div>

      <!-- Tags -->
      <div class="flex col-md-8 q-my-md">
        <tag
          v-for="(tag, index) in item.tags"
          :key="'tag' + index"
          :item="tag"
        />
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
import Tag from 'components/content/Tag'
export default {
  name: 'Post',
  components: { Tag },
  props: {
    item: {
      type: Object,
      required: true
    }
  }
}
</script>
