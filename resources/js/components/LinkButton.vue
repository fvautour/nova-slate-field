<script>
import { SlateMixin } from 'slate-vue'
import { Editor } from 'slate'
import { wrapLink, unwrapLink, isLinkActive } from '../plugins/withLinks'

export default {
  mixins: [SlateMixin],

  methods: {
    handleClick(event) {
      event.preventDefault()
      const [link] = Editor.nodes(this.$editor, {
        match: n => n.type === 'link'
      })

      const url = window.prompt('Enter the URL of the link:', link?.[0]?.url)

      if (!url || ! url.length) {
        unwrapLink(this.$editor)
      } else {
        wrapLink(this.$editor, url)
      }
    }
  },

  render() {
    return (
      <slate-button
        active={isLinkActive(this.$editor)}
        onMouseDown={event => this.handleClick(event)}
      >
        <slate-icon icon='link'></slate-icon>
      </slate-button>
    )
  }
}
</script>

<style scoped>
</style>
