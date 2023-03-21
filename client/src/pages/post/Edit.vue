<template>
  <div class="row justify-center q-px-sm">
    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5 q-mt-md">
      <q-card :flat="$q.platform.is.mobile" class="q-my-md">
        <!-- Post status info -->
        <q-card-section>
          <post-status-explanation :post-id="postId"/>
        </q-card-section>

        <!-- Post form, controls-->
        <template v-if="post && !post.deleted">
          <q-separator spaced="md"/>

          <!-- Post Form -->
          <q-card-section>

            <!-- Title -->
            <q-input
              outlined
              dense
              v-model="postEditor.formModel.title"
              :label="$t('title')"

              :error="!!postEditor.validator.errors.title"
              :error-message="postEditor.validator.errors.title"

              @input="postEditor.validator.resetFieldError('title')"
            />

            <!-- Movement, Deleting Section, Content -->
            <div class="ap-body">
              <div
                v-for="(section, index) in postEditor.formModel.sections"
                :key="'body-element' + section.order"
                class="q-mb-md"
              >
                <!-- Delete -->
                <div>
                  <!-- Delete -->
                  <icon-with-tooltip
                    @click="postEditor.deleteSection(index)"
                    :tooltip="$t('delete_section')"
                    icon="delete"
                  />
                </div>

                <!-- Content -->
                <you-tube-field
                  v-if="section.type === 'youtube'"
                  v-model="section.content"
                />
                <component
                  v-if="section.type !== 'youtube'"
                  :ref="'editor[' + section.order + ']'"
                  :is="section.type + '-field'"
                  :content="section.content"
                  :order="section.order"
                  :error-message="getSectionError(section.order)"
                  @input="postEditor.validator.resetFieldError('sections', section.order)"
                />
              </div>
            </div>

            <!-- Tags -->
            <tag-field
              @input="updatePostTags"
              :error-message="tagError"
            />
          </q-card-section>

          <!-- Main Error Message -->
          <q-card-section v-if="postEditor.validator.mainErrorMessage" class="flex justify-center">
            <div
              class="q-pa-md"
              style="color: white; background-color: red; border-radius: 5px"
            >
              {{ postEditor.validator.mainErrorMessage }}
            </div>
          </q-card-section>

          <!-- Number of Images -->
          <q-card-section
            v-if="postEditor.totalImages"
            class="flex justify-center"
          >
            <div
              :class="{'main-error-message': postEditor.totalImages > postEditor.totalImagesMax }"
            >
              {{ $t('total_number_of_images') }}:
              {{ postEditor.totalImages }}
              ({{ $t('maximum') }}: {{ postEditor.totalImagesMax }})
            </div>
          </q-card-section>

          <!-- Post controls -->
          <q-card-section class="flex justify-between">
            <q-inner-loading
              :showing="isBusy"
              color="positive"
            />

            <!-- Add Section -->
            <div class="inline-block">
              <!-- Text -->
              <icon-with-tooltip
                :tooltip="$t('add_text')"
                color="positive"
                :disabled="isBusy"
                size="xl"
                icon="notes"
                @click="postEditor.addSection('text')"
              />

              <!-- Image -->
              <icon-with-tooltip
                :tooltip="$t('add_image')"
                color="positive"
                :disabled="isBusy"
                size="xl"
                icon="image"
                @click="postEditor.addSection('image')"
              />

              <!-- YouTube -->
              <icon-with-tooltip
                :tooltip="$t('add_youtube')"
                color="positive"
                :disabled="isBusy"
                size="xl"
                icon="smart_display"
                @click="postEditor.addSection('youtube')"
              />
            </div>

            <!-- Cancel, Save, Preview -->
            <div class="inline-block">
              <!-- Preview -->
              <icon-with-tooltip
                :tooltip="$t('preview')"
                color="positive"
                :disabled="isBusy"
                v-if="canBeViewed"
                size="xl"
                icon="pageview"
                @click="$router.push({name: 'postPage', params: {id: postId}})"
              />

              <!-- Cancel -->
              <icon-with-tooltip
                @click="goBack"
                :tooltip="$t('cancel')"
                color="negative"
                :disabled="isBusy"
                size="xl"
                icon="cancel"
              />

              <!-- Save -->
              <icon-with-tooltip
                :tooltip="$t('save')"
                color="positive"
                :disabled="isBusy"
                size="xl"
                icon="check_circle"
                @click="saveOrUpdate()"
              />
            </div>
          </q-card-section>
        </template>
      </q-card>
    </div>
  </div>
</template>

<script>
import ImageField from 'components/form/post/ImageField'
import TextField from 'components/form/post/TextField'
import TagField from 'components/form/post/TagField'

import TooltipIcon from 'components/common/TooltipIcon'
import IconWithTooltip from 'components/common/IconWithTooltip'
import PostEditor from 'src/plugins/editor/post'
import PostModel from 'src/models/content/post'
import PostApi from 'src/plugins/api/post'
import PostImage from 'src/models/content/post-image'
import PostText from 'src/models/content/post-text'
import YouTubeField from 'components/form/common/YouTubeField'
import PostYouTube from 'src/models/content/post-you-tube'
import PostTag from 'src/models/content/post-tag'
import PostStatusExplanation from 'components/form/post/PostStatusExplanation'

export default {
  name: 'AddPost',

  components: {
    TooltipIcon,
    ImageField,
    TextField,
    IconWithTooltip,
    TagField,
    YouTubeField,
    PostStatusExplanation
  },

  data () {
    return {
      postEditor: new PostEditor(),
      postApi: new PostApi(),
      isBusy: false,
      canBeViewed: false
    }
  },

  computed: {
    sectionsErrors () {
      return this.postEditor.validator.errors.sections
    },

    isEditing () {
      return this.$route.name === 'editPost'
    },

    postId () {
      return this.$route.params.id
    },

    post () {
      return PostModel.query().withAll().find(this.postId)
    },

    tagError () {
      return this.postEditor.validator.errors.tags || null
    }
  },

  async created () {
    if (!this.post) {
      const res = await this.fetchPost()
      await PostModel.insert({ data: res.data.data })
    }

    this.$nextTick(() => {
      this.canBeViewed = true
      this.postEditor.fillFormModel(this.postId)
    })
  },

  beforeDestroy () {
    this.postEditor.resetFormModel()
    this.postEditor.validator.resetErrors()
  },

  methods: {
    goBack () {
      window.history.back()
    },

    fetchPost () {
      return new Promise((resolve, reject) => {
        this.isBusy = true
        this.postApi.fetchPost(this.postId, true)
          .then(res => {
            this.isBusy = false
            resolve(res)
          })
          .catch(() => {
            this.isBusy = false
          })
      })
    },

    getSectionError (order) {
      const sectionsErrors = this.postEditor.validator.errors.sections
      if (sectionsErrors && typeof sectionsErrors !== 'string') {
        return sectionsErrors[order]
      }

      return null
    },

    async removeCache (postId) {
      const dbPost = PostModel.query().withAll().find(postId)

      if (dbPost.post_images) {
        const ids = dbPost.post_images.map(i => i.id)

        for (const id of ids) {
          await PostImage.delete(id)
        }
      }

      if (dbPost.post_texts) {
        const ids = dbPost.post_texts.map(i => i.id)

        for (const id of ids) {
          await PostText.delete(id)
        }
      }

      if (dbPost.post_you_tubes) {
        const ids = dbPost.post_you_tubes.map(i => i.id)

        for (const id of ids) {
          await PostYouTube.delete(id)
        }
      }

      await PostTag.delete(pt => pt.post_id === parseInt(postId))
    },

    async saveOrUpdate () {
      this.isBusy = true

      await this.removeCache(this.postId)
      this.postEditor.saveOrUpdate(this.postId)
        .then(async (res) => {
          this.isBusy = false
          const post = res.data.data
          const postId = post.id

          await PostModel.insertOrUpdate({
            where: postId,
            data: post
          })

          this.postEditor.fillFormModel(postId)

          this.$q.notify({
            message: this.$t('success'),
            position: 'center',
            color: 'positive'
          })
        })
        .catch((e) => {
          this.isBusy = false
        })
    },

    updatePostTags () {
      this.postEditor.validator.resetFieldError('tags')
    },

    // @todo: To Apply this method to update post
    crtlSHandler (e) {
      document.addEventListener('keydown', e => {
        if (e.ctrlKey && e.key === 's') {
          // Prevent the Save dialog to open
          e.preventDefault()
          // Place your code here
          console.log('CTRL + S')
        }
      })
    }
  }
}
</script>
