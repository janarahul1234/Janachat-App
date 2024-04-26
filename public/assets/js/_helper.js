export const redirect = (url) => {
  window.location.replace(url);
};

export const isEmpty = (value) => {
  return typeof value == 'string' && !value.trim() || typeof value == 'undefined' || value === null;
};

let timeOut = null;

export const deBounce = (callback, delay) => {
  if (timeOut) clearTimeout(timeOut);
  timeOut = setTimeout(() => callback(), delay);
};

export const watcher = ({ url, method, payload }, target, callback) => {
  let options = {};
  let pervData = [];

  const event = setInterval(async () => {
    try {
      if (method === "POST") {
        options = {
          method: "POST",
          headers: { "Content-type": "application/json;charset=UTF-8" },
          body: JSON.stringify(payload),
        };
      } else {
        options = {
          method: "GET",
          headers: { "Content-type": "application/json;charset=UTF-8" },
        };
      }

      const response = await fetch(BASE_URL + url, options);

      if (!response.ok) {
        clearInterval(event);
        return console.warn(
          `status: ${response.status}, message: ${response.statusText}`
        );
      }

      const data = await response.json();

      if (data.status !== "success") {
        clearInterval(event);
        callback(data);
        return;
      }

      if (!data[target]) {
        clearInterval(event);
        console.warn("Error: Target not found!");
        return;
      }

      setTimeout(() => (pervData = data[target]), 100);

      if (pervData.length !== data[target].length) {
        callback(data[target]);
      }
    } catch (error) {
      clearInterval(event);
      return console.warn(`message: ${error}`);
    }
  }, 500);

  return event;
};
