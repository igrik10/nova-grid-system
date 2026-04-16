// Register AFTER Nova's own components are registered (booted, not booting)
// so our DefaultField/PanelItem overrides take effect
Nova.booted((app, store) => {
  app.component('DefaultField', require('./components/DefaultField').default)
  app.component('PanelItem', require('./components/PanelItem').default)
  app.component('DetailHeadingField', require('./components/Detail/HeadingField').default)
  app.component('FormHeadingField', require('./components/Form/HeadingField').default)
})
