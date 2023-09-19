import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-async-image', IndexField)
  app.component('detail-async-image', DetailField)
  app.component('form-async-image', FormField)
})
