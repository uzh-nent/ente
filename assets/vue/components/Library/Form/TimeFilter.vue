<template>
</template>


<script>

import FormField from "../FormLayout/FormField";

export default {
  components: {
    FormField,
  },
  emits: [
    'input-before',
    'input-after',
  ],
  props: {
    label: {
      type: String,
      required: true
    },
    initialBefore: {
      type: String,
      required: false
    },
    initialAfter: {
      type: String,
      required: false
    },
    allowFuture: {
      type: Boolean,
      default: true
    },
    help: {
      type: String,
      required: false
    }
  },
  data () {
    return {
      before: null,
      after: null
    }
  },
  watch: {
    before: function () {
      this.$emit('input-before', this.normalize(this.before))
    },
    after: function () {
      this.$emit('input-after', this.normalize(this.after))
    }
  },
  methods: {
    normalize: function (value) {
      if (!value) {
        return null
      }

      return value
    }
  },
  mounted () {
    if (this.initialBefore) {
      this.before = this.initialBefore
    }
    if (this.initialAfter) {
      this.after = this.initialAfter
    }
  }
}
</script>
