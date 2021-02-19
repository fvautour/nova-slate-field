<script>
  import {Editor} from 'slate'
  import {SlateMixin} from 'slate-vue';
  const isMarkActive = (editor, format) => {
    const marks = Editor.marks(editor)
    return marks ? marks[format] === true : false
  }
  const toggleMark = (editor, format) => {
    const isActive = isMarkActive(editor, format)
    if (isActive) {
      Editor.removeMark(editor, format)
    } else {
      Editor.addMark(editor, format, true)
    }
  }
  export default {
    mixins: [SlateMixin],
    props: {
      format: String,
      icon: String
    },
    render() {
      const editor = this.$editor
      return (
        <slate-button
          active={isMarkActive(editor, this.format)}
          onMouseDown={event => {
            event.preventDefault()
            toggleMark(editor, this.format)
          }}
        >
          <slate-icon icon={this.icon} />
        </slate-button>
      )
    }
  };
</script>

<style scoped>
</style>
