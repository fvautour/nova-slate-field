export default (attributes, children, leaf) => ({
  render() {
    children = <em>{children}</em>

    return <span {...{attrs: attributes}}>{children}</span>
  }
})
