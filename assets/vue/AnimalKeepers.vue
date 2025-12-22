<template>
  <add-animal-keeper-button @added="$refs.table.reload()" />
  <animal-keeper-table class="mt-2" ref="table" />
</template>

<script>
import AddAnimalKeeperButton from "./components/Action/AddAnimalKeeperButton.vue";
import AnimalKeeperTable from "./components/View/AnimalKeeperTable.vue";

export default {
  components: {AnimalKeeperTable, AddAnimalKeeperButton},
}

</script>
