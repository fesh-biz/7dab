<template>
  <div>
    <!-- Post -->
    <q-card
      :id="'post-id-' + post.id"
      :dusk="'post-' + post.id"
      :bordered="!$q.platform.is.mobile"
      flat
      class="q-my-md"
    >
      <!-- Title, Author -->
      <q-card-section class="q-pb-none">
        <!-- Author -->
        <author :name="post.user.login" :avatar="post.user.avatar"/>

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
      <q-card-section ref="postBody" :class="{folded: !isExpanded }">
        <component
          v-for="postSection in post.content"
          :key="'postSection-' + postSection.order"
          :is="'post-' + postSection.type"
          :data="postSection"
        />
      </q-card-section>

      <!-- Expander -->
      <q-card-section v-if="!isExpanded && isReady" class="q-pt-none">
        <div
          @click="expand"
          class="expander"
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

    <post-comments v-if="isPostPage" :post-id="post.id"/>
  </div>
</template>

<script>
import PostInfo from 'components/content/PostInfo'
import Post from 'src/models/content/post'
import PostText from 'components/content/PostText'
import PostImage from 'components/content/PostImage'
import DocumentState from 'src/plugins/tools/document-state'
import PostComments from 'components/content/PostComments'
import Author from 'components/common/Author'

export default {
  name: 'Post',
  components: { PostComments, PostImage, PostText, PostInfo, Author },
  props: {
    post: {
      type: Post,
      required: true
    },
    isPostPage: {
      type: Boolean,
      default: false
    }
  },

  data () {
    return {
      documentState: new DocumentState(),
      isReady: false
    }
  },

  computed: {
    isExpanded () {
      if (this.isPostPage) return true
      if (!this.postImagesLoaded) return false

      return this.post.is_expanded
    },

    postImagesLoaded () {
      return this.post.is_images_loaded
    }
  },

  created () {
    if (this.hasNotImages()) {
      this.isReady = true
    }
  },

  mounted () {
    if (!this.postImagesLoaded) {
      addEventListener('imagesUploaded', this.onImagesLoaded)
    }
  },

  methods: {
    hasNotImages () {
      return !!this.post.post_images.length
    },

    isShortBody () {
      const postBodyHeight = this.$refs.postBody.$el.clientHeight
      const allowedBodyHeightWithoutFolding = window.innerHeight * 0.49

      return postBodyHeight < allowedBodyHeightWithoutFolding
    },

    onImagesLoaded () {
      if (this.isShortBody()) {
        this.expand()
      }
      this.updateImagesLoadingStatus()
      this.isReady = true

      removeEventListener('imagesUploaded', this.onImagesLoaded)
    },

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
  max-height: 50vh
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
