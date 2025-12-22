<template>
    <div class="form-control form-control-dropzone" :class="{'can-drop': isDropTarget, 'is-valid': isDropTarget && validDropDetected, 'is-invalid': (isDropTarget && invalidDropDetected) || invalidPasteDetected}"
         @drag.stop.prevent="" @dragstart.stop.prevent=""
         @dragover.stop.prevent="isDropTarget = true" @dragenter.stop.prevent="dropAreaEntered"
         @dragleave.stop.prevent="isDropTarget = false" @dragend.stop.prevent="isDropTarget = false"
         @drop.stop.prevent="fileDropped"
    >
      <label class="form-control-dropzone-hint">
        <span class="text-center">
          <span class="d-block">{{ help }}</span>

          <span v-if="invalidPasteDetected" class="text-danger d-block text-center mt-2">
            {{ $t('_library.invalid_paste') }}
          </span>
          <span v-if="noAccessToClipboard" class="text-danger d-block text-center mt-2">
            {{ $t('_library.cannot_access_clipboard') }}
          </span>
          <span v-if="lastInvalidFileType && isDropTarget" class="text-danger d-block text-center mt-2">
            {{ lastInvalidFileType }}
          </span>
        </span>
        <input type="file" :id="id" @change="$emit('input', $event.target.files)">
      </label>
    </div>
    <button type="button" class="btn btn-outline-secondary btn-sm mt-2" @click.stop.prevent="pasteRequested">
      {{ $t('_library.paste_from_clipboard') }}
      <font-awesome-icon :icon="['fal', 'paste']" />
    </button>
</template>

<script>

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { DateTime } from 'luxon'

export default {
  components: { FontAwesomeIcon },
  emits: ['input'],
  data () {
    return {
      isDropTarget: false,
      validDropDetected: false,
      invalidDropDetected: false,
      invalidPasteDetected: false,
      noAccessToClipboard: false,
      lastInvalidFileType: null
    }
  },
  props: {
    id: {
      type: String,
      required: true
    },
    help: {
      type: String,
      required: true
    },
    validFileTypes: {
      type: Array,
      required: true
    }
  },
  methods: {
    pasteRequested: async function () {
      try {
        const clipboardItems = await navigator.clipboard.read()
        this.noAccessToClipboard = false

        for (const item of clipboardItems) {
          for (const fileType of this.validFileTypes) {
            console.log(fileType, item, item.types.includes(fileType))
            if (item.types.includes(fileType)) {
              const blob = await item.getType(fileType)
              const filename = this.$t('_library.clipboard') + '_' + DateTime.now().toFormat('yyyyLLdd-hhmmss') + '.' + fileType.split('/')[1]
              console.log(filename, blob)
              const file = new File([blob], filename, { type: fileType })
              this.invalidPasteDetected = false
              this.$emit('input', [file])
              return
            }
          }
        }

        this.invalidPasteDetected = true
      } catch (err) {
        this.noAccessToClipboard = true
      }
    },
    dropAreaEntered: function (event) {
      this.invalidPasteDetected = false

      this.isDropTarget = true
      const files = event.dataTransfer.items
      if (files.length === 0) {
        return
      }

      let lastInvalidFileType = null
      Array.from(files).forEach(file => {
        if (!this.validFileTypes.some(e => file.type === e)) {
          lastInvalidFileType = file.type
        }
      })

      this.validDropDetected = !lastInvalidFileType
      this.invalidDropDetected = !this.validDropDetected
      this.lastInvalidFileType = lastInvalidFileType
    },
    fileDropped: function (event) {
      this.isDropTarget = false
      if (this.validDropDetected) {
        this.$emit('input', event.dataTransfer.files)
      }
    }
  }
}
</script>
