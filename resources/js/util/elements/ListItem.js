export default (attributes, children, element) => ({
  render() {
    return <li {...{attrs: attributes}}>{children}</li>
  }
})
