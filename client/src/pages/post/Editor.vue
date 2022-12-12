<template>
  <div class="row justify-center q-px-sm">
    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5 q-mt-md">
      <q-card :flat="$q.platform.is.mobile" class="q-my-md">
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
              <component
                  :ref="'editor[' + section.order + ']'"
                  :is="section.type + '-field'"
                  :content="section.content"
                  :order="section.order"
                  :error-message="getSectionError(section.order)"
                  @input="postEditor.validator.resetFieldError('sections', section.order)"
              />
            </div>
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
          </div>

          <!-- Cancel, Save -->
          <div class="inline-block">
            <!-- Cancel -->
            <icon-with-tooltip
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
      </q-card>
    </div>
  </div>
</template>

<script>
import ImageField from 'components/form/post/ImageField'
import TextField from 'components/form/post/TextField'

import TooltipIcon from 'components/common/TooltipIcon'
import IconWithTooltip from 'components/common/IconWithTooltip'
import PostEditor from 'src/plugins/editor/post'
import PostModel from 'src/models/content/post'
import PostApi from 'src/plugins/api/post'

export default {
  name: 'AddPost',

  components: {
    TooltipIcon,
    ImageField,
    TextField,
    IconWithTooltip
  },

  data () {
    return {
      postEditor: new PostEditor(),
      postApi: new PostApi(),
      isBusy: false
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
    }
  },

  created () {
    if (this.isEditing && !this.post) {
      this.fetchPost()
        .then(res => {
          PostModel.insert({ data: res.data.data })

          this.postEditor.fillFormModel(this.postId)
        })
    }
  },

  beforeDestroy () {
    this.postEditor.resetFormModel()
  },

  methods: {
    fetchPost () {
      return new Promise((resolve, reject) => {
        this.isBusy = true
        this.postApi.fetchPost(this.postId)
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
      if (sectionsErrors) {
        if (typeof sectionsErrors === 'string') {
          return this.$t('all_fields_must_be_filled')
        }

        return sectionsErrors[order]
      }

      return null
    },

    saveOrUpdate () {
      this.isBusy = true
      this.postEditor.saveOrUpdate(this.postId)
        .then((res) => {
          this.isBusy = false
          const post = res.data.data
          const postId = post.id

          if (!this.isEditing) {
            PostModel.create({
              data: res.data.data
            })
            this.$router.push({ name: 'editPost', params: { id: postId } })
          }
        })
        .catch(() => {
          this.isBusy = false
        })
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
