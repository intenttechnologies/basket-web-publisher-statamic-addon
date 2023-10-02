import { log } from "./log";
import { getFunctionsUrl } from "./saveToBasket";

export const itemByUrl = async ({ environment, apiKey, url }) => {
  log("itemByUrl", url);
  const result = await fetch(
    getFunctionsUrl(environment, "publisher-item-retrieveByUrl"),
    {
      method: "post",
      headers: new Headers({
        "Content-Type": "application/json",
      }),
      body: JSON.stringify({ data: { url, apiKey } }),
    }
  )
    .then((r) => r.json())
    .then((r) => r.result);

  log("itemByUrl result", result);

  return result;
};
