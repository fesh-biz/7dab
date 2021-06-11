<template>
  <div class="row justify-center q-px-sm">
    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5 q-mt-md">
      <q-card :flat="$q.platform.is.mobile" class="q-my-md">
        <!-- Title -->
        <q-card-section class="text-center">
          <span class="text-h5">{{ $t('post_adding') }}</span>
        </q-card-section>

        <!-- Post Form -->
        <q-card-section>
          <!-- title -->
          <q-input
            outlined=""
            dense=""
            v-model="form.title"
            :label="$t('title')"

            :error="!!validator.errors.title"
            :error-message="validator.errors.title"

            @input="validator.resetFieldError('title')"
          />

          <!-- body -->
          <div class="ap-body">
            <div
              v-for="(bodySection, index) in form.bodySections"
              :key="'body-element' + bodySection.index"
            >
              <!-- Buttons -->
              <div v-if="form.bodySections.length > 1" class="flex q-my-sm">
                <tooltip-icon @mouseup="movingEnded" v-touch-pan.prevent.mouse="($event) => moveSection($event, bodySection.index)" :tooltip="$t('move_vertically')" icon-name="swap_vert" />
                <tooltip-icon @click="deleteSection(index)" :tooltip="$t('delete_section')" icon-name="delete" />
              </div>

              <editor
                :ref="'editor[' + bodySection.index + ']'"
                v-if="bodySection.type === 'text'"
                :value="bodySection.model"
              />
            </div>
          </div>
        </q-card-section>

        <!-- Post controls -->
        <q-card-section class="flex">
          <tooltip-icon @click="addSection" :tooltip="$t('add_section')" icon-name="add_circle"/>
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>

<script>
import Editor from 'components/form/Editor'

import Validator from 'src/plugins/Validator'
import TooltipIcon from 'components/common/TooltipIcon'

const formModel = {
  title: '',
  bodySections: [
    { index: 1, type: 'text', model: 'Section 1' },
    { index: 2, type: 'text', model: 'Section 2' }
  ]
}

export default {
  name: 'AddPost',

  components: {
    TooltipIcon,
    Editor
  },

  data () {
    return {
      form: JSON.parse(JSON.stringify(formModel)),
      validator: new Validator(formModel),
      movedSectionIndex: null
    }
  },

  methods: {
    addSection () {
      const lastIndex = this.form.bodySections[this.form.bodySections.length - 1].index + 1
      this.form.bodySections.push({
        index: lastIndex,
        type: 'text',
        model: 'Section ' + lastIndex
      })
    },

    deleteSection (index) {
      this.form.bodySections.splice(index, 1)
      console.log(this.form.bodySections)
    },

    moveSection (e, index) {
      // console.log(this.$refs)
      // console.log(e.position)
    },

    movingEnded (e) {
      console.log(e.position)
    }
  }
}
</script>
