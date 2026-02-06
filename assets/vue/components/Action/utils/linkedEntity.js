import {api} from "../../../services/api";
import {displaySuccess} from "../../../services/notifiers";

export const linkedEntityEditAction = {
  emits: ['update'],
  data() {
    return {
      useReference: false,
      storeReference: false,
      reference: null,

      patch: null,
    }
  },
  props: {
    entity: {
      type: Object,
        required: true
    },
  },
  computed: {
    template: function () {
      if (this.useReference) {
        return this.reference
      } else {
        return this.entity
      }
    },
    hasPatch: function () {
      return (this.patch && Object.keys(this.patch).length > 0)
    },
    canConfirm: function () {
      return this.hasPatch || this.useReference
    },
    payload: function () {
      return {...this.template, ...this.patch}
    },
    referenceIsDifferent: function () {
      if (!this.reference) {
        return false;
      }

      for (const field of this.entityFields) {
        if (this.payload[field] != this.reference[field]) {
          return true
        }
      }

      return false
    }
  },
  methods: {
    confirm: async function () {
      if (this.storeReference) {
        await api.patch(this.reference, this.payload)

        const successMessage = this.$t('_action.edit_reference.edited')
        displaySuccess(successMessage)
      }

      this.$emit('update', this.payload)
      this.useReference = this.storeReference = false
    },
    reloadReference: async function () {
      this.reference = await api.get(this.entity['@id'])
    },
  }
}
