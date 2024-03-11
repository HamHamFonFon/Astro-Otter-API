export const jwtParser = (token: string) => {
  if (null === token) {
    return token;
  }
  const base64Url: string = token.split('.')[1];
  if (undefined === base64Url) {
    return null;
  }

  const base64: string = base64Url.replace('-', '+').replace('_', '/');
  const jsonPayload: string = decodeURIComponent(window.atob(base64).split('').map((c) => {
    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
  }).join(''));

  return JSON.parse(jsonPayload);
}
