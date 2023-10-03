import {log} from './log'

export const getFunctionsUrl = (environment, path) =>
  `https://europe-west2-basket-${environment}.cloudfunctions.net/${path}`;

export const saveItems = async ({ environment, apiKey, urls, basketName }) => {
  log("saveItems", urls);
  const result = await fetch(
    getFunctionsUrl(environment, "publisher-basket-save"),
    {
      method: "post",
      headers: new Headers({
        "Content-Type": "application/json",
      }),
      body: JSON.stringify({ data: { basketName, apiKey, urls } }),
    }
  )
    .then((r) => r.json())
    .then((r) => r.result);

  return result;
};

export const getItems = async ({ environment, userId, basketId }) => {
  log("getItems", basketId);
  const result = await fetch(getFunctionsUrl(environment, "basket-retrieve"), {
    method: "post",
    headers: new Headers({
      "Content-Type": "application/json",
    }),
    body: JSON.stringify({ data: { userId, basketId } }),
  })
    .then((r) => r.json())
    .then((r) => r.result);

  return result;
};
