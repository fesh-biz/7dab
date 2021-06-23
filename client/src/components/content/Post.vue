<template>
  <q-card :dusk="'post-' + post.id" :flat="$q.platform.is.mobile" class="q-my-md">
    <!-- Title, Author -->
    <q-card-section class="q-pb-none">
      <!-- Author -->
      <div :dusk="'post-' + post.id + '-author'">
        {{ post.user.name }}
      </div>

      <!-- Title -->
      <q-item dense :to="{ name: 'postPage', params: {id: post.id }}" :dusk="'post-' + post.id + '-title'" class="font-lobster q-px-none">
        <q-item-section style="font-size: 1.2rem">
          {{ post.title }}
        </q-item-section>
      </q-item>
    </q-card-section>

    <!-- Body -->
    <q-card-section>
      <template v-for="postSection in post.content">
        <post-text :post-text="postSection" v-if="postSection.type === 'text'" :key="'postSection-' + postSection.order"/>
        <post-image :post-image="postSection" v-if="postSection.type === 'image'" :key="'postSection-' + postSection.order"/>
      </template>
    </q-card-section>

    <!-- Info -->
    <q-card-section :dusk="'post-' + post.id + '-info'">
      <post-info
        :post="post"
      />
    </q-card-section>

    <q-separator v-if="$q.platform.is.mobile"/>
  </q-card>
</template>

<script>
import PostInfo from 'components/content/PostInfo'
import PostModel from 'src/models/content/PostModel'
import PostText from 'components/content/PostText'
import PostImage from 'components/content/PostImage'
export default {
  name: 'Post',
  components: { PostImage, PostText, PostInfo },
  props: {
    post: {
      type: PostModel,
      required: true
    }
  }
}
</script>
