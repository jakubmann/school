<template>
  <div id="app">
    <img class="logo" alt="Logo" src="./assets/logo.png">
    <Add />
    <Scan v-for="scan in scans" :key="scan.id" :name="scan.name" :subject="scan.subject" :scan_path="scan.scan_path" />
  </div>
</template>

<script>
import axios from 'axios';

import Add from './components/Add.vue'
import Scan from './components/Scan.vue'

export default {
  name: 'app',
  components: {
    Add,
    Scan
  },
  data() {
    return {
      scans: null
    }
  },
  methods: {
    getScans() {
      axios
        .get('/scan/all')
        .then(response => (this.scans = response.data))
      }
  },
  mounted() {
    this.getScans();
  }
}
</script>

<style lang="scss">
//import fonts
@import url('https://fonts.googleapis.com/css?family=Roboto+Mono&display=swap');


body {
  background-color: black;
}
.logo {
  display: block;
  margin: 0px auto;
}
</style>
