import { log } from "./log";
import { getFunctionsUrl } from "./saveToBasket";

export const getItemsByUrl = async ({ environment, apiKey, urls }) => {
  log("itemsByUrl", urls);
  const result = await fetch(
    getFunctionsUrl(environment, "publisher-item-retrieveByUrl"),
    {
      method: "post",
      headers: new Headers({
        "Content-Type": "application/json",
      }),
      body: JSON.stringify({ data: { urls, apiKey } }),
    }
  )
    .then((r) => r.json())
    .then((r) => r.result.pages);

  return result;
};
