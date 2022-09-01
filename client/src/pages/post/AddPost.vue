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
              v-model="model.title"
              :label="$t('title')"

              :error="!!validator.errors.title"
              :error-message="validator.errors.title"

              @input="validator.resetFieldError('title')"
          />

          <!-- Movement, Deleting Section, Content -->
          <div class="ap-body">
            <div
                v-for="(section, index) in model.data"
                :key="'body-element' + section.index"
            >
              <!-- Deleting Section -->
              <div v-if="model.data.length > 1" class="flex q-my-sm">
                <!-- Deleting -->
                <tooltip-icon @click="deleteSection(index)" :tooltip="$t('delete_section')" icon-name="delete"/>
              </div>

              <!-- Content -->
              <component
                  :ref="'editor[' + section.index + ']'"
                  :is="section.type + '-field'"
                  :value="section.model"
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
              @click="addSection"
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

import Validator from 'src/plugins/Validator'
import TooltipIcon from 'components/common/TooltipIcon'
import IconWithTooltip from 'components/common/IconWithTooltip'
import PostApi from 'src/plugins/api/post'

const formModel = {
  title: 'Test Title',
  data: [
    { index: 1, type: 'text', model: 'Section 1' },
    { index: 2, type: 'text', model: 'Section 2' }
  ]
}

export default {
  name: 'AddPost',

  components: {
    TooltipIcon,
    TextField,
    IconWithTooltip
  },

  data () {
    return {
      model: JSON.parse(JSON.stringify(formModel)),
      validator: new Validator(formModel),
      postApi: new PostApi()
    }
  },

  methods: {
    addSection () {
      const lastIndex = this.model.data[this.model.data.length - 1].index + 1
      this.model.data.push({
        index: lastIndex,
        type: 'text',
        model: 'Section ' + lastIndex
      })
    },

    deleteSection (index) {
      this.model.data.splice(index, 1)
    },

    saveOrUpdate () {
      this.postApi.store(this.model)
    }
  }
}
</script>
