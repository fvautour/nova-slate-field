import elementMap from './elements'
import leafMap from './leafs'

export const renderLeaf = ({ attributes, children, leaf }) => {
  if (leaf.bold) {
    return leafMap.get('bold')(attributes, children, leaf)
  }
  if (leaf.code) {
    return leafMap.get('code')(attributes, children, leaf)
  }
  if (leaf.italic) {
    return leafMap.get('italic')(attributes, children, leaf)
  }
  if (leaf.underline) {
    return leafMap.get('underline')(attributes, children, leaf)
  }

  return {
    render() {
      return <span {...{attrs: attributes}}>{children}</span>
    }
  }
}

export const renderElement = ({ attributes, children, element }) => {
  if (elementMap.has(element.type)) {
    return elementMap.get(element.type)(attributes, children, element)
  }

  return {
    render() {
      return <p {...{attrs: attributes}}>{children}</p>
    }
  }
}
