import { Slate, Editable } from 'slate-vue'
import { renderLeaf, renderElement } from '../util/render'
import withLinks from '../plugins/withLinks'
import withEntry from '../plugins/withEntry'
import { withHistory } from 'slate-history'

export default {
  components: {
    Slate,
    Editable,
  },

  data: () => ({
    initialValue: null,
    renderElement,
    renderLeaf,
  }),
  
  beforeCreate() {
    withLinks(this.$editor)
    withHistory(this.$editor)
    withEntry(this.$editor)
  },

  created() {
    this.initialValue = this.field.value || '[{"children":[{"text":""}]}]'
  }
}
