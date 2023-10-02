import { log } from "./log";
import { getFunctionsUrl } from "./saveToBasket";

export const reportItem = async ({ environment, apiKey, itemId }) => {
  log("reportItem", itemId);
  const result = await fetch(
    getFunctionsUrl(environment, "publisher-item-report"),
    {
      method: "post",
      headers: new Headers({
        "Content-Type": "application/json",
      }),
      body: JSON.stringify({ data: { itemId, apiKey, channel: "web" } }),
    }
  )
    .then((r) => r.json())
    .then((r) => r.result);

  log("reportItem result", result);

  return result;
};

export const unreportItem = async ({ environment, apiKey, itemId }) => {
  log("unreportItem", itemId);
  const result = await fetch(
    getFunctionsUrl(environment, "publisher-item-unReport"),
    {
      method: "post",
      headers: new Headers({
        "Content-Type": "application/json",
      }),
      body: JSON.stringify({ data: { itemId, apiKey } }),
    }
  )
    .then((r) => r.json())
    .then((r) => r.result);

  log("unreportItem result", result);
  return result;
};
