<template>
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

    <!-- Hint of Available Files -->
    <div
      v-if="hasMediaField"
      style="width: 100%"
      class="hint q-mb-md"
    >
      Допустимі файли:
      <strong>.jpg, .jpeg, .gif, .webp, .png, .webm, .mp4</strong><br />
      Розмір зображень не більше 5Mb <br />
      Розмір GIF не більше 50Mb <br />
      Розмір відео не більше 100Mb <br />
      Загальний обсяг файлів не має перевищувати 300Mb
    </div>

    <!-- Tags -->
    <tag-field
      @input="updatePostTags"
      :error-message="tagError"
    />
  </q-card-section>
</template>

<script>
import Post from 'src/plugins/editor/post'
import IconWithTooltip from 'components/common/IconWithTooltip'
import YouTubeField from 'components/form/common/YouTubeField'
import TextField from 'components/form/post/TextField'
import ImageField from 'components/form/post/ImageField'
import TagField from 'components/form/post/TagField'
import MediaField from 'components/form/common/MediaField'

export default {
  name: 'PostForm',

  components: { TagField, YouTubeField, IconWithTooltip, TextField, ImageField, MediaField },

  data () {
    return {
      postEditor: new Post()
    }
  },

  computed: {
    tagError () {
      return this.postEditor.validator.errors.tags || null
    },

    hasMediaField () {
      let res = false

      for (const section of this.postEditor.formModel.sections) {
        if (section.type === 'media') {
          res = true
          break
        }
      }

      return res
    }
  },

  methods: {
    getSectionError (order) {
      const sectionsErrors = this.postEditor.validator.errors.sections
      if (sectionsErrors && typeof sectionsErrors !== 'string') {
        return sectionsErrors[order]
      }

      return null
    },

    updatePostTags () {
      this.postEditor.validator.resetFieldError('tags')
    }
  }
}
</script>
