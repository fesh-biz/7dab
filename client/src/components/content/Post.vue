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
      <!-- Title, Author, Controls -->
      <q-card-section class="q-pb-none">
        <!-- Author, Controls-->
        <div class="flex justify-between">
          <!-- Author -->
          <div>
            <author :name="post.user.login" :avatar="post.user.avatar"/>
          </div>

          <!-- Controls -->
          <div>
            <!--<q-btn flat dense no-caps label="test"/>-->
          </div>
        </div>

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
      <q-card-section ref="postBody" :class="{folded: !postCache.is_expanded && !isPostPage }">
        <component
          v-for="postSection in post.content"
          :key="'postSection-' + postSection.order"
          :is="'post-' + postSection.type"
          :data="postSection"
        />
      </q-card-section>

      <!-- Expander -->
      <q-card-section v-if="!postCache.is_expanded && !isPostPage" class="q-pt-none">
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
import PostYoutube from 'components/content/PostYoutube'

export default {
  name: 'Post',
  components: { Comments, PostImage, PostText, PostInfo, Author, PostYoutube },
  props: {
    post: {
      type: Post,
      required: true
    }
  },

  data () {
    return {
      isPostPage: false,
      cache: new Cache(),
      postCache: {
        is_expanded: false,
        is_images_loaded: false
      },
      isHasImages: false,
      postId: null
    }
  },

  async created () {
    this.isHasImages = !!this.post.post_images.length
    this.isPostPage = this.$route.name === 'postPage'
    this.postCache = await this.cache.getOrCreateEntityCache('posts', this.post.id)
  },

  async mounted () {
    if (!this.isHasImages && !this.postCache.is_expanded) {
      await this.maybeExpandBody('mounted')
    }

    if (!this.postCache.is_expanded && this.isHasImages && !this.postCache.is_images_loaded) {
      this.imagesLoadedHandler()
    }

    if (this.isPostPage && this.$route.params.toComments) {
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

    async expand () {
      this.isExpanded = true
      this.postCache = await this.cache.updateEntityCache('posts', this.post.id, {
        is_expanded: true
      })
    },

    async maybeExpandBody (imagesLoaded = false) {
      const cacheData = {
        is_images_loaded: imagesLoaded
      }

      if (this.isShortBody()) {
        cacheData.is_expanded = true
      }

      this.postCache = await this.cache.updateEntityCache('posts', this.post.id, cacheData)
    },

    imagesLoadedHandler () {
      const images = this.$refs.postBody.$el.getElementsByTagName('img')

      Promise.all(Array.from(images).filter(img => !img.complete).map(img => new Promise(resolve => {
        img.onload = img.onerror = resolve
      }))).then(() => {
        setTimeout(() => {
          this.maybeExpandBody(true)
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
