<template>
  <div class="ml-auto">
    <button
      class="btn border border-60 rounded px-2 py-1 bg-white shadow text-primary hover:text-primary-dark"
      @click.prevent.stop="openModal"
    >
      {{ __('Link resource') }}
    </button>

    <portal
      to="modals"
      v-if="modalOpen"
    >
      <resource-modal
        :field="field"
        :resource-name="resourceName"
        @close="closeModal"
        @confirm="handleConfirm"
      />
    </portal>
  </div>
</template>

<script>
import { SlateMixin } from 'slate-vue'
import ResourceModal from './ResourceModal'
import { insertEntryVoid } from '../plugins/withEntry'

export default {
  mixins: [SlateMixin],
  props: ['field', 'resourceName'],

  components: {
    ResourceModal,
  },

  mounted() {
    this.selection = this.$editor.selection
  },

  data: () => ({
    modalOpen: false,
    value: null,
    selection: null,
  }),

  methods: {
    closeModal() {
      this.modalOpen = false
    },

    openModal() {
      this.selection = this.$editor.selection
      console.log(this.$editor)
      this.modalOpen = true
    },

    handleConfirm(value) {
      this.value = value
      insertEntryVoid(this.$editor, this.value, this.selection)
      this.modalOpen = false
    }
  }
}
</script>

<style scoped>
div.ml-auto {
  margin-left: auto !important;
}
</style>
