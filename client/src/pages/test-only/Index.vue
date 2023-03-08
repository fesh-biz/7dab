<template>
  <div class="row flex justify-center">
    <div class="col-8">
      <p>Index: {{ model }}</p>
      <sync-testing :variable.sync="model"/>
      <model-sync v-model="model"/>
    </div>
  </div>
</template>

<script>
import ModelSync from 'pages/test-only/syncing/ModelSync'
import SyncTesting from 'pages/test-only/syncing/SyncTesting'
import _ from 'lodash'
import Post from 'src/models/content/post'

export default {
  name: 'Index',

  components: {
    SyncTesting,
    ModelSync
  },

  data () {
    return {
      model: 'Start val'
    }
  },

  created () {
    this.bigDataTestDeepClone()
  },

  methods: {
    bigDataTestDeepClone () {
      let bigObj = {}
      for (let i = 0; i < 10000; i++) {
        bigObj[i] = {
          string: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam animi doloribus, expedita impedit minima perspiciatis possimus temporibus velit. Accusantium architecto blanditiis dolorem harum iste non pariatur quisquam sequi tempore velit?',
          subObj: {
            string: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam animi doloribus, expedita impedit minima perspiciatis possimus temporibus velit. Accusantium architecto blanditiis dolorem harum iste non pariatur quisquam sequi tempore velit?',
            subObj: {
              string: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam animi doloribus, expedita impedit minima perspiciatis possimus temporibus velit. Accusantium architecto blanditiis dolorem harum iste non pariatur quisquam sequi tempore velit?',
              id: 1,
              subObj: {
                string: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam animi doloribus, expedita impedit minima perspiciatis possimus temporibus velit. Accusantium architecto blanditiis dolorem harum iste non pariatur quisquam sequi tempore velit?',
                subObj: {
                  string: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam animi doloribus, expedita impedit minima perspiciatis possimus temporibus velit. Accusantium architecto blanditiis dolorem harum iste non pariatur quisquam sequi tempore velit?',
                  id: 1
                }
              }
            }
          }
        }
      }

      console.time('deepClone test')
      bigObj = _.cloneDeep(bigObj)
      console.timeEnd('deepClone test')
    },

    async vuexOrmTest () {
      await Post.insertOrUpdate({
        data: {
          id: 1,
          title: 'test',
          user_id: 1,
          user: {
            id: 1,
            login: 'Login'
          }
        }
      })

      await Post.insertOrUpdate({
        data: [
          {
            id: 1,
            title: 'test',
            user_id: 1,
            user: {
              id: 1,
              login: 'Login',
              fetched_by: 'temporary_route'
            }
          }
        ]
      })

      await Post.insertOrUpdate({
        data: {
          id: 2,
          title: 'test',
          user_id: 2,
          user: {
            id: 2,
            login: 'Login'
          }
        }
      })

      await Post.delete(2, { relations: true })

      console.log(Post.query().withAll().get())
    }
  }
}
</script>
