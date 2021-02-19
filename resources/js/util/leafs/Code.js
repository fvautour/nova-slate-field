export default (attributes, children, leaf) => ({
  render() {
    children = <code class={"bg-50 px-1 border border-60 rounded text-danger"}>{children}</code>

    return <span {...{attrs: attributes}}>{children}</span>
  }
})
