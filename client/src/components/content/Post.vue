<template>
  <q-card
      :id="'post-id-' + post.id"
      :dusk="'post-' + post.id"
      :flat="$q.platform.is.mobile"
      class="q-my-md"
  >
    <!-- Title, Author -->
    <q-card-section class="q-pb-none">
      <!-- Author -->
      <div :dusk="'post-' + post.id + '-author'">
        {{ post.user.login }}
      </div>

      <!-- Title -->
      <q-item
          v-if="isPostPage"
          :dusk="'post-' + post.id + '-title'"
          class="font-lobster q-px-none"
      >
        <q-item-section style="font-size: 1.2rem">
          {{ post.title }}
        </q-item-section>
      </q-item>

      <q-item
          v-if="!isPostPage"
          dense
          :to="{ name: 'postPage', params: {id: post.id }}"
          :dusk="'post-' + post.id + '-title'"
          class="font-lobster q-px-none"
      >
        <q-item-section style="font-size: 1.2rem">
          {{ post.title }}
        </q-item-section>
      </q-item>
    </q-card-section>

    <!-- Body -->
    <q-card-section>
      <component
          v-for="postSection in post.content"
          :key="'postSection-' + postSection.order"
          :is="'post-' + postSection.type"
          :data="postSection"
      />
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
import Post from 'src/models/content/post'
import PostText from 'components/content/PostText'
import PostImage from 'components/content/PostImage'

export default {
  name: 'Post',
  components: { PostImage, PostText, PostInfo },
  props: {
    post: {
      type: Post,
      required: true
    },
    isPostPage: {
      type: Boolean,
      default: false
    }
  }
}
</script>
