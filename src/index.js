import './scss/main.scss';
import './js/main.js';

if (import.meta.hot) {
  import.meta.hot.accept(() => {
    console.log('HMR: Updated!');
  });
}