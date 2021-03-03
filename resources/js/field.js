import Vue from 'vue'
import { SlatePlugin } from 'slate-vue'

import Toolbar from './components/Toolbar'
import Icon from './components/Icon'
import Button from './components/Button'
import MarkButton from './components/MarkButton'
import BlockButton from './components/BlockButton'
import LinkButton from './components/LinkButton'
import ResourceButton from './components/ResourceButton'
import EntryVoid from './components/EntryVoid'

import FormResourceMorphToField from './components/Form/ResourceMorphToField'

import FormSlateField from './components/Form/SlateField'
import DetailSlateField from './components/Detail/SlateField'
import IndexSlateField from './components/Index/SlateField'

Vue.use(SlatePlugin)

Nova.booting((Vue, router, store) => {
  Vue.use(SlatePlugin)
  Vue.component('slate-toolbar', Toolbar)
  Vue.component('slate-icon', Icon)
  Vue.component('slate-button', Button)
  Vue.component('slate-mark-button', MarkButton)
  Vue.component('slate-block-button', BlockButton)
  Vue.component('slate-link-button', LinkButton)
  Vue.component('slate-resource-button', ResourceButton)
  // Vue.component('entry-void', EntryVoid)

  Vue.component('form-resource-morph-to-field', FormResourceMorphToField)

  Vue.component('detail-nova-slate-field', DetailSlateField)
  Vue.component('form-nova-slate-field', FormSlateField)
  Vue.component('index-nova-slate-field', IndexSlateField)
})
