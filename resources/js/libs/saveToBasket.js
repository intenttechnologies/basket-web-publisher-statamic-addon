export const getFunctionsUrl = (environment, path) =>
  `https://europe-west2-basket-${environment}.cloudfunctions.net/${path}`;

export const saveItems = async ({ environment, apiKey, urls, basketName }) => {
  console.log("saveItems", urls);
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

  console.log("saveItems result", result);

  return result;
};

export const getItems = async ({ environment, userId, basketId }) => {
  console.log("getItems", basketId);
  const result = await fetch(getFunctionsUrl(environment, "basket-retrieve"), {
    method: "post",
    headers: new Headers({
      "Content-Type": "application/json",
    }),
    body: JSON.stringify({ data: { userId, basketId } }),
  })
    .then((r) => r.json())
    .then((r) => r.result);

  console.log("getItems result", JSON.stringify(result, null, 2));

  return result;
};
