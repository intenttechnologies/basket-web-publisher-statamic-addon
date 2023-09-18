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
                Add to Basket button will not be visible on the public website
                and products will not be parsed from the page copy.
            </div>
        </div>
        <div v-if="enabled">
            <div v-if="value?.links && value.links.length > 0">
                <p class="text-sm mb-2">
                    The following products have been found in the page copy
                    (updated on save):
                </p>

                <div
                    class="bg-white rounded-xl atb-border overflow-hidden flex flex-wrap"
                >
                    <div
                        v-for="link in value?.links"
                        class="atb-link-item text-sm flex p-3 atb-border relative"
                    >
                        <div
                            class="atb-image-container shrink-0 rounded-xl overflow-hidden mr-4"
                        >
                            <img class="atb-image" :src="link?.image?.cdnUrl" />
                        </div>

                        <div class="atb-content grow min-w-0 flex flex-col">
                            <div v-if="link.status === 'fetching'" class="text-gray-700 atb-f-13 bg-gray-300 rounded-md w-15 mb-1">
                                &nbsp;
                            </div>
                            <h4 v-else class="atb-title truncate font-semibold">
                                <span class="truncate">{{
                                    link.status === "fetching"
                                        ? "..."
                                        : link?.title || "No title found"
                                }}</span>
                            </h4>
                            <div v-if="link.status === 'fetching'" class="text-gray-700 atb-f-13 bg-gray-300 rounded-md w-10">
                                &nbsp;
                            </div>
                            <div v-else class="text-gray-700 atb-f-13">
                                {{ link?.currencySymbol
                                }}{{
                                    link?.price?.toFixed(2) || "No price found"
                                }}
                            </div>
                            <div class="flex grow text-gray-700 items-end">
                                <div class="flex grow">
                                    <img
                                        class="atb-favicon mr-2"
                                        :src="link?.retailer?.faviconUrl"
                                    />
                                    <div class="atb-f-12 flex flex-col justify-center">
                                        <p>
                                            {{
                                                link?.retailer?.tld?.replace(
                                                    "www.",
                                                    ""
                                                )
                                            }}
                                        </p>
                                        <div
                                            v-if="link.status === 'fetching'"
                                            class="atb-adding mt-1"
                                        >
                                            ADDING
                                        </div>
                                    </div>
                                </div>
                                <a
                                    :href="link?.url"
                                    target="_blank"
                                    class="atb-button bg-gray-300 rounded-full px-3 py-1 text-blue font-semibold"
                                    >Visit</a
                                >
                            </div>
                        </div>
                        <!-- <div
                            v-if="link?.hidden"
                            class="atb-hidden absolute w-full h-full flex justify-center items-center flex-col"
                        >
                            <p class="text-md font-semibold">Item hidden</p>
                            <p class="text-xs text-gray-600">
                                This item won’t be published.
                            </p>
                        </div>
                        <Eye
                            v-bind:open="!link?.hidden"
                            class="atb-eye absolute cursor-pointer"
                            @click.native="
                                link.hidden = !Boolean(link.hidden);
                                save();
                            "
                        /> -->
                    </div>
                </div>
            </div>
            <div v-else>
                <p class="text-sm mb-2">
                    No products added. Products will be added when the page is
                    saved.
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* .atb-title {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
} */
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
    background: #eee;
}
.atb-link-item {
    margin: -1px;
    width: calc(100% + 2px);
}
@media (min-width: 1015px) {
    .atb-link-item {
        width: calc(50% + 2px);
    }
}
.atb-border {
    border: 1px solid #f0f0f0;
}
.atb-favicon {
    width: 24px;
    height: 24px;
}
.atb-eye {
    right: 12px;
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

<script setup>
import Eye from "./Eye.vue";
</script>

<script>
export default {
    props: ["value"],
    mixins: [Fieldtype],
    data() {
        const enabled =
            this.value?.enabled === undefined ? true : this.value?.enabled;
        return {
            enabled,
        };
    },
    mounted() {
        // set initial values
        // const links = (this.value?.links || []).map((link) => ({
        //     ...link,
        //     hidden: link.hidden ?? false,
        // }));

        this.update({
            enabled: this.enabled,
            links: this.value?.links || [],
        });
    },
    methods: {
        toggle() {
            this.enabled = !this.enabled;
            this.update({
                enabled: this.enabled,
                links: this.value?.links,
            });
        },
        save() {
            this.update({
                enabled: this.enabled,
                links: this.value?.links,
            });
        },
    },
};
</script>
