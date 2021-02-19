export default (attributes, children, leaf) => ({
  render() {
    children = <u>{children}</u>

    return <span {...{attrs: attributes}}>{children}</span>
  }
})
