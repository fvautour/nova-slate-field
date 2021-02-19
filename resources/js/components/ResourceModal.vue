<template>
  <modal
    tabindex="-1"
    role="dialog"
    @modal-close="handleClose"
  >
    <form
      autocomplete="off"
      @keydown="handleKeydown"
      @submit.prevent="handleConfirm"
      class="bg-white rounded-lg shadow-lg overflow-hidden w-action-fields"
    >
      <div>
        <heading :level="2" class="border-b border-40 py-8 px-8">{{
          __('Link resource')
        }}</heading>

        <div class="action">
          <component
            :is="'form-'+field.component"
            :field="field"
            :resource-name="resourceName"
            @change="handleSelectChange"
          />
        </div>
      </div>

      <div class="bg-30 px-6 py-3 flex">
        <div class="ml-auto">
          <button
            type="button"
            @click.prevent="handleClose"
            class="btn text-80 font-normal h-9 px-3 mr-3 btn-link"
          >
            {{ __('Cancel') }}
          </button>

          <button
            id="confirm-link-button"
            :disabled="working"
            type="submit"
            class="btn btn-default btn-primary"
          >
            <span>{{ __('Link resource') }}</span>
          </button>
        </div>
      </div>
    </form>
  </modal>
</template>

<script>
export default {
  props: ['field', 'resourceName'],

  data: () => ({
    working: true,
    value: null,
  }),

  mounted() {
    Nova.$on(this.field.attribute + '-change', value => {
      this.value = value
      this.working = value === null
    })
  },

  destroyed() {
    Nova.$off(this.field.attribute + '-change')
  },

  computed: {
    selectField() {
      return {
        attribute: 'recipe',
        name: this.__('Entry type'),
        morphToTypes: [
          {
            type: 'App\\Nova\\Recipe',
            singularLabel: 'Recipe',
            display: 'Recipe',
            value: 'recipes',
          }
        ],
      }
    }
  },

  methods: {
    handleKeydown(e) {
      if (['Escape', 'Enter'].indexOf(e.key) !== -1) {
        return
      }

      e.stopPropagation()
    },

    handleConfirm() {
      this.$emit('confirm', this.value)
    },

    handleClose() {
      this.$emit('close')
    },

    handleSelectChange(event) {
      console.log(event)
    }
  },
}
</script>

<style scoped>

</style>
