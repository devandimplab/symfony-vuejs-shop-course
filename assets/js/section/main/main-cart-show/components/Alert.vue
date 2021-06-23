<template>
  <div
      v-if="alert.message"
      :class="alertClass"
  >
    {{ alert.message }}
  </div>
</template>

<script>
import {mapState} from "vuex";

export default {
  name: "Alert",
  computed: {
    ...mapState("cart", ["alert"]),
    alertClass() {
      return "alert alert-" + this.alert.type;
    }
  },
}
</script>

<style scoped>

</style>