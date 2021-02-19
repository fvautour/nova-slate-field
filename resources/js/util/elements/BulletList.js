export default (attributes, children, element) => ({
  render() {
    return <ul {...{attrs: attributes}}>{children}</ul>
  }
})
