export default (attributes, children, leaf) => ({
  render() {
    children = <strong>{children}</strong>

    return <span {...{attrs: attributes}}>{children}</span>
  }
})
