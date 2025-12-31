/**
 * Encapsulates standard functionality for templated forms
 * You need to implement the fields, the rest is automatic.
 */
export const templatedForm = {
  emits: ['update'],
  data () {
    return {
      fields: {},
      entity: {}
    }
  },
  props: {
    template: {
      type: Object,
      default: {}
    }
  },
  watch: {
    updatePayload: {
      deep: true,
      immediate: true,
      handler: function () {
        this.$emit('update', this.updatePayload)
      }
    },
    template: {
      immediate: true,
      handler: function (newTemplate) {
        this.entity = createEntityFromTemplate(this.fields, newTemplate, this.entity)
      }
    }
  },
  methods: {
    blurField: function (field) {
      this.fields[field].dirty = true
    },
    validateField: function (field) {
      validateField(this.fields[field], this.entity[field])
    },
    validateAndBlurField: function (field) {
      this.validateField(field)
      this.blurField(field)
    }
  },
  computed: {
    updatePayload: function () {
      return updatePayload(this.fields, this.entity, this.template)
    }
  }
}

export const createField = function () {
  const field = {
    dirty: false,
    rules: [],
    errors: []
  }

  for (let i = 0; i < arguments.length; i++) {
    field.rules.push(arguments[i])
  }

  return field
}

export const requiredRule = {
  isValid: function (value) {
    if (Array.isArray(value)) {
      return value.length > 0
    }

    return !!value
  },
  errorMessage: '_validation.required'
}

export const ahvNumberRule = {
  isValid: function (value) {
    if (!value) {
      return true
    }

    const digits = value.split('').map(d => parseInt(d, 10));
    if (digits.length !== 13) {
      return false;
    }

    const checksum = digits[12];

    let sum = 0;
    let multiplier = 3;
    for (let i = 11; i >= 0; i--) {
      sum += digits[i] * multiplier;
      multiplier = (multiplier === 3) ? 1 : 3;
    }

    return (sum + checksum) % 10 === 0;
  },
  errorMessage: '_validation.invalid_ahv'
}

const emailRegex = /^\S+@\S+\.\S+$/
export const emailRule = {
  isValid: function (value) {
    if (!value) {
      return true
    }

    return emailRegex.test(value)
  },
  errorMessage: '_validation.not_an_email'
}

export const countryCode = {
  isValid: function (value) {
    if (!value) {
      return true
    }

    return value.toUpperCase() === value && value.length >= 1 && value.length <= 3
  },
  errorMessage: '_validation.not_a_country_code'
}

const sectionNumberRegex = /^[0-9]+(\.[0-9]+){0,2}$/
export const sectionNumberRule = {
  isValid: function (value) {
    if (!value) {
      return true
    }

    return sectionNumberRegex.test(value)
  },
  errorMessage: '_validation.invalid_section_number'
}

export const emailsRule = {
  isValid: function (value) {
    if (!value) {
      return true
    }

    return !value.split('\n').some(entry => !emailRegex.test(entry))
  },
  errorMessage: '_validation.one_not_an_email'
}

const validateField = function (field, value) {
  field.errors = field.rules
    .filter(rule => !rule.isValid(value))
    .map(rule => rule.errorMessage)

  field.valid = field.dirty && field.errors.length === 0
  field.invalid = field.dirty && field.errors.length > 0
}

const createEntityFromTemplate = function (fields, template = null, entityDefault = null) {
  const newEntity = {}
  for (const fieldName in fields) {
    if (Object.prototype.hasOwnProperty.call(fields, fieldName)) {
      if (template && fieldName in template) {
        newEntity[fieldName] = template[fieldName]
      } else if (entityDefault && fieldName in entityDefault) {
        newEntity[fieldName] = entityDefault[fieldName]
      } else {
        newEntity[fieldName] = null
      }

      validateField(fields[fieldName], newEntity[fieldName])
    }
  }

  return newEntity
}

const updatePayload = function (fields, values, template = null) {
  const result = {}
  for (const fieldName in fields) {
    if (Object.prototype.hasOwnProperty.call(fields, fieldName)) {
      if (template && template[fieldName] === values[fieldName]) {
        continue
      }

      validateField(fields[fieldName], values[fieldName])
      if (fields[fieldName].errors.length > 0) {
        return null
      }

      result[fieldName] = values[fieldName]
    }
  }

  return result
}
