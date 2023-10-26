<template>
  <div class="bg-gray-100 p-4 rounded-xl">
    <div class="mb-4">
      <label class="mb-2"
        >Enabled
        <input
          class="ml-4"
          type="checkbox"
          :checked="enabled"
          @change="toggle"
        />
      </label>
      <div v-if="!enabled" class="mt-2 text-gray-700 text-sm">
        Add to Basket button will not be visible on the public website and
        products will not be parsed from the Page Builder content.
      </div>
    </div>
    <div v-if="enabled">
      <div v-if="value.items.length > 0">
        <p class="text-sm mb-2">
          These products will be displayed in the Basket app:
        </p>
        <Items
          :items="goodItems"
          :handleReportItem="handleReportItem"
          :handleUnreportItem="handleUnreportItem"
        />

        <div v-if="fetchingItems.length > 0">
          <p class="text-sm mb-2 mt-4">
            These products are being fetched by Basket:
            <button
              @click="refetch"
              class="atb-button bg-gray-300 rounded-full px-2 py-1 text-blue text-xs font-semibold"
            >
              {{ refetching ? "Refetching..." : "Refetch" }}
            </button>
          </p>
          <Items
            :items="fetchingItems"
            :handleReportItem="handleReportItem"
            :handleUnreportItem="handleUnreportItem"
          />
        </div>

        <div v-if="badItems.length > 0">
          <p class="text-sm mb-2 mt-4">
            These products will NOT be displayed in the Basket app:
          </p>
          <Items
            :items="badItems"
            isBad="true"
            :handleReportItem="handleReportItem"
            :handleUnreportItem="handleUnreportItem"
          />
        </div>
      </div>
      <div v-else>
        <p class="text-sm mb-2">
          No products added. Products will be added when the page is saved.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import Items from "./Items.vue";
import { reportItem, unreportItem } from "../libs/reportItem";
import { log } from "../libs/log";
import { getItems } from "../libs/saveToBasket";
</script>

<script>
export default {
  props: ["value"],
  mixins: [Fieldtype],
  data() {
    return {
      enabled: Boolean(this.value?.enabled),
      refetching: false,
    };
  },
  computed: {
    goodItems() {
      return (
        this.value?.items?.filter(
          ({ status, type }) =>
            (status === "ready" || status === "fixing") && type === "product"
        ) || []
      );
    },
    badItems() {
      return (
        this.value?.items?.filter(
          ({ status, type }) =>
            !(status === "fetching") &&
            !((status === "ready" || status === "fixing") && type === "product")
        ) || []
      );
    },
    fetchingItems() {
      return this.value?.items?.filter(({ status }) => status === "fetching") || [];
    },
  },
  mounted() {
    this.update({
      enabled: this.enabled,
      items: this.value?.items || [],
      basketId: this.value?.basketId,
      userId: this.value?.userId,
    });
    this.refetch();
  },
  methods: {
    async refetch() {
      if (!this.value?.basketId) {
        log("Nothing to fetch");
        return;
      }
      this.refetching = true;
      const environment = Statamic.$config.get("add-to-basket:environment");
      const refetchedItems = await getItems({
        environment,
        basketId: this.value?.basketId,
        userId: this.value?.userId,
      });
      this.value.items = this.value.items.map((item) => ({
        ...item,
        ...refetchedItems.find(({ id }) => id === item.id),
      }));
      this.refetching = false;
    },
    toggle() {
      this.enabled = !this.enabled;
      this.save();
    },
    save() {
      this.update({
        enabled: this.enabled,
        items: this.value?.items,
        basketId: this.value?.basketId,
        userId: this.value?.userId, 
      });
    },
    async handleReportItem(item) {
      log("handleReportItem", item.id);
      const environment = Statamic.$config.get("add-to-basket:environment");
      const apiKey = Statamic.$config.get("add-to-basket:api_key");
      try {
        await reportItem({ itemId: item.id, environment, apiKey });
        item.isReported = true;
        this.save();
      } catch {
        console.error(`Failed to report item ${item.id}`);
      }
    },
    async handleUnreportItem(item) {
      log("handleUneportItem", item.id);
      const environment = Statamic.$config.get("add-to-basket:environment");
      const apiKey = Statamic.$config.get("add-to-basket:api_key");
      try {
        await unreportItem({ itemId: item.id, environment, apiKey });
        item.isReported = false;
        this.save();
      } catch {
        console.error(`Failed to unreport item ${item.id}`);
      }
    },
  },
};
</script>
