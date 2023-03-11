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
          class="q-px-none"
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
          class="q-px-none"
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

    <div ref="commentsAnchor"></div>
    <comments v-if="isPostPage" :post-id="post.id"/>
  </div>
</template>

<script>
import PostInfo from 'components/content/PostInfo'
import Post from 'src/models/content/post'
import PostText from 'components/content/PostText'
import PostImage from 'components/content/PostImage'
import Comments from 'components/comment/Comments'
import Author from 'components/common/Author'
import Cache from 'src/plugins/cache/cache'

export default {
  name: 'Post',
  components: { Comments, PostImage, PostText, PostInfo, Author },
  props: {
    post: {
      type: Post,
      required: true
    }
  },

  data () {
    return {
      isReady: false,
      isPostPage: false,
      cache: new Cache(),
      entityCache: null,
      isPostImagesLoaded: false,
      isExpanded: false,
      isHasImages: false,
      isScrollToComments: false
    }
  },

  created () {
    this.isHasImages = !!this.post.post_images.length
    this.isPostPage = this.$route.name === 'postPage'
    this.entityCache = this.cache.getEntityCache('posts', this.post.id)
    this.isPostImagesLoaded = this.entityCache.is_images_loaded
    this.isExpanded = this.isPostPage ||
      (this.isPostImagesLoaded && this.entityCache.is_expanded)

    this.isScrollToComments = this.isPostPage && this.$route.params.toComments

    if (!this.isHasImages || this.isPostImagesLoaded) {
      this.isReady = true
    }
  },

  mounted () {
    if (!this.isHasImages && !this.isExpanded) {
      this.maybeExpandBody()
    }

    if (!this.isPostPage && this.isHasImages && !this.isPostImagesLoaded) {
      this.imagesLoadedHandler()
    }

    if (this.isScrollToComments) {
      setTimeout(() => {
        this.$refs.commentsAnchor.scrollIntoView()
        window.scrollBy(0, 50)
      }, 10)
    }
  },

  methods: {
    isShortBody () {
      const postBodyHeight = this.$refs.postBody.$el.scrollHeight
      const allowedBodyHeightWithoutFolding = window.innerHeight * 0.49

      return postBodyHeight < allowedBodyHeightWithoutFolding
    },

    expand () {
      this.isExpanded = true
      this.cache.setEntityCache('posts', this.post.id, {
        is_expanded: true
      })
    },

    maybeExpandBody () {
      const cacheData = {
        is_images_loaded: true
      }
      this.isPostImagesLoaded = true

      if (this.isShortBody()) {
        cacheData.is_expanded = true
        this.isExpanded = true
      }

      this.cache.setEntityCache('posts', this.post.id, cacheData)
      this.isReady = true
    },

    imagesLoadedHandler () {
      const images = this.$refs.postBody.$el.getElementsByTagName('img')

      Promise.all(Array.from(images).filter(img => !img.complete).map(img => new Promise(resolve => {
        img.onload = img.onerror = resolve
      }))).then(() => {
        setTimeout(() => {
          this.maybeExpandBody()
        }, 10)
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
