<template>
  <div class="bg-white rounded-xl atb-border overflow-hidden flex flex-wrap">
    <Item
      v-for="item in items"
      :item="item"
      :isBad="isBad"
      :handleReportItem="handleReportItem"
      :handleUnreportItem="handleUnreportItem"
      :key="item.id"
    />
  </div>
</template>

<script setup>
import Item from "./Item.vue";
</script>

<style scoped>
.atb-border {
  border: 1px solid #f0f0f0;
}
</style>

<script>
export default {
  props: ["items", "isBad", "handleReportItem", "handleUnreportItem"],
};
</script>
