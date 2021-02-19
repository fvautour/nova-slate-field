<script>
  import {Editor, Transforms} from 'slate'
  import {SlateMixin} from 'slate-vue';
  const LIST_TYPES = ['numbered-list', 'bulleted-list']
  const isBlockActive = (editor, format) => {
    const [match] = Editor.nodes(editor, {
      match: n => n.type === format,
    })
    return !!match
  }
  const toggleBlock = (editor, format) => {
    const isActive = isBlockActive(editor, format)
    const isList = LIST_TYPES.includes(format)
    Transforms.unwrapNodes(editor, {
      match: n => LIST_TYPES.includes(n.type),
      split: true,
    })
    Transforms.setNodes(editor, {
      type: isActive ? 'paragraph' : isList ? 'list-item' : format,
    })
    if (!isActive && isList) {
      const block = { type: format, children: [] }
      Transforms.wrapNodes(editor, block)
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
          active={isBlockActive(editor, this.format)}
          onMouseDown={event => {
            event.preventDefault()
            toggleBlock(editor, this.format)
          }}
        >
          <slate-icon icon={this.icon} />
        </slate-button>
      )
    }
  };
</script>
