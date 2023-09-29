import { getFunctionsUrl } from "./saveToBasket";

export const reportItem = async ({ environment, apiKey, itemId }) => {
  console.log("reportItem", itemId);
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

  console.log("reportItem result", result);

  return result;
};

export const unreportItem = async ({ environment, apiKey, itemId }) => {
  console.log("unreportItem", itemId);
  const result = await fetch(
    getFunctionsUrl(environment, "publisher-item-unreport"),
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

  console.log("unreportItem result", result);
  return result;
};
