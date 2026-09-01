import {api} from "../../../services/api";
import {displaySuccess} from "../../../services/notifiers";

export const linkedEntityEditAction = {
  emits: ['update'],
  data() {
    return {
      useReference: false,
      storeReference: true,
      reference: null,

      otherReferencedOpenProbes: [],

      patch: null,
    }
  },
  props: {
    entity: {
      type: Object,
      required: true
    },
    probe: {
      type: Object,
      required: false
    },
    canUnlink: {
      type: Boolean,
      default: false
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
      return (this.hasPatch || this.useReference) || (this.referenceIsDifferent && this.storeReference)
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
    },
    abort: function () {
      if (!this.canUnlink) {
        return null
      }

      return this.unlink
    },
  },
  methods: {
    confirm: async function () {
      if (this.storeReference && this.referenceIsDifferent) {
        await api.patch(this.reference, this.payload)

        const successMessage = this.$t('_action.edit_reference.edited')
        displaySuccess(successMessage)
      }

      this.$emit('update', this.payload)
      this.useReference = false
      this.storeReference = true
    },
    unlink: async function () {
      this.$emit('update', null)
      this.useReference = false
      this.storeReference = true
    },
    reloadReference: async function () {
      this.reference = await api.get(this.entity['@id'])
    },
  }
}

export const createCleanPatch = function (probe, uncleanPatch) {
  const patch = {}
  for (const key in uncleanPatch) {
    if (!uncleanPatch.hasOwnProperty(key)) {
      continue
    }

    if (probe && uncleanPatch[key] === probe[key]) {
      continue
    }

    if (!probe && !uncleanPatch[key]) {
      continue
    }

    patch[key] = uncleanPatch[key]
  }

  return (Object.keys(patch).length === 0) ? null : patch
}
