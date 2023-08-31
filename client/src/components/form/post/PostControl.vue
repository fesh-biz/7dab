<template>
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
        @click="$emit('preview')"
      />

      <!-- Cancel -->
      <icon-with-tooltip
        @click="$emit('cancel')"
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
        @click="$emit('save')"
      />
    </div>
  </q-card-section>
</template>

<script>
import IconWithTooltip from 'components/common/IconWithTooltip'
import PostEditor from 'src/plugins/editor/post'

export default {
  name: 'PostControl',
  components: { IconWithTooltip },
  props: {
    isBusy: {
      type: Boolean,
      required: true
    },
    canBeViewed: {
      type: Boolean,
      required: true
    }
  },

  data () {
    return {
      postEditor: new PostEditor()
    }
  }
}
</script>
