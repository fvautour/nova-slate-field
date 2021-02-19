<template>
  <default-field
    :field="field"
    :errors="errors"
    :full-width-content="true"
    :show-help-text="showHelpText"
  >
    <template slot="field">
      <Slate
        :id="field.name"
        :value="initialValue"
        @onChange="handleEditableChange"
      >
        <slate-toolbar>
          <slate-block-button format="heading-one" icon="looks_one" />
          <slate-block-button format="heading-two" icon="looks_two" />
          <slate-block-button format="heading-three" icon="looks_3" />
          <slate-block-button format="heading-four" icon="looks_4" />
          <slate-block-button format="heading-five" icon="looks_5" />
          <slate-block-button format="heading-six" icon="looks_6" />
          <toolbar-separator />
          <slate-mark-button format="bold" icon="format_bold" />
          <slate-mark-button format="italic" icon="format_italic" />
          <slate-mark-button format="underline" icon="format_underlined" />
          <slate-mark-button format="code" icon="code" />
          <toolbar-separator />
          <slate-link-button />
          <toolbar-separator />
          <slate-block-button format="numbered-list" icon="format_list_numbered" />
          <slate-block-button format="bulleted-list" icon="format_list_bulleted" />
          <slate-block-button format="block-quote" icon="format_quote" />
          <slate-resource-button
            v-if="canLinkEntry"
            :field="field.entry_field"
            :resource-name="resourceName"
            class="ml-auto"
          />
        </slate-toolbar>
        <Editable
          placeholder="Enter some plain text..."
          :renderLeaf="renderLeaf"
          :renderElement="renderElement"
          class="form-input-bordered px-4 py-2 rounded-t-none slate-editable"
        />
      </Slate>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import EditableField from '../../mixins/EditableField'
import ToolbarSeparator from './../ToolbarSeparator'

export default {
  mixins: [FormField, HandlesValidationErrors, EditableField],

  props: ['resourceName', 'resourceId', 'field'],

  components: {
    ToolbarSeparator,
  },

  computed: {
    canLinkEntry() {
      return this.field.entry_field !== undefined && this.field.entry_field !== null
    }
  },

  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || '[{"children":[{"text":""}]}]'
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, JSON.stringify(this.$editor.children))
    },

    handleEditableChange() {
      this.value = JSON.stringify(this.$editor.children)

      if (this.field) {
        Nova.$emit(this.field.attribute + '-change', this.value)
      }
    }
  },
}
</script>
