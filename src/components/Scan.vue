<template>
  <div class="scan">
    <h2 class="scan_name">{{ name }}</h2>
    <pre class="scan_subject">{{ subject_name }}</pre>
    <a target="_blank" :href="'http://localhost/school/api/' + scan_path">View scan</a>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Post',
  props: {
    id: Number,
    name: String,
    subject: Number,
    scan_path: String
  },
  data() {
      return {
          subject_name: null
      }
  },
  beforeMount() {
      axios
        .get('/scan/subjectname/' + this.subject)
        .then(response => (this.subject_name = response.data.name))
  }
}
</script>

<style lang="scss" scoped>
.scan {
    color: white;
}
</style>