const heading = (level, attributes, children, element) => ({
  render() {
    const tag = `h${level}`
    return <tag {...{attrs: attributes}}>{children}</tag>
  }
})

export const HeadingOne = (attributes, children, element) => heading(1, attributes, children, element)
export const HeadingTwo = (attributes, children, element) => heading(2, attributes, children, element)
export const HeadingThree = (attributes, children, element) => heading(3, attributes, children, element)
export const HeadingFour = (attributes, children, element) => heading(4, attributes, children, element)
export const HeadingFive = (attributes, children, element) => heading(5, attributes, children, element)
export const HeadingSix = (attributes, children, element) => heading(6, attributes, children, element)
