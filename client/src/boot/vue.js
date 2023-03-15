import VueYouTubeEmbed from 'vue-youtube-embed'

export default ({ Vue }) => {
  Vue.prototype.$cl = (val) => { console.log(val) }
  Vue.use(VueYouTubeEmbed)
}
