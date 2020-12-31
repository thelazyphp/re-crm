<script>
import TagsInputTag from './TagsInputTag'

export default {
  components: {
    TagsInputTag
  },

  props: {
    tags: {
      type: Array,

      default () {
        return []
      }
    }
  },

  methods: {
    focus () {
      this.$refs.tagInput.focus()
    },

    removeTag (index) {
      this.$emit('remove', index)
    },

    addTag (event) {
      if ([' ', 'Enter'].includes(event.key)) {
        let tag = event.target.value.toLowerCase()

        if (event.key == ' ') {
          tag = tag.replace(/ $/, '')
        }

        if (tag && !this.tags.includes(tag)) {
          this.$emit('add', tag)
        }

        event.target.value = null
      }
    }
  }
}
</script>

<template>
  <div class="tags-input form-control" @click="focus">
    <TagsInputTag v-for="(tag, index) in tags" :key="index" :text="tag" class="mb-1 mr-1" @remove="removeTag(index)" />
    <input ref="tagInput" type="text" data-target="tag-input" class="form-control" placeholder="Теги..." @keydown="addTag">
  </div>
</template>

<style scoped>
.tags-input {
  cursor: text;
  height: auto;
}

[data-target="tag-input"] {
  display: inline-block;
  padding: 0;
  width: auto;
  height: auto;
  border: none;
  outline: none;
  box-shadow: none;
}
</style>
