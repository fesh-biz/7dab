<template>
  <q-card :flat="$q.platform.is.mobile" class="q-my-md">
    <!-- Post Form -->
    <post-form />

    <!-- Main Error Message -->
    <q-card-section v-if="postEditor.validator.mainErrorMessage" class="flex justify-center">
      <div
        class="main-error-message"
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

    <q-card-section v-if="me.id === 1">
      <select-fake-user v-model="postEditor.fake_user_id" />
    </q-card-section>

    <post-control
      :is-busy="isBusy"
      :can-be-viewed="canBeViewed"
      @preview="$router.push({name: 'postPage', params: {id: postId}})"
      @cancel="goBack"
      @save="saveOrUpdate"
    />
  </q-card>
</template>

<script>
import PostEditor from 'src/plugins/editor/post'
import PostModel from 'src/models/content/post'
import PostApi from 'src/plugins/api/post'
import Me from 'src/models/user/me'
import SelectFakeUser from 'components/fake-user/SelectFakeUser'
import PostControl from 'components/form/post/PostControl'
import PostForm from 'components/form/post/PostForm'

export default {
  name: 'AddPost',

  components: {
    PostForm,
    PostControl,
    SelectFakeUser
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
    me () {
      return Me.query().first() || {}
    },

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
    if (!this.me.is_verified) {
      this.$root.$emit('verify-email')
    }

    this.postEditor.resetFormModel()
    setTimeout(() => {
      this.postEditor.addSection('text')
    }, 10)
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

    saveOrUpdate () {
      this.isBusy = true
      this.postEditor.saveOrUpdate(this.postId)
        .then((res) => {
          this.isBusy = false
          const post = res.data
          const postId = post.id

          if (!this.isEditing) {
            PostModel.insert({
              data: post
            })
            this.$router.push({ name: 'editPost', params: { id: postId } })
          }

          this.$q.notify({
            message: this.$t('success'),
            position: 'center',
            color: 'positive'
          })
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
