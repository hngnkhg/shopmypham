<script setup>
import Home from "@/pages/Home.vue";
// import FirestoreExample from './components/FirestoreExample.vue'
</script>

<template>
   <div>
   <FirestoreExample />
   </div>
  <router-view />
</template>


<style scoped>
input {
  width: 30%;
  padding-left: 20px;
  margin-left: 5%;
}
.gradient-line {
  width: 70%;
  margin-left: 16%;
  margin-top: 30px;
  height: 4px;
  background: linear-gradient(
    90deg,
    #00ff00,
    #00ffff,
    #0000ff,
    #ff00ff,
    #ff0000,
    #ffff00
  ); /* Gradient effect */
  background-size: 200% 100%;
  background-position: right;
  border-radius: 2px;
  animation: moveIn 3s linear infinite;
}

@keyframes moveIn {
  0% {
    background-position: right; /* Start from the right */
  }
  50% {
    background-position: left; /* Move to the left */
  }
  100% {
    background-position: right; /* Reset to the right */
  }
}

.content {
  max-width: 1600px;
  margin: 0 auto;
  padding-top: 10px;
}
h1 {
  text-align: center;
  padding-top: 10px;
}

p {
  text-align: center;
  margin-left: 50px;
}
</style>
