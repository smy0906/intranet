import { combineReducers } from 'redux';
import rows from './rows';
import modal from './modal';

const tableApp = combineReducers({
  rows,
  modal
});

export default tableApp;
