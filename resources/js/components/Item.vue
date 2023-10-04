<template>
  <div
    class="atb-item-item text-sm flex p-3 atb-border relative"
    :class="{
      'atb-bad': isBad,
    }"
  >
    <div
      class="atb-image-container shrink-0 rounded-xl overflow-hidden mr-3"
      @click="handleIdClick"
    >
      <img
        class="atb-image"
        v-if="item.image?.cdnUrl || item.image?.retailerUrl"
        :src="item.image?.cdnUrl || item.image?.retailerUrl"
      />
      <div v-else class="atb-image flex items-center justify-center">
        <WarningFilledIcon />
      </div>
    </div>

    <div class="atb-content grow min-w-0 flex flex-col">
      <div
        v-if="item.status === 'fetching'"
        class="text-gray-700 atb-f-13 bg-gray-300 rounded-md w-15 mb-1 mr-6"
      >
        &nbsp;
      </div>
      <h4 v-else class="atb-title truncate font-semibold mr-4">
        <span class="truncate">{{
          item.status === "fetching" ? "..." : item.title || "No title found"
        }}</span>
      </h4>
      <div
        v-if="item.status === 'fetching'"
        class="text-gray-700 atb-f-13 bg-gray-300 rounded-md w-10"
      >
        &nbsp;
      </div>
      <div v-else-if="item.type === 'non_retail'" class="atb-f-13 atb-error">
        Non-product
      </div>
      <div
        v-else-if="item.availability?.current?.status === 'soldOut'"
        class="atb-error"
      >
        Sold out
      </div>
      <div
        v-else-if="item.availability?.current?.status === 'outOfStock'"
        class="atb-error"
      >
        Out of stock
      </div>
      <div
        v-else-if="item.availability?.current?.status === 'discontinued'"
        class="atb-error"
      >
        Discontinued
      </div>
      <div
        v-else-if="!item.price"
        class="text-gray-700 atb-f-13 atb-error"
      ></div>
      <div v-else class="text-gray-700 atb-f-13">
        {{ item.currencySymbol }}{{ item.price?.toFixed(2) }}
      </div>
      <div class="flex grow text-gray-700 items-end">
        <div class="flex grow">
          <img class="atb-favicon mr-2" :src="item.retailer?.faviconUrl" />
          <div class="atb-f-12 flex flex-col justify-center">
            <p>
              {{ item.retailer?.tld?.replace("www.", "") }}
            </p>
            <div v-if="item.isReported" class="atb-adding mt-1">REPORTED</div>
            <div v-else-if="item.status === 'fetching'" class="atb-adding mt-1">
              ADDING
            </div>
            <div
              v-else-if="
                !item.type === 'non_product' &&
                !item.image?.cdnUrl &&
                !item.image?.retailerUrl
              "
              class="atb-adding mt-1 atb-error"
            >
              NO IMAGE
            </div>
          </div>
        </div>
        <a
          :href="item.url"
          target="_blank"
          class="atb-button bg-gray-300 rounded-full px-3 py-1 text-blue font-semibold"
          >Visit</a
        >
      </div>
    </div>
    <div class="atb-more absolute flex items-end flex-col">
      <div class="px-4 cursor-pointer" @click="handleMenuToggle" ref="ignoreEl">
        <MoreIcon />
      </div>
      <div
        v-if="menuOpen"
        v-on-click-outside="[handleMenuClose, { ignore: [$refs.ignoreEl] }]"
        class="atb-error atb-menu rounded-lg overflow-hidden shadow-lg mr-3 z-10"
      >
        <div
          class="atb-menu-option p-3 flex items-center cursor-pointer transition-all"
          @click="handleReportToggle"
        >
          <div v-if="saving" class="mx-1">
            {{ item.isReported ? "Unreporting..." : "Reporting..." }}
          </div>
          <div v-else class="flex items-center">
            <WarningIcon class="mr-2" />
            <span v-if="!item.isReported">Report</span>
            <span v-else>Unreport</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import WarningFilledIcon from "./WarningFilledIcon.vue";
import WarningIcon from "./WarningIcon.vue";
import MoreIcon from "./MoreIcon.vue";
import { vOnClickOutside } from "@vueuse/components";
</script>

<script>
export default {
  props: ["item", "isBad", "handleReportItem", "handleUnreportItem"],
  data() {
    return {
      menuOpen: false,
      saving: false,
    };
  },
  methods: {
    handleIdClick() {
      navigator.clipboard.writeText(this.item.id);
    },
    handleMenuToggle() {
      this.menuOpen = !this.menuOpen;
    },
    handleMenuOpen() {
      this.menuOpen = true;
    },
    handleMenuClose() {
      this.menuOpen = false;
    },
    async handleReportToggle() {
      this.saving = true;
      if (this.item.isReported) {
        await this.handleUnreportItem(this.item);
      } else {
        await this.handleReportItem(this.item);
      }
      this.saving = false;
      this.handleMenuClose();
    },
  },
};
</script>

<style scoped>
.atb-bad {
  background: #f7e9e7;
}
.atb-menu {
  background: #fcfcfc;
}
.atb-menu-option {
  background: #ffffff;
}
.atb-menu-option:hover {
  background: #fcfcfc;
}
.atb-error {
  color: #bb4732;
}
.atb-image-container {
  width: 80px;
  height: 80px;
  border-radius: 10px;
  overflow: hidden;
}
.atb-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  background: rgba(0, 0, 0, 0.075);
}
.atb-item-item {
  margin: -1px;
  width: calc(100% + 2px);
}
@media (min-width: 1015px) {
  .atb-item-item {
    width: calc(50% + 2px);
  }
}
.atb-favicon {
  width: 24px;
  height: 24px;
}
.atb-more {
  right: 0;
}
.atb-hidden {
  background: rgba(244, 246, 249, 0.95);
  left: 0px;
  top: 0px;
  border: 1px solid #e5e5e5;
}
.atb-f-13 {
  font-size: 13px;
}
.atb-f-12 {
  font-size: 12px;
  line-height: 1;
}
.atb-button {
  transition: 100ms background ease-in-out, 100ms color ease-in-out;
}
.atb-button:hover {
  color: #777 !important;
  background: #ddd !important;
}
.atb-adding {
  font-size: 11px;
  letter-spacing: 2px;
}
</style>
