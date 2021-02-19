export default (attributes, children, element) => ({
  render() {
    return <ol {...{attrs: attributes}}>{children}</ol>
  }
})
