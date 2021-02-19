import Bold from './Bold'
import Code from './Code'
import Italic from './Italic'
import Underline from './Underline'

const leafMap = new Map()

leafMap.set('bold', Bold)
leafMap.set('code', Code)
leafMap.set('italic', Italic)
leafMap.set('underline', Underline)

export default leafMap
