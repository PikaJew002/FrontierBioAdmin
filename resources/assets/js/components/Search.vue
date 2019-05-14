<template>
  <div class="form-group row">
    <label class="col-sm-1 col-form-label">Filter by</label>
    <div class="col-sm-2">
      <select class="form-control" v-model="selectedFilter">
        <option v-for="attr in itemAttributes">
          {{ attr }}
        </option>
      </select>
    </div>
    <div class="col-sm-2">
      <input type="text" v-model="filterText" class="form-control" @change="onChange()">
    </div>
  </div>
</template>

<script>
  import Entity from './Entity.js';
  export default {
    props: {
      value: {
        type: Object,
        required: true
      },
      attributes: {
        type: Object,
        default: []
      }
    },

    data() {
      return {
        selectedFilter: '',
        filterText: ''
      }
    },

    created() {

    },

    methods: {
      onChange() {
        fetchEntities(this.endpoint_url + '?filter=' + this.selectedFilter + ':like:' + this.filterText)
      }
    },

    watch: {
      value() {
        this.$emit('input', this.value);
      }
    }
  }
</script>
