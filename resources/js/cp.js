import { cleanUrl } from "./libs/url-cleaner.mjs";
import { getItems, saveItems } from "./libs/saveToBasket";

const TOAST_DELAY = 3000;

const TOAST_MESSAGES = [
    "Saving Basket products...",
    "...still saving to Basket",
    "...nearly finished saving to Basket",
];

Statamic.$hooks.on("entry.saving", async (resolve, reject, payload) => {
    const ENV = Statamic.$config.get("add-to-basket:environment");
    const API_KEY = Statamic.$config.get("add-to-basket:api_key");
    let currentToastMessage = 0;

    console.log("Saving...");

    if (!payload.values.add_to_basket.enabled) {
        console.log("Disabled");
        resolve();
        return;
    }

    const slug = payload.values.slug;
    const bard = JSON.parse(payload.values.page_builder);

    // const storedLinks = payload.values.add_to_basket?.links || [];

    const linksFound = [
        ...bard.reduce((prev, curr) => {
            const values = curr?.attrs?.values;
            if (
                curr.type === "set" &&
                values?.type === "button" &&
                RegExp(/^Buy now/).test(values?.text)
            ) {
                const url = values.url;
                const cleaned = cleanUrl(url);
                prev.add(cleaned.url);
                // affiliateUrl should be set by public script on publisher
            }
            return prev;
        }, new Set()), // use set to prevent duplications
    ];

    console.log("linksFound", JSON.stringify(linksFound, null, 2));
    // console.log("storedLinks", JSON.stringify(storedLinks, null, 2));

    // const linksToSave =  linksInCopy;
    // const linksToSave = linksInCopy.filter(
    //     (link) =>
    //         !storedLinks.some(
    //             (storedLink) =>
    //                 storedLink?.url === link &&
    //                 storedLink?.status === "ready"
    //         )
    // );

    // console.log("linksToSave", JSON.stringify(linksToSave, null, 2));

    if (linksFound.length < 1) {
        console.log("Nothing to save");
        resolve();
        return;
    }

    const toastInterval = setInterval(() => {
        currentToastMessage++;
        Statamic.$toast.info(
            TOAST_MESSAGES[currentToastMessage] ||
                TOAST_MESSAGES[TOAST_MESSAGES.length - 1],
            { duration: TOAST_DELAY }
        );
    }, TOAST_DELAY + 500);

    Statamic.$toast.info(TOAST_MESSAGES[0], { duration: TOAST_DELAY });

    try {
        const { userId, basketId } = await saveItems({
            environment: ENV,
            apiKey: API_KEY,
            urls: linksFound,
            basketName: slug
        });
        const items = await getItems({
            environment: ENV,
            userId,
            basketId
        });

        // const resultsToSave = results.filter((r) => r?.url);

        // const linksInCorrectOrder = linksInCopy
        //     .map((link) => {
        //         const storedLink = storedLinks.find((s) => s?.url === link);
        //         if (storedLink && storedLink?.status === "ready") {
        //             return storedLink;
        //         }
        //         return results.find((s) => {
        //             console.log(s?.url, link)
        //             return s?.url === link
        //         });
        //     })
        //     .filter((s) => s?.url);
        // console.log("linksInCorrectOrder", JSON.stringify(linksInCorrectOrder, null, 2));

        payload.values.add_to_basket = {
            enabled: payload.values.add_to_basket.enabled,
            // ensure links data matches order in copy
            links: items,
        };

        clearInterval(toastInterval);
        resolve();
    } catch (err) {
        console.log(err);
        clearInterval(toastInterval);
        reject("An error occured in Add to Basket");
    }
});