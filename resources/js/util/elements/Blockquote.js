export default (attributes, children, element) => ({
    render() {
      return <blockquote {...{attrs: attributes}}>{children}</blockquote>
    }
})
