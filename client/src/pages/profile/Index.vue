<template>
  <div class="row justify-center q-mt-lg">
    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5">
      <q-card flat bordered>
        <q-item>
          <q-item-section avatar>
            <q-avatar>
              <user-avatar size="40px" />
            </q-avatar>
          </q-item-section>

          <q-item-section>
            <q-item-label>{{ me.login }}</q-item-label>
          </q-item-section>
        </q-item>

        <q-separator />

        <q-card-section horizontal>
          <q-card-section>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis consectetur id perspiciatis quae recusandae. Consequuntur delectus laboriosam magnam qui voluptate. At corporis exercitationem fuga fugiat laborum maxime officia quasi qui.
          </q-card-section>

          <q-separator vertical />

          <q-card-section class="col-4">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
          </q-card-section>
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>

<script>
import Me from 'src/models/user/me'
import UserAvatar from 'components/common/UserAvatar'

export default {
  name: 'Account',

  components: {
    UserAvatar
  },

  data () {
    return {
      me: {}
    }
  },

  created () {
    this.me = Me.query().first()
  }
}
</script>
