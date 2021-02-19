import EntryVoid from '../../components/EntryVoid'

export default (attributes, children, element) => ({
  components: {
    EntryVoid,
  },

  render() {
    return <entry-void {...{attrs: attributes}} resource-type={element.resourceType} resource={element.selectedResource}>{children}</entry-void>
  }
})
