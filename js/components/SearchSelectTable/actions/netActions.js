export const postJson = (url, formData) => {
  console.log('fetch:', json);
    return fetch(url, {
      credentials: 'same-origin',
      method: 'POST',
      body: formData
    });
  // .then(
  //   data => {
  //     console.log(data);
  //   }
  // )
  // .catch(error => {
  //   console.log('postJson action error', error);
  // });
};
