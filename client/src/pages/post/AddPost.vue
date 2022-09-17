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
              v-model="postEditor.title"
              :label="$t('title')"

              :error="!!postEditor.validator.errors.title"
              :error-message="postEditor.validator.errors.title"

              @input="postEditor.validator.resetFieldError('title')"
          />

          <!-- Movement, Deleting Section, Content -->
          <div class="ap-body">
            <div
                v-for="(section, index) in postEditor.sections"
                :key="'body-element' + section.order"
            >
              <!-- Deleting Section -->
              <div v-if="postEditor.sections.length > 1" class="flex q-my-sm">
                <!-- Deleting -->
                <tooltip-icon @click="postEditor.deleteSection(index)" :tooltip="$t('delete_section')"
                              icon-name="delete"/>
              </div>

              <!-- Content -->
              <component
                  :ref="'editor[' + section.order + ']'"
                  :is="section.type + '-field'"
                  :value="section.content"
                  :order="section.order"
              />
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
              @click="postEditor.addSection()"
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
import PostApi from 'src/plugins/api/post'
import PostEditor from 'src/plugins/editor/post'

export default {
  name: 'AddPost',

  components: {
    TooltipIcon,
    TextField,
    IconWithTooltip
  },

  data () {
    return {
      postApi: new PostApi(),
      postEditor: new PostEditor()
    }
  },

  created () {
    this.postEditor.addSection()
  },

  methods: {
    saveOrUpdate () {
      this.postApi.store({
        title: this.postEditor.title,
        data: this.postEditor.sections
      })
        .then(res => console.log('res', res))
    }
  }
}
</script>
