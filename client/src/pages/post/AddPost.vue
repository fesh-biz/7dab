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
              v-model="post.formModel.title"
              :label="$t('title')"

              :error="!!post.validator.errors.title"
              :error-message="post.validator.errors.title"

              @input="post.validator.resetFieldError('title')"
          />

          <!-- Movement, Deleting Section, Content -->
          <div class="ap-body">
            <div
                v-for="(section, index) in post.formModel.sections"
                :key="'body-element' + section.order"
            >
              <!-- Delete -->
              <div>
                <!-- Delete -->
                <icon-with-tooltip
                    @click="post.deleteSection(index)"
                    :tooltip="$t('delete_section')"
                    icon="delete"
                />
              </div>

              <!-- Content -->
              <component
                  :ref="'editor[' + section.order + ']'"
                  :is="section.type + '-field'"
                  :value="section.content"
                  :order="section.order"
                  :error-message="post.validator.errors.sections ? post.validator.errors.sections[section.order] : null"
                  @input="post.validator.resetFieldError('sections', section.order)"
              />

              <q-separator spaced/>
            </div>
          </div>
        </q-card-section>

        <!-- Post controls -->
        <q-card-section class="flex flex-center">
          <!-- Add Section -->
          <icon-with-tooltip
              :tooltip="$t('add_section')"
              color="positive"
              size="xl"
              icon="add_circle"
              @click="post.addSection()"
          />

          <!-- Cancel -->
          <icon-with-tooltip
              :tooltip="$t('cancel')"
              color="negative"
              size="xl"
              icon="cancel"
          />

          <!-- Save -->
          <icon-with-tooltip
              :tooltip="$t('save')"
              color="positive"
              size="xl"
              icon="check_circle"
              @click="saveOrUpdate()"
          />
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>

<script>
import TextField from 'components/form/post/TextField'

import TooltipIcon from 'components/common/TooltipIcon'
import IconWithTooltip from 'components/common/IconWithTooltip'
import Post from 'src/plugins/editor/post'

export default {
  name: 'AddPost',

  components: {
    TooltipIcon,
    TextField,
    IconWithTooltip
  },

  data () {
    return {
      post: new Post()
    }
  },

  computed: {
    sectionsErrors (val) {
      return this.post.validator.errors.sections
    }
  },

  created () {
    this.post.addSection()
  },

  methods: {
    saveOrUpdate () {
      this.post.saveOrUpdate()
        .then((res) => {
          // to make redirect on post page
        })
    }
  }
}
</script>
