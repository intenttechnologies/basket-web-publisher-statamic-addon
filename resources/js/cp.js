import { cleanUrl } from "./libs/url-cleaner.mjs";
import { getItems, saveItems } from "./libs/saveToBasket";
import { getItemsByUrl } from "./libs/getItemByUrl";
import { log } from "./libs/log";

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
    throw `A ${type} URL field contains invalid text: ${url}`;
  }
};

Statamic.$hooks.on("entry.saving", async (resolve, reject, payload) => {
  const ENV = Statamic.$config.get("add-to-basket:environment");
  const API_KEY = Statamic.$config.get("add-to-basket:api_key");
  let currentToastMessage = 0;
  const data = payload.values?.add_to_basket;

  log("Add to Basket");

  if (!data?.enabled) {
    log("Not enabled");
    resolve();
    return;
  }

  const currentItemData = data?.items;
  const slug = payload.values.slug;
  const content = JSON.parse(payload.values.content);

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
    log("Nothing to save");
    payload.values.add_to_basket = {
      enabled: payload.values.add_to_basket.enabled,
      items: [],
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
    const saveIfRequired = async () => {
      const isSaveRequired = linksFound.some(
        (link) =>
          !currentItemData.some(({ originalUrl }) => originalUrl === link)
      );
      log("isSaveRequired", isSaveRequired);
      if (isSaveRequired) {
        const { userId, basketId } = await saveItems({
          environment: ENV,
          apiKey: API_KEY,
          urls: linksFound,
          basketName: slug,
        });

        return { userId, basketId };
      }

      return {
        userId: data.userId,
        basketId: data.basketId,
      };
    };
    const { userId, basketId } = await saveIfRequired();

    // get item data
    const items = await getItems({
      environment: ENV,
      userId,
      basketId,
    });

    // add originalUrl
    const itemsByUrl = await getItemsByUrl({
      environment: ENV,
      apiKey: API_KEY,
      urls: linksFound,
    });
    itemsByUrl.forEach(({ id, originalUrl }) => {
      const item = items.find((item) => item.id === id);
      if (item) {
        item.originalUrl = originalUrl;
      }
    });

    // order according to links on page
    const orderedItems = linksFound.reduce((prev, cur) => {
      const match = items.find(({ originalUrl }) => originalUrl === cur);
      if (match) {
        prev.push(match);
      }
      return prev;
    }, []);

    const mapper = ({ title, url, originalUrl }) => ({
      title,
      url,
      originalUrl,
    });

    payload.values.add_to_basket = {
      enabled: payload.values.add_to_basket.enabled,
      basketId,
      userId,
      items: orderedItems,
    };

    clearInterval(toastInterval);
    resolve();
  } catch (err) {
    log(err);
    clearInterval(toastInterval);
    reject("An error occured in Add to Basket");
  }
});
