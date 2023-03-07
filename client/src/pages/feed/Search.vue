<template>
  <div class="row justify-center q-pt-lg">
    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5">
      <q-card>
        <q-card-section>
          <div class="text-h6">{{ $t('search') }}</div>
        </q-card-section>

        <!-- Search Form -->
        <q-card-section>
          <!-- Text -->
          <q-input
            class="q-mb-lg"
            outlined
            v-model="formModel.keyword"
            :label="$t('word_or_phrase')"
          />

          <tag-field
            v-model="formModel.tags"
          />
        </q-card-section>

        <!-- Controls -->
        <q-card-actions class="q-pa-md">
          <q-btn color="positive" no-caps :label="$t('search')"/>
        </q-card-actions>
      </q-card>
    </div>

    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5">
      {{ formModel.tags }}
    </div>
  </div>
</template>

<script>
import TagField from 'components/form/common/TagField'
import _ from 'lodash'

const formModel = {
  tags: [],
  keyword: ''
}

export default {
  name: 'Search',

  components: {
    TagField
  },

  data () {
    return {
      formModel: _.cloneDeep(formModel)
    }
  },

  computed: {
    posts () {
      return []
    }
  }
}
</script>
