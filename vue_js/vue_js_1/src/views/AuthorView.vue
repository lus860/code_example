<script setup>
import Author from '../components/Author.vue'
</script>

<template>
  <div class="about">
    <h1>Authors</h1>
    <main>
      <Author />
    </main>
  </div>
</template>

<style>
@media (min-width: 1024px) {
  .about {
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
}
</style>
