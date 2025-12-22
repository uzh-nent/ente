import {createApp} from 'vue'
import {createI18n} from 'vue-i18n'
import moment from 'moment'
import Organizations from "./Organizations.vue";

// languages
import de from './localization/de.json'

// settings
const locale = document.documentElement.lang.slice(0, 2)

// configure moment
moment.locale(locale)

// configure vue
function createVue(app) {
  const vue = createApp(app)
  vue.config.productionTip = false

  const i18n = createI18n({
    locale,
    fallbackLocale: 'de',
    globalInjection: true,
    messages: {de}
  })
  vue.use(i18n)

  return vue
}

if (document.getElementById('vue-organizations') != null) {
  createVue(Organizations).mount('#vue-organizations')
}
