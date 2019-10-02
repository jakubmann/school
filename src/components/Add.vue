<template>
  <div class="scan">
    <div class="scan__error" v-if="error">{{ error }}</div>
    <input @change="clearError()" v-model="inputName" class="scan__name" type="text"></input>
    <select @change="clearError()" v-model="inputSubject" class="scan__subject">
      <option class="subject" v-for="subject in subjects" :key="subject.id" :value="subject.id">{{ subject.name }}</option>
    </select>
    <label for="scan__file" class="scan__upload" :class="{ 'scan__upload--added': filename }">
      <div v-if="!filename">Add/drag scan</div>
      <div v-else>{{ filename }}</div>
    </label>
    <input @change="updateFile(); clearError()" id="scan__file" class="scan__file" type="file" name="newFile" accept=".pdf"/>
    <button @click="uploadFile()" class="scan__add">Add</button>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Add',
  data: function() {
    return {
      file: null,
      filename: false,
      subjects: [],
      inputName: "",
      inputSubject: null,
      error: false
    }
  },
  methods: {
    uploadFile() {
      if (!this.inputName) {
        this.error = "You must enter a name";
      }
      else if (!this.inputSubject) {
        this.error = "You must choose a subject";
      }
      else {
        if (this.file) {
          let formData = new FormData();
          formData.append('scan', this.file.files[0]);
          formData.append('data', JSON.stringify({
            name: this.inputName,
            subject: this.inputSubject
          }));
          const self = this;
          axios
          .post(
            '/scan/save',
            formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            } 
          })
          .then(function(response) {
            console.log(response.data);
          })
        } else {
          this.error = "You must select a file.";
        }
      }
    },
    updateFile() {
      this.file = document.querySelector("input[type=file]");
      this.filename = this.file.files[0].name;
    },
    getSubjects() {
      const self = this;
      axios
      .get("/scan/subjects")
      .then(function(response) {
        self.subjects = response.data;
      })
    },
    clearError() {
      this.error = false
    }
  },
  mounted() {
    this.getSubjects();
  }
}
</script>

<style scoped lang="scss">
.scan {
  margin: 20px auto;
  text-align: center;

  &__add {
    display: block;
    margin: 20px auto 0px auto;
    border: none;
    background: #b1efbf;
    border-radius: 20px;
    padding: 10px 20px;
    font-family: 'Roboto Mono', monospace;
    font-size: 18px;
    font-weight: 900;
  }

  &__file {
    display: none;
  }

  &__upload {
    display: block;
    margin: 0px auto;
    border: 1px solid white;
    background: black;
    color: white;
    border-radius: 20px;
    padding: 10px 20px;
    font-family: 'Roboto Mono', monospace;
    font-size: 15px;
    display: inline-block;
    cursor: pointer;
    &--added {
      background-color: white;
      color: black;
      border: none;
    }
  }

  &__name {
    border: 1px solid white;
    background: none;
    display: block;
    margin: 10px auto;
    color: white;
    padding: 7px 12px;
    border-radius: 20px;
  }

  &__subject {
    color: white;
    display: block;
    margin: 10px auto;
    padding: 10px;
    background: none;
  }

  &__error {
    color: #fc7474;
    font-family: 'Roboto Mono', monospace;
    font-size: 15px;
  }

  .subject {
    color: white;
    background-color: black;
    padding: 5px;
  }
}
</style>
