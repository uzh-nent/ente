// i18n
import {createApp} from 'vue'
import {createI18n} from 'vue-i18n'
import de from './localization/de.json'

// flatpickr
import Flatpickr from 'flatpickr'
import {German} from 'flatpickr/dist/l10n/de'

// libraries & directives
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import moment from 'moment'
import $ from "jquery";
import {clickOutside, focus} from './services/directives'

// components
import NewProbe from "./NewProbe.vue";
import ViewProbe from "./ViewProbe.vue";
import Organizations from "./Organizations.vue";
import Practitioners from "./Practitioners.vue";
import Patients from "./Patients.vue";
import AnimalKeepers from "./AnimalKeepers.vue";


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

$(document).ready(function () {
  if (document.getElementById('vue-organizations') != null) {
    createVue(Organizations).mount('#vue-organizations')
  }

  if (document.getElementById('vue-practitioners') != null) {
    createVue(Practitioners).mount('#vue-practitioners')
  }

  if (document.getElementById('vue-patients') != null) {
    createVue(Patients).mount('#vue-patients')
  }

  if (document.getElementById('vue-animal-keepers') != null) {
    createVue(AnimalKeepers).mount('#vue-animal-keepers')
  }

  if (document.getElementById('vue-probe-new') != null) {
    createVue(NewProbe).mount('#vue-probe-new')
  }

  if (document.getElementById('vue-probe-view') != null) {
    createVue(ViewProbe).mount('#vue-probe-view')
  }
})
