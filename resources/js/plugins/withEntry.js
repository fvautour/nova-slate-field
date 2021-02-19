import { Range, Transforms } from 'slate'

export const insertEntryVoid = (editor, entry, selection) => {
  console.group('insertEntryVoid')
  console.log(editor)

  if (selection !== null) {
    editor.selection = { ...selection }
  }

  console.log(selection)
  const isCollapsed = selection && Range.isCollapsed(selection)
  console.log(isCollapsed)

  const entryNode = {
    type: 'entry-void',
    ...entry,
    children: [{ text: '' }]
  }
  console.log(entryNode)
  if (isCollapsed) {
    Transforms.insertNodes(editor, entryNode)
  } else {
    Transforms.wrapNodes(editor, entryNode, { split: true })
    Transforms.collapse(editor, { edge: 'end' })
  }
  console.groupEnd('insertEntryVoid')
}

export const removeEntryVoid = (editor) => {
  Transforms.removeNodes(editor)
}

export default editor => {
  const { isVoid } = editor

  editor.isVoid = element => element.type === 'entry-void' ? true : isVoid(element)

  return editor
}
