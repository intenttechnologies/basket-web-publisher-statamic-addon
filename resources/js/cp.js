import { cleanUrl } from "./libs/url-cleaner.mjs";
import { getItems, saveItems } from "./libs/saveToBasket";

const TOAST_DELAY = 3000;

const TOAST_MESSAGES = [
  "Saving Basket products...",
  "...still saving to Basket",
  "...nearly finished saving to Basket",
];

const addCleanedUrl = (type, url, prev) => {
  try {
    const cleaned = cleanUrl(url);
    prev.add(cleaned.url);
  } catch (e) {
    throw `A ${type} purchase link URL field contains invalid text: ${url}`;
  }
};

Statamic.$hooks.on("entry.saving", async (resolve, reject, payload) => {
  const ENV = Statamic.$config.get("add-to-basket:environment");
  const API_KEY = Statamic.$config.get("add-to-basket:api_key");
  let currentToastMessage = 0;

  console.log("Add to Basket");

  if (!payload.values?.add_to_basket?.enabled) {
    console.log("> Not enabled");
    resolve();
    return;
  }

  const slug = payload.values.slug;
  const content = JSON.parse(payload.values.content);

  console.log(JSON.stringify(content, null, 2));

  let linksFound = [];
  try {
    linksFound = [
      ...content.reduce((prev, curr) => {
        const values = curr?.attrs?.values;

        if (
          curr.type === "set" &&
          values?.type === "button" &&
          RegExp(/^Buy now/).test(values?.text) &&
          values?.url
        ) {
          addCleanedUrl(values.type, values.url, prev);
        }

        if (values?.type === "product" && values?.buy_links?.length > 0) {
          values.buy_links.forEach((buyLink) =>
            addCleanedUrl("product buy link", buyLink.url, prev)
          );
        }

        return prev;
      }, new Set()), // use set to prevent duplications
    ];
  } catch (e) {
    reject(e);
    return;
  }

  if (linksFound.length < 1) {
    console.log("> Nothing to save");
    payload.values.add_to_basket = {
      enabled: payload.values.add_to_basket.enabled,
      links: [],
    };
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
      basketName: slug,
    });
    const items = await getItems({
      environment: ENV,
      userId,
      basketId,
    });

    payload.values.add_to_basket = {
      enabled: payload.values.add_to_basket.enabled,
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
