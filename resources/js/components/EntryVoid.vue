<template>
  <div ref="parent" contentEditable="false">
    <div
      class="border border-60 my-3 rounded-lg whitespace-normal"
      tabindex="1"
    >
      <div class="border-b border-60 px-3 py-2 flex justify-between">
        <h3 class="text-90 uppercase tracking-wide font-bold text-sm">{{ resourceTypeName() }}</h3>
        <button @mousedown="deleteEntry" active="false">
          <icon icon="close" />
        </button>
      </div>
      <div class="px-3 py-2">
        <a
          target="_blank"
          :href="url()"
          class="text-black text-justify no-underline dim"
        >
          {{ resource.display }}
        </a>
      </div>
    </div><!-- why there has an blank when using enter?/--><slot></slot>
  </div>
</template>

<script>
import { SelectedMixin, FocusedMixin } from 'slate-vue'
import Button from './Button'
import Icon from './Icon'
import { removeEntryVoid } from '../plugins/withEntry'

export default {
  name: 'EntryVoid',

  mixins: [SelectedMixin, FocusedMixin],

  props: ['resourceType', 'resource'],

  components: {
    Button,
    Icon,
  },

  methods: {
    resourceTypeName() {
      const resource = _.find(Nova.config.resources, { uriKey: this.resourceType })

      return resource?.singularLabel || this.resourceType
    },

    url() {
      return `${Nova.config.base}/resources/${this.resourceType}/${this.resource.value}`.replace('//', '/')
    },

    deleteEntry(event) {
      event.preventDefault()
      this.$refs.parent.click()
      removeEntryVoid(this.$editor)
    }
  }
}
</script>

<style>
div.selected {
  outline: 0;
  -webkit-box-shadow: 0 0 0 3px var(--primary-50);
  box-shadow: 0 0 0 3px var(--primary-50);
}
</style>
