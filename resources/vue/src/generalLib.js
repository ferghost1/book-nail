import axiosBase from 'axios';
import moment from 'moment';
import _ from 'lodash';

window.moment = moment;
// Put config here
const axios = axiosBase.create({
  baseURL: 'https://booknail.local/',
  timeout: 1000,
  responseType: 'json'
  timeout: 3000
});

window.axios = axios;
export {axios} 