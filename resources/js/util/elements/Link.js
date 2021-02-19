export default (attributes, children, element) => ({
  render() {
    return (
      <a class={"dim cursor-pointer text-primary hover:text-primary-dark no-underline font-extrabold"} {...{attrs: attributes}} href={element.url}>
        {children}
      </a>
    )
  }
})
