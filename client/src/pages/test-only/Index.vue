<template>
  <div class="row flex justify-center">
    <div class="col-8">
      <img src="https://cs1.terevenky.com/test/image1.jpg">
    </div>
  </div>
</template>

<script>
// import ModelSync from 'pages/test-only/syncing/ModelSync'
// import SyncTesting from 'pages/test-only/syncing/SyncTesting'
import _ from 'lodash'
import Post from 'src/models/content/post'

export default {
  name: 'Index',

  components: {
    // SyncTesting,
    // ModelSync
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

      const str = 'Vuex is a state management library for Vue.js applications. It provides a centralized store that enables you to manage the state of your application in a predictable and maintainable way. Here are some of the key features of Vuex:\n' +
        '\n' +
        'Centralized state management: Vuex provides a single source of truth for your application\'s state. Instead of having multiple components manage their own state independently, you can store the state in the Vuex store and access it from any component that needs it.\n' +
        '\n' +
        'Predictable state changes: In Vuex, you modify the state through mutations, which are synchronous functions that receive the current state and a payload as arguments. By using mutations to update the state, you can ensure that all changes to the state are tracked in a predictable way.\n' +
        '\n' +
        'Computed state properties: Vuex allows you to define computed properties that are derived from the state of your application. These computed properties are cached and only re-evaluated when their dependencies change, which can improve performance.\n' +
        '\n' +
        'Actions and async state changes: In addition to mutations, Vuex provides actions, which are asynchronous functions that can perform complex operations, such as making API calls. Actions can also commit mutations to update the state.\n' +
        '\n' +
        'Module system: If your application state becomes large and complex, you can organize it into modules, each with its own state, mutations, actions, and getters. This can help you manage the complexity of your application and make it easier to maintain.\n' +
        '\n' +
        'By providing a centralized store and a set of rules for modifying the state, Vuex helps you manage the complexity of your application\'s state and make it easier to reason about. It can also improve the performance of your application by caching computed properties and optimizing state updates.'

      for (let i = 0; i < 10000; i++) {
        bigObj[i] = {
          string: str,
          subObj: {
            string: str,
            subObj: {
              string: str,
              id: 1,
              subObj: {
                string: str,
                subObj: {
                  string: str,
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
