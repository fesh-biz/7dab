<template>
  <q-card
    :id="'post-id-' + post.id"
    :dusk="'post-' + post.id"
    :flat="$q.platform.is.mobile"
    class="q-my-md"
    :style="{opacity: postImagesLoaded ? 1 : 0}"
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
    <q-card-section ref="postBody" :class="{folded: postImagesLoaded && !isExpanded }">
      <component
        v-for="postSection in post.content"
        :key="'postSection-' + postSection.order"
        :is="'post-' + postSection.type"
        :data="postSection"
      />
    </q-card-section>

    <!-- Expander -->
    <q-card-section v-if="!isExpanded" class="q-pt-none">
      <div
        @click="expand"
        class="expander"
        style="border-radius: 0px 0px 10px 10px"
      >
        {{ $t('expand') }}
      </div>
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
    },
    isAllImagesLoaded: {
      type: Boolean,
      default: false
    }
  },

  computed: {
    isExpanded () {
      if (this.isPostPage) return true

      return this.post.is_expanded
    },

    postImagesLoaded () {
      return this.post.is_images_loaded
    }
  },

  watch: {
    isAllImagesLoaded () {
      const postBodyHeight = this.$refs.postBody.$el.clientHeight
      const allowedBodyHeightWithoutFolding = window.innerHeight * 0.7

      if (postBodyHeight < allowedBodyHeightWithoutFolding) {
        this.expand()
      }
      this.updateImagesLoadingStatus()
    }
  },

  methods: {
    expand () {
      Post.update({
        where: this.post.id,
        data: {
          is_expanded: true
        }
      })
    },

    updateImagesLoadingStatus () {
      Post.update({
        where: this.post.id,
        data: {
          is_images_loaded: true
        }
      })
    }
  }
}
</script>

<style lang="sass">
.folded
  max-height: 70vh
  overflow: hidden

.expander
  background-color: #dbecfd
  color: #0d47a1
  transition: 0.3s
  cursor: pointer
  text-align: center
  padding: 10px
  font-weight: bold

  &:hover
    color: white
    background-color: #00b0ff
</style>
