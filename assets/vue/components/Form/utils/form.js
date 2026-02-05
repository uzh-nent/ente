export const componentForm = {
  emits: ['update'],
  data () {
    return {
      components: {},
      componentTemplate: {},
      componentEntity: {}
    }
  },
  props: {
    template: {
      type: Object,
      required: false
    }
  },
  watch: {
    template: {
      immediate: true,
      handler: function (newTemplate) {
        this.componentTemplate = createComponentTemplateFromTemplate(this.components, newTemplate)
      }
    },
    updatePayload: {
      deep: true,
      immediate: true,
      handler: function (updatePayload) {
        this.$emit('update', updatePayload)
      }
    },
  },
  computed: {
    updatePayload: function () {
      return updatePayloadFromComponentEntity(this.components, this.componentEntity)
    },
  },
}

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
      required: false
    }
  },
  watch: {
    template: {
      immediate: true,
      handler: function (newTemplate) {
        this.entity = createEntityFromTemplate(this.fields, newTemplate)
      }
    },
    updatePayload: {
      immediate: true,
      handler: function (updatePayload) {
        this.$emit('update', updatePayload)
      }
    },
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

export const ahvNumberLengthRule = {
  isValid: function (value) {
    if (!value) {
      return true
    }

    const digits = value.split('').map(d => parseInt(d, 10));
    return digits.length === 13
  },
  errorMessage: '_validation.invalid_ahv_length'
}

export const ahvNumberCheckRule = {
  isValid: function (value) {
    if (!value) {
      return true
    }

    const digits = value.split('').map(d => parseInt(d, 10));
    if (digits.length !== 13) {
      return true;
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
  errorMessage: '_validation.invalid_ahv_checksum'
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

    return value.toUpperCase() === value && value.length >= 2 && value.length <= 3
  },
  errorMessage: '_validation.not_a_country_code'
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

const createEntityFromTemplate = function (fields, template = null) {
  const newEntity = {}
  for (const fieldName in fields) {
    if (Object.prototype.hasOwnProperty.call(fields, fieldName)) {
      if (template && fieldName in template) {
        newEntity[fieldName] = template[fieldName]
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
      validateField(fields[fieldName], values[fieldName])
      if (fields[fieldName].errors.length > 0) {
        return null
      }

      if (template && fieldName in template && template[fieldName] === values[fieldName]) {
        continue
      }

      result[fieldName] = values[fieldName]
    }
  }

  return result
}


const createComponentTemplateFromTemplate = function (components, template = null) {
  const componentEntity = {}
  for (const componentName in components) {
    if (Object.prototype.hasOwnProperty.call(components, componentName)) {
      const newComponent = {}
      for (const fieldName of components[componentName]) {
        if (template && fieldName in template) {
          newComponent[fieldName] = template[fieldName]
        } else {
          newComponent[fieldName] = null
        }
      }

      componentEntity[componentName] = newComponent
    }
  }

  return componentEntity
}

const updatePayloadFromComponentEntity = function (components, componentEntity) {
  const result = {}
  for (const componentName in components) {
    if (Object.prototype.hasOwnProperty.call(components, componentName)) {
      if (!componentEntity[componentName]) {
        return null
      }

      for (const fieldName of components[componentName]) {
        if (fieldName in componentEntity[componentName]) {
          result[fieldName] = componentEntity[componentName][fieldName]
        }
      }
    }
  }

  return result
}
