<template>
  <div :class="`text-${field.textAlign}`">
    <template v-if="hasValue">
      <span class="whitespace-no-wrap">{{ excerpt }}</span>
    </template>
    <p v-else>&mdash;</p>
  </div>
</template>

<script>
import { Node } from 'slate'

export default {
  props: ['resourceName', 'field'],

  computed: {
    excerpt() {
      let text = this.serialize(
        JSON.parse(this.field.value)
      )

      if (text.length <= this.limit) {
        return text
      }

      return `${text.substring(0, this.limit - 1)}...`;
    },

    limit() {
      return this.field.limit
    }
  },

  methods: {
    /**
     * Determine if the field has a value other than null.
     */
    hasValue() {
      return this.field.value !== null
    },

    serialize(nodes) {
      return nodes.map(n => Node.string(n)).join(' ')
    },
  },
}
</script>
