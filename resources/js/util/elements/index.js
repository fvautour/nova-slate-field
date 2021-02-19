import Blockquote from './Blockquote'
import BulletList from './BulletList'
import Entry from './Entry'
import {HeadingFive, HeadingFour, HeadingOne, HeadingSix, HeadingThree, HeadingTwo} from './Heading'
import Link from './Link'
import ListItem from './ListItem'
import NumberedList from './NumberedList'

const elementMap = new Map()

elementMap.set('block-quote', Blockquote)
elementMap.set('bulleted-list', BulletList)
elementMap.set('entry-void', Entry)
elementMap.set('heading-five', HeadingFive)
elementMap.set('heading-four', HeadingFour)
elementMap.set('heading-one', HeadingOne)
elementMap.set('heading-six', HeadingSix)
elementMap.set('heading-three', HeadingThree)
elementMap.set('heading-two', HeadingTwo)
elementMap.set('link', Link)
elementMap.set('list-item', ListItem)
elementMap.set('numbered-list', NumberedList)

export default elementMap
