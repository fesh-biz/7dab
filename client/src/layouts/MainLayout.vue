<template>
  <q-layout view="lHh Lpr lFf">
    <nav-header />

    <q-page-container style="padding-top: unset">
      <q-page>
        <div class="row justify-center q-px-sm">
          <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5">
            <router-view/>
          </div>
        </div>
      </q-page>
    </q-page-container>

    <verify-email />
  </q-layout>
</template>

<script>
import VerifyEmail from 'components/layout/VerifyEmail'
import NavHeader from 'components/navbar/NavHeader'

export default {
  name: 'MainLayout',
  components: {
    NavHeader,
    VerifyEmail
  },
  data () {
    return {}
  }
}
</script>

<style lang="sass">
#menuButton
  transition: all 0.3s ease-in-out

.ml-header.fixed-top
  left: unset
  background-color: unset

.ml-menu
  .q-btn__wrapper
    &:before
      background-color: #ffffff
</style>
