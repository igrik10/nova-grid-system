<template>
  <div :class="elementSize">
    <field-wrapper :stacked="field.stacked" v-if="field.visible">
      <div class="px-8" :class="field.stacked ? 'pt-6 w-full' : 'py-6 w-1/5'">
        <slot>
          <form-label
            :label-for="field.attribute"
            :class="{ 'mb-2': showHelpText && field.helpText }"
          >
            {{ fieldLabel }}

            <span v-if="field.required" class="text-danger text-sm">*</span>
          </form-label>
        </slot>
      </div>
      <div class="py-6 px-8" :class="fieldClasses">
        <slot name="field" />

        <help-text class="help-text mt-2" v-if="showHelpText">
          {{ field.helpText }}
        </help-text>
      </div>
    </field-wrapper>
  </div>
</template>

<script>
export default {
  props: {
    field: { type: Object, required: true },
    fieldName: { type: String },
    showHelpText: { type: Boolean, default: true },
    showErrors: { type: Boolean, default: true },
    fullWidthContent: { type: Boolean, default: false },
  },

  mounted() {
    if (!this.hasSize) {
      this.$el.parentElement?.classList.add('w-full')
    }

    if (this.hasSize) {
      const panel = this.findPanelContainer()
      if (panel) {
        panel.classList.add('flex-dom', 'flex-wrap', 'flex')
      }
    }

    if (this.getRemoveBottomBorder === true) {
      this.$el.children[0]?.classList.add('remove-bottom-border')
    } else if (this.getRemoveBottomBorder === false) {
      this.$el.children[0]?.classList.remove('remove-bottom-border')
    }
  },

  methods: {
    findPanelContainer() {
      let el = this.$el.parentElement
      for (let i = 0; i < 5 && el; i++) {
        if (
          el.classList.contains('card') ||
          el.classList.contains('rounded-lg') ||
          el.getAttribute('data-panel')
        ) {
          return el
        }
        el = el.parentElement
      }
      return null
    },
  },

  computed: {
    fieldLabel() {
      if (this.fieldName === '') return ''
      return this.fieldName || this.field.name || this.field.singularLabel
    },

    fieldClasses() {
      return this.fullWidthContent
        ? this.field.stacked ? 'w-full' : 'w-4/5'
        : this.hasSize ? 'w-full' : 'w-1/2'
    },

    elementSize() {
      return this.field.size || 'w-full'
    },

    hasSize() {
      return this.field.size !== undefined
    },

    getRemoveBottomBorder() {
      return this.field.removeBottomBorder || null
    },
  },
}
</script>
