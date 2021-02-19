<template>
    <panel-item :field="field">
      <template slot="value">
        <Slate :value="initialValue">
          <Editable
            :readOnly="true"
            :renderLeaf="renderLeaf"
            :renderElement="renderElement"
            class="slate-editable"
          />
        </Slate>
      </template>
    </panel-item>
</template>

<script>
import EditableField from '../../mixins/EditableField'

export default {
  mixins: [EditableField],

  props: ['resource', 'resourceName', 'resourceId', 'field'],
}
</script>
