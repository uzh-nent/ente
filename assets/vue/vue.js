import {createApp} from 'vue'
import {createI18n} from 'vue-i18n'
import moment from 'moment'
import Organizations from "./Organizations.vue";
import Patients from "./Patients.vue";
import AnimalKeepers from "./AnimalKeepers.vue";

// languages
import de from './localization/de.json'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Flatpickr from 'flatpickr'
import { German } from 'flatpickr/dist/l10n/de'

// directives
import { clickOutside, focus } from './services/directives'

// configure locales
moment.locale('de')
Flatpickr.localize(German)

// configure vue
function createVue(app) {
  const vue = createApp(app)
  vue.config.productionTip = false

  const i18n = createI18n({
    locale: 'de',
    fallbackLocale: 'de',
    globalInjection: true,
    messages: {de}
  })
  vue.use(i18n)

  vue.component('FontAwesomeIcon', FontAwesomeIcon)
  vue.directive('click-outside', clickOutside)
  vue.directive('focus', focus)

  return vue
}

if (document.getElementById('vue-organizations') != null) {
  createVue(Organizations).mount('#vue-organizations')
}

if (document.getElementById('vue-patients') != null) {
  createVue(Patients).mount('#vue-patients')
}

if (document.getElementById('vue-animal-keepers') != null) {
  createVue(AnimalKeepers).mount('#vue-animal-keepers')
}
